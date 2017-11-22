<?php

/**
 * 添加商品
 * Class edbProductDetailAdd
 */
 class edbProductDetailAdd {
 	public function __construct(){
 		$this->setFields('is_success,response_Code,response_Msg,field');
        $this->setXmlValues();
 	}

 	public function setFields( $fields ){
		$this->fields = $fields;
		return $this;
	}

	public function setXmlValues(){
		$xmlValues="<order>
    <orderInfo>
        <brand_name>米格达思</brand_name>
        <sort_name>背心1</sort_name>
        <supplier>aaa22a1</supplier>
        <productNo>12134569083</productNo>
        <product_name>艾樱 3D V脸雪肽微雕紧致面膜5片 补水保湿抗皱玻尿酸面膜贴 （官方直售 正品保证 全国包邮 售后无忧）</product_name>
        <market_price>198.00</market_price>
        <retail_price>158.00</retail_price>
        <product_intro>紧致肌肤 补水 保湿 提拉紧</product_intro>
        <factory_item>20150101678967</factory_item>
        <wfpid>122200002</wfpid>
    </orderInfo>
    <detailInfo>
        <detail_item>
            <bar_code>su1010822983</bar_code>
            <specification>件</specification>
            <color>aa</color>
            <size>a</size>
            <unit></unit>
            <weight></weight>
            <product_status>正常</product_status>
            <sell_price>158.00</sell_price>
            <contrast_purchase_price></contrast_purchase_price>
            <mini_purchase_price></mini_purchase_price>
            <cost></cost>
            <cost_tax></cost_tax>
            <purchase_price_ex></purchase_price_ex>
            <box_num></box_num>
            <period_validity></period_validity>
            <picture></picture>
            <is_consump></is_consump>
            <consump_cycle></consump_cycle>
            <is_single_send></is_single_send>
            <attribute></attribute>
            <purchase_type></purchase_type>
            <productURL></productURL>
        </detail_item>
    </detailInfo>
</order>";

		$xmlValues = str_replace(array(" ","　","\t","\n","\r"),array("","","","",""), $xmlValues);
		$this->xmlValues=$xmlValues;
		return $this;
	}
 }