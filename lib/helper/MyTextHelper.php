<?php

/*
 * 转义标题
 */

function wap_cv($string) {
    $string = str_replace(array('&amp;', '\t', '\r', '>', '<', '—'), array('&', ' &nbsp; &nbsp;', '', '&gt;', '&lt;', '&mdash;'), $string);
    $string = gb2312ToUtf8($string);
    return illegalCharactersFilter($string);
}

function mobile_title($string) {
    $string = str_replace(array('&amp;', '\t', '\r', '>', '<', '—', '&lt;img src=&quot;http://w1.hoopchina.com.cn/index/images/photo.gif&quot;&gt;', '&lt;img src=&quot;http://w1.hoopchina.com.cn/index/images/play.gif&quot;&gt;', '&lt;img src=&quot;http://w3.hoopchina.com.cn/index/images/photo.gif&quot;&gt;', '&lt;img src=&quot;http://w3.hoopchina.com.cn/index/images/play.gif&quot;&gt;'), array('&', ' &nbsp; &nbsp;', '', '&gt;', '&lt;', '&mdash;', '', '', '', ''), $string);
    return gb2312ToUtf8($string);
}

function gb2312ToUtf8($string) {
    return mb_convert_encoding($string, "UTF-8", "gbk");
}

function my_compare($a, $b) {
    return strcasecmp($b['time'], $a['time']);
}

function weibo_common($string, $showLink = false, $width=100) {    
    $string = str_replace("\'", "'", $string);
    //$string = preg_replace('/(.+?) @([^@]*)/', '$1 //@$2', $string);
    if($showLink){
        $string = preg_replace('/(http:\/\/t\.cn\/[0-9a-z]+)/i', '<a target="_blank" class="c6" href="$1">$1</a>', $string);
        $string = preg_replace('/<a.*?href=["\'](http:\/\/url\.cn\/[0-9a-z]+)["\'].*?>/i', '<a target="_blank" class="c6" href="$1">', $string);
    } 
    return preg_replace_callback('/<img.*?src=["\']?(.*?)["\'].*?>/i', 'voiceImgReplace', $string);    
    return preg_replace('/<img.*?src=["\']?(.*?)["\'].*?>/i', '<br><img src="$1" width="'.$width.'" ><br>', $string);
}

function voiceImgReplace($match){
    return '<br><img _src="'.$match[1].'" src="http://img04.store.sogou.com/net/a/46/link?appid=46&url='.urlencode($match[1]).'">';
}


function m_weibo_content($string, $showLink = false, $width=100){
    return weibo_common($string, $showLink, $width);
}
function web_weibo_content($string, $showLink = false, $width=100){
    $string = weibo_common($string, $showLink, $width);
    $string = preg_replace('/\s(http:\/\/t.co.*?)(\s|$)/', ' <a target="_blank" class="c6" href="http://untiny.me?url=$1">$1</a> ', $string);
    return $string;
}

function weibo_from($string) {
    if (strpos($string, 'weibo.com') !== false) {
        return '新浪微博';
    } elseif (strpos($string, 't.qq') !== false) {
        return '腾讯微博';
    } elseif (strpos($string, 't.sohu') !== false) {
        return '搜狐微博';
    } else {
        return '其他微博';
    }
}

/*
 * 手机版
 */
function weibio_content_reply($string){   
    $string = str_replace("\n", '<br/>', $string);
    $string = str_replace(array('[quote]', '[/quote]', '[b]', '[/b]'), array('[', ']', '<b>', '</b>'), $string);
    return $string;
}
function weibo_list_reply($string){    
    $string = preg_replace('/\[quote\]([\w|\t|\r|\W]*?)\[\/quote\]/', '', $string);  //去掉引用
    $string = preg_replace("/^\s+/", '', $string); //换第一个回车
    $string = str_replace("\n", '<br/>', $string);
    $string = preg_replace('/\[[a-z0-9\/]+\]/', '', $string);
    $string = preg_replace("/^<br\/>/i", '', $string);
    $string = preg_replace("/^<br>/i", '', $string);
    $string = preg_replace("/^<br \/>/i", '', $string);
    $string = preg_replace('/(^|\s)(http:\/\/t\.co\/[0-9a-z]+)([^0-9a-z]|$)/i', ' <a target="_blank" class="c6" href="$1">$1</a>', $string);
    return trim($string, "\n");
}
/*
 * web版
 */
