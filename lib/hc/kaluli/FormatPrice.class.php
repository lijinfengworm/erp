<?php

/**
 * 卡路里价格类
 * author: kworm
 */
class FormatPrice
{
	private static $instance=null;
	private $kaluli_discount_price = 0;
	private $kaluli_activity_label = '';
	private $kaluli_lineation_price = 0;
	private $kaluli_discount_rate = 0;
    private $kaluli_discount_type = 100;


	public static $kaluliActivity = [
		0	=> 	'单件折扣',
		1	=>	'多件折扣',
		2	=>	'满减',   //现在是优惠券。不是活动
		3	=>	'X元购',		
	];

	public static function getInstance(){
        if(self::$instance==null){
                self::$instance = new self();
        }

        return self::$instance;
    }
    public function initPrice($goods_id){
        $this->getLineationPriceByGoodsId($goods_id);
        $this->kaluli_discount_type = 100;
        $this->kaluli_activity_label = '';
    }
	//获得某个商品的单价
	public function getPrice($goods_id, $get_type=0){
		$this->initPrice($goods_id);
        if(!$get_type){
            $this->getActivityByGoodsId($goods_id);
            // $this->getXpriceByGoodsId($goods_id);
        }

		//根据商品id获得价格
		if($get_type == 1){
			$this->getActivityByGoodsId($goods_id);
		}elseif($get_type == 3){
            $this->getXpriceByGoodsId($goods_id);
        }
		return [
			'kaluli_discount_price' 		=> 	$this->kaluli_discount_price,
			'kaluli_activity_label'		    =>	$this->kaluli_activity_label,
			'kaluli_lineation_price'	    =>	$this->kaluli_lineation_price,
            'kaluli_discount_type'          =>  $this->kaluli_discount_type
		];
	}
	//金钱格式化  金钱、 是否输出  格式化方式
    public static function priceFormatAll($price, $is_echo = false, $frame_type = 0)
    {
        switch ($frame_type) {
            case 0:
                $price = number_format($price, 2, '.', '');
                break;
            case 1: // 保留不为 0 的尾数
                $price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));
                if (substr($price, -1) == '.') {
                    $price = substr($price, 0, -1);
                }
                break;
            case 2: // 不四舍五入，保留1位
                $price = substr(number_format($price, 2, '.', ''), 0, -1);
                break;
            case 3: // 直接取整
                $price = intval($price);
                break;
            case 4: // 四舍五入，保留 1 位
                $price = number_format($price, 1, '.', '');
                break;
            case 5: // 先四舍五入，不保留小数
                $price = round($price);
                break;
        }
        if ($is_echo) $price = sprintf("￥%s元", $price);
        return $price;
    }
    //根据商品活动活动
    private  function  getActivityByGoodsId($goods_id){

    	$redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
    	$redis->select(1);
        $activity_discount = [];
        $json = $redis->get('kaluli_marketing_activity_' . $goods_id);
        $activity_discount = unserialize($json);

        if(!empty($activity_discount) && isset($activity_discount['detail']['discount_rate'])){
        	$this->kaluli_discount_rate = $activity_discount['detail']['discount_rate'];
        	if($activity_discount['detail']['mode'] == 3){
                $this->kaluli_discount_type = 0;

        		$this->kaluli_activity_label =  $this->kaluli_discount_rate.'折';
        	}elseif ($activity_discount['detail']['mode'] == 2) {
                $piece = 0;
                if(isset($activity_discount['data'][0]['attr1'])){
                    $piece = $activity_discount['data'][0]['attr1'];
                }
                $this->kaluli_discount_type = 1;
        		$this->kaluli_activity_label =  $piece.'件'.$this->kaluli_discount_rate.'折';
        	}
        	$this->kaluli_discount_price = self::priceFormatAll(intval($this->kaluli_lineation_price)*$this->kaluli_discount_rate/10);
        }
        
    }
    //X元购
    private function getXpriceByGoodsId($goods_id){
        //x元购
        $serviceRequest = new kaluliServiceClient();
        $serviceRequest->setVersion("1.0");
        $serviceRequest->setMethod("activity.CheckActivity");
        $serviceRequest->setApiParam("itemId", $goods_id);
        $x_response = $serviceRequest->execute();
        if ($x_response->getStatusCode() == 203) {
            $itemActivity = $x_response->getValue("itemActivity");
            if(!empty($itemActivity)){
                $this->kaluli_discount_type = 3;
                $this->kaluli_discount_price = $itemActivity['price'];
                $this->kaluli_activity_label =  'x元购';
            }
        }
    }
    //根据商品id获得商品的单价
    private function getLineationPriceByGoodsId($goods_id){
        $products = KaluliItemTable::getOneForPrice($goods_id);
        
        if(!empty($products) && isset($products->discount_price) ){
        	$this->kaluli_lineation_price = $products->discount_price;
            $this->kaluli_discount_price = $products->discount_price;
        }else{
            $this->kaluli_lineation_price = 0;
            $this->kaluli_discount_price =0;
        }
    }
    /**
     * 优惠券拆分的计算公式
     * 子订单优惠金额 = （子订单小计/主订单小计）* 优惠券金额
     */
    public function splitCouponFee($order_number){
        $coupon = [];
        try {
            $main_order = KaluliMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
            $order_order = KaluliOrderTable::getInstance()->findByOrderNumber($order_number);
            if(!empty($main_order) && !empty($order_order)){
                $main = $main_order->toArray();
                $coupon_fee = $main['coupon_fee'];
                //主订单小计
                $main_subtotal = $main['total_price'] - $main['duty_fee'] - $main['express_fee'] + $main['coupon_fee'];
                //子订单小计
                $order = $order_order->toArray();
                
                foreach ($order as $key => $ord) {
                    $order_subtotal = $ord['price']*$ord['number']-$ord["marketing_fee"];
                    $coupon[$ord['id']]['coupon_fee'] = ($order_subtotal/$main_subtotal)*$main['coupon_fee'];
                }
                return $coupon;
            }else{
                throw new Exception('订单为空', -9);
            }
            
            
        } catch (Exception $e) {
            $data = array('code' => $e->getCode(),'msg' => $e->getMessage());
            return json_encode($data);
        }
    }
    /**
     * 获取子订单小计
     */
    public function getOrderSubtotal($ord){

        $coupon = $this->splitCouponFee($ord['order_number']);
        $subtotal = $ord['price']*$ord['number']-$ord["marketing_fee"]-$coupon[$ord['id']]['coupon_fee'];
        return $subtotal/$ord['number'];
    }
    /**
     * 推送价格
     * 货号仓库渠道确定一个推送价
     */
    public function getBbPushPrice($depot, $channel, $product_code){
        $bind = [];
        if(!empty($channel)){
            $bind['where']['channel'] = ' channel =  "'.$channel.'"';
        }
        if(empty($depot) || empty($product_code)) return false;
        $bind['where']['depot'] = ' depot =  '.$depot;
        $bind['where']['product_code'] = ' product_code = "'.$product_code.'"';
        $bind['limit'] = ' limit 1 ';
        $res = KllErpSkuPriceTable::getInstance()->getAll($bind);
        if(!empty($res)){
            $push_price = $res[0]['push_price'];
            return $push_price; 
        }else{
            return false;
        }

    }



}