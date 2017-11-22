<?php

/*
 * 新闻
 */

class gh_newsTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new gh_newsTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('gh_news');
            mysql_query('SET character_set_client = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_results = latin1;', self::$instance->db_connection);
            mysql_query('SET character_set_connection = UTF8;', self::$instance->db_connection);
        }
        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }


	public static function getTopNews()
	{
		//查询出置顶的最新一条
		$sql = "SELECT article_title,article_id FROM hoop_news WHERE article_tag LIKE '%top%' AND is_delete = '2' ORDER BY article_id DESC limit 10";
		$topNews =  mysql_fetch_object(self::query($sql));
		return $topNews ? $topNews : null;
	}
    public static function getNewsListByTag($tag, $page = 1, $top_news_id = 0) {
        if ($tag === 'all') {
            return self::getNewsWithoutTag($page, $top_news_id);
        }
        $tags = sfConfig::get('app_news_tag_alias');
        if (!isset($tags[$tag])) {
            return self::getNewsWithoutTag($page, $top_news_id);
        }
        return self::getNewsByTag($tags[$tag], $page, $top_news_id);
    }

    public static function getNewsWithoutTag($page, $top_news_id) {
        if($top_news_id != 0) {
            $condition = " and article_id != $top_news_id";
        } else {
            $condition = "";
        }

        $rs = self::query('select article_id, article_title, article_short_title from hoop_news where is_delete = 2'.$condition.' order by article_date desc limit ' . ($page - 1) * sfConfig::get('app_news_page_size') . ', ' . sfConfig::get('app_news_page_size'));
        if (!$rs) {
            return null;
        }
        $id_arr = array();
        while ($r = mysql_fetch_assoc($rs)) {
            if (!isset($r['article_short_title']) || empty($r['article_short_title'])) {
                $id_arr[] = array('article_id' => $r['article_id'], 'article_title' => $r['article_title']);
            } else {
                $id_arr[] = array('article_id' => $r['article_id'], 'article_title' => $r['article_short_title']);
            }
        }
        return $id_arr;
    }

    public static function getNewsByTag($tag, $page, $top_news_id) {
        if($top_news_id != 0) {
            $condition = " and article_id != $top_news_id";
        } else {
            $condition = "";
        }

        if($tag == "zhongda") {
            $sql = 'SELECT is_delete, n.article_id, n.article_title, n.article_short_title FROM hoop_news as n  where priority = 1 and is_delete=2 '.$condition.' order by n.article_date desc limit ' . ($page - 1) * sfConfig::get('app_news_page_size') . ', ' . sfConfig::get('app_news_page_size');
        } else {
            $tag = mb_convert_encoding($tag, 'gbk', 'utf-8');
            $sql = 'SELECT is_delete, n.article_id, n.article_title, n.article_short_title FROM hoop_tag_new_mapping as m left join hoop_news as n on m.new_id = n.article_id where m.tag_name = "' . $tag. '" and is_delete=2 '.$condition.' order by n.article_date desc limit ' . ($page - 1) * sfConfig::get('app_news_page_size') . ', ' . sfConfig::get('app_news_page_size');
        }

        $rs = self::query($sql);
        if (!$rs) {
            return null;
        }
        $id_arr = array();
        while ($r = mysql_fetch_assoc($rs)) {
            if (!isset($r['article_short_title']) || empty($r['article_short_title'])) {
                $id_arr[] = array('article_id' => $r['article_id'], 'article_title' => $r['article_title']);
            } else {
                $id_arr[] = array('article_id' => $r['article_id'], 'article_title' => $r['article_short_title']);
            }
        }
        return $id_arr;
    }

    public static function getTotalPageByTag($t) {
        $tags = sfConfig::get('app_news_tag_alias');
        if (isset($tags[$t])) {
            $tag = mb_convert_encoding($tags[$t], 'gbk', 'utf-8');
        }
        if (isset($tag)) {
            if($tag == "zhongda") {
                $sql = 'SELECT count(*) as num FROM hoop_news as n where priority = 1 and is_delete=2 order by n.article_date';
            } else {
                $sql = 'SELECT count(*) as num FROM hoop_tag_new_mapping as m left join hoop_news as n on m.new_id = n.article_id where m.tag_name = "' . $tag . '"';
            }
        } else {
            return sfConfig::get('app_news_max_pages');
        }
        $rs = self::query($sql);
        $rs = mysql_fetch_assoc($rs);
        return ceil($rs['num'] / sfConfig::get('app_news_page_size'));
    }

    public static function getNewsById($id) {
        $rs = self::query('select article_content, article_title,article_short_title, forum_link, article_short_title, edit_date, article_date from hoop_news where article_id = ' . $id);
        if (!$rs) {
            return null;
        }
        return mysql_fetch_assoc($rs);
    }

    /*
     * $ids: 1,3,4,6
     */

    public static function getNewsByIds($ids) {
        $rs = self::query('select article_id, article_title, article_short_title from hoop_news where article_id in (' . $ids . ')');
        if (!$rs) {
            return array();
        }
        $arr = array();
        while ($row = mysql_fetch_assoc($rs)) {
            $title = (isset($row['article_short_title']) && empty($row['article_short_title'])) ? $row['article_short_title'] : $row['article_title'];
            $arr[] = array('href' => $row['article_id'], 'title' => $title);
        }
        return $arr;
    }

    public static function getNextNews($time) {
        $rs = self::query('select article_id, article_title, article_short_title from hoop_news where article_date > "' . $time . '" limit 1');
        if (!$rs) {
            return null;
        }
        $row = mysql_fetch_assoc($rs);
        if (!isset($row['article_short_title']) || empty($row['article_short_title'])) {
            return array('article_id' => $row['article_id'], 'article_title' => $row['article_title']);
        } else {
            return array('article_id' => $row['article_id'], 'article_title' => $row['article_short_title']);
        }
    }

    public static function getHotNews($tid = 0) {
        $time = date('Y-m-d H:i:s', time() - (24 * 3600 * sfConfig::get('app_hot_news_within_days')));        
        $sql = "select article_id,article_title,article_date from hoop_news where article_date>='$time' and is_delete = '2' order by article_hots desc limit 5";
        $rs = self::query($sql);
        if (!$rs) {
            return null;
        }
        $arr = array();
        while ($row = mysql_fetch_assoc($rs)) {
            if (!isset($row['article_short_title']) || empty($row['article_short_title'])) {
                $arr[] = array('article_id' => $row['article_id'], 'article_title' => $row['article_title']);
            } else {
                $arr[] = array('article_id' => $row['article_id'], 'article_title' => $row['article_short_title']);
            }
        }
        return $arr;
    }

}