function weibio_content_reply_web($string){   
    $string = str_replace("\n", '<br/>', $string);
    
    $string = str_replace(array('[b]', '[/b]', '[quote]', '[/quote]'), array('<b>', '</b>', '<blockquote>', '</blockquote>'), $string);
//    var_dump($string);
    return $string;
}
function light_content($string, $pid, $tid = null) {
    $string = wap_content($string);
    $len = sfConfig::get('app_light_reply_content_length');
    $suffixStr = '...<a href="'.url_for('@post?tid=' . $tid.'#'.$pid).'">全文</a>';
    $string = wsubstr($string, $len, $suffixStr, 0, 'img|object|embed|a|small|p|table|tr|td|tbody|tfooter', 0.9, 'utf-8');
    return $string;
}
function voice_topics($topics){
    if(!count($topics)) return ;
    $str = '话题：';
    foreach($topics as $v){
        $str .= '<a target="_blank" href="'.url_for('@topic?slug='.$v['slug']).'">'.$v['title'].'</a>&nbsp;&nbsp;';
    }
    return $str;
}
/*
 * 截字符串 不截断html
 */
function wsubstr($str, $length = 0, $suffixStr = "...", $start = 0, $tags = "div|span|p", $zhfw = 0.9, $charset = "utf-8") {
    $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

    $zhre['utf-8'] = "/[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $zhre['gb2312'] = "/[\xb0-\xf7][\xa0-\xfe]/";
    $zhre['gbk'] = "/[\x81-\xfe][\x40-\xfe]/";
    $zhre['big5'] = "/[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    $tpos = array();
    preg_match_all("/<(" . $tags . ")([\s\S]*?)>|<\/(" . $tags . ")>/ism", $str, $match);
    $mpos = 0;
    for ($j = 0; $j < count($match[0]); $j++) {
        $mpos = strpos($str, $match[0][$j], $mpos);
        $tpos[$mpos] = $match[0][$j];
        $mpos += strlen($match[0][$j]);
    }
    ksort($tpos);

//根据标签位置解析整个字符
    $sarr = array();
    $bpos = 0;
    $epos = 0;
    foreach ($tpos as $k => $v) {
        $temp = substr($str, $bpos, $k - $epos);
        if (!empty($temp))
            array_push($sarr, $temp);
        array_push($sarr, $v);
        $bpos = ($k + strlen($v));
        $epos = $k + strlen($v);
    }
    $temp = substr($str, $bpos);
    if (!empty($temp))
        array_push($sarr, $temp);

//忽略标签截取字符串
    $bpos = $start;
    $epos = $length;
    for ($i = 0; $i < count($sarr); $i++) {
        if (preg_match("/^<([\s\S]*?)>$/i", $sarr[$i]))
            continue; //忽略标签

        preg_match_all($re[$charset], $sarr[$i], $match);

        for ($j = $bpos; $j < min($epos, count($match[0])); $j++) {
            if (preg_match($zhre[$charset], $match[0][$j]))
                $epos -= $zhfw; //计算中文字符
        }

        $sarr[$i] = "";
        for ($j = $bpos; $j < min($epos, count($match[0])); $j++) {//截取字符
            $sarr[$i] .= $match[0][$j];
        }
        $bpos -= count($match[0]);
        $bpos = max(0, $bpos);
        $epos -= count($match[0]);
        $epos = round($epos);
    }

//返回结果
    $slice = join("", $sarr); //自己可以加个清除空html标签的东东
    if ($slice != $str)
        return $slice . $suffixStr;
    return $slice;
}

