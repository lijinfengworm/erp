<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpLipinkaCreateTask extends sfBaseTask
{
    CONST WEB_SITE = 'http://www.shihuo.cn';
    CONST ERROR_NUM = 10;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace        = 'trade';
        $this->name             = 'AmqpLipinkaCreate';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:Amqp|INFO]
EOF;
    }

    /**
     * 礼品卡生成脚本
     */
    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        while(true) {
            //判断内存是否超出
            $this->checkMemory();
            //获取所有未生成的
            $record = TrdLipinkaRecordTable::getStartRecordData();
            //获取所有没有同步的卡密
            $no_sync_ids = TrdLipinkaCardTable::getNoSyncIds();
            if (empty($record) && empty($no_sync_ids)) {
                sleep(60);  //等待1分钟
            } else {//如果有内容 那么就继续生成
                if(!empty($record)) {
                    foreach ($record as $key => $val) {
                        $_exist_num = $_num = 0;
                        $_accept_uids = array();
                        //判断是否有生成过
                        $_exist_num = TrdLipinkaCardTable::getRecordCount($val['id']);
                        $_num = (int)($val['num'] - $_exist_num);
                        if ($val['type'] == 2) {
                            //判断是否已经有生成了
                            $_exist_uids = TrdLipinkaCardTable::getExistUids($val['id']);
                            if (!empty($_exist_uids)) {
                                $_accept_uids = array_diff($val['accept_uids'], $_exist_uids); //获取未生成的会员
                                $_accept_uids = array_values($_accept_uids);  //重新指定key
                            } else {
                                $_accept_uids = $val['accept_uids'];
                            }
                        }
                        //最多每次生成500条
                        if($_num > 500) $_num = 500;
                        for ($i = 0; $i < $_num; $i++) {
                            $card = new TrdlipinkaCard();
                            //判断是否生成大卡
                            if(!empty($val['is_large'])) {
                                $card->setIsLarge(1);
                                $card->setLargeId($val['large_id']);
                            }
                            $card->setStime($val['stime']);
                            $card->setEtime($val['etime']);
                            $card->setPostponeType($val['postpone_type']);
                            $card->setPostponeDay($val['postpone_day']);
                            $card->setOverdueTime($val['overdue_time']);
                            $card->setLipinkaId($val['lipinka_id']);
                            $card->setRecordId($val['id']);
                            $card->setCreateType($val['type']);
                            do {
                                $account = FunBase::genRandomString(10);
                            } while (TrdLipinkaCardTable::isRepeat($account));
                            $card->setAccount($account);
                            if ($val['type'] == 1) {  //生成卡密 标记uid  和状态都未使用
                                $card->setUserId(0);
                                $card->setStatus(0);
                            } else {  // 发放到具体个人
                                $card->setUserId($_accept_uids[$i]);
                                $card->setStatus(1);
                            }
                            $card->setAmount($val['price']);
                            $card->save();
                            //如果是发到个人账户 那么 还要同步到优惠卷表一份
                            if ($val['type'] == 2) {
                                $serviceRequest = new tradeServiceClient();
                                $serviceRequest->setMethod('coupons.received.by.backend');
                                $serviceRequest->setVersion('1.0');
                                $serviceRequest->setApiParam('card', $card);
                                $response = $serviceRequest->execute();
                                $sync_flag  = $response->hasError();
                            } else {
                                $sync_flag = false;
                            }
                            if($sync_flag == false) {
                                $card->setSyncStatus(1); //标记已同步
                                $card->save();
                            }
                            //判断内存使用
                            $this->checkMemory();
                        }
                        //再判断一次是否全部生成了 如果生成了  标记成功
                        $_exist_num = TrdLipinkaCardTable::getRecordCount($val['id']);
                        $lipinka_record = TrdLipinkaRecordTable::getInstance()->find($val['id']);
                        if($_exist_num == $lipinka_record->getNum()) {
                            $lipinka_record->setIsSuccess(2);
                            $lipinka_record->save();
                        }
                        //判断内存是否超出
                        $this->checkMemory();
                    }  //foreachend
                }  //if foreach end


                //重新发送同步
                if(!empty($no_sync_ids)) {
                    //判断内存是否超出
                    $this->checkMemory();
                    foreach ($no_sync_ids as $key => $val) {
                        $data = TrdLipinkaCardTable::getInstance()->find($val);
                        if(empty($data) || $data->getCreateType() == 1) continue;
                        //如果是直接发送到账户里面 那么就要通知
                        if ($data->getCreateType() == 2) {
                            $serviceRequest = new tradeServiceClient();
                            $serviceRequest->setMethod('coupons.received.by.backend');
                            $serviceRequest->setVersion('1.0');
                            $serviceRequest->setApiParam('card', $data);
                            $response = $serviceRequest->execute();
                            if($response->hasError() == false) {
                                $data->setSyncStatus(1); //标记已同步
                                $data->save();
                            }
                        }
                        //判断内存是否超出
                        $this->checkMemory();
                    }  //foreachend ;
                }

            }
        }
    }

    private function checkMemory() {
        $nowmem = (int)(memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }

}
