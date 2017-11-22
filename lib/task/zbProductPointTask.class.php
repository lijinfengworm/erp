<?php

class zbProductPointTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'zhuangbei'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'zhuangbei'),
      // add your own options here
    ));

    $this->namespace        = 'zhuangbei';
    $this->name             = 'zbProductPoint';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [zbProductPoint|INFO] task does things.
Call it with:

  [php symfony zbProductPoint|INFO]
EOF;
  }
 
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    
    $pids = ZbProductsTable::getDisplayProduct(Doctrine::getTable('ZbProducts')->createQuery('p')->select('p.id'))->toArray();
    foreach ($pids as $val)
    {
        $product_id = $val['id'];
        //更新统计
        zbPointCountsTable::updateProductPointCount($product_id,1);
        zbPointCountsTable::updateProductPointCount($product_id,2);
        zbPointCountsTable::updateProductPointCount($product_id,3);
        zbPointCountsTable::updateProductPointCount($product_id,4);
        zbPointCountsTable::updateProductPointCount($product_id,5);
        $count = ZbProductsTable::updateProductPoints($product_id);
        $this->log($product_id.":".$count);   
    }
  }
}
