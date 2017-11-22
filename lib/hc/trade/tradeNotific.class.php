<?php

/**
 * 识货 消息推送 
 */
class tradeNotific
{

    /**
     * 此时间范围内不发送通知
     * @var array
     */
    public static $excludeTimeRange = array(0, 7);

    /**
     * IOS通知以此值切割成N份
     * @var int
     */
    public static $iosMsgMaxNum = 2000;

    /**
     * Android通知以此值切割成N份
     * @var int
     */
    public static $androidMsgMaxNum = 2000;


    /**
     * ios设备通过发送最大失败次数，超过这个数字将不在发送
     * @var int
     */
    public static $iosMaxfeedbackNum = 10;

    /**
     * Redis 连接
     * @var resources
     */
    private $redis;
    
    /**
         * 实时通知发送Node.js请求地址
         */
    //线上发布
    private $notificUrls = array(
            'ios' => 'http://192.168.1.84:12320/ios/notific/com.hupu.shihuo',
            'droid' => 'http://192.168.1.84:12320/droid/notific/com.hupu.shihuo',
    );
/*
    // 线下测试
     private $notificUrls = array(
            'ios' => 'http://61.174.9.138:12320/ios/notific/com.hupu.shihuo',
            'droid' => 'http://61.174.9.138:12320/droid/notific/com.hupu.shihuo',
        );
*/
    public function __construct()
    {
        $this->redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
    }

    /**
     * 获取通知发送失败的设备信息并标识为不再发送
     */
    public function feedback()
    {
        echo "\r\n" . date('Ymd') . ' feedback begin | ';


        $server = 'http://61.174.9.138:12320/';
        $feedbackUrl = $server . 'ios/feedback/com.hupu.shihuo';
        $invtokenUrl = $server . 'ios/invtoken/com.hupu.shihuo';

        $invtoken = tradeCommon::getContents($invtokenUrl);
        $invtoken && $invtoken = @json_decode($invtoken, TRUE);

        if($invtoken)
        {
            foreach($invtoken as $k => $v)
            {
                //$_ENV['client']->setIosTypeByToken(self::splitToken($v), 255);
            }

            echo ' invtoken: ' . count($invtoken);
        }

        unset($invtoken);

        $feedback = tradeCommon::getContents($feedbackUrl);
        $feedback && $feedback = @json_decode($feedback, TRUE);

        if($feedback)
        {
            foreach($feedback as $k => $v)
            {
                //$_ENV['client']->setIosTypeByToken(self::splitToken($v[1]), 1);
            }

            echo ' | feedback: ' . count($feedback);
        }

        unset($feedback);

        echo "\r\n" . date('Ymd') . ' feedback end';
    }

    /**
     * 通知服务
     */
    public function notific()
    {
        if($this->etime())
        {
            return FALSE;
        }
        
        //获取发送通知任务
        $message = TrdMessageTable::getInstance()->createQuery()->where('status = ?',0)->andWhere('is_delete = ?',0)->limit(1)->fetchOne();
        if (!empty($message)){
            echo date("Y-m-d H:i:s")." ";
            $time_start = microtime(true);
            $data = $message->toArray();
            $count = $count_ios = 0;
            if ($data['type'] == 1){
                 // 发送Android通知
                $count = $this->sendMsg($data, "android");
            } else if ($data['type'] == 2){
                 // 发送ios通知
                 $count_ios = $this->sendMsg($data, "apple");
            } else if(empty($data['type'])){
                 // 发送Android通知
                 $count = $this->sendMsg($data, "android");
                 // 发送ios通知
                 $count_ios = $this->sendMsg($data, "apple");
            }
            
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            echo "time_cost: {$time},android: {$count},ios: {$count_ios} \r\n";
            
            //修改发送状态
            $msgObj = TrdMessageTable::getInstance()->findOneById($message['id']);
            $msgObj->setStatus(1);
            $msgObj->save();
        } else {
            sleep(60);
        }
        
        return ;
    }

    private function sendMsg($nd, $platform="android") {

        if(!in_array($platform, array("android", "apple"))) {
            return ;
        }

        //统计消息发送数量
        $msg_count = 0;

        //$iosMaxfeedbackNum = self::$iosMaxfeedbackNum;
        
        $data = array();
        if($platform == "android") {
            $clients = $this->getClients(1);
            if ($clients['total'] > 0){
                foreach($clients['data'] as $k=>$v){
                    $data[] = $v['client_str'];
                }
            }
        } else {
            $clients = $this->getClients(2);
            if ($clients['total'] > 0){
                foreach($clients['data'] as $k=>$v){
                    $data[] = $v['client_token'];
                }
            }
        }

        $msg_count = $msg_count + $clients['total'];
        if ($clients['total'] > 0){
            $this->goApi($nd,$platform,$data);
        }
        unset($clients);
        
        return $msg_count;
    }

