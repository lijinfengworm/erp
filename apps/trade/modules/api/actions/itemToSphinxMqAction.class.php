<?php
/**
 * User: wp
 * Date: 14-3-18
 * Time: 下午5:08
 */

class itemToSphinxMqAction extends sfAction{
    public function execute($request)
    {
        sfConfig::set('sf_web_debug',false);
        $mysphinxDb = new tradeSpinxMysql();
        $type = $request->getParameter('type');
        $id = $request->getParameter('id');
        if ($type == 1){//删除
            $result = $mysphinxDb->deleteOne($id);
            if(! $result){
                return $this->renderText(json_encode(array('status'=>500,'msg'=>$id.' 删除成功')));
            }
        } else {//新增、修改
            $item = TrdItemAllTable::getInstance()->find($id);
            if ($item){
                $info = '';
                if ($item->getRootId() && $item->getChildrenId()){
                    $info = 'R'.$item->getRootId().' C'.$item->getChildrenId().' '.str_replace('-','',str_replace(',', ' ', $item->getAttrCollect()));
                }
                $param = array(
                    'id' => $item->getId(),
                    'title' => strval($item->getTitle()),
                    'info' => $info,
                    'hot' => $item->getClickCount(),
                    'price' => $item->getPrice(),
                    'time' => $item->getPublishDate(),
                    'infos' => $item->getTitle().' '.$info,
                );
                $result = $mysphinxDb->saveData($param);
                if(! $result){
                    var_dump($result);
                    return $this->renderText(json_encode(array('status'=>500,'msg'=>$id.'保存失败')));
                }
            }
        }
        return $this->renderText(json_encode(array('status'=>200,'msg'=>$id.' 保存成功')));



    }
} 