/*
 * 获取用户当前访问的URL
 */

function getUrl() {
    if (!empty($_SERVER["REQUEST_URI"])) {
        $scrtName = $_SERVER["REQUEST_URI"];
        $nowurl = $scrtName;
    } else {
        $scrtName = $_SERVER["PHP_SELF"];
        if (empty($_SERVER["QUERY_STRING"])) {
            $nowurl = $scrtName;
        } else {
            $nowurl = $scrtName . "?" . $_SERVER["QUERY_STRING"];
        }
    }
    return strtolower('http://' . $_SERVER['HTTP_HOST'] . $nowurl);
}

/*
 * 
 * wap 内容过滤
 * 为了不影响线上的hc手机版，新建了一个函数，稳定后合并
 *
 * */

function gh_wap_content($string, $covert = true) {
    ini_set('pcre.backtrack_limit', 1000000);
    if ($covert) {
        $string = gb2312ToUtf8($string);
    }
    $string = str_replace(array('\n', '[quote]', '[/quote]'), array('', '<quote>', '</quote>'), $string);
    $string = preg_replace("/<script(.*?)<\/script(.*?)>/is", "", $string);
    $string = preg_replace("/<style(.*?)<\/style(.*?)>/is", "", $string);
    $string = preg_replace("/\[vote\]\d*?\[\/vote\]/is", "", $string);
    $string = preg_replace('/\[img\](.*?)\[\/img\]/is', '<img src="$1">', $string);
    $string = preg_replace('/\[[^<>].*?\]/is', '', $string);  //过滤所有UBB代码, [<a></a>]此为合理链接，不可过滤。
    $string = htmlspecialchars_decode($string);
    $string = preg_replace_callback('/<a[^<>]*?href=[\"\'](((?!goalhi)[^<>])*?)[\"\'][^<>]*?>(.*?)<\/a>/is', "funcCallBack", $string);  //外链转移动版
    $string = preg_replace('/<img.*?src=[\"|\']javascript.*?[\"|\'].*?>/i', '', $string);
    $string = preg_replace('/<img.*?src=[\"\']\\\".*?>/i', '', $string);
    $string = preg_replace('/<img.*?src=\\\".*?>/i', '', $string);  //<img src=\"http://i1.hoopcdfdfd.com/343.jpg\" width=34>
    $string = preg_replace('/<img.*?src=[\"|\'](((?!hoopchina).)*?)[\"|\'].*?>/i', '<center>[a href="$1"]外链图片[/a]</center>', $string);  //外链图片
    $string = preg_replace('/\[img\](((?!hoopchina).)*?)\[\/img\]/i', '<center>[a href="$1"]外链图片[/a]</center>', $string);  //外链图片
    $string = preg_images($string); //站内图片
    $string = str_replace(array('<quote>', '</quote>', '&#60;embed'), array('[ ', ' ]{br}', '<embed'), $string);
    $string = preg_replace('/<div class="quote"><div class="quote_box">([\s\S]*?)<\/div><\/div>/', "[ $1 ]<br />　　", $string);
    $string = preg_replace("/<\/?div(.*?)>/", "<br />　", $string);
    $string = strip_tags($string, "<p> <img> <br> <table> <tr> <td> <tfoot> <tbody> <small> <embed> <object> <center>");
    $string = preg_replace('/\[xxx.*?\][^\[]*?(\[a href=".*?"\].*?\[\/a\])[^\[]*?\[\/xxx\]/i', '$1', $string); //处理a标签中嵌套a标签,去除外面的a标签
    $string = preg_replace('/\[xxx(.*?)](.*?)\[\/xxx\]/', '<a$1>$2</a>', $string);
    $string = preg_replace('/\[a.*?href=\"(.*?)\"\](.*?)\[\/a\]/', '<a href="$1">$2</a>', $string);
    $string = preg_replace("/<embed.*?src=[\"|\'](.*?)[\"|\'].*?>/is", '<embed src="$1" wmode="transparent"  quality="high" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" width="240" height="200"$4 ></embed>', $string);
    $string = preg_replace("/<br>/is", "<br />", $string);
    $string = preg_replace("/<p(.*?)>/is", "<br />", $string);
    $string = preg_replace("/<\/p>/is", "", $string);
    //用p标签来排版
    $string = preg_replace("/<br(.*?)>/is", "</p><p>", $string);
    $string = "<p>{$string}</p>";
    $string = preg_replace("/<br \/>(　)*<br \/>((　)*<br \/>)*/", "<br />", $string);
    $string = preg_replace('/<br \/>([\s|\t]*?)<br \/>/i', '', $string);
    $string = preg_replace('/\sjquery\d*?=".*?"/i', ' ', $string); //去除标签中类似： jquery1300516854656="33"的多余字符，节省流量
    $string = preg_replace('/<br.*?\/>/is', '<br />', $string);
    $string = preg_replace('/{br}/is', '<br />', $string);
    //足球广告过滤
    $string = preg_replace('/>>查看最新足球流言: 中国队 中超/is', '', $string);
    $string = preg_replace('/>>查看最新足球流言: 中超 中国队 西甲 转会签约/is', '', $string);
    $string = preg_replace('/>>足球新闻Android手机客户端下载/is', '', $string);
    $string = preg_replace('/>>进入《热血球球》GoalHi专区参与球星乱斗/is', '', $string);
    $string = preg_replace('/观看手工精选足球视频/is', '', $string);
    $string = preg_replace('/我也要添加足球流言/is', '', $string);
    $string = preg_replace('/观看最全足球直播/is', '', $string);
    $string = preg_replace('/<p>\s+<\/p>/is', '', $string);
    $string = preg_replace('/<p><\/p>/is', '', $string);
    //$string = preg_replace('/<p>　<\/p>/is', '', $string);
    //$string = preg_replace('/<p>　+(.*)<\/p>/is', '<p>$1</p>', $string);
    $string = illegalCharactersFilter($string);
    return $string;
}

