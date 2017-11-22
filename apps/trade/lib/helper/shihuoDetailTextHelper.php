<?php
function procShiHuoDetailText($original_text = '',$link_url ='', $goods_title = '', $is_detailPage = 0, $param,$flag = 1,$is_text_img_link = 1){
    if($original_text && $is_text_img_link){

        $store_urls = '(taobao\.com|tmall\.com|tmall\.hk|\.yihaodian\.com|\.yhd\.com|\.1mall\.com|\.dangdang\.com|360buy\.com|jd\.com|\.s\.cn|\.paixie\.net|\.k121\.com|\.inshion\.com|\.taoxie\.com|springtour\.com|xiaomi\.com|\.e-lining\.com|\.xietoo\.com|yougou\.com|laiyifen\.com|dianping\.com|vipshop\.com|ctrip\.com|\.gome\.com\.cn|\.tao3c\.com|\.coo8\.com|suning\.com|\.efeihu\.com|\.womai\.com|\.masamaso\.com|\.vancl\.com|\.yintai\.com|\.camel\.com|\.newegg\.com\.cn|\.newegg\.cn|\.quwan\.com|\.yihaodian\.com|\.zm7\.cn|\.crucco\.com|\.vjia\.com|\.tonlion\.com|\.rax\.cn|\.banggo\.com|\.ihush\.com|\.letao\.com|\.homew\.com|amazon\.cn|amazon\.com|51buy\.com|yixun\.com|jumei\.com|mbaobao\.com|sfbest\.com|yohobuy\.com|yododo\.cn|super8\.com\.cn|998\.com|vip\.com)';

        //已经存在go链接的
        $pregStr = '/<a[^>]+?href=[\'|"](http:\/\/(go\.hupu\.com)[^>]*?[\s|\S]*?)[\'|"][^>]*?>(.*?<\/a>)/eis';
        $replStr = "getTradeNewUrlByUrl(\"\\1\",\"\\3\",'$param')";
        $original_text = preg_replace($pregStr, $replStr, $original_text);

        //匹配的正则表达式
        $pregStr = '/<a[^>]+?href=[\'|"]((https|http):\/\/(?!go\.hupu\.com)[^>]*?' . $store_urls . '[\s|\S]*?)[\'|"][^>]*?>(.*?)<\/a>/eis';
        $replStr = "getTradeAllUrlByUrl(\"\\1\",\"\\4\",'$param','$flag')";
        $original_text = preg_replace($pregStr, $replStr, $original_text);

        if (!$is_detailPage){//判断是否为内页
            $pregStr = "/(<\s*img.+)src=(\"?.+\"?)(.+\s*\/{0,1}>)/iU";//img图片标签正则
            $replStr = "<a isconvert=1 href='".$link_url."' target='_blank'>";
            $replStr.="\${1} src='http://b3.hoopchina.com.cn/images/noneImg.png' data-src=\${2}\${3}</a>";
        } else {
            $pregStr = '/(<\s*img.+\s*\/{0,1}>)/iU'; //img图片标签正则
            $replStr = "<a isconvert=1 href='".$link_url."' target='_blank'";
            $replStr.=" data-track='from/shihuo-content-img-". $goods_title. "' ";
            $replStr.=">\${1}</a>";
        }

        $original_text = preg_replace($pregStr, $replStr, $original_text);
    }

    return $original_text;
}

function getTradeAllUrlByUrl($itemUrl,$title,$param,$flag=1){

    $return  = '<a isconvert=1 rel="nofollow" target="_blank" href="';
    if ($flag == 2) {
        $url = parse_url($itemUrl);
        if (strpos($url['host'],'taobao') != false || strpos($url['host'],'tmall') != false) {
            $return  .= $itemUrl;
        } else{
            $apiConfig = sfConfig::get('app_api');
            $return  .= $apiConfig['go']['url'].'?url='.urlencode($itemUrl).$param;
        }
    } else {
        $apiConfig = sfConfig::get('app_api');
        $return  .= $apiConfig['go']['url'].'?url='.urlencode($itemUrl).$param;
    }
    $return  .='">'.$title.'</a>';
    return $return;
}

function getTradeNewUrlByUrl($itemUrl,$title,$param){
    $return  = '<a isconvert=1 rel="nofollow" target="_blank" href="';
    $return  .= $itemUrl.$param;
    $return  .='">'.$title.'</a>';
    return $return;
}

