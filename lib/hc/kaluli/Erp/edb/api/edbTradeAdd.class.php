<?php

/**
 * 订单添加
 * Class edbTradeGet
 * $api->setDateType("1970-1-1 00：00：00");
$api->setBeginTime(date('Y-m-d H:i:s',time()-86400 * 380));
$api->setEndTime(date('Y-m-d H:i:s',time()));
 */
class edbTradeAdd
{




    public $fields = '';

    public $xmlValues = '';

	public function __construct(){
       // $this->setFields('result,state');
    }

	public function setFields( $fields  = ''){
		$this->fields = $fields;
		return $this;
	}


    /** 外部平台号  */
    public function setOutTid($out_tid = '') {
        $this->xmlValues['out_tid'] = $out_tid;
        return $this;
    }

    /** 店铺号  */
    public function setShopId($shop_id = '') {
        $this->xmlValues['shop_id'] = $shop_id;
        return $this;
    }

    /** 仓库编码  */
    public function setStorageId($storage_id = '') {
        $this->xmlValues['storage_id'] = $storage_id;
        return $this;
    }


    /** 买家ID */
    public function setBuyerId($buyer_id = '') {
        $this->xmlValues['buyer_id'] = $buyer_id;
        return $this;
    }

    /** 买家 留言 */
    public function setBuyerMsg($buyer_msg = '') {
        $this->xmlValues['buyer_msg'] = $buyer_msg;
        return $this;
    }


    /** 买家邮件地址 */
    public function setBuyerEmail($buyer_email = '') {
        $this->xmlValues['buyer_email'] = $buyer_email;
        return $this;
    }

    /** 买家支付宝号 */
    public function setBuyerAlipay($buyer_alipay = '') {
        $this->xmlValues['buyer_alipay'] = $buyer_alipay;
        return $this;
    }

    /** 客服备注 */
    public function setSellerRemark($seller_remark = '') {
        $this->xmlValues['seller_remark'] = $seller_remark;
        return $this;
    }


    /** 收货人姓名 */
    public function setConsignee($consignee = '') {
        $this->xmlValues['consignee'] = $consignee;
        return $this;
    }

    /** 收货地址 */
    public function setAddress($address = '') {
        $this->xmlValues['address'] = $address;
        return $this;
    }

    /** 收货人邮编 */
    public function setPostcode($postcode = '') {
        $this->xmlValues['postcode'] = $postcode;
        return $this;
    }

    /** 联系电话 */
    public function setTelephone($telephone = '') {
        $this->xmlValues['telephone'] = $telephone;
        return $this;
    }

    /** 联系手机 */
    public function setMobilPhone($mobilPhone = '') {
        $this->xmlValues['mobilPhone'] = $mobilPhone;
        return $this;
    }

    /** 收货人省份 */
    public function setPrivince($privince = '') {
        $this->xmlValues['privince'] = $privince;
        return $this;
    }

    /** 收货人城市 */
    public function setCity($city = '') {
        $this->xmlValues['city'] = $city;
        return $this;
    }

    /** 收货人地区 */
    public function setArea($area = '') {
        $this->xmlValues['area'] = $area;
        return $this;
    }



    /** 配送方式 */
    public function setShipMethod($ship_method = '') {
        $this->xmlValues['ship_method'] = $ship_method;
        return $this;
    }

    /** 快递公司名 */
    public function setExpress($express = '') {
        $this->xmlValues['express'] = $express;
        return $this;
    }

    /** 订单类型 */
    public function setOrderType($order_type = '') {
        $this->xmlValues['order_type'] = $order_type;
        return $this;
    }

    /** 处理状态 */
    public function setProcessStatus($process_status = '') {
        $this->xmlValues['process_status'] = $process_status;
        return $this;
    }

    /** 发货状态 */
    public function setDeliverStatus($deliver_status = '') {
        $this->xmlValues['deliver_status'] = $deliver_status;
        return $this;
    }


    /** 订单总金额 */
    public function setOrderTotalMoney($order_totalMoney = '') {
        $this->xmlValues['order_totalMoney'] = $order_totalMoney;
        return $this;
    }

    /** 产品总金额 */
    public function setProductTotalMoney($product_totalMoney = '') {
        $this->xmlValues['product_totalMoney'] = $product_totalMoney;
        return $this;
    }

    /** 优惠金额 */
    public function setFavorableMoney($favorable_money = '') {
        $this->xmlValues['favorable_money'] = $favorable_money;
        return $this;
    }

    /** 外部平台付款单号 */
    public function setOutPayNo($out_payNo = '') {
        $this->xmlValues['out_payNo'] = $out_payNo;
        return $this;
    }