/*
 * 转义回复内容和帖子内容
 * 文字多的情况下 性能是方法2的5倍
 */

function wap_content($string, $covert = true) {
    ini_set('pcre.backtrack_limit', 1000000);    
    if ($covert) {
        $string = gb2312ToUtf8($string);
    }    
    $string =  preg_replace('/\[azhibo111\](.*?)\[\/azhibo111\]/', '', $string);
    $string = str_replace(array('\n', '[quote]', '[/quote]'), array('', '<quote>', '</quote>'), $string);
    $string = preg_replace("/<script(.*?)<\/script(.*?)>/is", "", $string);
    $string = preg_replace("/<style(.*?)<\/style(.*?)>/is", "", $string);
    $string = preg_replace('/\[url\]([^\[\]]*?)\[\/url\]/is', '<url href="$1">$1</url>', $string);  //转换ubb代码中的链接
    $string = preg_replace("/\[vote\]\d*?\[\/vote\]/is", "", $string);
    $string = preg_replace('/\[img\](.*?)\[\/img\]/is', '<img src="$1">', $string);  
    $string = preg_replace('/\[[a-z][^\]]*\]|\[\/[a-z]+\]/is', '', $string);  //过滤所有UBB代码  
    $string = preg_replace('/<url\shref="([^<>]*?)">([^<>]*?)<\/url>/is', '[a href="$1"]$2[/a]', $string);      //将转换为<url>形式的ubb代码转换回去
    $string = str_replace('../../..', 'http://wap.hupu.com/bbs', $string);
    $string = preg_replace('/<a[^<>]*?>([^<>]*?)<img([^<>]*?)><\/a>/', '$1<img$2>', $string);  //链接嵌套图片的问题, 去掉外面的链接
    $string = preg_replace('/<a[^<>]*?href=["\'](http:\/\/(bbs|m)\.hupu\.com[^<>]*?)["\'][^<>]*?>(.*?)<\/a>/is', '[a href="$1"]$3[/a]', $string);
    $string = preg_replace('/<a[^<>]*?href=["\']http:\/\/(nba|soccer)\.hupu\.com\/news\/\d*\/(\d*)\.html["\'][^<>]*?>(.*?)<\/a>/is', '[a href="http://wap.hupu.com/$1/news/$2.html"]$3[/a]', $string);
    $string = preg_replace('/<a[^<>]*?href=["\']http:\/\/(nba|soccer)\.hupu\.com\/news\/?["\'][^<>]*?>(.*?)<\/a>/is', '[a href="http://wap.hupu.com/$1/news"]$2[/a]', $string);
    $string = preg_replace_callback('/<a[^<>]*?href=[\"\'](((?!hupu)[^<>])*?)[\"\'][^<>]*?>(.*?)<\/a>/is', "funcCallBack", $string);  //外链转移动版
    $string = preg_replace('/<img[^<>]*?src=[\"|\'](javascript|javscript|data)[^>]*?[\"|\'].*?>/i', '', $string);
    $string = preg_replace('/<img[^<>]*?src=\\\"[^<>]*?>/i', '', $string);  //<img src=\"http://i1.hoopcdfdfd.com/343.jpg\" width=34>
    $string = preg_replace_callback('/<img[^<>]*?src=&quot;(((?!(hoopchina|goalhi)).)*?)&quot;.*?>/i', "outsiteCallBack", $string);  //可恶的box图片
    $string = preg_replace_callback('/<img[^<>]*?src=[\"|\'](((?!(hoopchina|goalhi)).)*?)[\"|\'].*?>/i', "outsiteCallBack", $string);  //外链图片
    $string = preg_replace_callback('/\[img\](((?!hoopchina).)*?)\[\/img\]/i', "outsiteCallBack", $string);    
    $string = preg_images($string); //站内图片   
    $string = str_replace(array('<quote>', '</quote>', '&#60;embed'), array('[ ', ' ]{br}', '<embed'), $string);
    $string = preg_replace('/<div class="quote"><div class="quote_box">([\s\S]*?)<\/div><\/div>/', "[ $1 ]<br />　　", $string);
    $string = preg_replace("/<\/?div(.*?)>/", "<br />", $string);
    $string = preg_replace('/<a.*?href=[\"\']http:\/\/caipiao\.hoopchina\.com(.*?)[\"\'].*?>(.*?)<\/a>/', '[a href="http://caipiao.m.hoopchina.com$1"]$2[/a]', $string);
    $string = strip_tags($string, "<p> <img> <br> <table> <tr> <td> <tfoot> <tbody> <small> <embed> <object> <center> <param>");   
    $string = preg_replace('/\[xxx[^\[\]]*?\][^\[]*?(\[a href="[^\[\]]*?"\].*?\[\/a\])[^\[]*?\[\/xxx\]/i', '$1', $string); //处理a标签中嵌套a标签,去除外面的a标签
    $string = preg_replace('/\[xxx(.*?)](.*?)\[\/xxx\]/', '<a$1>$2</a>', $string);
    $string = preg_replace('/\[a.*?href=\"(.*?)\"\](.*?)\[\/a\]/', '<a href="$1">$2</a>', $string);
    $string = preg_replace('/<a[^<>]*?href=["\']http:\/\/bbs\.hupu\.com\/(\d*)\.html["\'][^<>]*?>([^<>]*?)<\/a>/is', '<a href="http://wap.hupu.com/bbs/$1.html">$2</a>', $string);
    $string = preg_replace("/<embed.*?src=[\"|\'](.*?)[\"|\'].*?>/is", '<embed src="$1" wmode="transparent"  quality="high" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" width="240" height="200"$4></embed>', $string);
    $string = preg_replace("/<br>/is", "<br />", $string);
    $string = preg_replace("/<p (.*?)>/is", "<br />", $string);
    $string = preg_replace("/<\/p>/is", "", $string);
    $string = preg_replace("/<br \/>(　)*<br \/>((　)*<br \/>)*/", "<br />", $string);
    $string = preg_replace('/(<br \/>([\s|\t]*))+/i', '<br />', $string);
    $string = preg_replace('/<br \/>+/i', '<br />', $string);
    $string = preg_replace('/\sjquery\d*?=".*?"/i', ' ', $string); //去除标签中类似： jquery1300516854656="33"的多余字符，节省流量
    $string = preg_replace('/<br[^<>]*?\/>/is', '<br />', $string);
    $string = preg_replace('/{br}/is', '<br />', $string);
    $string = preg_replace('/\[a\shref[^\]]*\]/', '', $string); //过滤[a href="xxx"]
    $string = preg_replace('/\[\/a\]/', '', $string); //过滤[/a]
    $string = illegalCharactersFilter($string);  
    return $string;
}

