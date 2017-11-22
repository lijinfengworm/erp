<?php

class tradeGetAlipayRedTask extends sfBaseTask
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
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
            new sfCommandOption('number', null, sfCommandOption::PARAMETER_REQUIRED, 'The number name', ''),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'GetAlipayRedTask';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:GetYouhuiStats|INFO] task does things.
Call it with:

  [php symfony trade:GetAlipayRedTask|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);    
    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        if (!empty($options['type']) && !empty($options['number'])){
            $contents = file_get_contents(dirname(__FILE__).'/../../web/alipayred_'.$options['type'].'_'.$options['number'].'.txt');
        } else if (!empty($options['type']) && empty($options['number'])) {
            $contents = file_get_contents(dirname(__FILE__).'/../../web/alipayred_'.$options['type'].'.txt');
        } else {
            $contents = file_get_contents(dirname(__FILE__).'/../../web/alipayred.txt');
        }
        
        $content = explode("\r\n",$contents);
        $count = ceil((count($content))/20);
        if ($count > 0){
            for($i = 0;$i < $count;$i++){
                $items = array_slice($content,$i*20, 20);
                foreach ($items as $k=>$v){
                    $item = explode(",", $v);
                    $this->saveData($item[0],$item[1],$options['type']);
                }
            }
        } 
        exit;
  }

  /**
   *保存trd_alipay_red表信息
   * @param string $account 账号
   * @param string $pass 密码
   * @return int id
   */
  protected function saveData($account,$pass,$money){
      if (!$account || !$pass) return false;
      $num = $this->getCount($account);
      if ($num){
          return false;
      } 
      $time = date('Y-m-d H:i:s',time());
      $procObject = new TrdAlipayRed();
      $procObject->setAccount($account);
      $procObject->setPass($pass);
      $procObject->setMoney($money);
      $procObject->save();
      return  $procObject->getId();
  }
  
  protected function getCount($account){
       $query = TrdAlipayRedTable::getInstance()->createQuery('m')
                ->where('m.account = ?',$account)
                ->andWhere('m.status = 0');
        return $query->count();       
  }
}
