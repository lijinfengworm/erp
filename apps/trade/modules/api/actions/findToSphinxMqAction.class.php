<?php

class findToSphinxMqAction extends sfAction
{

    public function execute($request)
    {
        //将item_all表中的数据导到find表中
        sfConfig::set('sf_web_debug', false);
        $mysphinxDb = new tradeSpinxMysql();
        $type = $request->getParameter('type');
        $id = $request->getParameter('id');
        if ($type == 1) { //删除
            $result = $mysphinxDb->deleteOne($id, 'find');
            //删除find product数据
            $this->deleteFindProductById($id);
            if (!$result) {
                return $this->renderText(json_encode(array('status' => 500, 'msg' => $id . ' 删除失败')));
            }
        } else { //新增、修改
            $item = TrdItemAllTable::getInstance()->find($id);
            if ($item && $item->getStatus()==0 && $item->getIsHide() == 0) {
                $info = '';
                if ($item->getRootId() && $item->getChildrenId()) {
                    $info = 'R' . $item->getRootId() . ' C' . $item->getChildrenId() . ' ' . str_replace('-', '', str_replace(',', ' ', $item->getAttrCollect())) . ' ' . 'S' . $item->getMart();
                }else{
                    $info = 'S' . $item->getMart();
                }
                $param = array(
                    'id' => $item->getId(),
                    'title' =>strval($item->getTitle()),
                    'info' => $info,
                    'hot' => $item->getClickCount(),
                    'price' => $item->getPrice(),
                    'time' => $item->getPublishDate(),
                    'infos' => $item->getTitle() . ' ' . $info,
                    /****新增字段****/
                    'info1'=>$item->getRank(),
                    'display' => $item->getIsShowsports(),
                    'mart' => $item->getMart(),
                );
                $result = $mysphinxDb->saveData($param, 'find');
                //保存数据到find product
                $this->saveToFindProduct($item);
                if ($item->getHeat() < 100){
                    $heat = mt_rand(1000, 5000);
                    $item->setHeat($heat);
                    $item->save();
                }
                if (!$result) {
                    return $this->renderText(json_encode(array('status' => 500, 'msg' => $id . '保存失败')));
                }
            } else {
                $result = $mysphinxDb->deleteOne($id, 'find');
                //删除find product数据
                $this->deleteFindProductById($id);
            }
        }
        return $this->renderText(json_encode(array('status' => 200, 'msg' => $id . ' 保存成功', 'info' => '')));
    }
    
     public function saveToFindProduct($item)
    {
        if ($item) {
            if ($item->getRootId() == 1 && $item->getChildrenId() == 8) {
                $product = TrdFindProductTable::getInstance()->find($item->getId());
                $api_url = sfConfig::get('app_javaapi');
                $falg = true;
                if (!$product) {
                    $product = new TrdFindProduct();
                    $product->setId($item->getId());
                } else {
                    $falg = false;  
                }
                $product->setTitle($item->getTitle());
                $product->setMemo($item->getMemo());
                $product->setPrice($item->getPrice());
                $product->setRootId($item->getRootId());
                $product->setChildrenId($item->getChildrenId());
                $root_name = $this->getMenuName($item->getRootId());
                $children_name = $this->getMenuName($item->getChildrenId());
                $product->setRootName($root_name->getName());
                $product->setChildrenName($children_name->getName());
                $json = '';
                if ($item->getAttrCollect()){
                    $attr = explode(',', $item->getAttrCollect());
                    foreach ($attr as $k=>$v){
                        $attr_other = explode('-',$v);
                        $group_id = ltrim($attr_other[0],'G');
                        $attr_id = ltrim($attr_other[1],'A');
                        if ($group_id == 1){
                            $attr_key = 'brand';
                        } else if ($group_id == 2){
                            $attr_key = 'type';
                        } else if ($group_id == 3){
                            $attr_key = 'sex';
                        }
                        $name = $this->getAttrName($attr_id);
                        $json["$attr_key"] = $name->getName();
                    }
                }
                if ($json){
                    $product->setAttrCollect(json_encode($json));
                }
                if ($item->getPublishDate()){
                    $product->setPublishDate(date('Y-m-d H:i:s',$item->getPublishDate()));
                } else {
                    $product->setPublishDate($item->getCreatedAt());
                }
                $product->setIsShowsports($item->getIsShowsports());
                $product->setTag($item->getTagCollect());
                $product->save();
                
                if ($falg){
                    $shoe_put = tradeCommon::requestUrl($api_url['url'].'/so/find/'.$item->getId(),'POST',NULL,NULL,3);//java http接口 post 运动鞋频道的数据更新
                } else {
                    $shoe_put = tradeCommon::requestUrl($api_url['url'].'/so/find/'.$item->getId(),'PUT',NULL,NULL,3);//java http接口 put 运动鞋频道的数据更新
                }
            } else {
                $product = TrdFindProductTable::getInstance()->find($item->getId());
                if ($product && $product->getRootId() == 1 && $product->getChildrenId() == 8){
                    $this->deleteFindProductById($item->getId());
                }
            } 
        }
        return true;
    }
    
    private function deleteFindProductById($id){
        if (!$id) return false;
        //java http接口 delete 运动鞋频道的数据删除接口
        $api_url = sfConfig::get('app_javaapi');
        $shoe_put = tradeCommon::requestUrl($api_url['url'].'/so/find/'.$id,'DELETE',NULL,NULL,3);
        return Doctrine_Query::create()
            ->delete()
            ->from('TrdFindProduct')
            ->andWhere('id = ?',$id)
            ->execute();
    }
    
    /**
     *
     * 获取菜单名称（为了不影响后台，在此写获取菜单名称的方法，防止因为后台没有配置缓存而报错） 
     */
    private function getMenuName($menu_id = 0, $root_id = 0) {
        if (empty($menu_id) && empty($root_id))
            return false;
        if (!empty($menu_id)) {
            $result = Doctrine_Query::create()
                    ->setResultCacheLifeSpan(60 * 60 * 2)
                    ->useResultCache()
                    ->select('t.id, t.name')
                    ->from('TrdMenu t')
                    ->where('t.id = ?', $menu_id)
                    ->fetchOne();
        } else {
            $result = Doctrine_Query::create()
                    ->setResultCacheLifeSpan(60 * 60 * 2)
                    ->useResultCache()
                    ->select('t.id, t.name')
                    ->from('TrdMenu t')
                    ->where('t.root_id = ?', $root_id)
                    ->andWhere('t.level = ?', 1)
                    ->fetchArray();
        }
        return $result;
    }

     /**
     *
     * 获取属性名称（为了不影响后台，在此写获取属性名称的方法，防止因为后台没有配置缓存而报错） 
     */
    private function getAttrName($attr_id) {
        if (empty($attr_id))
            return false;
        //$data = TrdAttributeTable::getInstance()->findOneById($attr_id);
        $result = Doctrine_Query::create()
                ->setResultCacheLifeSpan(60 * 60 * 2)
                ->useResultCache()
                ->select('t.id, t.name')
                ->from('TrdAttribute t')
                ->where('t.id = ?', $attr_id)
                ->fetchOne();
        return $result;
    }


}
