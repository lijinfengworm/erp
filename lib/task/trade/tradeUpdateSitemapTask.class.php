<?php

class tradeUpdateSitemapTask extends sfBaseTask
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
    $this->name             = 'UpdateSitemapTask';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:UpdateSitemapTask|INFO] task does things.
Call it with:

  [php symfony trade:UpdateSitemapTask|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array()){

      sfContext::createInstance($this->configuration);
      set_time_limit(0);

      $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
      $redis->select(5);

      $youhuiKey = "shihuo_xml_date_youhui_";
      $faxianKey = "shihuo_xml_date_faxian_";
      $youhuiIdOffsetKey = 'shihuo_xml_id_youhui';
      $faxianIdOffsetKey = 'shihuo_xml_id_faxian';
    //  var_dump($redis->keys($youhuiKey.'*'));exit;
      $yuohuiId = $redis->get($youhuiIdOffsetKey);
      if(empty($yuohuiId))  $yuohuiId = 0;
      $faxianId = $redis->get($faxianIdOffsetKey);
      if(empty($faxianId))  $faxianId = 0;

      $databaseManager = new sfDatabaseManager($this->configuration);
      $connection = $databaseManager->getDatabase('trade')->getConnection();

      # 获取优惠信息
      $rs = $connection->query("select id,publish_date from trd_news where id>{$yuohuiId} order by id asc");
      while($row = $rs->fetch())
      {
          $date = date('Ymd',strtotime($row['publish_date']));
          if (sfConfig::get('sf_environment') == 'prod')
          {
              if($date < 20150928 ) continue;
          }
          $redis->sAdd( $youhuiKey.$date ,$row['id'] );
          # 记录offset
          $redis->set( $youhuiIdOffsetKey ,$row['id'] );

          echo "youhui:{$row['id']}\n";
      }

       # 获取发现信息
      $rs = $connection->query("select id,publish_date from trd_items_all where id>{$faxianId} order by id asc");
      while($row = $rs->fetch())
      {
          $date = date('Ymd',strtotime($row['publish_date']));
          if (sfConfig::get('sf_environment') == 'prod')
          {
              if($date < 20150928 ) continue;
          }
          $redis->sAdd($faxianKey.$date ,$row['id'] );
          # 记录offset
          $redis->set( $faxianIdOffsetKey ,$row['id'] );

          echo "find:{$row['id']}\n";
      }

//
//
//        sfContext::createInstance($this->configuration);
//
//        // initialize the database connection
//        $databaseManager = new sfDatabaseManager($this->configuration);
//        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
//        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
//
//        if ($options['type'] == 1){
//            $date = date('Ymd',time());
//            $val = 'http://www.shihuo.cn/xml/trade/shihuo_sitemap_'.$date.'.xml';
//            $dom  = new  DOMDocument ();
//            //$dom -> formatOutput = true;
//            if ($dom -> load (dirname(__FILE__).'/../../../web/xml/shihuo_sitemap.xml')){
//                    $root = $dom -> documentElement;//获得根节点(root)
//                    $index = $dom -> createElement('sitemap');
//                    $loc = $dom -> createElement('loc');
//                    $newsloc = $dom -> createTextNode($val);
//                    $loc -> appendChild($newsloc);
//                    $index -> appendChild($loc);
//                    $root -> appendChild($index);
//            }
//            $content = $dom->saveXML();
//            file_put_contents(dirname(__FILE__).'/../../../web/xml/shihuo_sitemap.xml',$content);
//        } else {
//            $data = array();
//            $ykey = 'shihuo_sitemap_youhui_ids';
//            $fkey = 'shihuo_sitemap_find_ids';
//            $yid = $redis->get($ykey);
//            $fid = $redis->get($fkey);
//            $youhui_data = $this->getYouhuiIds($yid);
//            $find_data = $this->getFindIds($fid);
//            if (!empty($youhui_data)){
//                foreach ($youhui_data as $k=>$v){
//                    $date = date('Ymd',strtotime($v['publish_date']));
//                    $data[$date][] =  'http://www.shihuo.cn/youhui/'.$v['id'].'.html';
//                }
//                $redis_yid = $v['id'];
//            }
//            if (!empty($find_data)){
//                foreach ($find_data as $k=>$v){
//                    $date = date('Ymd',$v['publish_date']);
//                    $data[$date][] =  'http://www.shihuo.cn/detail/'.$v['id'].'.html';
//                }
//                $redis_fid = $v['id'];
//            }
//            if (!empty($data)){
//
//                foreach ($data as $m=>$n){
//                    $dom=new DomDocument('1.0', 'utf-8');
//                    if (!is_file(dirname(__FILE__).'/../../../web/xml/trade/shihuo_sitemap_'.$m.'.xml')){
//                        //  创建一个XML文档并设置XML版本和编码。。
//
//                        //  创建根节点
//                        $article = $dom->createElement('urlset');
//                        $dom->appendchild($article);
//
//                        //  创建属性节点
//                        $attr = $dom->createAttribute('xmlns');
//                        $attr->value="http://www.sitemaps.org/schemas/sitemap/0.9";
//                        $article->appendchild($attr);
//                        $attr1 = $dom->createAttribute('xmlns:xsi');
//                        $attr1->value="http://www.w3.org/2001/XMLSchema-instance";
//                        $article->appendchild($attr1);
//                        $attr2 = $dom->createAttribute('xsi:schemaLocation');
//                        $attr2->value="http://www.sitemaps.org/schemas/sitemap/0.9
//                            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd";
//                        $article->appendchild($attr2);
//                        $content = $dom->saveXML();
//                        file_put_contents(dirname(__FILE__).'/../../../web/xml/trade/shihuo_sitemap_'.$m.'.xml',$content);
//                    } else {
//                        $dom -> load (dirname(__FILE__).'/../../../web/xml/trade/shihuo_sitemap_'.$m.'.xml');
//                    }
//                    $root = $dom -> documentElement;//获得根节点(root)
//                    foreach ($n as $kk=>$vv){
//                        $index = $dom -> createElement('url');
//                        $loc = $dom -> createElement('loc');
//                        $newsloc = $dom -> createTextNode($vv);
//                        $loc -> appendChild($newsloc);
//
//                        $priority = $dom -> createElement('priority');
//                        $newspriority = $dom -> createTextNode('1.0');
//                        $priority -> appendChild($newspriority);
//
//                        $changefreq = $dom -> createElement('changefreq');
//                        $newschangefreq = $dom -> createTextNode('daily');
//                        $changefreq -> appendChild($newschangefreq);
//
//                        $index -> appendChild($loc);
//                        $index -> appendChild($priority);
//                        $index -> appendChild($changefreq);
//                        $root -> appendChild($index);
//                    }
//                    $content = $dom->saveXML();
//                    file_put_contents(dirname(__FILE__).'/../../../web/xml/trade/shihuo_sitemap_'.$m.'.xml',$content);
//                    unset($dom);
//                }
//                if (isset($redis_yid) && !empty($redis_yid)) $redis->set($ykey,$redis_yid);
//                if (isset($redis_fid) && !empty($redis_fid)) $redis->set($fkey,$redis_fid);
//            }
//        }
        exit;
  }
  
  //id = 18006
