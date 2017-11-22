<?php
//namespace lib\badwords;
//
//use core\Cache,
//    core\Core\Config;
//use lib\cache\bbsCache;

/**
 * 替换或者检测是否有违禁词
 * @author 文林@2011-12-12
 */
class badwords
{

    /**
     * 报错违禁词
     * @var array
     */
    static public $bannedWords = array('\*河蟹词\*');

    /**
     * 违禁词列表
     * @var array
     */
    static private $badwords;

    /**
     * 内链系统列表
     * @var array
     */
    static private $insideLinks;

    /**
     * badwords词库缓存key
     * @var string
     */
    static private $replaceCacheKey = 'bbs:cache:badwords:';

    /**
     * badwords词库更新时间key
     * @var string
     */
    static private $replaceUptimeCacheKey = 'bbs:cache:badwords:uptime:';

    /**
     * 违禁字库
     * @var null
     */
    static private $replace = null;

    /**
     * 是否进行违禁词替换
     * @global array $replace   违禁词列表
     * @param string $message   需要替换的内容
     * @return string   返回替换过的内容
     */

    static public function replaceWord(& $message )
    {
        $message = self::unescape($message);
        if(!function_exists('badwords_replace'))
        {
            global $replace;

            isset($replace) || $replace = self::getbadwordsfb();

            return strtr($message, $replace);
        }
        $badwords = self::_get_shared_badwords();
        return badwords_replace($badwords, $message);
    }

    static public function getbadwordsfb($app = 'kaluli', $force = false)
    {

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(10);
        $badwordstamp = [];
        if(!self::$replace[$app] || $force)
        {
            $badwordstamp = $redis->smembers('badwords_bbscache');
        }


        !$badwordstamp && $badwordstamp = [];
        //chkcode函数只检测gbk的（2字节），所以只能用于bbs项目，antispam是utf8的
        if($app == 'bbs'){
            foreach ($badwordstamp as $key => $value) {
                if(!self::chkcode($key)){
                    unset($badwordstamp[$key]);
                }
            }
            $badwordstamp = eval('return '.iconv('gbk','utf-8',var_export($badwordstamp,true)).';');           
        }
        $newData = [];

        if(is_array($badwordstamp)){

            foreach ($badwordstamp as $v){

                $newData[$v] = '';
            }
        }
        //添加新的过滤词
        $newData['-'] = '';
        self::$replace[$app] = $newData;

        return self::$replace[$app];
    }

    /**
     * 检测是否存在违禁词
     * @global array $replace   违禁词列表
     * @param string $messages  需要检测的内容
     * @return string   返回检测到的违禁词
     *
     */
    static public function checkWord(& $messages, $app = NULL)
    {
        $result = NULL;

        $messages = self::unescape($messages);

        if(!function_exists('badwords_match'))
        {

            global $replace;

            if(!isset($replace))
            {
                $replace = self::getbadwordsfb("kaluli");

                $GLOBALS['replace'] = $replace;
            }

            if(is_array($messages))
            {

                foreach($messages as $messagesKey => $message)
                {
                    foreach($replace as $key => $val)
                    {
                        if(strpos($message, (string) $key) !== FALSE)
                        {
                            $result = $key;

                            break;
                        }
                    }

                    if($result)
                    {
                        break;
                    }
                }
            }
            else
            {
                foreach($replace as $key => & $val)
                {
                    if(strpos($messages, (string) $key) !== FALSE)
                    {
                        $result = $key;

                        break;
                    }
                }
            }

            return $result;
        }
        else
        {

            $badwords = self::_get_shared_badwords($app);


            if(is_array($messages))
            {
                foreach($messages as $messagesKey => & $message)
                {
                    $result = badwords_match($badwords, $message);

                    if($result)
                    {
                        break;
                    }
                }
            }
            else
            {
                $result = badwords_match($badwords, $messages);


            }

            return $result;
        }
    }

