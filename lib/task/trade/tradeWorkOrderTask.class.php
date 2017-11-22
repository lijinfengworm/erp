<?php

class tradeWorkOrderTask extends sfBaseTask
{

    private $_action = array('chaoshi','hebao');


    private $_titleFormat = array(
        'chaoshi' => '超时未发货 [mart_order_number]',
        'hebao' => '其他异常合包 [mart_express_number]',
    );

    private $_textFormat = array();





  protected function configure(){

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
            new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'The money name', '1'),
    ));

    $this->namespace        = 'trade';
    $this->name             = 'WorkOrder';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [trade:WorkOrderTask|INFO] task does things.
Call it with:

  [php symfony trade:WorkOrder|INFO]
EOF;
  }
  
    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        $this->setText();
         foreach($this->_action as $k=>$v) {
             $_fun = $v.'Action';
             $this->$_fun($v);
         }
    }

    protected  function chaoshiAction($type) { 
        $_mart_order_time = strtotime('- 30 day');
        $_sql = "select CONCAT('超时未发货_',id) as `key`,`order_number`,`title`,`mart_order_number`,`mart_express_number`,`business`,`business_account`,`grant_username`
                        from `trd_order`where
                        `mart_order_time`  < (UNIX_TIMESTAMP(NOW())-86400*5) and
                         `mart_order_time` > $_mart_order_time and
                         `pay_status` = 1 and
                          `mart_order_number` is not null and (`mart_express_number` is null or `mart_express_number` = '')
                          group by `mart_order_number`";
        $this->_exec($_sql,$type,"超时未发货");
    }

    private function  hebaoAction($type) {
        $_day = (int)(date('ymd',strtotime('- 60 day'))).'0000000000';
        $_sql = "select
                  CONCAT('其他异常合包_',mart_express_number) as `key`,
                  count(*) as co,
                  sum(`order_number`)/count(*) as ch,
                  `order_number`,`mart_express_number`,
                  `mart_order_number`,`business_account`,`business`,`delivery_type`
                   from `trd_order` where
                   `order_number` >= $_day
                   AND  mart_order_number != ''
                   AND  mart_express_number is not null
                   AND  mart_express_number <> ''
                   group by `mart_express_number`
                   having co > 1 and ch != order_number
                   order by `order_number` desc";
        $this->_exec($_sql,$type,"其他异常");
    }





    private  function  _exec($_sql = '',$type = '',$kf_type = '') {
        if(empty($_sql) || empty($type) || empty($kf_type)) return true;
        $connection = Doctrine_Manager::getInstance()->getConnection('trade');
        $statement = $connection->execute($_sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($result)) return true;
        $saveData = array();
        foreach($result as $k=>$v) {
            $ticket = TrdOrderTicketsTable::getInstance()->createQuery()->where('ticket_key =?', $v['key'])->fetchOne();
            if($ticket) {
                $ticketInfo = $this->getTicketInfo($ticket->getTicketId());
                $ticket->setStatus($ticketInfo['ticket']['status']);
                $ticket->save();
                continue;
            }
            $saveData['key'] = $v['key'];
            $saveData['title'] = $this->_titleFormat[$type];
            $saveData['text'] = $this->_textFormat[$type];
            foreach($v as $j=>$z) {
                $saveData['title'] = str_replace('['.$j.']',$z,$saveData['title']);
                $saveData['text'] = str_replace('['.$j.']',$z,$saveData['text']);
            }
            //创建新工单
            $ticketInfo = $this->addTicket(
                $saveData['title'],
                $saveData['text'],
                $kf_type,
                (empty($v['mart_express_number'])?false:$v['mart_express_number']),
                (empty($v['order_number'])?false:$v['order_number']),
                (empty($v['mart_order_number'])?false:$v['mart_order_number']),
                (empty($v['business_account'])?false:$v['business_account'])
            );
            $ticket = new TrdOrderTickets();
            $ticket->setTicketId($ticketInfo['ticket']['id']);
            $ticket->setStatus('new');
            $ticket->setTicketKey($saveData['key']);
            $ticket->save();
        }
    }




    public function addTicket($title,$text,$type,$mart_express_number,$order_number,$mart_order_number,$business_account)
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, 'https://shihuo.kf5.com/apiv2/tickets.json' );
        curl_setopt( $ch, CURLOPT_POST, true);
        $post = array('ticket'=>array('title'=>$title,'comment'=>array('content'=>nl2br($text)),'group_id'=>'18854'),'custom_fields'=>array());
        if($type == '超时未发货')
        {
            $post['ticket']['custom_fields'][] = array('name'=>'field_5361','value'=>'系统-超时未发货');
        }elseif($type == '超时未入库'){
            $post['ticket']['custom_fields'][] = array('name'=>'field_5361','value'=>'系统-超时未入库');
        }elseif($type == '未预报入库'){
            $post['ticket']['custom_fields'][] = array('name'=>'field_5361','value'=>'系统-未预报入库[待拍照]');
        }elseif($type == '超时未出库'){
            $post['ticket']['custom_fields'][] = array('name'=>'field_5361','value'=>'系统-超时未出库');
        }elseif($type == '其他异常'){
            $post['ticket']['custom_fields'][] = array('name'=>'field_5361','value'=>'其他异常');
        }

        if($mart_express_number)
        {
            $post['ticket']['custom_fields'][] = array('name'=>'field_5403','value'=>$mart_express_number);
        }
        if($order_number)
        {
            $post['ticket']['custom_fields'][] = array('name'=>'field_5405','value'=>$order_number);
        }
        if($mart_order_number)
        {
            $post['ticket']['custom_fields'][] = array('name'=>'field_5404','value'=>$mart_order_number);
        }
        if($business_account)
        {
            $post['ticket']['custom_fields'][] = array('name'=>'field_5431','value'=>$business_account);
        }
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_setopt( $ch, CURLOPT_USERPWD, ('wangpeng@hupu.com'.'/token:'.'2407b874f098e871e63359c818e15a'));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json'));
        curl_setopt( $ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt( $ch, CURLOPT_TIMEOUT, 30);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_HEADER, true);
        curl_setopt( $ch, CURLOPT_VERBOSE, true);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 3);
        $response = curl_exec($ch);
        $headerSize   = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $responseBody = substr($response, $headerSize);
        curl_close($ch);
        return json_decode($responseBody,1);
    }




    protected function setText() {
        $this->_textFormat['chaoshi'] = <<<TEXT
超时未发货

订单号  [mart_order_number]

账号  [business_account]

商品名  [title]

商家  [business]

识货订单号  [order_number]

下单人 [grant_username]
TEXT;
        $this->_textFormat['hebao'] = '其他异常合包 [mart_express_number]';
    }


    public function getTicketInfo($id)
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, 'https://shihuo.kf5.com/apiv2/tickets/'.$id.'.json' );
        curl_setopt( $ch, CURLOPT_USERPWD, ('wangpeng@hupu.com'.'/token:'.'2407b874f098e871e63359c818e15a'));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json'));
        curl_setopt( $ch, CURLINFO_HEADER_OUT, true);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt( $ch, CURLOPT_TIMEOUT, 30);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_HEADER, true);
        curl_setopt( $ch, CURLOPT_VERBOSE, true);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 3);
        $response = curl_exec($ch);
        $headerSize   = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $responseBody = substr($response, $headerSize);
        curl_close($ch);
        return json_decode($responseBody,1);
    }



}
