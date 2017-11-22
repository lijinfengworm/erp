<?php
//获取商品基本信息
class edbProductBaseInfoGet
{
	public function __construct()
	{
        /*
        $this->setFields( 'pro_memo,brand_code,proBI_id,pro_code,factory_item,pro_name,cycle_type,pro_date,market_price,retail_price,
        pro_intro,pro_type,supplier,pro_class,is_flight,po_mode,newExpire_date,pro_id,standard,sell_price,suitItem_count,
        pack_code,pack_standard,pro_picture,weight,cost,pro_status,is_suit,bar_code,boxNum,pro_color,pro_size,pro_unit,
        integral,validity_date,is_useIntegral,buy_integral,min_cost,lowest_cost,highest_cost,contrast_cost,avg_cost,avg_taxCost,
        child_arrival_amounto,child_costTax_price,avgcost_explain,is_deleted,is_stop,integral_scale,is_consump,consump_cycle,
        is_singleSend,attribute,stock_attribute,cost_explain,lowest_stock,po_cycle,sales_coutMode,pro_url,po_batch,is_packMaterials,
        brand_name,sort_name,size_name,color_name,Custom_Att_1,Custom_Att_2,Custom_Att_3,Custom_Att_4,Custom_Att_5,Custom_Att_6,
        Custom_Att_7,Custom_Att_8,Custom_Att_9' );
        */
	}

	public function setFields( $fields )
	{
		$this->fields = $fields;
		return $this;
	}

	public function setStartTime( $StartTime )
	{
		$this->StartTime = $StartTime;
		return $this;
	}

	public function setEndTime( $endTime )
	{
		$this->EndTime = $endTime;
		return $this;
	}

	public function setBarCode( $barCode )
	{
		$this->BarCode = $barCode;
		return $this;
	}

	public function setProductName( $ProductName )
	{
		$this->ProductName = $ProductName; // 注意首字母大写
		return $this;
	}

	public function setProductNum( $ProductNum )
	{
		$this->ProductNum = $ProductNum;
		return $this;
	}


    public function setPagenum( $pagenum )
    {
        $this->pagenum = $pagenum;
        return $this;
    }

	public function setPageSize( $pageSize )
	{
		$this->page_size = $pageSize;
		return $this;
	}
}