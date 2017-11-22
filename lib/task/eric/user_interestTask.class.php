<?php

class user_interestTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'eric'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'eric';
    $this->name             = 'user_interest';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [eric:user_interest|INFO] task does things.
Call it with:
    user_interest
  [php symfony user_interest|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);    
    set_time_limit(0);
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $taskConfig = sfConfig::get('app_tasks');
    $this->log('start');
    $this->lockDir = '/tmp';
    $this->lockFile = 'autoRun_interest.lock';
        //加锁
    if(!$this->enterLock())
    {
        exit();
    }
    
    $memcache = sfContext::getInstance()->getDatabaseConnection('tradeMemcache');
        $memcache_key = "user_interest_uid_28";

        if($memcache->get($memcache_key) !== false) {
            $start = $memcache->get($memcache_key);
        } else {
            $start = 0;
            $memcache->set($memcache_key, 0, 0, 0);
        }
        
        $limit = 1000;
       
        for($i = 0;$i<45000;$i++)
        {
            $rs = fbd_personTable::getInstance()->getSomeUsersInterest($start, $limit);
            if(empty($rs))
            {
                $this->log('没数据了');
                break;
            }
            foreach ($rs as $key=>$user)
            {

                $info = array();
                $info['action'] = "interest";
                $info["userid"] = $user["uid"];
                $info["username"] = mb_convert_encoding($user["username"], 'utf-8', 'gbk');
                $info["hobby"] = mb_convert_encoding($user["hobby"], 'utf-8', 'gbk');
                $hobby_array = explode('&&&', $info['hobby']);
                $all_hobby = explode('###', $hobby_array[0]);
                
                if(isset($all_hobby[1]))
                {
                    $match_hobby = explode('|', $all_hobby[1]);
                    foreach ($match_hobby as $k=>$v)
                    {
                        if(empty($v))
                        {
                            continue;
                        }
                        $info['object_name'] = $v;
                        //$this->log(trim($v));
                        $this->addLog($info);

                    }

                }
                if(isset($all_hobby[2]))
                {
                    $team_hobby = explode('|', html_entity_decode($all_hobby[2]));
                    foreach ($team_hobby as $k => $v)
                    {
                        if(empty($v))
                        {
                            continue;
                        }
                        $info['object_name'] = $v;
                        //$this->log(trim($v));
                        $this->addLog($info);
                    }
                }
                
                $start = $info['userid'];
                $this->log($info['userid'].':'.$info['username']);
                $memcache->set($memcache_key, $start, 0, 0);
                //$this->addLog($info);
            }
            
        }
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
}
