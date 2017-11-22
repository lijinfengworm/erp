<?php
/**
 * 卡路里礼品卡息逻辑服务
 * About 梁天
 */
class KaluliLipinkaService  {

    private $error_flag = false;


    //每次最大生成数量
    private $max_count = 3000;
    //所有条目加起来的最大值
    private $all_max_count = 30000;

    private $form = array();  //存放  form 对象集合
    private $options = array();  //存放 option 数据集合

    private $_var = array();

    private $check = array();  //临时验证 数据
    private $bind = array();   //最终入库数据

    //表名
    private $lipinka_post = 'kaluli_lipinka';
    private $record_post = 'record';
    //
    const SENDCOUPONVIP='kll_send_coupon_vip';
    const SENDCOUPONORDER='kll_send_coupon_order';
    const SENDMSG='kll_send_msg';

    //优惠券使用范围
    public static $_scope = array(
        1=> '全场券',
        2=>'集合券'
    );    
    /**
     * 连接后台用户服务
     * @staticvar \Admin\Service\Cache $systemHandier
     */
    static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }

    /**
     * 添加 form
     */
    public function addForm($key,$val) {
        if(empty($this->form[$key])) {
            $this->form[$key] = $val;
        }
    }

    /**
     * 添加 form
     */
    public function addOption($key,$val) {
        if(empty($this->options[$key])) {
            $this->options[$key] = $val;
        }
    }




    /**
     * 更新优惠新闻
     */
    public function add($request) {

        //验证主字段是否有问题
        $this->create($request);
        /*  验证记录  */
        $type = $this->options['type'];
        if(empty($type)) throw new sfException('请选择申请类型！');
        $this->_add_record($request);

        if($this->error_flag) throw new sfException('有错误');

        $_new = $this->form['form']->save();
        //判断是否要修改状态
        if(isset($this->options['change_status'])) {
            $_new->setStatus($this->options['change_status']);
        }
        $_new->setAmount($this->getVar('amount'));
        if($this->getVar('IS_POSTPONE_BEFORE')) {
            $_new->setStime(strtotime($this->getVar('stime')));
            $_new->setEtime(strtotime($this->getVar('etime')));
        } else {
            $_new->setStime('');
            $_new->setEtime('');
        }
        $_new->save();
        $_id = $_new->getId();
        if(empty($_id)) throw new sfException('新增礼品卡失败！');
        $ret = $this->_bindSaveAttr($_id);
        if(!$ret){
            if(!isset($this->options['edit'])){
                $_new->delete();
            }
            throw new sfException('新增礼品卡失败！');
        }

        return $_id;
    }


    /**
     * @param $lipinka_id
     * @return bool
     * 创建数据
     */
    private function _bindSaveAttr($lipinka_id) {
        
        //先删除以前的
        KaluliLipinkaRecordTable::del_old($lipinka_id);
        //删除以前的大卡
        KaluliLipinkaLargeTable::del_old($lipinka_id);
        
        if(!empty($this->bind) && count($this->bind) > 0) {
            foreach($this->bind as $k=>$v) {
                $_num = isset($v['num']) ? $v['num'] : 1;
                $lipinkaRecord = new KaluliLipinkaRecord();
                $lipinkaRecord->setLipinkaId($lipinka_id);
                $lipinkaRecord->setPostponeType($v['postpone_type']);
                $lipinkaRecord->setPostponeDay($v['postpone_day']);
                $lipinkaRecord->setOverdueDay($v['overdue_day']);
                $lipinkaRecord->setIsLarge($v['is_large']);
                $lipinkaRecord->setType($this->options['type']);
                $lipinkaRecord->setStime(!empty($v['stime']) ? strtotime($v['stime']) : NULL);
                $lipinkaRecord->setEtime(!empty($v['etime']) ? strtotime($v['etime']) : NULL);
                $lipinkaRecord->setAmount((int)$_num * $v['amount']);
                $lipinkaRecord->setCardLimit($v['card_limit']);
                $lipinkaRecord->setNum($_num);
                $lipinkaRecord->setGroupId($v['group_id']);
                if($this->options['type'] == 2) {
                    $lipinkaRecord->setAcceptUids(json_encode($v['accept_uids']));
                }
                $lipinkaRecord->setIsSuccess(0);
                $lipinkaRecord->save();

                //判断是否生成大卡
                if(!empty($v['is_large'])) {
                    $lipinkaLarge = new KaluliLipinkaLarge();
                    $lipinkaLarge->setLipinkaId($lipinka_id);

                    $lipinkaLarge->setRecordId($lipinkaRecord->getId());
                    $card = trim($v['is_large_card']);
                    //不重复生成卡密
                    if(KaluliLipinkaLargeTable::isRepeat($card)){
                        return false;
                    }else{
                        $card = trim($v['is_large_card']);
                    }
                    
                    $lipinkaLarge->setCard($card);
                    $lipinkaLarge->setNum($_num);
                    $lipinkaLarge->setNoReceive($_num);
                    $lipinkaLarge->setPostponeType($v['postpone_type']);
                    $lipinkaLarge->setPostponeDay($v['postpone_day']);
                    $lipinkaLarge->setStime(!empty($v['stime']) ? strtotime($v['stime']) : NULL);
                    $lipinkaLarge->setEtime(!empty($v['etime']) ? strtotime($v['etime']) : NULL);
                    $lipinkaLarge->setStatus(1);
                    $lipinkaLarge->save();
                    //最后把大卡ID插入记录表
                    $lipinkaRecord->setLargeId($lipinkaLarge->getId());
                    $lipinkaRecord->save();
                }
            }

        }
        return true;
    }



    /*
     * 获取 var
     */
    public function  getVar($key) {
        if(isset($this->_var[$key])) return $this->_var[$key];
    }

    private function  setVar($key,$val) {
        if(empty($this->_var[$key])) {
            $this->_var[$key] = $val;
        }
        return true;
    }


    /**
     * 申请类型 字段拼装
     */
    private function  _add_record($request) {
        $recordData = $request->getParameter($this->record_post);

        if(empty($recordData)) return true;
        //设置数量
        $this->setVar('record_count',count($recordData['stime']));
        $_amount = 0;
        $_all_max = 0;
        $_stime = $_etime = array();
        $_IS_POSTPONE_BEFORE = 0;
        $max_num_error = $max_data_error = false;

        //循环遍历
        foreach($recordData['amount'] as $k=>$v) {
            $_max_num  = 0;
            //判断类型
            $name = "record[postpone_type][".$k."]";
            $this->form['recordForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['recordForm']->setValidator($name, new sfValidatorString(array( 'required' => true,'trim' => true), array( 'required' => '不得为空！')));
            $this->bind[$k]['postpone_type']  = $this->check[$name] = isset($recordData['postpone_type'][$k]) ? $recordData['postpone_type'][$k]:"";

            //开始时间
            $_stime_name = "record[stime][" . $k . "]";
            $this->form['recordForm']->setWidget($_stime_name, new sfWidgetFormFilterInput());
            $this->bind[$k]['stime'] = $this->check[$_stime_name] = isset($recordData['stime'][$k]) ? $recordData['stime'][$k] : "";

            //结束时间
            $_etime_name = "record[etime][" . $k . "]";
            $this->form['recordForm']->setWidget($_etime_name, new sfWidgetFormFilterInput());
            $this->bind[$k]['etime'] = $this->check[$_etime_name] = isset($recordData['etime'][$k]) ? $recordData['etime'][$k] : "";

            //动态天数
            $_postpone_day_name = "record[postpone_day][" . $k . "]";
            $this->form['recordForm']->setWidget($_postpone_day_name, new sfWidgetFormFilterInput());
            $this->bind[$k]['postpone_day'] = $this->check[$_postpone_day_name] = isset($recordData['postpone_day'][$k]) ? $recordData['postpone_day'][$k] : "";

            //到期天数
            $_overdue_day_name = "record[overdue_day][" . $k . "]";
            $this->form['recordForm']->setWidget($_overdue_day_name, new sfWidgetFormFilterInput());
            $this->bind[$k]['overdue_day'] = $this->check[$_overdue_day_name] = isset($recordData['overdue_day'][$k]) ? $recordData['overdue_day'][$k] : "";



            //判断开始时间和 结束时间
            if($this->bind[$k]['postpone_type'] == KaluliLipinkaRecord::$POSTPONE_BEFORE) {
                $_IS_POSTPONE_BEFORE = 1;
                $this->form['recordForm']->setValidator($_stime_name, new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '不得为空！')));
                $this->form['recordForm']->setValidator($_etime_name, new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '不得为空！')));
                //判断开始时间是否大于结束时间
                if ($recordData['stime'][$k] > $recordData['etime'][$k]) {
                    $this->form['recordForm']->setValidator($_etime_name, new sfValidatorRegex(array('pattern' => '/^gwyy$/'), array('invalid' => '开始时间不得大于结束时间！')));
                }
                $this->form['recordForm']->setValidator($_postpone_day_name, new sfValidatorinteger(array('required' => false), array()));
                $this->form['recordForm']->setValidator($_overdue_day_name, new sfValidatorinteger(array('required' => false), array()));
            } else {
                $this->form['recordForm']->setValidator($_stime_name, new sfValidatorString(array('required' => false), array()));
                $this->form['recordForm']->setValidator($_etime_name, new sfValidatorString(array('required' => false), array()));
                $this->form['recordForm']->setValidator($_postpone_day_name, new sfValidatorinteger(array('min'=>1,'max'=>6000,'required' => true, 'trim' => true), array('max'=>'最大6000天', 'min'=>'最小为1', 'required' => '不得为空！')));
                $this->form['recordForm']->setValidator($_overdue_day_name, new sfValidatorinteger(array('min'=>0,'max'=>6000,'required' => false, 'trim' => true), array('max'=>'最大6000天', 'min'=>'最小为1')));
            }

            $name = "record[amount][".$k."]";
            $this->form['recordForm']->setWidget($name,new sfWidgetFormFilterInput());
            $this->form['recordForm']->setValidator($name, new sfValidatorString(array( 'required' => true,'trim' => true), array( 'required' => '不得为空！')));
            $this->bind[$k]['amount']  = $this->check[$name] = isset($recordData['amount'][$k]) ? $recordData['amount'][$k] : "";


            //是否变身大卡
            $_is_large_name = "record[is_large][" . $k . "]";
            $_is_large_name_card = "record[is_large_card][" . $k . "]";
            $this->form['recordForm']->setWidget($_is_large_name, new sfWidgetFormFilterInput());
            
            
            $this->form['recordForm']->setValidator($_is_large_name, new sfValidatorString(array('required' => false), array()));
            $this->form['recordForm']->setWidget($_is_large_name_card, new sfWidgetFormFilterInput());
            $this->form['recordForm']->setValidator($_is_large_name_card, new sfValidatorString(array('required' => false), array()));
            $this->bind[$k]['is_large'] = $this->check[$_is_large_name] = isset($recordData['is_large'][$k]) ? $recordData['is_large'][$k] : "";
            $this->bind[$k]['is_large_card'] = $this->check[$_is_large_name_card] = isset($recordData['is_large_card'][$k]) ? $recordData['is_large_card'][$k] : "";

            if($this->options['type'] == KaluliLipinkaRecord::$TYPE_CARD) {
                $name = "record[num][" . $k . "]";
                $this->form['recordForm']->setWidget($name, new sfWidgetFormFilterInput());
                $this->form['recordForm']->setValidator($name, new sfValidatorNumber(array('required' => true, 'trim' => true,'min' => 1), array('required' => '不得为空！','min' => '数量不得为0！')));
                $this->bind[$k]['num'] = $this->check[$name] = isset($recordData['num'][$k]) ? $recordData['num'][$k] : "";
            } else {
                $name = "record[accept_uids][" . $k . "]";
                $this->form['recordForm']->setWidget($name, new sfWidgetFormFilterInput());
                $this->form['recordForm']->setValidator($name, new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '不得为空！')));
                $this->check[$name] = isset($recordData['accept_uids'][$k]) ? $recordData['accept_uids'][$k] : "";
                $this->bind[$k]['accept_uids'] = FunBase::textareaToArray(isset($recordData['accept_uids'][$k]) ? $recordData['accept_uids'][$k] : "",true);
                $this->bind[$k]['num'] = count($this->bind[$k]['accept_uids']) == 0 ? 1 : count($this->bind[$k]['accept_uids']);
                $_max_num += $this->bind[$k]['num'];// 计算总的数量
                $_all_max += $this->bind[$k]['num']; //所有列的总项
            }

            if($this->options['type'] == 1) {
                $_num = $recordData['num'][$k];
            } else {
                $_num = $this->bind[$k]['num'];
            }
            //增加amount
            if(!empty($recordData['amount'][$k])) $_amount += (int)$recordData['amount'][$k] * $_num;
            //增加时间
            if(!empty($recordData['stime'][$k])) $_stime[] = $recordData['stime'][$k];
            if(!empty($recordData['etime'][$k])) $_etime[] = $recordData['etime'][$k];

            //判断是不是数量超过了
            if($_max_num > $this->max_count) {
                $max_num_error = true;
            }
            //判断是不是 内容超过了
            if(!empty($this->bind[$k]['accept_uids'])) {
                if(mb_strlen(json_encode($this->bind[$k]['accept_uids']),'UTF8') > 60000) {
                    $max_data_error = true;
                }
            }




            //判断是否有限制条件
            $_card_limit = $recordData['card_limit'][$k];
            $_card_limit_name = "record[card_limit][" . $k . "]";
            $_parse_limit = "";
            //如果有限制条件 那么就解析限制条件
            if(count($_card_limit) > 0 ) $_parse_limit = $this->_parseCardLimit($_card_limit);
            //限制条件
            $this->form['recordForm']->setWidget($_card_limit_name, new sfWidgetFormFilterInput());
            $this->form['recordForm']->setValidator($_card_limit_name, new sfValidatorString(array('required' => false), array()));
            $this->bind[$k]['card_limit'] = $this->check[$_card_limit_name] = $_parse_limit;
            //判断使用范围
            $scope="record[scope][".$k."]";
            $group_id="record[group_id][".$k."]";   //集合id
            $this->form['recordForm']->setWidget($scope,new sfWidgetFormChoice(array('choices'=>  self::$_scope)));
