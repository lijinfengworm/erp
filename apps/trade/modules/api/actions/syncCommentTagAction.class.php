<?php

class syncCommentTagAction extends sfAction
{

    public function execute($request)
    {
        sfConfig::set('sf_web_debug', false);
        $id = $request->getParameter('id',0);
        if(!$id)return sfView::NONE;

        $res = trdDaigouCommentTable::getInstance()->find($id);
        if($res){
            $tag_names = json_decode($res->getTagsAttr(),true);

            foreach($tag_names as $tag_k=>$tag_v){

                    $commentTags = trdDaigouCommentTagsTable::getInstance()->createQuery()->where('daigou_comment_id =?',$res->getId())->andWhere('tag_id= ?',$tag_k)->fetchOne();
                    if(!$commentTags){
                        $commentTags = new trdDaigouCommentTags();
                    }
                    $commentTags->setDaigouCommentId($res->getId());
                    $commentTags->setTagId($tag_k);
                    $commentTags->save();

                echo $res->getId().'success'.PHP_EOL;
            }
        }


        return sfView::NONE;
    }
}