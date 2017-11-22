<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2017/7/7
 * Time: 下午2:56
 * 模板消息生成类
 */


class kaluliWxTemplate {

    public static $typeTemplate =  array(
        1=>'hnjUMkO-teFOShgkqSpYb0mWz_fT-ixxlN9XmaWPM2s',
        2=>'noqeHmGJ1OESjlhy-JvfdSqNTkODFPPHcx-hrb1qVtA',
        3=>'zfSf6HpKhzfmT8z_-n_U8fOjgODA20SsARUYkPpFB1U'
    );
    public static $_DELIVERY = 1;  //商品发货

    public static $_REFUND = 2; //退款成功

    public static $_KOL   = 3;  //达人分销

    /**
     * 构造消息体
     * @param $openId 用户od
     * @param $type   type:1.商品发货 2.退款  3.达人分销
     * @param $params
     */
    public static function buildTemplate($openId,$type,$params){
        if(empty($openId) || empty($type) || empty($params)) {
            return array();
        }
        //假如设置的类型不存在
        if(empty(self::$typeTemplate[$type])) {
            return array();
        }
        $url = '';
        if($type == self::$_DELIVERY){
            $url = 'https://m.kaluli.com/order/orderLogistics/order_number/'.$params['orderNumber'];
            unset($params['orderNumber']);
        } elseif ($type ==self::$_KOL) {
            $url = 'https://m.kaluli.com/kol/main?kolId='.$params['kolId'];
            unset($params['kolId']);
        }

        //根据模板类型构造数据
        $template = array(
            'touser'=>$openId,
            'template_id'=>self::$typeTemplate[$type],
            'topcolor'=>'#FF0000',
            'url'=>$url,
            'data'=>self::buildData($params)
        );

        return json_encode($template);
    }


    /**
     * 构建消息体数据
     * @param $params
     * @param $type
     */
    public static function buildData($params) {
        $return = array();
        //构造first和remark
        foreach ($params as $k => $v) {
            $return[$k] = array('value'=>$v,'color'=>($k=='first')?'#000000':'#999999');
        }
        return $return;
    }





    

}