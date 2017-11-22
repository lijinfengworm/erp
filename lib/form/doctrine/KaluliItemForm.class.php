<?php

/**
 * KaluliItem form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KaluliItemForm extends BaseKaluliItemForm
{


    # 商品参数
    public static $itemAttr = array(
        '配料表',
        '保质期',
        '适用人群',
        '产品剂型',
        '计价单位',
        '品牌',
        '产地',
        '规格',
    );

    public static $statusList = array(
        0 => '待审核',
        1 => '审核通过',
        2 => '已退回',
        3 => '上架中',
        4 => '已下架'
    );

    public static $statusEs=array(
        1=>'能',
        0=>'不能',
    );
    public function configure()
    {
        unset($this['updated_at']);
        unset($this['created_at']);
        unset($this['hits']);


        # 标题
        $this->setWidget('title', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 30)));
        $this->setValidator('title', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 40, 'min_length' => 8), array('required' => '标题必填',  'max_length' => '标题不大于30个字')));
        # 商品品牌
        $this->setWidget('brand_id', new sfWidgetFormChoice(array('choices'=>self::getBrandsByDictionary())));
        $this->setValidator('brand_id', new sfValidatorChoice(array('choices'=>array_keys(self::getBrandsByDictionary()),'required' => true)));//验证
        #能否被ES搜索
        $this->setWidget('status_es', new sfWidgetFormChoice(array('choices'=>self::$statusEs)));
        # 上传图片
        $rule = array(
            'required'=>true,
            'max_size'=>'500000',
            //    'height'=>400,
            //    'width'=>400,
            'path'=>'uploads/kaluli/item',
            //   'ratio'=>'1x1'
        );
        $this->setWidget('upload_path',new tradeWidgetFormKupload(array("callback"=>"displayImage(data.url);","rule"=>$rule)));
        $this->setWidget('pic', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
        $this->setValidator('pic', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '商品图片必填', 'invalid' => '商品图片必填')));
        # 原价
        $this->setWidget('price', new sfWidgetFormInputHidden());
        # 折扣价
        $this->setWidget('discount_price', new sfWidgetFormInputHidden());
        # 商品细节图
        $rule = array(
            'required'=>true,
            'max_size'=>'500000',
            //    'height'=>400,
            //    'width'=>400,
            'path'=>'uploads/kaluli/item/detail',
            //   'ratio'=>'1x1'
        );
        $this->setWidget('upload_path2',new tradeWidgetFormKupload(array("callback"=>"displayImageDetail(data.url);","rule"=>$rule)));
        $this->setWidget('pic_detail', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 300)));
        //$this->setValidator('pic', new sfValidatorUrl(array('required' => true, 'trim' => true), array('required' => '商品图片必填', 'invalid' => '商品图片必填')));

        # 卖点
        $this->setWidget('sell_point', new sfWidgetFormInput(array(), array('size' => 50)));
        $this->setValidator('sell_point', new sfValidatorString(array('required' => true, 'trim' => true, ), array('required' => '卖点必填')));

        # 简介
        $this->setWidget('intro',new sfWidgetFormTextarea());
        $this->setValidator('intro', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '简介必填')));

        # 营养师点评
        $this->setWidget('review',new sfWidgetFormTextarea());
        $this->setValidator('review', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 50), array('required' => '营养师点评必填', 'max_length' => '不能超过最大50个字')));

        # 内容
        $this->setWidget('content',new tradeWidgetFormUeditor(array(),array('width'=>'auto')));
        //$this->setDefault('content',$content);
        $this->setValidator('content', new sfValidatorString(array('required' => true, 'trim' => true), array('required' => '内容必填')));




        $this->widgetSchema->setHelps(array(
//          'img_path' => '<span style="color: red">图片比例必须1:1</span>',
//          'root_type' => '<span style="color: red">PS：请勿把发给第三方的礼品卡导入到活动当中，以免造成礼品卡重复领取</span>',
        ));
        # 回调
//      $this->validatorSchema->setPostValidator(
//          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
//      );
    }

    public static  function getBrandsByDictionary() {
          $serviceRequest = new kaluliServiceClient();
          $serviceRequest->setVersion("1.0");
          $serviceRequest->setMethod("item.GetItemBrands");
          $response = $serviceRequest->execute();
          if(!$response->hasError()) {
              $data = $response->getData();
              return $data['data'];
          }
          
    }

    #获取tag
    public static function getScheme($id){
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(10);
        $sql = 'SELECT tr.tag_id FROM kll_tags_relate tr LEFT JOIN kll_tags t ON tr.tag_id = t.id  WHERE tr.type = 1 AND t.type = 1  AND `pid` = ?';
        $conn = Doctrine_Manager::getInstance()->getConnection('kaluli');
        $st = $conn->execute($sql, array($id));
        $res = $st->fetchAll(Doctrine_Core::FETCH_ASSOC);
        foreach($res as $v) {
            $tag[] = $v['tag_id'];
        }
        //热门商品逻辑
        $validUvList = array();
        $itemTable = KaluliItemTable::getInstance();
        if(!empty($tag)) {
            $tagIds = implode(",",$tag);
            $startTime = date("Ymd", strtotime("-60 days"));
            $endTime = date("Ymd", time());
            $sql = "select item_id,sum(uv) as uv from kll_item_count_log where item_id in ( select pid from kll_tags_relate where `tag_id` in (" . $tagIds . ") and type = 1) and `time`>={$startTime} and `time` <={$endTime}  group by item_id order by uv desc ";
            $st = $conn->execute($sql);
            $res = $st->fetchAll(Doctrine_Core::FETCH_ASSOC);
            $uvList = array();
            $goodList = array();
            foreach ($res as $value) {
                $uvList[$value["item_id"]] = $value['uv'];
                $goodList[] = $value['item_id'];
            }
            //去查询热门商品是否上架,拿到有效的数据
            unset($goodList[$id]);//去除当前商品
            $groundItems = $itemTable::getMessage(array("ids" => $goodList, 'select' => 'id,title,pic,sell_point,discount_price,price,intro', "arr" => 1));
            foreach ($groundItems as $goodValue) {
                $validUvList[$goodValue['id']] = $uvList[$goodValue['id']];
            }
            //排序
            arsort($validUvList);
        }
        if(count($validUvList) <4) {
            //不满4个取限时抢购补充
            $validItems = array_keys($validUvList);
            $date = date("Y-m-d");
            $sec_one = json_decode($redis->get('kaluli_limitbuy' . $date), true) ? json_decode($redis->get('kaluli_limitbuy' . $date), true) : array();
            for($i=0;$i<4;$i++){
                $validItems[] = $sec_one['ID'.$i];
            }
            $infos = $itemTable::getMessage(array("ids" => $validItems, 'select' => 'id,title,pic,sell_point,discount_price,price,intro','arr'=>1));
            $infos = json_encode($infos);
        } else {
            $validItems = array_slice(array_keys($validUvList),0,12);
            $infos = $itemTable::getMessage(array("ids" => $validItems, 'select' => 'id,title,pic,sell_point,discount_price,price,intro','arr'=>1));
            $infos = json_encode($infos);
        }

        return $infos;
    }

    /**
     * 增加X元购活动价格
     * @param $products
     * @return array
     */
    public static function getActivityPrice($products)
    {
        if (empty($products)) return array();
        foreach ($products as $k => $product) {
            $serviceRequest = new kaluliServiceClient();
            $serviceRequest->setVersion("1.0");
            $serviceRequest->setMethod("activity.CheckActivity");
            $serviceRequest->setApiParam("itemId", $product['id']);
            $response = $serviceRequest->execute();
            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 203 || $response->getStatusCode() == 202) { //未参加活动商品,跳转原商品详情页,202,203
                $itemActivity = $response->getValue('itemActivity');
                $products[$k]['activityPrice'] = $itemActivity['price'];
            }
        }
        return $products;
    }
    /*
     * 根据库存排序,增加标识
     */
    public static function sortByStock($items) {
        $noStockItems = array();
        foreach($items as $key => $v) {
            if(isset($v['id'])) {
                $itemSkus = KaluliItemSkuTable::getSkusByItemId($v['id']);
            } else {
                $itemSkus = KaluliItemSkuTable::getSkusByItemId($v['ID']);
            }
            $stock = 0;
            foreach($itemSkus as $sKey => $sV) {
                if($sV['status'] == 0) {
                    $stock+= $sV['total_num'];
                }
            }
            if($stock ==0) {
                $v['noStock'] = 1;
                $noStockItems[] =  $v;
                unset($items[$key]);
            }
        }
        $newItems = array_merge($items,$noStockItems);
        return $newItems;
    }


}
