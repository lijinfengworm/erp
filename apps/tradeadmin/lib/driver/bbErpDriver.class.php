<?php
/**
 * 卡路里BtoB
 * kworm  2015-05-22
 */
class bbErpDriver extends  ErpService  {

    private $orderData = null; //订单记录
    private $export_type = null; //1导出主表  2导出附表
    private $mainOrderAttr = null; //主订单附表数据
    private $mainOrder = null;  //主订单数据
    private $orderAttr = null; //附订单数据
    private $express_type = NULL; //快递类型
    //redis 缓存前缀
    private $cache_key_prefix = 'kaluli.order.import.bb';
    //redis 存入每个批次的订单
    private $cache_key_order = 'kaluli.order.bb';

    //订单状态
    public  $orderStatusFormat = array(
        0=>'买家已付款，等待卖家发货',
        1=>'已发货',
        2=>'订单完成',
        3=>'退货处理中',
        4=>'待用户发货',
        5=>'卡路里待收货',
        6=>'已退货',
        7=>'订单关闭',
        8=>'用户取消',
        9=>'卡路里取消',
        10=>'拒绝退货',
    );

    private $shop_name = '卡路里商城';

    public function __construct($options) {
        $this->options = $options;
        //生成记录
        $this->export_type = sfContext::getInstance()->getRequest()->getParameter('order_type');
        //快递类型
        $this->express_type = sfContext::getInstance()->getRequest()->getParameter('express_type');

    }

