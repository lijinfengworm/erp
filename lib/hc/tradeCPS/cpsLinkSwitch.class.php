<?php

/**
 * Description of cpsLinkSwitch
 *
 * @author zws
 */
class cpsLinkSwitch {

    static public $_prefix = 'http://cps.shihuo.cn/union?';//前缀url

    /**
     * 链接转化
     * @param $url 要转化的url
     * @param string $mid 网站id
     * @param $hupu_uid 用户id
     * @return url
     */
    public static function linkSwitch($url, $mid = '', $hupu_uid){
        if (!$url || !$hupu_uid) return array('status'=>1,'msg'=>'参数不完整');
        if(!preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
            return array('status'=>1,'msg'=>'url不合法');
        }
        $cpsUser =  CpsUserTable::getInstance()
            ->createQuery()
            ->select()
            ->where('hupu_uid = ?', $hupu_uid)
            ->andWhere('status = ?', 0)
            ->fetchOne();
        if (!$cpsUser) return array('status'=>1,'msg'=>'不是合法的用户');
        $parse_url = parse_url($url);
        if (isset($parse_url['fragment'])&& !empty($parse_url['fragment'])){
            $url = str_replace('#'.$parse_url['fragment'], '', $url);
        }
        $title = '';
        //获取标题
        $content = tradeCommon::getContents($url, 5);
        if ($content){
            $title_str = preg_match("/<title>(.*)<\/title>/i",$content, $titles);
            $title = isset($titles[1]) ? $titles[1] : '';
        }
        $data = array(
            'union_id' => $cpsUser->getUnionId(),
            'mid' => $mid,
            'to' => $url,
        );
        $return['title'] = $title;
        $return['link'] = self::$_prefix.http_build_query($data);

        return array('status'=>0,'data'=>$return,'msg'=>'');
    }
}
?>

