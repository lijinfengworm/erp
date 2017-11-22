<?php
class ListUrlUtil {
    public $curr_params;

    function __construct($curr_params) {
        $this->curr_params = $curr_params;
    }

    //过滤条件链接
    public function choose($new_param, $value, $unique=false) {
        for($i=0; $i <count($this->curr_params); $i++) {
            foreach($new_param as $k => $v) {
                if(($this->curr_params[$i]["key"] == $k) && ($this->curr_params[$i]["value"] == $v)) {
                    $this->curr_params[$i]["name"] = $value["value"];
                }
            }
        }

        $params = array();
        if(!$unique) {
            foreach($this->curr_params as $item) {
                $params[$item["key"]] = $item["value"];
            }
        }

        $params = array_merge($params, $new_param);
        $query = http_build_query($params);

        return sfContext::getInstance()->getController()->genUrl("@list_filter?module=item&action=list&" . $query);
    }

    //过滤条件删除链接
    public function unchoose($name) {
        $params = array();
        foreach($this->curr_params as $item) {
            if($item["key"] != $name) {
                $params[$item["key"]] = $item["value"];
            }
        }

        $query = http_build_query($params);
        if($query) {
            $query = "&" . $query;
        }

        return url_for("@list_filter?module=item&action=list" . $query);
    }

    //判断该条件是否选中
    public function is_choosed($param) {
        for($i=0; $i <count($this->curr_params); $i++) {
            foreach($param as $k => $v) {
                if(($this->curr_params[$i]["key"] == $k) && ($this->curr_params[$i]["value"] == $v)) {
                    return true;
                }
            }
        }

        return false;
    }

    //当前选中条件
    public function show_params() {
        $no_show_list = array("sort");
        $arr = $this->curr_params;
        for($i = 0; $i < count($arr); $i++) {
            if($arr[$i]["key"] == "q") {
                $arr[$i]["name"] = "搜索: " . $arr[$i]["value"];
            }
        }
        
        for($i = 0; $i < count($arr); $i++) {
            if(in_array($arr[$i]["key"], $no_show_list)) {
                unset($arr[$i]);
            }
        }

        return $arr;
    }
}

