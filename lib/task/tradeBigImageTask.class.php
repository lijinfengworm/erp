<?php

class tradeBigImageTask extends sfBaseTask
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
            new sfCommandOption('from', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', '0'),
            // add your own options here
        ));

        $this->namespace        = 'trade';
        $this->name             = 'BigImage';
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
        $from = $options["from"];
        $items = TrdItemTable::getInstance()->getItemsAll($from);
        foreach ($items as $item) {
            $file = sfConfig::get('app_img_dir_web') . $item->getImgUrl();
            $target_file = str_replace(".jpg", "_300.jpg", $file);
            if(file_exists($file) && !file_exists($target_file)) {
                copy($file, $target_file);
                //echo $target_file."\n";
            }
        }
    }
}