//  protected function getYouhuiIds($id){
//      if (empty($id)) $id = 18006;
//      $query = TrdNewsTable::getInstance()->createQuery('m')
//                ->select('id,publish_date')
//                ->where('m.id > ?',$id);
//        return $query->fetchArray();
//  }
  
  //id = 110592
//  protected function getFindIds($id){
//      if (empty($id)) $id = 110592;
//      $query = TrdItemAllTable::getInstance()->createQuery('m')
//                ->select('id,publish_date')
//                ->where('m.id > ?',$id);
//        return $query->fetchArray();
//  }
  
  protected function create_item($dom, $item, $data, $attribute) {
    if (is_array($data)) {
        foreach ($data as $key => $val) {
            //  创建元素
            $$key = $dom->createElement($key);
            $item->appendchild($$key);

            //  创建元素值
            $text = $dom->createTextNode($val);
            $$key->appendchild($text);

            if (isset($attribute[$key])) {
            //  如果此字段存在相关属性需要设置
                foreach ($attribute[$key] as $akey => $row) {
                    //  创建属性节点
                    $$akey = $dom->createAttribute($akey);
                    $$key->appendchild($$akey);

                    // 创建属性值节点
                    $aval = $dom->createTextNode($row);
                    $$akey->appendChild($aval);
                }
            }   //  end if
        }
    }   //  end if
}   //  end function
}
