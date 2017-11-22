<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeTrackingNotificationsTask extends sfBaseTask
{
    public $killtag =  true;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'TrackingNotifications';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:TrackingNotifications|INFO] task does things.
Call it with:
  通过第三方接口 订阅快递动态
  [php symfony trade:TrackingNotifications|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);

        ini_set('memory_limit','128M');

        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $channel->queue_declare('order.track.notifications', false, true, false, false, false);
        $channel->queue_bind('order.track.notifications', "amq.topic","shihuo.order.detail");
        $channel->basic_consume('order.track.notifications', '', false, false, false, false, 'tradeTrackingNotificationsTask::callback');

        while(count($channel->callbacks) ) {
            $nowmem = memory_get_usage()/1024/1024;
            if($nowmem <60){
                $channel->wait();
            }else{
                break;
            }
        }
        //9612804733974286541568
//        $lists = TrdOrderTable::getInstance()->createQuery()->select('id,mart_express_number')->where('id > 75748')->andwhere('mart_express_number is not null')->limit(1000)->fetchArray();
//        foreach($lists as $express)
//        {
//            $express_number = $express['mart_express_number'];
//            $message = array(
//                'id' => $express['id'],
//                'mart_express_number'=>$express_number,
//                'type' => 'mart_express_number_update',
//            );
//            $this->log($message);
//            $this->sendMqMessage($message);
//            if(preg_match('/1Z[A-Z0-9]{16}/i',$express_number))
//            {
//                $type = 'ups';
////                $result = self::trackUps($express_number);
//            }elseif(preg_match('/96[0-9]{20}/i',$express_number)){
//                $type = 'fedex';
////                $result = self::trackFedEx($express_number);
//            }elseif(preg_match('/9[34][0-9]{20}/i',$express_number)){
//                $type = 'usps';
////                $result = self::trackUsps($express_number);
//            }elseif(preg_match('/C[0-9]{14}/i',$express_number)){
//                $type = 'ontrac';
////                $result = self::trackOntrac($express_number);
//            }elseif(preg_match('/[0-9]{12}/i',$express_number)){
//                $type = 'fedex';
////                $result = self::trackFedEx($express_number);
//            }else{
//                echo ('找不到对应包裹服务商'.$express_number);
//            }
//            echo ($type);
//        }
        //$express_number = '1ZA86Y270214455239';
        //self::trackUps('1ZA86Y270214455239');
        //self::trackFedEx('586920833282');
        //self::trackFedEx('9612804733974286542015');
        //self::trackUsps('9400116901512691978577');
        //self::trackUsps('9374889877966111954161');
        //self::trackUsps('9361289877941110095286')
        //self::trackOntrac('C11000395644178');

    }
    public function sendMqMessage($message)
    {
        $amqpParams = sfConfig::get("app_mabbitmq_options_shihuo");
        $connection = new AMQPConnection($amqpParams['params']['host'], $amqpParams['params']['port'],$amqpParams['params']['user'], $amqpParams['params']['pass'], $amqpParams['params']['vhost']);
        $channel = $connection->channel();
        $arguments = array(
            "x-dead-letter-exchange" => array("S", "amq.topic"),
            "x-message-ttl" => array("I", 2000),
            "x-dead-letter-routing-key" => array("S", "shihuo.order.detail")
        );
        $channel->queue_declare('trd_order_deferred_2', false, true, false, false, false, $arguments);
        $msg = new AMQPMessage(json_encode($message));
        $channel->basic_publish($msg, '', 'trd_order_deferred_2');
    }
    public static function callback($msg)
    {
        $msgBody = json_decode($msg->body, true);
        $express_number = $msgBody['mart_express_number'];
        $type = $msgBody['type'];
        if($type == 'mart_express_number_update')
        {
            if(preg_match('/1Z[A-Z0-9]{16}/i',$express_number))
            {
                $type = 'ups';
                $result = self::trackUps($express_number);
            }elseif(preg_match('/96[0-9]{20}/i',$express_number)){
                $type = 'fedex';
                $result = self::trackFedEx($express_number);
            }elseif(preg_match('/9[34][0-9]{20}/i',$express_number)){
                $type = 'usps';
                $result = self::trackUsps($express_number);
            }elseif(preg_match('/C[0-9]{14}/i',$express_number)){
                $type = 'ontrac';
                $result = self::trackOntrac($express_number);
            }elseif(preg_match('/[0-9]{12}/i',$express_number)){
                $type = 'fedex';
                $result = self::trackFedEx($express_number);
            }else{
                $result = '';
                $type = '';
                echo ('找不到对应包裹服务商'.$express_number);
            }
            echo ($type.' '.$express_number.' '.$result);
        }
        $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag']);
    }
    public static function trackFedEx($number)
    {
        $resultText = tradeCommon::getContents('https://www.fedex.com/trackingCal/track',array('action'=>'sendNotification','locale'=>'en_US','version'=>'1','format'=>'json','data'=>'{"SendNotificationRequest":{"appType":"WTRK","uniqueKey":"","notificationList":[{"emailAddress":"hc_track@qq.com","format":"HTML","locale":"en_US","isEmailResult":true,"notifyOnDelivery":true,"notifyOnException":true,"notifyOnTendered":true}],"processingParameters":{},"trackingCarrier":"FDXG","trackingNbr":"'.$number.'","trackingQualifier":"","senderEMailAddress":"","senderContactName":"wangpeng","personalMessage":"","isTermsConditionsAccepted":true}}'));
        $resultJson = json_decode($resultText,1);
        if($resultJson && $resultJson['SendNotificationResponse']['successful'] == true)
        {
            return true;
        }
        return false;
    }

    public static function trackUps($number)
    {
        $cookieFile = sfConfig::get('sf_root_dir').'/cache/ups.cookie';
        $ch = curl_init('http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums='.$number.'&loc=zh_CN_us');
        curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $resultText = curl_exec ($ch);
        preg_match('/<form name="statusUpdatesform" action="\/WebTracking\/status" method="post" id="statusUpdatesformid">([\s|\S]*?)<\/form>/',$resultText,$match);
        preg_match_all('/name="([^"]*?)"(.*?)value="(.*?)"/',$match[1],$paramMatch);
        $params = array();
        foreach($paramMatch[1] as $k=>$v)
        {
            $params[$v] = $paramMatch[3][$k];
        }
        $ch = curl_init('http://wwwapps.ups.com/WebTracking/status');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $resultText = curl_exec ($ch);
        preg_match('/name="HIDDEN_FIELD_SESSION" type="HIDDEN" value="(.*?)"/',$resultText,$paramMatch);
        $ch = curl_init('http://wwwapps.ups.com/WebTracking/notification');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('loc'=>'zh_CN','notify'=>'submit','HIDDEN_FIELD_SESSION'=>$paramMatch[1],'smsMediaAddress1'=>'00','txtMediaAddress1'=>'hc_track@qq.com','language1'=>'zh_CN','sendDetail1'=>'true','sendException1'=>'true','sendDelivery1'=>'true','yourName'=>'wangpeng','yourEmail'=>'hc_track@qq.com')));
        $resultText = curl_exec ($ch);
        if(strpos($resultText,'确认请求状态更新'))
        {
            return true;
        }
        return false;
    }

    public static function trackUsps($number)
    {
        $resultText = tradeCommon::getContents('https://tools.usps.com/go/TrackConfirmRequestUpdateAJAXAction.action',array('label'=>$number,'name1'=>'wangpeng','email1'=>'hc_track@qq.com','update1'=>'on','update2'=>'on'));
        $resultJson = json_decode(trim($resultText),1);
        if($resultJson['serviceError'] == 'false')
        {
            return true;
        }
        return false;
    }
    public static function trackOntrac($number)
    {
        $ch = curl_init('http://www.ontrac.com/tracking_emails.asp');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('tracking'=>$number,'email_address'=>'hc_track@qq.com')));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $resultText = curl_exec ($ch);
        if(strpos($resultText,$number))
        {
            return true;
        }
        return false;
    }
}
