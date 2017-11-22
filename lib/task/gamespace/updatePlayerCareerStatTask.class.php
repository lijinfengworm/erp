<?php
/**
 * 更新球员的职业生涯的数据
 * 
 */
class updatePlayerCareerStatTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'gamespace'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'gamespace'),
      // add your own options here
    ));

    $this->namespace        = 'gamespace';
    $this->name             = 'updatePlayerCareerStat';
    $this->briefDescription = '更新球员的职业生涯的数据';
    $this->detailedDescription = <<<EOF
The [gamespace:updateMatchBetTaiId|INFO] task does things.
Call it with:

  [php symfony gamespace:updateMatchBetTaiId|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);    
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $taskConfig = sfConfig::get('app_tasks');
    
    $redis = sfContext::getInstance()->getDatabaseConnection('gamespaceRedis');
    $last_id = $redis->get('updatePlayerCareer_pid');
    if(empty($last_id))
    {
        $last_id = 0;
    }
    
    
    //$last_id = 1201;
    $players = HoopPlayerTable::getInstance()->createQuery()->andWhere('id > ?',$last_id)->andWhere('beitai_pid > 0')->orderBy('id asc')->limit(500)->execute();
    
    foreach($players as $player)
    {
        
        $regular_careerStats = HoopPlayerCareerStatsTable::getInstance()->createQuery()->where('player_id = ?',$player->getId())->andWhere('match_type = ?','REGULAR')->execute();
        
        
        $regular_url = BeitaiInterfaceUrl::getplayerCareerStat($player->getBeitaiPid(),1);
        
        $regular_data = common::Curl($regular_url);
        if(!empty($regular_data['playerCareerStat']))
        {
            $new_regular_careerStats = $this->updateCareerStat($player, $regular_data, "REGULAR");
            
            foreach ($regular_careerStats as $stats)
            {
                if(empty($new_regular_careerStats[$stats->getSeason().'_'.$stats->getTeamId()]))
                {
                    $stats->delete();
                }else{
                    $stats->fromArray($new_regular_careerStats[$stats->getSeason().'_'.$stats->getTeamId()]);
                    $stats->save();
                    unset($new_regular_careerStats[$stats->getSeason().'_'.$stats->getTeamId()]);
                }
            }
            foreach($new_regular_careerStats as $stats_array)
            {
                $HoopPlayerCareerStats = new HoopPlayerCareerStats();
                $HoopPlayerCareerStats->fromArray($stats_array);
                $HoopPlayerCareerStats->save();
            }
        }else{
            $this->log('curl regular error'.$player->getId());
        }
        
        $playoff_careerStats = HoopPlayerCareerStatsTable::getInstance()->createQuery()->where('player_id = ?',$player->getId())->andWhere('match_type = ?','PLAYOFF')->execute();
        
        $playoff_url = BeitaiInterfaceUrl::getplayerCareerStat($player->getBeitaiPid(),2);
        $playoff_data = common::Curl($playoff_url);
        if(!empty($playoff_data['playerCareerStat']))
        {
            $new_playoff_careerStats = $this->updateCareerStat($player, $playoff_data, "PLAYOFF");
            
            foreach ($playoff_careerStats as $stats)
            {
                
                if(empty($new_playoff_careerStats[$stats->getSeason().'_'.$stats->getTeamId()]))
                {
                    $stats->delete();
                }else{
                    $stats->fromArray($new_playoff_careerStats[$stats->getSeason().'_'.$stats->getTeamId()]);
                    $stats->save();
                    unset($new_playoff_careerStats[$stats->getSeason().'_'.$stats->getTeamId()]);
                }
            }
            
            foreach($new_playoff_careerStats as $stats_array)
            {
                $HoopPlayerCareerStats = new HoopPlayerCareerStats();
                $HoopPlayerCareerStats->fromArray($stats_array);
                $HoopPlayerCareerStats->save();
            }
            
        }else{
            $this->log('curl playoff error'.$player->getId());
        }
        $this->log('update:'.$player->getId());
        $redis->setex('updatePlayerCareer_pid', 60*60*2,$player->getId());
    }
    
  }
  
  
  public function formatData($beitai_data,$player_id,$player_name,$match_type)
  {
      /**
       * 
       *
        Season	String	赛季
        TeamID	Int	球队ID
        TeamCNAlias	String	球队中文简称
        TeamENName	String	球队英文名
        Games	Int	上场次数
        GamesStarted	Int	首发次数
        Minutes	Int	上场总分钟
        FieldGoals	Int	进球
        FieldGoalsAttempted	Int	投篮次数
        FieldGoalsPercentage	Double	投篮命中率
        FreeThrows	Int	罚球命中数
        FreeThrowsAttempted	Int	罚球次数
        FreeThrowsPercentage	Double	罚球命中率
        ThreePointGoals	Int	三分命中数
        ThreePointAttempted	Int	三分投篮数
        ThreePointPercentage	Double	三分命中率
        Points	Int	得分
        Rebounds	Int	篮板
        ReboundsOffensive	Int	进攻篮板
        ReboundsDefensive	Int	防守篮板
        Assists	Int	助攻
        Steals	Int	抢断
        Blocked	Int	盖帽
        Turnovers	Int	失误
        PersonalFouls	Int	犯规
        TechnicalFouls	Int	技术犯规
        PlusMinus	Double	效率

       */
      $data = array();
      $data['player_id'] = $player_id;
      $data['player_name'] = $player_name;
      
      $beitai_data['TeamID'] > 0 && $data['team_id'] = BeitaiTeamInfoSwitch::getHupuTeamIdByBeiTaiTeamId($beitai_data['TeamID']);
      
      
      $data['team_name'] = $beitai_data['TeamCNAlias'];
      $data['season'] = $beitai_data['Season'];
      $data['match_type'] = $match_type;
      $data['games'] = $beitai_data['Games'];
      $data['games_started'] = $beitai_data['GamesStarted'];
      $data['mins'] = $beitai_data['Minutes'];
      $data['pts'] = $beitai_data['Points'];
      $data['fga'] = $beitai_data['FieldGoalsAttempted'];
      $data['fgm'] = $beitai_data['FieldGoals'];
      $data['fgp'] = $beitai_data['FieldGoalsPercentage'];
      $data['tpt'] = $beitai_data['ThreePointGoals'] * 3;
      $data['tpa'] = $beitai_data['ThreePointAttempted'];
      $data['tpm'] = $beitai_data['ThreePointGoals'];
      $data['tpp'] = $beitai_data['ThreePointPercentage'];
      $data['fpt'] = $beitai_data['FreeThrows'];
      $data['fta'] = $beitai_data['FreeThrowsAttempted'];
      $data['ftm'] = $beitai_data['FreeThrows'];
      $data['ftp'] = $beitai_data['FreeThrowsPercentage'];
      $data['dreb'] = $beitai_data['ReboundsDefensive'];
      $data['oreb'] = $beitai_data['ReboundsOffensive'];
      $data['reb'] = $beitai_data['Rebounds'];
      $data['asts'] = $beitai_data['Assists'];
      $data['stl'] = $beitai_data['Steals'];
      $data['blk'] = $beitai_data['Blocked'];
      $data['to'] = $beitai_data['Turnovers'];
      $data['pf'] = $beitai_data['PersonalFouls'];
      $data['tf'] = $beitai_data['TechnicalFouls'];
      $data['plus_minus'] = $beitai_data['PlusMinus'];
      $data['sequence'] = $beitai_data['Sequence'];
      return $data;
  }
  public function updateCareerStat($player,$data,$type)
  {
      $result = array();
      $data = json_decode($data,1);
      foreach ($data['playerCareerStat'] as $stats)
      {
          $formatData = $this->formatData($stats,$player->getId(),$player->getName(),$type);
          $result[$formatData['season'].'_'.$formatData['team_id']] = $formatData;
      }
      return $result;
  }
  
  
}