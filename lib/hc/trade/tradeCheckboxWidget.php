<?php
class tradeCheckboxWidget extends sfWidgetFormSelectCheckbox {
    function render($name, $value = null, $attributes = array(), $errors = array()) {
        $rs = array();

        if(is_string($value)) {
            $arr = explode(",", $value);
            foreach($arr as $item) {
                if($item) {
                    $rs[] = $item;
                }
            }
        } else {
            $rs =$value;
        }

        return parent::render($name, $rs, $attributes, $errors);
    }
}
