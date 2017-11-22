<?php

class tradeClearActivityTask extends sfBaseTask
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
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'ClearActivity';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:UpdateSitemapTask|INFO] task does things.
Call it with:

  [php symfony trade:UpdateSitemapTask|INFO]
EOF;
  }

    # 清理过期数据
  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
        set_time_limit(0);

//        $databaseManager = new sfDatabaseManager($this->configuration);
//        $connection = $databaseManager->getDatabase('trade')->getConnection();
        $now = time();
        $ids  =  array();
        # 结束时间未到已停止的活动
        $activitys = TrdMarketingActivityTable::getInstance()->createQuery()->select('id')->where('etime > ?',$now)->andWhere('status = ?',4)->fetchArray();

        if(!empty($activitys))
        {
            foreach($activitys as $v)
            {
                $ids[] = $v['id'];
            }

        }
        if(!empty($ids))
        {
            $items = TrdMarketingActivityGroupTable::getInstance()->createQuery()
                ->whereIn('activity_id',$ids)
                ->limit(100)
                ->execute();
            if(empty($items)) goto Next;
        }
        else
        {
            Next:
            $items = TrdMarketingActivityGroupTable::getInstance()->createQuery()
              ->where('etime < ?',$now)
              ->limit(100)
              ->execute();
        }

        if(!empty($items))
        {
            foreach($items as $item)
            {
                $item->delete();
                echo "activity_id:{$item->activity_id} Item:{$item->id} \n";
            }
        }
  }

}
