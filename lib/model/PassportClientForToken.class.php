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
 * lastedit hopes@2011-07-12
 */
class PassportClientForToken
{
    public $time;
    public $passportUrl;
    public $passportDomain;
    public $passportArea;
    public $token;

    public $islogin = FALSE;
    public $userinfo = array();

    private $replaces = array(' ' => '+');

    public function __construct($passportDomain='hupu', $token=NULL)
    {
        $this->passportDomain = $passportDomain;
        $this->passportUrl = 'http://passport.' . $this->passportDomain . '.com/';
        $this->passportArea = '.' . $this->passportDomain . '.com';

        $this->token = $token;

        $this->time = time();
    }

    /**
     * 判断用户是否登录
     */
    public function iflogin()
    {
        !$this->islogin;

        return $this->islogin;
    }

    /**
     * 获取用户基本信息(uid,username)
     */
    public function userinfo()
    {
        if((empty($this->userinfo) && $this->islogin) || $this->iflogin())
        {
            $ui = explode ( '|', $this->token);
            $this->userinfo['uid'] = $ui [0];
            $this->userinfo['username'] =  base64_decode(strtr($ui[1], $this->replaces));
        }

        return $this->userinfo;
    }

    /**
     * 验证合法性
     */
    private function licit()
    {
        if(!empty($this->token))
        {
            $u = explode('|', $this->token);
            $md5key = 'ANS39ML8AO7';
            if((md5($md5key . $u[0] . strtr($u[1], $this->replaces) . md5($u[0]) . $u[2]) == $u[3]))
            {
                $ua = isset($_COOKIE['ua']) ? (is_numeric($_COOKIE['ua']) ? $_COOKIE['ua'] : FALSE) : FALSE;
                if(!empty($ua))
                {
                    $cktime = $this->time - $ua * (substr($u[0], -2, 2) + 1);
                    $ua = ($cktime > 7200 || $cktime < -500) ? FALSE : TRUE;
                }
                if(1 || $ua)
                {
                    $this->islogin = TRUE;
                }
                else
                {
                    if($this->uc_fopen($this->passportUrl . 'index.php?m=user&a=login', 0, 'uid=' . $u[0] . '&ticket=' . $u[3] . '&pass=' . $u[2]) != -1)
                    {
                        setcookie('ua', round($this->time / (substr($u[0], -2, 2) + 1)), $this->time+31622400, '/', $this->passportArea);
                        $this->islogin = TRUE;
                    }
                }
            }
        }
    }

    /**
     * 远程访问
     */
    public function uc_fopen($url, $limit=0, $post='', $cookie='', $bysocket=FALSE, $ip='', $timeout=3, $block=TRUE)
    {
        $return = FALSE;

        $matches = parse_url ( $url );
        ! isset ( $matches ['host'] ) && $matches ['host'] = '';
        ! isset ( $matches ['path'] ) && $matches ['path'] = '';
        ! isset ( $matches ['query'] ) && $matches ['query'] = '';
        ! isset ( $matches ['port'] ) && $matches ['port'] = '';
        $host = $matches ['host'];
        $path = $matches ['path'] ? $matches ['path'] . ($matches ['query'] ? '?' . $matches ['query'] : '') : '/';
        $port = ! empty ( $matches ['port'] ) ? $matches ['port'] : 80;
        if($post)
        {
            $out = "POST $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: ' . strlen ( $post ) . "\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        }
        else
        {
            $out = "GET $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }
        $fp = @fsockopen ( ($ip ? $ip : $host), $port, $errno, $errstr, $timeout );
        if($fp)
        {
            stream_set_blocking ( $fp, $block );
            stream_set_timeout ( $fp, $timeout );
            @fwrite ( $fp, $out );
            $status = stream_get_meta_data ( $fp );
            if(!$status['timed_out'])
            {
                while( !feof( $fp ) )
                {
                    if(($header = @fgets( $fp )) && ($header == "\r\n" || $header == "\n"))
                    {
                        break;
                    }
                }
                $stop = FALSE;
                while(!feof( $fp ) && !$stop )
                {
                    $data = fread ( $fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit) );
                    $return .= $data;
                    if($limit)
                    {
                        $limit -= strlen ( $data );
                        $stop = $limit <= 0;
                    }
                }
            }
            @fclose ( $fp );
        }

        return $return;
    }

    public function __destruct()
    {
        unset($this->passportDomain, $this->passportUrl, $this->passportArea, $this->replaces, $this->islogin, $this->userinfo);
    }
}
