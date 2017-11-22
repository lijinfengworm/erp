<?php
/**
 * 更新比赛的贝泰id
 * 
 */
class updatePlayerInfoTask extends sfBaseTask
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
    $this->name             = 'updatePlayerInfo';
    $this->briefDescription = '更新非现役球员基本信息';
    $this->detailedDescription = <<<EOF
The [gamespace:updatePlayerInfo|INFO] task does things.
Call it with:

  [php symfony gamespace:updatePlayerInfo|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);    
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $taskConfig = sfConfig::get('app_tasks');
    
    $last_id = file_get_contents('/tmp/playerId.txt');
    if(empty($last_id))
    {
        $last_id = 1;
    }

    $change_player = HoopPlayerTable::getInstance()->find($change_id);
    
    $players = HoopPlayerTable::getInstance()->createQuery()->andWhere('id >= ?',$last_id)->andWhere("beitai_pid = 0")->orderBy('id asc')->limit(200)->execute();

    if(!$players){
        exit("update finished");
    }
    foreach($players as $player)
    {
        $id = $player->getId();
        
        if($id > 0)
        {
            $url = "http://nba.hupu.com/interface/v1/api/nba/player/databaseinfo/".$id;
            
            $str = file_get_contents($url);
            $info = json_decode($str, TRUE);

            $birthDateArr = explode('-', $info['data'][0]['birthday']);
        
            $roundArr = array('第一轮'=> 1, '第二轮' => 2);
            $data = array(
             //   'id' => $id,   
                'birth_date' => mktime(0, 0 , 0, $birthDateArr[1], $birthDateArr[2], $birthDateArr[0]),
                'draft_round' => $roundArr[$info['data'][0]['draftRound']],
                'draft_year' => $info['data'][0]['draftYear'],
                'draft_pick' => $info['data'][0]['draft'],
                'country' => $info['data'][0]['nation'],
                'position' =>  $info['data'][0]['position']
            );
            
            if($player->getHeight() == 0){
                if(strpos($info['data'][0]['height'], '/')){
                    $heightArr = explode('/', $info['data'][0]['height']);
                    $hArr = explode('尺', trim($heightArr[1]));
                    $data['height'] = $hArr[0] *12 + $hArr[1];
                }else{
                    $data['height'] = round($info['data'][0]['height'] * 100 * 0.393700787); 
                }
            }

            $player->fromArray($data);
       
            $player->save();
            $this->log('update player info:'.$id);

            file_put_contents('/tmp/playerId.txt', $id);
        }
    }
    
  }
  
  
  
  public function updatePlayerStats($from_id,$to_id)
  {
      return HoopPlayerMatchStatsTable::getInstance()->createQuery()->update()->set('player_id',$to_id)->where('player_id = ?',$from_id)->execute();
  }
  
  
}