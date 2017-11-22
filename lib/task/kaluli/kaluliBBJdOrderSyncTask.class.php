<?php
use PhpAmqpLib\Connection\AMQPConnection;
class kaluliBBJdOrderSyncTask extends sfBaseTask
{
    CONST WEB_SITE = '//www.kaluli.com';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
            new sfCommandOption('shop', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', 'JDQT'),
        ));

        $this->namespace        = 'kaluli';
        $this->name             = 'BBJdOrderSync';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [kaluli:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    /**
     * symfony默认执行函数
     * params: arguments, options
     * 把订单提交到快递100
     */
    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        while(1) {
            //判断内存是否超出
            $this->checkMemory();
            $this->syncBBJdOrder($options);
            sleep(10);
            break;
        }
        exit;
    }

   
    /**
     * 京东订单轮询
     * 异常订单
     */
    public function syncBBJdOrder($options){
        if(empty($options)){
            throw new Exception("Error Processing Request", 1);
        }
        $start_time = date("Y-m-d H:i:s",time()-2*24*60*60);
        $end_time = date("Y-m-d H:i:s", time()+2*24*60*60);
        $buy_param_json_data = [
            'end_date'      => $end_time,
            'start_date'    => $start_time,
            'page'          => 1,
            'page_size'     =>  80,
            'order_state'   => "WAIT_SELLER_STOCK_OUT",
            // 'date_type'      => 1,
            // 'optional_fields'    => null
        ];

        $buy_param_json = json_encode($buy_param_json_data);
        $sign_data = [
            '360buy_param_json' =>  $buy_param_json,
            'app_key'           =>  JdSynService::APPKEY,
            'access_token'      =>  JdSynService::$access_token,
            'method'            =>  '360buy.order.search',
            'timestamp'         =>  date("Y-m-d H:i:s"),
            // 'v'                  =>  '2.0'
        ];
        $sign = JdSynService::getInstance()->createSign($sign_data);

        $postfield = [
            'timestamp'     => date("Y-m-d%20H:i:s"),
            'method'        => '360buy.order.search',
            'app_key'       => JdSynService::APPKEY,
            'access_token'  => JdSynService::$access_token,
            '360buy_param_json' =>  urlencode($buy_param_json),
            'v'             => '2.0',
            'sign'          => $sign,
        ];

        $string = self::getUrlString($postfield);
        $api_url = sfConfig::get('app_jd_open_api_url').'/routerjson?'.$string;
        $res = FunBase::getcurl($api_url);
        $orderObj = json_decode($res);

        if(isset($orderObj)){
            $order_response= $orderObj->order_search_response;
            if(!empty($order_response)){
                if($order_response->code == '0'){
                    $order = $order_response->order_search;
                    $main_order = $order->order_info_list;
                    if(!empty($main_order)){
                        foreach ($main_order as $key => $ord_info) {
                            //$log = new KllBBOrderLog();
                            //$log->setType(10)->setOrderNumber($order_number)->setContent($res)->setCreatTime(time())->setUpdateTime(time())->save();
                            $ret = JdSynService::getInstance()->sync($ord_info, $options);
                        }
                    }
                }
                
            }
        }
        //写入日志
        $message = [
            'author'        =>  '000000',
            'order_number'  =>  '',
            'type'          =>  1,
            'body'          =>  ['京东同步查询单号操作', $orderObj]
        ];
        logKaluliService::writeLog($message);

        return true;
    }
    private static function getUrlString($postfield){
        $string = '';
        if(is_array($postfield)){
            foreach ($postfield as $key => $field) {
                $string .= $key.'='.$field.'&';
            }
        }
        return $string;
    }
    private  function checkMemory() {
        $nowmem = (int)(memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }


}