    /**
     * 载入Badwords列表资源
     * @param $app 项目名称
     * @param $force 是否强制更新
     * @return array
     */
    static public function _get_shared_badwords($app = NULL, $force = false)
    {
        $force = true;

        if(empty(self::$badwords) || $force)
        {
            //$charset = BADWORDS_ENCODING_GBK;
            $charset = BADWORDS_ENCODING_UTF8;
            $replace = self::getbadwordsfb();

            $triebin = sfConfig::get('sf_web_dir'). '/' . 'com.hoopchina.bbs-wordsfb.bin';
            $persistkey = 'badwords::com.hoopchina.bbs::wordsfb';

            if($app == 'kaluli')
            {
                //var_dump($replace);exit;
                //$replace = array('江泽民' => '', '永康跳出来' => '', '绣湖广场' => '', '大雁塔广场' => '', '成品油乱涨价行为' => '', '汉族人也不是吃素的' => '', '江的娘家人永康' => '', '法轮功' => '', '波霸乳交器具' => '', '成人仿真抽插' => '', '灌肠自慰器' => '');
                $triebin = sfConfig::get('sf_web_dir'). '/' .'com.hoopchina.' . $app . '-badwords.bin';
                $persistkey = 'badwords::com.hoopchina.' . $app . '::badwords';
            }

            $cacheuptimekey =  (($app!=='antispam')?'badwords_bbscache':'badwords_antispam');
            $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $redis->select(10);

            //$wmtime = $redis->getRedisUptime($cacheuptimekey);
            $wmtime = time();
            $tmtime =  time()+1;
                //filemtime($triebin);

            //if($tmtime === FALSE || $tmtime !== $wmtime && mt_rand(0, 99) < 5 || $force)
            if($tmtime === FALSE ||  !$tmtime== $wmtime || $force)
            {

                $compiler = badwords_compiler_create($charset, True);

                badwords_compiler_append($compiler, $replace);
                unset($replace);

                $trie = badwords_compiler_compile($compiler);
                unset($compiler);

                if($trie)
                {
                    $triebin_tmp = $triebin . '-' . getmypid();
                    file_put_contents($triebin_tmp, $trie);
                    touch($triebin_tmp, $wmtime);
                    rename($triebin_tmp, $triebin);
                    unset($trie);
                }
            }

            self::$badwords = badwords_create($triebin, $persistkey);
        }


        return self::$badwords;
    }

    /**
     * 更新badwords
     * @return array
     */
    static public function up_shared_badwords()
    {
        return self::_get_shared_badwords(null, true);
    }

    /**
     * 检查内容中是否包含报错违禁词，存在则直接报错
     * @param string $content
     */
    static public function filterBannedContent(& $content, $insertTime = NULL, $showError = False, $permission = FALSE)
    {
        global $groupid;

        $result = FALSE;

        $content = self::unescape($content);

        if(!in_array($groupid, array(3, 4)) || $permission)
        {
            preg_match_all('/(' . implode('|', self::$bannedWords) . ')+/', $content, $matchs);

            if(!empty($matchs[0]))
            {
                if($showError === TRUE)
                {
                    Showmsg('data_error');
                }
                else
                {
                    $result = TRUE;
                }
            }
        }
        return $result;
    }

    /**
     * 插入坏词日志  只记日志和论坛的数据
     * @param   $postDate  发帖等产生时间
     * @param   $toUrl     相关url
     * @return   bool
     */
    static function insertBadWordLog($postDate, $toUrl)
    {
        global $db;
        //限制一次请求只做一次操作保证效率，假设一个请求中有多个屏蔽词，只会插入第一处
        static $insertCount = 0;

        if($insertCount == 0)
        {
            //此举为了避免大部分的数据库操作重复插入操作
            $logs = $db->getRow("SELECT * FROM fbd_bad_word_log WHERE url='{$toUrl}'");
            if($logs)
            {
                return false;
            }
            $url = 'http://' . $_SERVER['SERVER_NAME'] . DIRECTORY_SEPARATOR . $toUrl;
            $sql = "INSERT IGNORE fbd_bad_word_log  ( `url`, `dateline`)
	    			VALUES('{$url}', '" . $postDate . "' ) ";

            $db->update($sql);
        }

        $insertCount++;
        return true;
    }

    /**
     * 载入内链系统列表 Inside
     * @return array
     */
    static private function _get_shared_links($app = NULL)
    {
        if(empty(self::$insideLinks))
        {
            $charset = BADWORDS_ENCODING_GBK;
            $wordfile = D_P . 'data/bbscache/insideLinksShihuo.php';
            $triebin = '/dev/shm/com.hoopchina.bbs-insideLinksShihuo.bin';
            $persistkey = 'insideLinks::com.hoopchina.bbs::insideLinksShihuo';


            $wmtime = filemtime($wordfile);
            $tmtime = filemtime($triebin);

            if($tmtime === FALSE || $tmtime !== $wmtime && mt_rand(0, 99) < 5)
            {
                include($wordfile);
                $compiler = badwords_compiler_create($charset, True);
                badwords_compiler_append($compiler, $insideLinksShihuo);
                unset($insideLinksShihuo);

                $trie = badwords_compiler_compile($compiler);
                unset($compiler);

                if($trie)
                {
                    $triebin_tmp = $triebin . '-' . getmypid();
                    file_put_contents($triebin_tmp, $trie);
                    touch($triebin_tmp, $wmtime);
                    rename($triebin_tmp, $triebin);
                    unset($trie);
                }
            }
            self::$insideLinks = badwords_create($triebin, $persistkey);
        }
        return self::$insideLinks;
    }

    /**
     * 是否进行内链词替换
     * @global array $insideLinksShihuo   内链词列表
     * @param string $message   需要替换的内容
     * @return string   返回替换过的内容
     */
    static public function replaceInside(& $message)
    {

        if(!function_exists('badwords_replace'))
        {
            global $insideLinksShihuo;

            isset($insideLinksShihuo) || include(D_P . 'data/bbscache/insideLinksShihuo.php');

            return strtr($message, $insideLinksShihuo);
        }

        $insides = self::_get_shared_links();

        return badwords_replace($insides, $message);
    }

