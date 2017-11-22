<?php
/**
 * E店宝驱动雷
 * 梁天  2015-05-22
 */
class edianbaoErpDriver extends  ErpService  {

    private $orderData = null; //订单记录
    private $export_type = null; //1导出主表  2导出附表
    private $mainOrderAttr = null; //主订单附表数据
    private $mainOrder = null;  //主订单数据
    private $orderAttr = null; //附订单数据
    private $express_type = NULL; //快递类型
    //redis 缓存前缀
    private $cache_key_prefix = 'kaluli.order.import.edb';


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
    public function importOrder() {
        header('Content-Type:text/html;charset=utf-8');
        $goods_list = array();
        if(empty($_FILES['file_csv']['size']) || empty($_FILES['file_csv']['name'])) throw new sfException('请上传文件！');
        $filename = $_FILES['file_csv']['name'];
        # 获取扩展名
        $extend = strrchr ($filename,'.');
        //if(!in_array($extend,array('.csv')))  throw new sfException('文件格式错误');
        //if(!in_array($extend,array('.xls')))  throw new sfException('文件格式错误');
        $tmp_name = $_FILES['file_csv']['tmp_name'];

        $goods_list = $this->readexcel($tmp_name,$extend,'default');
        unlink($tmp_name); //删除文件

        $_cache_key = $this->cache_key_prefix;

        //实例化缓存
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        //先删除以前的
        $redis->del($_cache_key);
        $_number = 0;

       foreach ($goods_list as $k=>$v){
           if($v['店铺名称'] != '卡路里商城' || empty($v['快递单号'])) continue;
           $_number++;
           $line = array();
           $line['shop_name'] = $v['店铺名称'];  //微信店铺
           $line['erp_order'] = $v['订单编号']; //SDO1495995584   erp 订单号
           $line['express_number'] = $v['快递单号']; //100357198042  快递单号
           $line['order_number'] = $v['外部平台单号'];  //  1505262296216328-138;1505209439622778-77 多个 ；  分割
           $line['set_time']= date('Y-m-d H:i:s',time()); //2015/05/26 14:20:25
           $line['express_cpmpany'] = $v['快递公司']; //圆通速递
           $line['express_cpmpany_type'] = $this->setExpressType($line['express_cpmpany']);
           //写入到缓存里面
           $redis->hset($_cache_key,'key'.$_number,serialize($line));
        }
        $_url = "@default?module=kaluli_order&action=taskOrderImport";
        $_query = "&cache_key=".$_cache_key.'&count='.$_number.'&dosub=1&usenum=0&interval=1';
        //开始导入
        echo '<html><body><form target="sendAction" action="'.sfContext::getInstance()->getController()->genUrl($_url.$_query).'" >';
        echo  "查找到  ". $_number .' 个订单需要导入。';
        echo '<button type="submit" class="gwyy_btn">开始导入</button>';
        echo  '</form>';
        echo '<iframe style="border: 1px solid #ccc;width:700px;height:360px;" name="sendAction" src="'.sfContext::getInstance()->getController()->genUrl('@default?module=kaluli_order&action=taskOrderImport').'" class="send_iframe" ></iframe>';
        echo '</body>';

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





    //解析主表 内容
    private function _importData($create_attr = array()) {
        $_map  =  $_select_map = array();
        /* 判断时间  */
        $_date_start = sfContext::getInstance()->getRequest()->getParameter('date_start');
        $_date_stop = sfContext::getInstance()->getRequest()->getParameter('date_stop');
        if(strtotime($_date_start) !== false && !empty($_date_start) && empty($_date_stop)) {
            $_select_map['where']['order_time'] = "order_time >= '".$_date_start." 00:00:00'";
        }
        if(strtotime($_date_stop) !== false && empty($_date_start) && !empty($_date_stop)) {
            $_select_map['where']['order_time'] = "order_time <= '".$_date_stop." 23:59:59'";
        }
        if(strtotime($_date_start) !== false && strtotime($_date_stop) !== false && !empty($_date_start) && !empty($_date_stop)) {
            $_select_map['where']['order_time'] = "order_time >= '".$_date_start." 00:00:00' AND ";
            $_select_map['where']['order_time'] .= "order_time <= '".$_date_stop." 23:59:59'";
        }
        /* 订单状态 */
        $_status = sfContext::getInstance()->getRequest()->getParameter('status');
        if (isset($_status)) {
            if ($_status != '') {
                $_select_map['where']['status'] = 'status = ' . (int)$_status;
            }
        }

        /* 付款状态 */
        $_pay_status = sfContext::getInstance()->getRequest()->getParameter('pay_status');
        if (isset($_pay_status)) {
            if ($_pay_status != '') {
                $_select_map['where']['pay_status'] = 'pay_status = ' . (int)$_pay_status;
            }
        }

        $_map['select'] = '*';
        if(!empty($_select_map['where'])) {
            if(!empty($_map['where'])) {
                $_map['where'] = array_merge($_select_map['where'],$_map['where']);
            } else {
                $_map['where'] = $_select_map['where'];
            }
        }
        $this->orderData = KaluliOrderTable::getAll($_map); //获取数据
        if(empty($this->orderData))  throw new sfException("没有需要导出的订单。");


        if(count($create_attr) > 0) {
            //获取附加内容
            foreach ($this->orderData as $k => $v) {

                //2  create main order attr
                if (in_array(2, $create_attr)) {
                    //判断有没有获取过当前main order attr
                    if (empty($this->mainOrderAttr[$v['order_number']])) {
                        //获取 attr
                        $this->mainOrderAttr[$v['order_number']] = KaluliMainOrderAttrTable::getOne($v['order_number'], true);
                    }
                }

                //3  create order attr
                if (in_array(3, $create_attr)) {
                    $this->orderData[$k]['_ATTR_'] = KaluliOrderAttrTable::getOneByOrderId($v['id']);
                    //if(!empty($this->orderData[$k]['_ATTR_']['attr'])) {
                      //  $this->orderData[$k]['_ATTR_']['attr'] = json_decode($this->orderData[$k]['_ATTR_']['attr']);
                   // }
                }

            }
        }
    }















    public function down() {
        $this->_importData(array(2,3));
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
               ->setCellValue('B1',"产品条码")
               ->setCellValue('C1',"订单状态")
               ->setCellValue('D1','买家id')
               ->setCellValue('E1','子订单编号')
               ->setCellValue('F1','买家昵称')
               ->setCellValue('G1','商品名称')
               ->setCellValue('H1','产品规格')
               ->setCellValue('I1','商品单价')
               ->setCellValue('J1','商品数量')
               ->setCellValue('K1','商品总价')
               ->setCellValue('L1','运费')
               ->setCellValue('M1','购买优惠信息')
               ->setCellValue('N1','总金额')
               ->setCellValue('O1','买家购买附言')
               ->setCellValue('P1','收货人姓名')
               ->setCellValue('Q1','收货地址-省市')
               ->setCellValue('R1','收货地址-街道地址')
               ->setCellValue('S1','邮编')
               ->setCellValue('T1','收货人手机')
               ->setCellValue('U1','收货人电话')
               ->setCellValue('V1','买家选择运送方式')
               ->setCellValue('W1','卖家备忘内容')
               ->setCellValue('X1','订单创建时间')
               ->setCellValue('Y1','付款时间')
               ->setCellValue('Z1','物流公司')
               ->setCellValue('AA1','物流单号')
               ->setCellValue('AB1','发货附言')
               ->setCellValue('AC1','发票抬头')
               ->setCellValue('AD1','电子邮件');
       $i=2;
       if(empty($this->orderData)) FunBase::myDebug('没数据，别看了。爱你哟。么么哒！');

       foreach($this->orderData as $k=>$v ) {
           $objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,$v['order_number'].'-'.$v['id']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i,$v['_ATTR_']['code']); //商家编码
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$i,$this->isShow($this->orderStatusFormat[$v['status']],'',true));  //订单状态
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$i,$v['hupu_uid']); //买家ID
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$i,$v['id']); //子订单ID
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$i,$this->isShow($v['hupu_username'],'',true)); //卖家昵称
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$i,$this->isShow($v['title'],'',true));  //商品名称
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$i,KaluliOrderAttrTable::importFormatAttr($v['_ATTR_']['attr'],'txt')) ; //规格名称
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$i,$v['price'],PHPExcel_Cell_DataType::TYPE_STRING);  //商品价格
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$i,$v['number'],PHPExcel_Cell_DataType::TYPE_STRING);  //商品数量
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$i,number_format($v['price'] * $v['number'], 2, '.', ''),PHPExcel_Cell_DataType::TYPE_STRING);  //商品总价
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$i,$v['express_fee'],PHPExcel_Cell_DataType::TYPE_STRING);  //运费
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('M'.$i,0,PHPExcel_Cell_DataType::TYPE_STRING);  //优惠信息
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('N'.$i,$v['total_price'],PHPExcel_Cell_DataType::TYPE_STRING);  //总价
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('O'.$i,$this->mainOrderAttr[$v['order_number']]['remark']);  //买家留言
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('P'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['name']));  //收货人
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('Q'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['region'])) ; //收货地址
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('R'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['street']));  //收货街道
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('S'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['postcode'])) ; //收货邮编
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('T'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['mobile'])) ; //收货手机
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('U'.$i,'');  //收货电话
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('V'.$i,'快递');  //快递类型
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('W'.$i,'');  //卖家备注
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('X'.$i,$v['order_time']) ; //创建时间
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('Y'.$i,$v['pay_time']) ; //付款时间
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('Z'.$i,$this->isShow(KaluliOrderTable::$domestic_express_type[$v['domestic_express_type']],'',true));  //快递类型
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('AA'.$i,'');  //物流单号
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('AB'.$i,'');  //发货留言
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('AC'.$i,'');  //发票抬头
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('AD'.$i,'');  //电子邮件

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





    public function down1() {
        $this->_importData(array(2,3));
        $filename = "订单详明细".date('YmdHis').".csv ";

        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        /*
      header("Content-type:application/vnd.ms-excel");
      header("Content-Disposition:filename=".$filename);
      header('Expires:0');
      header('Pragma:public');

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
       ->setCellValue('B1',"产品条码")
       ->setCellValue('C1',"订单状态")
       ->setCellValue('D1','买家ID')
       ->setCellValue('E1','子订单编号')
       ->setCellValue('F1','买家昵称')
       ->setCellValue('G1','商品名称')
       ->setCellValue('H1','产品规格')
       ->setCellValue('I1','商品单价')
       ->setCellValue('J1','商品数量')
       ->setCellValue('K1','商品总价')
       ->setCellValue('L1','运费')
       ->setCellValue('M1','购买优惠信息')
       ->setCellValue('N1','总金额')
       ->setCellValue('O1','买家购买留言')
       ->setCellValue('P1','收货人姓名')
       ->setCellValue('Q1','收货地址-省市')
       ->setCellValue('R1','收货地址-街道地址')
       ->setCellValue('S1','邮编')
       ->setCellValue('T1','收货人手机')
       ->setCellValue('U1','收货人电话')
       ->setCellValue('V1','买家选择运送方式')
       ->setCellValue('W1','卖家备忘内容')
       ->setCellValue('X1','订单创建时间')
       ->setCellValue('Y1','付款时间')
       ->setCellValue('Z1','物流公司')
       ->setCellValue('AA1','物流单号')
       ->setCellValue('AB1','发货附言')
       ->setCellValue('AC1','发票抬头')
       ->setCellValue('AD1','电子邮件');
   $i=2;

   if(empty($this->orderData)) FunBase::myDebug('没数据，别看了。爱你哟。么么哒！');



   foreach($this->orderData as $k=>$v ) {
       $objPHPExcel->setActiveSheetIndex(0)
           ->setCellValue('A'.$i,$v['order_number'].'-'.$v['id'])//订单号
           ->setCellValue('B'.$i,$v['_ATTR_']['code']) //商家编码
           ->setCellValue('C'.$i,$this->isShow($this->orderStatusFormat[$v['status']],'',true))  //订单状态
           ->setCellValue('D'.$i,$v['hupu_uid']) //买家ID
           ->setCellValue('E'.$i,$v['id']) //子订单ID
           ->setCellValue('F'.$i,$this->isShow($v['hupu_username'],'',true)) //卖家昵称
           ->setCellValue('G'.$i,$this->isShow($v['title'],'',true))  //商品名称
           ->setCellValue('H'.$i,KaluliOrderAttrTable::importFormatAttr($v['_ATTR_']['attr'],'txt'))  //规格名称
           ->setCellValue('I'.$i,$v['price'])  //商品价格
           ->setCellValue('J'.$i,$v['number'])  //商品数量
           ->setCellValue('K'.$i,number_format($v['price'] * $v['number'], 2, '.', ''))  //商品总价
           ->setCellValue('L'.$i,$v['express_fee'])  //运费
           ->setCellValue('M'.$i,0)  //优惠信息
           ->setCellValue('N'.$i,$v['total_price'])  //总价
           ->setCellValue('O'.$i,$this->mainOrderAttr[$v['order_number']]['remark'])  //买家留言
           ->setCellValue('P'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['name']))  //收货人
           ->setCellValue('Q'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['region']))  //收货地址
           ->setCellValue('R'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['street']))  //收货街道
           ->setCellValue('S'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['postcode']))  //收货邮编
           ->setCellValue('T'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['mobile']))  //收货手机
           ->setCellValue('U'.$i,'')  //收货电话
           ->setCellValue('V'.$i,'快递')  //快递类型
           ->setCellValue('W'.$i,'')  //卖家备注
           ->setCellValue('X'.$i,$v['order_time'])  //创建时间
           ->setCellValue('Y'.$i,$v['pay_time'])  //付款时间
           ->setCellValue('Z'.$i,$this->isShow(KaluliOrderTable::$domestic_express_type[$v['domestic_express_type']],'',true))  //快递类型
           ->setCellValue('AA'.$i,'')  //物流单号
           ->setCellValue('AB'.$i,'')  //发货留言
           ->setCellValue('AC'.$i,'')  //发票抬头
           ->setCellValue('AD'.$i,'')  //电子邮件
       ;
       $i++;
   }

   $objPHPExcel->getActiveSheet()->setTitle("kaluli_order");
   $objPHPExcel->setActiveSheetIndex(0);
   header('Content-Type: application/vnd.ms-excel');
   header('Content-Disposition: attachment;filename='.$filename);
   header('Cache-Control: max-age=0');
   $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
   $objWriter->save('php://output');
   exit();

   */


        //导出 标题
        $str = "订单号\t产品条码\t订单状态\t买家ID\t子订单编号\t买家昵称\t商品名称\t产品规格\t商品单价\t商品数量\t商品总价\t运费\t购买优惠信息\t总金额\t买家购买留言\t收货人姓名\t收货地址-省市\t收货地址-街道地址\t邮编\t收货人手机\t收货人电话\t买家选择运送方式\t卖家备忘内容\t订单创建时间\t付款时间\t物流公司\t物流单号\t发货附言\t发票抬头\t电子邮件\n";
        $str = "订单号,产品条码,订单状态,买家ID,子订单编号,买家昵称,商品名称,产品规格,商品单价,商品数量,商品总价,运费,购买优惠信息,总金额,买家购买留言,收货人姓名,收货地址-省市,收货地址-街道地址,邮编,收货人手机,收货人电话,买家选择运送方式,卖家备忘内容,订单创建时间,付款时间,物流公司,物流单号,发货附言,发票抬头,电子邮件\n";
        $str = mb_convert_encoding($str,'gbk','utf-8');
        //导出内容
        if(!empty($this->orderData)) {
            foreach($this->orderData as $k=>$v) {
                /*
                $str .= mb_convert_encoding($v['order_number'].'-'.$v['id'],'gbk','utf-8')."\t";  //订单号
                $str .= mb_convert_encoding((string)$v['_ATTR_']['code'],'gbk','utf-8')."\t"; //商家编码
                $str .= mb_convert_encoding($this->isShow($this->orderStatusFormat[$v['status']],'',true),'gbk','utf-8')."\t";  //订单状态
                $str .= mb_convert_encoding((int)$v['hupu_uid'],'gbk','utf-8')."\t"; // 买家ID
                $str .= mb_convert_encoding($v['id'],'gbk','utf-8')."\t";   //子订单编号
                $str .= mb_convert_encoding($this->isShow($v['hupu_username'],'',true),'gbk','utf-8')."\t";//卖家昵称
                $str .= mb_convert_encoding($this->isShow($v['title'],'',true),'gbk','utf-8')."\t";//商品名称
                $str .= mb_convert_encoding(KaluliOrderAttrTable::importFormatAttr($v['_ATTR_']['attr'],'txt'),'gbk','utf-8')."\t";   //规格名称
                $str .= mb_convert_encoding($v['price'],'gbk','utf-8')."\t"; // 商品价格
                $str .= mb_convert_encoding($v['number'],'gbk','utf-8')."\t"; // 商品数量
                $str .= mb_convert_encoding($v['price'] * $v['number'],'gbk','utf-8')."\t"; // 商品总价
                $str .= mb_convert_encoding($v['express_fee'],'gbk','utf-8')."\t"; // 运费
                $str .= '0'."\t"; // 购买优惠信息
                $str .= mb_convert_encoding($v['total_price'],'gbk','utf-8')."\t"; // 商品总价
                $str .= mb_convert_encoding($this->mainOrderAttr[$v['order_number']]['remark'],'gbk','utf-8')."\t"; // 买家购买附言
                $str .= mb_convert_encoding($this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['name'],'',true),'gbk','utf-8')."\t";  //收货人
                $str .= mb_convert_encoding( $this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['region'],'',true),'gbk','utf-8')."\t";  //收货地址
                $str .= mb_convert_encoding( $this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['street'],'',true),'gbk','utf-8')."\t";  //收货人街道
                $str .= mb_convert_encoding( $this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['postcode'],'',true),'gbk','utf-8')."\t";  //邮编
                $str .= mb_convert_encoding( $this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['mobile'],'',true),'gbk','utf-8')."\t";  //收货人手机
                $str .= "\t";  //收货人电话
                $str .= mb_convert_encoding("快递",'gbk','utf-8')."\t";  //快递类型
                $str .= "\t"; //卖家备忘
                $str .= $v['order_time']."\t"; //订单创建时间
                $str .= $v['pay_time']."\t"; //付款时间
                $str .= mb_convert_encoding( $this->isShow(KaluliOrderTable::$domestic_express_type[$v['domestic_express_type']],'',true),'gbk','utf-8')."\t";  //快递类型
                $str .= "\t"; //物流单号
                $str .= "\t"; //发货留言
                $str .= "\t"; //发票抬头
                $str .= "\t"; //电子邮件
                $str .=  "\n";

                */


                $str .= mb_convert_encoding('"'.$v['order_number'].'-'.$v['id'].'"','gbk','utf-8');  //订单号
                $str .= mb_convert_encoding(',"'.(string)$v['_ATTR_']['code'].'"','gbk','utf-8'); //商家编码
                $str .= mb_convert_encoding(',"'.$this->isShow($this->orderStatusFormat[$v['status']],'',true).'"','gbk','utf-8');  //订单状态
                $str .= mb_convert_encoding(',"'.(int)$v['hupu_uid'].'"','gbk','utf-8'); // 买家ID
                $str .= mb_convert_encoding(',"'.$v['id'].'"','gbk','utf-8');   //子订单编号
                $str .= mb_convert_encoding(',"'.$this->isShow($v['hupu_username'],'',true).'"','gbk','utf-8');//卖家昵称
                $str .= mb_convert_encoding(',"'.$this->isShow($v['title'],'',true).'"','gbk','utf-8');//商品名称
                $str .= mb_convert_encoding(',"'.KaluliOrderAttrTable::importFormatAttr($v['_ATTR_']['attr'],'txt').'"','gbk','utf-8');   //规格名称
                $str .= mb_convert_encoding(',"'.$v['price'].'"','gbk','utf-8'); // 商品价格
                $str .= mb_convert_encoding(',"'.$v['number'].'"','gbk','utf-8'); // 商品数量
                $str .= mb_convert_encoding(',"'.$v['price'] * $v['number'].'"','gbk','utf-8'); // 商品总价
                $str .= mb_convert_encoding(',"'.$v['express_fee'].'"','gbk','utf-8'); // 运费
                $str .= ',0'; // 购买优惠信息
                $str .= mb_convert_encoding(',"'.$v['total_price'].'"','gbk','utf-8'); // 商品总价
                $str .= mb_convert_encoding(',"'.$this->mainOrderAttr[$v['order_number']]['remark'].'"','gbk','utf-8'); // 买家购买附言
                $str .= mb_convert_encoding(',"'.$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['name'],'',true).'"','gbk','utf-8');  //收货人
                $str .= mb_convert_encoding(',"'. $this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['region'],'',true).'"','gbk','utf-8');  //收货地址
                $str .= mb_convert_encoding(',"'. $this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['street'],'',true).'"','gbk','utf-8');  //收货人街道
                $str .= mb_convert_encoding(',"'. $this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['postcode'],'',true).'"','gbk','utf-8');  //邮编
                $str .= mb_convert_encoding(',"'. $this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['mobile'],'',true).'"','gbk','utf-8');  //收货人手机
                $str .= ',';  //收货人电话
                $str .= mb_convert_encoding(',"快递"','gbk','utf-8');  //快递类型
                $str .= ','; //卖家备忘
                $str .= ','.$v['created_at']; //订单创建时间
                $str .= ','.$v['pay_time']; //付款时间
                $str .= mb_convert_encoding(',"'. $this->isShow(KaluliOrderTable::$domestic_express_type[$v['domestic_express_type']],KaluliOrder::$_DEFAULT_EXPRESS_TYPE,true).'"','gbk','utf-8');  //快递类型
                $str .= ','; //物流单号
                $str .= ','; //发货留言
                $str .= ','; //发票抬头
                $str .= ','; //电子邮件
                $str .=  "\n";

            }
        }
        echo $str;
        exit();
    }














    private function isShow($text,$default = '',$is_escape = false) {
        if(empty($text) || $text == '') return $default;
        if($is_escape) {
            $text =  str_replace(',', ' ', $text);
            $text =  str_replace('"', ' ', $text);
            $text =  str_replace("'", ' ', $text);
        }
        return $text;
    }




   private function readexcel( $filePath,$file_type,$type='default') {
       if( $file_type=='.xlsx'||$file_type=='.xls' ){
           $objPHPExcel = PHPExcel_IOFactory::load($filePath);
       }else if( $file_type=='.csv' ){
           $objReader = PHPExcel_IOFactory::createReader('CSV')
               ->setDelimiter(',')
               ->setInputEncoding('GBK') //不设置将导致中文列内容返回boolean(false)或乱码
               ->setEnclosure('"')
               ->setLineEnding("\r\n")
               ->setSheetIndex(0);
           $objPHPExcel = $objReader->load($filePath);

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