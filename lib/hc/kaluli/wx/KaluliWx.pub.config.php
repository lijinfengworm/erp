<?php
/**
 * Created by PhpStorm.
 * User: lilei
 * Date: 16/7/28
 * Time: 上午9:48
 */
class KaluliWx
{
    //=======【基本信息设置】=====================================
    //微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
    const APPID = 'wxef62bb5e24d590ec';

    //商户支付密钥Key。审核通过后，在微信发送的邮件中查看
    const KEY = 'cd26285021f00a069ca8b8de70a00145';


    //=======【JSAPI路径设置】===================================
    //获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
    const API_CALL_BACH_URL = '//m.kaluli.com/activity/widsomStadium?act=huodong&order=1';


    //=======【curl超时设置】===================================
    //本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
    const CURL_TIMEOUT = 30;
}
