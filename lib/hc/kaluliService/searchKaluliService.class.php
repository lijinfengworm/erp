<?php

class searchKaluliService extends kaluliService{

    public function executeRecommendGet()
    {
        $articleNum = $this->getRequest()->getParameter('articleNum',0);
        $articleNotId = $this->getRequest()->getParameter('articleNotId',0);
        $itemNum = $this->getRequest()->getParameter('itemNum',0);
        $itemNotId = $this->getRequest()->getParameter('itemNotId',0);
        $tags = $this->getRequest()->getParameter('tags',array());

        #验证
        if(!is_array($tags))return  $this->error('401','tags为数组');
        if(!is_numeric($articleNum) || !is_numeric($itemNum) || !is_numeric($articleNotId) || !is_numeric($itemNotId)){
            return $this->error('401','参数不合法');
        }

        $return =  array();
        #文章
        if($articleNum) {
            $params = array(
              'num' => $articleNum,
              'tags' => $tags,
              'notId'=>$articleNotId
            );

            $kaluliArticleSearch = new  kaluliArticleSearch();
            $articleContent = $kaluliArticleSearch ->searchByTag($params);

            $hotArticleNum = $articleNum - count($articleContent['result']);
            if($hotArticleNum){#小于需要的条数取最热
                $notIds = $this->getIds($articleContent['result'],$articleNotId);

                $condition = array('select'=>'id,title','order'=>'hits DESC','day'=>30,'limit'=>$hotArticleNum,'arr'=>true);
                if($notIds) $condition['not_id'] = $notIds;
                $hotArticlData = kaluliArticleTable::getMessage($condition);

                $articleContent['result'] = array_merge($articleContent['result'],$hotArticlData);
            }
            $return['article'] = $articleContent;
        }

        #商品
        if($itemNum) {
            $params = array(
                'num' => $itemNum,
                'tags' => $tags,
                'notId'=> $itemNotId
            );

            $kaluliItemSearch = new  kaluliItemSearch();
            $itemContent = $kaluliItemSearch ->searchByTag($params);

            $return['item'] = $itemContent;

            $hotItemNum = $itemNum - count($itemContent['result']);
            if($hotItemNum){#小于需要的条数取最热
                $notIds = $this->getIds($itemContent['result'],$itemNotId);

                $condition = array('select'=>'id,title,pic,price','order'=>'hits DESC','day'=>30,'limit'=>$hotItemNum,'arr'=>true);
                if($notIds) $condition['not_id'] = $notIds;
                $hotItemData = kaluliItemTable::getMessage($condition);

                $itemContent['result'] = array_merge($itemContent['result'],$hotItemData);
            }
            $return['item'] = $itemContent;
        }

        return $this->success($return);
    }


    #获取ID
    private function  getIds($arr,$notId){
        $new_arr = array();

        if(is_array($arr)){
            foreach($arr as $k=>$v){
                $new_arr[] =  $v['id'];
            }
        }

        if($notId)
            $new_arr[] = $notId;

        return $new_arr;
    }

} 