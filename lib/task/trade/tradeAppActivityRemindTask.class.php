<?php

class tradeAppActivityRemindTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AppActivityRemind';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:AppActivityRemind|INFO] task does things.
Call it with:

  [php symfony trade:AppActivityRemind|INFO]
EOF;
    }
  
    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit','256M');
        while (true) {
            $time_start = microtime(true);
            $datas = TrdAppActivityRemindTable::appNotify();
            $count = count($datas);

            $message = new tradeSendMessage();
            foreach ($datas as $item) {
                $str = '您好，您想要抢购的' . $item['title'] . '商品优惠码，还有30分钟就要开抢了。';
                $message->send($item['mobile'], $str);
                $id = $item['id'];
                $remind = TrdAppActivityRemindTable::getInstance()->find($id);
                $remind->setStatus(1);
                $remind->save();
            }
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            echo "time_cost: {$time},total: {$count} \r\n";
            sleep(1);
            $nowmem = (int)(memory_get_usage() / 1024 / 1024);
            if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
                exit(0);
            }
        }
    }
 
}
