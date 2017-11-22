<?php
class tradeAmqpBFGReissueTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace            = 'trade';
        $this->name                 = 'AmqpBFGReissue';
        $this->briefDescription     = '';
        $this->detailedDescription  = <<<EOF
The [trade:AmqpBFGReissue|INFO] task does things.
Call it with:

  [php symfony trade:AmqpBFGReissue|INFO]
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

            $sendSuccessv2 = 'bfg_send_success_list_v2';
            $sendFail = 'bfg_send_fail_list';
            $sendFailv2 = 'bfg_send_fail_list_v2';

            $info = $redis->SPOP($sendFail);
            if (!empty($info)) {
                $info = json_decode($info, true);
                $openid = $info['openid'];
                $historyKey = 'bfg_red_envelope_history_' . $openid;
                $redisHistory = unserialize($redis->get($historyKey));
                if (!empty($redisHistory)) {
                    $amount = $redisHistory['amount'];

                    $redEnvelope = new WxSendRedEnvelope();
                    $sendRes = $redEnvelope->send($openid, $amount);
                    if ($sendRes['status']) {
                        $redis->SADD($sendSuccessv2, $openid);
                        echo '发送成功, 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户: ' . $openid . ',金额: ' . $amount / 100 . "\r\n";
                    } else {
                        $issueInfo = array(
                            'openid' => $openid,
                            'amount' => $amount,
                            'msg' => '原因: ' . $sendRes['msg'] . ' 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户: ' . $openid . ',金额: ' . $amount / 100
                        );
                        $redis->SADD($sendFailv2, json_encode($issueInfo));
                        echo '发送失败, 原因: **' . $sendRes['msg'] . '** 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户: ' . $openid . ',金额: ' . $amount / 100 . "\r\n";
                    }
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