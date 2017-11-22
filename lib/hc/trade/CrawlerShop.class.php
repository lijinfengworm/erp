<?php
/**
 * 抓取各个网站的商品信息 初级版
 * User: 梁天
 * Date: 2015/3/16
 * Time: 18:14
 */
class CrawlerShop {

    private $_url = null; //抓取url
    private $_userInfo = null; //url 信息
    private $_host = null;  //url  host部分
    private $_site = null;  //url site部分
    //可以抓取的网站
    private $_crawlerList = array('tmall', 'taobao', 'jd', 'yixun', 'yhd', 'amazon', 'yougou');

    private $_data = array();
    private $_result = array('data'=>array(),'status'=>1,'message'=>'');
    private $_content = null;

    //执行方法
    private $_execute = array('Taobao','Tmall','Jd', 'Yixun', 'Yhd', 'AmazonCn','AmazonCom', 'Yougou');

    public function __construct($_url = '') {
        /* 分析 url  */
        $this->getUrl($_url);
        /* 强力抓取器 汪汪汪 开始。。。。 */
        $this->_init();
    }


    /*
     * 获取url
     */
    private  function getUrl($_url = '') {
        /* 为空直接返回 */
        if (empty($_url)) throw new sfException('url不能为空！');
        /* 防止内存溢出破坏 */
        $this->_url = substr($_url, 0, 8182);
        /* 确定输入正确的url地址 */
        if (!preg_match("/^http(s){0,1}:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\’:+!]*([^<>\"])*$/", $this->_url)) {
            throw new sfException('暂不支持此地址的信息抓取！');
        }
        /* 分析 url  */
        $this->_urlinfo = parse_url($this->_url);
        $this->_host = $this->_urlinfo['host'];
        $this->_site = explode('.', $this->_host);
        if (!in_array($this->_site[1], $this->_crawlerList)) {
            throw new sfException('暂不支持此地址的信息抓取！');
        }
    }

    private function _getContent() {
        $this->_content = file_get_contents($this->_url);
    }

    private function _getTitle() {
        $pattern = '/<meta.*charset=[\'"]?([^"\']*)[\'"]?.*>/';
        preg_match($pattern, $this->_content, $m);
        if ($m) {
            if (isset($m[1]) && $m[1]) {
                $charset = $m[1];
            }
        }
        $pattern = "/<title>(.*)-(.*)<\/title>/";
        preg_match($pattern, $this->_content, $m);
        if ($m) {
            if (isset($m[1]) && $m[1]) {
                if (isset($charset) && $charset && strtolower($charset) != 'utf-8') {
                    $title = iconv($charset, 'UTF-8', $m[1]); //将其它编码转换为utf-8
                } else {
                    $title = $m[1];
                }
                $this->setData('title',$title);
            }
        }
    }
    /*
     * 初始化抓取
     */
    private function _init() {
        $flag = false;
        foreach($this->_execute as $k=>$v) {
            $_fun = '_exec'.$v;
            if (method_exists($this, $_fun)) {
                $flag = $this->$_fun();
                if($flag) break;
            }
        }
    }

    private function _execTaobao() {
        $_price  = $_title = $_shihuo_price = $_pic = null;
        if (stripos($this->_host, 'taobao') && !empty($this->_urlinfo['query'])) {
            parse_str($this->_urlinfo['query'], $queryRes);
            if (!empty($queryRes['id'])) {
                $url = 'http://hws.m.taobao.com/cache/wdetail/5.0/?id=' . $queryRes['id'];
                $result = file_get_contents($url);
                $content = json_decode($result, true);
                if ('SUCCESS::调用成功' == $content['ret'][0]) {
                    $_title = $content['data']['itemInfoModel']['title'];
                    $esc = json_decode($content['data']['apiStack'][0]['value'], true);
                    $priceUnits = $esc['data']['itemInfoModel']['priceUnits'];
                    $_shihuo_price = $priceUnits[0]['price'];
                    !empty($priceUnits[1]) && $_price = $priceUnits[1]['price'];
                    $_pic = $content['data']['itemInfoModel']['picsPath'];
                }
            }

            $this->setData('title',$_title);
            $this->setData('shihuo_price',$_shihuo_price);
            $this->setData('price',$_price);
            $this->setData('pic',$_pic);
            $this->setResult('type','taobao');
            return true;
        }
       return false;
    }


