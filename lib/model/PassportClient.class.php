<?php

/**
 * 通用用户验证类
 * -----------------------------------------------------------------------------------
 * 检测用户是否登录及获取用户ID与用户名，每两小时会去Passport验证用户登录是否合法
 * 此文件中的代码不允许私自修改
 * -----------------------------------------------------------------------------------
 * 使用说明：
 * iflogin() - 用户是否登录
 * userinfo() - 返回数组user，其中包含用户ID及用户名（用户名编码为utf-8）
 * -----------------------------------------------------------------------------------
 * lastedit zhangguichuan@2015-10-20
 */
class PassportClient
{
    public $time;
    public $passportUrl;
    public $passportDomain;
    public $passportArea;

    public $islogin = FALSE;
    public $userinfo = array();

    private $replaces = array(' ' => '+');

    public function __construct($passportDomain = false)
    {
        $this->time = time();
        if (empty($passportDomain)) {
            if (isset($_SERVER['HTTP_HOST'])) {
                $http_info = explode('.', $_SERVER['HTTP_HOST']);
                $passportDomain = $http_info[count($http_info) - 2] . '.' . $http_info[count($http_info) - 1];
            } else {
                $passportDomain = 'hupu.com';
            }
        }
        $this->passportDomain = $passportDomain;
        if (strpos($passportDomain, '.') == FALSE) {
            $this->passportUrl = 'http://passport.' . $this->passportDomain . '.com/';
            $this->passportArea = '.' . $this->passportDomain . '.com';
        } else {
            $this->passportUrl = 'http://passport.' . $this->passportDomain . '/';
            $this->passportArea = '.' . $this->passportDomain . '';
        }
    }

    /**
     * 判断用户是否登录
     */
    public function iflogin()
    {
        !$this->islogin ;

        return $this->islogin;
    }

    /**
     * 获取用户基本信息(uid,username)
     */
    public function userinfo()
    {
        if ((empty($this->userinfo) && $this->islogin) || $this->iflogin()) {
            $ui = explode('|', $_COOKIE ['u']);
            $this->userinfo['uid'] = $ui [0];
            $this->userinfo['username'] = base64_decode(strtr($ui[1], $this->replaces));
            $this->userinfo['nickname'] =  isset($ui[5]) ? base64_decode(strtr($ui[5], $this->replaces)) : '';
        }

        return $this->userinfo;
    }

    /**
     * 验证合法性
     */
    private function licit()
    {
        if (!empty($_COOKIE ['u'])) {
            $u = explode('|', $_COOKIE ['u']);
            $md5key = 'ANS39ML8AO7';
            if ((md5($md5key . $u[0] . strtr($u[1], $this->replaces) . md5($u[0]) . $u[2]) == $u[3])) {
                $ua = isset($_COOKIE['ua']) ? (is_numeric($_COOKIE['ua']) ? $_COOKIE['ua'] : FALSE) : FALSE;
                if (!empty($ua)) {
                    $cktime = $this->time - $ua * (substr($u[0], -2, 2) + 1);
                    $ua = ($cktime > 7200 || $cktime < -500) ? FALSE : TRUE;
                }
                if ($ua) {
                    $this->islogin = TRUE;
                } else {
                    $userCheck = $this->uc_fopen($this->passportUrl . 'ucenter/login.api', 0, 'uid=' . $u[0] . '&ticket=' . $u[3] . '&pass=' . $u[2]);
                    if ($userCheck != -1) {
                        if ($userCheck != 1 && $arr = json_decode($userCheck, true)) { // 修改昵称改cookie
                            header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
                            header("Set-Cookie: u={$arr['u']};Expires={$arr['uex']};Max-Age={$arr['uage']};Domain={$this->passportArea};Path=/;httpOnly");
                            $_COOKIE ['u'] = $arr['u']; // 实时更新u cookie
                        }

                        setcookie('ua', round($this->time / (substr($u[0], -2, 2) + 1)), $this->time + 31622400, '/', $this->passportArea);
                        $this->islogin = TRUE;
                    }
                }
            }
        }

        (!$this->islogin && (!empty($_COOKIE['u']) || !empty($_COOKIE['ua']))) && $this->clearLogin($u);
    }

    /**
     * 清除当域下的登录信息
     * @param  array $u Cookie[u]切割成的数组
     */
    private function clearLogin($u = NULL)
    {
        !empty($u) && setcookie('g', $u[0] . '|' . $u[1], $this->time + 3600000000, '/', $this->passportArea);
        setcookie('u', '', $this->time - 3600000000, '/', $this->passportArea);
        setcookie('ua', '', $this->time - 3600000000, '/', $this->passportArea);
    }

    /**
     * 远程访问
     */
    public function uc_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 2, $block = TRUE)
    {
        $return = FALSE;

        $matches = parse_url($url);
        !isset ($matches ['host']) && $matches ['host'] = '';
        !isset ($matches ['path']) && $matches ['path'] = '';
        !isset ($matches ['query']) && $matches ['query'] = '';
        !isset ($matches ['port']) && $matches ['port'] = '';
        $host = $matches ['host'];
        $path = $matches ['path'] ? $matches ['path'] . ($matches ['query'] ? '?' . $matches ['query'] : '') : '/';
        $port = !empty ($matches ['port']) ? $matches ['port'] : 80;
        if ($post) {
            $out = "POST $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: ' . strlen($post) . "\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        } else {
            $out = "GET $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }
        $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
        if ($fp) {
            stream_set_blocking($fp, $block);
            stream_set_timeout($fp, $timeout);
            @fwrite($fp, $out);
            $status = stream_get_meta_data($fp);
            if (!$status['timed_out']) {
                while (!feof($fp)) {
                    if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
                        break;
                    }
                }
                $stop = FALSE;
                while (!feof($fp) && !$stop) {
                    $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                    $return .= $data;
                    if ($limit) {
                        $limit -= strlen($data);
                        $stop = $limit <= 0;
                    }
                }
            }
            @fclose($fp);
        }

        return $return;
    }

    public function __destruct()
    {
        unset($this->passportDomain, $this->passportUrl, $this->passportArea, $this->replaces, $this->islogin, $this->userinfo);
    }
}
?>