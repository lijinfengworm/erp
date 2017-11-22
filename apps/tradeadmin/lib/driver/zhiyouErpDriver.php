<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2016/3/15
 * Time: 15:57
 */

class zhiyouErpDriver extends ErpService {

    private $orderData = null; //订单记录
    private $export_type = null; //1导出主表  2导出附表
    private $mainOrderAttr = null; //主订单附表数据
    private $mainOrder = null;  //主订单数据
    private $orderAttr = null; //附订单数据
    private $express_type = NULL; //快递类型

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




    private $depot_type =5; //直邮的

    //redis 缓存前缀
    private $cache_key_prefix = 'kaluli.order.import.zhiyou';

    public function __construct($options)
    {
        $this->options = $options;
        //生成记录
        $this->export_type = sfContext::getInstance()->getRequest()->getParameter('order_type');
        //快递类型
        $this->express_type = sfContext::getInstance()->getRequest()->getParameter('express_type');

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

        //获取直邮
        $_select_map['where']['depot_type'] = 'depot_type='.$this->depot_type;




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
                //根据商品goodId获取条形码内容
                $skuCode = KaluliItemSkuTable::getOne($v['goods_id'],true,'code');

                //调用字典服务获取
                $serviceRequest = new kaluliServiceClient();
                $serviceRequest->setMethod('dictionary.getDictionary');
                $serviceRequest->setVersion('1.0');
                $serviceRequest->setApiParam('type', sfConfig::get("app_doc_zhiyou"));
                $response = $serviceRequest->execute();
                if( false === $response->hasError() ) {
                    $tmp = $response->getData();
                    $doc = $tmp['data'];
                    $this->orderData[$k]['sku_code'] = $skuCode;
                    foreach($doc as $value) {
                        if($value['str_value'] == $skuCode) {
                            $this->orderData[$k]['declare_id'] = $value['kll_code'];
                            $this->orderData[$k]['declare_price'] = $value['int_value'];
                            if($value['is_special'] == 1){
                                $this->orderData[$k]['is_special'] = 1;
                            }
                        }
                    }
                    if(!array_key_exists("declare_id",$this->orderData[$k])){
                        $this->orderData[$k]['declare_id'] = "";
                    }
                    if(!array_key_exists("declare_price",$this->orderData[$k])){
                        $this->orderData[$k]['declare_price'] = "";
                    }
                }
                //根据code获取对应对应的

            }
        }
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
            ->setCellValue('B1',"买家昵称")
            ->setCellValue('C1',"收货人")
            ->setCellValue('D1','手机号')
            ->setCellValue('E1','支付id')
            ->setCellValue('F1','身份证')
            ->setCellValue('G1','收货人地址')
            ->setCellValue('H1','省')
            ->setCellValue('I1','市')
            ->setCellValue('J1','区')
            ->setCellValue('K1','邮编')
            ->setCellValue('L1','商品id')
            ->setCellValue('M1','商品价格')
            ->setCellValue('N1','商品数量')
            ->setCellValue("O1",'是否特殊处理');
        $i=2;
        if(empty($this->orderData)) FunBase::myDebug('没数据，别看了。爱你哟。么么哒！');

        foreach($this->orderData as $k=>$v ) {
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,$v['order_number'].'-'.$v['id']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i,$this->isShow($v['hupu_username'],'',true)); //买家昵称
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['name']));  //收货人
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['mobile'])); //手机号
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$i,$v['ibilling_number']); //支付id
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['identity_number'])); //身份证
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['region'])." ".$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['street']));  //收货人地址
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['province'])) ; //省
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['city']));  //市
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['area']));  //区
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$i,$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['postcode']));  //邮编
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$i,$v['declare_id']);  //商品id
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('M'.$i,$v['declare_price'],PHPExcel_Cell_DataType::TYPE_STRING);  //商品价格
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('N'.$i,$v['number'],PHPExcel_Cell_DataType::TYPE_STRING);  //商品数量
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('O'.$i,isset($v['is_special'])?"特殊处理":"");
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



    private function isShow($text,$default = '',$is_escape = false) {
        if(empty($text) || $text == '') return $default;
        if($is_escape) {
            $text =  str_replace(',', ' ', $text);
            $text =  str_replace('"', ' ', $text);
            $text =  str_replace("'", ' ', $text);
        }
        return $text;
    }





}