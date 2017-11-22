<?php
/*
 *临时统计
 **/
class acountAction extends sfActions
{
    public function executeAcount(sfWebRequest $request) {
        $f = $request->getParameter('f');
        sfConfig::set('sf_web_debug', false);
        if($f == '20150721'){
            $this->_export_20150721();
        } elseif($f == '20150908'){
            $this->_export_20150908();
        } elseif($f == '20151008'){
            $this->_export_20151008();
        } elseif($f == '_export_201510082'){
            $this->_export_201510082();
        } elseif($f == '_export_2015102201'){
            $this->_export_2015102201();
        }elseif($f == '_export_20151118'){
            $this->_export_20151118($request);
        }elseif($f == '_export_20151123'){//form提交
            $this->_export_20151123($request);
        }elseif($f == '_export_2015112302'){//集合图片版本更迭
            $this->_export_2015112302($request);
        }elseif($f == '_export_2015112303'){//集合图片版本更迭
            $this->_export_2015112303($request);
        }elseif($f == '_export_2015112304'){//集合图片版本更迭
            $this->_export_2015112304($request);
        }elseif($f == '_export_2015112305'){//集合图片版本更迭
            $this->_export_2015112305($request);
        }elseif($f == '_export_2015112401'){//专题图片更新
            $this->_export_2015112401($request);
        }elseif($f == '_export_2015120101'){//清单导出
            $this->_export_2015120101($request);
        }elseif($f == '_export_2015121101'){//团购插数据
            $this->_export_2015121101($request);
        }elseif($f == 'getOriginalUrl'){//获取海淘商品源url
            return $this->getOriginalUrl($request);
        }
    }

    /*
    * 获取海淘商品信息
    **/
    private function getOriginalUrl(sfWebRequest $request){
        $id = $request->getParameter('id');

        $return = array(
            'status'=>false,
        );

        if($id){
            $product = trdProductAttrTable::getInstance()->find($id);
            if($product){
                $return['status'] = true;
                $return['data']  = array(
                    'url' => $product->getUrl(),
                    'id'  => $product->getId(),
                );
            }
        }

        return $this->renderText(json_encode($return));
    }

    /*导出*/
    private function _export_20150721(){
        $zt =  array('【测评大湿】' ,'【高颜值趴】' ,'【女JR私有物】');
        $data = array();
        $k = 0;

        foreach($zt as $zt_v){
           $shaiwuRes =  trdShaiwuProductTable::getInstance()
                ->createQuery()
                ->where('created_at > ?','2015-07-10')
                ->andWhere('created_at < ?','2015-07-20')
                ->andWhere('status =?',1)
                ->andWhere('title like \''.$zt_v.'%\'')
               ->fetchArray();

            foreach($shaiwuRes as $shaiwuRes_v){
                $data[$k]['id'] = $shaiwuRes_v['id'];
                $data[$k]['author_id'] = $shaiwuRes_v['author_id'];
                $data[$k]['title'] = $shaiwuRes_v['title'];
                $data[$k]['support'] = $shaiwuRes_v['support'];
                $data[$k]['created_at'] = $shaiwuRes_v['created_at'];

                //评论数
                $shaiwuCommentCount = trdCommentTable::getInstance()
                    ->createQuery()
                   // ->select('id,count(*) c,reply_count')
                    ->andWhere('product_id = ?', $shaiwuRes_v['id'])
                    ->andWhere('type_id =?', 2)
                    ->andwhere('created_at > ?','2015-07-10')
                    ->andWhere('created_at < ?','2015-07-20')
                    ->groupby('user_id')
                    ->count();

                /*//回复数
                $shaiwuReplyCount = 0;
                foreach($shaiwuCommentCount as $shaiwuCommentCount_v){
                    if($shaiwuCommentCount_v['reply_count'] > 0){
                        $shaiwuReplyCount += trdCommentClusterTable::getInstance()
                            ->createQuery()
                            ->andWhere('comment_id = ?', $shaiwuCommentCount_v['id'])
                            ->groupby('user_id')
                            ->count();
                    }
                }*/
                $data[$k]['comment_count'] = $shaiwuCommentCount;
                $k++;
            }
        }

        //排序
        $sort_arr = array();
        foreach($data as $data_k=>$data_v){
            $sort_arr[$data_k] = $data_v['comment_count'];
        }
        array_multisort($sort_arr, SORT_DESC, $data);


        //导出
        header( "Content-type:   application/octet-stream ");
        header( "Accept-Ranges:   bytes ");
        header( "Content-type:application/vnd.ms-excel ;charset=utf-8");//自己写编码
        header( "Content-Disposition:attachment;filename=活动数据.xls ");

        echo "<table width='100%' border='1' cellspacing='0'>\n"; //边框
        echo "<tr>";
        echo "<td  align='center'>  <font size=4>晒物人ID </font></td>";
        echo "<td  align='center'>  <font size=4>晒物ID </font></td>";
        echo "<td  align='center'>  <font size=4>晒物标题 </font></td>";
        echo "<td  align='center'>  <font size=4>评论数 </font></td>";
        echo "<td  align='center'>  <font size=4>点赞数 </font></td>";
        echo "<td  align='center'>  <font size=4>发表时间 </font></td>";
        echo "</tr>\n";
        $i = 1;


        if(!empty($data)) {
            foreach($data as $k=>$v) {
                echo "<tr>";
                echo "<td  align='center'>".$v['author_id']."</td>\t";
                echo "<td  align='center'>".$v['id']."</td>\t";
                echo "<td  align='center'>".$v['title']."</td>\t";
                echo "<td  align='center'>".$v['comment_count'] ."</td>\t";
                echo "<td  align='center'>".$v['support']."</td>\t";
                echo "<td  align='center'>".$v['created_at']."</td>\t";
                echo "</tr>\n";
                $i++;
            }
        }
        echo "</table>";
        exit;
    }