/*
 * 回调函数
 */

function outsiteCallBack($matches) {
    return '<center>[a href="'.$matches[1].'"]<img  border="0" src="http://img04.store.sogou.com/net/a/46/link?appid=46&url='. urlencode($matches[1]).'" />[/a]</center>';
}
function funcCallBack($matches) {
    $name = isset($matches[3]) ? $matches[3] : '';
    $name = preg_replace("/^\n/", '', $name);
    //azhibo地址原样输出
    if(FALSE === strpos($matches[1], 'www.azhibo.com')){
        return '[xxx href="http://www.google.com/gwt/x?u=' . urlencode($matches[1]) . '"]' . $name . '[/xxx]';
    }else{
        return '[xxx href="' . $matches[1] . '"]' . $name . '[/xxx]';
    }
    
}

/*
 * 回调函数1
 */

function funcCallBack1($matches) {
    if (strpos($matches[2], 'b1.hoopchina.com.cn/post/smile')) {
        return '<img src="' . $matches[2] . '" >';
    }
    if (strpos($matches[2], 'b3.hoopchina.com.cn/post/smile')) {
        return '<img src="' . $matches[2] . '" >';
    }
    return '<center>[a href="'. $matches[2] . '"]<img border="0" src="http://img04.store.sogou.com/net/a/46/link?appid=46&url=' . urlencode($matches[2]) . '" />[/a]</center>';
}

