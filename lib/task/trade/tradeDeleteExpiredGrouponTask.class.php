<?php
class tradeDeleteExpiredGrouponTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'DeleteExpiredGrouponTask';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:DeleteExpiredGrouponTask|INFO] task does things.
Call it with:

  [php symfony trade:DeleteExpiredGrouponTask|INFO]
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
            ->select('id')
            ->where('end_time = ?', $endTime)
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