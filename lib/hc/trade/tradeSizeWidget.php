<?php
class tradeSizeWidget extends sfWidgetFormSelectCheckbox {
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

        $man = array();
        $woman = array();
        foreach($this->getChoices() as $key => $item) {
            $arr = explode("_", $item);
            $size = $arr[0];
            $sex = $arr[1];
            if(!empty($rs) && in_array($key, $rs)) {
                $checked = " checked='checked' ";
            } else {
                $checked = "";
            }

            $str = "<li><input type='checkbox' $checked value='$key' name='{$name}[]' /> <label for='data_size_ids_{$key}'>$size</label></li>";

            if($sex == 1) {
                $man[] = $str;
            } else {
                $woman[] = $str;
            }
        }

        return "<div class='item man_sizes'><div class='hdItem'><h4>男鞋：</h4><label><input type='checkbox' data='man' class='size_checkall' />全选</label></div>"
            . "<div class='itemList'><ul>"
            . join("", $man)
            . "</ul></div></div>"
            . "<div class='item woman_sizes'><div class='hdItem'><h4>女鞋：</h4><label><input type='checkbox' data='woman' class='size_checkall' />全选</label></div>"
            . "<div class='itemList'><ul>"
            . join("", $woman)
            . "</ul></div></div>";
    }
}
