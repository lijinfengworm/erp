<?php
/**
 * Created by HUPU-VOICE'DEVELOP'TEAM.
 * User: MR.Rain
 * Date: 14-7-7
 * Time: 上午11:15
 */

//统计用户当天在线时长
class voiceCountUserOnlineTimeTask extends sfBaseTask {

    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'star'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'voice'),
            // add your own options here
        ));

        $this->namespace = 'voice';
        $this->name = 'countUserOnlineTime';
        $this->briefDescription = '统计用户当天在线时长';
        $this->detailedDescription = <<<EOF
The [countUserOnlineTime|INFO] task does things.
Call it with:

  [php symfony countUserOnlineTime|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        sfContext::createInstance(sfProjectConfiguration::getApplicationConfiguration('star', $options['env'], true));

        $this->log('start proc');

        //处理用户当天在线时长
        $proc_redis = sfContext::getInstance()->getDatabaseConnection('voiceRedis');
        $activeDaysCountKey = 'user_active_days_count'; //统计用户活跃天数redis集合key值
        //$checkUserActiveDaysCountKey = 'check_u_a_days'; //跟踪查看用户活跃天数到底统计了几次

        while(1){
            $proc_result = $proc_redis->sGetMembers($activeDaysCountKey);

            if(!count($proc_result)){
                $this->log('no data to process');
                break;
            }

            foreach($proc_result as $val){
                $uid = trim($val,'#');
                userRankTable::addActiveDaysByUid((int)$uid, 0.5);//用户活跃天数加0.5
                //$proc_redis->lPush($checkUserActiveDaysCountKey, $uid.'|'.time());
                $this->log($uid . ' has added 0.5 active days');
                $proc_redis->sRem($activeDaysCountKey,$val);
            }

            $this->log('sleep 2 seconds');
            sleep(2);
        }

        $this->log('end proc');
    }

}