    private function goApi($nd ,$platform, $data)
    {
        // 发送Android通知
        if($platform == "android") {
            $androidData = array(
                'clients' => array(),
                'expiry' => time() + 3600,
                'payload' => array(
                    'aps' => array(
                        'alert' => array(
                            'body' => $nd['content'],
                            'title' => $nd['title'],
                            'action-loc-key' => '',
                            'loc-key' => '',
                            'loc-args' => '',
                            'launch-image' => '',
                            ),
                        'badge' => 3,
                        'sound' => '',
                        ),
                    //'url' => "app://youhui/".$nd['news_id'],
                    'url' => $nd['href'],
                    'args' => "",
                    ),
                );

            $data_arr = self::sliceArray($data, self::$androidMsgMaxNum);

            foreach($data_arr as $key => $clients) {
                $androidData['clients'] = $clients;
                tradeCommon::getContents($this->notificUrls['droid'], json_encode($androidData), 10, 'POST', 'application/json');
            }

        } else if($platform == "apple") {
            $iosData = array(
                'tokens' => array(),
                'expiry' => time() + 1800,
                'payload' => array(
                    'aps' => array(
                        'alert' => $nd['content'],
                    ),
                    'url' => isset($nd['href']) ? $nd['href'] : '',
                ),
            );

            $data_arr = self::sliceArray($data, self::$iosMsgMaxNum);

            foreach($data_arr as $key => $tokens) {
                $iosData['tokens'] = $tokens;
                tradeCommon::getContents($this->notificUrls['ios'], json_encode($iosData), 10, 'POST', 'application/json');
            }
        }
    }


    /**
     * 在某时间范围内不发送通知
     * @return boolean
     */
    private function etime()
    {
        return FALSE;

        $hour = intval(date('H'));

        return $hour >= self::$excludeTimeRange[0] && $hour < self::$excludeTimeRange[1];
    }

    /**
     * 将数据切割成指定数块
     * @param array $data
     * @param int $sliceNum
     * @return array
     */
    static private function & sliceArray($data, $sliceNum)
    {
        $result = array();

        $count = count($data);

        if($count <= $sliceNum)
        {
            $result[] = & $data;
        }
        else
        {
            $num = ceil($count / $sliceNum);

            for($i = 0; $i < $num; $i++)
            {
                $result[] = array_splice($data, 0, $sliceNum);
            }
        }

        return $result;
    }

    static private function & sliceArrayOld($data, $sliceNum)
    {
        $result = array();

        $count = count($data);
        $sliceCount = (!$sliceNum || $sliceNum < 0) ? $count : ceil($count / $sliceNum);

        for($i = 0; $i <= $count; $i += $sliceCount)
        {
            $sliceData = array_splice($data, 0, $sliceCount);

            if(!$sliceData)
            {
                break;
            }

            $result[] = $sliceData;
        }

        return $result;
    }

    /**
     * 将没有空格的Token生成有空格的形式
     * @param string $token
     * @return string
     */
    static private function splitToken($token)
    {
        if($token && strlen($token) != 71)
        {
            $tmp = str_split($token, 8);
            $token = implode(' ', $tmp);
        }

        return $token;
    }
    
    //获取app用户
    public function getClients($type=0){
        $limit = 1000;
        $info = array(
            'total' => 0,
            'data' => array()
        );
        $currentNumKey = 'trade_app_client_info_' . $type . '_current_num';
        $currentNumRedis = $this->redis->get($currentNumKey);
        if ($currentNumRedis) {
            $offset = unserialize($currentNumRedis);
            $curretnDataKey = 'trade_app_client_info_' . $type . '_current_data_' . '_limit_' . $limit . '_offset_' . $offset;
            $info = unserialize($this->redis->get($curretnDataKey));
        } else {
            $offset = 0;
        }
        $key = 'trade_app_client_info_' . $type;
        $infoRedis = $this->redis->get($key);
        if ($infoRedis) {
            $info = unserialize($infoRedis);
        }
        if (empty($info['data']) || $offset) {
            $minLastVisit = strtotime('-90 day');
            do {
                $query = TrdClientInfoTable::getInstance()->createQuery('t')
                    ->select('t.client_str,t.client_token')
                    ->where('t.status  = ?',0)
                    ->andWhere('t.push_switch = ?',0)
                    ->andWhere('t.last_virst > ?',$minLastVisit)
                    ->limit($limit)
                    ->offset($offset);
                if ($type) $query = $query->andWhere('t.type = ?',$type);
                $data = $query->fetchArray();
                $count = count($data);
                $offset += $count;
                $info['total'] = $offset;
                $info['data'] = array_merge($info['data'], $data);
                $this->redis->set($currentNumKey, serialize($offset));
                $curretnDataKey = 'trade_app_client_info_' . $type . '_current_data_' . '_limit_' . $limit . '_offset_' . $offset;
                $this->redis->set($curretnDataKey, serialize($info));
            } while ($count != 0);
            $this->redis->del($currentNumKey);
            $this->redis->del($curretnDataKey);
            $this->redis->set($key, serialize($info), 600);
        }
        return $info;
    }
}
