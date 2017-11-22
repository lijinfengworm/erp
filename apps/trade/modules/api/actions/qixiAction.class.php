<?php
/*
 *七夕
 **/
Class qixiAction extends sfActions{
    private $_return;
    private $_request;
    private $_redis;
    private $_uid;
    private $_username;
    private $_jumpUrl;
    private $supporterIds = array(19430536, 19277613, 21162552, 19008037, 26264808, 19273649);
    private $deniedCommentUids = array( 16948059 );
    private $adminUids = array( 19449010, 19468341 ,19136811 ,17413824 ,15704205, 20874065 );
    private $key = 'trade:qixi2015:data:key';                                                    //全部数据临时缓存
    private $commentFlagKey = 'trade:qixi2015:comment:supporter:{supporter}:flag:key';           //评论标志ID
    private $commentKey = 'trade:qixi2015:comment:supporter:{supporter}:key';                    //评论集合
    private $commentRestrictKey = 'trade:qixi2015:comment:restrict:uid:{uid}:key';               //评论限制
    private $commentRestrictNum = 8;                                                             //评论分钟限制数
    private $supportUidsKey = 'trade:qixi2015:support:uids:{date}:key';                          //支持的Uid集合
    private $supportUserInfoKey = 'trade:qixi2015:support:{support}:userinfo:{uid}:{date}:key';  //支持的userinfo
    private $supportNumKey  = 'trade:qixi2015:support:{support}:num:key';                        //支持者支持数
    private $goodsInfoKey  = 'trade:qixi2015:goodsInofo4:{supporter}:key';                        //商品缓存
    private $supportErrorMsg = array(
        '对不起，您已经支持过了，明天继续哦。',
        '男人要专一，每天只能领一次哦。',
        '您已经领取过了，快拉朋友来支持我吧。',
        '每天只能领一次，先把兜里的券花掉啦！',
        '您已经领取过啦，请明天再来！',
    );
    private $supportSuccessMsg = array(
        '感谢支持哟，么么哒！',
        '报告大人！10元现金券奉上！',
        '真开心，下次请你吃辣条！',
        'Good！来人，为本女王打赏！',
        '喏，拿去花吧！记得给我买礼物！',
        '感谢您的支持，我感觉自己萌萌哒！',
        '茫茫人海中相遇！你支持了我，我赠券给你！',
        '感恩的心，感谢有你的支持！',
        '棒棒哒！你做了一个令人感动的选择！',
        '你饿不饿？要不要我为你煮碗面感谢你？',
    );
    private $activityTime = array(
        'startTime'=> '2015-08-18 00:00:00',
        'endTime'  => '2015-08-25 23:59:59',
    );
    private $codeToMsg = array(
        200 => '请求成功.',
        401 => '参数错误.',
        402 => '内容不能为空.',
        403 => '评论太频繁,请稍后再试.',
        404 => '活动已结束.',
        405 => '优惠券领取失败',
        406 => '你已被禁止评论',
        407 => '内容字数大于200.',
        410 => '支持成功',
        411 => '支持失败',
        501 => '请先登录',
    );
    private $supporters = array(
        array(
            'id'=> 19430536,
            'name'=>'虎扑安妮',
            'goodsIds'=>array(1348, 44231, 3554, 40367, 3554, 97132),
            'goodsInfo'=>array(),
            'support'=>0,
            'isSupport'=>false,
            'comment'=>array(),
        ),
        array(
            'id'=> 19008037,
            'name'=>'陈美男呐',
            'goodsIds'=>array(32709, 68316, 7959,  60524, 3222, 45600),
            'goodsInfo'=>array(),
            'support'=>0,
            'isSupport'=>false,
            'comment'=>array(),
        ),
        array(
            'id'=> 21162552,
            'name'=>'jocelyn15',
            'goodsIds'=>array(44091, 30577, 21679, 30397, 98077, 78084),
            'goodsInfo'=>array(),
            'support'=>0,
            'isSupport'=>false,
            'comment'=>array(),
        ),
        array(
            'id'=> 26264808,
            'name'=>'KissMeyoki',
            'goodsIds'=>array(21270, 4846, 113349, 25977, 23751, 113158),
            'goodsInfo'=>array(),
            'support'=>0,
            'isSupport'=>false,
            'comment'=>array(),
        ),
        array(
            'id'=> 19277613,
            'name'=>'萌萌呆子',
            'goodsIds'=>array(21913, 30763, 111541, 3586 ,30763, 117513),
            'goodsInfo'=>array(),
            'support'=>0,
            'isSupport'=>false,
            'comment'=>array(),
        ),
        array(
            'id'=> 19273649,
            'name'=>'甜Melody',
            'goodsIds'=>array(9178, 5867, 27243, 50458 ,73853 ,27624),
            'goodsInfo'=>array(),
            'support'=>0,
            'isSupport'=>false,
            'comment'=>array(),
        )
    );
    private $activityType = 2;                                                                //活动类型
    public function preExecute(){
        parent::preExecute();

        $this->_uid      = $this->getUser()->getAttribute('uid');                           //当前操作用户id
        $this->_username = $this->getUser()->getAttribute('username');
        $this->_redis    = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $this->_redis->select(6);
    }

    public function executeQixi(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', false);
        header("Content-type:text/html;charset=utf-8");
        $this->_request = $request;

        //验证客户端
        $this->_ismobile();
        //活动时间
        $date = date('Y-m-d H:i:s');
        if($date <= $this->activityTime['startTime'] || $date >= $this->activityTime['endTime']){
            if(!in_array($this->_uid, $this->adminUids)){
                if($request->isXmlHttpRequest()){
                    return $this->_returnJson( 404 );
                }else{
                    $this->_return['status'] = 404;
                    $this->_return['msg']    = '活动已结束';
                }
            }
        }

        //action
        $act = $request->getParameter('act');
        if(method_exists( $this, $act )){
            if($request->isXmlHttpRequest()){
                return $this->$act();
            }else{
                return $this->$act();exit;
            }
        }

        //取数据
        $persistenceRedis = sfContext::getInstance()->getDatabaseConnection('tradePersistenceRedis');
        foreach($this->supporters as $supporterId=>$supporterVal){
            //取支持者最新的60条评论
            $commentKey = $this->_keyFormat('{supporter}', $supporterVal['id'], $this->commentKey);
            $commentJson = $this->_redis->zrevrangebyscore ($commentKey, '+inf', '-inf',array(
                    'limit' => array(0, 60)
                )
            );
            if($commentJson){
                foreach($commentJson as $commentJsonValKey => $commentJsonVal){
                    $this->supporters[$supporterId]['comment'][$commentJsonValKey] = json_decode($commentJsonVal ,true);
                }
            }

            //支持数
            $date = date('Ymd');
            $supportNumKey = $this->_keyFormat('{support}', $supporterVal['id'] , $this->supportNumKey);
            $this->supporters[$supporterId]['support'] = (int)$persistenceRedis->get($supportNumKey);

            //是否支持过
            if($this->_uid){
                $supporterUsInfoKey = $this->_keyFormat(array('{date}', '{support}','{uid}'), array($date, $supporterVal['id'] ,$this->_uid) , $this->supportUserInfoKey);
                $userinfo = $this->_redis->hget($supporterUsInfoKey, 'uid');
                if($userinfo){
                    $this->supporters[$supporterId]['isSupport'] = true;
                }
            }

            //商品
            $goodsInfoKey = $this->_keyFormat('{supporter}', $supporterVal['id'] , $this->goodsInfoKey);
            $goodsInfo = unserialize($this->_redis->get($goodsInfoKey));
            if(!$goodsInfo){
                $goodsInfo = trdProductAttrTable::getMessage(array(
                    'select'=>'id,price,img_path,title,goods_id',
                    'ids'=> $this->supporters[$supporterId]['goodsIds'],
                    'arr'=>true
                ));
                $this->_redis->set($goodsInfoKey, serialize($goodsInfo), 60*10);
            }
            $this->supporters[$supporterId]['goodsInfo'] = $goodsInfo;
        }

        $this->_return['data']  = $this->supporters;
        if( empty($this->_return['status']) ) $this->_return['status'] = 200;
        $this->res = $this->_return;
    }


    /*
    *ajax 评论
    **/
    private  function ajaxComment(){
        //验证
        if(!$this->_uid){
            return  $this->_returnJson( 501 ,null, array('jumpUrl'=>$this->_jumpUrl) );
        }

        $supporterId = $this->_request->getParameter('sid');
        $content     = $this->_request->getParameter('content');
        if(!$supporterId || !in_array($supporterId, $this->supporterIds)){
            return  $this->_returnJson( 401 );
        }

        $content = strip_tags($content);
        $content = FunBase::clearUrl($content);
        if(!$content){
            return  $this->_returnJson( 402 );
        }elseif(utf8_strlen($content) > 200){
            return  $this->_returnJson( 407 );
        }

        //评论限制
        $commentRestrictKey = $this->_keyFormat('{uid}', $this->_uid, $this->commentRestrictKey);
        $commentRestrictNum = (int)$this->_redis->get($commentRestrictKey);
        if($commentRestrictNum > $this->commentRestrictNum){
            return  $this->_returnJson( 403 );
        }

        //评论禁止
        if(in_array($this->_uid, $this->deniedCommentUids)){
            return  $this->_returnJson( 406 );
        }

        //保存
        $comment = array(
            'uid' => $this->_uid,
            'username'=> $this->_username,
            'content' => $content,
            'time'  => time(),
        );

        $commentFlagKey = $this->_keyFormat('{supporter}', $supporterId, $this->commentFlagKey);
        $commentKey = $this->_keyFormat('{supporter}', $supporterId, $this->commentKey);

        $commentFlag = (int)$this->_redis->get($commentFlagKey);
        $this->_redis->zadd($commentKey, $commentFlag, json_encode($comment));
        $this->_redis->incr($commentFlagKey);
        $this->_redis->set($commentRestrictKey, ++$commentRestrictNum, 60);

        return $this->_returnJson( 200 );
    }

    /*
    *ajax 支持
    **/
    private  function ajaxSupport(){
        //验证
        if(!$this->_uid){
            return  $this->_returnJson( 501 ,null, array('jumpUrl'=>$this->_jumpUrl) );
        }

        $supporterId = $this->_request->getParameter('sid');
        if(!$supporterId || !in_array($supporterId, $this->supporterIds)){
            return  $this->_returnJson( 401 );
        }

        //支持
        $date = date('Ymd');
        $supportNumKey      = $this->_keyFormat('{support}', $supporterId , $this->supportNumKey);
        $supportUidsKey     = $this->_keyFormat(array('{date}'), array($date) , $this->supportUidsKey);
        $supporterUsInfoKey = $this->_keyFormat(
            array('{date}', '{support}','{uid}'),
            array($date, $supporterId ,$this->_uid) ,
            $this->supportUserInfoKey
        );

        $isSuppor = $this->_redis->sismember($supportUidsKey, $this->_uid);
        if($isSuppor){
            return  $this->_returnJson( 411 );
        }else{
            $this->_redis->sadd($supportUidsKey, $this->_uid);
            $this->_redis->hmset($supporterUsInfoKey, array(
                'uid'=> $this->_uid,
                'username'=> $this->_username,
                'time'  => time(),
            ));
            $supportNum = $this->_redis->incr($supportNumKey);

            //持久化
            $persistenceRedis = sfContext::getInstance()->getDatabaseConnection('tradePersistenceRedis');
            $persistenceRedis->set($supportNumKey, $supportNum);
        }

        //领取券接口
        $serviceRequest = new tradeServiceClient();
        $serviceRequest->setMethod('user.activity.noviciate.get');
        $serviceRequest->setApiParam('type', $this->activityType);
        $serviceRequest->setVersion('1.0');
        $serviceRequest->setUserToken($this->_request->getCookie('u'));

        $response = $serviceRequest->execute();
        $res = $response->getData();
        if($res['status'] != 200){
            return  $this->_returnJson( 405 ,$res['msg'] ,array('num'=> $supportNum));
        }

        return  $this->_returnJson( 410 , false, array('num'=> $supportNum));
    }

    /*
     * 发表评论
     *
     * */
    private  function sendComment(){
        $supporterId = $this->_request->getParameter('sid');
        $content     = $this->_request->getParameter('content');
        $uid         = $this->_request->getParameter('uid', -1);
        $username    = $this->_request->getParameter('username');

        if(!$supporterId || !$content || !$username){
            return  $this->_returnJson( 401 );
        }
        //保存
        $comment = array(
            'uid' => $uid,
            'username'=> $username,
            'content' => $content,
            'time'  => time(),
        );

        $commentFlagKey = $this->_keyFormat('{supporter}', $supporterId, $this->commentFlagKey);
        $commentKey = $this->_keyFormat('{supporter}', $supporterId, $this->commentKey);

        $commentFlag = (int)$this->_redis->get($commentFlagKey);
        $this->_redis->zadd($commentKey, $commentFlag, json_encode($comment));
        $this->_redis->incr($commentFlagKey);

        return $this->_returnJson( 200 );
    }

    /*
     * 删除某用户的评论
    */
    private  function delCommentByUsername(){
        if(!in_array($this->_uid, $this->adminUids))
            return  $this->_returnJson( 401 );

        $delUsername = $this->_request->getParameter('username');
        if(!$delUsername)
            return  $this->_returnJson( 401 );

        $delNum = 0;
        foreach($this->supporters as $supporterId=>$supporterVal){
            $commentKey = $this->_keyFormat('{supporter}', $supporterVal['id'], $this->commentKey);

            $comment = $this->_redis->zrange($commentKey, 0, -1);
            foreach($comment as $comment_v_json){
                $comment_v = json_decode($comment_v_json, true);
                if($comment_v['username'] == $delUsername){
                    $this->_redis->zrem($commentKey, $comment_v_json);
                    $delNum++;
                }
            }
        }

        return $this->_returnJson( 200, '删除成功', array('username'=>$delUsername, 'delNum'=>$delNum) );
    }

    /*
     *修改支持数
     **/
    private function editSupport(){
        if(!in_array($this->_uid, $this->adminUids))
            return  $this->_returnJson( 401 );

        $supporterId = $this->_request->getParameter('sid');
        if(!$supporterId || !in_array($supporterId, $this->supporterIds)){
            return  $this->_returnJson( 401 );
        }

        $supporterNum = $this->_request->getParameter('num');
        if(!$supporterNum || !is_numeric($supporterNum)){
            return  $this->_returnJson( 401 );
        }

        //修改支持
        $supportNumKey  = $this->_keyFormat('{support}', $supporterId , $this->supportNumKey);
        $this->_redis->set($supportNumKey, $supporterNum);

        $persistenceRedis = sfContext::getInstance()->getDatabaseConnection('tradePersistenceRedis');
        $persistenceRedis->set($supportNumKey, $supporterNum);

        return $this->_returnJson( 200, '修改成功', array('id'=>$supporterId, 'num'=>$supporterNum) );

    }

    private function _ismobile(){
        sfProjectConfiguration::getActive()->loadHelpers('common');
        if (is_mobile()){
            if(!$this->_request->isXmlHttpRequest()){
                $this->setLayout(false);
                $this->setTemplate('mobileQixi');
            }

            $this->_jumpUrl =
                'http://passport.hupu.com/m/2?from=m%26project=shihuo%26appid=10017http%3A%2F%2Fwww.shihuo.cn%2Fhaitao%2Fqixi%26jumpurl=http%3A%2F%2Fwww.shihuo.cn%2Fhaitao%2Fxinshoudali';
        }else{
            $this->_jumpUrl =
                'http://passport.shihuo.cn/login?project=shihuo%26from=pc%26jumpurl=http%3A%2F%2Fwww.shihuo.cn%2Fhaitao%2Fqixi';
        }
    }

    private function _returnJson($status = 200, $msg = '', array $data = array()){
        $return =  array('status'=>$status, 'data'=>$data);
        if($msg){
            $return['msg'] = $msg;
        }else{
            $return['msg'] = $this->_codeToMsg($status);
        }
        return $this->renderText(json_encode(
                $return
           )
        );
    }

    private function _keyFormat($flag, $replace, $key) {
        return str_replace($flag, $replace, $key);
    }

    private function _codeToMsg($code){
        if($code == 410){
            $msg = $this->supportSuccessMsg[array_rand($this->supportSuccessMsg)];
        }else if($code == 411){
            $msg = $this->supportErrorMsg[array_rand($this->supportErrorMsg)];
        }else{
            $msg = $this->codeToMsg[$code];
        }

        return $msg;
    }
}