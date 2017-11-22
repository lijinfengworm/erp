<?php
use PhpAmqpLib\Connection\AMQPConnection;
class kaluliBBKaolaOrderTask extends sfBaseTask
{
    CONST WEB_SITE = '//www.kaluli.com';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'kaluli';
        $this->name             = 'BBKaolaOrder';
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
            //$this->pollingBBKaolaOrder();
            $this->createBBKaolaOrder();
            sleep(60);
            break;
        }
        exit();
    }
    /**
     * 同步物流信息
     * 从订单表查询到物流表的同步
     */
    public function createBBKaolaOrder() {
    	//查询已发货status=1的所有订单
    	//$order = KaluliMainOrderTable::getAllOrderByStatus(2);
    	$order = KllBBMainOrderTable::getAllOrderByStatusAndSource(4, 'KL');
        if(empty($order)){
            $this->log('订单不存在');
            exit();
        }

    	foreach ($order as $key => $item) {
            if($item['syn_api'] == 2){
                KaolaSynService::getInstance()->synLogistics($item);
                $this->log('物流同步完成');
                sleep(60);
            }
    	}

        return true;
    	
    }
    /**
     * 考拉订单轮询
     * 异常订单不再同步
     */
    public function pollingBBKaolaOrder(){
        $start_time = date("Y-m-d",time()-48*60*60);
        $end_time = date("Y-m-d", time()+24*60*60);
        $sign_data = [
            'timestamp'     => date("Y-m-d H:i:s"),
            'page_size'     => 50,
            'end_time'      => $end_time,
            'start_time'    => $start_time,
            'method'        => 'kaola.order.search',
            'app_key'       =>  KaolaSynService::APPKEY,
            'page_no'       => 1,
            'order_status'  => 5,
            'date_type'     => 1,
            'access_token'  => KaolaSynService::$access_token
        ];
        $sign = KaolaSynService::getInstance()->createSign($sign_data);

        $postfield = [
            'sign'          => $sign,
            'timestamp'     => date("Y-m-d%20H:i:s"),
            'page_size'     => 50,
            'end_time'      => $end_time,
            'start_time'    => $start_time,
            'method'        => 'kaola.order.search',
            'app_key'       => KaolaSynService::APPKEY,
            'page_no'       => 1,
            'order_status'  => 2,
            'date_type'     => 1,
            'access_token'  => KaolaSynService::$access_token
        ];
        $string = KaolaSynService::getUrlString($postfield);
        $api_url = sfConfig::get('app_kaola_open_api_url').'/router?'.$string;

        $res = FunBase::getcurl($api_url);
        $orderObj = json_decode($res);
        if(isset($orderObj)){
            $order= $orderObj->kaola_order_search_response;
            if(!empty($order)){
                $main_order = $order->orders;

                if(!empty($main_order)){
                    foreach ($main_order as $key => $ord) {
                        $order_number = $ord->order_id;
                        // $log = new KllBBOrderLog();
                        // $log->setType(10)->setOrderNumber($order_number)->setContent($res)->setCreatTime(time())->setUpdateTime(time())->save();
                        $ret = KaolaSynService::getInstance()->sync($ord);
                        sleep(60);
                    }
                }
            }
        }
        return true;
    }
    
    private  function checkMemory() {
        $nowmem = (int)(memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }


}
