<?php

class starUrlUtil{
    
    /**
     * 通过一个连接获取到 这个链接对应的messageid 或者topicid
     * @param type $url
     */
    static public function getMessageidOrTopicidByUrl($url)
    {
        
        $result = array('message_id'=>0,'topic_id'=>0);
        preg_match('/(\d+)\.html/', $url,$match);
        if(!empty($match[1]))
        {
            $result['message_id'] = $match[1];
        }else{
            preg_match('/topic\/(\S+)/', $url,$match);
            if(!empty($match[1]))
            {
                $slug = $match[1];
                $topic = twitterTopicTable::getInstance()->createQuery()->where('slug =?',$slug)->fetchOne();
                if($topic)
                {
                    $result['topic_id'] = $topic->getId();
                }
            }
        }
        return $result;
    }
    
    public static function getMobileUrl($messageId,$category)
    {
        switch ($category):
        case 1:
            return 'http://mt.hupu.com/nba/news/'.$messageId.'.html';
            break;
        case 2:
            return 'http://mt.hupu.com/soccer/news/'.$messageId.'.html';
            break;
        case 3:
            return 'http://mt.hupu.com/f1/news/'.$messageId.'.html';
            break;
        case 4:
            return 'http://mt.hupu.com/tennis/news/'.$messageId.'.html';
            break;
        case 5:
            return 'http://mt.hupu.com/sports/news/'.$messageId.'.html';
            break;
        case 6:
            return 'http://mt.hupu.com/cba/news/'.$messageId.'.html';
            break;
        case 7:
            return 'http://mt.hupu.com/soccer/news/china/'.$messageId.'.html';
            break;
        default :
            return '';
        endswitch;
    }
}
?>
