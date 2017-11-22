<?php

class tradeSendMessageToWPTask extends sfBaseTask
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
    $this->name             = 'SendMessageToWP';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:SendMessageToWP|INFO] task does things.
Call it with:

  [php symfony trade:SendMessageToWP|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration); 
        sfContext::createInstance($this->configuration);    
    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        
        ini_set('memory_limit', '256M');
        //获取发送通知任务
        $message = TrdMessageTable::getInstance()->createQuery()->where('status = ?',0)->andWhere('type = ?',3)->andWhere('is_delete = ?',0)->limit(1)->fetchOne();
        if (!empty($message)){
            echo date("Y-m-d H:i:s")." ";
            $time_start = microtime(true);
            $mess = $message->toArray();
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
                    $this->sendMessage($mess['title'],$mess['content'],'youhui',$mess['news_id'],$vv);
                }
            }
            
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            echo "time_cost: {$time},wp: {$clients['total']} \r\n";
            unset($clients);
            //修改发送状态
            $msgObj = TrdMessageTable::getInstance()->findOneById($message['id']);
            $msgObj->setStatus(1);
            $msgObj->save();
        }
        exit;
  }
  private function sendMessage($title,$content,$type,$id,$url){
      // Create the toast message
     $toastMessage = "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
                    "<wp:Notification xmlns:wp=\"WPNotification\">" .
                    "<wp:Toast>" .
                            "<wp:Text1>" . htmlspecialchars($title) . "</wp:Text1>" .
                            "<wp:Text2>" . htmlspecialchars($content) . "</wp:Text2>" .
                            "<wp:Param>" . htmlspecialchars("/DetailPage.xaml?type=$type&id=$id") . "</wp:Param>" .
                            "</wp:Toast> " .
                    "</wp:Notification>";

        // Create request to send
        $r = curl_init();
        curl_setopt($r, CURLOPT_URL,$url);
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($r, CURLOPT_POST, true);
        curl_setopt($r, CURLOPT_HEADER, true); 

        // add headers
        $httpHeaders=array('Content-type: text/xml; charset=utf-8', 'X-WindowsPhone-Target: toast',
                        'Accept: application/*', 'X-NotificationClass: 2','Content-Length:'.strlen($toastMessage));
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
