<?php

class cmsKaluliService extends kaluliService{

    /**
     * 根据位置找出专题ID
     */
    function executeGetSpecialForChild(){

        $position = $this->getRequest()->getParameter('position');
        $cid = $this->getRequest()->getParameter('cid');
        $listMap['where']['is_use'] = 'is_use = 1';
        $listMap['where']['position'] = "position = '".$position."'";
        if($cid) {
            $listMap['where']['cid'] = "cid = '" . $cid . "'";
        }
        $specialID = KllSpecialTable::getInstance()->findSpecialIDForCategory($listMap);

        return $specialID;
    }
    /**
     * 获得所有分类
     *
     */
    function executeGetCategoryForSpecial(){

        $cate = [];
        $listMap['where']['is_use'] = 'is_use = 1';
        $listMap['where']['fa'] = 'fa  = 0';
        $listMap['where']['platform'] = 'platform = 1 or platform = 3';
        $category = KllCategoryTable::getInstance()->findCategory($listMap);

        $childMap['where']['is_use'] = 'is_use = 1';
        foreach($category as $val){
            $childMap['where']['fa'] = "fa  = '".$val['id']."'";
            $cate[$val['id']] = [
                'id' => $val['id'],
                'name' => $val['name'],
                'child' => KllCategoryTable::getInstance()->findCategory($childMap)
            ];

        }
        return $cate;
    }
    /**
     * 相关文章
     */
    public function executeGetRelateArticle(){
        $cid = $this->getRequest()->getParameter('cid');
        $listMap['where']['is_use'] = 'is_use = 1';
        $listMap['where']['platform'] = 'platform != 2';
        $listMap['where']['cid'] = "cid = '".$cid."'";
        $listMap['limit'] = 3;
        $article = KllArticlesTable::getInstance()->findArticleID($listMap);
        return $article;
    }
    /**
     * 文章seo
     */
    public function executeGetArticleSeo(){
        $seo_id = $this->getRequest()->getParameter('seo_id');
        $listMap['where']['id'] = "id = '".$seo_id."'";
        $listMap['limit'] = 1;
        $article_seo = KllArticleSeoTable::getInstance()->findArticleSeo($listMap);
        return $article_seo;
    }
    /**
     * 根据位置获得专题
     */
    function executeGetSpecialIDByPosition(){

        $position = $this->getRequest()->getParameter('position');

        if($position){
            $listMap['where']['special_id'] = "position = '".$position."'";
            $listMap['where']['is_use'] = 'is_use = 1';
            $ret = KllSpecialTable::getInstance()->findSpecialIDForCategory($listMap);
            if(!empty($ret)){
                return $ret[0]['id'];
            }
        }
        return 0;

    }
    /**
     * 根据专题ID获得文章
     * @return array|bool
     */
    function executeGetAllDataBySpecial(){
        $type = $this->getRequest()->getParameter('type');
        $num = $this->getRequest()->getParameter('num');

        $sid = $this->getRequest()->getParameter('sid');

        $articleIDS = [];
        if($sid){
            $listMap['where']['special_id'] = "special_id = '".$sid."'";
            $articleIDS = self::_formatIDS(self::getAllArticleBySpecial($listMap), $num);

        }

        if(empty($articleIDS)){
            $articleIDS = self::getArticleIDRandom($num);
        }
        return self::getArticleFromTable($articleIDS);

    }
    private static function _formatIDS($articleID, $num){
        $newArticleIDS = [];
        if(!empty($articleID)){
            foreach($articleID as $k => $val){
                if($k < $num) {
                    $newArticleIDS[] = $val['article_id'];
                }
            }
        }
        return $newArticleIDS;
    }
    private function getArticleFromTable($articleIDS){

        $listMap['wherein'] = $articleIDS;
        $listMap['where']['is_use'] = 'is_use = 1';
        $listMap['where']['platform'] = 'platform != 2';
        $article = KllArticlesTable::getInstance()->findArticleID($listMap);

        /*foreach($article as &$val){
                //获取文章对应标签-修改
                $label='';
                $label=  self::getLabelIdByAid($val['id']);            
                $val['childLabels']=$label?$label:'';   
                $label='';
        }*/

        $newData = [];
        foreach($articleIDS as $k => $val){

            foreach($article as $art){
                if(!empty($art['id']) && $art['id'] == $val){
                    $newData[$k] = $art;
                    $label=  self::getLabelIdByAid($art['id']);
                    $newData[$k]['childLabels']=$label?$label:'';

                }
            }
        }


        return $newData;
    }
    private static function getAllArticleBySpecial($bind = []){

        return KllSpecialArticleTable::getInstance()->findSpecialID($bind);
    }
    private static function getArticleIDRandom($num){

        $listMap['select'] = 'id';
        $listMap['where']['is_use'] = 'is_use != 2';

        $articleID = KllArticlesTable::getInstance()->findArticleID($listMap);

        $newArticleID  = [];
        if($articleID){
            $tmpData = array_rand($articleID, $num);
            foreach($tmpData as $val){
                $newArticleID[] = $articleID[$val]['id'];
            }
        }

        return $newArticleID;
    }
    ////根据分类id，获取对应的专题
    private function getSpecialByCid($id,$limit=5)
    {
        $list= KllSpecialTable::getInstance()->getSpecialByCid($id,$limit);

        return $list;
    }
    ////根据专题id获取文章id
    public function executeGetArticlesBySid()
    {
        $page = $this->request->getParameter("page",1);
        $pagesize = $this->request->getParameter("pagesize",10);
        $special_id = $this->getRequest()->getParameter('special_id',1);
        $type = $this->getRequest()->getParameter('type',0);
        $orderby = $this->getRequest()->getParameter('$orderby','id');
        ////获取文章id
            $arr_a=array();
            $bind=[];
            $bind['special_id']=$special_id;
            $bind['limit']=$pagesize;
            $bind['offset']=($page-1)*$pagesize;
            $bind['type']=$type;
            $bind['orderby']=$orderby;
            $aid=  KllSpecialArticleTable::getInstance()->getAidByCid($bind);
            if($aid)
            {
                $a_id=  self::i_array_column($aid, 'article_id');

                $a_list=$this->getArticlesById($a_id);
                if($a_list)
                {
                    ////获取文章对应的标签
                    $label=  self::getLabelIdByAid($a_id);
                    if($label)
                    {
                        $a_list['label']=$label;
                    }
                    else
                    {
                        $a_list['label']='';
                    }
                    return $a_list;
                }
                return false;
            }
        return false;
    }
    //获得文章根据达人
    public function executeGetArticlesByTalentId(){
        $page = $this->request->getParameter("page",1);
        $pagesize = $this->request->getParameter("pagesize",10);
        $talentId = $this->getRequest()->getParameter('talent',0);
        $type = $this->getRequest()->getParameter('type',0);
        $is_use = $this->getRequest()->getParameter('is_use',1);
        $orderby = $this->getRequest()->getParameter('orderby','id');
        ////获取文章id
        $arr_a=array();

        $bind=[];

        if($talentId) {
            $bind['where']['talent_id'] = 'talent_id =' . $talentId;
        }
        $bind['where']['is_use']='is_use='.$is_use;
        $bind['where']['platform'] = 'platform != 2';
        $bind['limit']=$pagesize;
        $bind['offset']=($page-1)*$pagesize;
        $bind['type']=$type;
        $bind['orderby']=$orderby;
        $a_list=KllArticlesTable::getInstance()->getAll($bind);
        if($a_list)
        {
            foreach($a_list as $k=>$v)
            {
                $label=  self::getLabelIdByAid($v['id']);
                if($label)
                {
                    $a_list[$k]['label']=$label;
                }
                else
                {
                    $a_list['label']='';
                }
            }
            return $a_list;
        }
        return false;
    }
    ////根据分类id获取文章id
    public function executeGetArticlesByCid()
    {

        $page = $this->request->getParameter("page",1);
        $pagesize = $this->request->getParameter("pagesize",10);
        $cid = $this->getRequest()->getParameter('cid',0);
        $type = $this->getRequest()->getParameter('type',0);
        $is_use = $this->getRequest()->getParameter('is_use',1);
        $orderby = $this->getRequest()->getParameter('orderby','id');
        ////获取文章id
        $arr_a=array();
        $bind=[];

        if($cid) {

            $bind['where']['cid'] = 'cid=' . $cid;
            $bind['order'] = 'order DESC,  audit_time DESC';
        }
        $bind['where']['is_use']='is_use='.$is_use;
        $bind['where']['platform'] = 'platform != 2';
        $bind['limit']=$pagesize;
        $bind['offset']=($page-1)*$pagesize;
        $bind['type']=$type;
        $bind['order'] = 'index_order  DESC, audit_time DESC';

        $a_list=KllArticlesTable::getInstance()->getAll($bind);
        if($a_list)
        {
            foreach($a_list as $k=>$v)
            {
                $label=  self::getLabelIdByAid($v['id']);
                if($label)
                {
                    $a_list[$k]['label']=$label;
                }
                else
                {
                    $a_list['label']='';
                }
            }
            return $a_list;
        }
        return false;
    }
    /*
     * 文章详情
     */
    public function executeGetArticleOne()
    {

        $aid = $this->getRequest()->getParameter('aid',1);

        $desc=$this->getArticlesById($aid);

        if($desc)
        {
            ////获取文章对应的标签
            $label=  self::getLabelIdByAid($aid);
            if($label)
            {
                $desc['label']=$label;
            }
            else
            {
                $desc['label']='';
            }
            return $desc;
        }
        return false;
    }
    ////根据id获取文章
    private function getArticlesById($aid)
    {
//        $aid = $this->getRequest()->getParameter('aid',0);
        $a_list= KllArticlesTable::getInstance()->getArticles($aid);
        return $a_list;
    }
    ////根据文章id获取对应的标签--废弃
//    private static function getLabelIdByAid($aid)
//    {
//        $label_id= KllArticlesTable::getInstance()->getLabelId($aid);
//        if(!empty($label_id))
//        {
//            $l_id=$this->i_array_column($label_id, 'label','id');
//            foreach($l_id as $k=>$v)
//            {
//                $id=  explode(',', $v);
//                $label=$this->getLabel($id);
//                if($label)
//                {
//                    $l_id[$k]=$label;
//                }
//            }
//            return $l_id;
//        }
//        return false;
//    }
    ////根据标签id获取标签
    private function getLabel($id)
    {
        $label=  KllArticleLabelTable::getInstance()->getLabel($id);
        return $label;
    }
    function i_array_column($input, $columnKey, $indexKey=null){
        if(!function_exists('array_column')){ 
            $columnKeyIsNumber  = (is_numeric($columnKey))?true:false; 
            $indexKeyIsNull            = (is_null($indexKey))?true :false; 
            $indexKeyIsNumber     = (is_numeric($indexKey))?true:false; 
            $result                         = array(); 
            foreach((array)$input as $key=>$row){ 
                if($columnKeyIsNumber){ 
                    $tmp= array_slice($row, $columnKey, 1); 
                    $tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null; 
                }else{ 
                    $tmp= isset($row[$columnKey])?$row[$columnKey]:null; 
                } 
                if(!$indexKeyIsNull){ 
                    if($indexKeyIsNumber){ 
                      $key = array_slice($row, $indexKey, 1); 
                      $key = (is_array($key) && !empty($key))?current($key):null; 
                      $key = is_null($key)?0:$key; 
                    }else{ 
                      $key = isset($row[$indexKey])?$row[$indexKey]:0; 
                    } 
                } 
                $result[$key] = $tmp; 
            } 
            return $result; 
        }else{
            return array_column($input, $columnKey, $indexKey);
        }
    }
    ////获取焦点图
    public function executeGetBanner()
    {
        $limit = $this->getRequest()->getParameter('limit',10);
        $bind['limit']=$limit;
        return KllBannerTable::getInstance()->getAll($bind);
    }
    ////获取焦点图
    public function executeGetAttImg()
    {
        $id = $this->getRequest()->getParameter('id',0);
        return KllAttachmentTable::getInstance()->getAllById($id);
    }
    //获取导航
    public function executeGetNav()
    {
        $cid = $this->getRequest()->getParameter('cid',0);
        $nav=array(
            'c_title'=>'',
            'f_title'=>'',
        );
        $c_info=  KllCategoryTable::getInstance()->fetchOneById($cid); ////当前分类
        if($c_info)
        {
            $nav['c_title']=$c_info->getName();
            $f_info=  KllCategoryTable::getInstance()->fetchOneById($c_info->getFa()); ////上级分类
            if($f_info) 
            {
                $nav['f_title']=$f_info->getName();
                
            }
        }
        return $nav;
    }
    /**
     * 获取文章的label
     * @param array $aid 文章id
     */
   private  function getLabelIdByAid($aid)
    {
//        $aid = $this->getRequest()->getParameter('aid',0);
        if(empty($aid))
        {
            return false;
        }
        $bind['where']['article_id']='article_id='.$aid;
        $relation=  KllLabelArticleRelationTable::getInstance()->findAllBy($bind);  ////文章对应的所有标签
        if($relation)
        {
            $l_id=  self::i_array_column($relation, 'label_id');
            $label=  self::getLabel($l_id);
            return $label;
        }
        return false;
    }
    ////根据cid获取文章总数
    public function executeGetArtCountByCid()
    {
        $bind = $this->getRequest()->getParameter('bind',[]);
        $count= KllArticlesTable::getInstance()->getCountByCid($bind);
        return $count;
    }
    //根据talent_id获得文章数
    public function executeGetArtCountByTalentId()
    {
        $talentId =  $this->getRequest()->getParameter('talent_id');

        $bind['where']['talent_id'] = " talent_id = '".$talentId."'" ;
        $bind['where']['is_use'] = " is_use = 1 " ;
        $bind['where']['platform'] = 'platform != 2';
        $bind['is_count'] = 1;
        $bind['select'] = 'count(id) as num';
        $count= KllArticlesTable::getAll($bind);
        return $count;
    }
    ////根据标签id获取文章总数
    public function executeGetArtCountByLid()
    {
        $bind = $this->getRequest()->getParameter('bind',[]);
        $count= KllLabelArticleRelationTable::getInstance()->getCountByLid($bind);
        return $count;
    }
     ////根据专题id获取文章id
    public function executeGetArticlesByLid()
    {
        $page = $this->request->getParameter("page",1);
        $pagesize = $this->request->getParameter("pagesize",10);
        $label_id = $this->getRequest()->getParameter('label_id',0);
        $type = $this->getRequest()->getParameter('type',0);
        $status = $this->getRequest()->getParameter('status',0);
        $orderby = $this->getRequest()->getParameter('orderby','id');
        ////获取文章id
        $bind=[];
        $bind['where']['label_id']='label_id='.$label_id;
        $bind['limit']=$pagesize;
        $bind['offset']=($page-1)*$pagesize;

        $list= KllLabelArticleRelationTable::getInstance()->findAllBy($bind);

        if($list)
        {
            $aid=  self::i_array_column($list, 'article_id');
            $a_list=  KllArticlesTable::getInstance()->getArticles($aid,$pagesize);
            if($a_list)
            {
                foreach($a_list as $k=>$v)
                {
                    $label=  self::getLabelIdByAid($v['id']);
                    if($label){
                        $a_list[$k]['label']=$label;
                    }else{
                        $a_list[$k]['label'] = '';
                    }

                }
                return $a_list;
            }
            return false;
        }
        return false;
    }
    ////获取标签
    public function executeGetLabelById()
    {
        $id = $this->getRequest()->getParameter('id',0);

        return KllArticleLabelTable::getInstance()->findOneBy('id',$id);
    }

    //获取百科内容
    public function executeGetBaikeDetail() {
        $id = $this->getRequest()->getParameter("id");
        if(empty($id)){
            return $this->error("500","参数错误");
        }
        $baikeInfo = KllWikiTable::getInstance()->findOneById($id);
        if(empty($baikeInfo)) {
            return $this->error("500","百科内容不存在");
        }
        $data = array();
        //构造返回数组
        $data['title'] = $baikeInfo->getTitle();
        $data['banner'] = $baikeInfo->getBanner();
        $data['content'] = KllWikiForm::_formatForm($baikeInfo->getContent());
        $data['qa'] = json_decode($baikeInfo->getQa(),true);
        $ids = explode(",",$baikeInfo->getRelateArticle());
        $relates = array();
        foreach($ids as $v) {
            $info = KllArticlesTable::getInstance()->findOneById($v);
            if($info) {
                $relate['id'] = $v;
                $relate['title'] = $info->getTitle();
                $relate['cover'] = $info->getCover();
                $relates[] = $relate;
            }
        }
        $data['relates'] = $relates;
        return $this->success($data);
    }
}