<?php

class tradeHaitaoForecastNewTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
            // add your own options here
        ));

        $this->namespace        = 'trade';
        $this->name             = 'HaitaoForecastNew';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:HaitaoForecastNew|INFO] task does things.
Call it with:

  [php symfony trade:HaitaoForecastNew|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        ini_set('memory_limit', '128M');
        set_time_limit(0);
        sfContext::createInstance($this->configuration);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $birdex = new tradeBirdexService();
        $birdex->forecastPackage(15);
        exit();
    }
}
