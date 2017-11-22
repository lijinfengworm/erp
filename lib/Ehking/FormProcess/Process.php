<?php
/**
 *
 *
 * PHP Version 5
 *
 * @category  Class
 * @file      Process.php
 * @package Ehking/FormProcess
 * @author    chao.ma <chao.ma@ehking.com>

 */

//namespace Ehking/FormProcess;


//use Ehking/Configuration/ConfigurationUtils;
//use Ehking/Entity/AbstractModel;
//use Ehking/Excation/HmacVerifyException;
//use Ehking/Excation/InvalidRequestException;
//use Ehking/Excation/InvalidResponseException;
//use Ehking/ResponseHandle/ResponseTypeHandle;
$dir=dirname(__FILE__);
require_once $dir.'/../Configuration/ConfigurationUtils.php';
require_once $dir.'/../Entity/AbstractModel.php';
require_once $dir.'/../Excation/HmacVerifyException.php';
require_once $dir.'/../Excation/InvalidRequestException.php';
require_once $dir.'/../Excation/InvalidResponseException.php';
require_once $dir.'/../ResponseHandle/ResponseTypeHandle.php';


abstract class Process {

    const STATUS = 'status';
    const SUCCESS = 'SUCCESS';
    const FAILED = 'FAILED';
    const CANCEL = 'CANCEL';
    const INIT = 'INIT';
    const ERROR = 'ERROR';
    const REDIRECT = 'REDIRECT';

    private $response_hmac_fields = array();

    public abstract function builder($params);

    public function setHmacFields($fields)
    {
        $this->response_hmac_fields = $fields;
    }
    /**
     * hmac 验证
     * @return mixed
     */
    public function checkHmac($data)
    {
        $hmacSource = '';
        foreach($this->response_hmac_fields as $key)
        {
            $d = isset($data[$key])?$data[$key]:'';
            $k = $key;
            if (strpos($key, '.') !== false){

                list($i, $v) = explode('.',$key);
                $d = isset($data[$i][$v])?$data[$i][$v]:'';
            }

            if ($k == 'listprice'){
                $hmacSource .= $d ? number_format($d, 6, '.', '0') : '';
            }else{
                $hmacSource .= $d;
            }
        }

        if (!empty($hmacSource)){

            if (empty($data['hmac'])){
                throw new HmacVerifyException(array(
                    'error_description'=>'hmac validation error',
                    'responseData' => $data
                ));
            }
            $sourceHmac = hash_hmac('md5', $hmacSource, ConfigurationUtils::getInstance()->getHmacKey(isset($data['merchantId'])?$data['merchantId']:''));
            $hmac = $data['hmac'];
            if ($sourceHmac !== $hmac){
                throw new HmacVerifyException(array(
                    'error_description'=>'hmac validation error'
                ));
            }
        }
    }

    public function execute($url, $param, ResponseTypeHandle $handle=null)
    {
        $data = $this->httpRequestPost($url, $param);
        if($handle !== null && $handle instanceof ResponseTypeHandle){
            $handle->handle($data);
        }
        return $data;
    }
    public function httpRequestPost($url, $param)
    {
        $curl = curl_init($this->absoluteUrl($url));
        curl_setopt($curl,CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_HTTPHEADER,array(
            'Content-Type: application/vnd.ehking-v1.0+json'
        ));
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl,CURLOPT_POST,true); // post传输数据
        curl_setopt($curl,CURLOPT_POSTFIELDS, $param);// post传输数据
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证

        $responseText = curl_exec($curl);

        if (curl_errno($curl) || $responseText === false) {
            curl_close($curl);
            throw new InvalidRequestException(array(
                'error_description'=> 'Request Error'
            ));
        }
        curl_close($curl);

        $data = json_decode($responseText, true);

        if( $data === false){
            throw new InvalidResponseException(array(
                'error_description'=>'Response Error'
            ));
        }
        $this->checkHmac($data);
        return $data;
    }


    /**
     *
     * @return string
     */
    protected function buildJson($para=null)
    {
        $vars = $para?'':get_object_vars($this);
        unset($vars['response_hmac_fields']);
        $data = array();
        foreach($vars as $k=>$var){
            if(is_scalar($var) && $var !== '' && $var !== null){
                $data[$k] = $var;
            }else if(is_object($var) && $var instanceof AbstractModel){
                $data[$k] = array_filter((array) $var);
            }else if(is_array($var)){
                $data[$k] = array_filter($var);
            }

            if(empty($data[$k])){
                unset($data[$k]);
            }
        }
        $data['hmac'] = $this->generateHmac();

        return json_encode($data);
    }

    private function absoluteUrl($url, $param=null)
    {
        if ($param !== null) {
            $parse = parse_url($url);

            $port = '';
            if ( ($parse['scheme'] == 'http') && ( empty($parse['port']) || $parse['port'] == 80) ){
                $port = '';
            }else{
                $port = $parse['port'];
            }
            $url = $parse['scheme'].'//'.$parse['host'].$port. $parse['path'];

            if(!empty($parse['query'])){
                parse_str($parse['query'], $output);
                $param = array_merge($output, $param);
            }
            $url .= '?'. http_build_query($param);
        }

        return $url;
    }
    /**
     * 生成认证串
     * @return mixed
     */
    abstract function generateHmac();

    /**
     * 加密数据
     * @param $data
     * @param $key
     * @return string
     */
    public function encipher($data, $key)
    {
        return hash_hmac("md5", $data, $key);
    }
} 