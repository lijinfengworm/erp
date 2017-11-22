<?php
/*
 *stoneComment
 **/
Class stoneCommentAction extends sfActions{
    private $redis;
    private $stone_producer_key = 'trade2016:stonecomment:producer';
    private $filter_txt = array(
    );

    public function executeStoneComment(sfWebRequest $request){
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
        $store = $request->getParameter('store','all');

        $goodsSupplier =  $this->getAvailable($store);
        if($goodsSupplier){
            $return = array(
                'status'=>true,
                'data'=>array(
                    'id'=>$goodsSupplier->getId(),
                    'url'=>$goodsSupplier->getUrl(),
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
    public function executeConsumer(sfWebRequest $request){
        sfConfig::set('sf_web_debug', false);
        $info = $request->getParameter('info');
        $info = json_decode($info,true);

        $id = (int)$info['Id'];
        $status = !empty($info['Status']) ? $info['Status'] : array();
        $data   = !empty($info['Data']) ? $info['Data'] : array();
        $error  = !empty($info['Msg']) ? $info['Msg'] : array();

        if($id && $status){
            $goodsSupplier = TrdGoodsSupplierTable::getInstance()->find($id);

            if(2 == $status){
                if(is_array($data['Comments'])){
                    foreach($data['Comments'] as $comment){
                        $Photos =  !empty($comment['Photos']) ? $comment['Photos'] : null;
                        if($Photos){
                            foreach($Photos as &$Photo){
                                if (stristr($Photo, '_400x400.jpg')) {
                                    $Photo = strstr($Photo, '_400x400.jpg', true);
                                }
                                $Photo = tradeCommon::getQiNiuProxyPath($Photo);
                            }

                            $Photos =  json_encode($Photos);
                        }

                        if(!trdGoodsSupplierCommentTable::getInstance()
                            ->createQuery()
                            ->andWhere('supplier_id = ?', $id)
                            ->andWhere('unique_id = ?', $comment['Md5'])
                            ->fetchOne()
                        ){
                            $goodsSupplierComment = new trdGoodsSupplierComment();
                            $goodsSupplierComment->setSupplierId($id);
                            $goodsSupplierComment->setGoodsId($goodsSupplier->getGoodsId());
                            $goodsSupplierComment->setSupplierName($goodsSupplier->getName());
                            $goodsSupplierComment->setSupplierUrl($data['ShopUrl']);
                            $goodsSupplierComment->setNickname($comment['Nick']);
                            $goodsSupplierComment->setContent($comment['Content']);
                            $goodsSupplierComment->setImgAttr($Photos);
                            $goodsSupplierComment->setSku($comment['Sku']);
                            $goodsSupplierComment->setUniqueId($comment['Md5']);
                            $goodsSupplierComment->save();
                        }
                    }
                }
            }

            $goodsSupplier->setCommentUpdateTime(date('Y-m-d H:i:s'));
            $goodsSupplier->save();


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


    //获取可用的处理信息
    private function getAvailable($store){
        $this->redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $this->redis->select(6);

        //where 条件
        $wheres = array(
            'status = 0',
            '(comment_update_time < "'.date('Y-m-d H:i:s',strtotime('-2 week')) .'" or comment_update_time is null)',
            "store != '其他'"
        );

        if('all' == $store) {
            array_push($wheres, "store in ('淘宝','天猫','中亚')");
        }elseif($store){
            array_push($wheres,  'store = "'.$store.'"');
        }

        while(true){
            $trdGoodsSupplierTable = trdGoodsSupplierTable::getInstance()->createQuery();
            foreach($wheres as $where){
                $trdGoodsSupplierTable = $trdGoodsSupplierTable->andWhere($where);
            }
            $goodsSupplier = $trdGoodsSupplierTable->orderby('comment_update_time asc')->limit(1)->fetchOne();

            if($goodsSupplier){
                //redis 查询可用性
                $stone_producer = $this->redis->hget($this->stone_producer_key, $goodsSupplier->getId());
                if($stone_producer){
                    $stone_producer = json_decode($stone_producer, true);
                    if(time() - $stone_producer['time'] > 30){
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