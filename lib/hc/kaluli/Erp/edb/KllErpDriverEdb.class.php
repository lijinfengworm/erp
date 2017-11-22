<?php
/**
 * 卡路里 ERP 驱动  E店宝
 * User: liangtian
 * Date: 15/12/28
 * Time: 下午2:33
 */
class  KllErpDriverEdb {

    /** API接口地址 */
    private $server;
    /** 软件注册用户，比如edb_aXXXXX（接口调用的唯一标识），用户的主帐号 */
    private $dbhost;
    /** 公钥，你申请的appkey， 以标识来源 */
    private $appkey;
    /** 返回值类型：json、xml,默认XML */
    private $format;
    /** 方法名，例:edbStockTradeadd */
    private $method;
    /** 时间戳 到分钟，如（201307251321） */
    private $timestamp;
    /** 版本号(1.0或者2.0) */
    private $v;
    /** 返回结果加密方式(0:表示不加密，1:表示加密) */
    private $slencry;
    /** 本机的外网IP地址 */
    private $Ip;
    /** 作为请求url中的最后一个参数，做权限认证用，是md5算法产生的结果值 */
    private $sign;
    /** appscret */
    private $appscret;
    /** token */
    private $token;

    /** config 配置信息 */
    public static  $config = array(
        'server'=>'http://vip532.edb05.com/rest/index.aspx',
        'dbhost'=>'edb_a84292',
        'appkey'=>'7d4aba5b',
        'format'=>'JSON',
        'appscret'=>'ac9e91e7622e4656834f10a94e56de8c',
        'token'=>'9fd1ac5bc22e4604841291b063366d0a',
        'slencry'=>'0',
        'v'=>'2.0'
    );

    public function __construct( array $config = array() ){
        date_default_timezone_set('PRC');
        $this->server    = !empty($config['server']) ? $config['server'] : self::$config['server'];
        $this->appkey    = !empty($config['appkey']) ? $config['appkey'] : self::$config['appkey'];
        $this->appscret  = !empty($config['appscret']) ? $config['appscret'] : self::$config['appscret'];
        $this->token     = !empty($config['token']) ? $config['token'] : self::$config['token'];
        $this->dbhost    = !empty($config['dbhost']) ? $config['dbhost'] : self::$config['dbhost'];
        $this->format    = !empty($config['format']) ? $config['format'] : self::$config['format'];
        //$this->timestamp = !empty($config['timestamp']) ? $config['timestamp'] : date( 'YmdHi', isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() );
        $this->timestamp = date('YmdHi',time());
        $this->v         = !empty($config['v']) ? $config['v'] : self::$config['v'];
        $this->slencry   = !empty($config['slencry']) ? $config['slencry'] : self::$config['slencry'];
        $this->Ip        = !empty($config['ip']) ? $config['ip'] : KaluliFun::get_client_ip();

    }


    public function setConfig($key= '',$var = '') {
        if(empty($key)) return false;
        $this->$key = $var;
    }


    /**
     * 切换到沙盒模式
     * @param array $config
     */
    public function sandboxEnv( array $config = array() ){
        $this->server   = !empty($config['server']) ? $config['server'] : 'http://vip802.6x86.com/edb2/rest/index.aspx';
        $this->appkey   = !empty($config['appkey']) ? $config['appkey'] : '6f55e36b';
        $this->appscret = !empty($config['appscret']) ? $config['appscret'] : 'adeaac8b252e4ed6a564cdcb1a064082';
        $this->token    = !empty($config['token']) ? $config['token'] : 'a266066b633c429890bf4df1690789a3';
        $this->dbhost   = !empty($config['dbhost']) ? $config['dbhost'] : 'edb_a88888';
    }


    /**
     * 构造一个 api
     * @param $api_name
     * @param $options
     * @return mixed
     * @throws sfException
     */
    public function builder($api_name = '',$options = array()) {
        if(empty($api_name)) throw new sfException('api名称不得为空！');
        if (class_exists($api_name)) {
            $this->method = $api_name;
            return new $api_name($options);
        } else {
            throw new sfException('API：'.$api_name.' 不存在！');
        }
    }


    public function exec($apiOjb = null,$method = 'get') {
        if(empty($apiOjb)) throw new sfException('未知的api!');
        $args = array_merge( get_object_vars( $this ), get_object_vars($apiOjb) );


        // 清理不必要的值（接口地址、空值）
        unset( $args['server'] );
        foreach ( $args as $key => $val ) {
            if ( $val === '' or is_null( $val ) ) {
                unset( $args[$key] );
            }
        }

        $args['sign'] = $this->_getSign( $args );

        unset($args['appscret']);
        unset($args['token']);
        //如果是get模式发送
        if($method == 'get') {
            $this->server = $this->server . ( stripos( $this->server, '?' ) ? '&' : '?' );
            $temp = array();
            foreach ( $args as $key => $val ) {
                if($key!="xmlValues"){
                    $temp[] = $key . '=' . urlencode( $val );
                }else{
                    $temp[] = $key . '=' . urlencode($val);
                }
            }
            $this->server .= implode( '&', $temp );
            $result=self::curl_post($this->server);
        } else {
            $result=self::curl_post($this->server,$args);
        }
        return array('result' => $result );
    }


    private function _getSign( $query ){
        $query = array_flip( $query );
        natcasesort( $query );
        $query = array_flip( $query );
        $str = $this->appkey;
        foreach ( $query as $key => $val ) {
            $str .= $key . $val;
        }
       // kaluliLog::info("str",array("str" =>$str));
        return strtoupper(md5( $str ));
    }

    public function curl_post($url,$data=""){
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output=curl_exec($ch);
        curl_close($ch);
        return $output;
    }





}
