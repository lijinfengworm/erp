<?php
/**
 * Created by PhpStorm.
 * User: kworm
 * Date: 16-4-14
 * Time: 上午10:08
 */
class KaluliArticleService{
    const TABLE = 'kll_articles';
    const SEOTABLE = 'kll_article_seo';
    const CATEGORYTABLE = 'kll_category';
    const ARTICLELABELTABLE = 'kll_article_label';
    const SPECIALTABLE = 'kll_special';
    const SPECIALARTICLETABLE = 'kll_special_article';
    const ATTACHMENTTABLE='kll_attachment';
    const BANNERTABLE='kll_banner';
    const ADTABLE='kll_ad';
    const TALENTTABLE = 'kll_talent';

    private $form = [];
    //插入的数组
    private $bind = [];
    private $error_flag = false;

    private $model = null;
    static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }
    //$form赋值
    function addForm($key, $val){
        if(empty($this->form[$key])) {
            $this->form[$key] = $val;
        }
    }
    public function commonCreate($request, $table){

        $_post = $request->getPostParameters($table);
        $this->form[$table.'Form']->bind($_post[$table]);
        if(!$this->form[$table.'Form']->isValid()) {
            $this->error_flag = true;
        }
    }
    public function createSpecial($request, $table){

    }
    //绑定主字段
    public function create($request) {
        $_post = $request->getPostParameters(self::TABLE);
        $this->form['articleForm']->bind($_post[self::TABLE]);
        if(!$this->form['articleForm']->isValid()) {
            $this->error_flag = true;
        }
    }
    //绑定seo主字段
    public function createSeo($request) {
        $_post = $request->getPostParameters(self::SEOTABLE);

        $this->form['articleSeoForm']->bind($_post[self::SEOTABLE]);
        if(!$this->form['articleSeoForm']->isValid()) {
            $this->error_flag = true;
        }
    }
    //获得附件url
    public static function getAttrUrl($att_id = 0){

        $data_att= KllAttachmentTable::getInstance()->findOneById($att_id);
        $att_img_path='';
        if($data_att)
        {
            $att_img_path=$data_att->getOriginal();
        }
        return $att_img_path;
    }

    //根据分类ID获得分类名
    public static function getCategoryName($cid){

        $ret = KllCategoryTable::getInstance()->findOneById($cid);
        
        return $ret->getName();
    }
    public function addArticle($type, $request,$find  = '') {

        //如果是修改
        if(!empty($find)) {
            $this->model = $find;
        }
        try{
            $this->createSeo($request);
            $_seo = $this->form['articleSeoForm']->save();
            $_seo->save();
            $seoID = $_seo->getId();
            if($seoID){
                $this->create($request);
                $_new = $this->form['articleForm']->save();
                $labelPost = $request->getParameter('labels');

                $platPost = $request->getParameter('platform');
                $platPostNum = count($platPost);
                if($platPostNum == 0){
                    $platform = 1;
                }elseif($platPostNum == 1){
                    $platform = $platPost[0];
                }elseif($platPostNum == 2){
                    $platform = intval($platPost[0]) ^ intval($platPost[1]);
                }else{
                    $platform = 1;
                }

                $_new->setAdd_time(time())->setPlatform($platform)->setSeoId($seoID)->setPublic_time(time())->setH_id(sfContext::getInstance()->getUser()->getTrdUserHuPuId())->save();
                $articleID =  $_new->getId();
                
                if($articleID && !empty($labelPost)){
                    $oldLable = KllLabelArticleRelationTable::getInstance()->findBy('article_id', $articleID);
                    $oldLable->delete();
                    foreach($labelPost as $lb){
                        $lableRelation = new KllLabelArticleRelation();
                        $lableRelation->setLabelId($lb);
                        $lableRelation->setArticleId($articleID);
                        $lableRelation->save();
                        $lableRelation->getId();
                        unset($lableRelation);

                    }
                }
            }
            return false;
        }catch (sfException $e){
            $this->setVar('error',1);
        }


    }
    public function addCategory($request, $find=''){
        if(!empty($find)) {
            $this->model = $find;
        }
        try{
            $platPost = $request->getParameter('platform');
            $platPostNum = count($platPost);
            if($platPostNum == 0){
                $platform = 1;
            }elseif($platPostNum == 1){
                $platform = $platPost[0];
            }elseif($platPostNum == 2){
                $platform = intval($platPost[0]) ^ intval($platPost[1]);
            }else{
                $platform = 1;
            }
            $this->commonCreate($request, self::CATEGORYTABLE);
            $cate = $this->form[self::CATEGORYTABLE.'Form']->save();
            $cate->setPlatform($platform)->setOpt_uid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())->save();
            $cate->setUpdate_time(time())->save();
            $cate->save();
            return $cate->getId();

        }catch (sfException $e){
            $this->setVar('error',1);
        }
    }
    //添加标签
    function addArticleLabel($request, $find=''){
        if(!empty($find)) {
            $this->model = $find;
        }
        try{
            $this->commonCreate($request, self::ARTICLELABELTABLE);
            $label = $this->form[self::ARTICLELABELTABLE.'Form']->save();
            $label->setOpt_uid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())->save();
            $label->setUpdate_time(time())->save();
            $label->save();
            return $label->getId();

        }catch (sfException $e){
            $this->setVar('error',1);
        }
    }
    //手动添加一个新的标签
    function addNewArticleLabel($labelName, $cate){

        $label = new KllArticleLabel();
        $label->setFa($cate)->setName($labelName)->setOptUid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())->setAddTime(time())->save();
        return $label->getId();
    }
    //检查标签时候已经存在
    function checkArticleLabelIsExist($label){
        $bind['where']['like'] = "name like '%".$label."%'";
        return KllArticleLabelTable::getInstance()->findAllByBind($bind);
    }
    //添加合集
    function addSpecial($request, $find=''){
        if(!empty($find)) {
            $this->model = $find;
        }
        try{

            $this->commonCreate($request, self::SPECIALTABLE);
            $special = $this->form[self::SPECIALTABLE.'Form']->save();
            $special->setOpt_uid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())->save();
            $special->setUpdate_time(time())->save();
            $special->save();
            $sid = $special->getId();
            if($sid){
                //$this->createSpecial($request, self::SPECIALARTICLETABLE);
                $_post = $request->getPostParameters(self::SPECIALARTICLETABLE);

                if(!empty($_post[self::SPECIALARTICLETABLE]['article_id'])){
                    KllSpecialArticleTable::getInstance()->findBy('special_id', $sid)->delete();

                    foreach($_post[self::SPECIALARTICLETABLE]['article_id'] as $k => $val){

                        $art = new KllSpecialArticle();
                        $art->setArticle_id($val)
                            ->setSpecial_id($sid)
                            ->save();
                    }
                }

                return true;
            }

        }catch (sfException $e){
            $this->setVar('error',1);
        }

    }
    //获得文章数据
    public static function  getAllArticle($bind = array(), $style)
    {
        return KllArticlesTable::getAll($bind);
    }
    //获得文章单条数据
    public static function getOneByID($id = 0){
        return KllArticlesTable::getInstance()->findOneBy('id', $id);
    }
    public static function getSpecialSingle($sid = 0){
        return KllSpecialTable::getInstance()->findOneBy('id', $sid);
    }
    //获得所有分类
    public static function getAllCategory(){
        return KllCategoryTable::getInstance()->findAll();
    }
    //获得所有标签
    public static function getAllArticleLabel(){
        return KllArticleLabelTable::getInstance()->findAll();
    }
    //获得文章池的数据
    public static function getAllArticlePool($bind = [], $style){
        return KllArticlePoolTable::getAll($bind);
    }
    //获得所有的专题
    public static function getAllSpecial(){
        return KllSpecialTable::getInstance()->findAll();
    }
    //获得所有的达人
    public static function getAllTalent(){
        return KllTalentTable::getInstance()->findAll();
    }

    ////添加广告
    public function addAd($type,$request,$find  = '')
    {
        //如果是修改
        if(!empty($find)) {
            $this->model = $find;
        }
        ////
        if($this->error_flag)            $this->setVar('error',1);

        $this->commonCreate($request, self::ADTABLE);
        $res = $this->form[self::ADTABLE.'Form']->save();
        $res->setOpt_uid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())->save();
        $res->setAdd_time(time())->save();
        $res->save();
        return $res->getId();
    }
    ////添加banner
    public function addBanner($type,$request,$find  = '')
    {
        //如果是修改
        if(!empty($find)) {
            $this->model = $find;
        }
        ////
        if($this->error_flag)            $this->setVar('error',1);

        $this->commonCreate($request, self::BANNERTABLE);
        $res = $this->form[self::BANNERTABLE.'Form']->save();
        $res->setAdd_time(time())->save();
        $res->save();
        return $res->getId();
    }
    //增加达人
    public function addTalent($request,$find  = ''){

        //如果是修改
        if(!empty($find)) {
            $this->model = $find;
        }
        ////
        if($this->error_flag)            $this->setVar('error',1);
        $this->commonCreate($request, self::TALENTTABLE);
        $res = $this->form[self::TALENTTABLE.'Form']->save();
        $res->setAdd_time(time())->save();
        return $res->getId();
    }
    public function addAtt($type,$request,$find  = '')
    {
        //如果是修改
        if(!empty($find)) {
            $this->model = $find;
        }
        ////
        if($this->error_flag)            $this->setVar('error',1);
            $this->commonCreate($request, self::ATTACHMENTTABLE);
        $res = $this->form[self::ATTACHMENTTABLE.'Form']->save();
        $res->save();
        return $res->getId();
    }    
    /**
     * ajax保存附件路径
     * @param $att_path 附件路径
     */
    public function ajaxAddAtt($att_path)
    {
        $obj_att=new KllAttachment();
        $obj_att->setOriginal($att_path)
                ->setSmall($att_path)
                ->setMedium($att_path)
                ->save();
        $obj_att->save();
        return $obj_att->getId();
    }
    //删除和审核
    function deleteOrAuditArticle($id, $type=0){
        $article = KllArticlesTable::getInstance()->findOneById($id);
        if($type == 1){
            //审核通过
            $article->setAudit_uid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())
                ->setAudit_time(time())
                ->setIs_use(1)
                ->save();
            $label = KllLabelArticleRelationTable::getInstance()->findBy('article_id', $id);
            foreach($label as $lb){
                $labelRelation = KllLabelArticleRelationTable::getInstance()->findOneBy('id', $lb['id']);
                $labelRelation->setStatus(1)->save();
            }

        }elseif($type == 2){
            //删除
            $article->setAudit_uid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())
                ->setIs_use(2)
                ->save();
            //删除的时候标签也一并删除

            return KllLabelArticleRelationTable::getInstance()->findBy('article_id',$id)->delete();



        }else{
            $article->setAudit_uid(sfContext::getInstance()->getUser()->getTrdUserHuPuId())
                ->setIs_use(2)
                ->save();
        }
        return $article->getId();
    }
    //获得所有标签
    static function getAllLabels(){
        return KllArticleLabelTable::getInstance()->findAll();
    }
    public static function  getAllBanner($bind = array(), $style)
    {
        return KllBannerTable::getInstance()->getAll($bind);
    }    
}