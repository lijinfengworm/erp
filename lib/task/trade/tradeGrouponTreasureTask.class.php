<?php
/*
 *用户活动送券
 **/
class tradeGrouponTreasureTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'grouponTreasure';
    $this->briefDescription = '团购免费团商品状态变更';
    $this->detailedDescription = <<<EOF
The [trade:grouponTreasure|INFO] task does things.
Call it with:

  [php symfony trade:grouponTreasure|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
      sfContext::createInstance($this->configuration);

      //过期改状态
      trdGrouponTreasureTable::getInstance()
          ->createQuery()
          ->where('status = 2')
          ->andWhere('end_time < ?', date('Y-m-d H:i:s'))
          ->update()
          ->set('status', 3)
          ->execute();

      //两周未审核改状态
      trdGrouponTreasureTable::getInstance()
          ->createQuery()
          ->where('status = 1')
          ->andWhere('created_at < ?', date('Y-m-d H:i:s',(time()-(3600*24*14))))
          ->update()
          ->set('status', 4)
          ->execute();
  }
}