    /*临时处理*/
    private function _export_20150908(){
        $keys = array('product_type_r_c','daigou_tags_type_r_c');
        $tmpkeys = array('product_type_tmp_num','daigou_tags_type_tmp_num');

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        foreach($keys as $redis_key=>$redis_val){
            $all = $redis->hGetAll($redis_val);
            ksort($all);
            foreach($all as $key=>$val){
                if($redis_val == 'product_type_r_c'){
                    if($key >= 87000){
                        break;
                    }
                }elseif($redis_val == 'daigou_tags_type_r_c'){
                    if($key >= 32){
                        break;
                    }
                }
                echo $key.'删除成功'.PHP_EOL;
                $redis->hdel($redis_val, $key);
                $redis->set($tmpkeys[$redis_key], 1);
                usleep(20);
            }
        }

        exit;
    }

    public function _export_20151008(){
        $itmeAll = trdItemAllTable::getInstance()->createQuery()->select('id,img_url')->orderBy('id DESC')->limit(300)->execute();

        foreach($itmeAll as $itmeAllVal){
           if(strpos($itmeAllVal->getImgUrl(), 'http://shihuo.hupucdn.com') !== false){
               $itmeAllVal->setImgUrl(str_replace( 'http://shihuo.hupucdn.com', '', $itmeAllVal->getImgUrl()));
               $itmeAllVal->save();
           }
        }
      //  FunBase::myDebug($itmeAll->toArray());
     exit;
    }

    public function _export_201510082(){
        $itmeAll = trdBaoliaoTable::getInstance()->createQuery()->select('id,img_url')->orderBy('id DESC')->limit(300)->execute();

        foreach($itmeAll as $itmeAllVal){
            if(strpos($itmeAllVal->getImgUrl(), 'http://shihuo.hupucdn.com') !== false){
                $itmeAllVal->setImgUrl(str_replace( 'http://shihuo.hupucdn.com', '', $itmeAllVal->getImgUrl()));
                $itmeAllVal->save();
            }
        }
        //  FunBase::myDebug($itmeAll->toArray());
        exit;
    }

