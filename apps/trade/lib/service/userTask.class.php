<?php
/*
*用户日常任务服务接口
*@author  韩晓林
*@date 2015/3/26
**/
class userTask
{
    private $hupuUid;
    private $currentDate;
    private $taskKey;         //redis key
    private $_oneTimeTaskStatus = array(//一次性任务
        'novice'=>0,
    );

    private $_taskStatus = array( /*任务=》状态*/
        'signIn'=>0,
        'haitaoSignIn'=>0,
        'praise'=>0,
        'haitaoComment'=>0,
        'finish'=>0,
    );

    private $_taskAction = array(/*task=》action*/
        'novice'=>'_novice',
        'signIn'=>'_taskSignIn',
        'haitaoSignIn'=>'_taskHaitaoSignIn',
        'praise'=>'_taskPraise',
        'haitaoComment'=>'_taskHaitaoComment',
        'finish'=>'_taskFinish',
    );

    public function __Construct($hupuUid){
        $this->currentDate = date('Y-m-d H:i:s');
        $this->hupuUid  = $hupuUid;
        $this->taskKey = "trade:index:task:info:".$hupuUid;
    }

    //获取任务状态
    public function task()
    {
        $userAccount = array();

        if($this->hupuUid){
            //用户信息
            $serviceRequest = new tradeServiceClient();
            $serviceRequest->setMethod('user.info.get');
            $serviceRequest->setVersion('1.0');
            $serviceRequest->setUserToken(sfContext::getInstance()->getRequest()->getCookie('u'));
            $response = $serviceRequest->execute();
            $responseData = $response->getData();
            $userAccount = $responseData['data']['user_info'];

            //task 信息
            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $data = unserialize($redis->get($this->taskKey));
            if(!$data){
                $userTask = TrdUserTaskTable::getTaskByUid($this->hupuUid);
                if(empty($userTask) || !$userTask->getOneTimeAttr()) {
                    if(empty($userTask)){
                        $userTask = new TrdUserTask();
                    }
                    $userTask->setHupuUid($this->hupuUid);
                    $userTask->setTaskAttr(serialize($this->_taskStatus));
                    $userTask->setOneTimeAttr(serialize($this->_oneTimeTaskStatus));
                    $userTask->setFinishedAt($this->currentDate);
                    $userTask->save();
                }

                $data = $this->checkTask($userTask);

                //前三个状态不写入缓存
                if(in_array($data['status'],array('praise','haitaoComment', 'finish'))){
                    $redis->set($this->taskKey, serialize($data), 10);
                }
            }
        }else{
            $data = $this->_taskNotLogin();
        }

        return array('data'=>$data,'userAccount'=>$userAccount);
    }

    //设置一次性任务
    public function setOneTime(array $arr){
        $userTask = TrdUserTaskTable::getTaskByUid($this->hupuUid);
        $oneTimeTask = (array)unserialize($userTask->getOneTimeAttr());

        foreach($arr as $v){
            if(isset($this->_oneTimeTaskStatus[$v])){
                $oneTimeTask[$v] = 1;
            }
        }

        try {
            $userTask->setOneTimeAttr(serialize($oneTimeTask));
            $userTask->save();
            return true;
        }catch(sfException $e) {
            return false;
        }
    }

    private function checkTask($userTask){
        $zeroTime = strtotime(date('Y-m-d'));

        $_taskStatus = unserialize($userTask->getTaskAttr());
        $_onetimeTaskStatus = unserialize($userTask->getOneTimeAttr());
        //最后完成时间零点初始化
        if(strtotime($userTask->getFinishedAt()) < $zeroTime){
            $userTask->setTaskAttr(serialize($this->_taskStatus));
            $userTask->setFinishedAt($this->currentDate);
            $userTask->save();

            $_taskStatus = $this->_taskStatus;
        }

        //一次性任务
        foreach($_onetimeTaskStatus as $k=>$v){
            if($v == 0){
                $action = $this->_taskAction[$k];
                $data = $this->$action($this->hupuUid);

                return $data;
            }
        }

        //日常任务
        foreach($_taskStatus as $k=>$v){
            //循环看哪个任务没有完成
            if($v == 0){
                $action = $this->_taskAction[$k];
                $data = $this->$action($this->hupuUid);

                //点赞数或海淘评论已完成 保存状态
                if(!$data){
                    $_taskStatus[$k] = 1;

                    $userTask->setTaskAttr(serialize($_taskStatus));
                    $userTask->setFinishedAt($this->currentDate);
                    $userTask->save();
                }else{
                    break;
                }
            }
        }

        return $data;
    }

    private function _taskNotLogin(){
        return array('status'=>'notLogin');
    }

    private function _novice(){
        return array('status'=>'novice');
    }

    private function _taskSignIn($hupuUid){
        $tag = TrdAccountHistoryTable::isSigninToday($hupuUid);
        if($tag){
            return false;
        }

        return array('status'=>'signIn');
    }

    private function _taskHaitaoSignIn($hupuUid){
        $tag = TrdAccountHistoryTable::isHaitaoSigninToday($hupuUid);
        if($tag){
            return false;
        }

        return array('status'=>'haitaoSignIn');
    }

    private function _taskPraise($hupu_uid){
        $stime = date('Y-m-d');
        $etime = date('Y-m-d',strtotime('+1 day'));

        $all =  10;                                                                                                          //总的
        $finish = trdAccountHistoryTable::getHistoryCountByUid($hupu_uid,array(5,6,7),array(),$stime,$etime,true);               //已点赞
        $finish = $finish > 10 ? $all : $finish;

        $steps = ($finish > 0) ? round(($finish/$all)*100,2) : 0;

        if($finish == $all){
            return false;
        }

        return array('all'=>$all,'finish'=>$finish,'steps'=>$steps,'status'=>'praise');
    }


    private function _taskHaitaoComment($hupu_uid){
        $all=  trdOrderTable::getInstance()->createQuery()->where('hupu_uid = ?',$hupu_uid)->andWhere('status = ?',2)->count();                               //总的
        $finish = trdOrderTable::getInstance()->createQuery()->where('hupu_uid = ?',$hupu_uid)->andWhere('status = ?',2)->andWhere('is_comment =?',1)->count();      //已回复
        $steps = (($finish) > 0 && ($all > 0 ))  ? round(($finish/$all)*100,2) : 0;

        if($finish == $all){
            return false;
        }

        return array('all'=>$all,'finish'=>$finish,'steps'=>$steps,'status'=>'haitaoComment');
    }

    private function _taskFinish($hupuUid){
        return array('status'=>'finish');
    }
}