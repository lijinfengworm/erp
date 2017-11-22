<?php
class tradeAmqpSendRedEnvelopeOnceTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace            = 'trade';
        $this->name                 = 'AmqpSendRedEnvelopeOnce';
        $this->briefDescription     = '';
        $this->detailedDescription  = <<<EOF
The [trade:AmqpSendRedEnvelopeOnce|INFO] task does things.
Call it with:

  [php symfony trade:AmqpSendRedEnvelopeOnce|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);
        set_time_limit(0);
        ini_set('memory_limit','128M');

        //判断内存是否超出
        $this->_checkMemory();

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);

        $sendSuccess = 'send_success_list';
        $sendListRedis = $redis->get($sendSuccess);
        if (empty($sendListRedis)) {
            $sendList = array();
        } else {
            $sendList = unserialize($sendListRedis);
        }
        $queueRedis = $redis->get('send_queue');
        if (!empty($queueRedis)) {
            $sendQueue = unserialize($queueRedis);
            foreach ($sendQueue as $openid => $amount) {
                if (!in_array($openid, $sendList)) {
                    $amount = $amount * 100;
                    $redEnvelope = new WxSendRedEnvelope();
                    $sendRes = $redEnvelope->send($openid, $amount);
                    if ($sendRes['status']) {
                        echo '发送成功, 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户: ' . $openid . ',金额: ' . $amount . "\r\n";
                        $sendList[] = $openid;
                        $redis->set($sendSuccess, serialize($sendList)); // 加入发送成功的列表
                    } else {
                        echo '发送失败, 原因: **' . $sendRes['msg'] . '** 时间: '  . date('Y-m-d H:i:s') . ' 发送给用户: ' . $openid . ',金额: ' . $amount . "\r\n";
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