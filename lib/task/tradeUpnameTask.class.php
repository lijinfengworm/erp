<?php

class tradeUpnameTask extends sfBaseTask
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
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'Upname';
    $this->briefDescription = '跟新用户名';
    $this->detailedDescription = <<<EOF
The [trade:test|INFO] task does things.
Call it with:

  [php symfony trade:test|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);
      $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
      $red_id = $redis->get('trade_account_id_updatename2')?$redis->get('trade_account_id_updatename2'):1;

      $lists = TrdAccountTable::getInstance()->createQuery()->where("id>?",$red_id)->limit(20)->execute();
      foreach($lists as $list){
          $redis->set('trade_account_id_updatename2', $list->getId());
          $id=$list->getHupu_uid();
          $this->log('跟新到'.$list->getId());
          $url="http://passport.hupu.com/interface_getUserInfo?key=LSE7OAS2MW&uid=".$id;
          $result=$this->check_username($url);
          if($result!='0') {
              $res = unserialize($result);
              if ($res['username'] != $list->getHupu_username()) {
                  $list->setHupu_username($res['username']);
              }
          }
          $list->save();

      }
















    
  }


    protected function send_post($url,$post_data){
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;

    }


    protected function check_username($url){
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'timeout' => 15 * 60
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;

    }










}