    /* 订货日期 */
    public function setOrderDate($order_date = '') {
        $this->xmlValues['order_date'] = $order_date;
        return $this;
    }


    /* 付款日期 */
    public function setPayDate($pay_date = '') {
        $this->xmlValues['pay_date'] = $pay_date;
        return $this;
    }

    /* 其他备注 */
    public function setOtherRemark($other_remark = '') {
        $this->xmlValues['other_remark'] = $other_remark;
        return $this;
    }


    /* 实付运费 */
    public function setActualFreightPay($actual_freight_pay = '') {
        $this->xmlValues['actual_freight_pay'] = $actual_freight_pay;
        return $this;
    }
    /** 实收运费 */
    public function setActualFreightGet($actual_freight_get = '') {
        $this->xmlValues['actual_freight_get'] = $actual_freight_get;
        return $this;
    }

    /** 产品运费 */
    public function setProductFreight($product_freight = '') {
        $this->xmlValues['product_freight'] = $product_freight;
        return $this;
    }

    /* 条形码 */
    public function setBarCode($bar_code = '') {
        $this->xmlValues['barCode'] = $bar_code;
        return $this;
    }

    /* 网店名称 */
    public function setProductTitle($product_title = '') {
        $this->xmlValues['product_title'] = $product_title;
        return $this;
    }



    /* 网店规格 */
    public function setInMemo($in_memo = '') {
        $this->xmlValues['in_memo'] = $in_memo;
        return $this;
    }


    /* 网店规格 */
    public function setStandard($standard = '') {
        $this->xmlValues['standard'] = $standard;
        return $this;
    }
    /* 付款状态 */
    public function setPayStatus($pay_status = '') {
        $this->xmlValues['pay_status'] = $pay_status;
        return $this;
    }

    /* 订货数量 */
    public function setOrderGoodsNum($orderGoods_Num = '') {
        $this->xmlValues['orderGoods_Num'] = $orderGoods_Num;
        return $this;
    }

    /* 成交单价 */
    public function setCostPrice($cost_Price = '') {
        $this->xmlValues['cost_Price'] = $cost_Price;
        return $this;
    }


    private function _parame($val) {
        if(empty($val)) return '';
        return $val;
    }

