<?php

/**
 * 订单获取
 * Class edbTradeGet
 * $api->setDateType("1970-1-1 00：00：00");
$api->setBeginTime(date('Y-m-d H:i:s',time()-86400 * 380));
$api->setEndTime(date('Y-m-d H:i:s',time()));
 */
class edbTradeGet
{

    public $date_type = '';
    public $begin_time = '';
    public $end_time = '';
    public $order_type = '';
    public $payment_status = '';
    public $order_status = '';
    public $proce_Status = '';
    public $storage_id = '';
    public $shopid = '';
    public $express_no = '';
    public $express = '';
    public $out_tid = '';
    public $invoice_isprint = '';
    public $invoice_isopen = '';
    public $page_no = '';
    public $page_size = '';
    public $import_mark = '';
    public $ProductInfo_type = '';
    public $fields = '';


	public function __construct(){
       /*
        $this->setFields(
            'storage_id,tid,transaction_id,customer_id,distributor_id,shop_name,out_tid,out_pay_tid,voucher_id,
        shopid,serial_num,order_channel,order_from,buyer_id,buyer_name,type,status,abnormal_status,merge_status,receiver_name,
        receiver_mobile,phone,province,city,district,address,post,email,is_bill,invoice_name,invoice_situation,invoice_title,
        invoice_type,invoice_content,pro_totalfee,order_totalfee,reference_price_paid,invoice_fee,cod_fee,other_fee,refund_totalfee,
        discount_fee,discount,channel_disfee,merchant_disfee,order_disfee,commission_fee,is_cod,point_pay,cost_point,point,
        superior_point,royalty_fee,external_point,express_no,tradegifadd,express,express_coding,online_express,sending_type,
        real_income_freight,real_pay_freight,gross_weight,gross_weight_freight,net_weight_freight,freight_explain,total_weight,
        tid_net_weight,tid_time,pay_time,get_time,order_creater,business_man,payment_received_operator,payment_received_time,
        review_orders_operator,review_orders_time,finance_review_operator,finance_review_time,advance_printer,printer,print_time,
        is_print,adv_distributer,adv_distribut_time,distributer,distribut_time,is_inspection,inspecter,inspect_time,cancel_operator,
        cancel_time,revoke_cancel_er,revoke_cancel_time,packager,pack_time,weigh_operator,weigh_time,book_delivery_time,delivery_operator,
        delivery_time,locker,lock_time,book_file_time,file_operator,file_time,finish_time,modity_time,is_promotion,promotion_plan,
        out_promotion_detail,good_receive_time,receive_time,verificaty_time,enable_inte_sto_time,enable_inte_delivery_time,alipay_id,
        alipay_status,pay_mothed,pay_status,platform_status,rate,currency,delivery_status,buyer_message,service_remarks,inner_lable,
        distributor_mark,system_remarks,other_remarks,message,message_time,is_stock,related_orders,related_orders_type,import_mark,
        delivery_name,is_new_customer,distributor_level,cod_service_fee,express_col_fee,product_num,sku,item_num,single_num,
        flag_color,is_flag,taobao_delivery_order_status,taobao_delivery_status,taobao_delivery_method,order_process_time,is_break,
        breaker,break_time,break_explain,plat_send_status,plat_type,is_adv_sale,provinc_code,city_code,area_code,express_code,
        last_returned_time,last_refund_time,deliver_centre,deliver_station,is_pre_delivery_notice,jd_delivery_time,Sorting_code,
        cod_settlement_vouchernumber,big_marker,total_num,child_storage_id,child_tid,child_pro_detail_code,child_pro_name,
        child_specification,child_barcode,child_combine_barcode,child_iscancel,child_isscheduled,child_stock_situation,child_isbook_pro,
        child_iscombination,child_isgifts,child_gift_num,child_book_storage,child_pro_num,child_send_num,child_refund_num,
        child_refund_renum,child_inspection_num,child_timeinventory,child_cost_price,child_sell_price,child_average_price,
        child_original_price,child_sys_price,child_ferght,child_item_discountfee,child_inspection_time,child_weight,child_shopid,
        child_out_tid,child_out_proid,child_out_prosku,child_proexplain,child_buyer_memo,child_seller_remark,child_distributer,
        child_distribut_time,child_second_barcode,child_product_no,child_brand_number,child_brand_name,child_book_inventory,
        child_product_specification,child_discount_amount,child_credit_amount,child_MD5_encryption'
        );*/

    }

	public function setFields( $fields ){
		$this->fields = $fields;
		return $this;
	}


    public function setDateType($date_type) {
        $this->date_type = $date_type;
        return $this;
    }

    public function setBeginTime($begin_time) {
        $this->begin_time = $begin_time;
        return $this;
    }

    public function setEndTime($end_time) {
        $this->end_time = $end_time;
        return $this;
    }


    public function setOrderType($order_type) {
        $this->order_type = $order_type;
        return $this;
    }


    public function setPaymentStatus($payment_status) {
        $this->payment_status = $payment_status;
        return $this;
    }

    public function setOrderStatus($order_status) {
        $this->order_status = $order_status;
        return $this;
    }

    public function setProceStatus($proce_Status) {
        $this->proce_Status = $proce_Status;
        return $this;
    }

    public function setStorageId($storage_id) {
        $this->storage_id = $storage_id;
        return $this;
    }


    public function setShopid($shopid) {
        $this->shopid = $shopid;
        return $this;
    }


    public function setExpressNo($express_no) {
        $this->express_no = $express_no;
        return $this;
    }


    public function setExpress($express) {
        $this->express = $express;
        return $this;
    }

    public function setOutTid($out_tid) {
        $this->out_tid = $out_tid;
        return $this;
    }

    public function setInvoiceIsprint($invoice_isprint) {
        $this->invoice_isprint = $invoice_isprint;
        return $this;
    }

    public function setInvoiceIsopen($invoice_isopen) {
        $this->invoice_isopen = $invoice_isopen;
        return $this;
    }


    public function setPageNo($page_no) {
        $this->page_no = $page_no;
        return $this;
    }


    public function setPageSize($page_size) {
        $this->page_size = $page_size;
        return $this;
    }

    public function setImportMark($import_mark) {
        $this->import_mark = $import_mark;
        return $this;
    }


    public function setProductInfoType($ProductInfo_type) {
        $this->ProductInfo_type = $ProductInfo_type;
        return $this;
    }










}