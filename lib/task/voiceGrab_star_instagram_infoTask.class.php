<?php

class voiceGrab_star_instagram_infoTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
      new sfCommandOption('account', null, sfCommandOption::PARAMETER_REQUIRED, 'The account type', 'instagram'),
      new sfCommandOption('period', null, sfCommandOption::PARAMETER_REQUIRED, 'The grab cycle time (minutes)', 30),  
      // add your own options here
    ));

    $this->namespace        = 'voice';
    $this->name             = 'grab_star_instagram_info';
    $this->briefDescription = '定时抓取新声球星的Instagram的信息';
    $this->detailedDescription = <<<EOF
The [voice:grab_star_instagram_info|INFO] task does things.
Call it with:

  [php symfony voice:grab_star_instagram_info|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
      sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('api', $options['env'], true));
      
      $this->runStatus = sfContext::getInstance()->getDatabaseConnection('liangleMemcache');

//      if (!$this->getRunningStatus()){
         $this->log('start task!');
//         $this->setRunningStatus();
         $this->getInstagramInfo($options['account'], $options['period'], $options['env']);
         $this->log('task over!');
//      }else 
//          $this->log('task is running!');
//      
//       $this->delRunningStatus();  
  }
  
  private function getInstagramInfo($type, $time, $env){
       $accounts = twitterAccountTable::getAccountsByType($type);
        $accounts = $accounts->toArray();

        if (count($accounts) == 0){
            return $this->log('no instangram account');
        }
        
        foreach ($accounts as $account){
            $messages = $this->getInstagramUrlInfo($account['url'], $time);   
            $connection = Doctrine_Manager::getInstance()->getConnection('voice'); 
            Doctrine_Manager::getInstance()->closeConnection($connection);
          if ($env == 'prod'){
              $params = array(
              'dsn' => 'mysql:host=192.168.1.160;dbname=voice',
              'username' => 'voice_hupu',
              'password' => 'H38uyerYWry284hUwr',
              'encoding' => 'utf8',
              'name' => 'voice'); 

          } else {
//              $params = array(
//              'dsn' => 'mysql:host=192.168.8.43;dbname=voice;port=3306',
//              'username' => 'root',
//              'password' => '',
//              'encoding' => 'utf8',
//              'name' => 'voice'); 
               $params = array(
               'dsn' => 'mysql:host=192.168.8.11;dbname=voice_dev;port=3233',
               'username' => 'root',
               'password' => 'testserver',
               'encoding' => 'utf8',
               'name' => 'voice');
          }
          

          $dd = new sfDoctrineDatabase($params);
          //$connection = sfContext::getInstance()->getDatabaseConnection('voice');
          $connetion = $dd->getDoctrineConnection();
 
            if ($messages === FALSE || $messages === NULL || empty($messages)){
                is_array($messages) && empty($messages) ? $this->log($account['url'] . ' : The last time to now no update!') : '';
                continue;
            }
            $datacount = array('failed' => array(), 'success' => array(), 'url' => $account['url']);
            
            foreach ($messages as $message){
//                $data = array();
//                $data['twitter_user_id'] = $account['twitter_user_id'];
//                $data['twitter_account_id'] = $account['id'];
//                $data['publish_date'] = $message['time'];
//                $data['text'] = $message['text'];
//                $data['orginal_url'] = $message['origin_url'];
//                $data['img_link'] = $message['img'];
//                $data['publish_category'] = $type;
                $query = 'insert into  voiceFeedMessages (`twitter_user_id`, `twitter_account_id`, `publish_date`, `text`, `orginal_url`, `img_link`, `publish_category`, `created_at`, `updated_at`) values ("' .$account['twitter_user_id'].'","'.$account['id'].'","'.$message['time'].'","'.addslashes($message['text']).'","'.addslashes($message['origin_url']).'","'.addslashes($message['img']).'","'.$type.'",now(),now())';

                try{
                    $connection->execute($query);
                    $datacount['success'][] = 1;
                } catch (Exception $e){
                    $this->log($e->getMessage());
                    $this->log($e->getCode());
                    $this->log($e->getTraceAsString());
                    $datacount['failed'][] = 1;
                }
                
                $query = null;
            }
            
            $this->log($datacount['url'] . ':');
            $this->log( 'update success: ' . count($datacount['success']));
            $this->log('update failed: ' . count($datacount['failed']));
        }
  }
  
  private function getInstagramUrlInfo($url, $time) {
        try {
           //$url = 'http://instagram.com/1235';
            $data = file_get_contents($url);
        } catch (Exception $e) {
            $this->log($url . ' : Remote access error!');
            $this->log($e->getTraceAsString());
            return false;
        }

        if (!preg_match("/(\"userMedia\"\:\[.*\])\,/is", $data, $match)) {
            $this->log($url . ' : no match data or occur an error!');
            return NULL;
        }
        
        $jsondata = json_decode('{'. trim($match[1],','). '}',true);
        
//        if (!preg_match("/(\[\"lib\\\\\/fullpage\\\\\/[\S|\s]*\}\]\])\,/is", $data, $match)) {
//            $this->log($url . ' : no match data or occur an error!');
//            return NULL;
//        }
       
//        $jsondata = json_decode($match[1],true);
        
        
        //$this->log($jsondata);
        //$jsondata = $jsondata[count($jsondata)-1]; 
        //$this->log($jsondata['2']);
        //$this->log($jsondata['2']['3']);
        
        if(empty($jsondata) || empty($jsondata['userMedia'])){
            return array();
        }
        
        $jsondata = $jsondata['userMedia'];
        
//        if (!isset($jsondata['2']['0']['props']['userMedia']) || empty($jsondata['2']['0']['props']['userMedia'])){
//            return array();
//        }
//        $jsondata = $jsondata['2']['0']['props']['userMedia'];
        
        $starttime = time() - 60 * $time;
        $data = array();
        foreach ($jsondata as $k => $jd) {
            
            if (!$jd['caption'])
                continue;

            if ($jd['caption']['created_time'] < $starttime)
                return $data;

            $data[$k]['time'] = date('Y-m-d H:i:s', $jd['caption']['created_time']);
            $data[$k]['text'] = $jd['caption']['text'];
            $data[$k]['origin_url'] = $jd['link'];
            $data[$k]['img'] = $jd['images']['standard_resolution']['url'];
        }
        
        return $data;
    }

//    private function getRunningStatus() {
//        return $this->runStatus->get($this->name);
//    }
//
//    private function setRunningStatus() {
//        $this->runStatus->set($this->name, TRUE, 1800);
//    }
//
//    private function delRunningStatus() {
//        $this->runStatus->delete($this->name);
//    }
}