/*
 * 回调函数2
 */

function funcCallBack2($matches) {
    if (strpos($matches[1], 'b1.hoopchina.com.cn/post/smile')) {
        return '<img src="' . $matches[1] . '" >';
    }
    if (strpos($matches[1], 'b3.hoopchina.com.cn/post/smile')) {
        return '<img src="' . $matches[1] . '" >';
    }
    return '<center>[a href="' . $matches[1] . '"]<img border="0" src="http://img04.store.sogou.com/net/a/46/link?appid=46&url='  . urlencode($matches[2]) . '" />[/a]</center>';
}

/*
 * 站内图片匹配
 */

function preg_images($string) {
    $string = preg_replace_callback('/<img[^<>]*?src=(\"|\'|&quot;)(([^<>]*?)(hoopchina)\.com(\.cn)?([^<>]*?))(\"|\'|&quot;)[^<>]*?\s?>/i', "funcCallBack1", $string);
    $string = preg_replace_callback('/\[img\]((.*?)hoopchina\.com(\.cn)?(.*?))\[\/img\]/i', "funcCallBack2", $string);
    return $string;
}

/*
 * 根据PC端地址返回手机端地址
 */

function getMobileHref($href) {
    if (is_numeric($href)) {
        return url_for('@news?module=news&action=news&id=' . $href);
    }
    if (preg_match_all('/http:\/\/soccer\.hupu.*\/?priority(.*)/', $href, $match)) {
        return url_for('@newslist?tag=zhongda');
    } else if (preg_match_all('/http:\/\/(.*)\.(goalhi|hupu).*\/column.*?#(\d*)/', $href, $match)
            || preg_match_all('/http:\/\/(.*)\.(goalhi|hupu).*\/column\/(\d*).html/', $href, $match)
    ) {
        return url_for('@column_item?id=' . $match[3][0]);
    } else if (preg_match_all('/http:\/\/news\.goalhi.*\/(\d*)\/(\d*)\.html/', $href, $match)) {
        return url_for('@news?module=news&action=news&category=soccer&id=' . $match[2][0]);
    } else if (preg_match_all('/http:\/\/(.*)\.(hoopchina|goalhi).*\/(\d*)-?(\d*)\.html/', $href, $match)) {
        if ($match[1][0] == 'bbs') {
            return url_for('@post?module=bbs&action=post&tid=' . $match[3][0]);
        } else {
            return url_for('@news?module=news&category=nba&action=news&id=' . $match[3][0]);
        }
    } elseif (preg_match_all('/(nba|soccer)\.hupu\.com\/news.*\/(\d*)-?(\d*)\.html/', $href, $match)) {
        return url_for('@news?id=' . $match[2][0]);
    } elseif (preg_match_all('/bbs\.hupu\.com\/(\d*)-?(\d*)\.html/', $href, $match)) {
        return url_for('@post?tid=' . $match[1][0]);
    } elseif (preg_match('/(nba|soccer)\.hupu\.com\/news\/?/', $href, $match)) {
        return  url_for('@default_index?module=news') ;
    } elseif (preg_match('/(nba|www|soccer|bbs)\.hupu\.com\/?/', $href, $match)) {
        if ($match[1] == 'www') {
            return url_for('@index');
        } elseif ($match[1] == 'soccer' || $match[1] == 'nba') {
            return url_for('@homepage');
        }elseif ($match[1] == 'bbs'){
            return url_for('@bbsIndex');
        }
    }
    if(strpos($href, 'http')!==0){
        $href = 'http://'.$href;
    }
    return $href;
}

