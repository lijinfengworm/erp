<?php

function weiboZhName($type) {
    $type = strtoupper($type);
    switch ($type) {
        case 'FACEBOOK':
            return 'facebook';
        case 'TWITTER':
            return 'twitter';
        case 'SINA':
            return '新浪微博';
        case 'QQ':
            return '腾讯微博';
        case 'SOHU':
            return '搜狐微博';
        case 'INSTAGRAM':
            return 'instagram';
        default:
            return '其他微博';
    }
}

function replyBnt($light, $reply) {
    $html = $light ? $light . '亮' : '';
    $html.= $reply ? $reply . '回复' : '回复';
    return $html;
}

function getSlugRoutingName($root_id) {
    switch ($root_id) {
        case 58:
            return 'f1slug';
        case 63:
            return 'soccerslug';
        case 64:
            return 'olympicsslug';
        case 107:
            return 'tennisslug';
        default :
            return 'slug';
    }
}
function getSlug($root_id) {
    switch ($root_id) {
        case 58:
            return 'f1';
        case 63:
            return 'soccer';
        case 64:
            return '2012';
        case 107:
            return 'tennis';
        default :
            return 'nba';
    }
}

function getWeiboRoutingName($root_id) {
    switch ($root_id) {
        case 58:
            return 'f1weibo';
        case 63:
            return 'soccerweibo';
        case 64:
            return 'olympicsweibo';
        case 107:
            return 'tennisweibo';
        default :
            return 'weibo';
    }
}

function getTagTitle($tag_name, $category = null) {
    if ($category === null) {
        return $tag_name;
    }
    switch ($category) {
        case voiceHotEventTable::$soccer:
            $n = ' - 足球的脉搏';
            break;
        default:
            $n = ' - 篮球的脉搏';
            break;
    }


    return $tag_name . ' - 虎扑新声' . $n;
}

function messageRender($text) {
    $text = preg_replace('#(^|[^"])(https{0,1}://(([a-z0-9-]+\.)+[a-z]{2,6})[a-z0-9:;&\#@=_~%\?\/\.\,\+\-]+)#i', '$1 <a target="_blank" href="$2">$3...</a><a href="$2" target="_blank" class="cut-linkA"><i class="voice-ico-cutTheLink"></i></a> ', $text);
    return preg_replace('/<img[^>]*>/i', '', $text);
}

function messageRender2($text) {
    return preg_replace('/<img[^>]*>/i', '', $text);
}

function myurlencode($string) {
    return str_replace(array(' ', ':'), array('%20', '%3A'), $string);
}

function getLiveTimeByGroupIdAndMessageId($groupMessages, $group_id, $message_id) {
    foreach ($groupMessages as $v) {
        if ($v->getTwitterTopicGroupId() == $group_id && $message_id == $v->getTwitterMessageId()) {
            return $v->getLiveTime();
        }
    }
}

/**
 * 数组usort中的会掉函数
 * 
 * @param Doctrine_Collection $reply_a
 * @param Doctrine_Collection $reply_b
 * 
 * @return integer   -1、0、1
 */
function myLightRepliesSort($reply_a, $reply_b) {
    if ($reply_a->getLightCount() == $reply_b->getLightCount())
        return 0;
    return $reply_a->getLightCount() > $reply_b->getLightCount() ? -1 : 1;
}

function getPublishDate($timeline) {
    $time = time() - $timeline;
    
    if ($time <= 5) {
        return '刚刚';
    } elseif ($time < 60) {
        return $time . '秒前';
    } elseif ($time < 3600) {
        return floor($time / 60) . '分钟前';
    } elseif ($time < 86400) {
        return floor($time / 3600) . '小时前';
    } else {
        return date('n月j日', $timeline);
    }
}