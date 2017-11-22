<?php

class tradeTestTask extends sfBaseTask
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
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'Test';
    $this->briefDescription = '线下测试';
    $this->detailedDescription = <<<EOF
The [trade:test|INFO] task does things.
Call it with:

  [php symfony trade:test|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {


    $url='https://detail.tmall.com/item.htm?id=522626988797';
    $this->log(urlencode($url));
    
  }
}