/*
 * 和谐过滤
 */

function illegalCharactersFilter(&$message, $ifreplace = true) {
    $newfile = sfConfig::get('sf_web_dir') . '/generated/mobile/wordsfb.php';
    if (file_exists($newfile))
    {
        $wordfile = $newfile;
    }
    else
    {
        $wordfile = sfConfig::get('sf_web_dir') . '/../data/wordsfb.php';
    }
    if (!extension_loaded('badwords')) {
        global $replace;
        isset($replace) || include($wordfile);
        return strtr($message, $replace);
    }
    $triebin = '/tmp/com.hoopchina.bbs-wordsfb.bin';

    $persistkey = 'badwords::com.hoopchina.m::wordsfb';
    $wmtime = filemtime($wordfile);
    $tmtime = file_exists($triebin) ? filemtime($triebin) : false;

    if ($tmtime === FALSE || $tmtime !== $wmtime && mt_rand(0, 99) < 5) {
        include($wordfile);
        $compiler = badwords_compiler_create(BADWORDS_ENCODING_UTF8, True);
        badwords_compiler_append($compiler, $replace);
        unset($replace);
        $trie = badwords_compiler_compile($compiler);
        unset($compiler);
        if ($trie) {
            $triebin_tmp = $triebin . '-' . getmypid();
            file_put_contents($triebin_tmp, $trie);
            touch($triebin_tmp, $wmtime);
            rename($triebin_tmp, $triebin);
            unset($trie);
        }
    }
    $badwords = badwords_create($triebin, $persistkey);
    return badwords_replace($badwords, $message);
}


/* functions used in module: news */

function newsContent($string) {
    $string = gb2312ToUtf8($string);
    $string = htmlspecialchars_decode($string);
    $string = preg_replace('/\sjquery\d*?=".*?"/i', ' ', $string);
    $string = preg_replace('/\[.*?\].*?\[\/.*?\]/is', '', $string);
    $string = preg_replace('/<p[^>]*?>.*?<a[^>]*?>.*?<img[^>]*?>.*?<\/a>[\s\S]*?<\/p>/i', '', $string);
    $string = strip_tags($string, '<p><br>');
    return $string;
}

