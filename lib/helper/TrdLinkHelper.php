<?php

function go_link($itemUrl,$taobaoItemId = 0,$flag=1)
{
    if ($flag == 2) {
        $url = parse_url($itemUrl);
        if (strpos($url['host'],'taobao') != false || strpos($url['host'],'tmall') != false) {
            return $itemUrl;
        }
    }
    $apiConfig = sfConfig::get('app_api');
    
    if($taobaoItemId != 0)
    {
        $url = parse_url($itemUrl);
         $go_url = $apiConfig['go']['url'].'?url='.urlencode('http://'.$url['host'].'/item.htm?id='.$taobaoItemId);
    }
    else{
         $go_url = $apiConfig['go']['url'].'?url='.urlencode($itemUrl);
    }


    return $go_url;
}

function shop_go_link($shopUrl, $nick = "") {
    $apiConfig = sfConfig::get('app_api');

    $go_url = $apiConfig['go']['url'].'?url='.urlencode($shopUrl);

    if($nick) {
        $go_url .= "&nick=" . urlencode($nick);
    }

    return $go_url;
}

function user_profile_link($hupuUid)
{
    return  'http://my.hupu.com/'.$hupuUid;
}

function news_go_link($itemUrl, $tp = "", $title="", $news_id="", $type="",$flag = 1){
    if ($flag == 2) {
        $url = parse_url($itemUrl);
        if (strpos($url['host'],'taobao') != false || strpos($url['host'],'tmall') != false) {
            return $itemUrl;
        }
    }
    $apiConfig = sfConfig::get('app_api');

    $go_url = $apiConfig['go']['url'].'?url='.urlencode($itemUrl);

    if($tp) {
        $go_url .= "&tp=" . urlencode($tp);
    }
    if($title) {
        $go_url .= "&title=" . urlencode($title);
    }
    if($news_id) {
        $go_url .= "&news_id=" . urlencode($news_id);
    }
    if($type) {
        $go_url .= "&type=" . urlencode($type);
    }

    return $go_url;
}

function param_go_link($tp = "", $title="", $news_id="", $type=""){
    $go_url = '';

    if($tp) {
        $go_url .= "&tp=" . urlencode($tp);
    }
    if($title) {
        $go_url .= "&title=" . urlencode($title);
    }
    if($news_id) {
        $go_url .= "&news_id=" . urlencode($news_id);
    }
    if($type) {
        $go_url .= "&type=" . urlencode($type);
    }

    return $go_url;
}


/*搜索页*/
function search_link($infos,$type,$search_id){
    $url = 'http://www.shihuo.cn/search';

    $_param = array();
    if(isset($infos['channelType']) && $infos['channelType']) $_param['channelType'] = $infos['channelType'];
    if(isset($infos['dateSpace']) && $infos['dateSpace']) $_param['dateSpace'] = $infos['dateSpace'];
    if(isset($infos['keywords']) && $infos['keywords']) $_param['keywords'] = $infos['keywords'];

    if($type == 'channelType'){
        if($search_id && $infos['channelType'] && strpos($infos['channelType'],$search_id) !== false){
            $channelType = preg_replace('/'.$search_id.'/','',$infos['channelType']);
            if(!$channelType) $channelType = 0;
        }elseif($search_id && $infos['channelType'] && strpos($infos['channelType'],$search_id) === false){
            $channelType = $infos['channelType'].$search_id;
        }else{
            $channelType = $search_id;
        }
        $_param['channelType'] =$channelType;

    }elseif($type == 'dateSpace'){
        $dateSpace = $search_id;

        $_param['dateSpace'] =$dateSpace;
    }

    if($_param) $url .= '?'.http_build_query($_param);
    return $url;
}


/*tags列表页*/
function tags_link($infos,$type,$search_id){
    $url = 'http://www.shihuo.cn/tag';

    $_param = array();
    if(isset($infos['dateSpace']) && $infos['dateSpace']) $_param['dateSpace'] = $infos['dateSpace'];
    if(isset($infos['keywords']) && $infos['keywords']) $url .= '/'.$infos['keywords'];

    if($type == 'dateSpace'){
        if($search_id){
            $dateSpace = $search_id;
            $_param['dateSpace'] =$dateSpace;
        }else{
            if(isset($_param['dateSpace'])) unset($_param['dateSpace']);
        }
    }

    if($_param) $url .= '?'.http_build_query($_param);
    return $url;
}

/* 生成期刊url  */
function create_journal_url($id = '',$tmp = '',$tips = '') {
    if(in_array($tmp,TrdSpecial::$NOT_DATA_TMP)) {
        return url_for("@default?module=shiwuzhi&action=detail&id=".$id).$tips;
    } else {
        return url_for("@default?module=special&action=index&id=".$id).$tips;
    }
}