<?php
Class kaluliArticleSearch{
    CONST _TYPE = 'article';             //表
    CONST _CHANNEL_TYPE = 1;


    public function search($search_data){

    }

    /*搜索BY tag*/
    public function searchByTag($params){
        $array = $data = $terms = $orders = array();

        foreach($params['tags'] as $tag){
            $terms[] = array('term'=>
                array('tag'=>$tag)
            );
        }
        $orders = array('_score'=>'desc','point'=>'desc');

        if($params['notId'])  $data['query']['bool']['must_not'] = array('term'=>array('id'=>$params['notId']));
        $data['query']['bool']['should'] = $terms;
        $data['size'] = $params['num'];
        $data['sort'] = $orders;

        $data['fields'] = array('id','title');
        $array['_type'] = self::_TYPE;
        $array['data'] = $data;

        #搜索
        $es =new kaluliElasticSearch();
        $indexData = $es->search($array);
        $indexData = json_decode($indexData,true);

        #返回数据
        return $res = $this->checkTagData($indexData);
    }

    #返回数据
    private function checkTagData($indexData){
        if($indexData['status']){
            $result = array();
            $data_hits= isset($indexData['data']['hits']) ? $indexData['data']['hits'] : array();
            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                foreach($data_hits['hits'] as $k=>$v){
                    $result[$k]['id'] =  $v['fields']['id'][0];
                    $result[$k]['title'] =  $v['fields']['title'][0];
                }
            }

            $return['status'] = true;
            $return['result'] = $result;

        }else{
            $return['status'] = false;
        }

        return $return;
    }


    /*重建索引*/
    public  function reindex($id = 1){
        set_time_limit(3600);
        $id = is_numeric($id) ? $id : 1;

        echo str_repeat(" ",1024);      //达到输出限制
        for($i = $id; $i >= 1 ; $i--){
            echo $this->update($i);

            ob_flush();
            flush();
            usleep(50);
        }
    }

    /*索引创建*/
    public function create($id,$close_link = false){
        return $this->update($id,$close_link);
    }


    /*索引更新*/
    public function update($id,$close_link = false){
        $return = array('status'=>'error');
        if(!$id || !is_numeric($id)){
            $return['msg'] = '参数错误';
            return json_encode($return);
        }

        $kaluliArticleTable = KaluliArticleTable::getInstance();
        $kaluliArticle = $kaluliArticleTable->findOneBy('id', $id);

        if($kaluliArticle && $kaluliArticle->getStatus() == 1){#审核通过
            $tagsdata = $this->getTag($id);

            $data = array();
            $data['id'] =  $kaluliArticle->getId();
            $data['title'] =  $kaluliArticle->getTitle();
            $data['channelType'] =  self::_CHANNEL_TYPE;
            $data['point'] =  $this->getPoint($kaluliArticle->getCreatedAt(), $kaluliArticle->getHits());
            $data['intro'] =  strip_tags($kaluliArticle->getIntro());
            $data['attrs']['scheme'] =  $tagsdata['scheme'];
            $data['attrs']['type'] =  $tagsdata['type'];
            $data['tag'] =  $tagsdata['tags'];
            $data['createTime'] =  $kaluliArticle->getCreatedAt();

            $array = array(
                '_type'=>self::_TYPE,
                '_id'=>$kaluliArticle->getId(),
                'data'=>$data
            );

            $es =new kaluliElasticSearch();
            $put = $es->update($array);

//            if($close_link) $kaluliArticleTable->getConnection()->close();
            return  $put;
        }else{
            return $this->delete($id);
        }
    }

    /*索引删除*/
    public  function delete($id){
        $return = array('status'=>'error');
        if(!$id || !is_numeric($id)){
            $return['msg'] = '参数错误';
            return json_encode($return);
        }

        $data = array();
        $array = array(
            '_type'=>self::_TYPE,
            '_id'=>$id,
            'data'=>$data
        );

        $es =new kaluliElasticSearch();
        $put = $es->delete($array);
        return $put;
    }


    /*设置mapping*/
    public  function mapping(){
        $data = array(
            'article'=>array(
                'properties'=>array(
                    'id'=>array('type'=> 'long'),
                    'title'=>array(
                        'index_analyzer'=> 'index_ansj',
                        'search_analyzer'=> 'query_ansj',
                        'type'=> 'string'
                    ),
                    'channelType'=>array('type'=> 'long'),
                    'createTime'=>array('type'=> 'date','format'=> 'yyyy-MM-dd HH:mm:ss'),
                    'intro'=> array(
                        'index_analyzer'=> 'index_ansj',
                        'search_analyzer'=> 'query_ansj',
                        'type'=> 'string'
                    ),
                    'tag'=>array('type'=> 'string','index'=>'not_analyzed'),
                    'attrs'=>array(
                        'properties'=>array(
                            'scheme'=>array('type'=> 'string','index'=>'not_analyzed'),
                            'type'=>array('type'=> 'string','index'=>'not_analyzed'),
                        )
                    ),
                    'point'=>array('type'=> 'double'),
                )
            )
        );

        $array = array(
            '_type'=>self::_TYPE,
            'data'=>$data
        );

        $es =new kaluliElasticSearch();
        $shoe_put = $es->mapping($array);
        return $shoe_put;
    }

    /*删除mapping*/
    public function deleteMapping(){
        $array = array(
            '_type'=>self::_TYPE,
        );
        $es =new kaluliElasticSearch();
        $shoe_put = $es->deleteMapping($array);
        return $shoe_put;
    }

    /*设置索引*/
    public  function  index(){
        $data = array();

        $array = array(
            '_type'=>self::_TYPE,
            'data'=>$data
        );

        $es =new kaluliElasticSearch();                                               //更新索引
        $put = $es->index($array);
        return $put;
    }


    #获取类目名
    private function getCategoryName($id){
        if(!$id || !is_numeric($id)) return '';

        $categoryTable = KaluliCategoryTable::getInstance();

        $category = $categoryTable->find($id);
        $categoryName = $category->getName();

//        $categoryTable->getConnection()->close();   #关闭连接

        return $categoryName;
    }

    #获取tag
    private function getTag($id){
        $sql = 'SELECT t.name,t.type FROM kll_tags_relate tr LEFT JOIN kll_tags t ON tr.tag_id = t.id  WHERE tr.type = 2 AND `pid` = ?';
        $conn = Doctrine_Manager::getInstance()->getConnection('kaluli');
        $st = $conn->execute($sql, array($id));
        $res = $st->fetchAll(Doctrine_Core::FETCH_ASSOC);
//        $conn->close();

        $tagsArr = $schemeArr = $typeArr = array();
        foreach($res as $k=>$v){
            $tagsArr[] = $v['name'];

            if($v['type'] == 1)
                $schemeArr[] = $v['name'];
            else if($v['type'] == 2)
                $typeArr[] = $v['name'];
        }

        $data = array(
            'tags'=>$tagsArr,
            'scheme'=>$schemeArr,
            'type'=>$typeArr
        );

        return $data;
    }

    /*热点值计算*/
    private  function getPoint($date,$attendCount){
        $date_point = (strtotime($date) - strtotime(date('2015-01-01'))) / (86400/2);
        $click_point = log($attendCount,3);

        if($click_point <= 0) $click_point =0;
        return round(($date_point+$click_point),2);
    }
}













