<?php
/**
 * 更新比赛的贝泰id
 * 
 */
class updateHupuidTask extends sfBaseTask
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
    $this->name             = 'updateHupuid';
    $this->briefDescription = '更新hupu uid 使虎扑uid变为主键';
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
    
    $last_id = file_get_contents('/tmp/hupuid.txt');
    if(empty($last_id))
    {
        $last_id = 3613;
    }
    $change_id = 4898;
    $change_player = HoopPlayerTable::getInstance()->find($change_id);
    
    $players = HoopPlayerTable::getInstance()->createQuery()->andWhere('id > ?',$last_id)->orderBy('id asc')->limit(10)->execute();
    foreach($players as $player)
    {
        //把正确的hupuuid 转换为主键
        if($player->getHupuid() > 0)
        {
            $this->log('开始处理： id:'.$player->getId().' hupu:'.$player->getHupuid());
            
            
            $zhan_player = HoopPlayerTable::getInstance()->find($player->getHupuid());
            if($zhan_player)
            {
                $this->log('--侵占了');
                $is_qingzhan  = 1;
                //stats 数据转换
                $zhan_player_id = $zhan_player->getId();
                
                $up_1 = $this->updatePlayerStats($zhan_player_id,$change_id);
                $this->log('----更新球员数据 from '.$zhan_player_id.' to '.$change_id.' count '.$up_1);
                
                $zhan_player_data = $zhan_player->toArray();
                unset($zhan_player_data['id']);
                $change_player->fromArray($zhan_player_data);
                $change_player->save();
                
                
                $up_2 = $this->updatePlayerStats($player->getId(),$zhan_player->getId());
                $this->log('----更新球员数据 from '.$player->getId().' to '.$zhan_player->getId().' count '.$up_2);
                
                $player_data = $player->toArray();
                unset($player_data['id']);
                $zhan_player->fromArray($player_data);
                $zhan_player->save();
                
                
                $up_3 = $this->updatePlayerStats($change_id,$player->getId());
                $this->log('----更新球员数据 from '.$change_id.' to '.$player->getId().' count '.$up_3);
                
                $change_player_data = $change_player->toArray();
                unset($change_player_data['id']);
                $player->fromArray($change_player_data);
                $player->save();
                
            }else{
                $is_qingzhan  = 0;
                $this->log('--未被侵占');
                $zhan_player = new HoopPlayer();
                $player_data = $player->toArray();
                $player_data['id'] = $player_data['hupuid'];
                $zhan_player->fromArray($player_data);
                $stats = $zhan_player->save();
                $this->log('----建立成功');
                $up_1 = $this->updatePlayerStats($player->getId(),$player->getHupuid());
                $this->log('----更新球员数据 from '.$player->getId().' to '.$player->getHupuid().' count '.$up_1);

                $player->delete();
                
                
                
            }
            file_put_contents('/tmp/hupuid.txt', $player->getId());
            
        }
    }
    
  }
  
  
  
  public function updatePlayerStats($from_id,$to_id)
  {
      return HoopPlayerMatchStatsTable::getInstance()->createQuery()->update()->set('player_id',$to_id)->where('player_id = ?',$from_id)->execute();
  }
  
  
}