    private function _execTmall() {
        if (stripos($this->_host, 'tmall')) {
            $this->_getContent();
            $this->_getTitle();
            $pattern = '/defaultItemPrice":"([^"]*)",?/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $_price = $m[1];
                    if(strstr($_price,'-') !== false) {
                        $_price_arr = explode('-',$_price);
                        $_price = trim($_price_arr[0]);
                    }
                    $this->setData('shihuo_price',$_price);
                }
            }
            $pattern = '/<img.*J_ImgBooth.*src="([^"]*)".*>/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $this->setData('pic',array($m[1]));
                }
            }
            $this->setResult('type','tmall');
            return true;
        }
        return false;
    }

    private function _execJd() {
        if (stripos($this->_host, 'jd')) {
            $jid = substr($this->_urlinfo['path'], 1, stripos($this->_urlinfo['path'], '.html') - 1);
            $this->_getContent();
            $this->_getTitle();
            $_url = 'http://p.3.cn/prices/mgets?type=1&skuIds=J_' . $jid;
            $data = json_decode(file_get_contents($_url), true);
            if (isset($data[0]['p']) && $data[0]['p']) {
                $this->setData('shihuo_price',$data[0]['p']);
            }
            $pattern = '/<div.*spec-n1.*\s*<img.*src="([^"]*)".*>\s*<\/div>/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $this->setData('pic',array($m[1]));
                }
            }
            $this->setResult('type','jd');
            return true;
        }
        return false;
    }


    private function _execYhd() {
        if (stripos($this->_host, 'yhd')) {
           $this->_getContent();
            $this->_getTitle();
            $pattern = '/<span.*itemprop="(low)?[pP]rice"><i.*priceCurrency.*>.*<\/i>(.*)<\/span>/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[2]) && $m[2]) {
                    $this->setData('shihuo_price',$m[2]);
                }
            }
            $pattern = '/<img.*xgalleryImg.*src="([^"]*)".*>/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $this->setData('pic',array($m[1]));
                }
            }
            $this->setResult('type','yhd');
            return true;
        }
        return false;
    }

    /**
     * 亚马逊中国
     */
    private function _execAmazonCn() {
        if (stripos($this->_host, 'amazon.cn')) {

            $this->_getContent();
            $this->_getTitle();
            $pattern = '/<tr.*>\s*<td.*>\w{1,5}[：:]{1}.*\s*<td.*>\s*<span.*>\D{1,2}(\d+(\.\d+)*).*/u';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $this->setData('shihuo_price',$m[1]);
                }
            }
            $pattern = '/<div.*imgTagWrapperId.*>\s*<img.*src="([^"]*)".*>/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $this->setData('pic',array($m[1]));
                }
            }
            $this->setResult('type','amazoncn');
            return true;
        }
        return false;
    }

    /**
     * 亚马逊美国
     */
    private function _execAmazonCom() {
        if (stripos($this->_host, 'amazon')) {
            $this->_getContent();
            $this->_getTitle();
            $pattern = '/<tr.*>\s*<td.*>\w{1,5}[：:]{1}.*\s*<td.*>\s*<span.*>\D{1,2}(\d+(\.\d+)*).*/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $this->setData('shihuo_price','$ ' . $m[1]);
                }
            }
            $pattern = '/<div.*imgTagWrapperId.*>\s*<img.*src="([^"]*)".*>/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $this->setData('pic',array($m[1]));
                }
            }
            $this->setResult('type','amazoncom');
            return true;
        }
        return false;
    }

    /**
     * 优购
     */
    private function _execYougou() {
        if (stripos($this->_host, 'yougou')) {
            $this->_getContent();
            $this->_getTitle();
            $pattern = '/salePrice\s:\s"([^"]*)",/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $this->setData('shihuo_price',$m[1]);
                }
            }
            $pattern = '/<img\sid="pD-bimg".*src="([^"]*)"/';
            preg_match($pattern, $this->_content, $m);
            if ($m) {
                if (isset($m[1]) && $m[1]) {
                    $this->setData('pic',array($m[1]));
                }
            }
            $this->setResult('type','yougou');
            return true;
        }
        return false;
    }


    private function setData($key,$val = '') {
        $this->_data[$key] = $val;
    }

    private function setResult($key,$val = '') {
        $this->_result[$key] = $val;
    }

    public function  getData() {
        return $this->_data;
    }

    /**
     * 获取完整信息
     */
    public function getResult() {
        if(empty($this->_data)) {
            $this->_result['status'] = 0;
            $this->_result['message'] = '暂不支持此地址的信息抓取';
        } else {
            //判断是否抓取完整
            $flag = 0;
            foreach ($this->_data as $v) {
                if (!empty($v))  $flag++;
            }
            if ($flag === 0) {
                $this->_result['status'] = 0;
                $this->_result['message'] = '暂不支持此地址的信息抓取';
            } else if ($flag < count($this->_data)) {
                $this->_result['status'] = 0;
                $this->_result['message'] = '只抓到部分信息，请完善信息吧 ~';
                $this->_result['data'] = $this->getData();
            } else {
                $this->_result['status'] = 1;
                $this->_result['message'] = '商品信息获取成功';
                $this->_result['data'] = $this->getData();
            }
        }
        return $this->_result;
    }







}