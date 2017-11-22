<?php
use PhpAmqpLib\Connection\AMQPConnection;

/**
 * Class tradeAmqpNewsTimingTask
 * 优惠信息 && 专题  定时发布 task
 * 梁天 2015-05-13
 */
class tradeAmqpNewsTimingTask extends sfBaseTask
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
        $this->name             = 'AmqpNewsTiming';
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
        $start_time = time();
        $record = TrdNewsTable::getTimingIntervalData();
        if (!empty($record)) {
            foreach ($record as $k => $v) {
                if (time() >= $v['timing_interval']) {
                    //修改成已发布
                    $use_article = TrdNewsTable::getInstance()->find($v['id']);
                    $use_article->setTimingInterval('');
                    $use_article->setPublishDate(date('Y-m-d H:i:s',$v['timing_interval']));
                    $use_article->setIsDelete(0);
                    $use_article->save();
                }
            }  // foreach end
        }

        $_special_record = TrdSpecialTable::getTimingIntervalData();
        if (!empty($_special_record)) {
            foreach ($_special_record as $k => $v) {
                if (time() >= $v['timing_interval']) {
                    //修改成已发布
                    $use_special = TrdSpecialTable::getInstance()->find($v['id']);
                    $use_special->setTimingInterval('');
                    $use_special->setCreatedAt(date('Y-m-d H:i:s',$v['timing_interval']));
                    $use_special->setSpecialStatus(TrdSpecialTable::$SHOW_FLAG);
                    $use_special->save();
                }
            }  // foreach end
        }


        $end_time = time();
        $sleep_time = 60 - (int)($end_time - $start_time);
        sleep($sleep_time);
        exit();
        }



}
