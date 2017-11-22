<?php
/**
 * guanyiErp驱动类
 * 梁天  2015-05-22
 */
class guanyiErpDriver extends  ErpService  {

    private $orderData = null; //订单记录
    private $export_type = null; //1导出主表  2导出附表
    private $mainOrderAttr = null; //主订单附表数据
    private $mainOrder = null;  //主订单数据
    private $orderAttr = null; //附订单数据
    private $express_type = NULL; //快递类型
    //redis 缓存前缀
    private $cache_key_prefix = 'kaluli.order.import';


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
        if(!in_array($extend,array('.csv')))  throw new sfException('文件格式错误');
        $tmp_name = $_FILES['file_csv']['tmp_name'];
        $file = fopen($tmp_name,'r');
        $i = 1;

        while ($data = fgets($file,1000)) { //每次读取CSV里面的一行内容
            if($i != 1) {
                $data = mb_convert_encoding($data,"utf-8","gbk");
                $tmp = explode(',',$data);
                foreach($tmp as $k=>$v) {
                   if(substr($v,0,1) == '"') $v = $tmp[$k] = trim(substr($v,1));
                   if(substr($v,-1) == '"') $tmp[$k] = trim(substr($v,0,strlen($v)-1));
                }
                $goods_list[] = $tmp;
                $_tmp = null;
            }
            $i++;
        }
        fclose($file);
        unlink($tmp_name); //删除文件

