<?php
/**
 *  boxscore 更新最近五场数据
 * 
 */
class updateCurrentSeasonMatchesTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
      // // add your own arguments here
    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'gamespace'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'gamespace'),
      // add your own options here
    ));

    $this->namespace        = 'gamespace';
    $this->name             = 'updateCurrentSeasonMatches';
    $this->briefDescription = '更新当前赛季赛程';
    $this->detailedDescription = <<<EOF
The [gamespace:updateCurrentSeasonMatches|INFO] task does things.
Call it with:

  [php symfony gamespace:updateCurrentSeasonMatches|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);

        ini_set("memory_limit", 524288000); //500m
        ini_set("max_execution_time", 2400); //40mins

        $season = GamespaceProvider::getCurrentSeason();

        $redis = sfContext::getInstance()->getDatabaseConnection('gamespaceRedis');
        $key = 'gamespace_season_all_matches_'.$season;

        $matches = HoopMatchTable::getInstance()
            ->getAllBySeasonInObj($season);
        if($matches) {
            $redis->setex($key, 5 * 3600, serialize($matches));
        }
    }



}