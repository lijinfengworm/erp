<?php

/*
 * ttServer操作类
 */

class liangleMemcache {

    protected static $instance;
    protected $connection;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = sfContext::getInstance()->getDatabaseConnection('liangleMemcache');
        }
        return self::$instance;
    }

    public static function readTt($key) {
        return self::getInstance()->get($key);
    }

    /*
     * getMulti()
     */

    public static function getMutilKeys($keys, $cas_tokens = null, $flags = null) {
        return self::getInstance()->getMulti($keys, $cas_tokens, $flags);
    }

    /*
     * 写key值
     * 失败返回false，成功返回true
     */

    public static function writeTt($key, $value, $expire = 0) {
        return self::getInstance()->set($key, $value, 0, $expire);
    }

    /*
     * 获取焦点图片信息,返回序列化之后的字符串,为空返回null
     */

    public static function getFocusPhotos() {
        $photos = self::readTt('focusPhotos');        
        if (!$photos) {        //键值为空或获取失败
            $photos = self::setFocusPhotos();
            return $photos === false ? null : $photos;
        }
        return $photos;
    }

    /*
     * 设置焦点图片信息
     * 成功返回序列化之后的字符串
     * 失败返回false
     */

    public static function setFocusPhotos() {
        $photos = serialize(LlArticleTable::getFocusPhotos());
        if (self::writeTt('focusPhotos', $photos)) {
            return $photos;
        }
        return false;
    }

    /*
     * 获取头条新闻,返回序列化之后的字符串,为空返回null
     */

    public static function getHeaderNews() {
        $headerNews = self::readTt('headerNews');
        if (!$headerNews) {        //键值为空或获取失败
            $headerNews = self::setHeaderNews();
            return $headerNews === false ? null : $headerNews;
        }
        return $headerNews;
    }

    /*
     * 设置头条新闻
     * 成功返回序列化之后的字符串
     * 失败返回false
     */

    public static function setHeaderNews() {
        $headerNews = serialize(LlArticleTable::getHeaderNews());
        if (self::writeTt('headerNews', $headerNews)) {
            return $headerNews;
        }
        return false;
    }
    /*
     * 获取每日必看视频
     */

    public static function getRecommendVideos() {
        $videos = self::readTt('videos');
        if (!$videos) {        //键值为空或获取失败
            $videos = self::setRecommendVideos();
            return $videos === false ? null : $videos;
        }
        return $videos;
    }

    /*
     * 设置每日必看视频
     * 成功返回序列化之后的字符串
     * 失败返回false
     */

    public static function setRecommendVideos() {
        $videos = serialize(v_videoTable::getInstance()->getSystemRecommendVideos());
        if (self::writeTt('videos', $videos, 180)) {
            return $videos;
        }
        return false;
    }
    /*
     * 获取花边数据
     */

    public static function getSwaggers() {
        $swaggers = self::readTt('swaggers');
        if (!$swaggers) {        //键值为空或获取失败
            $swaggers = self::setSwaggers();
            return $swaggers === false ? null : $swaggers;
        }
        return $swaggers;
    }

    /*
     * 设置花边数据
     * 成功返回序列化之后的字符串
     * 失败返回false
     */

    public static function setSwaggers() {
        $swaggers = serialize(llArticleTable::getCategoryArticles(8));
        if (self::writeTt('swaggers', $swaggers, 86400)) {
            return $swaggers;
        }
        return false;
    }

    /*
     * 获取每日必看右侧话题栏目数据
     */

    public static function getBbs() {
        $bbs = self::readTt('bbs');
        
        if (!$bbs) {        //键值为空或获取失败
            $bbs = self::setBbs();
            return $bbs === false ? null : $bbs;
        }
        return $bbs;
    }

    /*
     * 设置每日必看右侧话题栏目数据
     * 成功返回序列化之后的字符串
     * 失败返回false
     */

    public static function setBbs() {
        $bbs = LlArticleTable::getBbs();
        if(!$bbs)   return false;
        $tids = array();
        foreach($bbs as $v){
            preg_match('/http:.*?\/(\d+)\.html/i', $v['redirect_url'], $matches);
            $tids[] = $matches[1];
        }
        $replies = pw_threadsTable::getInstance()->getRepliesByTids($tids);
        foreach($bbs as $k => &$v){
            $v['replies'] = isset($replies[$tids[$k]]) ? $replies[$tids[$k]] : 0;
        }
        $bbs = serialize($bbs);
        if (self::writeTt('bbs', $bbs, 1800)) {
            return $bbs;
        }
        return false;
    }

    /*
     * 获取每日必看右侧话题栏目数据
     */

    public static function getFeatures() {
        $features = self::readTt('features');
        if (!$features) {        //键值为空或获取失败
            $features = self::setFeatures();
            return $features === false ? null : $features;
        }
        
        return $features;
    }

    /*
     * 设置每日必看右侧话题栏目数据
     * 成功返回序列化之后的字符串
     * 失败返回false
     */

    public static function setFeatures() {
        $features = serialize(LlArticleTable::getFeatures());
        if (self::writeTt('features', $features, 3500)) {
            return $features;
        }
        return false;
    }

    /*
     * 获取某天的比赛信息,返回json字符串,为空返回null
     * $day: 2011-06-01
     */

    public static function getMatchs($day = null) {
        $day = $day === null ? date('Y-m-d') : $day;
        $matchs = self::readTt('matchs_' . $day);
        
        if (!$matchs || $matchs == '[]') {        //键值为空或获取失败
            $matchs = self::setMatchs($day);
            return $matchs === false ? null : $matchs;
        }
        return $matchs;
    }

    /*
     * 设置比赛信息
     * 成功返回json字符串
     * 失败返回false
     */

    public static function setMatchs($day = null) {
        $day = $day === null ? date('Y-m-d') : $day;
        $tmp = array();
        $tmp = LlMatchTable::getMatchs($day);
        $tmp[] = date('Y-m-d', strtotime($day) - 3600 * 24);
        $tmp[] = date('Y-m-d', strtotime($day) + 3600 * 24);
        $tmp[] = substr($day, 5);
        if(count($tmp) > 3){
            $matchs = json_encode($tmp);
            if (self::writeTt('matchs_' . $day, $matchs)) {
                return $matchs;
            }
        }else{
            return $matchs = json_encode($tmp);
        }
        return false;
    }
    /*
     * 根据日期更新比赛信息，用户后台管理比赛时调用
     */
    public static function updateMatchsByDate($date = null) {
        $day = $date === null ? date('Y-m-d') : $date;
        $tmp = array();
        $tmp = LlMatchTable::getMatchs($day);
        $tmp[] = date('Y-m-d', strtotime($day) - 3600 * 24);
        $tmp[] = date('Y-m-d', strtotime($day) + 3600 * 24);
        $tmp[] = substr($day, 5);
        $matchs = json_encode($tmp);
        self::writeTt('matchs_' . $day, $matchs);
             
    }

    /*
     * 获取9个频道的视频、图片和文章数据,返回序列化之后的字符串,为空返回null
     *  category_4 : F1
     */
    public static function getChannelsData() {
        $channelsData = array();
        //$channelsData = self::getMutilKeys(array('channel_1_articles', 'channel_1_videoAndImg', 'channel_2_articles', 'channel_2_videoAndImg', 'channel_3_articles', 'channel_3_videoAndImg', 'channel_4_articles', 'channel_4_videoAndImg', 'channel_5_articles', 'channel_5_videoAndImg', 'channel_6_articles', 'channel_6_videoAndImg', 'channel_7_articles', 'channel_7_videoAndImg', 'channel_8_articles', 'channel_8_videoAndImg'), null, Memcached::GET_PRESERVE_ORDER);
        $channelsData[] = self::readTt('channel_1_articles');       //篮球文章
        $channelsData[] = self::readTt('channel_1_videoAndImg');    //篮球视频、图片
        $channelsData[] = self::readTt('channel_2_articles');       //足球文章
        $channelsData[] = self::readTt('channel_2_videoAndImg');    //足球视频、图片
        $channelsData[] = self::readTt('channel_3_articles');       //网球文章
        $channelsData[] = self::readTt('channel_3_videoAndImg');    //网球视频、图片
        $channelsData[] = self::readTt('channel_4_articles');       //F1文章
        $channelsData[] = self::readTt('channel_4_videoAndImg');    //F1视频、图片
        $channelsData[] = self::readTt('channel_5_articles');       //MMA文章
        $channelsData[] = self::readTt('channel_5_videoAndImg');    //MMA视频、图片
        $channelsData[] = self::readTt('channel_6_articles');       //NFL文章
        $channelsData[] = self::readTt('channel_6_videoAndImg');    //NFL视频、图片
        $channelsData[] = self::readTt('channel_7_articles');       //奥运文章
        $channelsData[] = self::readTt('channel_7_videoAndImg');    //奥运视频、图片
        $channelsData[] = true;                                     //花边文章
        $channelsData[] = true;                                     //花边视频、图片
        $channelsData[] = self::readTt('channel_9_articles');       //X-Game文章
        $channelsData[] = self::readTt('channel_9_videoAndImg1');    //X-Game视频、图片
        $channelsData[] = true;                                     //BBS
        $channelsData[] = true;                                     //BBS
        $channelsData[] = self::readTt('channel_11_articles');      //其他运动文章
        $channelsData[] = self::readTt('channel_11_videoAndImg');   //其他运动视频、图片
        if (!$channelsData || ($errorDataKeys = array_keys($channelsData, false, true))) {        //键值为空或获取失败
            foreach ($errorDataKeys as $v) {
                $channelsData[$v] = self::setChannelData($v);
            }
        }        
        return $channelsData;
    }

    /*
     * 设置9个频道的视频、图片和文章数据
     * $key = 0 说明设置 $category_id = 1的频道的文章数据
     * $key = 1 说明设置 $category_id = 1的频道的视频、图片数据， 类推。。。
     */

    public static function setChannelData($key) {
        $category_id = floor($key / 2) + 1;
        return $key % 2 == 0 ? self::setChannelArticles($category_id) : self::setChannelVideoAndImg($category_id);
    }

    /*
     * 设置9个频道的文章数据
     */

    public static function setChannelArticles($category_id) {
        $articles = llArticleTable::getCategoryArticles($category_id);
        if($articles == null ){
            return serialize(array());
        }
        $articles = serialize($articles); 
        if (self::writeTt('channel_' . $category_id . '_articles', $articles)) {
            return $articles;
        }
        return false;
    }

    /*
     * 设置9个频道的视频、图片数据
     */

    public static function setChannelVideoAndImg($category_id) {
        $data = array();
        $data['videos'] = v_videoTable::getInstance()->getVideosByCategory_id($category_id);  
        if($category_id == 11){
            $url = 'http://photo.hupu.com/index.php' . '?controller=api&action=getalbumbymutitag&num=2&tag=' . iconv('utf-8', 'gbk', sfConfig::get('app_category_11'));
        }else{
            $url = 'http://photo.hupu.com/index.php' . '?controller=api&action=getalbumbytag&num=2&tag=' . iconv('utf-8', 'gbk', sfConfig::get('app_category_'.$category_id));
        }
        $data['images'] = common::Curl($url);//剩余小图片
       
//        $data['images'] = json_encode(liangle_photoTable::getInstance()->getPhotoByTagAndNumber(iconv('utf-8', 'gbk', sfConfig::get('app_category_'.$category_id)), 2));//剩余小图片
//        var_dump($data['images']);
        $data = serialize($data);
        if (self::writeTt('channel_' . $category_id . '_videoAndImg', $data, (7200+120*$category_id) )) {
            return $data;
        }
        return false;
    }

    /*
     * $ids = array(1,3)
     */
    public static function clearArticlesCacheByIds($ids){
        if(!is_array($ids) || empty ($ids)){
            return ;
        }
        foreach($ids as $v){
            self::setChannelArticles($v);
        }
    }

    /*
     * 获取投票
     */

    public static function getVote() {
        $vote = self::readTt('vote');
        if (!$vote) {        //键值为空或获取失败
            $vote = self::setCacheVote();
            return $vote === false ? null : $vote;
        }    
        return $vote;
    }

    public static function setCacheVote() {
        $vote = voteTable::getVote();        
        if (self::writeTt('vote', $vote, 85000)) {
            return $vote;
        }
        return false;
    }

    /*
     * In Zone
     * 返回序列化数据
     */

    public static function getInZone() {
        $in_zone = self::readTt('in_zone');
        if (!$in_zone) {        //键值为空或获取失败
            $in_zone = self::setInZone();
            return $in_zone === false ? null : $in_zone;
        }
        return $in_zone;
    }

    /*
     * 设置in_zone
     */

    public static function setInZone() {
        $in_zone = serialize(llArticleTable::getInZone());
        if (self::writeTt('in_zone', $in_zone)) {
            return $in_zone;
        }
        return false;
    }

    /*
     * 亮女郎
     * 返回序列化数据
     */

    public static function getBeauty() {
        $beauty = self::readTt('beauty');
        if (!$beauty) {        //键值为空或获取失败
            $beauty = self::setBeauty();
            return $beauty === false ? null : $beauty;
        }
        return $beauty;
    }

    /*
     * 设置亮女郎
     * others:值为json数据
     */

    public static function setBeauty() {
        $tmp = array();
        $tmp['cover_pic'] = PhotoTable::getCoverPhoto();   //大图片
        $url = 'http://photo.hupu.com/index.php' . '?controller=api&action=getalbumbytag&num=4&tag=' . iconv('utf-8', 'gbk', '美女');
        $tmp['others'] = common::Curl($url);//剩余小图片
        $beauty = serialize($tmp);
        if (self::writeTt('beauty', $beauty, 7000)) {
            return $beauty;
        }
        return false;
    }

    /*
     * 从编辑列表中某个用户
     */
    public static function RemoveUserFromEditingList($type, $index) {
        switch ($type) {
                case 'photo':
                    liangleMemcache::RemoveUserFromPhotoEditingList($index);
                    break;
                case 'news':
                    liangleMemcache::RemoveUserFromNewsEditingList($index);
                    break;
                case 'bbs':
                    liangleMemcache::RemoveUserFromBbsEditingList($type);
                    break;
                default:
                    break;
       }
    }
    public static function RemoveUserFromPhotoEditingList($index) {
        $users = unserialize(liangleMemcache::readTt('photo_'.$index.'_editing_users'));
        if(is_array($users)){
            $key = false;
            foreach ($users as $k => $v){
                if($v['name'] == sfContext::getInstance()->getUser()->getAttribute('username')){
                    $key = $k;
                    break;
                }
            }            
            if($key !== false){
                unset($users[$key]);
                $users = array_merge($users);
                liangleMemcache::writeTt('photo_'.$index.'_editing_users', serialize($users));
            }
        }
        
    }
    public static function RemoveUserFromNewsEditingList($index) {
        $users = unserialize(liangleMemcache::readTt('headerNews_'.$index.'_editing_users'));
        if(is_array($users)){
            $key = false;
            foreach ($users as $k => $v){
                if($v['name'] == sfContext::getInstance()->getUser()->getAttribute('username')){
                    $key = $k;
                    break;
                }
            }            
            if($key !== false){
                unset($users[$key]);
                $users = array_merge($users);
                liangleMemcache::writeTt('headerNews_'.$index.'_editing_users', serialize($users));
            }
        }
        
    }
    public static function RemoveUserFromBbsEditingList() {
        $users = unserialize(liangleMemcache::readTt('bbs_editing_users'));
        if(is_array($users)){
            $key = false;
            foreach ($users as $k => $v){
                if($v['name'] == sfContext::getInstance()->getUser()->getAttribute('username')){
                    $key = $k;
                    break;
                }
            }            
            if($key !== false){
                unset($users[$key]);
                $users = array_merge($users);
                liangleMemcache::writeTt('bbs_editing_users', serialize($users));
            }
        }
        
    }
    /*
     * 灵光一闪
     * 返回序列化数据
     */

    public static function getNote($page) {
        $notes = self::readTt('note_page_' . $page);
        if (!$notes) {        //键值为空或获取失败
            $notes = self::setNotes($page);
            return $notes === false ? null : $notes;
        }
        return $notes;
    }

    /*
     * 设置灵光一闪
     * others:值为json数据
     */

    public static function setNotes($page) {
        $notesHC = common::Curl('http://www.hoopchina.com/special/lingguang/show_html.php?id=' . $page);   //调取HC灵光一闪数据
        $notesGH = index_module_finalTable::getInstance()->getNotes($page);
        $notes = json_encode(self::formate_notes($notesHC, $notesGH));
        if (self::writeTt('note_page_' . $page, $notes, 86000)) {
            return $notes;
        }
        return false;
    }

    private static function formate_notes($notesHC, $notesGH) {
        preg_match_all('/<div.*?class=["\']?reply["\']?.*?>(.*?)<\/div>\s*.*?<a.*?href=["\'](.*?)["\'].*?>(.*?)\s*<\/a><\/div><\/div>/', $notesHC, $arr);
        $tmp = array();
        foreach ($notesGH as $key => $v) {
            $v = stripslashes($v);
            $v = unserialize($v);
            foreach ($v as $k => &$val) {
                $v[$k] = mb_convert_encoding($val, 'utf-8', 'gbk');
            }
            $v['from'] = 'goalhi';
            $tmp[] = $v;
            if (isset($arr[1][$key])) {
                $tmp[] = array('from' => 'hc', 'reply' => $arr[1][$key], 'zhaiyao' => $arr[3][$key], 'link' => $arr[2][$key]);
            }
        }
        return $tmp;
    }
    
    
    
    //约战2期方法：
    
    /*
     * 获取举办过比赛的城市
     * 返回序列化之后的值
     */
    public static function getCities(){
        $cities = self::readTt('cities');
        if(!$cities){
            $cities = self::setCities();
            self::writeTt('cities', $cities);
        }
        return $cities;
    }
    
    public static function setCities(){
        $arr = LocationTable::getAllMatchCities();
        $cities = array();        
        foreach($arr as $v){
            $cities[$v->getId()] = $v->getName();
        }
        $cities = serialize($cities);
        self::writeTt('cities', $cities);
        return $cities;
    }

    public static function setMatchData($data, $uid = 0){
        self::writeTt('match_data_'.$uid, $data);
    }
    
    public static function getMatchData($data, $uid = 0){
        return unserialize(self::readTt('match_data_'.$uid, $data));
    }
    
    
    /*
     * weibo slugs
     */
    public static function getWeiboSlugs(){
        $slugs = self::readTt('weibo_slugs');
        if(!$slugs){
            return unserialize(self::updateWeiboSlugs());
        }
        return unserialize($slugs);
    }
    
    public static function updateWeiboSlugs(){
        $slug1 = twitterTagTable::getAllSlugs();
        $slug2 = twitterUserTable::getAllSlugs();
        $str = serialize($slug1 + $slug2);
        self::writeTt('weibo_slugs', $str);
//        self::updateAPC();
        return $str;
    }
    
    /*
     * 
     */
    public static function getVoiceTopicSlugs(){
        if(!$slugs = self::readTt('voice_topic_slugs')){
            return self::updateVoiceTopicSlugs();
        }
        return unserialize($slugs);
    }
    
    public static function updateVoiceTopicSlugs(){
        $slugs = twitterTopicTable::getAllTopics();
        $slugs = count($slugs) ? $slugs->toArray() : array();
        self::writeTt('voice_topic_slugs', serialize($slugs));
//        self::updateAPC();
        return $slugs;
    }
    
    public static function updateAPC(){
//        $routing_cache = sfConfig::get('sf_cache_dir').'/mobile/'.sfConfig::get('sf_environment').'/config/config_routing.yml.php';
//        if(file_exists($routing_cache)){
//           unlink($routing_cache);
//           apc_delete_file($routing_cache);             
//        }    
//        $routing_cache = sfConfig::get('sf_cache_dir').'/star/'.sfConfig::get('sf_environment').'/config/config_routing.yml.php';
//        if(file_exists($routing_cache)){
//           unlink($routing_cache);
//           apc_delete_file($routing_cache);             
//        }    
    }
    
}

