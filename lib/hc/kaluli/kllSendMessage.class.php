<?php

/**
 *  卡路里 消息推送
 */
class kllSendMessage
{
    
    private  $url="https://inform.hupu.com/sms.json";
    private  $smsClient;
    private  $white_list = [28989349,28880872,26963041,28879888,26473705];

//    public  static $_ORDER_REMIND = '亲 ，您刚刚在小店拍下的订单即将自动关闭了呦~请亲尽快付款~ ';
//    public static $_ORDER_OK = '亲，订单号{order_number}已付款，若有任何疑问，请联系客服：4000125118。';
//    public static $_ORDER_EXPRESS = '亲，您购买的宝贝已乘坐{express_name}出发，运单号{express_number}，请亲时时关注您的宝贝哦！';
//    public static $_ORDER_BACK = '亲 ，您的退款已经处理成功，非常抱歉没有给你带来良好的体验，期待下次给亲更好的体验。';
    public static $_ORDER_REMIND = 38;//'亲 ，您刚刚在小店拍下的订单即将自动关闭了呦~请亲尽快付款~ ';
    public static $_ORDER_OK = 37;//'亲，订单号{order_number}已付款，若有任何疑问，请联系客服：4000125118。';
    public static $_ORDER_EXPRESS =40;// '亲，您购买的宝贝已乘坐{express_name}出发，运单号{express_number}，请亲时时关注您的宝贝哦！';
    public static $_ORDER_BACK = 39;//'亲 ，您的退款已经处理成功，非常抱歉没有给你带来良好的体验，期待下次给亲更好的体验。';
    public static $_LIPINKA_REMIND = 41; //ID为:${id}的组合礼品卡数量已经少于你设置的警戒值${num}！请马上更换其他组合优惠券！
    public static $_LIPINKA_COUPON=42;  //【卡路里】${mobile},感谢您的购买,一份优惠券已塞入您的账户,点击"我的订单"查看,下次购买可用.客服:4000125118。
    public static $_LIPINKA_REMIND_2=43;  //【卡路里】${mobile}，您设置的订单发券活动剩余券数量不足50张，请尽快更新。
    public static $_MCODE_SEND = 45;//【卡路里】${mcode}(短信验证码)，请在十分钟内完成验证，您的手机号为${mobile}，为保障账号安全，请勿转发验证码给他人。
    public static $_ACTIVITY_COUPON = 47;//您申领的优惠券已塞入当前手机账户，使用当前手机号登录卡路里商城即可使用.客服:4000125118
    public static $_LINPINKA_SEND_ACTIVITY = 48; //【卡路里】您在双11活动中单笔订单满399元，价值150元的优惠券大礼包已塞入您的账户,可点击“个人中心-我的优惠券”查看。客服:4000125118
    public static $_NEW_USER_TASK_0 = 60; //亲，有${num}位肌友通过你的分享成功领取新人礼包，${couponFee}元无门槛优惠券轻松到手，快去访问公众号 “卡路里运动营养” 查看吧~ 回复TD退订
    public static $_NEW_USER_TASK_1 = 61; //亲，有${num}位肌友通过你的分享成功领取新人礼包，${couponFee}元优惠大礼包轻松到手，快去访问公众号 “卡路里运动营养” 查看吧~ 回复TD退订
    public static $_NEW_USER_TASK_2 = 62; //哇塞，有${num}位肌友通过你的分享下单啦，恭喜你免费获得${title}1份，快快联系公众号 “卡路里运动营养” 客服领取吧~ 回复TD退订
    public static $_NEW_USER_TASK_3 = 63; //哇塞，有${num}位肌友通过你的分享下单啦，恭喜你免费获得${title}1份，快快联系公众号 “卡路里运动营养” 客服领取大奖吧~ 回复TD退订
    //【卡路里】#code# 如非本人操作，请忽略本短信
    const _DEFAULT_TMP_ID = 37;


    public function __construct(){
        $this->smsClient = new kaluliJsonRPCClient($this->url);
    }

