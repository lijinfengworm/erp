<?php

class specialForm extends BaseForm {
    public function configure()
    {
        $_id        =    $this->getOption("id", false);                      //id
        $_title     =    $this->getOption("title", false);                   //title
        $_pic       =    $this->getOption("pic", false);                     //图片
        $_price     =    $this->getOption("price", false);                   //price
        $_url       =    $this->getOption("url", false);                     //url
        $_discount  =    $this->getOption("discount", false);                //折扣数
        $_daigou    =    $this->getOption("daigou", false);                  //代购抓取
        $_num       =    $this->getOption("num", 4);                         //数量
        $_must_num  =    $this->getOption("must_num", $_num);                //必填数量
        $_guonei_price  =    $this->getOption("guonei_price", false);
        $_m_url     =    $this->getOption("murl",false);

        $_type      =    $this->getOption("type", 'special');                //标志

        for($i=0;$i<$_num;$i++) {
            if($_id){//id是否显示
                if($i >= $_must_num){
                    $_widgets[$i."[id]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"选填项"));
                }else {
                    $_widgets[$i."[id]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"必填项"));

                }

                $_schema[$i."[id]"] = "商品ID:";
            }

            if($_price){//price是否显示
                if($i >= $_must_num){
                    $_widgets[$i."[price]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"选填项"));
                }else {
                    $_widgets[$i."[price]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"必填项"));

                }

                $_schema[$i."[price]"] = "价格:";
            }

            if($_guonei_price){
                if($i >= $_must_num){
                    $_widgets[$i."[guonei_price]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"选填项"));
                }else {
                    $_widgets[$i."[guonei_price]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"必填项"));

                }

                $_schema[$i."[guonei_price]"] = "国内价:";
            }

            if($_pic){//图片是否显示
                if($i >= $_must_num){
                    $_widgets[$i."[pic]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w240','placeholder'=>"选填项"));
                }else {
                    $_widgets[$i."[pic]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w240','placeholder'=>"必填项"));

                }

                $rule = is_numeric(key($_pic))? $_pic[$i] : $_pic;
                $_widgets[$i."[btn]"] = new tradeWidgetFormKupload(array("callback"=>'callback("'.$_type.'_'.$i.'_pic",data.url);',"rule"=>$rule));
                $_schema[$i."[pic]"] = "图片地址:";
            }

            if($_url){//url是否显示
                if($i >= $_must_num){
                    $_widgets[$i."[url]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w240','placeholder'=>"选填项"));
                }else {
                    $_widgets[$i."[url]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w240','placeholder'=>"必填，必须是url"));
                }

                $_schema[$i."[url]"] = "链接地址:";
            }

            if($_url){//url是否显示
                if($i >= $_must_num){
                    $_widgets[$i."[murl]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w240','placeholder'=>"选填项"));
                }else {
                    $_widgets[$i."[murl]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w240','placeholder'=>"必填，必须是url"));
                }

                $_schema[$i."[murl]"] = "m站链接地址:";
            }

            if($_title){
                if($i >= $_must_num){
                    $_widgets[$i."[title]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"选填项"));
                }else {
                    $_widgets[$i."[title]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"必填项"));
                }

                $_schema[$i."[title]"] = "关键字:";
            }

            if($_daigou){
                $_widgets[$i."[flag]"] = new sfWidgetFormChoice(array('choices'=>array('商品','优惠信息')));
                $_widgets[$i."[get]"] = new sfWidgetFormInput(array(),array('class'=>'daigou_goods_get "btn btn-warning','type'=>"button","value"=>"获取",'onclick'=>"getDaigouInfo({$i},'{$_type}')"));
            }

            if($_discount){//折扣数
                if($i >= $_must_num){
                    $_widgets[$i."[discount]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"选填项"));
                }else {
                    $_widgets[$i."[discount]"] = new sfWidgetFormInputText(array(),array('class'=>'text_default w120','placeholder'=>"必填项"));

                }

                $_schema[$i."[discount]"] = "折扣:";
            }

            // 循环验证
            if($i >= $_must_num){
                if($_id)        $_validator[$i."[id]"]    = new sfValidatorNumber(array('trim' => true,'required'=>false));
                if($_price)     $_validator[$i."[price]"] = new sfValidatorNumber(array('trim' => true,'required'=>false));
                if($_discount)  $_validator[$i."[discount]"] = new sfValidatorNumber(array('trim' => true,'required'=>false));
                if($_title)     $_validator[$i."[title]"] = new sfValidatorString(array('trim' => true,'required'=>false));
                if($_url)       $_validator[$i."[url]"]   = new sfValidatorUrl(array('trim' => true,'required'=>false),array('invalid'=>'url格式不正确'));
                if($_pic)       $_validator[$i."[pic]"]   = new sfValidatorUrl(array('trim' => true,'required'=>false),array('invalid'=>'url格式不正确'));
                if($_guonei_price)   $_validator[$i."[guonei_price]"] = new sfValidatorNumber(array('trim' => true,'required'=>false));
                if($_m_url)       $_validator[$i."[murl]"]   = new sfValidatorUrl(array('trim' => true,'required'=>false),array('invalid'=>'url格式不正确'));

            }else{
                if($_id)        $_validator[$i."[id]"]    = new sfValidatorNumber(array('trim' => true),array('required'=>'不得为空'));
                if($_price)     $_validator[$i."[price]"] = new sfValidatorNumber(array('trim' => true),array('required'=>'不得为空'));
                if($_discount)  $_validator[$i."[discount]"] = new sfValidatorNumber(array('trim' => true),array('required'=>'不得为空'));
                if($_title)     $_validator[$i."[title]"] = new sfValidatorString(array('trim' => true),array('required'=>'不得为空'));
                if($_url)       $_validator[$i."[url]"]   = new sfValidatorUrl(array('trim' => true),array('required'=>'不得为空','invalid'=>'url格式不正确'));
                if($_pic)       $_validator[$i."[pic]"]   = new sfValidatorUrl(array('trim' => true),array('required'=>'不得为空','invalid'=>'url格式不正确'));
                if($_guonei_price)  $_validator[$i."[guonei_price]"] =new sfValidatorNumber(array('trim' => true),array('required'=>'不得为空'));
                if($_m_url)       $_validator[$i."[murl]"]   = new sfValidatorUrl(array('trim' => true,'required'=>false),array('invalid'=>'url格式不正确'));

            }
        }

        $this->setWidgets($_widgets);
        $this->widgetSchema->setLabels($_schema);
        $this->widgetSchema->setNameFormat("{$_type}[%s]");

        $this->setValidators($_validator);
    }
}

?>