    //订单导入
    public function importOrder($file) {
        header('Content-Type:text/html;charset=utf-8');
        $obj = KllOrderFileTable::getInstance()->findOneByFile($file);
        $file_id = $obj->getId();
        empty($file_id) && $file_id = 0;
        //获得文件批次号
        $batch = $obj->getBatch();
        $goods_list = array();
        $filename = $file;
        # 获取扩展名
        $extend = strrchr ($filename,'.');
        //if(!in_array($extend,array('.csv')))  throw new sfException('文件格式错误');
        //if(!in_array($extend,array('.xls')))  throw new sfException('文件格式错误');
        $tmp_name = $file;
        
        $goods_list = $this->readexcel($tmp_name,$extend,'default');
        // unlink($tmp_name); //删除文件
        $_cache_key = $this->cache_key_prefix;
        


        //实例化缓存
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        //先删除以前的
        $redis->del($_cache_key);
        $_number = 0;
        $order = [];
        foreach ($goods_list as $k=>$v){
            $_number++;
            $line = array();
            $line['file_id'] = $file_id;
            $line['batch'] = $batch;
            $line['order_type'] = isset($v['订单类型']) ? str_replace('`','', $v['订单类型']) : ''; 
            //主订单及附件表的数据
            $line['pay_time'] = isset($v['日期']) ? str_replace('`','', $v['日期']) : '';  //下单日期
            $line['origin_order_number'] = isset($v['订单编号']) ? str_replace('`','', $v['订单编号']) : ''; //SDO1495995584   

            $line['total_price'] = isset($v['总价']) ? str_replace('`','', $v['总价']) : ''; //总价
            $line['payer'] = isset($v['支付人']) ? str_replace('`','', $v['支付人']) : ''; //总价
            $line['source'] = isset($v['订单来源']) ? str_replace('`','', $v['订单来源']) : ''; //总价
            $line['real_price'] = isset($v['实付价']) ? str_replace('`','', $v['实付价']) : ''; //实付价
            $line['push_price'] = isset($v['推送价']) ? str_replace('`','', $v['推送价']) : ''; //推送价
            $line['count'] = isset($v['数量']) ? str_replace('`','', $v['数量']): ''; //数量
            $line['province'] = isset($v['省份']) ? str_replace('`','', $v['省份']) : ''; //省份
            $line['city'] = isset($v['城市']) ? str_replace('`','', $v['城市']) : ''; //城市
            $line['area'] = isset($v['县区']) ? str_replace('`','', $v['县区']) : ''; //区县
            $line['address'] = isset($v['地址']) ? str_replace('`','', $v['地址']) : ''; //地址
            $line['receiver'] = isset($v['收件人']) ? str_replace('`','', $v['收件人']) : ''; //收件人
            $line['account'] = isset($v['账号']) ? str_replace('`','', $v['账号']) : ''; //账号
            $line['real_name'] = isset($v['姓名']) ? str_replace('`','', $v['姓名']) : ''; //姓名
            $line['code'] = isset($v['邮编']) ? str_replace('`','', $v['邮编']) : ''; //邮编
            $line['postal_code'] = isset($v['邮编']) ? str_replace('`','', $v['邮编']) : ''; //订单类型
            $line['express_fee'] = isset($v['运费']) ? str_replace('`','', $v['运费']) : ''; //运费
            $line['duty_fee'] = isset($v['税费']) ? str_replace('`','', $v['税费']) : ''; //税费
            $line['card_code'] = isset($v['身份证']) ? str_replace('`','', $v['身份证']) : ''; //税费
            $line['mobile'] = isset($v['手机号码']) ? str_replace('`','', $v['手机号码']) : ''; //手机
            //子订单数据
            $line['product_id'] = isset($v['skuID']) ? str_replace('`','', $v['skuID']) : ''; //skuid
            $line['goods_id'] = isset($v['goodsID']) ? str_replace('`','', $v['goodsID']) : ''; //商品id
            $line['price'] = isset($v['单价']) ? str_replace('`','', $v['单价']) : ''; //单价
            $line['name'] = isset($v['商品名称']) ? str_replace('`','', $v['商品名称']) : ''; //商品名称
            $line['description'] = isset($v['商品描述']) ? str_replace('`','', $v['商品描述']) : ''; //商品描述
            $line['child_order_number'] = isset($v['子订单']) ? str_replace('`','', $v['子订单']) : ''; //子订单
            $line['cashier'] = isset($v['收款人']) ? str_replace('`','', $v['收款人']) : ''; //收款人
            $line['product_code'] = isset($v['商品货号']) ? str_replace('`','', $v['商品货号']) : ''; //商品货号
            //写入到缓存里面
            $redis->hset($_cache_key,'key'.$_number,serialize($line));
        }
        
        $_url = "@default?module=kaluli_bb&action=taskOrderImport";
        $_query = "&cache_key=".$_cache_key.'&count='.$_number.'&dosub=1&usenum=0&interval=1';
        // return $_url.$_query;
        FunBase::redirect(sfContext::getInstance()->getController()->genUrl($_url.$_query));

        exit();

    }
    //erp系统商品导入
    public function importSku($file, $uid) {
        header('Content-Type:text/html;charset=utf-8');
       
        $goods_list = array();
        $filename = $file;
        # 获取扩展名
        $extend = strrchr ($filename,'.');
        //if(!in_array($extend,array('.csv')))  throw new sfException('文件格式错误');
        //if(!in_array($extend,array('.xls')))  throw new sfException('文件格式错误');
        $tmp_name = $file;
        
        $goods_list = $this->readexcel($tmp_name,$extend,'default');
        // unlink($tmp_name); //删除文件
        $_cache_key = $this->cache_key_prefix;
        

        //实例化缓存
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        //先删除以前的
        $redis->del($_cache_key);
        $_number = 0;
        $order = [];
        $line = array();
        foreach ($goods_list as $k=>$v){
            $_number++;
            $line[$k]['goods_id'] = isset($v['商品ID']) ? str_replace('`','', $v['商品ID']) : ''; 
            $line[$k]['sku_id'] = isset($v['sku_id']) ? str_replace('`','', $v['sku_id']) : ''; 
            //主订单及附件表的数据
            $line[$k]['code_num'] = isset($v['条形码']) ? str_replace('`','', $v['条形码']) : '';  //下单日期
            $line[$k]['goods_title'] = isset($v['商品标题']) ? str_replace('`','', $v['商品标题']) : ''; //SDO1495995584   

            $line[$k]['depot'] = isset($v['仓库']) ? str_replace('`','', $v['仓库']) : ''; //总价
            $line[$k]['channel'] = isset($v['渠道']) ? str_replace('`','', $v['渠道']) : ''; //总价
            $line[$k]['standard_price'] = isset($v['标准价']) ? str_replace('`','', $v['标准价']) : ''; //总价
            $line[$k]['cost_price'] = isset($v['成本价']) ? str_replace('`','', $v['成本价']) : ''; //实付价
            $line[$k]['add_user'] = $uid; //实付价
            //写入到缓存里面
        }
        
        KaluliErpService::getInstance()->insertSkuPrice($line);
        
        FunBase::redirect(sfContext::getInstance()->getController()->genUrl("@default?module=kaluli_sku&action=index"));

        exit();

    }
    


