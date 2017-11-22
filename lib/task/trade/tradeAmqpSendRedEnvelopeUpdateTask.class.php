<?php
class tradeAmqpSendRedEnvelopeUpdateTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace            = 'trade';
        $this->name                 = 'AmqpSendRedEnvelopeUpdate';
        $this->briefDescription     = '';
        $this->detailedDescription  = <<<EOF
The [trade:AmqpSendRedEnvelopeUpdate|INFO] task does things.
Call it with:

  [php symfony trade:AmqpSendRedEnvelopeUpdate|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);
        set_time_limit(0);
        ini_set('memory_limit','128M');

        while(true) {
            // 23:55 ~ 8:05 自动退出
            $time = date('H:i:s');
            if ('23:55:00' <= $time || ('08:05:00' >= $time)) {
                sleep(10);
                exit(0);
            }

            //判断内存是否超出
            $this->_checkMemory();

            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $redis->select(5);

/*            $sendSuccess = 'send_success_list';
            $sendFail = 'send_fail_list';*/

            $sendSuccess = 'send_success_list_v2';
            $sendFail = 'send_fail_list_v2';

            //$key = 'send_queue_optimize'; // v1
            $key = 'send_queue_optimize_v2'; // v2
            $queueSize = $redis->lSize($key);
            if (0 !== $queueSize) {
                $queueData = $redis->lGet($key, 0);
                $queueData = json_decode($queueData, true);
                $openid = $queueData['openid'];
                $amount = $queueData['amount'];
                if ($redis->SISMEMBER($sendSuccess, $openid)) {
                    $redis->lPop($key);
                } else {
                    $redEnvelope = new WxSendRedEnvelope();
                    $sendRes = $redEnvelope->send($openid, $amount);
                    if ($sendRes['status']) {
                        $redis->SADD($sendSuccess, $openid);
                        echo '发送成功, 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户(IP: ' . $queueData['ip'] . ' ' . $queueData['nickname'] . '): ' . $openid . ',金额: ' . $amount . "\r\n";
                    } else {
                        $issueInfo = array(
                            'openid' => $openid,
                            'msg' => '原因: ' . $sendRes['msg'] . ' 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户(IP: ' . $queueData['ip'] . ' ' . $queueData['nickname'] . '): ' . $openid . ',金额: ' . $amount
                        );
                        $redis->SADD($sendFail, json_encode($issueInfo));
                        echo '发送失败, 原因: **' . $sendRes['msg'] . '** 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户(IP: ' . $queueData['ip'] . ' ' . $queueData['nickname'] . '): ' . $openid . ',金额: ' . $amount . "\r\n";
                    }
                    $redis->lPop($key);
                }
            }
        }
    }

    private function _checkMemory() {
        $nowmem = (int) (memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }
}