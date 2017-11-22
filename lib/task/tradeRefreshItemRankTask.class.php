<?php

class tradeRefreshItemRankTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('itemtype', null, sfCommandOption::PARAMETER_REQUIRED, 'The item type', 'all'),
                // add your own options here
        ));

        $this->namespace = 'trade';
        $this->name = 'RefreshItemRank';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tradeSetItemTitleTask|INFO] task does things.
Call it with:
    hello world
  [php symfony tradeSetItemTitleTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        sfContext::createInstance($this->configuration);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        //add your code
        $itemtype = $options['itemtype'];
        
        $ttserver = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $ttkey = 'refreshitemranklastid' . $itemtype;
        $lastId = $ttserver->get($ttkey);
        $this->log('lastId :'.$lastId);
        if (!empty($lastId)) {
            if ($itemtype == 'shoe') {
                $items = TrdItemTable::getInstance()->getItemsForRefresh(1000, $lastId, 0);
            } else {
                $items = TrdItemAllTable::getInstance()->getItemsForRefresh(1000, $lastId, 0);
            }
        } else {
            if ($itemtype == 'shoe') {
                $items = TrdItemTable::getInstance()->getItemsForRefresh(1000, 0, 1);
            } else {
                $items = TrdItemAllTable::getInstance()->getItemsForRefresh(1000, 0, 1);
            }
        }

        if (count($items) > 0) {
            foreach ($items as $item) {
                $rank = $item->getRankByBaseDate();
                $item->setRank($rank);
                $item->save();
                $this->log($item->getId().':'.$rank);
                $ttserver->set($ttkey, $item->getId(), 0, 86400);
            }
        } else {
            $ttserver->set($ttkey, 0);
        }
        
    }

}