    //2015双11导入店家优惠券进redis
    private function _export_2015102201(){
        $redis    = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(6);

        $redis_key =  "trade:20151111:lottery:coupon";

        $path   = $_SERVER['DOCUMENT_ROOT'].'/js/trade/csv/coupon_20151111.csv';
        $handle = fopen($path,"r");
        $n = 0;
        while ($data = fgetcsv($handle, 500, ",")){
            if($n == 0){
                $n ++;
                continue;
            }

            //转成utf-8
            $data[0] = iconv('gb2312','utf-8',$data[0]);
            $data[1] = iconv('gb2312','utf-8',$data[1]);

            echo '--success';
            if(true){
                $redis->sadd($redis_key,json_encode(array(
                        "name"   => $data[0],
                        "amount" => $data[1],
                        "link"   => $data[2],
                    )));

                echo '--success';
            }
            $n++;
        }
    }

    //擂台生成签名
    private $acivity_sign_key      = 'trademobile:user:activity:sign:v';             //签名使用库key
    private $acivity_sign_store_key      = 'trademobile:user:activity:store:sign:v'; //签名存储库key
    private $acivity_sign_version  = 1;  //签名版本号
    private function _export_20151118($request){
        $num    = $request->getParameter('num', 10000);
        $redis  = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(4);

        $expire_time = 3600*24*30;
        //签名库key加版本号
        $this->acivity_sign_version_key = $this->acivity_sign_key.$this->acivity_sign_version;
        $this->acivity_sign_version_store_key = $this->acivity_sign_store_key.$this->acivity_sign_version;

        for($i = 0;$i< $num;$i++){
            $sign = md5(uniqid().rand(20, 1000));

            $redis->sadd($this->acivity_sign_version_key, $sign);
            $redis->sadd($this->acivity_sign_version_store_key, $sign);
        }

        $redis->expire($this->acivity_sign_version_key, $expire_time);
        $redis->expire($this->acivity_sign_version_store_key, $expire_time);
    }


    //海淘活动提交接口
    private function _export_20151123(){
        $this->setLayout(false);
        $this->setTemplate('_export_20151123');
    }


    private   $daigou_old_img_key = 'trade:activity:goods:old:img'; //商品旧图
    private   $daigou_old_img_acount_flag_key = 'trade:activity:acount:flag:goods:old:img'; //商品旧图
    private   $watermark = 'http://www.shihuo.cn/images/trade/activity/blackFriday/{off}_off.png'; //水印图
    private   $activity_set_ids = array(//黑五活动集合ID
        157 => 8,
        158 => 7.5,
        159 => 7.5,
        160 => 7
    );
    //集合图片全部更迭【修改已有的商品】
    private function _export_2015112302($request){ //
        if(sfConfig::get('sf_environment') == 'dev') {
            $this->activity_set_ids = array(
                32 => 7.5
            );
        }
        //集合ID
        $set_id = $request->getParameter('set_id');
        if(!$set_id) die('not set id');

        $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
        $redis->select(5);

        $activity = TrdMarketingActivityTable::getInstance()->createQuery()
            ->where('status = ?',3)
            ->andwhere('scope = 2')
            ->andWhere('group_id = ?', $set_id)
            ->fetchArray();

        if($activity){
            $activity = array_shift($activity);
            $page = $redis->get($this->daigou_old_img_acount_flag_key) ? $redis->get($this->daigou_old_img_acount_flag_key) : 1;
            $pagesize = 200;

            $param = array();
            $param['aid'] = array($activity['id']);
            $param['pageSize'] = $pagesize;

            while(true){
                $param['pageNo'] = $page;

                $daigouSearch = new daigouSearch();
                $res = $daigouSearch->search($param);

                if (!empty($res['result'])){
                    $ids = $this->getIds($res['result']);

                    foreach($ids as $id){
                        $product = trdProductAttrTable::getInstance()->find($id);

                        if($product && $product->getPurchaseFlag() == 0 && !$redis->hget($this->daigou_old_img_key, $id)){
                            $watermark_img  = str_replace('{off}', $this->activity_set_ids[$set_id], $this->watermark);
                            $watermark = $product->getImgPath()."?watermark/1/image/".base64_encode($watermark_img)."/dx/10/dy/10/gravity/SouthEast/ws/0.2";

                            $old_name  = substr($product->getImgPath(), strrpos($product->getImgPath(), '/'));

                            $tradeQiNiu = new tradeQiNiu();
                            $imgs_return = $tradeQiNiu->saveas($watermark, "blackfriday2/{$set_id}{$old_name}");
                            $imgs_return = json_decode($imgs_return, true);

                            if($imgs_return['status']){
                                if(!$redis->hget($this->daigou_old_img_key, $id))
                                    $redis->hset($this->daigou_old_img_key, $id, $product->getImgPath());

                                $product->setImgPath($imgs_return['url']);
                                $product->save();
                            }
                        }

                        unset($product);
                    }

                    ++$page;
                    $redis->set($this->daigou_old_img_acount_flag_key, $page, 3600*24*90);
                }else{
                    echo '没有资源当前'.$page.'页';exit;
                }
            }
        }else{
            echo '没有此活动'.$this->activity_set_ids;exit;
        }
        exit;
    }

