<?php
/**
 * 更新比赛的贝泰id
 * 
 */
class updateMatchAvgScoreTask extends sfBaseTask
{
  protected function configure()
  {
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
    $this->name             = 'updateMatchAvgScore';
    $this->briefDescription = '更新比赛平均成绩';
    $this->detailedDescription = <<<EOF
The [gamespace:updateMatchAvgScore|INFO] task does things.
Call it with:

  [php symfony gamespace:updateMatchAvgScore|INFO]
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


        $season=GamespaceProvider::getCurrentSeason();

        $matchAvgInfo = HoopMatchStatsTable::getInstance()->updateMatchAvgInfo($season,$arguments['match_type']);
        $avgLoseScore = HoopMatchStatsTable::getInstance()->updateAvgLoseScore($season,$arguments['match_type']);
        //print_r($matchAvgInfo);
        //print_r($avgLoseScore);
        $info =array_map("array_merge",$matchAvgInfo,$avgLoseScore);
        //var_dump($info);

        $rank = $this->getRank($info);

        //var_dump($rank);


        $redis = sfContext::getInstance()->getDatabaseConnection('gamespaceRedis');
        $re_key = "gs_match_avg_score";

        if($redis->setex($re_key,  3600*12*7 ,json_encode($rank))){
            echo 'updated'. "\n";
        }else{
            echo 'error ';
        }


    }


    public function getRank($avgInfo){

        $rank = array();
        $array=array('pts'=>'desc','ast'=>'desc','reb'=>'desc','tno'=>'asc','lts'=>'asc');
            foreach($array as $name=>$type){
            $tmp = $this->array_sort($avgInfo,$name,$type);
                /*
                if($name=='lts'){
                    var_dump($tmp);exit;
                }
                */
            foreach($tmp as $k => $v){
                $rank[$v['team_id']] [$name] = $k+1;
                $rank[$v['team_id']] [$name.'_score'] = round($v[$name],1);
            }

        }
        return $rank;
    }


    private  function array_sort($arr,$keys,$type='desc'){    //$arr需要排序的二维数组 ，$keys需要通过排序的字段 $type 排序的方式默认倒叙

        $keysvalue = $new_array = array();   //先定义两个数组

        foreach ($arr as $k=>$v){   //遍历二维数组 将需要排序的值 存入新数组，其中键值是$arr的键值
            $keysvalue[$k] = $v[$keys];
        }

        if($type == 'asc'){
            asort($keysvalue);   //从小到大排序 保持键值不变
        }else{
            arsort($keysvalue);  //从大到小排序 保持键值不变
        }
        reset($keysvalue);
        foreach ($keysvalue as $k=>$v){    //遍历新数组 这是新数组的键值就是原数组的键值
            $new_array[] = $arr[$k];   //$new_array[$k] = $arr[$k];
        }
        return $new_array;
    }
}