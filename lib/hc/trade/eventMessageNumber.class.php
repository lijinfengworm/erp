<?php
/**
 * Description of eventNewMessageNumber
 *
 * @author hcsyp
 */
class eventMessageNumber {
    
    private static $expire = 300; //5min
    private static $cache = null;

    private static function getCache(){        
        if (!isset(self::$cache)) {            
            self::$cache = new voiceCache();
        }
        return self::$cache;
    }
    
    /*
     * 获取tag的所有message数
     */
    public static function getTag($tag_id){
        $key = 'tag_messages_number_' . $tag_id;
        if(!$num = self::getCache()->get($key)){
            $num = voiceTagTwitterMessageTable::getMessageCount($tag_id);
            self::getCache()->set($key, $num, self::$expire);
        }
        return $num;
    }
    
    
    /*
     * 对热门事件中的tag的message数加1
     */
    public static function tagsIncrease($category, $tags){
        if(empty($tags)) return ;
        $events = voiceHotEventTable::getHotEvents($category);
        foreach($events as $event){
            if($event->getObjectType() == $event->TAGTYPE && in_array($event->getVoiceTagId(), $tags)){
                self::tagIncrease($event->getVoiceTagId());
            }
        }
    }
    
    

    /*
     * tag的message数加1
     */
    public static function tagIncrease($tag_id){
        $key = 'tag_messages_number_' . $tag_id;
        self::getCache()->set($key, self::getCache()->get($key) + 1, self::$expire);    
    }
    
    /*
     * 对热门事件中的tag的message数加1
     */
    public static function updateTopic($category, $topic_id){
        $events = voiceHotEventTable::getHotEvents($category);
        foreach($events as $event){
            if($event->getObjectType() == $event->TOPICTYPE && $event->getTwitterTopicId() == $topic_id){
                $key = 'topic_messages_number_' . $topic_id;
                $num = twitterTopicTable::getAllMessagesNumber($topic_id);
                self::getCache()->set($key, $num, self::$expire);
            }
        }
    }

    /*
     * 获取topic下所有message数
     */
    public static function getTopic($topic_id){
        $key = 'topic_messages_number_' . $topic_id;
        if(!$num = self::getCache()->get($key)){
            $num = twitterTopicTable::getAllMessagesNumber($topic_id);
            self::getCache()->set($key, $num, self::$expire);
        }        
        return $num;
    }
}

?>