    //集合图片全部更迭【还原已有的商品】
    private function _export_2015112303($request){
        $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
        $redis->select(5);

        $ids = $redis->hgetall($this->daigou_old_img_key);

        foreach($ids as $id=>$iamg_path){
            $product = trdProductAttrTable::getInstance()->find($id);
            $product->setImgPath($iamg_path);
            $product->save();

            $redis->hdel($this->daigou_old_img_key, $id);
        }

        $redis->set($this->daigou_old_img_acount_flag_key, 0 , 3600*24*90);
        exit;
    }

    //单个ID
    private function _export_2015112304($request){
        $id  =  (int)$request->getParameter('id','');
        $set_id  =  (int)$request->getParameter('set_id','');
        if(!$id||!$set_id) exit( 'not params');
        $product = trdProductAttrTable::getInstance()->find($id);

        $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
        $redis->select(5);
        if($product && $product->getPurchaseFlag() == 0 && !$redis->hget($this->daigou_old_img_key, $id)) {
            $watermark_img  = str_replace('{off}', $this->activity_set_ids[$set_id], $this->watermark);
            $watermark = $product->getImgPath()."?watermark/1/image/".base64_encode($watermark_img)."/dx/10/dy/10/gravity/SouthEast/ws/0.2";
            $old_name  = substr($product->getImgPath(), strrpos($product->getImgPath(), '/'));

            $tradeQiNiu = new tradeQiNiu();
            $imgs_return = $tradeQiNiu->saveas($watermark, "blackfriday/{$this->activity_set_ids}{$old_name}");
            $imgs_return = json_decode($imgs_return, true);

            if ($imgs_return['status']) {
                $redis->hset($this->daigou_old_img_key, $id, $product->getImgPath());

                $product->setImgPath($imgs_return['url']);
                $product->save();
            }
        }

        exit;
    }

    //单个ID还原
    private function _export_2015112305($request){
        $id    =  (int)$request->getParameter('id','');

        $redis = sfContext::getInstance()->getDatabaseConnection('tradeActivityRedis');
        $redis->select(5);

        $iamg_path = $redis->hget($this->daigou_old_img_key, $id);
        if($iamg_path){
            $product = trdProductAttrTable::getInstance()->find($id);
            $product->setImgPath($iamg_path);
            $product->save();

            $redis->hdel($this->daigou_old_img_key, $id);
        }

        exit;
    }

    private function _export_2015112401(){
        //2015黑五专场 海淘专场
        //$cate_ids = trdSpecialCateTable::getIdByName(array('一角钱的商品荟萃','每日9.9'));
        $cate_ids = trdSpecialCateTable::getIdByName(array('2015黑五专场','海淘专场'));
        //$cate_ids = array(466);

        if($cate_ids){
           $special = trdSpecialTable::getInstance()
               ->createQuery()
               ->select('id,info,template')
               ->whereIn('cateid', $cate_ids)
               ->andwhere('template = 1')
               ->andwhere('type = 0')
               ->orderby('created_at desc')
               ->fetchArray();


           $pattern_daigpu = '/http:\/\/www\.shihuo\.cn\/haitao\/buy\/(\d+)[-]{0,1}.*?/si';
           foreach($special as $special_key=>$special_val){
               $info = json_decode($special_val['info'], true);
               //if($special_val['id'] != 108) continue;

               $table = trdSpecialTable::getInstance()->find($special_val['id']);

               if(!empty($info['attr']['cates']['catetitle'])){
                   $cate_titles = $info['attr']['cates']['catetitle'];
                   foreach($cate_titles as $cate_title_key=>$cate_title_val){
                       foreach($info['data']['cateitemurl'.$cate_title_key] as $data_key=>$data_val){
                           if($data_val){
                               preg_match($pattern_daigpu, $data_val, $data_url);
                               if(isset($data_url[1])){
                                   $product = TrdProductAttrTable::getInstance()->find($data_url[1]);
                                   if($product){
                                       $info['data']['cateiteminputfile'.$cate_title_key][$data_key] = $product->getImgPath();
                                   }
                               }
                           }
                       }
                   }
               }

               $table->setInfo(json_encode($info));
               $table->save();
               unset($table);
           }
        }
        exit;
    }

