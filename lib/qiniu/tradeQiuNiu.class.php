<?php
require_once("qiniu/io.php");
require_once("qiniu/rs.php");
require_once("qiniu/fop.php");
require_once("qiniu/http.php");
require_once("qiniu/auth_digest.php");
require_once("qiniu/utils.php");
/**
 * qiuniu library
 */
class tradeQiNiu
{
    private $bucket;
    private $accessKey;
    private $secretKey;
    private $uploadHost;
    public function __construct() {
		$config = sfConfig::get('app_trade_qiniu');
        $this->bucket = $config['bucket'];
        $this->accessKey = $config['accessKey'];
        $this->secretKey = $config['secretKey'];
        $this->uploadHost = $config['uploadHost'];
    }

    /**
     * 生成token
     */
    public function generateToken() {
        Qiniu_SetKeys($this->accessKey, $this->secretKey);
        $putPolicy = new Qiniu_RS_PutPolicy($this->bucket);
        $upToken = $putPolicy->Token(null);
        return $upToken;
    }

    /**
     * 上传文件
     * @param string $qiniu_name 七牛的文件名称
     * @param string $filename 本地的路径
     * @return boolean / string 上传失败 / 上传成功后路径
     */
    public function uploadFile($qiniu_name,$filename){

        Qiniu_SetKeys($this->accessKey, $this->secretKey);
        $putPolicy = new Qiniu_RS_PutPolicy($this->bucket);
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_PutFile($upToken, $qiniu_name, $filename, $putExtra);
        if ($err !== null) {
            return false;
        } else {
            return $this->uploadHost.$ret['key'];
        }
    }
    /**
     * 删除单个文件
     * @param string $qiniu_name 七牛的文件名称
     * @return boolean 
     */
    public function deleteFile($qiniu_name){
        Qiniu_SetKeys($this->accessKey, $this->secretKey);
        $client = new Qiniu_MacHttpClient(null);
        $err = Qiniu_RS_Delete($client, $this->bucket, $qiniu_name);
        if ($err !== null) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * 生成预览图片
     * @param type $qiniu_name
     * @return type 
     */
    public function previewImage($qiniu_name){
        Qiniu_SetKeys($this->accessKey, $this->secretKey);
        //生成baseUrl
        $baseUrl = Qiniu_RS_MakeBaseUrl('shihuo.hupucdn.com', $qiniu_name);
        //生成fopUrl
        $imgView = new Qiniu_ImageView;
        $imgView->Mode = 1;
        $imgView->Width = 100;
        $imgView->Height = 100;
        $imgViewUrl = $imgView->MakeRequest($baseUrl);  
        return $imgViewUrl;
    }
    
    /**
     * 上传远程图片
     * @param string $targetUrl 远程文件地址
     * @return string $qiniu_name 七牛文件名
     */
    public function uploadRemoteImage($targetUrl,$qiniu_name){
        $encodedUrl = Qiniu_Encode($targetUrl);
        $destEntry = "$this->bucket:$qiniu_name";
        $encodedEntry = Qiniu_Encode($destEntry);

        $apiHost = "http://iovip.qbox.me";
        $apiPath = "/fetch/$encodedUrl/to/$encodedEntry";
        $requestBody = "";

        $mac = new Qiniu_Mac($this->accessKey, $this->secretKey);
        $client = new Qiniu_MacHttpClient($mac);

        list($ret, $err) = Qiniu_Client_CallWithForm($client, $apiHost . $apiPath, $requestBody);
        if ($err !== null) {
            return false;
        } else {
            return $this->uploadHost.$qiniu_name;
        }
    }

    /*
    *处理结果另存（saveas）
    *@param string $qiuniu_path 七牛文件地址
    *@param string $folder 七牛文件地址
    *@return string $qiniu_name 七牛文件名
    * */
    public function saveas($qiuniu_path, $folder){
        //生成EncodedEntryURI的值
        $newPicName = md5(microtime(true).mt_rand(5000,100000));
        $entry = "hupu-shihuo:{$folder}";
        $encodedEntryURI = FunBase::base64ForQiniu($entry);

        //使用SecretKey对新的下载URL进行HMAC1-SHA1签名
        if(false !== strpos($qiuniu_path, 'http://')){
            $qiuniu_path = substr($qiuniu_path, 7);
        }
        $newurl = "{$qiuniu_path}|saveas/".$encodedEntryURI;
        $sign   = hash_hmac("sha1", $newurl, $this->secretKey, true);


        //对签名进行URL安全的Base64编码
        $encodedSign = FunBase::base64ForQiniu($sign);

        //最终得到的完整下载URL
        $finalURL = "http://".$newurl."/sign/".$this->accessKey.":".$encodedSign;

        //请求存储
        $res = tradeCommon::requestUrl($finalURL, 'GET', NULL, NULL, 3);

        $return = array('status'=>false);
        if (FunBase::is_json($res)) {
            $res = json_decode($res, true);
            if (isset($res['key'])) {
                $return['status'] = true;
                $return['url']    = $this->uploadHost.$res['key'];
            }
        }

        return json_encode($return);
    }
}

?>