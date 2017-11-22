<?php
/**
 * User: wp
 * Date: 14-3-18
 * Time: 下午5:08
 */

class youhuiToSphinxMqAction extends sfAction{
    public function execute($request)
    {
        sfConfig::set('sf_web_debug',false);
        $mysphinxDb = new tradeSpinxMysql();
        $type = $request->getParameter('type');
        $id = $request->getParameter('id');
        if ($type == 1){//删除
            $result = $mysphinxDb->deleteOne($id,'youhui');
            if(! $result){
                return $this->renderText(json_encode(array('status'=>500,'msg'=>$id.' 删除成功')));
            }
        } else {//新增、修改
            $item = TrdNewsTable::getInstance()->find($id);
            if ($item && !$item->getIsDelete()){
                $info = '';
                if ($item->getRootId() && $item->getChildrenId()){
                    $info = 'R'.$item->getRootId().' C'.$item->getChildrenId();
                }
                $param = array(
                    'id' => $item->getId(),
                    'title' => strval($item->getTitle()).' '.strval($item->getSubTitle()),
                    'subtitle' => strval($item->getSubTitle()),
                    'info' => $info,
                    'hot' => $item->getHits(),
                    'type' => $item->getType(),
                    'time' => strtotime($item->getPublishDate()),
                    'infos' => $item->getTitle().' '.$item->getSubTitle().' '.$info,
                );
                $result = $mysphinxDb->saveData($param,'youhui');
                if(! $result){
                    var_dump($result);
                    return $this->renderText(json_encode(array('status'=>500,'msg'=>$id.'保存失败')));
                }
            }
        }
        return $this->renderText(json_encode(array('status'=>200,'msg'=>$id.' 保存成功')));



    }
} 