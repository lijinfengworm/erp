<?php
/**
 *  boxscore 更新最近五场数据
 * 
 */
class updateLatestFiveDataTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
      // // add your own arguments here
      $this->addArguments(array(
          new sfCommandArgument('match_type', sfCommandArgument::OPTIONAL, 'The match type','PRESEASON'),
      ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'gamespace'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'gamespace'),
      // add your own options here
    ));

    $this->namespace        = 'gamespace';
    $this->name             = 'updateLatestFiveData';
    $this->briefDescription = '更新最近五场数据';
    $this->detailedDescription = <<<EOF
The [gamespace:updateLatestFiveData|INFO] task does things.
Call it with:

  [php symfony gamespace:updateLatestFiveData|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        if(!in_array($arguments['match_type'],array(HoopMatch::SEASON_PLAYOFF,HoopMatch::SEASON_PRESEASON,HoopMatch::SEASON_REGULAR))){
            echo 'match_type params error ';exit;
        }
        sfContext::createInstance($this->configuration);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $season = GamespaceProvider::getCurrentSeason();  //


        HoopMatchStatsTable::getInstance()->updateLatestFiveDaysMatchInfo($arguments['match_type']);


    }



}