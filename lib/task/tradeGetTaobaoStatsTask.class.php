<?php

class tradeGetTaobaoStatsTask extends sfBaseTask
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
    $this->name             = 'GetTaobaoStats';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:GetTaobaoStats|INFO] task does things.
Call it with:

  [php symfony trade:GetTaobaoStats|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    sfContext::createInstance($this->configuration);    
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $taskConfig = sfConfig::get('app_tasks');
    
    TrdGoOuterCodeInfoTable::getInstance()->createQuery()->where('trade_time > ?',  date('Y-m-d',time()-86400) )->addwhere('trade_time < ?',date('Y-m-d',time()) )->delete()->execute();
    $this->show_date = date("Ymd", time()-86400);
    $this->log('date：'.$this->show_date);
    for($i=1;;$i++){
        $rs = $this->get_data($this->show_date,$i,100);
        $this->log('loopCount：'.  count($rs));
        $outer_code_array = array();
        foreach ($rs as $key=>$item){
            
            if($item->outer_code != 'bbs' && $item->outer_code != 'my' && $item->outer_code != 'null' && $item->outer_code != 'shihuo' && $item->outer_code != 'zb')
            {
                $outer_code_array[$key] = $item->outer_code;
            }
        }
        
        //test
//        $outer_code_array= array(1=>1,2=>2,3=>4,4=>5,5=>6);
//        foreach($rs as $key=>&$item) {
//            if(array_key_exists($key, $outer_code_array))
//            {
//                $item->outer_code = $outer_code_array[$key];
//            }
//        }
        //testend
        
        $outer_code_array = array_map('hexdec', $outer_code_array);
        $click_info_array = array();
        if(!empty($outer_code_array))
        {
            $click_infos = TrdGoClickInfoTable::getInstance()->createQuery()->whereIn('id',$outer_code_array)->execute();
            foreach ($click_infos as $key=>$val)
            {
                $click_info_array[$val->getId()] = $val;
            }    
        }
        $this->itemInfos = array();
        $this->outerCodeInfos = array();
        foreach ($rs as $key=>&$item)
        {
            $this->log('key'.$key.'--'.$item->item_title);
            if($item->outer_code == 'bbs' or $item->outer_code == 'my' or $item->outer_code == 'null' or $item->outer_code == 'shihuo' or $item->outer_code == 'zb')
            {
                $this->itemInfos[] = $item;
            }else{
                $this->log('outer_code---'.$item->outer_code);
                $click_id = hexdec($item->outer_code);
                if(!empty($click_info_array[$click_id])){
                    $this->outerCodeInfos[] = TrdGoOuterCodeInfoTable::formatdata($click_info_array[$click_id], $item,TRUE); 
                }else{
                    $this->itemInfos[] = $item;
                }
            }
        }
        
        if(count($rs) < 100){
            break;
        }
    }

        
  }
  
  private function get_data($date, $page = 1,$limit = 100) {
        $c = new TaoBaoTopClient();
        


        $fields = array(
            "outer_code",
            "trade_id",
            "pay_time",
            "pay_price",
            "num_iid",
            "item_title",
            "item_num",
            "category_id",
            "category_name",
            "shop_title",
            "commission_rate",
            "commission",
            "iid",
            "seller_nick",
            "real_pay_fee"
        );

        $req = new TaobaokeReportGetRequest;
        $req->setFields(join(",", $fields));
        $req->setDate($date);
        $req->setPageNo($page);
        $req->setPageSize($limit);

        $session_key = "61025067ac3f84121351903fe7d439e878442e5ee2078a3889120098";
        $resp = $c->execute($req, $session_key);
        if(isset($resp->code) && $resp->code == 27) {
            echo "<a href='http://container.api.taobao.com/container?appkey={$c->appkey}'>验证一下</a>";
        }
        
        if(isset($resp->taobaoke_report->taobaoke_report_members->taobaoke_report_member)) {
            $rs = $resp->taobaoke_report->taobaoke_report_members->taobaoke_report_member;
        } else {
            $rs = array();
        }
        $i = 1;
        foreach($rs as &$item) {
            if(!isset($item->outer_code)) {
                $item->outer_code = "-";
            }
            if(!isset($item->category_name) || $item->category_name == "") {
                $item->category_name = "-";
            }
        }

        $total = $resp->taobaoke_report->total_results;
        
        return $rs;
    }
}