    /**
     * 检测是否存在内链词
     * @global array $insideLinksShihuo   内链词列表
     * @param string $messages  需要检测的内容
     * @return string   返回检测到的违禁词
     */
    static public function checkInside(& $messages, $app = NULL)
    {
        $result = NULL;

        if(!function_exists('badwords_match'))
        {
            global $insideLinksShihuo;

            if(!isset($insideLinksShihuo))
            {
                require_once D_P . 'data/bbscache/insideLinksShihuo.php';

                $GLOBALS['insideList'] = $insideLinksShihuo;
            }

            if(is_array($messages))
            {

                foreach($messages as $messagesKey => & $message)
                {
                    foreach($insideLinksShihuo as $key => & $val)
                    {
                        if(strpos($message, (string) $key) !== FALSE)
                        {
                            $result = $key;

                            break;
                        }
                    }

                    if($result)
                    {
                        break;
                    }
                }
            }
            else
            {
                foreach($insideLinksShihuo as $key => & $val)
                {
                    if(strpos($messages, (string) $key) !== FALSE)
                    {
                        $result = $key;

                        break;
                    }
                }
            }

            return $result;
        }
        else
        {
            $insides = self::_get_shared_links($app);

            if(is_array($messages))
            {
                foreach($messages as $messagesKey => & $message)
                {
                    $result = badwords_match($insides, $message);

                    if($result)
                    {
                        break;
                    }
                }
            }
            else
            {
                $result = badwords_match($insides, $messages);
            }

            return $result;
        }
    }

    /**
     * qqery扩展的数据写入指定文件 并返回访问的地址
     * @return string
     */
    static private function _get_qqwry($app = NULL)
    {
        $wordfile = D_P . 'data/bbscache/qqwry.dat';
        $charset = BADWORDS_ENCODING_GBK;
        $triebin = '/dev/shm/com.hoopchina.bbs-qqwry.bin';

        $wmtime = filemtime($wordfile);
        $tmtime = filemtime($triebin);

        if($tmtime === FALSE || $tmtime !== $wmtime && mt_rand(0, 99) < 100)
        {
            $trie = @file_get_contents($wordfile, FALSE, stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 3))));
            $triebin_tmp = $triebin . '-' . getmypid();
            @file_put_contents($triebin_tmp, $trie);
            touch($triebin_tmp, $wmtime);
            rename($triebin_tmp, $triebin);
            unset($trie);
        }

        return $triebin;
    }

    /**
     * 返回指定ip所在的省市
     * @param   string  $ip     ip地址
     * @return  array
     */
    static public function getIpCity($ip)
    {
        $qqwryPath = self::_get_qqwry();
        $qqwryPath = is_file($qqwryPath) ? $qqwryPath : D_P . 'data/bbscache/qqwry.dat';

        $qqwry = new qqwry($qqwryPath);
        $result = $qqwry->q($ip);

        return $result;
    }

    /**
     * Unicode编码进行解码的函数
     */
    static public function unescape($message)
    {
        global $winduid;
        if(strpos($message, '&#') === FALSE || ($winduid < 17470000 && $winduid != 6508852))
        {
            return $message;
        }

        $message = rawurldecode($message);
        preg_match_all("/(?:%u.{4})|&#x.{4};|&#\d+;|.+/U", $message, $r);
        $ar = $r[0];
        //print_r($ar);
        foreach($ar as $k => $v)
        {
            if(substr($v, 0, 2) == "%u")
            {
                $ar[$k] = iconv("UCS-2BE", "UTF-8", pack("H4", substr($v, -4)));
            }
            elseif(substr($v, 0, 3) == "&#x")
            {
                $ar[$k] = iconv("UCS-2BE", "UTF-8", pack("H4", substr($v, 3, -1)));
            }
            elseif(substr($v, 0, 2) == "&#")
            {
                $ar[$k] = iconv("UCS-2BE", "UTF-8", pack("n", substr($v, 2, -1)));
            }
        }
        return join("", $ar);
    }

    /*
    过滤键值
    */
    static public function chkcode ($code) {
        $chk = true;
        $strlen = strlen($code);
        if($strlen<1){
            return false;
        }
        for($i=0;$i<$strlen;$i++){
            $codetemp = substr( $code, $i,1);
            if(ord($codetemp)<32){
                $chk = false;
                break;
            }elseif(ord($codetemp)>127){
                $i++;
                $codetemp = substr( $code, $i,1);
                if(!(ord($codetemp)>=161&&ord($codetemp)<=254)){
                    $chk = false;
                    break;
                }
            }
        }
        return $chk;
    }
    function test(){
        return 111;
    }

}



?>