        $_cache_key = $this->cache_key_prefix;
        //实例化缓存
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        //先删除以前的
        $redis->del($_cache_key);
        $_number = 0;
       foreach ($goods_list as $k=>$v){
           if($v[5] != $this->shop_name) continue;
           $_number++;
           $line = array();
           $line['shop_name'] = $v[5];  //微信店铺
           $line['erp_order'] = $v[0]; //SDO1495995584
           $line['express_number'] = $v[1]; //100357198042
           $line['order_number'] = $v[2];  //  1505262296216328-138;1505209439622778-77 多个 ；  分割
           $line['set_time']= $v[3]; //2015/05/26 14:20:25
           $line['express_cpmpany'] = $v[4]; //圆通速递
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
                }

            }
        }
    }


    public function down() {
        $_order_type_fun = '_down_'.$this->export_type;
        if (method_exists($this, $_order_type_fun)) {
            $this->$_order_type_fun();
        } else {
            throw new sfException("未知导出类型。");
        }
    }


    //导出子订单
    private function _down_2() {
        $this->_importData(array(2,3));

        /*
        foreach($this->orderData as $k=>$v) {

            echo  mb_convert_encoding('gbk','utf-8',',"'.(string)$v['_ATTR_']['code'].'"');
            echo '<br />';
        }
        exit();



        $a = "0111";
        echo $a.'<br />';
        echo  ',"'.hexdec($a).'"';
        exit();
        */

        $filename = "订单详明细".date('Ymd').".csv ";
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');

        //导出 标题
        $str = "订单编号,标题,价格,购买数量,外部系统编号,规格名称,套餐信息,备注,订单状态,商家编码\n";
        $str = mb_convert_encoding($str,'gbk','utf-8');


        //导出内容
        if(!empty($this->orderData)) {
            foreach($this->orderData as $k=>$v) {
                $str .= mb_convert_encoding('"'.$v['order_number'].'-'.$v['id'].'"','gbk','utf-8');  //编号
                $str .= mb_convert_encoding(',"'.$this->isShow($v['title'],'',true).'"','gbk','utf-8');  //标题
                $str .= mb_convert_encoding(',"'.$v['price'].'"','gbk','utf-8'); // 价格
                $str .= mb_convert_encoding(',"'.$this->isShow($v['number'],1).'"','gbk','utf-8');   //购买数量
                $str .= mb_convert_encoding(',"'.(string)$v['_ATTR_']['code'].'"','gbk','utf-8'); //商家编码
                $str .= mb_convert_encoding(',"'.KaluliOrderAttrTable::importFormatAttr($v['_ATTR_']['attr'],'txt').'"','gbk','utf-8');   //规格名称
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //套餐信息
                $str .= mb_convert_encoding(',"'.$this->isShow($this->mainOrderAttr[$v['order_number']]['remark'],'',true).'"','gbk','utf-8');  //备注
                $str .= mb_convert_encoding(',"买家已付款，等待卖家发货"','gbk','utf-8');  //订单状态
                $str .= mb_convert_encoding(',"'.(string)$v['_ATTR_']['code'].'"','gbk','utf-8'); //商家编码
                $str .=  "\n";
            }
        }
        echo $str;
        exit();
        
    }







    //导出主订单
    private function _down_1() {
        error_reporting(E_ALL^E_NOTICE^E_WARNING);

        $this->_importData(array(2));


        /*
        foreach($this->mainOrderAttr as $k=>$v) {
            $a = KaluliMainOrderAttrTable::getInstance()->find($v['id']);
            $a->setRemark("卡路里测试订单");
            $a->save();
        }
        foreach($this->orderData as $k=>$v) {
            $a = KaluliOrderTable::getInstance()->find($v['id']);
            $a->setTitle("卡路里测试商品01");
            $a->save();
        }
        FunBase::myDebug($this->orderData);
      */


        $filename = "订单详主表".date('Ymd').".csv ";
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');


        //导出 标题
        $str = "订单编号,买家会员名,买家支付宝账号,买家应付货款,买家应付邮费,买家支付积分,总金额,返点积分,支付金额,买家实际支付积分,";
        $str .= "订单状态,买家留言,收货人,收货地址,运送方式,联系电话,联系手机,订单创建时间,订单付款时间,宝贝标题,宝贝种类,物流单号,物流公司,";
        $str .= "订单备注,宝贝总数量,店铺Id,店铺名称,订单关闭原因,卖家服务费,买家服务费,发票抬头,是否手机订单,分阶段订单信息,定金排名,";
        $str .= "修改后的sku,修改后的收货地址,异常信息\n";
        $str = mb_convert_encoding($str,'gbk','utf-8');
        //导出内容
        if(!empty($this->orderData)) {
            foreach($this->orderData as $k=>$v) {
                $str .= mb_convert_encoding('"'.$v['order_number'].'-'.$v['id'].'"','gbk','utf-8');
                //$str .= mb_convert_encoding(',"卡路里用户"','gbk','utf-8');
                $str .= mb_convert_encoding(',"'.$this->isShow($v['hupu_username'],'',true).'"','gbk','utf-8');
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //支付宝
                $str .= mb_convert_encoding(',"'.FunBase::price_format_all($v['price']).'"','gbk','utf-8');  //货款
                $str .= mb_convert_encoding(',"'.FunBase::price_format_all($v['express_fee']).'"','gbk','utf-8');  //邮费
                $str .= mb_convert_encoding(',"0"','gbk','utf-8');  //积分
                $str .= mb_convert_encoding(',"'.FunBase::price_format_all($v['total_price']).'"','gbk','utf-8');  //总金额
                $str .= mb_convert_encoding(',"0"','gbk','utf-8'); //返点积分
                $str .= mb_convert_encoding(',"'.FunBase::price_format_all($v['total_price']).'"','gbk','utf-8');  //支付金额
                $str .= mb_convert_encoding(',"0"','gbk','utf-8');  //实际支付积分
                $str .= mb_convert_encoding(',"买家已付款，等待卖家发货"','gbk','utf-8');  //订单状态
                $str .= mb_convert_encoding(',"'.$this->isShow($this->mainOrderAttr[$v['order_number']]['remark'],'',true).'"','gbk','utf-8');  //买家留言
                $str .= mb_convert_encoding(',"'.$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['name'],'',true).'"','gbk','utf-8');  //收货人
                $str .= mb_convert_encoding(',"'.
                    $this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['region'],'',true)." - ".$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['street'],'',true).'"','gbk','utf-8');  //收货地址
                $str .= mb_convert_encoding(',"快递"','gbk','utf-8');  //运送方式
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //联系电话
                $str .= mb_convert_encoding(',"'.$this->isShow($this->mainOrderAttr[$v['order_number']]['address_attr']['mobile']).'"','gbk','utf-8');  //联系手机
                $str .= mb_convert_encoding(',"'.$v['created_at'].'"','gbk','utf-8');  //订单创建时间
                $str .= mb_convert_encoding(',"'.$v['pay_time'].'"','gbk','utf-8');  //订单付款时间
                $str .= mb_convert_encoding(',"'.$v['title'].'"','gbk','utf-8');  //标题
                $str .= mb_convert_encoding(',"1"','gbk','utf-8');  //宝贝种类
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //物流单号
                $str .= mb_convert_encoding(',"'.$this->express_type.'"','gbk','utf-8');  //物流公司
                $str .= mb_convert_encoding(',"卡路里销售仓"','gbk','utf-8');  //订单备注
                $str .= mb_convert_encoding(',"'.$this->isShow($v['number'],1).'"','gbk','utf-8');  //宝贝总数
                $str .= mb_convert_encoding(',"0"','gbk','utf-8');  //店铺id
                $str .= mb_convert_encoding(',"卡路里商城"','gbk','utf-8');  //店铺名称
                $str .= mb_convert_encoding(',"订单未关闭"','gbk','utf-8');  //订单关闭原因
                $str .= mb_convert_encoding(',"0"','gbk','utf-8');  //卖家服务费
                $str .= mb_convert_encoding(',"0"','gbk','utf-8');  //买家服务费
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //发票抬头
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //是否手机订单
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //分阶段订单信息
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //定金排名
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //修改后的sku
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //修改后的收货地址
                $str .= mb_convert_encoding(',""','gbk','utf-8');  //异常信息
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





}