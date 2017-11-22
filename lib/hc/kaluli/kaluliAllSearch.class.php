<?php
Class kaluliAllSearch
{
    /*搜索BY tag*/
    public function searchByTag($params)
    {
        $array = $data = $terms = array();

        foreach ($params['tags'] as $tag) {
            $terms[] = array('term' =>
                array('tag' => $tag)
            );
        }

        $data['size'] = $params['num'];
        $data['query']['bool']['should'] = $terms;

        $array['_type'] = $params['type'];
        $array['data'] = $data;

        #搜索
        $es = new kaluliElasticSearch();
        $indexData = $es->search($array);
        $indexData = json_decode($indexData, true);
        FunBase::myDebug($indexData);
    }
}