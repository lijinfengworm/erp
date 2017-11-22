<?php

class hupuGenerate_www_important_matchesTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'hupu'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'generate_www_important_matches';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [hupu:generate_www_important_matches|INFO] task does things.
Call it with:

  [php symfony hupu:generate_www_important_matches|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {

   sfContext::createInstance($this->configuration);
   $request = sfContext::getInstance()->getRequest();
   $request->setRelativeUrlRoot('/');

   $dbConnection = sfContext::getInstance()->getDatabaseConnection('doctrine');
   if(!$dbConnection){
     $this->log('Cant connetc to db');
     exit;
   }
   
   $matches = LlMatchTable::getInstance()->getTodayMatches();
   //var_dump($matches);die;
   if(count($matches)){
                foreach($matches as $k=>$v){
                    if($v['match_category'] == 'cba'){
                            $cba_url = 'http://nba.hupu.com/interface/v1/cba/game/info/game/'.$v['match_id'];
                            $cba_match = json_decode(file_get_contents($cba_url), true);
                            if($cba_match['status'] == 200){
                                //$cba_status = 
                                if($cba_match['data']['status']==2){
                                    $cba_status = 1;
                                }elseif($cba_match['data']['status']==1){
                                    $cba_status = 2;
                                }else{
                                    $cba_status = 3;
                                }
                                $cba_update = LlMatchTable::getInstance()->updateTodayMatchInfo($v['id'],$cba_status,$cba_match['data']['recap']['url']);
                                if(is_numeric($cba_update)){
                                    $this->log('成功更新cba-比赛id是'.$v['match_id']);
                                }else{
                                    $this->log('失败更新cba-比赛id是'.$v['match_id']);
                                }
                            }
                    }elseif($v['match_category'] == 'nba'){
                            $nba_match = HoopMatchTable::getInstance()->getMatchInfoByMatchId($v['match_id']);
                            //var_dump($nba_match->toArray());die;
                            if(count($nba_match)){
                                if($nba_match['status']==1){
                                    $recap_url = 'http://g.hupu.com/nba/boxscore_'.$v['match_id'].'.html';
                                }else{
                                    $recap_url = '';
                                }
                                
                                $nba_updata = LlMatchTable::getInstance()->updateTodayMatchInfo($v['id'],$nba_match['status'],$recap_url);
                                if(is_numeric($nba_updata)){
                                    $this->log('成功更新nba-比赛id是'.$v['match_id']);
                                }else{
                                    $this->log('失败更新nba-比赛id是'.$v['match_id']);
                                }
                            } 
                    }else{
                            $soccer_url = 'http://g.hupu.com/soccer/api/gs_match_for_www.php?aid='.$v['match_id'];
                            $soccer_match = json_decode(file_get_contents($soccer_url), true);
                            //var_dump($soccer_match);die;
                            if($soccer_match){
                                $recap_url = '';
                                if($soccer_match['match']["ended"]==1){
                                    $recap_url = $soccer_match['match']["url"];
                                }
                                $soccer_update = LlMatchTable::getInstance()->updateTodayMatchInfo($v['id'],$soccer_match['match']["ended"],$recap_url);
                                if(is_numeric($soccer_update)){
                                    $this->log('成功更新足球-比赛id是'.$v['match_id']);
                                }else{
                                    $this->log('成功更新足球-比赛id是'.$v['match_id']);
                                }
                            }
                    }
                }
   }
  }
}
