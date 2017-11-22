<?php
/**
 * 把旧的nba数据更新过来
 * 
 */
class downloadPlayerImageTask extends sfBaseTask
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
    $this->name             = 'downloadPlayerImage';
    $this->briefDescription = '下载用户头像图片';
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
    
    $last_id = file_get_contents('/tmp/downloadPlayerImage.txt');
    if(empty($last_id))
    {
        $last_id = 0;
    }
    //$last_id = 699;
    $players = HoopPlayerTable::getInstance()->createQuery()->andWhere('id > ?',$last_id)->andWhere('hupuid > ?',0)->orderBy('id asc')->limit(1000)->execute();
    foreach($players as $player)
    {
        
        $old_playerInfo = common::Curl('http://nba.hupu.com/interface/v1/nba/player/'.$player->getHupuid());
        if($old_playerInfo)
        {
            $old_playerInfo = json_decode($old_playerInfo,1);
            if($old_playerInfo['data']['info']['playerId'])
            {
                $info = $old_playerInfo['data']['info'];
//                $photo_path = sfConfig::get('sf_upload_dir') .'/gamespace/players/'.($player->getId()%10).'/'.$player->getId().'.jpg';
//                $save = $this->grabImage($info['photo'],$photo_path);
//                if($save)
//                {
//                    $player->setPhoto('/' . basename(sfConfig::get('sf_upload_dir')) . '/gamespace/players/'.($player->getId()%10).'/'.$player->getId().'.jpg');
//                }
//                else{
//                    $this->log('download error');
//                }
                preg_match('/((\d).(\d+))米\/.*/', $info['height'],$match);
                
                $player->setHeight(round(($match[2]*100+$match[3])/2.54));
                
                $player->setWeight($info['weight']);
                $player->setWage($info['contract']);
                $player->save();
                $this->log('update :'.$player->getId());
            }else{
                $this->log('data error');
            }
        }  else
        {
            $this->log('curl error');
        }
        if($player->getHupuid() != $player->getId())
        {
            $this->log('不匹配id');
        }
        
        file_put_contents('/tmp/downloadPlayerImage.txt', $player->getId());
        
    }
    
  }
  /**
   * 抓取远程图片
   *
   * @param string $url 远程图片路径
   * @param string $filename 本地存储文件名
   */
  function grabImage($url, $filename = '') {
     if(!is_dir(dirname($filename)))
     {
         //mkdir(dirname($filename), "0755");
     }
     $header = get_headers($url);
     if($header[0] == 'HTTP/1.1 200 OK')
     {
        //$s = file_put_contents($filename,file_get_contents($url));
        return TRUE;
     }
     return false;     
  }
  
  
  public function updatePlayerStats($from_id,$to_id)
  {
      return HoopPlayerMatchStatsTable::getInstance()->createQuery()->update()->set('player_id',$to_id)->where('player_id = ?',$from_id)->execute();
  }
  
  
}