    //清单导出
    private function _export_2015120101(){
        header( "Content-type:   application/octet-stream ");
        header( "Accept-Ranges:   bytes ");
        header( "Content-type:application/vnd.ms-excel ;charset=utf-8");//自己写编码
        header( "Content-Disposition:attachment;filename=活动数据.xls ");

        $data = trdDaigouInventoryTable::getInstance()->findAll()->toArray();

        echo "<table width='100%' border='1' cellspacing='0'>\n"; //边框
        echo "<tr>";
        echo "<td  align='center'>  <font size=4>用户名 </font></td>";
        echo "<td  align='center'>  <font size=4>标题 </font></td>";
        echo "<td  align='center'>  <font size=4>类型 </font></td>";
        echo "<td  align='center'>  <font size=4>点赞数 </font></td>";
        echo "<td  align='center'>  <font size=4>清单商品数 </font></td>";
        echo "<td  align='center'>  <font size=4>发表时间 </font></td>";
        echo "</tr>\n";
        $i = 1;


        if(!empty($data)) {
            foreach($data as $k=>$v) {
                echo "<tr>";
                echo "<td  align='center'>".$v['hupu_username']."</td>\t";
                echo "<td  align='center'>".$v['title']."</td>\t";
                echo "<td  align='center'>".trdDaigouInventory::$_type[$v['type_id']]."</td>\t";
                echo "<td  align='center'>".$v['like_count'] ."</td>\t";
                echo "<td  align='center'>".$v['goods_num']."</td>\t";
                echo "<td  align='center'>".$v['created_at']."</td>\t";
                echo "</tr>\n";
                $i++;
            }
        }
        echo "</table>";
        exit;
    }

    private function _export_2015121101(){
        $groupon = trdGrouponTable::getInstance()->find(13261);//13261

        $attr = unserialize($groupon->getAttr());
        $pic_attr = json_encode(array('normal_img'=>array($attr['images_frist']), 'auditing_img'=>array()));
        $memo     = base64_encode(gzcompress(json_encode($groupon->getMemo())));

        $groupon_treasure_table = new trdGrouponTreasure();
        $groupon_treasure_table->setTitle($groupon->getTitle());
        $groupon_treasure_table->setMemo($memo);
        $groupon_treasure_table->setHupuUid($groupon->getHupuUid());
        $groupon_treasure_table->setHupuUsername($groupon->getHupuUsername());
        $groupon_treasure_table->setGoodsNum($groupon->getGoodsNum());
        $groupon_treasure_table->setUrl($groupon->getUrl());
        $groupon_treasure_table->setBrandId($groupon->getBrandId());
        $groupon_treasure_table->setCategoryId($groupon->getCategoryId());
        $groupon_treasure_table->setPrice($groupon->getPrice());
        $groupon_treasure_table->setOriginalPrice($groupon->getOriginalPrice());
        $groupon_treasure_table->setDiscount($groupon->getDiscount());
        $groupon_treasure_table->setPicAttr($pic_attr);
        $groupon_treasure_table->setSuperiority('');
        $groupon_treasure_table->save();
    }

    /*获取ID*/
    private function  getIds($arr){
        $new_arr = array();

        if(is_array($arr)){
            foreach($arr as $k=>$v){
                $new_arr[] =  $v['id'];
            }
        }

        return $new_arr;
    }
}