function newsContent_new($string) {
    $string = htmlspecialchars_decode($string);
    $string = gb2312ToUtf8($string);
    $string = preg_replace('/\sjquery\d*?=".*?"/i', ' ', $string);
    $string = preg_replace('/\[.*?\].*?\[\/.*?\]/is', '', $string);
    $string = strip_tags($string, '<p><br><a>');
    $string = preg_replace_callback('/<a.*?href=[\"\']((.*)goalhi.com(.*))[\"\'](\s)?(.)?>(.*?)<\/a>/i', "in_site_link_change", $string);  //站内链转移动版
    $string = preg_replace_callback('/<a[^<>]*?href=[\"\'](((?!hoopchina)[^<>])*?)[\"\'][^<>]*?>(.*?)<\/a>/i', "funcCallBack", $string);  //站外链转移动版
    $string = preg_replace('/\[xxx.*?\].*?(\[a href=".*?"\].*?\[\/a\]){1}.*?\[\/xxx\]/i', '$1', $string); //处理a标签中嵌套a标签,去除外面的a标签
    $string = preg_replace('/\[xxx(.*?)](.*?)\[\/xxx\]/', '<a$1>$2</a>', $string);
    return $string;
}

//站内链接转换
function in_site_link_change($matches) {
    $name = isset($matches[6]) ? $matches[6] : '';
    $link = isset($matches[1]) ? $matches[1] : '';
    $link = getMobileHref($link);
    return '[xxx href="' . $link . '"]' . $name . '[/xxx]';
}

/* 文字直播 */

function live($string) {
    $string = strip_tags($string, '<b>');
    return $string = str_replace(array('&nbsp;', ' '), '', $string);

    return $string = gb2312ToUtf8($string);
}

/* functions used in module: news */

function getArticleTime($date) {
    if (substr($date, 0, 10) == date('Y-m-d')) {
        return substr($date, 11, 5);
    } else {
        return substr($date, 5, 5);
    }
}

 function checkInfoFrom($via){
       $vias = array(
            1 => '<a style="color:#666" href="http://kanqiu.hupu.com/app?_r=bbsvia1" target="_blank">发自虎扑体育Android客户端</a>',
            2 => '<a style="color:#666" href="http://kanqiu.hupu.com/app?_r=bbsvia2" target="_blank">发自虎扑体育iPhone客户端</a>',
            3 => '<small class="f666">发自手机虎扑 m.hupu.com</small>',
            4 => '<small class="f666">发自legendlee的HupuChrome</small>',
            5 => '<small class="f666">发自手机虎扑 m.hupu.com</small>',
            6 => '<small class="f666">发自手机虎扑 m.hupu.com</small>',
            8 => '<a style="color:#666" href="http://kanqiu.hupu.com/app?_r=bbsvia8" target="_blank">发自虎扑体育App</a>',
            9 => '<a style="color:#666" href="http://kanqiu.hupu.com/app?_r=bbsvia9" target="_blank">发自虎扑体育Android客户端</a>',
            10 =>'<a style="color:#666" href="http://kanqiu.hupu.com/app?_r=bbsvia10" target="_blank">发自虎扑体育iPhone客户端</a>',
       );
        $tempvias = array_flip($vias);
        if (in_array($via,$tempvias))
              return $vias[$via];
        return '';
    }
    /**
     * 
     * 新闻detail_text若有图片，转换图片地址
     */
    function  wapNewsContent($str){
        $str = preg_replace_callback('/< *img[^>]*src *= *["\']?([^"\']*)["\']? *>/i', "newsImgUrlCallback", $str); 
        return $str;
    }
    
    /**
     * 新闻内页图片url转换
    */
    function newsImgUrlCallback($matches){
      return    '<center><img border="0" src="http://img04.store.sogou.com/net/a/46/link?appid=46&url=' . urlencode($matches[1]) . '" /></center>';
    }

