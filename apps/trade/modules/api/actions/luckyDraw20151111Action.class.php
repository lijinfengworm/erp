<?php
/*
*2015 双11 大转盘抽奖
*@author 韩晓林
*@date  2015/10/22
**/
class luckyDraw20151111Action extends sfAction{
    private $user_lottery_num_key    = "trade:20151111:lottery:num:";
    private $user_lottery_share_key  = "trade:20151111:lottery:share:";
    private $user_ip_num_key         = "trade:20151111:ip:num:";         //ip请求次数
    private $user_lottery_coupon_key = "trade:20151111:lottery:coupon";  //店铺优惠券
    private $init_lottery_num = 5;   //初始抽奖次数
    private $redis;
    private $returnJson;             //返回的json
    private $expire_time;            //过期时间
    private $activity_time = array(  //活动时间
      'start_time' => '2015-10-30 00:00:00',
      'end_time'   => '2015-11-11 23:59:59'
    );
    private $hongbao_link;
    public function preExecute(){
        parent::preExecute();
        sfConfig::set('sf_web_debug', false);
        header('Access-Control-Allow-Origin: http://m.shihuo.cn');
        header('Access-Control-Allow-Credentials: true');

        //线下测试
        if(sfConfig::get('sf_environment') == 'dev') {
            $this->activity_time = array(
                'start_time' => '2015-10-20 00:00:00',
                'end_time'   => '2015-11-11 23:59:59'
            );
        }

        $this->redis    = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $this->redis->select(6);
    }
    public function execute($request) {
        $act            = $request->getParameter('act');
        $dacevid        = $request->getParameter('dacevid');
        $dacevid_cookie = isset($_COOKIE['_dacevid3']) ? $_COOKIE['_dacevid3'] :  (isset($_COOKIE['__dacevid3']) ? $_COOKIE['__dacevid3'] : '');

        $this->user_lottery_num_key   .= $dacevid ? $dacevid : $dacevid_cookie;
        $this->user_lottery_share_key .= $dacevid ? $dacevid : $dacevid_cookie;
        $this->user_ip_num_key        .= FunBase::get_client_ip();
        $this->expire_time = strtotime(date('Y-m-d', strtotime('+1 day'))) - time();
        $action = '_'.$act;
        if(method_exists( $this, $action )){
            if($request->isXmlHttpRequest() || $dacevid){
                $date = date('Y-m-d H:i:s');
                $user_ip_num = $this->redis->get($this->user_ip_num_key);

                if($date < $this->activity_time['start_time'] || $date > $this->activity_time['end_time']){ //活动时间限制
                    $this->_return('活动尚未开放.');
                }elseif((int)$user_ip_num >= 500){ //ip访问次数限制
                    $this->_return('请求太频繁，请稍后再试.');
                }else{
                    $this->$action();

                    if(!$user_ip_num){
                        $this->redis->set($this->user_ip_num_key, 1, 3600); //一小时
                    }else{
                        $this->redis->incr($this->user_ip_num_key);
                    }
                }

                return $this->renderText(json_encode($this->returnJson));
            }else{
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>-__-！非法请求"; exit;
            }
        }
    }


