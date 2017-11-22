<?php

/*
 * 新闻
 */

class hc_newsTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new hc_newsTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hc_news');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }

    public static function getNewsListByTag($tag, $page = 1) {
        if ($tag === 'all') {
            return self::getNewsWithoutTag($page);
        }
        $tags = sfConfig::get('app_news_tag_relation');
        if (!isset($tags[$tag])) {
            return self::getNewsWithoutTag($page);
        }
        return self::getNewsByTag($tags[$tag], $page);
    }

    public static function getNewsWithoutTag($page) {
        $rs = self::query('select article_id,article_title,article_caption from hoop_news where is_delete = 2 and article_tag !="'. mb_convert_encoding('地方站', 'gbk', 'utf-8').'" and article_tag !="'. mb_convert_encoding('运动装备,', 'gbk', 'utf-8').'" order by article_date desc limit ' . ($page - 1) * sfConfig::get('app_news_page_size') . ', ' . sfConfig::get('app_news_page_size'));
        if (!$rs) {
            return null;
        }
        $id_arr = array();
        while ($r = mysql_fetch_assoc($rs)) {
            if (!$r['article_caption'] || $r['article_caption'] == 'undefined') {
                $id_arr[] = array('article_id' => $r['article_id'], 'article_title' => $r['article_title']);
            } else {
                $id_arr[] = array('article_id' => $r['article_id'], 'article_title' => $r['article_caption']);
            }
        }
        return $id_arr;
    }

    public static function getNewsByTag($tag, $page) {
        $tag = mb_convert_encoding($tag, 'gbk', 'utf-8');
        $sql = 'SELECT  n.article_id, n.article_title, n.article_caption FROM hoop_tag_new_mapping as m left join hoop_news as n on m.new_id = n.article_id where m.tag_name = "' . $tag . '" and n.is_delete = 2 and n.article_tag !="'. mb_convert_encoding('地方站', 'gbk', 'utf-8').'" and  n.article_tag !="'. mb_convert_encoding('运动装备,', 'gbk', 'utf-8').'" order by n.article_date desc limit ' . ($page - 1) * sfConfig::get('app_news_page_size') . ', ' . sfConfig::get('app_news_page_size');
        $rs = self::query($sql);
        if (!$rs) {
            return null;
        }
        $id_arr = array();
        while ($r = mysql_fetch_assoc($rs)) {
            if (!$r['article_caption'] || $r['article_caption'] == 'undefined') {
                $id_arr[] = array('article_id' => $r['article_id'], 'article_title' => $r['article_title']);
            } else {
                $id_arr[] = array('article_id' => $r['article_id'], 'article_title' => $r['article_caption']);
            }
        }
        return $id_arr;
    }

    public static function getTotalPageByTag($t) {
        $tags = sfConfig::get('app_news_tag_relation');
        if (isset($tags[$t])) {
            $tag = mb_convert_encoding($tags[$t], 'gbk', 'utf-8');
        }
        if (isset($tag)) {
            $sql = 'SELECT count(*) as num FROM hoop_tag_new_mapping as m left join hoop_news as n on m.new_id = n.article_id where m.tag_name = "' . $tag . '" and n.is_delete = 2 and n.article_tag != "'. mb_convert_encoding('地方站', 'gbk', 'utf-8').'" and n.article_tag !="'. mb_convert_encoding('运动装备,', 'gbk', 'utf-8').'"';
        } else {
            return sfConfig::get('app_news_max_pages');
        }
        $rs = self::query($sql);
        $rs = mysql_fetch_assoc($rs);
        return ceil($rs['num'] / sfConfig::get('app_news_page_size'));
    }

    public static function getNewsById($id) {
        $rs = self::query('select article_content, article_title, article_caption, edit_date, article_date, forum_link from hoop_news where is_delete = 2 and article_id = ' . $id);
        if (!$rs) {
            return null;
        }
        return mysql_fetch_assoc($rs);
    }

    /*
     * $ids: 1,3,4,6
     */

    public static function getNewsByIds($ids) {
        $rs = self::query('select article_id, article_title, article_caption from hoop_news where is_delete = 2 and article_id in (' . $ids . ')');
        if (!$rs) {
            return array();
        }
        $arr = array();
        while ($row = mysql_fetch_assoc($rs)) {
            $title = ($row['article_caption'] && $row['article_caption'] != 'undefined') ? $row['article_caption'] : $row['article_title'];
            $arr[] = array('href' => $row['article_id'], 'title' => $title);
        }
        return $arr;
    }

    public static function getNextNews($time) {
		$rs = self::query('select article_id, article_title, article_caption from hoop_news where is_delete = 2 and article_tag !="'. mb_convert_encoding('地方站', 'gbk', 'utf-8').'"  and article_tag !="'. mb_convert_encoding('运动装备,', 'gbk', 'utf-8').'" and article_date > "' . $time . '" limit 1');
        if (!$rs) {
            return null;
        }
		$row = mysql_fetch_assoc($rs);
		if($row==null){
			return null;
		}elseif (!$row['article_caption'] || $row['article_caption'] == 'undefined') {
            return array('article_id' => $row['article_id'], 'article_title' => $row['article_title']);
        } else {
            return array('article_id' => $row['article_id'], 'article_title' => $row['article_caption']);
        }
    }

    public static function getHotNews($tid = 0) {
        $time = date('Y-m-d H:i:s', time() - (24 * 3600 * sfConfig::get('app_hot_news_within_days')));
        $rs = self::query('select article_id, article_title, article_caption from hoop_news where is_delete = 2 and article_tag !="'. mb_convert_encoding('地方站', 'gbk', 'utf-8').'" and article_tag !="'. mb_convert_encoding('运动装备,', 'gbk', 'utf-8').'" and article_date > "' . $time . '" order by hits desc limit ' . sfConfig::get('app_hot_news_num'));
        if (!$rs) {
            return null;
        }
        $arr = array();
        while ($row = mysql_fetch_assoc($rs)) {
            if (!$row['article_caption'] || $row['article_caption'] == 'undefined') {
                $arr[] = array('article_id' => $row['article_id'], 'article_title' => $row['article_title']);
            } else {
                $arr[] = array('article_id' => $row['article_id'], 'article_title' => $row['article_caption']);
            }
            
        }
        return $arr;
    }

    /*
     * 特亮新闻
     */
    public static function getHeaderNews(){
        $rs = self::query('select article_id, article_title, article_caption from hoop_news where priority = 2 and  is_delete = 2 and article_tag !="运动装备," order by article_date desc limit 5');
        if (!$rs) {
            return array();
        }
        $arr = array();
        while($row = mysql_fetch_assoc($rs)){
            if (!$row['article_caption'] || $row['article_caption'] == 'undefined') {
                $arr[] = array('article_id' => $row['article_id'], 'article_title' => $row['article_title']);
            } else {
                $arr[] = array('article_id' => $row['article_id'], 'article_title' => $row['article_caption']);
            }
        }
        return $arr;
        
    }
}
