<?php
class KaluliAllListUrlUtil {
    public $curr_params;

    function __construct($curr_params) {
        $this->curr_params = $curr_params;
    }
    
    /**
     *
     *  重新拼接属性条件
     * @param int $del_attr_id 要删除的属性id 
     * @param bool $flag 表示拼接关键词url  
     * @param bool $issort 是否是排序拼接 
     * @param string $groupattr 去除某个属性后的url
     */
    public function getJoinLink($del_attr_id,$flag = false,$issort = false,$groupattr=''){
        $params = $this->curr_params;
        $query = array();
        if (isset($params['rootname']) && !empty($params['rootname'])){//一级菜单
            $query['r'] = $params['rootname']['id'];
        }
        if (isset($params['childrenname']) && !empty($params['childrenname'])){//二级菜单
            $query['c'] = $params['childrenname']['id'];
        }

        if (isset($params['store']) && !empty($params['store'])){//商城标签
            $query['store'] = $params['store'];
        }

        if (!$issort){
            if (isset($params['sort']) && !empty($params['sort'])){//排序
                $query['sort'] = $params['sort'];
            }
        }
        if (!$flag){//拼接选择条件关键词的url
            if (isset($params['w']) && !empty($params['w'])){//关键词
                $query['w'] = $params['w'];
            }
        }
        
        if (isset($params['attrname']) && !empty($params['attrname'])){//属性块
            if (!empty($del_attr_id)){
                if (isset($params['attrname'][$del_attr_id]) && !empty($params['attrname'][$del_attr_id])){
                    unset($params['attrname'][$del_attr_id]);
                }
            }
            $attr = '';
            if (isset($params['attrname']) && !empty($params['attrname'])){
                foreach ($params['attrname'] as $k=>$v){
                    foreach ($v as $kk=>$vv){
                        if ($groupattr != $vv['ga']){
                            $attr .= $vv['ga'].'_';
                        }
                    }
                }
                if ($attr) $query['ga'] = rtrim($attr,'_');
            }
        }
        
        $url = sfContext::getInstance()->getController()->genUrl("@category_list?type=find");
        if (isset($query['r']) && !empty($query['r']) && !isset($query['c']) && empty($query['c'])){
            $url = sfContext::getInstance()->getController()->genUrl("@item_all_list_one?r=" . $query['r']);
            unset($query['r']);
        }
        if (isset($query['c']) && !empty($query['c'])){
            $url = sfContext::getInstance()->getController()->genUrl("@item_all_list_two?r=" . $query['r'] ."&c=" . $query['c']);
            unset($query['r']);
            unset($query['c']);
        }
        $where = '';
        foreach ($query as $m=>$n){
            if ($n) $where .= '&'.$m.'='.urlencode($n);
            $where = ltrim($where,'&');
        }
        if ($query){
            $url = $url .'?'.$where;
        }
        
        return $url;
    }
    
    //变成新的url字符串
    public function _formatAttr($attr){
        if (!$attr) return false;
        $gr = explode('_', $attr);
        $return  = $sort = array();
        foreach ($gr as $k=>$v){
            $mr = explode("-",$v);
            $return[$mr[0]][] = $mr[1];
        }
        ksort($return);
        foreach ($return as $kk=>&$vv){
            asort($vv);
            //array_multisort($vv, SORT_ASC, $vv);
        }
        $str = '';
        foreach ($return as $m=>$n){
            foreach($n as $mm=>$nn){
                $str .= $m.'-'.$nn.'_';
            }
        }
        $str = rtrim($str,'_');
        return $str;
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

        return sfContext::getInstance()->getController()->genUrl("@category_list?type=all&" . $query);
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


    /**
     *商品列表页的连接
     * @param string $prefix 前缀
     * @param string $name
     * @param string $val
     * @param int $type 1表示增加 0 表示删除
     */
    public function getProductNewUrl($prefix,$name,$val,$type=1){
        $curr_params = $this->curr_params;
        if(isset($curr_params['root_id']))unset($curr_params['root_id']);
        if(isset($curr_params['children_id']))unset($curr_params['children_id']);

        if ($type == 1){
            $where = array();
            if($name) $curr_params[$name] = $val;
            if (isset($curr_params['brand'])) $where['brand'] = $curr_params['brand'];
            if (isset($curr_params['type'])) $where['type'] = $curr_params['type'];
            if (isset($curr_params['scheme'])) $where['scheme'] = $curr_params['scheme'];
            if (isset($curr_params['price'])) $where['price'] = $curr_params['price'];
            if (isset($curr_params['order'])) $where['order'] = $curr_params['order'];
            if (isset($curr_params['page']) &&  $curr_params['page'] > 1) $where['page'] = $curr_params['page'];
            if (isset($curr_params['keywords'])) $where['keywords'] = $curr_params['keywords'];
            if (isset($curr_params['aIds'])) $where['aIds'] = $curr_params['aIds'];
            if (isset($curr_params['forPeople'])) $where['forPeople'] = $curr_params['forPeople'];
            $curr_params = $where;
        } else {
            unset($curr_params[$name]);
        }
        $return  = $prefix;
        $postfix = '';
        if (isset($curr_params['brand'])){
            $postfix .= '-'.urlencode(urlencode(strtr($curr_params['brand'],'-','*')));
            unset($curr_params['brand']);
        } else {
            if(isset($curr_params['type']) || isset($curr_params['scheme']) || isset($curr_params['forPeople']))
                 $postfix .= '-0';
        }
        if (isset($curr_params['type'])){
            $postfix .= '-'.$curr_params['type'];
            unset($curr_params['type']);
        } else {
            if(isset($curr_params['scheme']) || isset($curr_params['forPeople']))
                $postfix .= '-0';
        }
        if (isset($curr_params['scheme'])){
            $postfix .= '-'.$curr_params['scheme'];
            unset($curr_params['scheme']);
        } else {
            if(isset($curr_params['forPeople']))
                $postfix .= '-0';
        }
        if (isset($curr_params['forPeople'])){
            $postfix .= '-'.$curr_params['forPeople'];
            unset($curr_params['forPeople']);
        }

        if ($postfix){
            $return .= '/p'.$postfix;
        }
        $query = http_build_query($curr_params);
        if ($query){
            return $return.'?'.$query;
        } else {
            return $return;
        }
    }
}

