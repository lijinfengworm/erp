<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2016/12/17
 * Time: 下午4:20
 * x元购后台服务接口
 */

class KaluliXbuyService {
    private $error_flag = false;

    private $form = array();  //存放  form 对象集合
    private $options = array();  //存放 option 数据集合

    //表名
    private $xbuy = 'kll_xbuy';
    private $xbuy_item = 'kll_xbuy_item';

    /**
     * 连接后台用户服务
     * @staticvar \Admin\Service\Cache $systemHandier
     */
    static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }

    /**
     * 添加 form
     */
    public function addForm($key,$val) {
        if(empty($this->form[$key])) {
            $this->form[$key] = $val;
        }
    }


    public function add($request) {
        //校验主字段
        $this->create($request);
        if($this->error_flag) throw new sfException("有错误");
        $items = $request->getParameter($this->xbuy_item);
        if(empty($items)) {
            throw new sfException("商品不能为空");
        }

        $this->checkItems($items);
        if($this->error_flag) throw new sfException("抢购数量和价格不能为空");
        //x元购活动记录存入数据库
        $activity = $this->form[$this->xbuy]->save();
        if(empty($activity)) {
            throw new sfException("活动不存在");
        }
        //存商品对应活动数据
        $this->save($items,$activity->getId());
    }


    /*
    * 验证主字段
    */
    public function create($request) {
        $_post[$this->xbuy] = $request->getParameter($this->xbuy);
        /* 验证主字段  */
        $this->form[$this->xbuy]->bind($_post[$this->xbuy]);
        if(!$this->form[$this->xbuy]->isValid()) {
            $this->error_flag = true;
        }
    }

    public function checkItems($items) {
        foreach($items["itemId"] as $k => $v) {
            if($items['price'][$k] == "" || $items["number"][$k] == "") {
                $this->error_flag = true;
            }
        }
    }

    /**
     * @param $request
     * @param $activityId
     */
    public function save($items,$activityId){
        foreach($items["itemId"] as $k => $v) {
            //查找是否有现有的商品
            $data = KllXbuyItemTable::getInstance()->findOneByActivityIdAndItemId($activityId,$items['itemId'][$k]);
            if(!$data) {
                $data = array();
            }
            $formItem = new KllXbuyItemForm($data);
            $itemArray = array(
                "activity_id"=>$activityId,
                "item_id" => $items['itemId'][$k],
                "price"   => $items['price'][$k],
                "number"  => $items['number'][$k],
                "title"   => $items['title'][$k],
                "origin_price" => $items['origin_price'][$k]
            );
            $formItem->bind($itemArray);
            if($formItem->isValid()) {
                $formItem->save();
            }

        }
    }

    public function startActivity($id) {
        $activity = KllXbuyTable::getInstance()->find($id);
        if(!$activity) {
            throw new sfException("活动不存在");
        }
        $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
        $db->beginTransaction();
        try{
            //更新活动表
            $activity->setStatus(1);
            $activity->save();
            //获取当前活动下的所有商品
            $items = KllXbuyItemTable::getInstance()->createQuery()->where("activity_id = ?",$activity->getId())->fetchArray();
            if(empty($items)) throw new sfException("没有商品无法启动");
            foreach( $items as $item) {
                //看是否有相关商品启动
                $info = KllXbuyItemTable::getInstance()->findOneByItemIdAndStatus($item['item_id'],1);
                if(!empty($info)) {
                    throw new sfException("已经有进行中的活动,商品id".$item['item_id']);
                }
                $data = KllXbuyItemTable::getInstance()->find($item['id']);
                $data->setStatus(1);
                $data->save();
            }
            $db->commit();
        }catch(Exception $e) {
            $db->rollback();
            throw new sfException($e->getMessage());
        }
    }

    public function shutdownActivity($id){
        $activity = KllXbuyTable::getInstance()->find($id);
        if(!$activity) {
            throw new sfException("活动不存在");
        }
        $db = Doctrine_Manager::getInstance()->getConnection('kaluli');
        $db->beginTransaction();
        try{
            //更新活动表
            $activity->setStatus(2);
            $activity->save();
            $items = KllXbuyItemTable::getInstance()->createQuery()->where("activity_id = ?",$activity->getId())->fetchArray();
            if(empty($items)) throw new sfException("没有商品关闭错误");
            foreach( $items as $item) {
                $data = KllXbuyItemTable::getInstance()->find($item['id']);
                $data->setStatus(0);
                $data->save();
            }
            $db->commit();

        }catch(Exception $e) {
            $db->rollback();
            throw new sfException($e->getMessage());
        }
    }

}