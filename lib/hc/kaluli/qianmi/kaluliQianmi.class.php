<?php
include("qianmiAPI/OpenSdk.php");
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2017/9/15
 * Time: 下午2:20
 */


class kaluliQianmi {

    private $appId;
    private $appSecret;
    private $redis;

    //用于存放实例化的对象
    static private $_instance;


    private function __construct()
    {

        $this->appId = 10001693;
        $this->appSecret = 'gXnf0bXg3qsa5wbhZkOIJp1wbJxwM1MO';

        //初始化redis服务
        $this->redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $this->redis->select(10);
        $this->initSdk(); //初始化sdk

    }

    private function initSdk() {
        $loader  = new QmLoader;
        $loader  -> autoload_path  = array(CURRENT_FILE_DIR.DS."client");
        $loader  -> init();
        $loader  -> autoload();
    }

    public function getClient() {
        $client  = new OpenClient;
        $client  -> appKey =  $this->appId;
        $client  -> appSecret =  $this->appSecret;
        return $client;
    }

    //公共静态方法获取实例化的对象
    static public function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public  function getAppId()
    {
        return $this->appId;
    }

    public function getAppSecret()
    {
        return $this->appSecret;
    }

    //获取accessToken
    public  function getAccessToken($code){
        $url = "http://oauth.qianmi.com/token";
        $grant_type = "authorization_code";
        $data = Array (
            'client_id'  => $this->appId,
            'code' => $code,
            'grant_type'  => $grant_type
        );
        ksort($data);
        $plain_text="";
        foreach($data as  $key => $value) {
            $plain_text .= $key.$value;
        }
        $plain_text  = $this->appSecret.$plain_text.$this->appSecret;
        $sign = strtoupper(sha1($plain_text));
        $data['sign'] = $sign;
        ksort($data);
        $url_params = "";
        foreach ($data as $key => $value) {
            $url_params .= "&".$key."=".$value;
        }
        $url_params = ltrim($url_params,"&");
        //curl初始化
        $ch = curl_init();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $url_params );
        $return = curl_exec ( $ch );
        //出错检测
        if(curl_errno($ch)){
            $return = "curl error:".curl_errno($ch);
            return false;
        }
        curl_close ( $ch );
        //存储accessToken
        $info = json_decode($return,true);
        if($info['status'] == 1) {
            $this->redis->set("kaluli.qianmi.accessToken",$info['data']['access_token'],$info['data']['expires_in']);
            return $info['data']['access_token'];
        }else {
            return false;
        }

    }
    //获得快递公司
    public static function getQmExpress($type)
    {
        switch ($type) {
            case 1:
                return ['name'=>'申通快递',"id"=>"101485276"];
                break;
            case 2:
                return ['name'=>'顺丰速运',"id"=>"101485277"];
                break;
            case 3:
                return ['name'=>'EMS',"id"=>"101485274"];
                break;
            case 4:
                return ['name'=>'圆通速递',"id"=>"101485280"];
                break;
            case 5:
                return ['name'=>'韵达快递',"id"=>"101485279"];
                break;
            case 6:
                return ['name'=>'中通快递',"id"=>"101485282"];
                break;
            case 7:
                return ['name'=>'天天快递',"id"=>"101485278"];
                break;
            case 8:
                return ['name'=>'百世快递',"id"=>"101485275"];
                break;
            case 9:
                return ['name'=>'宅急送',"id"=>"101485283"];
                break;
            case 33:
                return ['name'=>'速尔',"id"=>"101565434"];
                break;
            default:
                return [];
                break;
        }
    }



}