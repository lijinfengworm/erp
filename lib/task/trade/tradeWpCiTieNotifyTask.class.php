<?php

class tradeWpCiTieNotifyTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            //new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'WpCiTieNotify';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:WpCiTieNotify|INFO] task does things.
Call it with:

  [php symfony trade:WpCiTieNotify|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);    
        set_time_limit(0);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        
        ini_set('memory_limit', '256M');
        echo date("Y-m-d H:i:s")." ";
        $time_start = microtime(true);
        $count = 0;
        //推送
        $key = "trade_app_client_info_3";
        $clientinfo = $redis->get($key);
        if ($clientinfo){
            $clients = unserialize ($clientinfo);
        } else {
            $clients = $this->getClients(3);
            $redis->set(serialize($clients),600);
        }
        if ($clients['total'] > 0){
            foreach($clients['data'] as $k=>$v){
                $data[] = $v['wp_url'];
            }
        }
        if ($clients['total'] > 0){
            foreach ($data as $kk=>$vv){
                try {   
                    $this->sendMessage($vv);
                } catch (Exception $e) {   
                    echo "curl error";
                }  
                if ($kk % 50 == 0) {
                    sleep(5);
                }
                echo $kk.' ';
            }
        }
        if ($clients['total'] > 0){
            foreach ($data as $kk=>$vv){
                try {   
                    $this->sendMessage($vv,false);
                } catch (Exception $e) {   
                    echo "curl error";
                }   
                if ($kk % 50 == 0) {
                    sleep(5);
                }
                echo $kk.' ';
            }
        }

        $time_end = microtime(true);
        $time = $time_end - $time_start;
        $num = $clients['total'] * 2;
        echo "time_cost: {$time},wp: {$num} \r\n";
        unset($clients);
        exit;
  }
  private function sendMessage($url,$type = true){
// Create the toast message
     $toastMessage = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
                    "<wp:Notification xmlns:wp=\"WPNotification\">" .
                    "<wp:Tile";
     if ($type) $toastMessage  .= " ID=\"/MainPage.xaml?s=2\"";
     $toastMessage .= "><wp:BackgroundImage>http://c1.hoopchina.com.cn/images/trade/FlipCycleTileMedium.png</wp:BackgroundImage>" .
                        "<wp:Title> </wp:Title>" .
                        "<wp:BackBackgroundImage>http://www.shihuo.cn/uploads/trade/news/shihuocitieforwp336*336.jpg</wp:BackBackgroundImage>" .
                        "<wp:BackTitle></wp:BackTitle>" .
                        "<wp:BackContent></wp:BackContent>" .
                    "</wp:Tile> " .
                    "</wp:Notification>";
        // Create request to send
        $r = curl_init();
        curl_setopt($r, CURLOPT_URL,$url);
        //curl_setopt($r, CURLOPT_TIMEOUT, 5); 
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_POST, true);
        curl_setopt($r, CURLOPT_HEADER, true); 

        // add headers
        $httpHeaders=array('Content-type: text/xml; charset=utf-8', 'X-WindowsPhone-Target: token',
                        'Accept: application/*', 'X-NotificationClass: 1','Content-Length:'.strlen($toastMessage));
        curl_setopt($r, CURLOPT_HTTPHEADER, $httpHeaders);

        // add message
        curl_setopt($r, CURLOPT_POSTFIELDS, $toastMessage);

        // execute request
        $output = curl_exec($r);
        if(curl_errno($r))
        {
            echo 'Curl error: ' . curl_error($r);
            exit;
        }
        $http_status = curl_getinfo($r, CURLINFO_HTTP_CODE);  
        curl_close($r);
        echo 'curl_http_code:'.$http_status.' ';
        return $output;
  }
  
  //获取app用户
    private function getClients($type=0){
        $minLastVisit = strtotime('-90 day');
        $query = TrdClientInfoTable::getInstance()->createQuery('t')
                ->select('t.client_str,t.client_token,wp_url')
                ->where('t.status  = ?',0)
                ->andWhere('t.push_switch = ?',0)
                ->andWhere('t.last_virst > ?',$minLastVisit);     
        if ($type) $query = $query->andWhere('t.type = ?',$type);
        $num = $query->count();
        $data = $query->fetchArray();
        $info = array('total'=>$num,'data'=>$data);
        return  $info;
    }
 
}
