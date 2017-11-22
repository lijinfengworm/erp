<?php
class tradeColorCheckboxWidget extends sfWidgetFormSelectCheckbox {
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

        $arr = array();
        foreach($this->getChoices() as $key => $item) {
            $color = explode("_", $item);
            $color_name = $color[0];
            $color_value = $color[1];
            if(!empty($rs) && in_array($key, $rs)) {
                $checked = " checked='checked' ";
            } else {
                $checked = "";
            }

            $arr[] = "<li><input type='checkbox' $checked value='$key' name='{$name}[]' />".
                "<label title='{$color_name}色' style='background-color: {$color_value}'for='data_color_ids_{$key}'>$color_name</label></li>";
        }
        return "<ul class='checkbox_list'>" . join("", $arr) . "</ul>";
    }
}
