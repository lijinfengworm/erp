<?php 
/**
 * author kworm
 * 2017-03-02
 */
class bbKaluliService extends kaluliService {
	
	private $merchantId = '120142646';
	public static $merchant = [
		'qr' => 'ff808081598ce505015acab3dd25120d',
        'wwq' => 'ff808081598ce505015adb9c15191304'
	];
    private static $XML_PUSH = ['msgtype','procode','MerID','OrderNo','sysno','cardno','traceno','termid','CurrCode','CurrCodeCIQ','PayAmount','MerNo','RealName','CredentialsType','CredentialsNo','ShoppingDate','InternetDomainName','ECommerceCode','ECommerceName','MerCode','MerName','CbepComCode','CbepComName','CbepMerCode','CbepMerName','GoodsAmount','TaxAmount','Freight','InsuredFee','Mobile','Email','BizTypeCode','OriOrderNo','PayNo','PaymentType','IEType','OrganType','CustomsCode','PortCode','CIQOrgCode','BusinessType','CreTime','GetResultTime'];
	
	private static $XML_ASK = ['msgtype','procode','MerID','OrderStatus','OrderNo','traceno','termid','MerNo','BeginTime','EndTime'];
    private static $XML_UPDATE = ['msgtype','procode','MerID','OrderNo','sysno','cardno','traceno','termid','MerNo','ShoppingDate','OrganType','UpdateData','CustomsCode'];
    private static $XML_UPDATE_COLOMN = ['OrderAmount','RealName','GoodsAmount','TaxAmount','Freight','InsuredFee','CredentialsType','CredentialsNo','ECommerceCode','ECommerceName','MerCode','MerName','CbepComCode','CbepComName','CbepMerCode','CbepMerName','CustomsCode','PortCode','CIQOrgCode','OperStat','InternetDomainName','BusinessType','BizTypeCode'];
    
    const ENV = 'prod';
    // const NUM = '002172785100019';

    // const TERMID = '00904061';YPJSE20170914170527077977864
    const NUM = '002148165100017';
    const TERMID = '00000463';
    /**
     * 提交订单的操作
     */
    public function executeSendXml(){
        $mq = new KllAmqpMQ();
        $type = $this->getRequest()->getParameter('type');
        $opt =  $this->getRequest()->getParameter('opt');
        $data = $this->getRequest()->getParameter('main_order');
        $data = $this->dealData($data);
       try {
            $dt = $this->assembleXml($type, $data);
            $length = sprintf('%06s', strlen($dt));
            $respond = Funbase::getCurlXml('210.12.240.163','6001', $length.$dt);
            //写入日志
            $message = [
                'order_number'  =>  $data['order_number'],
                'body'          =>  ['银联推送日志', ['data' => $respond]]
            ];
            $mq->setExchangeMqTast("kaluli.erp.log", ['msg' => $message]);
            // 测试输出
            if(($type.'_'.$opt == 'PUSH_IN' || $type.'_'.$opt == 'ASK_IN') || ($type.'_'.$opt == 'PUSH_OUT' || $type.'_'.$opt == 'ASK_OUT')){
                $xml = new XMLParser();
                $res = $xml->loadXmlString($dt);
                var_dump($res);
                echo '<br />';
                echo '<br />';
                echo '<br />';
                var_dump($respond);
            }
            $this->dealRespond($respond, $type, $data);

            return $this->success();
        } catch (ExceptionInterface $e){
            return $this->error(400,json_encode(unserialize($e->getMessage())));
        }
    }
    /**
     * 处理数据
     */
    private function dealData($data){

        $data['start_time'] =  date("Y-m-d ", $data['pay_time']). '00:00:10';
        //date("Y-m-d H:i:s",time()-23*60*60);
        //echo  exit;
        $data['end_time'] = date("Y-m-d ", $data['pay_time']) .'23:59:50';
        //date("Y-m-d H:i:s", $data['pay_time'] + 6*60*60);
        //date("Y-m-d H:i:s", time()+60*60);
        $data['flow_number'] =  substr($data['flow_number'], -12);
        return $data;
    }
    
    /**
     * 接收返回
     */
    public function dealRespond($respond, $type, $data){
        $xml_length = substr($respond, 0, 6);
        $respond = str_replace($xml_length, '', $respond);
        $order_number = $data['order_number'];
        $message['order_number'] = $order_number;
        $message['author'] = $data['uid'];
        $xml_string = substr($respond, strlen('<?xml version="1.0" encoding="gbk"?>'),(int)$xml_length);
        $xml = simplexml_load_string($xml_string, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xml),true);
        $message['body']= isset($val['BODY']['Message']) ? $val['BODY']['Message'] : '未返回信息';

