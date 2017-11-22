<?php
/*
 *stone
 **/
Class stoneAction extends sfActions{
    private $redis;
    private $stone_producer_key = 'trade2016:stone:producer';
    public function preExecute(){
        parent::preExecute();
    }

    public function executeStone(sfWebRequest $request){
        //action
        $act = 'execute'.ucfirst($request->getParameter('act','xxxxxx'));
        if(method_exists( $this, $act )){
            return $this->$act($request);
        }else{
            return sfView::NONE;
        }
    }

    /*
    *生成处理的信息
    **/
    private function executeProducer(sfWebRequest $request){
        sfConfig::set('sf_web_debug', false);
        $store = $request->getParameter('store');

        $goodsSupplier =  $this->getAvailable($store);
        if($goodsSupplier){
            $updateInfo = $goodsSupplier->getUpdateInfo();
            if($updateInfo){
                $updateInfo = json_decode($updateInfo, true);
                $uniqueId =  $updateInfo['md5'];
            }else{
                $uniqueId = '';
            }

            $return = array(
                'status'=>true,
                'data'=>array(
                    'id'=>$goodsSupplier->getId(),
                   'url'=>$goodsSupplier->getUrl(),
                   'md5'=>$uniqueId
               )
            );
        }else{
            $return = array(
                'status'=>false,
                'msg'=>'not found!'
            );
        }

       return $this->renderText(json_encode($return));
    }


    /*
     *
     *处理商品信息
     **/
    public function executeConsumer(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $info = $request->getParameter('info');
        $info = json_decode($info,true);

        $id = (int)$info['Id'];
        $status = !empty($info['Status']) ? $info['Status'] : array();
        $data   = !empty($info['Data']) ? $info['Data'] : array();
        $error  = !empty($info['Msg']) ? $info['Msg'] : array();

        if($id && $status){
            $goodsSupplier = trdGoodsSupplierTable::getInstance()->find($id);
            if(!$goodsSupplier){
                return $this->renderText(json_encode(array(
                    'status'=>false
                )));
            }

            if(1 == $status){//错误
                $error = is_array($error) ? json_encode($error, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) : $error;

                $goodsSupplier->setUpdateTime(date('Y-m-d H:i:s'));
                $goodsSupplier->setUpdateErrorNum($goodsSupplier->getUpdateErrorNum()+1);
                $goodsSupplier->setUpdateErrorInfo($error);
                $goodsSupplier->save();
            }elseif(3 == $status){//正常
                $validate = trdGoodsSupplierTable::getInstance()->createQuery()
                    ->andWhere('id != ?', $id)
                    ->andWhere('goods_id = ?', $goodsSupplier->getGoodsId())
                    ->andWhere('unique_id = ?', $data['Unique'])
                    ->fetchOne();

                if(!$validate){
                    if($data['Status'] == 'inStock'){
                        $rate  = TrdHaitaoCurrencyExchangeTable::getRate(strtolower($data['Items'][0]['Offers'][0]['List'][0]['Type']));
                        $price = $data['Items'][0]['Offers'][0]['List'][0]['Price'] * $rate;

                        $goodsSupplier->setUniqueId($data['Unique']);
                        $goodsSupplier->setPrice($price);
                        $goodsSupplier->setUpdateTime(date('Y-m-d H:i:s'));
                        $goodsSupplier->setUpdateErrorNum(0);
                        $goodsSupplier->setUpdateErrorInfo(NULL);
                        $goodsSupplier->setUpdateInfo(json_encode(array(
                            'unique'=>$data['Unique'],
                            'md5'   => $data['Md5'],
                            'status'=> $data['Status']
                        ),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
                    }else{
                        $goodsSupplier->setStatus(1); //下架
                        $goodsSupplier->setUpdateTime(date('Y-m-d H:i:s'));
                        $goodsSupplier->setUpdateErrorNum(0);
                        $goodsSupplier->setUpdateErrorInfo(NULL);
                        $goodsSupplier->setUpdateInfo(json_encode(array(
                            'unique'=> '',
                            'md5'   => '',
                            'status'=> $data['Status']
                        ),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
                    }

                    $goodsSupplier->save();
                }else{
                    trdGoodsSupplierTable::getInstance()->createQuery()->andWhere('id = ?', $id)->execute()->delete();
                }
            }elseif(2 == $status){//未更改
                $goodsSupplier->setUpdateTime(date('Y-m-d H:i:s'));
                $goodsSupplier->save();
            }

            //消除redis
            $this->redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $this->redis->select(6);
            $this->redis->hdel($this->stone_producer_key, $id);

            return $this->renderText(json_encode(array(
                'status'=>true
            )));
        }else{
            return $this->renderText(json_encode(array(
                'status'=>false
            )));
        }
    }

    /*
    *识货来源处理
    *
    **/
    public function executeShihuoGoods(sfWebRequest $request)
    {
        sfConfig::set('sf_web_debug', false);
        $url = $request->getParameter('url');

        $return = array('status'=>false);
        if($url){
            $pattern_buy = '/.*?shihuo\.cn\/haitao\/buy\/(\d+)[-]{0,1}.*?/si';
            preg_match($pattern_buy, $url, $match);

            if (!empty($match[1])) {
                $serviceRequest = new tradeServiceClient();
                $serviceRequest->setMethod('daigouproduct.detail.get');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('product_id', $match[1]);
                $response = $serviceRequest->execute();
                $data = $response->getData();

                if($data['status'] == 200){
                    if ($data['data']['is_self_business']) {//自营
                        $return['status'] = true;
                        $return['type'] = 1;
                        $return['data'] = array(
                            'id'=>$data['data']['product_id'],
                            'url'=>$url,
                            'title'=>$data['data']['title'],
                            'price'=>$data['data']['price'],
                            'stock'=>'inStock',
                        );
                    }else{//海外
                        $return['status'] = true;
                        $return['type'] = 2;
                        $return['data'] = array(
                            'id' => $data['data']['product_id'],
                            'url'=> $data['data']['url'],
                        );
                    }
                }
            }
        }

        return $this->renderText(json_encode($return));
    }

    //获取可用的处理信息
    private function getAvailable($store){
        $this->redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $this->redis->select(6);

        //where 条件
        $wheres = array(
            'status = 0',
            '(update_time < "'.date('Y-m-d H:i:s',strtotime(date('Y-m-d'))) .'" or update_time is null)',
            "store != '其他'",
            "store != '识货团购'"
        );
        if('guonei' == $store){
            array_push($wheres,  "store in ('淘宝','天猫','nike商城','优购','中亚','识货自营')");
        }elseif('guowai' == $store){
            array_push($wheres,  "store in ('6pm','美亚','日亚','识货海淘')");
        }elseif($store && 'all' != $store){
            array_push($wheres,  'store = "'.$store.'"');
        }

        while(true){
            $trdGoodsSupplierTable = trdGoodsSupplierTable::getInstance()->createQuery();
            foreach($wheres as $where){
                $trdGoodsSupplierTable = $trdGoodsSupplierTable->andWhere($where);
            }
            $goodsSupplier = $trdGoodsSupplierTable->orderby('update_time asc')->limit(1)->fetchOne();

            if($goodsSupplier){
                //redis 查询可用性
                $stone_producer = $this->redis->hget($this->stone_producer_key, $goodsSupplier->getId());
                if($stone_producer){
                    $stone_producer = json_decode($stone_producer, true);
                    if(time() - $stone_producer['time'] > 180){
                        $this->redis->hset($this->stone_producer_key, $goodsSupplier->getId() ,json_encode(array(
                            'id'   => $goodsSupplier->getId(),
                            'time' => time()
                        )));
                        break;
                    }else{
                        array_push($wheres, "id !={$goodsSupplier->getId()}");
                    }
                }else{
                    $this->redis->hset($this->stone_producer_key, $goodsSupplier->getId() ,json_encode(array(
                        'id'   => $goodsSupplier->getId(),
                        'time' => time()
                    )));
                    break;
                }
            }else{
                break;
            }
        }

        return $goodsSupplier;
    }

}