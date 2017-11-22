<?php
class tradeDeleteExpiredGrouponOnceTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'DeleteExpiredGrouponOnceTask';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:DeleteExpiredGrouponOnceTask|INFO] task does things.
Call it with:

  [php symfony trade:DeleteExpiredGrouponOnceTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        set_time_limit(0);
        $time_start = time();
        $endTime = date('Y-m-d 10:00:00');
        $groupon = TrdGrouponTable::getInstance()
            ->createQuery()
            ->select('id, end_time')
            ->where('id >= ?', 4197)
            ->andWhere('id <= ?', 4952)
            ->andWhere('end_time <= ?', $endTime)
            ->addOrderBy('id desc')
            ->execute();
        foreach ($groupon as $item) {
            $grouponId = $item->getId();
            echo $grouponId, '=>';
            $c = new TaeShihuoTopClient();
            $req = new TaeDeliveryItemDeleteRequest();
            $req->setOutId($grouponId);
            $resp = $c->execute($req);
            if (isset($resp->result) && $resp->result) {
                echo 'delete success', PHP_EOL;
            } else {
                echo 'delete fail', PHP_EOL;
            }
        }
        $time_end = time();
        $time = $time_end - $time_start;
        echo "time_cost: {$time}s \r\n";
        unset($clients);
        exit;
    }
}