    public  function setXmlValues() {
        $xml  = "<order>
                    <orderInfo>
                        <tid></tid>
                        <out_tid>".$this->_parame(isset($this->xmlValues['out_tid']) ? $this->xmlValues['out_tid'] : '')."</out_tid>
                        <shop_id>".$this->_parame(isset($this->xmlValues['shop_id']) ? $this->xmlValues['shop_id'] : '')."</shop_id>
                        <storage_id>".$this->_parame(isset($this->xmlValues['storage_id']) ? $this->xmlValues['storage_id'] : '')."</storage_id>
                        <buyer_id>".$this->_parame(isset($this->xmlValues['buyer_id']) ? $this->xmlValues['buyer_id'] : '')."</buyer_id>
                        <buyer_msg>".$this->_parame(isset($this->xmlValues['buyer_msg']) ? $this->xmlValues['buyer_msg'] : '')."</buyer_msg>
                        <buyer_email>".$this->_parame(isset($this->xmlValues['buyer_email']) ? $this->xmlValues['buyer_email'] : '')."</buyer_email>
                        <buyer_alipay>".$this->_parame(isset($this->xmlValues['buyer_alipay']) ? $this->xmlValues['buyer_alipay'] : '')."</buyer_alipay>
                        <seller_remark>".$this->_parame(isset($this->xmlValues['seller_remark']) ? $this->xmlValues['seller_remark'] : '')."</seller_remark>
                        <consignee>".$this->_parame(isset($this->xmlValues['consignee']) ? $this->xmlValues['consignee'] : '')."</consignee>
                        <address></address>
                        <postcode>".$this->_parame(isset($this->xmlValues['postcode']) ? $this->xmlValues['postcode'] : '')."</postcode>
                        <telephone>".$this->_parame(isset($this->xmlValues['telephone']) ? $this->xmlValues['telephone'] : '')."</telephone>
                        <mobilPhone>".$this->_parame(isset($this->xmlValues['mobilPhone']) ? $this->xmlValues['mobilPhone'] : '')."</mobilPhone>
                        <privince>".$this->_parame(isset($this->xmlValues['privince']) ? $this->xmlValues['privince'] : '')."</privince>
                        <city>".$this->_parame(isset($this->xmlValues['city']) ? $this->xmlValues['city'] : '')."</city>
                        <area>".$this->_parame(isset($this->xmlValues['area']) ? $this->xmlValues['area'] : '')."</area>
                        <actual_freight_get>".$this->_parame(isset($this->xmlValues['actual_freight_get']) ? $this->xmlValues['actual_freight_get'] : '')."</actual_freight_get>
                        <actual_RP></actual_RP>
                        <ship_method>".$this->_parame(isset($this->xmlValues['ship_method']) ? $this->xmlValues['ship_method'] : '')."</ship_method>
                        <express>".$this->_parame(isset($this->xmlValues['express']) ? $this->xmlValues['express'] : '')."</express>
                        <is_invoiceOpened></is_invoiceOpened>
                        <invoice_type></invoice_type>
                        <invoice_money></invoice_money>
                        <invoice_title></invoice_title>
                        <invoice_msg></invoice_msg>
                        <order_type>".$this->_parame(isset($this->xmlValues['order_type']) ? $this->xmlValues['order_type'] : '')."</order_type>
                        <process_status>".$this->_parame(isset($this->xmlValues['process_status']) ? $this->xmlValues['process_status'] : '')."</process_status>
                        <pay_status>".$this->_parame(isset($this->xmlValues['pay_status']) ? $this->xmlValues['pay_status'] : '')."</pay_status>
                        <deliver_status>".$this->_parame(isset($this->xmlValues['deliver_status']) ? $this->xmlValues['deliver_status'] : '')."</deliver_status>
                        <is_COD></is_COD>
                        <serverCost_COD></serverCost_COD>
                        <order_totalMoney>".$this->_parame(isset($this->xmlValues['order_totalMoney']) ? $this->xmlValues['order_totalMoney'] : '')."</order_totalMoney>
                        <product_totalMoney>".$this->_parame(isset($this->xmlValues['product_totalMoney']) ? $this->xmlValues['product_totalMoney'] : '')."</product_totalMoney>
                        <pay_method>".$this->_parame(isset($this->xmlValues['pay_method']) ? $this->xmlValues['pay_method'] : '')."</pay_method>
                        <pay_commission>".$this->_parame(isset($this->xmlValues['pay_commission']) ? $this->xmlValues['pay_commission'] : '')."</pay_commission>
                        <pay_score>".$this->_parame(isset($this->xmlValues['pay_score']) ? $this->xmlValues['pay_score'] : '')."</pay_score>
                        <return_score>".$this->_parame(isset($this->xmlValues['return_score']) ? $this->xmlValues['return_score'] : '')."</return_score>
                        <favorable_money>".$this->_parame(isset($this->xmlValues['favorable_money']) ? $this->xmlValues['favorable_money'] : '')."</favorable_money>
                        <alipay_transaction_no>".$this->_parame(isset($this->xmlValues['alipay_transaction_no']) ? $this->xmlValues['alipay_transaction_no'] : '')."</alipay_transaction_no>
                        <out_payNo>".$this->_parame(isset($this->xmlValues['alipay_transaction_no']) ? $this->xmlValues['alipay_transaction_no'] : '')."</out_payNo>
                        <out_express_method>".$this->_parame(isset($this->xmlValues['out_express_method']) ? $this->xmlValues['out_express_method'] : '')."</out_express_method>
                        <out_order_status>".$this->_parame(isset($this->xmlValues['out_order_status']) ? $this->xmlValues['out_order_status'] : '')."</out_order_status>
                        <order_date></order_date>
                        <pay_date></pay_date>
                        <finish_date>".$this->_parame(isset($this->xmlValues['finish_date']) ? $this->xmlValues['finish_date'] : '')."</finish_date>
                        <plat_type>".$this->_parame(isset($this->xmlValues['plat_type']) ? $this->xmlValues['plat_type'] : '')."</plat_type>
                        <distributor_no>".$this->_parame(isset($this->xmlValues['distributor_no']) ? $this->xmlValues['distributor_no'] : '')."</distributor_no>
                        <WuLiu>".$this->_parame(isset($this->xmlValues['WuLiu']) ? $this->xmlValues['WuLiu'] : '')."</WuLiu>
                        <WuLiu_no>".$this->_parame(isset($this->xmlValues['WuLiu_no']) ? $this->xmlValues['WuLiu_no'] : '')."</WuLiu_no>
                        <terminal_type>".$this->_parame(isset($this->xmlValues['terminal_type']) ? $this->xmlValues['terminal_type'] : '')."</terminal_type>
                        <in_memo>".$this->_parame(isset($this->xmlValues['in_memo']) ? $this->xmlValues['in_memo'] : '')."</in_memo>
                        <other_remark>".$this->_parame(isset($this->xmlValues['other_remark']) ? $this->xmlValues['other_remark'] : '')."</other_remark>
                        <actual_freight_pay>".$this->_parame(isset($this->xmlValues['actual_freight_pay']) ? $this->xmlValues['actual_freight_pay'] : '')."</actual_freight_pay>
                        <ship_date_plan>".$this->_parame(isset($this->xmlValues['ship_date_plan']) ? $this->xmlValues['ship_date_plan'] : '')."</ship_date_plan>
                        <deliver_date_plan>".$this->_parame(isset($this->xmlValues['deliver_date_plan']) ? $this->xmlValues['deliver_date_plan'] : '')."</deliver_date_plan>
                        <is_scorePay>".$this->_parame(isset($this->xmlValues['is_scorePay']) ? $this->xmlValues['is_scorePay'] : '')."</is_scorePay>
                        <is_needInvoice>".$this->_parame(isset($this->xmlValues['is_needInvoice']) ? $this->xmlValues['is_needInvoice'] : '')."</is_needInvoice>
                    </orderInfo>
                    <productInfo>
                        <product_item>
                            <barCode>".$this->_parame(isset($this->xmlValues['barCode']) ? $this->xmlValues['barCode'] : '')."</barCode>
                            <product_title>".$this->_parame(isset($this->xmlValues['product_title']) ? $this->xmlValues['product_title'] : '')."</product_title>
                            <standard>".$this->_parame(isset($this->xmlValues['standard']) ? $this->xmlValues['standard'] : '')."</standard>
                            <out_price>".$this->_parame(isset($this->xmlValues['out_price']) ? $this->xmlValues['out_price'] : '')."</out_price>
                            <favorite_money>".$this->_parame(isset($this->xmlValues['favorite_money']) ? $this->xmlValues['favorite_money'] : '')."</favorite_money>
                            <orderGoods_Num>".$this->_parame(isset($this->xmlValues['orderGoods_Num']) ? $this->xmlValues['orderGoods_Num'] : '')."</orderGoods_Num>
                            <gift_Num>".$this->_parame(isset($this->xmlValues['gift_Num']) ? $this->xmlValues['gift_Num'] : '')."</gift_Num>
                            <cost_Price>".$this->_parame(isset($this->xmlValues['cost_Price']) ? $this->xmlValues['cost_Price'] : '')."</cost_Price>
                            <tid>".$this->_parame(isset($this->xmlValues['tid']) ? $this->xmlValues['tid'] : '')."</tid>
                            <product_stockout>".$this->_parame(isset($this->xmlValues['product_stockout']) ? $this->xmlValues['product_stockout'] : '')."</product_stockout>
                            <is_Book>".$this->_parame(isset($this->xmlValues['is_Book']) ? $this->xmlValues['is_Book'] : '')."</is_Book>
                            <is_presell>".$this->_parame(isset($this->xmlValues['is_presell']) ? $this->xmlValues['is_presell'] : '')."</is_presell>
                            <is_Gift>".$this->_parame(isset($this->xmlValues['is_Gift']) ? $this->xmlValues['is_Gift'] : '')."</is_Gift>
                            <avg_price>".$this->_parame(isset($this->xmlValues['avg_price']) ? $this->xmlValues['avg_price'] : '')."</avg_price>
                            <product_freight>".$this->_parame(isset($this->xmlValues['product_freight']) ? $this->xmlValues['product_freight'] : '')."</product_freight>
                            <shop_id>".$this->_parame(isset($this->xmlValues['shop_id']) ? $this->xmlValues['shop_id'] : '')."</shop_id>
                            <out_tid>".$this->_parame(isset($this->xmlValues['out_tid']) ? $this->xmlValues['out_tid'] : '')."</out_tid>
                            <out_productId>".$this->_parame(isset($this->xmlValues['out_productId']) ? $this->xmlValues['out_productId'] : '')."</out_productId>
                            <out_barCode>".$this->_parame(isset($this->xmlValues['out_barCode']) ? $this->xmlValues['out_barCode'] : '')."</out_barCode>
                            <product_intro>".$this->_parame(isset($this->xmlValues['product_intro']) ? $this->xmlValues['product_intro'] : '')."</product_intro>
                        </product_item>
                    </productInfo>
                </order>";


        $xmlValues = str_replace(array(" ","　","\t","\n","\r"),array("","","","",""), $xml);
        $xmlValues = str_replace('<order_date></order_date>','<order_date>'.$this->_parame(isset($this->xmlValues['order_date']) ? $this->xmlValues['order_date'] : '').'</order_date>',$xmlValues);
        $xmlValues = str_replace('<pay_date></pay_date>','<pay_date>'.$this->_parame(isset($this->xmlValues['order_date']) ? $this->xmlValues['order_date'] : '').'</pay_date>',$xmlValues);
        $xmlValues = str_replace('<address></address>','<address>'.$this->_parame(isset($this->xmlValues['address']) ? $this->xmlValues['address'] : '').'</address>',$xmlValues);
        $this->xmlValues=trim($xmlValues);


        return $this;
    }










}