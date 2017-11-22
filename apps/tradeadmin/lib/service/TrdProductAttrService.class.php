<?php
/**
 * 优惠信息逻辑服务
 * About  梁天
 */
class TrdProductAttrService  {

    //审核理由
    public static  $auditMessage = array(
        1=>'重复',
        2=>'价格过高',
        3=>'品牌太小众',
        4=>'品类不合适',
        0=>'其他理由',
    );

    /**
     * 连接操作
     */
    static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }



    /**
     * 保存代购
     */
    public function saveProductAttr($form,$request) {
        if ($request->isMethod('post')) {
            $post = $request->getParameter('trd_product_attr');
            if (in_array($post['business'], TrdProductAttrTable::$zhifa_business_arr)){//直发url不需要填写
                $form->setValidator('url', new sfValidatorUrl(array('required' => false, 'trim' => true), array('required' => '商品url必填', 'invalid' => '商品url格式错误')));
            }
            $form->bind($post, $request->getFiles($form->getName()));
            if ($form->isValid()) {
                $item = $form->save();
             }else{
                throw new sfException('有错误，内容保存失败。');
            }
        }
    }






    /**
     * 判断是否发布过商品
     */
    public function  CheckProductAttrExist($item_url = '') {
        if (!$item_url) throw new sfException('请输入url');
        $web_prefix = tradeCommon::getDaigouPrefix($item_url);
        if (!$web_prefix) throw new sfException('暂不支持该网站抓取');
        $urls = tradeCommon::getHaitaoRemoteIp($item_url);
        $data = $this->curl($urls, '');
        if ($data['info']['http_code'] == 200){
            $content =json_decode($data['result'],true);
        } else {
            throw new sfException($data['error']);
        }
        if (!isset($content) || empty($content)){
            throw new sfException('验证失败');
        }
        $is_shop_product = $this->isShopToProduct($content['ASIN']);
        if ($is_shop_product){
            return array('id'=>$is_shop_product->getId(),'status'=>2,'data'=>$is_shop_product->getId(), 'msg'=>'已发布过该商品了，您可以去编辑！');
        }
        return array('status'=>1,'data'=>'','msg'=>'该商品未发布过！');
    }


    /**
     *检测是否发布
     */
    public function isShopToProduct($asin = '') {
        if(empty($asin)) return false;
        $product = TrdProductAttrTable::getInstance()->createQuery()
            ->select('*')->where('name = ?',$asin)
            ->andWhere('status = ?',0)->andWhere('show_flag = ?',0)
            ->orderBy('updated_at desc')->limit(1)->fetchOne();
        if(!$product) {
            $product = TrdProductAttrTable::getInstance()->createQuery()
                ->select('*')->where('name = ?',$asin)->orderBy('updated_at desc')->limit(1)->fetchOne();
        }
        if($product) return $product;
        return false;
    }



    /**
     * 识货新版海淘抓取商品
     */
    public function NewGetOverseasProductAttr($id = '',$item_url = '',$is_check = false) {
        if (!$id || !$item_url) throw new sfException('参数未传递!');
        $web_prefix = tradeCommon::getDaigouPrefix($item_url);
        if (!$web_prefix) throw new sfException('暂不支持该网站抓取!');
        $info = TrdProductAttrTable::getInstance()->find($id);
        if (!$info) throw new sfException('不存在该条记录!');

        //在抓取前更新时间 相同商品的更新量
        $info->setLastCrawlDate(time());
        $info->save();

        //抓取时间太长 关闭数据库连接不然会超时
        TrdProductAttrTable::getInstance()->getConnection()->close();

        $urls = tradeCommon::getHaitaoRemoteIp($item_url);

        $data = $this->curl($urls, '');
        if ($data['info']['http_code'] == 200){
            $content =json_decode($data['result'],true);
        } else {
            throw new sfException($data['error']);
        }
        if (!isset($content) && empty($content)) throw new sfException('抓取服务器出错，请联系程序猿');

        //如果开启了验证 那么就先判断代沟表是否已经有此代购
        if($is_check) {
            $chk_product = $this->isShopToProduct($content['ASIN']);
            if(!empty($chk_product) && $chk_product['id'] != $id) {
                return  array('id'=>$chk_product->getId(),'is_error'=>1,'error_code'=>1,'error_msg'=>'repeat');
            }
        }
        //更新goods表状态
        TrdHaitaoGoodsTable::updateStatusByProductId($id);
        $json_string = "";
        $low_price = $low_gid =  0;
        $business_weight = 0;
        if (isset($content['ItemAttributes']['PackageDimensions']['Weight']['Num']) && !empty($content['ItemAttributes']['PackageDimensions']['Weight']['Num'])){
            $business_weight = $content['ItemAttributes']['PackageDimensions']['Weight']['Num']/100;
        }
        /*  -------  更新商品表  --------*/
        if (isset($content['Variations']['VariationDimensions']['VariationDimension']) && !empty($content['Variations']['VariationDimensions']['VariationDimension'])){
            $VariationDimension = (array)$content['Variations']['VariationDimensions']['VariationDimension'];
            foreach ($VariationDimension as $k=>$v){//存储属性
                if (trim($v)) $json_string['attr'][] = trim($v);
            }
            $json_arr = array();
            foreach ($content['items'] as $k=>$v){//存储属性
                $flag = false;
                $json_attr_str = '';
                if (isset($v['Offers']['Offer']['OfferListing']['IsEligibleForSuperSaverShipping']) && isset($v['Offers']['Offer']['Merchant']['Name']) &&($v['Offers']['Offer']['OfferListing']['IsEligibleForSuperSaverShipping'] == 1 || $v['Offers']['Offer']['Merchant']['Name'] == 'Under Armour' || $v['Offers']['Offer']['Merchant']['Name'] == 'Amazon.com' || $v['Offers']['Offer']['Merchant']['Name'] == '6PM' || $v['Offers']['Offer']['Merchant']['Name'] == 'GNC' || $v['Offers']['Offer']['Merchant']['Name'] == 'Levis') && isset($v['Offers']['Offer']['OfferListing']['Price']['Amount'])){
                    if (isset($v['LargeImage']['URL']) && !empty($v['LargeImage']['URL'])){//只存储有图片的
                        $json_string['content'][$k]['name'] = $v['ASIN'];
                        if (empty($v['LargeImage']['URL']) && isset($v['ImageSets']['ImageSet'][0]['LargeImage']['URL'])) {
                            $v['LargeImage']['URL'] = $v['ImageSets']['ImageSet'][0]['LargeImage']['URL'];
                        }
                        $json_string['content'][$k]['img'] = isset($v['LargeImage']['URL']) && !empty($v['LargeImage']['URL']) ? $v['LargeImage']['URL'] : 'http://www.shihuo.cn/images/trade/no-image.png';
                        foreach ($v['VariationAttributes']['VariationAttribute'] as $kk=>$vv){
                            if (!isset($vv['Value'])){
                                $flag = true;
                                unset($json_string['content'][$k]);
                                break;
                            }
                            if (!isset($json_string[$vv['Name']]) || (isset($json_string[$vv['Name']]) && !in_array($vv['Value'],$json_string[$vv['Name']]))){
                                $json_string[$vv['Name']][] =  $vv['Value'];
                            }
                            $json_string['content'][$k][$vv['Name']] = $vv['Value'];
                            $json_attr_str .= $vv['Value'];
                        }
                        if ($flag) continue;
                        $json_string['content'][$k]['price'] = $v['Offers']['Offer']['OfferListing']['Price']['Amount'];
                        $json_arr_key = md5($json_attr_str);
                        //是否有重复属性的数据判断 取价格大的
                        if(isset($json_arr[$json_arr_key]) && $json_arr[$json_arr_key]['price']>=$json_string['content'][$k]['price']){
                            unset($json_string['content'][$k]);
                            break;
                        } else{
                            if(isset($json_arr[$json_arr_key])) unset($json_string['content'][$json_arr[$json_arr_key]['id']]);
                            $json_arr[$json_arr_key]['price'] = $json_string['content'][$k]['price'];
                            $json_arr[$json_arr_key]['id'] = $k;
                        }
                        $json_string['content'][$k]['code'] = $v['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'];
                        if (isset($v['ImageSets']['ImageSet']) && !empty($v['ImageSets']['ImageSet'])){//处理图片
                            $v['ImageSets']['ImageSet'] = $this->_formatImages($v['ImageSets']['ImageSet']);
                            if (count($v['ImageSets']['ImageSet']) > 5){
                                $v['ImageSets']['ImageSet'] = array_slice($v['ImageSets']['ImageSet'], 0, 5);
                            }
                        } else {
                            $v['ImageSets']['ImageSet'][0]['LargeImage']['URL'] = 'http://www.shihuo.cn/images/trade/no-image.png';
                        }
                        //保存到商品表
                        tradeCommon::getLock('updateHaitaoGood.'.'usa.amazon.'.$v['ASIN'],5);
                        $goods = TrdHaitaoGoodsTable::getInstance()->findOneByGoodsId($web_prefix.$v['ASIN']);
                        if(!$goods) {
                            $goods = new TrdHaitaoGoods();
                        }
                        $goods->setProductId($id);
                        $goods->setGoodsId($web_prefix.$v['ASIN']);
                        $goods->setTitle($v['ItemAttributes']['Title']);
                        if(!isset($v['ItemAttributes']['UPC'])) $v['ItemAttributes']['UPC']='';
                        if(!isset($v['ItemAttributes']['EAN'])) $v['ItemAttributes']['EAN']='';
                        $code = empty($v['ItemAttributes']['UPC'])?$v['ItemAttributes']['EAN']:$v['ItemAttributes']['UPC'];
                        $goods->setCode($code);
                        $goods->setStatus(0);
                        $goods->setAttr(json_encode($v));
                        $goods->save();
                        tradeCommon::releaseLock('updateHaitaoGood.'.'usa.amazon.'.$v['ASIN']);
                        $json_string['content'][$k]['gid'] = $goods->getId();
                        if (!$low_price){
                            $low_price = $json_string['content'][$k]['price'];
                            $low_gid = $goods->getId();
                        } else if($low_price>$json_string['content'][$k]['price']){
                            $low_price = $json_string['content'][$k]['price'];
                            $low_gid = $goods->getId();
                        }
                    }
                }
            }
            unset($json_arr);//删除临时数组
            if (!isset($json_string['content'])){//没有自营商品 下线
                $product_info = TrdProductAttrTable::getInstance()->find($id);
                $product_info->setShowFlag(0);
                $product_info->save();
                throw new sfException('该商品已没有亚马逊自营或者UA销售的!');
            }
            sort($json_string['content']);
        }
        if (isset($content['item']) && !empty($content['item'])){
            if (isset($content['item']['Offers']['Offer']['Merchant']['Name']) &&($content['item']['Offers']['Offer']['OfferListing']['IsEligibleForSuperSaverShipping'] == 1 || $content['item']['Offers']['Offer']['Merchant']['Name'] == 'Under Armour' || $content['item']['Offers']['Offer']['Merchant']['Name'] == 'Amazon.com' || $content['item']['Offers']['Offer']['Merchant']['Name'] == '6PM' || $content['item']['Offers']['Offer']['Merchant']['Name'] == 'GNC' || $content['item']['Offers']['Offer']['Merchant']['Name'] == 'Levis') && isset($content['item']['Offers']['Offer']['OfferListing']['Price']['Amount'])){
                $json_string['attr'] = '';
                $json_string['content'][0]['name'] = $content['ASIN'];
                if (empty($content['item']['LargeImage']['URL']) && isset($content['item']['ImageSets']['ImageSet'][0]['LargeImage']['URL'])) {
                    $content['item']['LargeImage']['URL'] = $content['item']['ImageSets']['ImageSet'][0]['LargeImage']['URL'];
                }
                $json_string['content'][0]['img'] = isset($content['item']['LargeImage']['URL']) && !empty($content['item']['LargeImage']['URL']) ? $content['item']['LargeImage']['URL'] : 'http://www.shihuo.cn/images/trade/no-image.png';
                $json_string['content'][0]['price'] = $content['item']['Offers']['Offer']['OfferListing']['Price']['Amount'];
                $json_string['content'][0]['code'] = $content['item']['Offers']['Offer']['OfferListing']['Price']['CurrencyCode'];

                if (isset($content['item']['ImageSets']['ImageSet']) && !empty($content['item']['ImageSets']['ImageSet'])){//处理图片
                    $content['item']['ImageSets']['ImageSet'] = $this->_formatImages($content['item']['ImageSets']['ImageSet']);
                    if (count($content['item']['ImageSets']['ImageSet']) > 5){
                        $content['item']['ImageSets']['ImageSet'] = array_slice($content['item']['ImageSets']['ImageSet'], 0, 5);
                    }
                } else {
                    $content['item']['ImageSets']['ImageSet'][0]['LargeImage']['URL'] = 'http://www.shihuo.cn/images/trade/no-image.png';
                }
                //保存到商品表
                tradeCommon::getLock('updateHaitaoGood.'.'usa.amazon.'.$content['item']['ASIN'],5);
                $goods = TrdHaitaoGoodsTable::getInstance()->findOneByGoodsId($web_prefix.$content['item']['ASIN']);
                if(!$goods) {
                    $goods = new TrdHaitaoGoods();
                }
                $goods->setProductId($id);
                $goods->setGoodsId($web_prefix.$content['item']['ASIN']);
                $goods->setTitle($content['item']['ItemAttributes']['Title']);
                if(!isset($content['item']['ItemAttributes']['UPC'])) $content['item']['ItemAttributes']['UPC']='';
                if(!isset($content['item']['ItemAttributes']['EAN'])) $content['item']['ItemAttributes']['EAN']='';
                $code = empty($content['item']['ItemAttributes']['UPC'])?$content['item']['ItemAttributes']['EAN']:$content['item']['ItemAttributes']['UPC'];
                $goods->setCode($code);
                $goods->setStatus(0);
                $goods->setAttr(json_encode($content['item']));
                $goods->save();
                tradeCommon::releaseLock('updateHaitaoGood.'.'usa.amazon.'.$content['item']['ASIN']);
                $json_string['content'][0]['gid'] = $goods->getId();
                if (!$low_price){
                    $low_price = $json_string['content'][0]['price'];
                    $low_gid = $goods->getId();
                } else if($low_price>$json_string['content'][0]['price']){
                    $low_price = $json_string['content'][0]['price'];
                    $low_gid = $goods->getId();
                }
            } else {
                $product_info = TrdProductAttrTable::getInstance()->find($id);
                $product_info->setShowFlag(0);
                $product_info->save();
                throw new sfException('该商品已没有亚马逊自营或者UA销售的!');
            }
        }
        $info = TrdProductAttrTable::getInstance()->find($id);
        $info->setName($content['ASIN']);
        $LowestPrice = $low_price;
        if ($web_prefix == 'jp.amazon.') {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate('jpy');
            $price = ceil($LowestPrice*$rate*100)/100;
            $info->setExchange($LowestPrice);
        } else {
            $rate = TrdHaitaoCurrencyExchangeTable::getRate();
            $price = ceil($LowestPrice*$rate)/100;
            $info->setExchange($LowestPrice/100);
        }
        if ($low_gid) $info->setGoodsId($low_gid);
        $info->setPrice($price);
        $attr = base64_encode(gzcompress(json_encode($json_string)));
        if (strlen($attr) > 65535) {
            throw new sfException('抓取商品的属性太长!');
        }
        $info->setContent($attr);
        $info->setCrawlFlag(0);
        $info->setShowFlag(1);
        $info->setLastCrawlDate(time());
        //保存重量
        if ($business_weight) {
            if($business_weight >= 0.5) {
                $business_weight += 0.6;
            } else {
                $business_weight += 0.4;
            }
            $info->setBusinessWeight($business_weight);
        }
        $info->save();
        return array('data'=>array('id'=>$info->getId()), 'msg'=>'抓取成功');
    }



    //图片去重
    private function _formatImages($data){
        if (empty($data)) return $data;
        $return = $image = array();
        foreach ($data as $k=>$v){
            $image[] = $v['LargeImage']['URL'];
        }
        $img = array_unique($image);
        foreach($img as $kk=>$vv){
            $return[$kk]['LargeImage']['URL'] = $vv;
        }
        return $return;
    }



    /*
       * @purpose: 使用curl并行处理url
       * @return: array 每个url获取的数据
       * @param: $urls array url列表
       * @param: $callback string 需要进行内容处理的回调函数。示例：func(array)
       */
    private function curl($urls = array(), $callback = '')
    {
        $response = array();
        if (empty($urls)) {
            return $response;
        }
        $chs = curl_multi_init();
        $map = array();
        foreach($urls as $url){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip,deflate'));
            curl_setopt($ch,CURLOPT_ENCODING, '');
            curl_multi_add_handle($chs, $ch);
            $map[strval($ch)] = $url;
        }
        do{
            if (($status = curl_multi_exec($chs, $active)) != CURLM_CALL_MULTI_PERFORM) {
                if ($status != CURLM_OK) { break; } //如果没有准备就绪，就再次调用curl_multi_exec
                while ($done = curl_multi_info_read($chs)) {
                    $info = curl_getinfo($done["handle"]);
                    $error = curl_error($done["handle"]);
                    $result = curl_multi_getcontent($done["handle"]);
                    $url = $map[strval($done["handle"])];
                    $rtn = compact('info', 'error', 'result', 'url');
                    if ($rtn && empty($error) && $info['http_code'] != 500) {
                        curl_multi_close($chs);
                        return $rtn;
                        break;
                    }
                    $response = $rtn;
                    curl_multi_remove_handle($chs, $done['handle']);
                    curl_close($done['handle']);
                    //如果仍然有未处理完毕的句柄，那么就select
                    if ($active > 0) {
                        curl_multi_select($chs, 0.5); //此处会导致阻塞大概0.5秒。
                    }
                }
            }
        }
        while($active > 0); //还有句柄处理还在进行中
        curl_multi_close($chs);
        return $response;
    }





















}