    /**
     * 模板接口发短信
     * apikey 为云片分配的apikey
     * tpl_id 为模板id
     *  'phone' => 15800867003,
     *  'var' => array('order_number'=>11112)
     *
     */
    //public function send($mobile,$tmp_text, $text ,$tpl_id = 1182325){
    public function send($args){
        //只有模板为45才进行同盾校验
        if($args['tpl_id'] == 45) {
            //增加云盾监控
            $form = new BaseForm();
            $data = array(
                "partner_code" => "hupu",
                "secret_key" => "5085086efc4747c2a99fd556dab9eeda",
                "event_id" => "SMS_web_20170306",
                "token_id" => $args['var']['token'],
                "account_mobile" => $args['phone'],
                "ip_address" => $_COOKIE['remoteIp']
            );
            kaluliLog::info("tongDunParam",$data);
            $result = $this->invoke_fraud_api($data);
            if ($result['success']) {
                if ($result['final_decision'] != "Reject") {
                    //假如手机号不存在返回
                    if (empty($args['phone'])) return false;
                    if (empty($args['tpl_id'])) $args['tpl_id'] = self::_DEFAULT_TMP_ID;
                    if (isset($args['user_id']) && !empty($args['user_id'])) {
                        if (in_array($args['user_id'], $this->white_list))
                            return false;
                    }
                    $param = json_encode($args['var']);//构造param
                    //生成token
                    $time = time();
                    $token = $this->tokenCreate($time);
                    //构造推送数据
                    $data = array(
                        'clientId' => $this->clientId,
                        'token' => $token,
                        "time" => $time,
                        'mobile' => $args['phone'],
                        'templateCode' => $args['tpl_id'],
                        'param' => $param
                    );

                    $result = $this->smsClient->send($data);
                    if ($result['status'] == 1) {
                        kaluliLog::info("sms_success", array("error_code" => $result['code'], "mobile" => $args['phone'], "tempalteCode" => $args['tpl_id']));
                    } else {
                        //记录发送错误日志
                        kaluliLog::info("sms_error", array("error_code" => $result['code'], "error_msg" => $result['msg']));
                    }
                    return $result;
                } else {
                    kaluliLog::info("tongdunResult", array("reason_code" => $result['final_decision']));
                }
            }
        } else {
            //假如手机号不存在返回
            if (empty($args['phone'])) return false;
            if (empty($args['tpl_id'])) $args['tpl_id'] = self::_DEFAULT_TMP_ID;
            if (isset($args['user_id']) && !empty($args['user_id'])) {
                if (in_array($args['user_id'], $this->white_list))
                    return false;
            }
            $param = json_encode($args['var']);//构造param
            //生成token
            $time = time();
            $token = $this->tokenCreate($time);
            //构造推送数据
            $data = array(
                'clientId' => $this->clientId,
                'token' => $token,
                "time" => $time,
                'mobile' => $args['phone'],
                'templateCode' => $args['tpl_id'],
                'param' => $param
            );

            $result = $this->smsClient->send($data);
            if ($result['status'] == 1) {
                kaluliLog::info("sms_success", array("error_code" => $result['code'], "mobile" => $args['phone'], "tempalteCode" => $args['tpl_id']));
            } else {
                //记录发送错误日志
                kaluliLog::info("sms_error", array("error_code" => $result['code'], "error_msg" => $result['msg']));
            }
            return $result;
        }
    }

    //目前规则 md5(md5($privateKey).$time)
    public function tokenCreate($param) {
        return md5(md5($this->apikey).$param);
    }

    /**
     * $params 请求参数
     * $timeout 超时时间
     * $connection_timeout 连接超时时间
     */
    function invoke_fraud_api(array $params, $timeout = 500, $connection_timeout = 500) {
        $api_url = "https://api.tongdun.cn/sms/protection/v1";

        $options = array(
            CURLOPT_POST => 1,            // 请求方式为POST
            CURLOPT_URL => $api_url,      // 请求URL
            CURLOPT_RETURNTRANSFER => 1,  // 获取请求结果
            // -----------请确保启用以下两行配置------------
            CURLOPT_SSL_VERIFYPEER => 1,  // 验证证书
            CURLOPT_SSL_VERIFYHOST => 2,  // 验证主机名
            // -----------否则会存在被窃听的风险------------
            CURLOPT_POSTFIELDS => http_build_query($params) // 注入接口参数
        );
        if (defined("CURLOPT_TIMEOUT_MS")) {
            $options[CURLOPT_NOSIGNAL] = 1;
            $options[CURLOPT_TIMEOUT_MS] = $timeout;
        } else {
            $options[CURLOPT_TIMEOUT] = ceil($timeout / 1000);
        }
        if (defined("CURLOPT_CONNECTTIMEOUT_MS")) {
            $options[CURLOPT_CONNECTTIMEOUT_MS] = $connection_timeout;
        } else {
            $options[CURLOPT_CONNECTTIMEOUT] = ceil($connection_timeout / 1000);
        }
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        if(!($response = curl_exec($ch))) {
            // 错误处理，按照同盾接口格式fake调用结果
            return array(
                "success" => false,
                "reason_code" => "000:调用API时发生错误[".curl_error($ch)."]"
            );
        }
        curl_close($ch);
        return json_decode($response, true);
    }


}