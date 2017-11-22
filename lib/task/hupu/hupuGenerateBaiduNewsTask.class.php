<?php

class hupuGenerateBaiduNewsTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'hupu'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'hupuHomepageDatabase'),
      // add your own options here
    ));

    $this->namespace        = 'hupu';
    $this->name             = 'generate-baidu-news';
    $this->briefDescription = 'Generate news for baidu - hao123.com';
    $this->detailedDescription = <<<EOF
The [hupuGenerateBaiduNews|INFO] task does things.
Call it with:

  [php symfony hupu:generate-baidu-news|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);
    $this->dateinterface = new importantMatch();
    
    $newsInfo = false;
    $news = $this->getHupuNews();
    if($news !== false)
        $newsInfo = file_put_contents(sfConfig::get('sf_web_dir') . "/xml/baidu_news.json", $news);

    if($newsInfo) {
        echo "Generate News Success \n";
    }
    else {
        echo "Generate News Failed \n";
    }

    $matchInfo = false;
    $importantMatches = $this->getImportantMatches();
    if($importantMatches !== false)
        $matchInfo = file_put_contents(sfConfig::get('sf_web_dir') . "/xml/important_matches.json", $importantMatches);

    if($matchInfo) {
        echo "Generate Match Success \n";
    }
    else {
        echo "Generate Match Failed \n";
    }
  }

  protected function getHupuNews()
  {
     $allData = array();
     $newsInfo = array();
     $value = array();
     $newsInfo = HomepageDataTable::getInstance()->getNewsData();
     $value = base64_decode($newsInfo[0]['value']);
     $newsInfo = unserialize($value);
     
     $columnInfo = array();
     $columnInfo = $newsInfo['feature'];

     $column = array();
     $i = 0;
     foreach($columnInfo['links'] as $k=>$v) {
         $column[$i]['title'] =  $v['title'];
         $column[$i]['url'] = $v['link'];
         $column[$i]['image'] = 'http://www.hupu.com/uploads/hupu/feature/'.$v['image'];
         $i++;
     }

     foreach($columnInfo['text_links'] as $k=>$v)
     {
         $column[$i]['title'] = $v['title'];
         $column[$i]['url'] = $v['link'];
         $column[$i]['image'] = '';
         $i++;
     }

     $columnData['虎扑制造']=$column;
     $soccer = false;//file_get_contents("http://soccer.hupu.com/news/xml/hao123.js");

     if($soccer !== false) {
         $soccerData = json_decode($soccer,true);
     }
     else {
         return false;
     }

     $nba = file_get_contents("http://nba.hupu.com/api/nbaHupuDataForBaidu.php");
     if($nba !== false) {
         $nbaData = json_decode($nba,true);
     }
     else {
         return false;
     }
     $allData = array_merge($soccerData,$nbaData,$columnData);
     $allData = json_encode($allData);
     return $allData;
  }
  
  
  protected function getImportantMatches()
  {
    $todayMatches = LlMatchTable::getInstance()->getMatchs(date('Y-m-d'));
    $yesterdayMatches = LlMatchTable::getInstance()->getMatchs(date('Y-m-d', strtotime("-1 day"))); // array()
    $tomorrowMatches = LlMatchTable::getInstance()->getMatchs(date('Y-m-d', strtotime("+1 day")));

    //$yesterdayMatches = array_slice($yesterdayMatches, -5, 5);
    //$todayMatches = array_slice($todayMatches, -5, 5);
    //$tomorrowMatches = array_slice($tomorrowMatches, -5, 5);

    $importantMatches = array();
    $importantMatches['yesterday'] = $yesterdayMatches;
    $importantMatches['today'] = $todayMatches;
    $importantMatches['tomorrowMatches'] = $tomorrowMatches;
    $i = 0;
    $matches = array();

    foreach ($importantMatches as $key => $value)
    {
        $j = 0;
        if(count($value) == 0)
        {
            $matches["$i"] = "null";
        }
        foreach ($value as $k => $v)
        {
            $starttime = $v['start_time'];
            $race = substr($v['name'], 0, strpos($v['name'], ' '));
            $vs = substr($v['name'], strpos($v['name'], ' ') + 1);

            $begin = strtotime($starttime);
            $length = $v['match_time'] * 60;
            $end = $begin + $length;

            $matches[$i][$j]['race'] = $race;
            $matches[$i][$j]['vs'] = $vs;
            $matches[$i][$j]['url'] = $v['link_to'];
            $matches[$i][$j]['start'] = strtotime($v['start_time']);

            if(in_array($v['match_time'],array(1,2,3)))
            {
                            /////////当match_time=1,2,3,时代表状态,1代表结束，2代表正在进行，3代表未开始
                            switch($v['match_time'])
                            {
                                case 1;
                                    $matches[$i][$j]['status'] = "已结束";
                                    $matches[$i][$j]['url'] = $v['link_to_report'];
                                break;
                                case 2:
                                    $matches[$i][$j]['status'] = "正在进行中";
                                break;
                                case 3:
                                    $matches[$i][$j]['start_time'] = strtotime($v['start_time']);
                                break;

                            }
                            /////////
            }else{
                          if (time() < $begin)
                            {
                                $matches[$i][$j]['start_time'] = strtotime($v['start_time']);
                            }
                            elseif (time() < $end)
                            {
                                $matches[$i][$j]['status'] = "正在进行中";
                            }
                            else
                            {
                                $matches[$i][$j]['status'] = "已结束";
                                $matches[$i][$j]['url'] = $v['link_to_report'];;
                            }
            }
            
            $j++;
        }
        $i++;
    }

    $interfaceYesterday = $this->getdatematchs(date('Y-m-d', strtotime("-1 day")));
    $matches[0] = $this->mergeData($matches[0], $interfaceYesterday);
    
    $interfaceToday     = $this->getdatematchs(date('Y-m-d'));
    $matches[1] = $this->mergeData($matches[1], $interfaceToday);
    
    $interfaceTommary   = $this->getdatematchs(date('Y-m-d', strtotime("+1 day")));
    $matches[2] = $this->mergeData($matches[2], $interfaceTommary);
    
    return json_encode($matches);
  }
  
  /**
   *合并数据 并截取最近的五场比赛
   */
  private  function mergeData($source, $arr)
  {
      if(!is_array($source)) { $source = array(); }
      if(!empty($arr) && count($arr))
      {   //合并
          foreach($arr as $k=>$v)
          {
              array_push($source, $v);
          }
          //时间排序,最近的五场时间
          foreach($source as $k=>$v)
          {
              $timedata[] = $v['start'];
          }
          sort($timedata);
          $timedata = array_reverse(array_slice($timedata, -5, 5));
          //最近五场比赛
          foreach($timedata as $tk=>$tv)
          {
              foreach($source as $k=>$v)
              {
                  if($v['start'] == $tv)
                  {
                      $least5matches[] = $v;
                      unset($source[$k]);
                      break;
                  }
              }
          }
          return $least5matches;
      }
      return array_slice($source, 0, 5);
  }


  /**
   *获取接口数据(nba，cba, soccer)
   * @param type $date 
   */
  protected function getdatematchs($date)
  {
    $soccerImportantMatch = $this->dateinterface->getSoccerImportantMatches($date);
    $nbaImportantMatch    = $this->dateinterface->getNbaImportantMatches($date);
    $cbaImportantMatch    = $this->dateinterface->getCbaImportantMatches($date);
    
    $soccerImportant = array();
    $nbaImportant    = array();
    $cbaImportant    = array();

    //足球数据
    if(is_array($soccerImportantMatch) && count($soccerImportantMatch))
    {
        foreach($soccerImportantMatch as $k=>$v)
        {
            $soccerImportant[] =  $this->dealSoccerData($v);
        }
    }
    //nba数据
    if(is_array($nbaImportantMatch) && count($nbaImportantMatch))
    {
        foreach($nbaImportantMatch as $k=>$v)
        {
            $nbaImportant[] = $this->dealNbaData($v);
            array_push($soccerImportant, $this->dealNbaData($v));
        }
    }
    //cba数据
    if(is_array($cbaImportantMatch) && count($cbaImportantMatch))
    {
        foreach($cbaImportantMatch as $k=>$v)
        {
            $cbaImportant[] = $this->dealCbaData($v);
            array_push($soccerImportant, $this->dealCbaData($v));
        }
    }
    $dayData = $soccerImportant;
    return $dayData;
   }
   //处理足球数据
   protected function dealSoccerData($data)
   {
        $soccer = array();
        if(!empty($data) && count($data))
        {
                $soccer['race'] = '足球';
                $soccer['start'] = $data['startTime'];
                switch($data['ended'])
                {
                    case 2:
                        $soccer['vs']     = $data['homeCnName'].' '.$data['homeScore'].':'.$data['awayScore'].' '.$data['awayCnName'];
                        $soccer['status'] = '已结束';
                        $soccer['url']    = "http://g.hupu.com/soccer/report_".$data['aid'].".html";
                    break;
                    case 1:
                        $soccer['vs']     = $data['homeCnName'].' '.$data['homeSorce'].$data['awayScore'].' '.$data['awayCnName'];
                        $soccer['status'] = '进行中';
                        $soccer['url']    = "http://g.hupu.com/soccer/preview_".$data['aid'].".html";
                    break;
                    default:
                        $soccer['vs']     = $data['homeCnName'].'vs'.$data['awayCnName'];
                        $soccer['start_time'] = date('Y-m-d H:i:s', $data['startTime']);
                        $soccer['url']    = "http://g.hupu.com/soccer/preview_".$data['aid'].".html";
                }
        }
        return $soccer;
    }
    //处理nba数据
    protected function dealNbaData($data)
    {
        $nba = array();
        if(!empty($data) && count($data))
        {
            $nba['race'] = 'NBA';
            $nba['start'] = strtotime($data['time']);
                switch($data['status'])
                {
                    case 1:
                        $nba['vs']     = $data['home']['name'].' '.$data['home']['score'].':'.$data['away']['score'].' '.$data['away']['name'];
                        $nba['status'] = '已结束';
                        $nba['url']    = $data['recap_link'];
                    break;
                    case 2:
                        $nba['vs']     = $data['home']['name'].' '.$data['home']['score'].':'.$data['away']['score'].' '.$data['away']['name'];
                        $nba['status'] = '进行中';
                        $nba['url']    = $data['boxscore_link'];
                    break;
                    default:
                        $nba['vs']     = $data['home']['name'].'vs'.$data['away']['name'];
                        $nba['start_time'] = $data['time'];
                        $nba['url']    = $data['boxscore_link'];
                }
            }
        return $nba;
    }
   //处理cba数据
   protected function dealCbaData($data)
   {
       $cba = array();
       if(!empty($data) && count($data))
       {
           $cba['race'] = 'CBA';
           $cba['start'] = intval($data['gametime']);
              switch($data['status'])
              {
                  case 2:
                      $cba['vs']     = $data['home']['teamname'].' '.$data['home_score'].':'.$data['away_score'].' '.$data['away']['teamname'];
                      $cba['status'] = '已结束';
                      $cba['url']    = $data['recap']['url'];
                  break;
                  case 1:
                      $cba['vs']     = $data['home']['teamname'].' '.$data['home_score'].':'.$data['away_score'].' '.$data['away']['teamname'];
                      $cba['status'] = '进行中';
                      $cba['url']    = $data['boxscore'];
                  break;
                  default:
                      $cba['vs']     = $data['home']['teamname'].'vs'.$data['away']['teamname'];
                      $cba['start_time'] = date('Y-m-d H:i:s', $data['gametime']);
                      $cba['url']    = $data['boxscore'];
              }
       }
       return $cba;   
   }
}
