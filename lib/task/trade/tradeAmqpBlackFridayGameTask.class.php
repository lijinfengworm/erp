<?php
class tradeAmqpBlackFridayGameTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace            = 'trade';
        $this->name                 = 'AmqpBlackFridayGame';
        $this->briefDescription     = '';
        $this->detailedDescription  = <<<EOF
The [trade:AmqpBlackFridayGame|INFO] task does things.
Call it with:

  [php symfony trade:AmqpBlackFridayGame|INFO]
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
            $redis->select(4);

            $sendSuccess = 'bfg_send_success_list';
            $sendFail = 'bfg_send_fail_list';

            $key = 'bfg_send_queue';
            $queueSize = $redis->LLEN($key);
            if (0 !== $queueSize) {
                $queueData = $redis->lindex($key, 0);
                $queueData = json_decode($queueData, true);
                $source = $queueData['source'];
                $uid = $queueData['uid'];
                $nickname = $queueData['nickname'];
                $no = $queueData['NO.'];
                $amount = $queueData['amount'];
                $ip = $queueData['ip'];
                if ($redis->SISMEMBER($sendSuccess, $uid)) {
                    $redis->lPop($key);
                } else {
                    $redEnvelope = new WxSendRedEnvelope();
                    $sendRes = $redEnvelope->send($uid, $amount);
                    if ($sendRes['status']) {
                        $redis->SADD($sendSuccess, $uid);
                        echo '发送成功, 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户NO.' . $no . '(IP: ' . $ip . ' ' . $nickname . '): ' . $uid . ',金额: ' . $amount / 100 . "\r\n";
                    } else {
                        $issueInfo = array(
                            'openid' => $uid,
                            'msg' => '原因: ' . $sendRes['msg'] . ' 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户(IP: ' . $ip . ' ' . $nickname . '): ' . $uid . ',金额: ' . $amount / 100
                        );
                        $redis->SADD($sendFail, json_encode($issueInfo));
                        echo '发送失败, 原因: **' . $sendRes['msg'] . '** 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户(IP: ' . $ip . ' ' . $nickname . '): ' . $uid . ',金额: ' . $amount / 100 . "\r\n";
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