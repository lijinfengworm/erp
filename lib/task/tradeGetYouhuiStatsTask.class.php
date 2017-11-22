<?php

class tradeGetYouhuiStatsTask extends sfBaseTask
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
    $this->name             = 'GetYouhuiStats';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:GetYouhuiStats|INFO] task does things.
Call it with:

  [php symfony trade:GetYouhuiStats|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);    
    
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $redis = new tradeUpdateRedisList('stats_golink_for_dace');
        $length = $redis->getListLength();
        if ($length > 0){
            $num = ceil($length/20);
            for($i=0;$i<$num;$i++){
                $data = $redis->getData(20);
                foreach($data as $k=>$v){
                    $refer_info = $this->getReferUrlInfo($v['refer_url']);
                    if (!empty($refer_info)){
                        $refer_id = $refer_info[0]['id'];
                    } else {
                        $refer_id = $this->saveReferUrl($v['refer_url'], $v['tp'], $v['time']);
                    }
                    $go_url = $this->getGoUrlInfo($v['url'],$v['news_id']);
                    if (!empty($go_url)){
                        $go_id = $go_url[0]['id'];
                    } else {
                        $go_id = $this->saveGoUrl($v['url'], $v['title'], $v['type'], $v['news_id'], $v['time']);
                    }
                    $result = $this->saveGoClick($refer_id, $go_id, $v['uid'], $v['vid'], $v['vst'], $v['time']);
                    if (!$result) continue;
                }
                $redis->clearPartDate(count($data));
            }
            sleep(2);
        }
        exit;
  }
  
  protected function getReferUrlInfo($url){
      if (!$url) return false;
      $md5_url = substr(md5($url),0,8);
      $query = TrdReferUrlTable::getInstance()->createQuery('m')
                ->where('m.encrypt_url = ?',$md5_url)
                ->andWhere('m.url = ?',$url);
        return $query->fetchArray();
  }
  
  /**
   *保存trd_refer_url表信息
   * @param string $url 来源url
   * @param int $tp 来源地址类型  1：识货首页，2：识货列表页，3：识货内页
   * @param datetime 时间
   * @return int id
   */
  protected function saveReferUrl($url,$tp,$time){
      if (!$url || !$tp) return false;
      $md5_url = substr(md5($url),0,8);
      $time = !empty($time) ? $time : date('Y-m-d H:i:s',time());
      $procObject = new TrdReferUrl();
      $procObject->setUrl($url);
      $procObject->setEncryptUrl($md5_url);
      $procObject->setTp($tp);
      $procObject->setAddtime($time);
      $procObject->save();
      return  $procObject->getId();
  }
  
  protected function getGoUrlInfo($url,$id){
      if (!$url || !$id) return false;
      $md5_url = substr(md5($url),0,8);
      $query = TrdGoUrlTable::getInstance()->createQuery('m')
                ->where('m.encrypt_url = ?',$md5_url)
                ->addWhere('m.trd_news_id = ?',$id)
                ->andWhere('m.url = ?',$url);
        return $query->fetchArray();
  }
  
  /**
   * 保存trd_go_url表信息
   * @param string $url 出站的url
   * @param string $title 标题
   * @param string $type 类型
   * @param datetime 时间
   * @return int id
   */
  protected function saveGoUrl($url,$title,$type,$id,$time){
      if (!$url || !$title || !$id) return false;
      $md5_url = substr(md5($url),0,8);
      $shop = $this->getShopNameByUrl($url);
      $time = !empty($time) ? $time : date('Y-m-d H:i:s',time());
      $procObject = new TrdGoUrl();
      $procObject->setTrdNewsId($id);
      $procObject->setUrl($url);
      $procObject->setEncryptUrl($md5_url);
      $procObject->setTitle($title);
      $procObject->setShop($shop);
      if ($type) $procObject->setType($type);
      $procObject->setAddtime($time);
      $procObject->save();
      return  $procObject->getId();
  }
  
  /**
   * 保存trd_go_click表信息
   * @param int $refer_id 来源url id
   * @param int $go_id 出站url id
   * @param int $uid 用户id
   * @param string $vid dace需要拼接cookie的参数
   * @param string $vst dace需要拼接cookie的参数
   * @param datetime 时间
   * @return int id
   */
  protected function saveGoClick($refer_id,$go_id,$uid,$vid,$vst,$time){
      if (!$refer_id || !$go_id) return false;
      $procObject = new TrdGoClick();
      $time = !empty($time) ? $time : date('Y-m-d H:i:s',time());
      $procObject->setReferId($refer_id);
      $procObject->setGoId($go_id);
      if ($uid) $procObject->setUid($uid);
      if ($vid) $procObject->setVid($vid);
      if ($vst) $procObject->setVst($vst);
      $procObject->setClicktime($time);
      $procObject->save();
      return  $procObject->getId();
  }


  /**
   * 根据域名获取统一商店名称
   * @param string $url 出站的url
   * @return string shop 商店名称
   */
  protected function getShopNameByUrl($url){
      if (!$url) return false;
      $info = parse_url($url);
      if (preg_match('/taobao.com/', $info['host'])) return '淘宝';
      if (preg_match('/tmall.com/', $info['host'])) return '天猫';
      if (preg_match('/suning.com/', $info['host'])) return '苏宁易购';
      if (preg_match('/letao.com/', $info['host'])) return '乐淘';
      if (preg_match('/vancl.com/', $info['host'])) return '凡客诚品';
      if (preg_match('/yougou.com/', $info['host'])) return '优购';
      if (preg_match('/jd.com/', $info['host'])) return '京东';
      if (preg_match('/vipshop.com/', $info['host'])) return '唯品会';
      if (preg_match('/amazon.cn/', $info['host'])) return '中国亚马逊';
      if (preg_match('/yixun.com/', $info['host'])) return '易迅';
      if (preg_match('/gome.com.cn/', $info['host'])) return '国美';
      if (preg_match('/dangdang.com/', $info['host'])) return '当当';
      if (preg_match('/yihaodian.com/', $info['host'])) return '一号店';
      if (preg_match('/ctrip.com/', $info['host'])) return '携程';
      if (preg_match('/newegg.com.cn/', $info['host'])) return '新蛋';
      if (preg_match('/springtour.com/', $info['host'])) return '春秋旅游';
      if (preg_match('/womai.com/', $info['host'])) return '我买';
      if (preg_match('/laiyifen.com/', $info['host'])) return '来伊份';
      if (preg_match('/dianping.com/', $info['host'])) return '大众点评';
      if (preg_match('/paixie.net/', $info['host'])) return '拍鞋';
      if (preg_match('/amazon.com/', $info['host'])) return '美国亚马逊';
      if (preg_match('/efeihu.com/', $info['host'])) return '飞虎乐购';
      if (preg_match('/k121.com/', $info['host'])) return '酷运动';
      if (preg_match('/taoxie.com/', $info['host'])) return '淘鞋';
      if (preg_match('/e-lining.com/', $info['host'])) return '李宁';
      if (preg_match('/xietoo.com/', $info['host'])) return '鞋途';
      if (preg_match('/coo8.com/', $info['host'])) return '库巴';
      if (preg_match('/yintai.com/', $info['host'])) return '银泰';
      if (preg_match('/camel.com/', $info['host'])) return '骆驼';
      if (preg_match('/zm7.cn/', $info['host'])) return '卓美';
      if (preg_match('/vjia.com/', $info['host'])) return '凡客V+';
      if (preg_match('/tonlion.com/', $info['host'])) return '唐狮';
      if (preg_match('/ihush.com/', $info['host'])) return '俏物悄语';
      if (preg_match('/banggo.com/', $info['host'])) return '邦购';
      if (preg_match('/shihuo.hupu.com/', $info['host'])) return '虎扑识货';
      if (preg_match('/www.shihuo.cn/', $info['host'])) return '虎扑识货';
      if (preg_match('/bbs.hupu.com/', $info['host'])) return '虎扑bbs';
      if (preg_match('/xiaomi.com/', $info['host'])) return '小米';
      
      return '其他';
  }
}
