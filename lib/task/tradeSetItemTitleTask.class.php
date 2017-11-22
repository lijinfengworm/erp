<?php

class tradeSetItemTitleTask extends sfBaseTask
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
        $this->name             = 'SetItemTitle';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tradeSetItemTitleTask|INFO] task does things.
Call it with:

  [php symfony tradeSetItemTitleTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        sfContext::createInstance($this->configuration);    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $c = new TaoBaoTopClient();

        $req = new ItemGetRequest;
        $req->setFields("title");

        $items = TrdItemTable::getInstance()->getItemsNoTitle(1000);
        foreach ($items as $item) {
            $req->setNumIid($item->getItemId());
            $resp = $c->execute($req);
            if(!isset($resp->item)) {
                continue;
            }
            $title = $resp->item->title;
            echo $item->getId() . " ";
            echo $item->getName() . "\n";
            $item->setTitle($title);
            $item->save();
        }
    }
}
