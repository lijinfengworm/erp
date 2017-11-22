<?php

    /*
     * 该表用来统计每天不同操作的数量
     */

    class countsTable {

        protected $db_connection;
        protected static $instance;
        protected static $today;

        public static function getInstance() {
            if (!isset(self::$instance)) {
                self::$instance = new countsTable();
                self::$instance->db_connection = sfContext::getInstance()->getDatabaseConnection('hoopchina');
                self::$instance->db_connection->query('SET character_set_client = latin1;', self::$instance->db_connection);
                self::$instance->db_connection->query('SET character_set_results = latin1;', self::$instance->db_connection);
                self::$instance->db_connection->query('SET character_set_connection = UTF8;', self::$instance->db_connection);
            }
            return self::$instance;
        }

        public static function query($sql) {
            return self::$instance->db_connection->query($sql, self::$instance->db_connection);
        }

        /*
         * 今天该操作是否已有记录
         */
        public static function hasLoged($type, $today){
            $rs = self::query("SELECT count(id) as num FROM counts where type='$type' and time='$today'");
            $rs = mysql_fetch_assoc($rs);
            if($rs['num']){
                return true;
            }
            return false;
        }
        
        public static function updateCounts($type){
            $today = date('Y-m-d');
            if(self::hasLoged($type, $today)){
                self::query("update counts set nums=nums+1 where type='$type' and time='$today'");
            }else{
                self::query("insert into counts(type,time,nums) values('$type','$today','1')");
            }
        }
    }

