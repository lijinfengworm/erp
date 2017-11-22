<?php
/*
 *日期选择器
 *
 **/
class tradeWidgetFormDatetime extends sfWidgetFormInput{


    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->addOption('class');

    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $class = !$this->getOption('class') ? 'btn' : $this->getOption('class') ;

        sfContext::getInstance()->getResponse()->addJavascript('/js/trade/groupon_admin/My97DatePicker/WdatePicker.js');

        $return = '<input type="text" name="'.$name.'" value= "'.$value.'" class="'.$class.' form_datetime" onClick="WdatePicker({skin:\'twoer\'})" >';

        return $return;
    }


}