function getLitterContentForApp($original_text = '',$link_url ='',$param=''){
    if($original_text){

        $store_urls = '(taobao\.com|tmall\.com|tmall\.hk|\.yihaodian\.com|\.yhd\.com|\.1mall\.com|\.dangdang\.com|360buy\.com|jd\.com|\.s\.cn|\.paixie\.net|\.k121\.com|\.inshion\.com|\.taoxie\.com|springtour\.com|xiaomi\.com|\.e-lining\.com|\.xietoo\.com|yougou\.com|laiyifen\.com|dianping\.com|vipshop\.com|ctrip\.com|\.gome\.com\.cn|\.tao3c\.com|\.coo8\.com|suning\.com|\.efeihu\.com|\.womai\.com|\.masamaso\.com|\.vancl\.com|\.yintai\.com|\.camel\.com|\.newegg\.com\.cn|\.newegg\.cn|\.quwan\.com|\.yihaodian\.com|\.zm7\.cn|\.crucco\.com|\.vjia\.com|\.tonlion\.com|\.rax\.cn|\.banggo\.com|\.ihush\.com|\.letao\.com|\.homew\.com|amazon\.cn|amazon\.com|51buy\.com|yixun\.com|jumei\.com|mbaobao\.com|sfbest\.com|yohobuy\.com|yododo\.cn|super8\.com\.cn|998\.com|vip\.com)';

        //已经存在go链接的
        $pregStr = '/<a[^>]+?href=[\'|"](http:\/\/(go\.hupu\.com)[^>]*?[\s|\S]*?)[\'|"][^>]*?>(.*?<\/a>)/eis';
        $replStr = "getTradeNewUrlByUrl(\"\\1\",\"\\3\",'')";
        $original_text = preg_replace($pregStr, $replStr, $original_text);

        //匹配的正则表达式
        $pregStr = '/<a[^>]+?href=[\'|"](http:\/\/(?!go\.hupu\.com)[^>]*?' . $store_urls . '[\s|\S]*?)[\'|"][^>]*?>(.*?)<\/a>/eis';
        $replStr = "getTradeAllUrlByUrl(\"\\1\",\"\\3\",'$param')";
        $original_text = preg_replace($pregStr, $replStr, $original_text);

//           $pregStr = '/(<\s*img.+\s*\/>)/iU'; //img图片标签正则
//           $replStr = "<a href='".$link_url."' target='_blank'";
//           $replStr.=" data-track='from/shihuo-content-img-". $goods_title. "' ";
//           $replStr.=">\${1}</a>";
//
//           $original_text = preg_replace($pregStr, $replStr, $original_text);
//             $aa = '/(<\s*img.+\s*\/>)/iU'; //img图片标签正则
//             $bb = preg_match_all($aa, $original_text, $matches);
//             if (isset($matches[1]) && !empty($matches) && count($matches[1]) > 10){
//                 $new_array = array_slice($matches[1],10);
//                 foreach ($new_array as $k=>$v){
//                     $original_text = str_replace($v,'',$original_text);
//                 };
//             }
    }
    return $original_text;
}

function procShiHuoDuihuanText($original_text = ''){

    if($original_text){

        $store_urls = '(taobao\.com|tmall\.com|tmall\.hk|\.yihaodian\.com|\.yhd\.com|\.1mall\.com|\.dangdang\.com|360buy\.com|jd\.com|\.s\.cn|\.paixie\.net|\.k121\.com|\.inshion\.com|\.taoxie\.com|springtour\.com|xiaomi\.com|\.e-lining\.com|\.xietoo\.com|yougou\.com|laiyifen\.com|dianping\.com|vipshop\.com|ctrip\.com|\.gome\.com\.cn|\.tao3c\.com|\.coo8\.com|suning\.com|\.efeihu\.com|\.womai\.com|\.masamaso\.com|\.vancl\.com|\.yintai\.com|\.camel\.com|\.newegg\.com\.cn|\.newegg\.cn|\.quwan\.com|\.yihaodian\.com|\.zm7\.cn|\.crucco\.com|\.vjia\.com|\.tonlion\.com|\.rax\.cn|\.banggo\.com|\.ihush\.com|\.letao\.com|\.homew\.com|amazon\.cn|amazon\.com|51buy\.com|yixun\.com|jumei\.com|mbaobao\.com|sfbest\.com|yohobuy\.com|yododo\.cn|super8\.com\.cn|998\.com|vip\.com)';

        //已经存在go链接的
        $pregStr = '/<a[^>]+?href=[\'|"](http:\/\/(go\.hupu\.com)[^>]*?[\s|\S]*?)[\'|"][^>]*?>(.*?<\/a>)/eis';
        $replStr = "getTradeNewUrlByUrl(\"\\1\",\"\\3\",'')";
        $original_text = preg_replace($pregStr, $replStr, $original_text);

        //匹配的正则表达式
        $pregStr = '/<a[^>]+?href=[\'|"](http:\/\/(?!go\.hupu\.com)[^>]*?' . $store_urls . '[\s|\S]*?)[\'|"][^>]*?>(.*?)<\/a>/eis';
        $replStr = "getTradeAllUrlByUrl(\"\\1\",\"\\3\",'')";
        $original_text = preg_replace($pregStr, $replStr, $original_text);
        $original_text = preg_replace($pregStr, $replStr, $original_text);
    }
    return $original_text;
}