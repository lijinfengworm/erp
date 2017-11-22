<?php

/**
 * 虎扑社区接口SDK
 * 虎扑社区常用接口列表：http://redmine.hoopchina.com/projects/bbs-my-snsopt/documents
 * @author hopes@2011-11-28
 */
class SnsInterface
{
    /**
     * 社区接口网址
     */
    const INTERFACE_URL = 'http://interface.hupu.com/';

    /**
     * 常见错误编码对应的错误信息 (更多错误信息请参数接口说明文档)
     * @var array 
     */
    static private $errors = array(
        '-1' => '验证参数有误',
        '-2' => 'Appid不存在或appid被串用',
        '-3' => 'Appid已被停用',
        '-4' => '签名不正确',
        '-11' => '接口传入参数不完整或不正确',
        '-12' => '数据为空',
    );

    /**
     * 拼接接口参数并访问接口返回其内容
     * @param string $apiname   接口名称
     * @param int $appid    应用ID
     * @param string $key   应用KEY
     * @param array $arrays 接口参数
     * @param string $method    接口访问方式 (GET or POST)
     * @param int $timeout  接口访问超时时间 (秒)
     * @return array
     */
    static public function getContents($apiname, $appid, $key, array $arrays, $method = 'GET', $timeout = 3)
    {
        $result = NULL;

        // 检测方法传入参数是否完整
        if(!empty($apiname) && !empty($appid) && !empty($key))
        {
            $method = strtoupper($method);
            $method != 'GET' && $method == 'POST';
            
            $time = time();

            // 基本参数
            $baseArray = array(
                'appid' => $appid,
                'time' => $time,
                'sign' => md5(md5($appid) . $time . $key),
            );

            // file_get_contents 参数
            $options = array(
                'http' => array(
                    'method' => $method,
                    'timeout' => $timeout,
                ),
            );

            $url = self::INTERFACE_URL . $apiname . '?' . http_build_query($baseArray, NULL, '&');

            if($method == 'GET')
            {
                $url .= '&' . http_build_query($arrays, NULL, '&');
            }
            else
            {
                if(isset($arrays['a'])) $url = $url.'&a='.$arrays['a'];
                $options['http']['content'] = http_build_query($arrays, NULL, '&');
                $options['http']['header'] = "Content-type: application/x-www-form-urlencoded ";
            }

            // 访问接口网址
             $results = file_get_contents($url, false, stream_context_create($options));

            // 处理接口返回内容
            if(!empty($results) && !is_numeric($results))
            {
                $result = @unserialize($results);

                $result === FALSE && $result = $results;
            }
            // 返回内容为字符串，但其本身是数字，则转为整型
            is_numeric($results) && $result = intval($results);
        }
        else
        {
            // 参数不完整
            echo '方法getContents所需参数不完整';
        }
        return $result;
    }

    /**
     * 通过错误ID获取错误信息
     * @param int $errorid  错误信息编号 (一般均为负数)
     * @return string 
     */
    static public function getErrorInfo($errorid)
    {
        return !empty(self::$errors[$errorid]) ? self::$errors[$errorid] : '其它错误';
    }

}

?>
