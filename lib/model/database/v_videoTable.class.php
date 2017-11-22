<?php

class v_videoTable {

    protected $db_connection;
    protected static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new v_videoTable();
            self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('v_liangle');
        }

        return self::$instance;
    }

    private static function query($sql) {
        return mysql_query($sql, self::$instance->db_connection);
    }

    public static function getSystemRecommendVideos() {
        $rs = self::query('select v.vid, v.title, v.localcover from v_sysrecommend r left join v_video v on r.vid = v.vid where r.isfocus =0 order by r.recdate desc limit 8');
        if (!$rs) {
            return null;
        } else {
            $tmp = array();
            while ($row = mysql_fetch_assoc($rs)) {
                $tmp[] = $row;
            }
            return $tmp;
        }
    }

    
    public static function getVideosByCategory_id($category_id) {
        $rs = self::query('select id from v_tagsort where linkname = "' . sfConfig::get('app_linkname_' . $category_id) . '" limit 1');
        if (!$rs) {
            return null;
        }
        $r = mysql_fetch_array($rs);
        $rs = self::query('SELECT v.vid,v.title,v.dateline,v.hits as playtime,v.localcover FROM v_sorttag AS st, v_videotag AS vt, v_tags AS t, v_video AS v LEFT JOIN v_matchvideo as m USING(vid) WHERE m.vid IS NULL AND st.sid="' . $r['id'] . '" AND st.tid=t.tid AND t.tagname=vt.tagname AND v.display=1 AND v.vid=vt.vid GROUP BY v.vid order by v.dateline desc limit ' . sfConfig::get('app_category_' . $category_id . '_videos_num'));
        if (!$rs) {
            return null;
        } else {
            $tmp = array();
            while ($row = mysql_fetch_assoc($rs)) {
                $tmp[] = $row;
            }
            return $tmp;
        }
    }

}
