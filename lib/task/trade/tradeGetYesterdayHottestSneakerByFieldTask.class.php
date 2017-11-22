<?php

class tradeGetYesterdayHottestSneakerByFieldTask extends sfBaseTask
{
    protected function configure()
    {
        //add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('field', sfCommandArgument::REQUIRED, '') //参数分别为brand或者type，分别表示品牌和类型，其它为错误的参数
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        ));

        $this->namespace = 'trade';
        $this->name = 'GetYesterdayHottestSneakerByField';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:GetYesterdayHottestSneakerByField|INFO] task does things.
Call it with:

  [php symfony trade:GetYesterdayHottestSneakerByField argument|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $field = $arguments['field'];
        sfContext::createInstance($this->configuration);
        set_time_limit(0);
        // initialize the database connection
        //$databaseManager = new sfDatabaseManager($this->configuration);
        //$connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');

        $item = Doctrine::getTable('TrdItemAll');
        $key = 'yesterdayHottestSneaker' . $field;
        $data = $item->getYesterdayHottestByField($field);
        if ($data) {
            $info = explode('-', $data['theField']);
            $count = $data['ct'];
            $id = substr($info[1], 1);
            $name = Doctrine::getTable('TrdAttribute')->find($id)->getName();
            $redis->set($key, serialize(array('id' => $id, 'name' => $name, 'ct' => $count, 'attr' => $data['theField'])));
        } else {
            echo 'fault operation';
            return;
        }
        //echo $redis->get('yesterdayHottestSneaker' . $field);
    }

}
