<?php

class tradeUniqueItemTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            // add your own options here
        ));

        $this->namespace        = 'trade';
        $this->name             = 'UniqueItem';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
none

EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        sfContext::createInstance($this->configuration);    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $key = "unique_item_last_id";
        $lastId = $memcache->get($key);
        if(!$lastId) {
            $lastId = 0;
        }

        $itemTable = TrdItemTable::getInstance();
        $items = $itemTable->getItemsAll($lastId, 1000);

        foreach ($items as $item) {
            $itemTable->doUniqueItem($item);
            $memcache->set($key, $item->getId(), 0, 3600);
        }

//        $this->execute($arguments, $options);
    }

    function cleanup($arguments = array(), $options = array()) {
    }
}
