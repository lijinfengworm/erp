<?php

class totalTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','eric'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            // add your own options here
        ));

        $this->namespace        = 'eric';
        $this->name             = 'total';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);    
        $databaseManager = new sfDatabaseManager($this->configuration);

        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $objects_key = "objects_key";

        $logTable = ErLogTable::getInstance();
//        $objects = $logTable->createQuery()
//            ->select("count(*) as count, object_name")
//            ->groupby("object_name")
//            ->orderby("count desc")
//            ->execute();
//        $objects = $objects->toArray();
//        $memcache->set($objects_key, $objects, 0, 0);
//        print_r($memcache->get($objects_key));
        $objects = $memcache->get($objects_key);
        $i = 1;
        $objects = array(
//            array("object_name" => "湖人"),
//            array("object_name" => "热火"),
            array("object_name" => "火箭"),
            );
        foreach($objects as $object) {
            if($object["object_name"] == "NBA") {
                continue;
            }
            if($i > 3) {
                exit();
            }
            $rs = $logTable->createQuery()
                ->select("count(*) as count, object_name")
                ->andWhere("object_name = ?", $object["object_name"])
                ->groupby("userid")
                ->count();

            print_r($object);
            print_r($object["object_name"] . "," . $rs . "\n");
            $i ++;
        }

    }
}
