<?php
/*
 * hoopchina首页新闻、帖子调取
 */

class hoop_index_ylTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new hoop_index_ylTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoop_bballs');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }

    /*
     * 获取首页调用的新闻或帖子
     * 左侧焦点图中前两个链接
     * 且链接为bbs或new的内容
     */

    public static function getIndexNews() {
        $rs = self::query('select id, href, title from hoop_bballs.hoop_index_yl where id IN (\'photo_1\', \'photo_2\') and (href like \'%bbs%\' or href like \'%news%\')');
        if (!$rs) {
            return false;
        } else {
            $arr = array();
            $newsid = '';
            $order = array();
            while ($row = mysql_fetch_assoc($rs)) {  
                if(preg_match('/http:\/\/news\.hoopchina\.com\/\d*\/(\d*)\.html/', $row['href'], $match) || preg_match('/nba\.hupu\.com\/news\/\d*\/(\d*)\.html/', $row['href'], $match)){
                    $newsid .= $newsid == '' ? $match[1] : ', '.$match[1];
                    $order[$row['id']] = $match[1];
                }else{
                    $arr[$row['id']] = $row;
                }
            }
            $news = hc_newsTable::getInstance()->getNewsByIds($newsid);
            foreach($news as $key => $v){
                foreach ($order as $k => $val){
                    if($v['href'] == $val){
                        $order[$k] = $v;
                    }
                }
            }
            $arr = array_merge($arr, $order);           
            return self::order($arr);
        }
    }

    /*
     * 排序
     * 规则：
     */

    private static function order($news) {
//        $order_arr = array('news_imp_0' => '', 'news_imp_11' => '', 'news_imp_1' => '', 'news_imp_2' => '', 'news_imp_5' => '', 'news_imp_6' => '', 'news_imp1_0' => '', 'news_imp1_3' => '', 'news_imp1_1' => '', 'news_imp1_2' => '', 'photo_1' => '', 'photo_2' => '');
        $order_arr = array('photo_1' => '', 'photo_2' => '');
        foreach ($news as $k => $v) {
            $v['title'] = str_replace(array('#FF0000', '#f60', '#666', '000000'), array('', '', '', ''), $v['title']);
            $v['title'] = preg_replace('/&lt;.*?&gt;/i', '', $v['title']);
            $order_arr[$k] = $v;
        }
        return $order_arr;
    }
    /*
     * 获得除新闻留言、虎扑制造外(篮球论坛、话题区、步行街)三个栏目的帖子
     * $channels: array 各栏目的名字及需要返回的条数
     * $channels = array('kb'=>1, 'heat'=>2);
     * 返回数组 or null
     * 如果返回条数是1，默认作为篮球论坛的一个帖子返回，否则，单独返回
     * 对于话题区title字段存放帖子id,href字段存放帖子标题 （无比纠结蛋疼中 = =!）
     */
    public static function getChannelsInfo($channels){
        if(is_array($channels) && !empty ($channels)){
            $where = '';
            foreach($channels as $k => $v){
                $where .= $where == '' ? 'id like "'.$k.'%"' : ' or id like "' .$k.'%"';
            }            
            $rs = self::query('select id, href, title from hoop_bballs.hoop_index_yl where '. $where .' order by id');
            if(!$rs){
                return null;
            }else{
                $data = array();
                $tids = array();
                while($row = mysql_fetch_assoc($rs)){
                    preg_match('/(.*?)_(\d{1,2})/', $row['id'], $match);
                    if(in_array($match[1], array_keys($channels))){
                        if($channels[$match[1]] == 1){     //篮球论坛
                            !isset($data['basketball'][$match[1]]) && $data['basketball'][$match[1]] = $row;                           
                        }else{
                            if(!isset($data[$match[1]]) || count($data[$match[1]]) < $channels[$match[1]] ){
                                if($match[1] == 'hot'){
                                    $data[$match[1]][] = $row;
                                    $tids[$match[1]][] = $row['title'];
                                }else{
                                    preg_match('/.*?(\d*)-?(\d*)\.html/i', $row['href'], $arr);
                                    $tids[$match[1]][] = $arr[1];
                                    $data[$match[1]][] = $row;
                                }
                            }
                        }
                    }
                }
                return $data;
            }
        }else{
            return null;
        }
    }

    
    public function getDataWithIds($ids){
        if(empty($ids)) return array();
        $where = '';
        foreach($ids as $v){
                $where .= $where == '' ? '"'.$v.'"' : ', "'.$v.'"';
        } 
        $rs = self::query('select id, href, title from hoop_bballs.hoop_index_yl where id in ('. $where .')');
        $data = array();
        while($row = mysql_fetch_assoc($rs)){
            $data[strtolower($row['id'])] = $row;
        }
        return $data;
    }
    
    public function getHuaTi($num){
        $rs = self::query('select id, href, title from hoop_bballs.hoop_index_yl where id like "hot%" order by id');
        $data = array();
        while($row = mysql_fetch_assoc($rs)){
            if(count($data) < $num){
                $tmp = array();
                $tmp['id'] = $row['title'];
                $tmp['title'] = $row['href'];
                $data[] = $tmp;
            }            
        }
        return $data;
    }

}
