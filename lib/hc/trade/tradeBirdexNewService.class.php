<?php

/*
 * 笨鸟、海带宝服务接口
 */

class tradeBirdexNewService {

    private $post_url = 'http://openapi.birdex.cn/';//正式提交地址
    private $secretKey = '124aa22d50c74a6ddba4d81db9a18d35';
    private $appKey = 'SHIHUO';


    /**
     *
     * 验证身份证号
     */
    public function idcardValidate($identityNumber,$name, $timeout = 20){
        if(!$identityNumber || !$name) return false;
        $where['name'] = $name;
        $where['identityNumber'] = $identityNumber;
        $res = $this->sendMessage($where, $this->post_url.'idcard/validate', $timeout);
        if($res == 9999){
            $message = array(
                'message'=>'笨鸟验证身份证超时',
                'param'=>$where,
                'res'=>$res
            );
            tradeLog::error('idcardValidate',$message);
            return 'timeout';
        }
        $result = json_decode($res, true);
        if ($result['code'] == 0){
            $message = array(
                'message'=>'笨鸟验证身份证成功',
                'param'=>$where,
                'res'=>$result,
            );
            tradeLog::info('idcardValidate',$message);
            return 'success';
        }
        $message = array(
            'message'=>'笨鸟验证身份证失败',
            'param'=>$where,
            'res'=>$result
        );
        tradeLog::error('idcardValidate',$message);
        return 'failed';
    }

    /**
     *
     * 公用发送报文
     * @param array $content
     */
    public function sendMessage($content, $url, $timeout = 20){
        $content['eventTime'] = date('Y-m-d H:i:s');
        $content['occurTime'] = date('Y-m-d H:i:s');
        $content['appKey'] = $this->appKey;
        $logistics_interface = json_encode($content);
        $sign = $this->HmacSha1($logistics_interface, $this->secretKey);
        return tradeCommon::getBirdexContents($url, $logistics_interface, $timeout, 'POST', array("Sign:$sign",'Content-Type: application/json'));
    }

    /**
     * 生成签名数据
     *
     * @param sourceData 待加密的数据源
     * @param secretKey 密匙
     *
     * return 签名后数据
     */
    private function HmacSha1($sourceJson, $secretKey)
    {
        $signature = base64_encode((hash_hmac("sha1",$sourceJson, $secretKey, true)));
        $signature = str_replace(array('+','/','='),array('-','_',''),$signature);
        return $signature;
    }
}