//            $this->bind[$k]['scope']  =$this->check[$scope]= isset($recordData['scope'][$k]) ? $recordData['scope'][$k]:1;
//            $this->form['recordForm']->setValidator($scope, new sfValidatorString(array( 'required' => true,'trim' => true), array( 'required' => '不得为空！')));
            $this->form['recordForm']->setWidget($group_id,new sfWidgetFormFilterInput());
            $this->form['recordForm']->setValidator($group_id, new sfValidatorString(array( 'required' => false,'trim' => true),array()));
            $boo_group_id=false;
            if($recordData['scope'][$k]==2)   //集合券
            {
                $this->bind[$k]['group_id']  =$this->check[$group_id]= isset($recordData['group_id'][$k]) ? $recordData['group_id'][$k]:"";
                $check_group_id=$this->check_group_id($recordData['group_id'][$k]);
                if(!$recordData['group_id'][$k] || !$check_group_id) {
                    $boo_group_id=true;
                }                        
            }
            else
            {
                $this->bind[$k]['group_id']  =$this->check[$group_id]= '';
            }
        }  //foreach end
        sort($_stime);
        rsort($_etime);
        $this->setVar('amount',$_amount);

        if(!empty($_stime)) $this->setVar('stime',!empty($_stime[0]) ? $_stime[0] : '');

        if(!empty($_etime)) $this->setVar('etime',!empty($_etime[0]) ? $_etime[0]  : '');

        $this->setVar('IS_POSTPONE_BEFORE',$_IS_POSTPONE_BEFORE);

        $this->form['recordForm']->bind($this->check);
        //unset($this->form['recordForm']);
        if(!$this->form['recordForm']->isValid()) {
            
            $this->error_flag = true;
        }
        if($max_num_error) {
            throw new sfException('每次申请礼品卡最大数量不得超过'.$this->max_count.'张!');
        }
        if($max_data_error) {
            throw new sfException('接收账户总大小超过了最大值 65535 位！');
        }
        if($boo_group_id)
        {
            throw new sfException('集合id不存在 ');
        }
        /*
        if($_all_max > $this->all_max_count) {
            throw new sfException('当次所有的礼品卡最大数量不得超过'.$this->all_max_count.'张!');
        }*/
    }



    //解析限制规则
    public function _parseCardLimit($_card_arr) {
        $_parse = '';
        foreach($_card_arr as $k=>$v) {
            if($k == 'order_money' && !empty($v) && $v > 0) {
                $_parse .= 'order_money='.(int)$v.',';
            }
        }
        if(!empty($_parse)) return rtrim($_parse,',');
        return '';
    }


    //反解析限制规则
    public static function cardLimitToArr($card_limit) {
        if(empty($card_limit)) return '';
        $card_limit_arr = explode(',',$card_limit);
        if(empty($card_limit_arr) || count($card_limit_arr) < 1) return '';
        $_return_arr = array();
        foreach($card_limit_arr as $k=>$v) {
            $_tmp = explode('=',$v);
            $_return_arr[$_tmp[0]] = $_tmp[1];
        }
        return $_return_arr;
    }



    /*
    * 验证主字段
    */
    public function create($request) {
        $_post[$this->lipinka_post] = $request->getParameter($this->lipinka_post);
        /* 验证主字段  */
        $this->form['form']->bind($_post[$this->lipinka_post]);
        if(!$this->form['form']->isValid()) {
            $this->error_flag = true;
        }
    }
    public function commonCreate($request, $table){

        $_post = $request->getPostParameters($table);
        $this->form[$table.'Form']->bind($_post[$table]);
        if(!$this->form[$table.'Form']->isValid()) {
            $this->error_flag = true;
        }
    }  
    /**
     * 批次号对应的优惠券
     */
    private function checkCardNum($record_id)
    {
        $state=0;
        $now=time();
        $bind=[];
        $bind['record_id']='record_id='.$record_id;
        $bind['status']='status='.$state;
        $bind['stime']='stime <='.$now;
        $bind['etime']='etime >='.$now;
        $num= KaluliLipinkaCardTable::getInstance()->getAll(array('where'=>$bind,'select'=>'count(id) as num','limit'=>1,'is_count'=>1));
        return $num;
    }
    /**
     * 集合id是否存在
     */
    private function check_group_id($group_id)
    {
        $boo=  KllMarketingActivityGroupTable::getInstance()->findOneBy('id',$group_id);
        return $boo;
    }
    
    /**
     * 更新会员送券配置
     */
    public function addCouponVip($request)
    {
//        $this->commonCreate($request, self::SENDCOUPONVIP);
        
        $res = $this->form[self::SENDCOUPONVIP.'Form']->save();
        $record =$request->getPostParameters(self::SENDCOUPONVIP);
        $record_ids=$record[self::SENDCOUPONVIP]['record_id'];
        $record_id=(implode('|',$record_ids));
        $res->setRecordId($record_id)->save();
        $res->setOptUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())->save();
        $res->setSTime(strtotime($record[self::SENDCOUPONVIP]['s_time']))->save();
        $res->setETime(strtotime($record[self::SENDCOUPONVIP]['e_time']))->save();
        $res->save();
        if($res->getId() && $res->getState()==1)   //只允许启动一个配置
        {
            $this->setState($res->getId());
        }
        return $res->getId();
    }
    /**
     * 更新会员送券配置
     */
    public function addCouponOrder($request)
    {
        
        $res = $this->form[self::SENDCOUPONORDER.'Form']->save();
        $record =$request->getPostParameters(self::SENDCOUPONORDER);
        $record_ids=$record[self::SENDCOUPONORDER]['record_id'];
        $record_id=(implode('|',$record_ids));
        $res->setRecordId($record_id)->save();
        $res->setOptUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())->save();
        $res->setSTime(strtotime($record[self::SENDCOUPONORDER]['s_time']))->save();
        $res->setETime(strtotime($record[self::SENDCOUPONORDER]['e_time']))->save();
        $res->save();
        if($res->getId() && $res->getState()==1)   //只允许启动一个配置
        {
            $this->setStateByOrder($res->getId(),$res->getPosition(),$res->getChannelId(),$res->getType());
        }
        return $res->getId();
    }
    /**
     * 短信预警
     */
    public function addSiteMsg($request)
    {
        
        $res = $this->form[self::SENDMSG.'Form']->save();
        $record =$request->getPostParameters(self::SENDMSG);
        $res->setOptUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())->save();
        $res->setStime(time())->save();
        $res->save();
        return $res->getId();
    }
    
    /**
     * 保存送券配置时，把其他配置的状态改为“关闭”
     */
    private function setState($id)
    {
        $bind=[];
        $bind['id'] = 'id != '.$id;
        $bind['state'] = 'state = 1';
        $site=  KllSendCouponVipTable::getAll(array('where'=>$bind));
        if($site)
        {
            //todo 更新状态
            foreach($site as $k=>$v)
            {
                $obj=  KllSendCouponVipTable::getInstance()->findOneById($v['id']);
                $obj->setState(2)->save();
            }
            return true;
        }
        return false;
    }
    /**
     * 订单送券，把其他配置的状态改为“关闭”
     */
    private function setStateByOrder($id,$position,$channel_id,$type)
    {
        $bind=[];
        $bind['id'] = 'id != '.$id;
        $bind['state'] = 'state = 1';
        $bind['position'] = 'position ='.$position;
        $bind['channel_id'] = 'channel_id = '.$channel_id;
        if($type == '') {
            $bind['type'] = 'type = "" or type = null ';
        } else {
            $bind['type'] = " type = '".$type."'";
        }
        $site= KllSendCouponOrderTable::getAll(array('where'=>$bind));
        if($site)
        {
            //todo 更新状态
            foreach($site as $k=>$v)
            {
                $obj= KllSendCouponOrderTable::getInstance()->findOneById($v['id']);
                $obj->setState(2)->save();
            }
            return true;
        }
        return false;
    }











}