    //抽奖
    private function _luckyDraw(){
        sfProjectConfiguration::getActive()->loadHelpers('common');

        $today_lottery_num       = $this->redis->get($this->user_lottery_num_key);
        $today_lottery_share_num = $this->redis->get($this->user_lottery_share_key);
        if(false === $today_lottery_num){
            $today_lottery_num = $this->init_lottery_num;
            $this->redis->set($this->user_lottery_num_key, $today_lottery_num, $this->expire_time);
        }

        //红包地址
        if(is_mobile()){
            if(FunBase::checkBrowser()['name'] == 'app'
                || strpos($_SERVER['HTTP_USER_AGENT'], 'kanqiu') !== false
                || strpos($_SERVER['HTTP_USER_AGENT'], 'shihuo') !== false
            ){
                $this->hongbao_link =  'http://s.click.taobao.com/qzJA1nx';
            }else{
                $this->hongbao_link =  'taobao://hbao.tmall.com/h5?refpid=mm_31576222_4300201_40086581&eh=hag1Fj9iCEdNUyfOh2k%2BHrPxrLXXj3YcEDtF9eowng7BcP%2BCgIfvObXos%2BTOJF0nnJalO1%2BMdsL1xBQqYNwtVvtky2d2cFlT&ali_trackid=2:mm_31576222_4300201_40086581:1446018103_254_1789784320';
            }
        }else{
            $this->hongbao_link =  'http://s.click.taobao.com/9rDA1nx';
        }

        //pc 第一次无奖品，第二次中红包 m,app第一次中红包 ，
        //其余次数pc m app 二分之一几率中优惠券或者无奖品
        if( $today_lottery_num <= 0 ){
             $this->_return('你的抽奖次数已用完，请明天再来吧.');
        }elseif(5 == $today_lottery_num){
            if(false === $today_lottery_share_num){
                if(is_mobile()){
                    $this->_return(2);
                }else{
                    $this->_return(1);
                }
            }else{
                $rand_num = (mt_rand(1,1000) % 2);
                if(0 == $rand_num){
                    $this->_return(1);
                }else{
                    $this->_return(3);
                }
            }
        }elseif(4 == $today_lottery_num){
            if(false === $today_lottery_share_num){
                if(is_mobile()){
                    $rand_num = (mt_rand(1,1000) % 2);
                    if(0 == $rand_num){
                        $this->_return(1);
                    }else{
                        $this->_return(3);
                    }
                }else{
                    $this->_return(2);
                }
            }else{
                $rand_num = (mt_rand(1,1000) % 2);
                if(0 == $rand_num){
                    $this->_return(1);
                }else{
                    $this->_return(3);
                }
            }
        }elseif(10 == $today_lottery_num){
            if(is_mobile()){
                $this->_return(2);
            }else{
                $this->_return(1);
            }
        }elseif(9 == $today_lottery_num){
            if(is_mobile()){
                $rand_num = (mt_rand(1,1000) % 2);
                if(0 == $rand_num){
                    $this->_return(1);
                }else{
                    $this->_return(3);
                }
            }else{
                $this->_return(2);
            }
        }else{
            $rand_num = (mt_rand(1,1000) % 2);
            if(0 == $rand_num){
                $this->_return(1);
            }else{
                $this->_return(3);
            }
        }

        //减次数
        if($this->returnJson['status'])
            $this->redis->decr($this->user_lottery_num_key);
    }

    //分享加抽奖次数
    private function _share(){
        $today_lottery_num       = $this->redis->get($this->user_lottery_num_key);
        $today_lottery_share_num = $this->redis->get($this->user_lottery_share_key);

        if(false !== $today_lottery_share_num){
             $this->_return('今天的分享次数已用完，请明天再来吧.');
        }else{
            $this->redis->incrby($this->user_lottery_num_key, 5);
            $this->redis->set($this->user_lottery_share_key, 1, $this->expire_time);

            $this->returnJson = array(
                    'status' => true,
                    'msg'   => '额外增加5次分享',
                    'data'=>array('num' => $today_lottery_num + 5)
            );
        }
    }

    //返回数据
    private function _return($code){
        switch($code){
            case 1://无奖
                $this->returnJson = array(
                        'status' => true,
                        'msg'   => '成功',
                        'data'  => array('title' => '嘿咻嘿咻，再来一次.', 'link' => '', "code" => $code)
                    );
                break;
            case 2://红包
                $this->returnJson = array(
                        'status' => true,
                        'msg'   => '成功',
                        'data'  => array('title' => '点击领取双11现金红包', 'link' => $this->hongbao_link, "code" => $code)
                    );
                break;
            case 3://优惠券
                $user_lottery_coupon = $this->redis->srandmember ($this->user_lottery_coupon_key);
                $user_lottery_coupon  = json_decode($user_lottery_coupon, true);

                $this->returnJson = array(
                        'status' => true,
                        'msg'   => '成功',
                        'data'  => array(
                            'title' => "恭喜你获得了{$user_lottery_coupon['name']}{$user_lottery_coupon['amount']}",
                            'link'  => $user_lottery_coupon['link'],
                            "code"  => $code
                        )
                    );
                break;
            default:
                $this->returnJson = array(
                        'status' => false,
                        'msg'   =>  $code,
                );
        }
    }


}