    //设置快递名称
    //1申通 2顺丰 3EMS 4圆通 5韵达 6中通 7天天 8汇通 9宅急送 10其他
    //$_orderData->setDomesticExpressType($this->setExpressType($express_cpmpany));
    private function setExpressType($express_type) {
       foreach(KaluliOrder::$EXPRESS_TYPE as $k=>$v) {
           if(preg_match("/.*".$v.".*/",$express_type)) {
                return $k;
           }
       }
        return 0;
    }



    public function down($orderData) {
        ini_set('memory_limit', '256M');
        $this->orderData = $orderData;

        $filename = "订单详明细".date('YmdHis').".xls";

           $objPHPExcel=new PHPExcel();
           $objPHPExcel->getProperties()->setCreator('hupu-kaluli')
               ->setLastModifiedBy('hupu')
               ->setTitle('kaluli_order')
               ->setSubject('kaluli_order')
               ->setDescription('kaluli_order')
               ->setKeywords('kaluli_order')
               ->setCategory('kaluli_order');
           $objPHPExcel->setActiveSheetIndex(0)
               ->setCellValue('A1','订单号')
               ->setCellValue('B1',"原订单号")
               ->setCellValue('C1',"支付单号")
               ->setCellValue('D1','下单人姓名')
               ->setCellValue('E1','下单人外部账号')
               ->setCellValue('F1','收货人姓名')
               ->setCellValue('G1','支付金额')
               ->setCellValue('H1','运费')
               ->setCellValue('I1','税费')
               ->setCellValue('J1','收货人地址')
               ->setCellValue('K1','完成时间')
               ->setCellValue('L1','订单状态')
               ->setCellValue('M1','订单来源')
               ->setCellValue('N1','物流公司')
               ->setCellValue('O1','物流单号')
               ->setCellValue('P1','操作人')
               ->setCellValue('Q1','手机号码')
               ->setCellValue('R1','证件号码')
               ->setCellValue('S1','证件号码')
               ;
          $i=2;
       if(empty($this->orderData)) FunBase::myDebug('没数据，别看了。爱你哟。么么哒！');

       foreach($this->orderData as $k=>$v ) {
           $objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,$v['order_number'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i,$v['origin_order_number'],PHPExcel_Cell_DataType::TYPE_STRING); //商家编码
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$i,$v['flow_number'],PHPExcel_Cell_DataType::TYPE_STRING);  //订单状态
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$i,$v['real_name'],PHPExcel_Cell_DataType::TYPE_STRING); //买家ID
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$i,$v['account'],PHPExcel_Cell_DataType::TYPE_STRING); //子订单ID
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$i,$v['receiver'],PHPExcel_Cell_DataType::TYPE_STRING); //卖家昵称
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$i,$v['real_price'],PHPExcel_Cell_DataType::TYPE_STRING);  //商品名称
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$i,$v['express_fee'],PHPExcel_Cell_DataType::TYPE_STRING) ; //规格名称
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$i,$v['duty_fee'],PHPExcel_Cell_DataType::TYPE_STRING);  //商品价格
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$i,$v['address'],PHPExcel_Cell_DataType::TYPE_STRING);  //商品数量
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$i,date("Y-m-d", $v['update_time']),PHPExcel_Cell_DataType::TYPE_STRING);  //商品总价
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$i,KaluliBBService::$_order_status[$v['status']],PHPExcel_Cell_DataType::TYPE_STRING);  //运费
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('M'.$i,KaluliBBService::$_order_source[$v['source']],PHPExcel_Cell_DataType::TYPE_STRING);  //优惠信息
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('N'.$i,$v['logistic_number'],PHPExcel_Cell_DataType::TYPE_STRING);  //总价
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('O'.$i,FunBase::getDomesticExpress($v['logistic_type']),PHPExcel_Cell_DataType::TYPE_STRING);  //总价
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('P'.$i,TrdAdminUserTable::getOneByhpId($v['uid'],'username'),PHPExcel_Cell_DataType::TYPE_STRING);  //总价
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('Q'.$i,$v['mobile'],PHPExcel_Cell_DataType::TYPE_STRING);  //手机号码
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('R'.$i,$v['card_code'],PHPExcel_Cell_DataType::TYPE_STRING);  //手机号码
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('S'.$i,$v['push_price'],PHPExcel_Cell_DataType::TYPE_STRING);  //手机号码

           $i++;
       }

       $objPHPExcel->getActiveSheet()->setTitle("Sheet1");
       $objPHPExcel->setActiveSheetIndex(0);
       header('Content-Type: application/vnd.ms-excel');
       header('Content-Disposition: attachment;filename='.$filename);
       header('Cache-Control: max-age=0');
       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
       $objWriter->save('php://output');
        exit();
    }




   public function readexcel( $filePath,$file_type,$type='default') {
       if( $file_type=='.xlsx'||$file_type=='.xls' ){
           $objPHPExcel = PHPExcel_IOFactory::load($filePath);
       }else if( $file_type=='.csv' ){
           $objReader = PHPExcel_IOFactory::createReader('CSV')
               ->setDelimiter(',')
               ->setInputEncoding('GBK') //不设置将导致中文列内容返回boolean(false)或乱码
               ->setEnclosure('"')
               ->setLineEnding("\r\n")
               ->setSheetIndex(0);
            file_put_contents('tmp.csv',file_get_contents($filePath));
            $objPHPExcel = $objReader->load('tmp.csv');
            @unlink('tmp.csv');

       }else{
           die('Not supported file types!');
       }
       $sheet = $objPHPExcel->getSheet(0);

       //获取行数与列数,注意列数需要转换
       $highestRowNum = $sheet->getHighestRow();
       $highestColumn = $sheet->getHighestColumn();
       $highestColumnNum = PHPExcel_Cell::columnIndexFromString($highestColumn);

      //取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
       $filed = array();
       for($i=0; $i<$highestColumnNum;$i++){
           $cellName = PHPExcel_Cell::stringFromColumnIndex($i).'1';
           $cellVal = $sheet->getCell($cellName)->getValue();//取得列内容
           $filed []= $cellVal;
       }

      //开始取出数据并存入数组
       $data = array();
       for($i=2;$i<=$highestRowNum;$i++){//ignore row 1
           $row = array();
           $num = 1;
           for($j=0; $j<$highestColumnNum;$j++){
               $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
               $cellVal = $sheet->getCell($cellName)->getValue();
               if($type == 'default') {
                   $row[ $filed[$j] ] = $cellVal;
               } else if($type == 'small') {
                   $row[ $num++ ] = $cellVal;
               }
           }
           $data []= $row;
       }
       return $data;
    }


}