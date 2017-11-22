<?php

class hcRepMysqlDatabase {
    const MODE_READ=1;
    const MODE_WRITE=2;
    private $_conn;

    function __construct($master, $slave, $role) {
        $this->db_config[self::MODE_READ] = $slave;
        $this->db_config[self::MODE_WRITE] = $master;
    }

    private function getModeBySQL($sql) {
        switch ($sql) {
            case 'sel':
                return self::MODE_READ;
                break;
            case 'upd':
                return self::MODE_WRITE;
                break;
            case 'ins':
                return self::MODE_WRITE;
                break;
            case 'del':
                return self::MODE_WRITE;
                break;
            case 'set':
                return self::MODE_READ;
                break;
            default:
                throw new sfDatabaseException(sprintf('[hcRepMysqlDatabase]Unknow Mode "%s".', $sql));
                break;
        }
    }

    public function query($sql) {
        $mode = $this->getModeBySQL(strtolower(substr($sql, 0, 3)));
        return mysql_query($sql, $this->getConnectionByMode($mode));
    }

    public function getConnectionByMode($mode) {
        if (!isset($this->_conn[$mode])) {
            $this->_conn[$mode] = mysql_connect($this->db_config[$mode][0]['host'], $this->db_config[$mode][0]['username'], $this->db_config[$mode][0]['password']);
            mysql_select_db($this->db_config[$mode][0]['database'], $this->_conn[$mode]);
        }
        return $this->_conn[$mode];
    }

}

?>
