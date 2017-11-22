<?php

class user_favorTask extends sfBaseTask
{
    protected function configure()
    {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','eric'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            // add your own options here
        ));

        $this->namespace        = 'eric';
        $this->name             = 'user_favor';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
分析用户主队信息
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {

        sfContext::createInstance($this->configuration);    
        $databaseManager = new sfDatabaseManager($this->configuration);
        
         $this->log('start');
        $this->lockDir = '/tmp';
        $this->lockFile = 'autoRun_favor.lock';
            //加锁
        if(!$this->enterLock())
        {
            exit();
        }        
        
        $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $memcache_key = "user_team_process_no_new_027";
       
        if($memcache->get($memcache_key) !== false) {
            $start = $memcache->get($memcache_key);
        } else {
            $start = 0;
            $memcache->set($memcache_key, 0, 0, 0);
        }
        
        $types = array("NBA"=>"favor", "CBA" => "cba",  "西甲" => "laliga", "意甲" => "seriea", "德甲" => "bundesliga", "法甲" => "ligue1", "英超" => "epl");
        $limit = 1000;
        while (1)
        {
            $rs = fbd_personTable::getInstance()->getUsersWithFavor($start, $limit);
            if(empty($rs))
            {
                break;
            }
            
            foreach($rs as $user) {
            //$this->log($user);
            
                foreach($types as $k => $v) {
                    $info = array();
                    $name = $v;
                    if($v == "favor") {
                        $name = "nba";
                    }

                    $teamname = "";

                    if($teamname = $this->getTeamConfig($name, $user[$v])) {
                        $info["object_name"] = $teamname;
                    }

                    if(!$teamname) {
                        $this->log($user["uid"].'null--'.$name.'--'.$user[$v]);
                        continue;
                    }

                    $info["userid"] = $user["uid"];
                    $info["username"] = mb_convert_encoding($user["username"], 'utf-8', 'gbk');
                    $info["action"] = "hometeam";
                    $this->addLog($info);
                    $this->log($info['userid']);
                    
                    $start = $info['userid'];
                    $memcache->set($memcache_key, $info['userid'], 0, 0);
                    
                    //$this->log( $info['userid'] .":{$k}主队 : $v-----> " . $user[$v] ." - " . $this->getTeamConfig($name, $user[$v]) . "\n");
                }
            }
            //exit;
        }
        

    }
    /**
	 * 加锁，阻止本程序的第二个实例启动运行。
	 */
	private function enterLock()
	{
		$this->log( "启动加锁" );
		$this->fnLock = $this->lockDir . DIRECTORY_SEPARATOR . $this->lockFile;
		$this->fpLock = fopen( $this->fnLock, 'w+' );
		if ( $this->fpLock ) {
			if ( flock( $this->fpLock, LOCK_EX | LOCK_NB ) ) {
				return true;
			}
			fclose( $this->fpLock );
			$this->fpLock = null;
		}
		$this->log( "加锁失败" );
		return false;
	}
    function addLog($info) {
        $info["site"] = "bbs";
        $info["userfrom"] = "hupu";

        $keys = array("site", "userfrom", "userid", "username", "object_name", "action", "url", "time");
        $arr = array();

        $log = new ErLog();

        foreach($info as $k=>$v) {
            if(in_array($k, $keys) && $v) {
                $arr[$k] = urldecode($v);
                $names = explode("_", $k);
                $i = 1;
                $setname = "set";
                foreach($names as &$name) {
                    $setname .= ucfirst($name);
                    $i ++;
                }

                $log->$setname(urldecode($v));
            }
        }

        $log->save();
    }

    private function getTeamConfig($name, $id) {
        $teamsInfoConfig = array();
        $teamsInfoConfig['nba']=array(
            '1'=>array(
                'ename'=>'Celtics',
                'name'=>'凯尔特人',
                'fid'=>'82',
                'id'=>'1',
                'allname'=>'波士顿凯尔特人',
                'home'=>'TD 北岸花园',
                'eallname'=>'Boston Celtics',
            ),
            '2'=>array(
                'ename'=>'Heat',
                'name'=>'热火',
                'fid'=>'77',
                'id'=>'2',
                'allname'=>'迈阿密热火',
                'home'=>'美国航空球馆',
                'eallname'=>'Miami Heat',
            ),
            '3'=>array(
                'ename'=>'Nets',
                'name'=>'篮网',
                'fid'=>'84',
                'id'=>'3',
                'allname'=>'新泽西篮网',
                'home'=>'大陆航空球馆',
                'eallname'=>'New Jersey Nets',
            ),
            '4'=>array(
                'ename'=>'Knicks',
                'name'=>'尼克斯',
                'fid'=>'92',
                'id'=>'4',
                'allname'=>'纽约尼克斯',
                'home'=>'麦迪逊广场花园',
                'eallname'=>'New York Knicks',
            ),
            '5'=>array(
                'ename'=>'Magic',
                'name'=>'魔术',
                'fid'=>'88',
                'id'=>'5',
                'allname'=>'奥兰多魔术',
                'home'=>'TD 水屋中心',
                'eallname'=>'Orlando Magic',
            ),
            '6'=>array(
                'ename'=>'76ers',
                'name'=>'76人',
                'fid'=>'124',
                'id'=>'6',
                'allname'=>'费城76人',
                'home'=>'瓦科维亚中心',
                'eallname'=>'Philadelphia 76ers',
            ),
            '7'=>array(
                'ename'=>'Wizards',
                'name'=>'奇才',
                'fid'=>'86',
                'id'=>'7',
                'allname'=>'华盛顿奇才',
                'home'=>'Verizon 中心',
                'eallname'=>'Washington Wizards',
            ),
            '8'=>array(
                'ename'=>'Hawks',
                'name'=>'老鹰',
                'fid'=>'126',
                'id'=>'8',
                'allname'=>'亚特兰老鹰',
                'home'=>'菲利普斯球馆',
                'eallname'=>'Atlanta Hawks',
            ),
            '9'=>array(
                'ename'=>'Bulls',
                'name'=>'公牛',
                'fid'=>'68',
                'id'=>'9',
                'allname'=>'芝加哥公牛',
                'home'=>'联航中心',
                'eallname'=>'Chicago Bulls',
            ),
            '10'=>array(
                'ename'=>'Cavaliers',
                'name'=>'骑士',
                'fid'=>'85',
                'id'=>'10',
                'allname'=>'克利夫兰骑士',
                'home'=>'速贷体育馆',
                'eallname'=>'Cleveland Cavaliers',
            ),
            '11'=>array(
                'ename'=>'Pistons',
                'name'=>'活塞',
                'fid'=>'87',
                'id'=>'11',
                'allname'=>'底特律活塞',
                'home'=>'奥本山宫殿球馆',
                'eallname'=>'Detroit Pistons',
            ),
            '12'=>array(
                'ename'=>'Pacers',
                'name'=>'步行者',
                'fid'=>'74',
                'id'=>'12',
                'allname'=>'印第安纳步行者',
                'home'=>'康赛卡球场',
                'eallname'=>'Indiana Pacers',
            ),
            '13'=>array(
                'ename'=>'Bucks',
                'name'=>'雄鹿',
                'fid'=>'110',
                'id'=>'13',
                'allname'=>'密尔沃基雄鹿',
                'home'=>'布拉德利中心',
                'eallname'=>'Milwaukee Bucks',
            ),
            '14'=>array(
                'ename'=>'Hornets',
                'name'=>'鹈鹕',
                'fid'=>'89',
                'id'=>'14',
                'allname'=>'新奥尔良鹈鹕',
                'home'=>'新奥尔良球馆',
                'eallname'=>'New Orleans Hornets',
            ),
            '15'=>array(
                'ename'=>'Raptors',
                'name'=>'猛龙',
                'fid'=>'90',
                'id'=>'15',
                'allname'=>'多伦多猛龙',
                'home'=>'加拿大航空球馆',
                'eallname'=>'Toronto Raptors',
            ),
            '16'=>array(
                'ename'=>'Mavericks',
                'name'=>'小牛',
                'fid'=>'80',
                'id'=>'16',
                'allname'=>'达拉斯小牛',
                'home'=>'美国航空中心',
                'eallname'=>'Dallas Mavericks',
            ),
            '17'=>array(
                'ename'=>'Nuggets',
                'name'=>'掘金',
                'fid'=>'72',
                'id'=>'17',
                'allname'=>'丹佛掘金',
                'home'=>'百事中心',
                'eallname'=>'Denver Nuggets',
            ),
            '18'=>array(
                'ename'=>'Rockets',
                'name'=>'火箭',
                'fid'=>'44',
                'id'=>'18',
                'allname'=>'休斯顿火箭',
                'home'=>'丰田中心',
                'eallname'=>'Houston Rockets',
            ),
            '19'=>array(
                'ename'=>'Grizzlies',
                'name'=>'灰熊',
                'fid'=>'128',
                'id'=>'19',
                'allname'=>'孟菲斯灰熊',
                'home'=>'联邦特快运动中心',
                'eallname'=>'Memphis Grizzlies',
            ),
            '20'=>array(
                'ename'=>'Timberwolves',
                'name'=>'森林狼',
                'fid'=>'76',
                'id'=>'20',
                'allname'=>'明尼苏达森林狼',
                'home'=>'标靶中心',
                'eallname'=>'Minnesota Timberwolves',
            ),
            '21'=>array(
                'ename'=>'Spurs',
                'name'=>'马刺',
                'fid'=>'105',
                'id'=>'21',
                'allname'=>'圣安东尼奥马刺',
                'home'=>'AT&T 中心',
                'eallname'=>'San Antonio Spurs',
            ),
            '22'=>array(
                'ename'=>'Jazz',
                'name'=>'爵士',
                'fid'=>'70',
                'id'=>'22',
                'allname'=>'犹他爵士',
                'home'=>'三角洲中心',
                'eallname'=>'Utah Jazz',
            ),
            '23'=>array(
                'ename'=>'Kings',
                'name'=>'国王',
                'fid'=>'79',
                'id'=>'23',
                'allname'=>'萨克拉门托国王',
                'home'=>'ARCO 球馆',
                'eallname'=>'Sacramento Kings',
            ),
            '24'=>array(
                'ename'=>'Lakers',
                'name'=>'湖人',
                'fid'=>'81',
                'id'=>'24',
                'allname'=>'洛杉矶湖人',
                'home'=>'斯台普斯中心',
                'eallname'=>'Los Angeles Lakers',
            ),
            '25'=>array(
                'ename'=>'Blazers',
                'name'=>'开拓者',
                'fid'=>'96',
                'id'=>'25',
                'allname'=>'波特兰开拓者',
                'home'=>'玫瑰花园',
                'eallname'=>'Portland Trail Blazers',
            ),
            '26'=>array(
                'ename'=>'Suns',
                'name'=>'太阳',
                'fid'=>'71',
                'id'=>'26',
                'allname'=>'菲尼克斯太阳',
                'home'=>'美国空中走廊中心',
                'eallname'=>'Phoenix Suns',
            ),
            '27'=>array(
                'ename'=>'Thunder',
                'name'=>'雷霆',
                'fid'=>'108',
                'id'=>'27',
                'allname'=>'俄克拉荷马雷霆',
                'home'=>'钥匙球馆',
                'eallname'=>'Oklahoma City',
            ),
            '28'=>array(
                'ename'=>'Warriors',
                'name'=>'勇士',
                'fid'=>'102',
                'id'=>'28',
                'allname'=>'金州勇士',
                'home'=>'甲骨文球馆',
                'eallname'=>'Golden State Warriors',
            ),
            '29'=>array(
                'ename'=>'Clippers',
                'name'=>'快船',
                'fid'=>'127',
                'id'=>'29',
                'allname'=>'洛杉矶快船',
                'home'=>'斯台普斯中心',
                'eallname'=>'Los Angeles Clippers',
            ),
            '30'=>array(
                'ename'=>'Bobcats',
                'name'=>'山猫',
                'fid'=>'125',
                'id'=>'30',
                'allname'=>'夏洛特山猫',
                'home'=>'山猫球馆',
                'eallname'=>'Charlotte Bobcats',
            ),      
        );
        $teamsInfoConfig['cba'] = array(
            '1'=>array(
                'name'=>'广东华南虎',
                'allname'=>'广东华南虎',
                'fid'=>'218'
            ),
            '2'=>array(
                'name'=>'八一火箭',
                'allname'=>'八一火箭',
                'fid'=>'757'
            ),
            '3'=>array(
                'name'=>'北京鸭',         
                'allname'=>'北京鸭',
                'fid'=>'2297'
            ),
            '4'=>array(
                'name'=>'江苏龙',        
                'allname'=>'江苏龙',
                'fid'=>'221'
            ),
            '5'=>array(
                'name'=>'新疆飞虎',         
                'allname'=>'新疆飞虎',
                'fid'=>'866'
            ),
            '7'=>array(
                'name'=>'辽宁捷豹',         
                'allname'=>'辽宁捷豹',
                'fid'=>'2201'
            ),
            '8'=>array(
                'name'=>'吉林东北虎',         
                'allname'=>'吉林东北虎',
                'fid'=>'1800'
            ),
            '9'=>array(
                'name'=>'上海大鲨鱼',         
                'allname'=>'上海大鲨鱼',
                'fid'=>'1499'
            ),
            '10'=>array(
                'name'=>'福建鲟',         
                'allname'=>'福建中华鲟',
                'fid'=>'2691'
            ),
            '11'=>array(
                'name'=>'浙江金牛',         
                'allname'=>'浙江金牛',
                'fid'=>'2957'
            ),
            '12'=>array(
                'name'=>'山东金狮',         
                'allname'=>'山东金狮',
                'fid'=>'2254'
            ),
            '13'=>array(
                'name'=>'佛山龙狮',         
                'allname'=>'佛山龙狮',
                'fid'=>'2977'
            ),
            '14'=>array(
                'name'=>'东莞烈豹',         
                'allname'=>'东莞烈豹',
                'fid'=>'2519'
            ),
            '15'=>array(
                'name'=>'山西猛龙',         
                'allname'=>'山西猛龙',
                'fid'=>'1215'
            ),
            '16'=>array(
                'name'=>'浙江广厦猛狮',         
                'allname'=>'浙江广厦猛狮',
                'fid'=>'2368'
            ),
            '17'=>array(
                'name'=>'天津金狮',         
                'allname'=>'天津金狮',
                'fid'=>'2976'
            ),
            '18'=>array(
                'name'=>'青岛雄鹰',         
                'allname'=>'青岛雄鹰',
                'fid'=>'2978'
            )                                                                                                               
        );
        $teamsInfoConfig['laliga'] = array(
            '1'=>array(
                'ename'=>'Barcelona',
                'name'=>'巴萨',
                'fid'=>'2544',
                'id'=>'1',
                'allname'=>'巴塞罗那',
                'home'=>'诺坎普球场',
                'eallname'=>'',
            ),
            '2'=>array(
                'ename'=>'Mallorca',
                'name'=>'马洛卡',
                'id'=>'2',
                'allname'=>'皇家马洛卡',
                'home'=>'伊比利亚之星球场',
                'eallname'=>'',
            ),
            '3'=>array(
                'ename'=>'Real Madrid',
                'name'=>'皇马',
                'fid'=>'2543',
                'id'=>'3',
                'allname'=>'皇家马德里',
                'home'=>'伯纳乌球场',
                'eallname'=>'',
            ),
            /*'4'=>array(
             *       'ename'=>'Racing Santander',
             *               'name'=>'桑坦德',
             *                       'id'=>'4',
             *                               'allname'=>'桑坦德竞技',
             *                                       'home'=>'沙丁鱼人球场',
             *                                               'eallname'=>'',
             *                                                   ),*/
            '5'=>array(
                'ename'=>'Espanyol',
                'name'=>'西班牙人',
                'id'=>'5',
                'allname'=>'西班牙人',
                'home'=>'科内利亚-埃尔普拉特球场',
                'eallname'=>'',
            ),
            '6'=>array(
                'ename'=>'Real Sociedad',
                'name'=>'皇家社会',
                'id'=>'6',
                'allname'=>'皇家社会',
                'home'=>'阿诺埃塔球场',
                'eallname'=>'',
            ),
            '7'=>array(
                'ename'=>'Real Zaragoza',
                'name'=>'萨拉戈萨',
                'id'=>'7',
                'allname'=>'皇家萨拉戈萨',
                'home'=>'拉罗马雷达球场',
                'eallname'=>'',
            ),
            '8'=>array(
                'ename'=>'Athletic Bilbao',
                'name'=>'毕尔巴鄂',
                'id'=>'8',
                'allname'=>'毕尔巴鄂竞技',
                'home'=>'圣马梅斯球场',
                'eallname'=>'',
            ),
            '9'=>array(
                'ename'=>'Valencia',
                'name'=>'瓦伦西亚',
                'fid'=>'2892',
                'id'=>'9',
                'allname'=>'瓦伦西亚',
                'home'=>'梅斯塔利亚球场',
                'eallname'=>'',
            ),
            '10'=>array(
                'ename'=>'Osasuna',
                'name'=>'奥萨苏纳',
                'id'=>'10',
                'allname'=>'奥萨苏纳',
                'home'=>'纳瓦拉王国球场',
                'eallname'=>'',
            ),
            '11'=>array(
                'ename'=>'Málaga',
                'name'=>'马拉加',
                'id'=>'11',
                'allname'=>'马拉加',
                'home'=>'玫瑰园球场',
                'eallname'=>'',
            ),
            '12'=>array(
                'ename'=>'Rayo Vallecano',
                'name'=>'巴列卡诺',
                'id'=>'12',
                'allname'=>'巴列卡诺',
                'home'=>'特蕾莎-里维罗球场',
                'eallname'=>'',
            ),
            /*'13'=>array(
             *      'ename'=>'Villarreal',
             *              'name'=>'比利亚雷亚尔',
             *                      'id'=>'13',
             *                              'allname'=>'比利亚雷亚尔',
             *                                      'home'=>'情歌球场',
             *                                              'eallname'=>'',
             *
             *                                                  ),*/
            '14'=>array(
                'ename'=>'Granada 74',
                'name'=>'格拉纳达',
                'id'=>'14',
                'allname'=>'格拉纳达',
                'home'=>'新洛斯卡门斯球场',
                'eallname'=>'',
            ),
            /*'15'=>array(
             *      'ename'=>'Sporting Gijon',
             *              'name'=>'希洪',
             *                      'id'=>'15',
             *                              'allname'=>'希洪竞技',
             *                                      'home'=>'大磨坊球场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            '16'=>array(
                'ename'=>'Sevilla FC',
                'name'=>'塞维利亚',
                'id'=>'16',
                'allname'=>'塞维利亚',
                'home'=>'皮斯胡安球场',
                'eallname'=>'',
            ),
            '17'=>array(
                'ename'=>'Real Betis',
                'name'=>'贝蒂斯',
                'id'=>'17',
                'allname'=>'皇家贝蒂斯',
                'home'=>'贝尼托-比拉马林球场',
                'eallname'=>'',
            ),
            '18'=>array(
                'ename'=>'Atlético Madrid',
                'name'=>'马竞',
                'id'=>'18',
                'allname'=>'马德里竞技',
                'home'=>'卡尔德隆球场',
                'eallname'=>'',
            ),
            '19'=>array(
                'ename'=>'Levante',
                'name'=>'莱万特',
                'id'=>'19',
                'allname'=>'莱万特',
                'home'=>'瓦伦西亚城市体育场',
                'eallname'=>'',
            ),
            '20'=>array(
                'ename'=>'Getafe',
                'name'=>'赫塔菲',
                'id'=>'20',
                'allname'=>'赫塔菲',
                'home'=>'阿方索-佩雷斯球场',
                'eallname'=>'',
            ),
            '21'=>array(
                'ename'=>'Deportivo La Coruna',
                'name'=>'拉科',
                'id'=>'21',
                'allname'=>'拉科鲁尼亚',
                'home'=>'里亚索球场',
                'eallname'=>'',
            ),
            '22'=>array(
                'ename'=>'Celta Vigo',
                'name'=>'塞尔塔',
                'id'=>'22',
                'allname'=>'维戈塞尔塔',
                'home'=>'巴莱多斯球场',
                'eallname'=>'',
            ),
            '23'=>array(
                'ename'=>'Valladolid',
                'name'=>'瓦拉多利德',
                'id'=>'23',
                'allname'=>'瓦拉多利德',
                'home'=>'索里利亚球场',
                'eallname'=>'',
            ));
        $teamsInfoConfig['seriea'] = array(
            '1'=>array(
                'ename'=>'AC Milan',
                'name'=>'AC米兰',
                'fid'=>'1845',
                'id'=>'1',
                'allname'=>'AC米兰',
                'home'=>'圣西罗球场',
                'eallname'=>'',
            ),
            '2'=>array(
                'ename'=>'AS Roma',
                'name'=>'罗马',
                'fid'=>'1848',
                'id'=>'2',
                'allname'=>'罗马',
                'home'=>'罗马奥林匹克球场',
                'eallname'=>'',
            ),
            '3'=>array(
                'ename'=>'Atalanta',
                'name'=>'亚特兰大',
                'id'=>'3',
                'allname'=>'亚特兰大',
                'home'=>'蓝色意大利球场',
                'eallname'=>'',
            ),
            '4'=>array(
                'ename'=>'Bologna',
                'name'=>'博洛尼亚',
                'id'=>'4',
                'allname'=>'博洛尼亚',
                'home'=>'达拉拉球场',
                'eallname'=>'',
            ),
            '5'=>array(
                'ename'=>'Fiorentina',
                'name'=>'佛罗伦萨',
                'id'=>'5',
                'allname'=>'佛罗伦萨',
                'home'=>'弗兰基球场',
                'eallname'=>'',
            ),
            '6'=>array(
                'ename'=>'Internazionale',
                'name'=>'国际米兰',
                'fid'=>'1847',
                'id'=>'6',
                'allname'=>'国际米兰',
                'home'=>'梅阿查球场',
                'eallname'=>'',
            ),
            '7'=>array(
                'ename'=>'Juventus',
                'name'=>'尤文图斯',
                'fid'=>'1846',
                'id'=>'7',
                'allname'=>'尤文图斯',
                'home'=>'新阿尔皮球场',
                'eallname'=>'',
            ),
            '8'=>array(
                'ename'=>'Lazio',
                'name'=>'拉齐奥',
                'id'=>'8',
                'allname'=>'拉齐奥',
                'home'=>'罗马奥林匹克球场',
                'eallname'=>'',
            ),
            /*'9'=>array(
             *      'ename'=>'Lecce',
             *              'name'=>'莱切',
             *                      'id'=>'9',
             *                              'allname'=>'莱切',
             *                                      'home'=>'德尔马雷球场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            '10'=>array(
                'ename'=>'Napoli',
                'name'=>'那不勒斯',
                'id'=>'10',
                'allname'=>'那不勒斯',
                'home'=>'圣保罗球场',
                'eallname'=>'',
            ),
            '11'=>array(
                'ename'=>'Parma',
                'name'=>'帕尔马',
                'id'=>'11',
                'allname'=>'帕尔马',
                'home'=>'塔尔迪尼球场',
                'eallname'=>'',
            ),
            '12'=>array(
                'ename'=>'Udinese',
                'name'=>'乌迪内斯',
                'id'=>'12',
                'allname'=>'乌迪内斯',
                'home'=>'弗留利球场',
                'eallname'=>'',
            ),
            '13'=>array(
                'ename'=>'Siena',
                'name'=>'锡耶纳',
                'id'=>'13',
                'allname'=>'锡耶纳',
                'home'=>'弗兰基球场',
                'eallname'=>'',  
            ),
            '14'=>array(
                'ename'=>'Palermo',
                'name'=>'巴勒莫',
                'id'=>'14',
                'allname'=>'巴勒莫',
                'home'=>'巴尔贝拉球场',
                'eallname'=>'',
            ),
            '15'=>array(
                'ename'=>'Cagliari',
                'name'=>'卡利亚里',
                'id'=>'15',
                'allname'=>'卡利亚里',
                'home'=>'圣埃利亚球场',
                'eallname'=>'',
            ),
            '16'=>array(
                'ename'=>'Genoa',
                'name'=>'热那亚',
                'id'=>'16',
                'allname'=>'热那亚',
                'home'=>'费拉里斯球场',
                'eallname'=>'',
            ),
            '17'=>array(
                'ename'=>'Catania',
                'name'=>'卡塔尼亚',
                'id'=>'17',
                'allname'=>'卡塔尼亚',
                'home'=>'马西米诺球场',
                'eallname'=>'',
            ),
            /*'18'=>array(
             *      'ename'=>'Cesena',
             *              'name'=>'切塞纳',
             *                      'id'=>'18',
             *                              'allname'=>'切塞纳',
             *                                      'home'=>'马努奇球场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            '19'=>array(
                'ename'=>'Chievo Verona',
                'name'=>'切沃',
                'id'=>'19',
                'allname'=>'切沃',
                'home'=>'本特戈迪球场',
                'eallname'=>'',
            ),
            /*'20'=>array(
             *      'ename'=>'Novara',
             *              'name'=>'诺瓦拉',
             *                      'id'=>'20',
             *                              'allname'=>'诺瓦拉',
             *                                      'home'=>'皮奥拉球场',
             *                                              'eallname'=>'',
             *                                                          ),*/
            '21'=>array(
                'ename'=>'Torino FC',
                'name'=>'都灵',
                'id'=>'21',
                'allname'=>'都灵',
                'home'=>'都灵奥林匹克体育场',
                'eallname'=>'',
            ),
            '22'=>array(
                'ename'=>'Pescara',
                'name'=>'佩斯卡拉',
                'id'=>'22',
                'allname'=>'佩斯卡拉',
                'home'=>'阿德里亚蒂科球场',
                'eallname'=>'',
            ),
            '23'=>array(
                'ename'=>'Sampdoria',
                'name'=>'桑普',
                'id'=>'23',
                'allname'=>'桑普多利亚',
                'home'=>'马拉西球场',
                'eallname'=>'',
            )
        );
        $teamsInfoConfig['bundesliga'] = array(
            /*'1'=>array(
             *      'ename'=>'FC Cologne',
             *              'name'=>'科隆',
             *                      'id'=>'1',
             *                              'allname'=>'科隆',
             *                                      'home'=>'莱茵能源球场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            '2'=>array(
                'ename'=>'Borussia Dortmund',
                'name'=>'多特蒙德',
                'fid'=>'2433',
                'id'=>'2',
                'allname'=>'多特蒙德',
                'home'=>'伊杜纳信号公园',
                'eallname'=>'',
            ),
            '3'=>array(
                'ename'=>'SC Freiburg',
                'name'=>'弗赖堡',
                'id'=>'3',
                'allname'=>'弗赖堡',
                'home'=>'巴登诺瓦球场',
                'eallname'=>'',
            ),
            '4'=>array(
                'ename'=>'Hamburg SV',
                'name'=>'汉堡',
                'id'=>'4',
                'allname'=>'汉堡',
                'home'=>'英泰竞技场',
                'eallname'=>'',
            ),
            /*'5'=>array(
             *      'ename'=>'Hertha Berlin',
             *              'name'=>'柏林赫塔',
             *                      'id'=>'5',
             *                              'allname'=>'柏林赫塔',
             *                                      'home'=>'奥林匹克体育场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            /*'6'=>array(
             *      'ename'=>'Kaiserslautern',
             *              'name'=>'凯泽斯劳滕',
             *                      'id'=>'6',
             *                              'allname'=>'凯泽斯劳滕',
             *                                      'home'=>'弗里茨-瓦尔特球场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            '7'=>array(
                'ename'=>'Bayer Leverkusen',
                'name'=>'勒沃库森',
                'fid'=>'2434',
                'id'=>'7',
                'allname'=>'勒沃库森',
                'home'=>'拜耳竞技场',
                'eallname'=>'',
            ),
            '8'=>array(
                'ename'=>'Bayern Munich',
                'name'=>'拜仁',
                'fid'=>'2432',
                'id'=>'8',
                'allname'=>'拜仁慕尼黑',
                'home'=>'安联球场',
                'eallname'=>'',
            ),
            '9'=>array(
                'ename'=>'Schalke 04',
                'name'=>'沙尔克04',
                'fid'=>'2436',
                'id'=>'9',
                'allname'=>'沙尔克04',
                'home'=>'费尔廷斯竞技场',
                'eallname'=>'',
            ),
            '10'=>array(
                'ename'=>'VfB Stuttgart',
                'name'=>'斯图加特',
                'id'=>'10',
                'allname'=>'斯图加特',
                'home'=>'梅赛德斯-奔驰竞技场',
                'eallname'=>'',
            ),
            '11'=>array(
                'ename'=>'Werder Bremen',
                'name'=>'不莱梅',
                'fid'=>'2435',
                'id'=>'11',
                'allname'=>'云达不莱梅',
                'home'=>'威悉球场',
                'eallname'=>'',
            ),
            '12'=>array(
                'ename'=>'VfL Wolfsburg',
                'name'=>'沃尔夫斯堡',
                'id'=>'12',
                'allname'=>'沃尔夫斯堡',
                'home'=>'大众汽车竞技场',
                'eallname'=>'',
            ),
            '13'=>array(
                'ename'=>'Borussia Monchengladbach',
                'name'=>'门兴',
                'id'=>'13',
                'allname'=>'门兴格拉德巴赫',
                'home'=>'普鲁士公园球场',
                'eallname'=>'',
            ),
            '14'=>array(
                'ename'=>'Nurnberg',
                'name'=>'纽伦堡',
                'id'=>'14',
                'allname'=>'纽伦堡',
                'home'=>'易贷球场',
                'eallname'=>'',
            ),
            '15'=>array(
                'ename'=>'FC Augsburg',
                'name'=>'奥格斯堡',
                'id'=>'15',
                'allname'=>'奥格斯堡',
                'home'=>'西格里竞技场',
                'eallname'=>'',
            ),
            '16'=>array(
                'ename'=>'TSG Hoffenheim',
                'name'=>'霍芬海姆',
                'id'=>'16',
                'allname'=>'霍芬海姆',
                'home'=>'维尔索尔莱茵-内卡竞技场',
                'eallname'=>'',
            ),
            '17'=>array(
                'ename'=>'Hannover 96',
                'name'=>'汉诺威96',
                'id'=>'17',
                'allname'=>'汉诺威96',
                'home'=>'AWD竞技场',
                'eallname'=>'',
            ),
            '18'=>array(
                'ename'=>'Mainz',
                'name'=>'美因茨',
                'id'=>'18',
                'allname'=>'美因茨05',
                'home'=>'科法斯竞技场',
                'eallname'=>'',
            ),
            '19'=>array(
                'ename'=>'Greuther Furth',
                'name'=>'菲尔特',
                'id'=>'19',
                'allname'=>'菲尔特',
                'home'=>'普莱莫比尔球场',
                'eallname'=>'',
            ),
            '20'=>array(
                'ename'=>'Frankfurt',
                'name'=>'法兰克福',
                'id'=>'20',
                'allname'=>'法兰克福',
                'home'=>'商业银行球场',
                'eallname'=>'',
            ),
            '21'=>array(
                'ename'=>'Dusseldorf',
                'name'=>'杜塞尔多夫',
                'id'=>'21',
                'allname'=>'杜塞尔多夫',
                'home'=>'思捷环球竞技场',
                'eallname'=>'',
            )
        );
        $teamsInfoConfig['ligue1'] = array(
            '1'=>array(
                'ename'=>'Bordeaux',
                'name'=>'波尔多',
                'id'=>'1',
                'allname'=>'波尔多',
                'home'=>'沙邦-戴尔马球场',
                'eallname'=>'',
            ),
            '2'=>array(
                'ename'=>'Paris Saint-Germain ',
                'name'=>'圣日耳曼',
                'id'=>'2',
                'allname'=>'巴黎圣日耳曼',
                'home'=>'王子公园球场',
                'eallname'=>'',
            ),
            '3'=>array(
                'ename'=>'Lille',
                'name'=>'里尔',
                'id'=>'3',
                'allname'=>'里尔',
                'home'=>'里尔市政球场',
                'eallname'=>'',
            ),
            '4'=>array(
                'ename'=>'Lyon',
                'name'=>'里昂',
                'id'=>'4',
                'allname'=>'里昂',
                'home'=>'热尔兰球场',
                'eallname'=>'',
            ),
            '5'=>array(
                'ename'=>'Stade Rennes',
                'name'=>'雷恩',
                'id'=>'5',
                'allname'=>'雷恩',
                'home'=>'洛里昂路球场',
                'eallname'=>'',
            ),
            /*'6'=>array(
             *      'ename'=>'AJ Auxerre',
             *              'name'=>'欧塞尔',
             *                      'id'=>'6',
             *                              'allname'=>'欧塞尔',
             *                                      'home'=>'阿贝-德尚球场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            '7'=>array(
                'ename'=>'Marseille',
                'name'=>'马赛',
                'id'=>'7',
                'allname'=>'马赛',
                'home'=>'韦洛德罗姆球场',
                'eallname'=>'',
            ),
            '8'=>array(
                'ename'=>'St Etienne',
                'name'=>'圣埃蒂安',
                'id'=>'8',
                'allname'=>'圣埃蒂安',
                'home'=>'吉夏尔球场',
                'eallname'=>'',
            ),
            '9'=>array(
                'ename'=>'Toulouse',
                'name'=>'图卢兹',
                'id'=>'9',
                'allname'=>'图卢兹',
                'home'=>'市政球场',
                'eallname'=>'',
            ),
            '10'=>array(
                'ename'=>'Sochaux',
                'name'=>'索肖',
                'id'=>'10',
                'allname'=>'索肖',
                'home'=>'博纳尔球场',
                'eallname'=>'',
            ),
            '11'=>array(
                'ename'=>'Lorient',
                'name'=>'洛里昂',
                'id'=>'11',
                'allname'=>'洛里昂',
                'home'=>'穆斯托伊尔球场',
                'eallname'=>'',
            ),
            '12'=>array(
                'ename'=>'Montpellier',
                'name'=>'蒙彼利埃',
                'id'=>'12',
                'allname'=>'蒙彼利埃',
                'home'=>'拉蒙松球场',
                'eallname'=>'',
            ),
            '13'=>array(
                'ename'=>'Nice',
                'name'=>'尼斯',
                'id'=>'13',
                'allname'=>'尼斯',
                'home'=>'杜雷球场',
                'eallname'=>'',
            ),
            '14'=>array(
                'ename'=>'AC Ajaccio',
                'name'=>'阿雅克肖',
                'id'=>'14',
                'allname'=>'阿雅克肖',
                'home'=>'弗朗索瓦-科蒂球场',
                'eallname'=>'',
            ),
            /*'15'=>array(
             *      'ename'=>'Caen',
             *              'name'=>'卡昂',
             *                      'id'=>'15',
             *                              'allname'=>'卡昂',
             *                                      'home'=>'米歇尔-多尔纳诺球场',
             *                                              'eallname'=>'',
             *
             *                                                  ),*/
            /*'16'=>array(
             *      'ename'=>'Dijon FCO',
             *              'name'=>'第戎',
             *                      'id'=>'16',
             *                              'allname'=>'第戎',
             *                                      'home'=>'加斯东-热拉尔球场',
             *                                              'eallname'=>'',
             *
             *                                                      
             *                                                          ),*/
            '17'=>array(
                'ename'=>'AS Nancy Lorraine',
                'name'=>'南锡',
                'id'=>'17',
                'allname'=>'南锡',
                'home'=>'马塞尔-皮科球场',
                'eallname'=>'',
            ),
            '18'=>array(
                'ename'=>'Valenciennes',
                'name'=>'瓦朗谢讷',
                'id'=>'18',
                'allname'=>'瓦朗谢讷',
                'home'=>'杜艾诺球场',
                'eallname'=>'',
            ),
            '19'=>array(
                'ename'=>'Brest',
                'name'=>'布雷斯特',
                'id'=>'19',
                'allname'=>'布雷斯特',
                'home'=>'勒比莱球场',
                'eallname'=>'',

            ),
            '20'=>array(
                'ename'=>'Evian',
                'name'=>'埃维昂',
                'id'=>'20',
                'allname'=>'埃维昂',
                'home'=>'阿衲西体育公园球场',
                'eallname'=>'',
            ),
            '21'=>array(
                'ename'=>'SC Bastia',
                'name'=>'巴斯蒂亚',
                'id'=>'21',
                'allname'=>'巴斯蒂亚',
                'home'=>'艾曼施沙利球场',
                'eallname'=>'',

            ),
            '22'=>array(
                'ename'=>'Reims',
                'name'=>'兰斯',
                'id'=>'22',
                'allname'=>'兰斯',
                'home'=>'奥古斯特德洛纳体育场',
                'eallname'=>'',

            ),
            '23'=>array(
                'ename'=>'Troyes',
                'name'=>'特鲁瓦',
                'id'=>'23',
                'allname'=>'特鲁瓦',
                'home'=>'黎明体育场',
                'eallname'=>'',

            )
        );
        $teamsInfoConfig['epl'] = array(
            '1'=>array(
                'ename'=>'Swansea City',
                'name'=>'斯旺西',
                'id'=>'1',
                'allname'=>'斯旺西城',
                'home'=>'自由球场',
                'eallname'=>'',
            ),
            '2'=>array(
                'ename'=>'Queens Park Rangers',
                'name'=>'女王公园',
                'id'=>'2',
                'allname'=>'女王公园巡游者',
                'home'=>'洛夫图斯球场',
                'eallname'=>'',
            ),
            '3'=>array(
                'ename'=>'Stoke City',
                'name'=>'斯托克城',
                'id'=>'3',
                'allname'=>'斯托克城',
                'home'=>'不列颠尼亚球场',
                'eallname'=>'',
            ),
            '4'=>array(
                'ename'=>'Wigan Athletic',
                'name'=>'维冈',
                'id'=>'4',
                'allname'=>'维冈竞技',
                'home'=>'JJB球场',
                'eallname'=>'',
            ),
            /*'5'=>array(
             *      'ename'=>'Bolton Wanderers',
             *              'name'=>'博尔顿',
             *                      'id'=>'5',
             *                              'allname'=>'博尔顿',
             *                                      'home'=>'锐步球场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            '6'=>array(
                'ename'=>'Arsenal',
                'name'=>'阿森纳',
                'fid'=>'2918',
                'id'=>'6',
                'allname'=>'阿森纳',
                'home'=>'酋长球场',
                'eallname'=>'',
            ),
            '7'=>array(
                'ename'=>'Manchester United',
                'name'=>'曼联',
                'fid'=>'2916',
                'id'=>'7',
                'allname'=>'曼彻斯特联队',
                'home'=>'老特拉福德球场',
                'eallname'=>'',
            ),
            '8'=>array(
                'ename'=>'Newcastle United',
                'name'=>'纽卡斯尔',
                'id'=>'8',
                'allname'=>'纽卡斯尔联队',
                'home'=>'圣詹姆斯公园球场',
                'eallname'=>'',
            ),
            '9'=>array(
                'ename'=>'Aston Villa',
                'name'=>'维拉',
                'id'=>'9',
                'allname'=>'阿斯顿维拉',
                'home'=>'维拉公园球场',
                'eallname'=>'',
            ),
            '10'=>array(
                'ename'=>'Chelsea',
                'name'=>'切尔西',
                'fid'=>'2919',
                'id'=>'10',
                'allname'=>'切尔西',
                'home'=>'斯坦福桥球场 ',
                'eallname'=>'',
            ),
            '11'=>array(
                'ename'=>'Liverpool',
                'name'=>'利物浦',
                'fid'=>'2917',
                'id'=>'11',
                'allname'=>'利物浦',
                'home'=>'安菲尔德球场',
                'eallname'=>'',
            ),
            /*'12'=>array(
             *      'ename'=>'Blackburn Rovers',
             *              'name'=>'布莱克本',
             *                      'id'=>'12',
             *                              'allname'=>'布莱克本',
             *                                      'home'=>'埃伍德公园球场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            '13'=>array(
                'ename'=>'Sunderland',
                'name'=>'桑德兰',
                'id'=>'13',
                'allname'=>'桑德兰',
                'home'=>'光明球场',
                'eallname'=>'',
            ),
            '14'=>array(
                'ename'=>'Tottenham Hotspur',
                'name'=>'热刺',
                'fid'=>'3112',
                'id'=>'14',
                'allname'=>'托特纳姆热刺',
                'home'=>'白鹿巷球场',
                'eallname'=>'',
            ),
            '15'=>array(
                'ename'=>'Everton',
                'name'=>'埃弗顿',
                'id'=>'15',
                'allname'=>'埃弗顿',
                'home'=>'古迪逊公园球场',
                'eallname'=>'',
            ),
            '16'=>array(
                'ename'=>'Fulham',
                'name'=>'富勒姆',
                'id'=>'16',
                'allname'=>'富勒姆',
                'home'=>'克拉文农场球场',
                'eallname'=>'',
            ),
            /*'17'=>array(
             *      'ename'=>'Wolverhampton Wanderers',
             *              'name'=>'狼队',
             *                      'id'=>'17',
             *                              'allname'=>'狼队',
             *                                      'home'=>'莫里纽斯球场',
             *                                              'eallname'=>'',
             *                                                  ),*/
            '18'=>array(
                'ename'=>'Norwich City',
                'name'=>'诺维奇',
                'id'=>'18',
                'allname'=>'诺维奇城',
                'home'=>'卡罗路球场',
                'eallname'=>'',
            ),
            '19'=>array(
                'ename'=>'Manchester City',
                'name'=>'曼城',
                'fid'=>'2908',
                'id'=>'19',
                'allname'=>'曼彻斯特城队',
                'home'=>'伊蒂哈德球场',
                'eallname'=>'',
            ),
            '20'=>array(
                'ename'=>'West Bromwich Albion',
                'name'=>'西布朗',
                'id'=>'20',
                'allname'=>'西布朗维奇',
                'home'=>'山楂球场',
                'eallname'=>'',
            ),
            '21'=>array(
                'ename'=>'Readomg FC',
                'name'=>'雷丁',
                'fid'=>'',
                'id'=>'21',
                'allname'=>'雷丁',
                'home'=>'麦德捷斯基球场',
                'eallname'=>'',
            ),
            '22'=>array(
                'ename'=>'West Ham United Football Club',
                'name'=>'南安普敦 ',
                'fid'=>'',
                'id'=>'22',
                'allname'=>'南安普敦 ',
                'home'=>'圣玛丽球场',
                'eallname'=>'',
            ),
            '23'=>array(
                'ename'=>'West Ham United Football Club',
                'name'=>'西汉姆',
                'fid'=>'',
                'id'=>'23',
                'allname'=>'西汉姆联联队',
                'home'=>'厄普顿公园球场',
                'eallname'=>'',
            )
        );
        $teamsInfoConfig['csl'] = array(
            '1'=>array(
                'name'=>'恒大',
                'allname'=>'广州恒大',
                'fid'=>'2932'
            ),
            '2'=>array(
                'name'=>'国安',
                'allname'=>'北京国安',
                'fid'=>'2933'
            ),
            '3'=>array(
                'name'=>'宏运',
                'allname'=>'辽宁宏运',
                'fid'=>''
            ),
            '4'=>array(
                'name'=>'舜天',
                'allname'=>'江苏舜天',
                'fid'=>'3084'
            ),
            '5'=>array(
                'name'=>'鲁能',
                'allname'=>'山东鲁能',
                'fid'=>'3082'
            ),
            '6'=>array(
                'name'=>'中能',
                'allname'=>'青岛中能',
                'fid'=>''
            ),
            '7'=>array(
                'name'=>'亚泰',
                'allname'=>'长春亚泰',
                'fid'=>''
            ),
            '8'=>array(
                'name'=>'绿城',
                'allname'=>'杭州绿城',
                'fid'=>'3127'
            ),
            '9'=>array(
                'name'=>'人和',
                'allname'=>'贵州人和',
                'fid'=>'3130'
            ),
            '10'=>array(
                'name'=>'泰达',
                'allname'=>'天津泰达',
                'fid'=>'3083'
            ),
            '11'=>array(
                'name'=>'申花',
                'allname'=>'上海申花',
                'fid'=>'3053'
            ),
            '12'=>array(
                'name'=>'实德',
                'allname'=>'大连实德',
                'fid'=>'3128'
            ),
            '13'=>array(
                'name'=>'申鑫',
                'allname'=>'上海申鑫',
                'fid'=>''
            ),
            '14'=>array(
                'name'=>'建业',
                'allname'=>'河南建业',
                'fid'=>'3129'
            ),
            '15'=>array(
                'name'=>'阿尔滨',
                'allname'=>'大连阿尔滨',
                'fid'=>'3131'
            ),
            '16'=>array(
                'name'=>'富力',
                'allname'=>'广州富力',
                'fid'=>''
            )
        );

        if(isset($teamsInfoConfig[$name][$id])) {
            return $teamsInfoConfig[$name][$id]["name"];
        } else {
            return "";
        }
    }
}