        if(isset($val['BODY']['Code']) && $val['BODY']['Code'] == '00'){
            if($type == 'PUSH'){
                try {
                    $flow_number = $val['BODY']['sysno'];
                    $upDataObj = KllBBMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
                    $upDataObj->setFlowNumber($flow_number)->save();
                    //写入流程记录
                    $message['body']  = ['data' => '支付单回执'];
                    self::insertBBProcess($order_number, 4, '支付单回执');
                } catch (Exception $e) {
                    $message['body']  = ['data' => '支付单推送错误'];
                }
            }
            if($type == "ASK"){
                try {
                    //成功之后把状态变成3(待处理)----交给java把订单推送到海关就可以了
                    $upDataObj = KllBBMainOrderTable::getInstance()->findOneByOrderNumber($order_number);
                    $upDataObj->setStatus(3)->save();
                    self::insertBBProcess($order_number, 5, '推送支付单');
                    $message['body']  = ['data' => '支付单确认成功'];
                } catch (Exception $e) {
                     $message['body']  = ['data' => '支付单确认错误'];
                }
                
            }
            
        }
        $mq = new KllAmqpMQ();
        $mq->setExchangeMqTast("kaluli.erp.log", ['msg' => $message]);

    }

    /**
     * 初始化xml,后面再填充
     */
    private function assembleXml($type = 'PUSH', $data=[], $version='1.0', $encoding="gbk"){
        $xml = '';
        $xml .= '<?xml version="'.$version.'" encoding="'.$encoding.'"?>';
        $xml .= '<PACKAGE><BODY>';
        switch ($type) {
            case 'PUSH':
                foreach (self::$XML_PUSH as $key => $item) {
                    $xml .= '<'.$item.'>';
                    $xml .= $this->getXmlItem($type, $item, $data);
                    $xml .= '</'.$item.'>';
                }
                break;
            case 'ASK':
                foreach (self::$XML_ASK as $key => $item) {
                    $xml .= '<'.$item.'>';
                    $xml .= $this->getXmlItem($type, $item, $data);
                    $xml .= '</'.$item.'>';
                }
                break;
            case 'UPDATE':
                foreach (self::$XML_UPDATE as $key => $item) {
                    if($item == "UpdateData"){
                        $xml .= '<'.$item.'>';
                        $xml .= $this->getUpdateItem($type, $data);
                        $xml .= '</'.$item.'>';
                    }else{
                        $xml .= '<'.$item.'>';
                        $xml .= $this->getXmlItem($type, $item, $data);
                        $xml .= '</'.$item.'>';
                    }
                    
                }
                break;
            default:
                break;
        }
        $xml .= '</BODY></PACKAGE>';
        return $xml;
    }
    /**
     * 根据数据返回值
     */
    private function getXmlItem($type = 'PUSH',$item, $data = []){
        
        switch ($item) {
            case 'msgtype':
                return '0100';
                break;
            case 'procode':
                if($type == "UPDATE"){
                    return '370000';
                }elseif ($type == "ASK") {
                    return '380000';
                }else{
                    return '360000';
                }
                break;
            case 'MerID':
                if(self::ENV == 'dev'){
                    return '0P3';
                }
                return 'BZZ';
                break;
            case 'OrderNo':
                if($type == "UPDATE"){
                    //20170804BZZ080472834504
                    return $data['flow_number'];
                }elseif($type == "ASK"){
                    return $data['flow_number'];
                }else{
                    return $data['order_number'];
                }
                break;
            case 'sysno':
                return '000000000000';
                break;
            case 'cardno':
                return '0000000000000000000';
                break;
            case 'traceno':
                return date("His");
                break;
            case 'termid':
                return self::TERMID;
                break;
            case 'CurrCode':
                return '142';
                break;
            case 'CurrCodeCIQ':
                return '156';
                break;
            case 'PayAmount':
                return sprintf('%012s', $data['push_price']*100);
                break;
            case 'MerNo':
                return self::NUM;
                break;
            case 'RealName':
                return iconv("utf-8","gbk",$data['real_name']);
                break;
            case 'CredentialsType':
                return '01';
                break;
            case 'CredentialsNo':
                return $data['card_code'];
                break;
            case 'ShoppingDate':
                return date("Ymd");
                break;
            case 'InternetDomainName':
                return 'www.kaluli.com';
                break;
            case 'ECommerceCode':
                return '3302462230';
                break;
            case 'ECommerceName':
                return iconv("utf-8","gb2312//IGNORE",'宁波鑫海通达贸易有限公司');
                break;
            case 'MerCode':
                return '3302462230';
                break;
            case 'MerName':
                return iconv("utf-8","gb2312//IGNORE",'宁波鑫海通达贸易有限公司');
                break;
            case 'CbepComCode':
                return '3302462230';
                break;
            case 'CbepComName':
                return iconv("utf-8","gb2312//IGNORE",'宁波鑫海通达贸易有限公司');
                break;
            case 'CbepMerCode':
                return '3302462230';
                break;
            case 'CbepMerName':
                return iconv("utf-8","gb2312//IGNORE",'宁波鑫海通达贸易有限公司');
                break;
            case 'GoodsAmount':
                if($type == "UPDATE_COLOMN"){
                    return Funbase::price_format($data['push_price']);
                }else{
                    return sprintf('%012s', $data['push_price']*100);
                }
                break;
            case 'TaxAmount':
                if($type == "UPDATE_COLOMN"){
                    return Funbase::price_format($data['duty_fee']);
                }else{
                    return sprintf('%012s', $data['duty_fee']*100);
                }
                break;
            case 'Freight':
                if($type == "UPDATE_COLOMN"){
                    return Funbase::price_format($data['express_fee']);
                }else{
                    return sprintf('%012s', $data['express_fee']*100);
                }
                break;
            case 'InsuredFee':
                if($type == "UPDATE_COLOMN"){
                    return Funbase::price_format($data['coupon_fee']);
                }else{
                    return sprintf('%012s', $data['coupon_fee']*100);
                }
                break;
            case 'Mobile':
                return '';
                break;
            case 'Email':
                return '';
                break;
            case 'BizTypeCode':
                return '2';
                break;
            case 'OriOrderNo':
                return $data['order_number'];
                break;
            case 'PayNo':
                if(self::ENV == 'dev'){
                    return '0P3000000000000';
                }
                return 'BZZ000000000000';
                break;
            case 'PaymentType':
                return '1';
                break;
            case 'IEType':
                return 'I';
                break;
            case 'OrganType':
                return '1';
                break;
            case 'CustomsCode':
                return '100016';
                break;
            case 'PortCode':
                return '3105';
                break;
            case 'CIQOrgCode':
                return '380000';
                break;
            case 'BusinessType':
                return 'B2C';
                break;
            case 'CreTime':
                return date("YmdHis", $data['pay_time'] - 60);
                break;
            case 'GetResultTime':
                return date("YmdHis", $data['pay_time'] + 60);
                break;
            case 'OrderStatus':
                return 0;
                break;
            case 'BeginTime':
                return $data['start_time'];
                break;
            case 'EndTime':
                return $data['end_time'];
                break;
            default:
                return '';
                break;
        }
        return '';
    }
    /**
     * 更新数据的时候获得updateData
     */
    public function getUpdateItem($type, $data = []){
        $updata_xml = '';
        foreach (self::$XML_UPDATE_COLOMN as $key => $item) {
            $updata_xml .= $item.':'.$this->getXmlItem('UPDATE_COLOMN', $item, $data).'|';
        }
        return substr($updata_xml,0,strlen($updata_xml)-1);
    }
    /**
     * 添加流程表
     */
    public static function insertBBProcess($order_number, $sequence, $content, $create_time = 0){
        if($create_time == 0) $create_time = time();
        if($sequence > 1){
            //上一个状态
            $pre_sequence = $sequence - 1;
            $pre_process = KllBBProcessTable::getInstance()->findOneByOrderNumberAndSequence($order_number, $pre_sequence);
            if(!empty($pre_process)){
                $pre_process_status = $pre_process->getStatus();
                if($pre_process_status != 1){
                    return false;
                }
            }
            $current_process = KllBBProcessTable::getInstance()->findOneByOrderNumberAndSequence($order_number, $sequence);
            if(!empty($current_process)){
                return false;
            }
        }else{
            $pre_process = KllBBProcessTable::getInstance()->findOneByOrderNumberAndSequence($order_number, 1);
            if(!empty($pre_process)){
                return false;
            }
        }

        $process = new KllBBProcess();
        $process->setOrderNumber($order_number)->setSequence($sequence)->setContent($content)->setStatus(1)->setCreateTime($create_time)->setUpdateTime(time())->save();
        return true;

    }
   
}