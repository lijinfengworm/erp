<?php
class generateBBSThreadTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('date', sfCommandArgument::OPTIONAL, 'match date', date('Y-m-d')),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'gamespace'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'gamespace'),
            // add your own options here
        ));

        $this->namespace = 'gamespace';
        $this->name = 'generateBBSThread';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [generateBBSThread|INFO] task does things.
Call it with:

  [php symfony gamespace:generateBBSThread|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        // add your code here
        $today = isset($arguments['date']) ? $arguments['date'] :date('Y-m-d');

        if ($this->articleExist($today)) {
            $firstArticle = $this->getTodayFirstArticle($today);
            $forumLink = $firstArticle->getForumLink();
            //获取综述贴标题内容
            $matchIds = $this->getAllArticleMatchId($today);
            $data = $this->getThreadContent($matchIds);

            $content = $this->getHtml($data);//$action->getPartial("hoop_match/matchBbs", $data);
            $title = $firstArticle->getTitle();
            if ($forumLink) {
                //更新bbs综述贴
                $bbsUrl = $this->writeReportToBbs($content, $title, $forumLink);
            } else {
                //创建综述贴
                $bbsUrl = $this->writeReportToBbs($content, $title, '');
            }
            echo $bbsUrl . PHP_EOL;
            //当天所有需要更新的战报
            $articleIds = $this->getNoForumLinkArticleIds($today);

            if (!empty($articleIds)) {
                //线下
//                $gdcUpdateReacapApi = 'http://192.168.8.15:8080/nba/match/articles';
                //线上
                $gdcUpdateReacapApi = 'http://gdc.hupu.com/nba/match/articles';

                $paramData = array();
                foreach ($articleIds as $one) {
                    array_push($paramData, array('id' => intval($one), 'forum_link' => $bbsUrl));

                }
                echo json_encode($paramData); echo PHP_EOL;
                $this->simpleCurl($gdcUpdateReacapApi, json_encode($paramData));


            } else
            {
                echo 'no empty form_link to update!';
            }

        } else {
            echo 'no article';
        }
    }


    /**
     * @param $today
     * @return bool
     * @desc 今天是否有战报
     */

    public function articleExist($today)
    {
        $exist = false;

        $matches = HoopMatchTable::getInstance()->getMatchListByDateApi($today);
        if (empty($matches)) return $exist;

        foreach ($matches as $match) {
            if ($match->getIsArticle()) {
                return true;
            }
        }

        return $exist;
    }

    /**
     * @desc 某天所有有战报的比赛ID
     */
    public function getAllArticleMatchId($today)
    {
        $matchIds = array();

        $matches = HoopMatchTable::getInstance()->getMatchListByDateApi($today);
        if (empty($matches)) return $matchIds;

        foreach ($matches as $match) {
            if ($match->getIsArticle()) {
                array_push($matchIds, $match->getId());
            }
        }

        return $matchIds;
    }

    /**
     * @param $today
     * @desc 今天第一篇战报
     */
    public function getTodayFirstArticle($today)
    {
        $firstArticle = array();
        $matchIds = $this->getAllArticleMatchId($today);
        if (!empty($matchIds)) {
            $articles = HoopMatchArticleTable::getInstance()->getAllDayArticle($matchIds);
            foreach ($articles as $article) {
                if ($article->isLegal()) {
                    return $article;
                }
            }
        }
        return $firstArticle;
    }

    /**
     * @param $ids 今天所有比赛ID
     * @desc 今天所有战报组合综述贴内容
     */
    public function getThreadContent($ids)
    {
        $articles = HoopMatchArticleTable::getInstance()->getByMatchIds($ids);

        //季后赛显示大比分及在本联盟排名
        $ranks = '';
        $vsscores = '';
        $homeWins = $homeLosts = $awayWins = $awayLosts = array();
        foreach ($articles as $info) {
            $match = HoopMatchTable::getInstance()->find($info->getMatchId());
            $hTeamInfo = HoopStandingTable::getInstance()->getBySeasonAndTeamId($match->getSeason(), $match->getMatchType(), $match->getHomeTeamId());
            $aTeamInfo = HoopStandingTable::getInstance()->getBySeasonAndTeamId($match->getSeason(), $match->getMatchType(), $match->getAwayTeamId());

            $homeNames[] = $match->getHomeTeamNameZh();
            $awayNames[] = $match->getAwayTeamNameZh();
            $homeScores[] = $match->getHomeScore();
            $awayScores[] = $match->getAwayScore();
            if ($hTeamInfo) {
                $homeWins[] = $hTeamInfo->getWon();
                $homeLosts[] = $hTeamInfo->getLost();
            }else
            {
                $homeWins[] = 0;
                $homeLosts[] = 0;
            }

            if ($aTeamInfo) {
                $awayWins[] = $aTeamInfo->getWon();
                $awayLosts[] = $aTeamInfo->getLost();
            }else
            {
                $awayWins[] = 0;
                $awayLosts[] = 0;
            }
            $matchIds[] = $info->getMatchId();
            $contents[] = $info->getContent();
            $homeEngNames[] = strtolower($match->getHomeTeam()->getEngName());
            $awayEngNames[] = strtolower($match->getAwayTeam()->getEngName());
            $imgs[] = $info->getImg();
            $videoLinks[] = $info->getVideoLink();
            $titles[] = $info->getTitle();
            $forumLink[] = $info->getForumLink();
            //比赛类型（页面显示判断用）
            $types[] = $match->getMatchType();
            if ($match->getMatchType() == HoopMatch::SEASON_PLAYOFF) {
                $homeTeam = $match->getHomeTeam();
                $awayTeam = $match->getAwayTeam();
                //常规赛排名
                $homeRank = $homeTeam->getConferenceRank($match->getSeason(), HoopMatch::SEASON_REGULAR);
                $awayRank = $awayTeam->getConferenceRank($match->getSeason(), HoopMatch::SEASON_REGULAR);
                $ranks[] = array("home" => $homeRank, "away" => $awayRank);
                //季后赛两队大比分
                $scores = $match->getTeamVsScore($match->getHomeTeamId(), $match->getAwayTeamId(), $match->getSeason(), HoopMatch::SEASON_PLAYOFF);
                $vsscores[] = array('home' => $scores[$match->getHomeTeamId()], 'away' => $scores[$match->getAwayTeamId()]);
            }
        }

        return array("homeName" => $homeNames,
            "awayName" => $awayNames,
            "homeScore" => $homeScores,
            "awayScore" => $awayScores,
            "homeWin" => $homeWins,
            "homeLost" => $homeLosts,
            "awayWin" => $awayWins,
            "awayLost" => $awayLosts,
            "matchId" => $matchIds,
            "content" => $contents,
            "count" => count($articles),
            "title" => $titles,
            "homeEngName" => $homeEngNames,
            "awayEngName" => $awayEngNames,
            "forumUrl" => end($forumLink),
            "img" => $imgs,
            "videoLink" => $videoLinks,
            'type' => $types,
            'rank' => $ranks,
            'score' => $vsscores,
        );

    }

    /**
     * @param $today
     * @desc 今天要更新的战报ID
     */
    public function getNoForumLinkArticleIds($today)
    {
        $articleIds = array();
        $matchIds = $this->getAllArticleMatchId($today);
        if (!empty($matchIds)) {
            $articles = HoopMatchArticleTable::getInstance()->getAllDayArticle($matchIds);
            foreach ($articles as $article) {
                if ($article->isLegal() && !$article->getForumLink()) {
                    array_push($articleIds, $article->getId());
                }
            }
        }
        return $articleIds;
    }

    /**
     * @param $data
     * @return string
     */


    public function getHtml($data)
    {
        $html = '';
        if($data['count'] == 1)
        {
            $html .= $data['homeName'][0] . '(' . $data['homeScore'][0] . '-' . $data['awayScore'][0] . ')' . $data['awayName'][0] . '---' . $data['title'][0];;
        }else
        {
            for($i = 0; $i < $data['count']; $i++)
            {
                $p = '';
                $p .= '<p>';
                if(empty($data['forumUrl']))
                {
                    $p .= $data['homeName'][$i].'('. $data['homeScore'][$i] . '-' .$data['awayScore'][$i]. ')' . $data['awayName'][$i] . '---' . $data['title'][$i] ;

                }else
                {
                    $p .= '<a href="'.$data['forumUrl'].'#match_'.$data['matchId'][$i].'">'.$data['homeName'][$i].'('. $data['homeScore'][$i] . '-' .$data['awayScore'][$i]. ')' . $data['awayName'][$i] . '---' . $data['title'][$i] . '</a>';

                }
                $p .= '</p>';
                $html .= $p;
            }
        }
        for($i = 0; $i < $data['count']; $i++)
        {
            if ($data['type'][$i] == HoopMatch::SEASON_PLAYOFF) {
                $homeCss = 'scoreC444'; $awayCss = 'scoreC444';
                if ($data['score'][$i]['home'] > $data['score'][$i]['away']) {
                    $homeCss = 'scoreRed';
                }else if($data['score'][$i]['home'] < $data['score'][$i]['away']){
                    $awayCss = 'scoreRed';
                }
            }
            $div = '';
            $div .= '<div>';
            $div .= '<p>';
            $div .= '<div align="center">';
            $div .= '<blockquote><p><b>';
            $div .= '<a id="match_'.$data['matchId'][$i].'" name="match_'.$data['matchId'][$i].'" href="'.HoopMatchArticle::WWW_TEAM . '/' . $data['homeEngName'][$i] .'" target="_blank">'.$data['homeName'][$i].'</a>';
            $div .= '(';
            if($data['type'][$i] != HoopMatch::SEASON_PLAYOFF)
            {
                $div .= $data['homeWin'][$i] . '胜' . $data['homeLost'][$i] .'负';

            }else
            {
                $div .= '<span class="'.$homeCss.'">'.$data['score'][$i]['home'].'</span>&nbsp;'. $data['rank'][$i]['home'];

            }
            $div .= ')';
            $div .= $data['homeScore'][$i] . '-' . $data['awayScore'][$i];
            $div .= '<a href="'.HoopMatchArticle::WWW_TEAM . '/' . $data['awayEngName'][$i].'" target="_blank">'.$data['awayName'][$i].'</a>';

            $div .= '(';
            if($data['type'][$i] != HoopMatch::SEASON_PLAYOFF)
            {
                $div .= $data['awayWin'][$i] . '胜' . $data['awayLost'][$i] .'负';

            }else
            {
                $div .= '<span class="'.$awayCss.'">'.$data['score'][$i]['away'].'</span>&nbsp;'. $data['rank'][$i]['away'];

            }
            $div .= ')';
            $div .= '<a href="'.HoopMatchArticle::GAMESPACE_URL_PREFIX.'/nba/boxscore_'.$data['matchId'][$i].'.html" target="_blank">技术统计</a>';
            $div .= ' | ';
            $div .= '<a href="'.HoopMatchArticle::GAMESPACE_URL_PREFIX.'/nba/playbyplay_'.$data['matchId'][$i].'.html" target="_blank">直播实录</a>';
            if($data['videoLink'][$i])
            {
                $div .= ' | <a href="'.$data['videoLink'][$i].'" target="_blank">视频集锦</a>';
            }

            $div .= '</b></p></blockquote>';
            $div .= '</div>';
            $div .= '</p>';
            if($data['img'][$i])
            {
                $div .= '<p style="float:right;">';
                $div .= '<img src="'.HoopMatchArticle::UPLOAD_IMAGE_URL_PREFIX . '/' . $data['img'][$i].'"></img>';
                $div .= '</p>';
            }
            $div .= $data['content'][$i];
            $div .= '</div>';
            $html .= $div;
        }
        return $html;
    }

    function simpleCurl($url, $data, $method='PUT')
    {
        // 创建一个新cURL资源
        $ch = curl_init(); //初始化CURL句柄
        //设置请求的URL
        curl_setopt($ch, CURLOPT_URL, $url);
        //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        //设置请求方式
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        //设置HTTP头信息
        curl_setopt($ch,CURLOPT_HTTPHEADER, array("X-HTTP-Method-Override: $method", "Content-Type: application/json"));
        //设置提交的字符串json
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $document = curl_exec($ch);

        if(!curl_errno($ch))
        {
            $info = curl_getinfo($ch);
            echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
            $return = json_decode($document, true);
            foreach($return as $one)
            {
                if($one[1] !== 1)
                {
                    echo 'unsuccesssful articleID['. $one[0] .  ']'.PHP_EOL;
                }
            }
        } else
        {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);

        return $document;
    }


    /**
     *
     * 在添加战报时，生成相应bbs帖子
     * @param string $title    战报标题
     * @param string $content  战报内容
     * @param string $bbsUrl   bbs URL
     * @return string  返回相应bbs帖子URL
     */
    public function writeReportToBbs($content, $title = '', $bbsUrl = ''){
        $threadAuthor   = '虎扑篮球'; // 用户名是什么
        $threadAuthorId = '25948'; // 对应的 author id
        $threadFID      = '130';
        $apiname = 'bbsthreads';
        $appid   = 38;
        $appkey = '4df99a6f47d707c';


        $content = stripslashes($content);
        $content = str_replace('&lt;', '<', $content);
        $content = str_replace('&gt;', '>', $content);

        $post_data['content'] = $content;

        if($bbsUrl){
            preg_match_all('/\/(\d*).html/s', $bbsUrl, $threadId);
            $threadId  = $threadId['1'][0];
        }else{
            $threadId = 0;
        }

        if ((int)$threadId > 0) {
            $post_data['tid'] = $threadId;
            $post_data['charset'] = 'utf-8';
            $post_data['a']='edit';

            $result = SnsInterface::getContents($apiname,$appid,$appkey,$post_data, 'post');
            var_dump($result);
        }else{
            if(!empty($title)) {
                $post_data['title'] = date('m月d日') . 'NBA 比赛综述：' . $title;
            }
            $post_data['fid'] = $threadFID;
            $post_data['authorid'] = $threadAuthorId;
            $post_data['author'] = $threadAuthor;
            $post_data['a']='create';
            $post_data['charset'] = 'utf-8';
            $threadId = SnsInterface::getContents($apiname,$appid,$appkey,$post_data, 'post');
        }
        if(intval($threadId)>0)
        {
            $bbsUrl = 'http://bbs.hupu.com/'.$threadId.'.html';
        }


        return $bbsUrl;
    }
}