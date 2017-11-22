<?php

class tradeUpdateCouponTimeTask extends sfBaseTask
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
    $this->name             = 'UpdateCouponTime';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:UpdateSitemapTask|INFO] task does things.
Call it with:

  [php symfony trade:UpdateSitemapTask|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
        set_time_limit(0);

        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        # 获取已领取的优惠券
        $rs = $connection->query("select * from trd_coupons_detail where status=1 and list_id>0");

        while($row = $rs->fetch())
        {
             if(empty($row['account']))
             {
                 echo "nocouunt:rowid-{$row['id']}\n";
                 continue;
             }
             $coupon = $connection->query("select * from trd_coupons_recevied where root_type=0 and account='{$row['account']}' and list_id='{$row['list_id']}'")->fetch();

             if(empty($coupon))
             {
                 echo "noexsit:rowid-{$row['id']}\n";
                 continue;
             }
            if(!empty($row['stime']) && !empty($row['stime']))
            {
                $sql  = "UPDATE trd_coupons_recevied SET stime={$row['stime']},etime={$row['etime']},status=1 WHERE id={$coupon['id']}";
                $connection->query($sql);
                echo 'type1:'.$coupon['account']."\n";exit;
            }
             elseif(!empty($row['list_id']))
             {
                 $list = $connection->query("select * from trd_coupons_list where id={$row['list_id']}")->fetch();
                // $list = TrdCouponsListTable::getInstance()->find($row['list_id']);
                 if(empty($list))
                 {
                     echo "noList:rowid-{$row['id']}\n";
                     continue;
                 }
                 $stime = strtotime($list['start_date']);
                 $etime = strtotime($list['expiry_date']);
                 $sql  = "UPDATE trd_coupons_recevied SET stime={$stime},etime={$etime},status=1 WHERE id={$coupon['id']}";
                 $connection->query($sql);
                 echo "type2:".$coupon['account']."\n";
             }
            else
            {
                echo "type2:rowid-{$row['id']}\n";
            }

        }
  }

}
