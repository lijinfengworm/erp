<?php
/**
 * Created by PhpStorm.
 * User: gupenghui
 * Date: 2015/10/22
 * Time: 15:30
 */
class WxSendRedEnvelope
{
    public function __construct()
    {
        // TODO
    }

        // 发送红包
    public function send($re_openid, $amount)
    {
        if (empty($re_openid) || empty($amount)) {
            return array('status' => false, 'msg' => '参数错误');
        }
        $total_amount = $amount; // 红包发放总金额，即一组红包金额总和，包括分享者的红包和裂变的红包，单位分
        $total_num = '1'; // 红包发放总人数，即总共有多少人可以领到该组红包（包括分享者）

        //$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $mch_id = WxPayConfig::MCHID;
        $wxappid = WxPayConfig::APPID;
        $vars = array(
            'nonce_str' => FunBase::genRandomString(32),
            'mch_billno' => $this->_generateBillno($mch_id),
            'mch_id' => $mch_id,
            'wxappid' => $wxappid,
            'send_name' => '虎扑识货',
            're_openid' => $re_openid,
            'total_amount' => $total_amount,
            'total_num' => $total_num,
            //'amt_type' => 'ALL_RAND',
            'client_ip' => FunBase::get_client_ip(),
            'wishing' => "红包数量有限, 分享活动给你的好友来领取",
            'act_name' => '识货黑五神价猜猜猜',
            'remark' => '快快分享吧'
        );
        $sign = $this->_generateSign($vars);
        $vars['sign'] = $sign;
        $varsXml = $this->_arrayToXml($vars);
        $res = $this->_curl_post_ssl($url, $varsXml);
        if ($res) {
            $array_data = (array) simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
            if ('SUCCESS' == $array_data['return_code'] && ('SUCCESS' == $array_data['result_code'])) {
                $amount = $array_data['total_amount']; // 单位分
                return array(
                    'status' => true,
                    'amount' => $amount,
                    'msg' => $array_data['return_msg']
                );
            } else {
                return array('status' => false, 'msg' => $array_data['return_msg']);
            }
        }
        return array('status' => false, 'msg' => '发送失败');
    }

    // 生成商户订单号
    private function _generateBillno($mch_id)
    {
        return $mch_id . date('Ymd', time()) . rand(1000000000, 9999999999);
    }

    /**
     * 	作用：生成签名
     */
    private function _generateSign($Obj)
    {
        $params = array();
        foreach ($Obj as $k => $v) {
            $params[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($params);
        $String = $this->_formatBizQueryParaMap($params, false);
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . WxPayConfig::KEY;
        //echo "【string2】".$String."</br>";
        //签名步骤三：MD5加密
        $String = md5($String);
        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }

    /**
     * 	作用：格式化参数，签名过程需要使用
     */
    private function _formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    // array转xml
    private function _arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<". $key .">". $val ."</" . $key . ">";

            } else {
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    // 发送ssl post请求
    private function _curl_post_ssl($url, $vars, $aHeader = array(), $second = 30)
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch, CURLOPT_SSLCERT, dirname(__FILE__) . WxPayConfig::SSLCERT_PATH);

        curl_setopt($ch, CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch, CURLOPT_SSLKEY, dirname(__FILE__) . WxPayConfig::SSLKEY_PATH);

        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        $data = curl_exec($ch);

        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }
}