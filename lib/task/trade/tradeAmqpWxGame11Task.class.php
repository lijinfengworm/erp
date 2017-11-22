<?php
class tradeAmqpWxGame11Task extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->namespace            = 'trade';
        $this->name                 = 'AmqpWxGame11';
        $this->briefDescription     = '';
        $this->detailedDescription  = <<<EOF
The [trade:AmqpWxGame11|INFO] task does things.
Call it with:

  [php symfony trade:AmqpWxGame11|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        sfContext::createInstance($this->configuration);
        set_time_limit(0);
        ini_set('memory_limit','128M');

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);

        while(true) {
            //判断内存是否超出
            $this->_checkMemory();

            $key = 'g11_room_collection';
            $roomSize = $redis->LLEN($key);
            if (0 !== $roomSize) {
                $queueData = $redis->LINDEX($key, 0);
                $queueData = json_decode($queueData, true);
                $roomId = $queueData['roomId'];
                $masterid = $queueData['masterid'];
                $ip = $queueData['ip'];

                //echo '*****由' . $masterid . '(IP: ' . $ip . ')创建的擂台(id: ' .  $roomId . ')进行处理。' . "\r\n";

                $userInfo = unserialize($redis->get('g11_user_info_' . $masterid)); // 擂主信息
                $mastername = $userInfo['nickname']; // 擂主昵称

                $roomBaseKey = 'g11_room_base_info_' . $roomId;
                $roomBaseInfo = unserialize($redis->get($roomBaseKey));

                if (empty($roomBaseInfo)) {
                    $redis->LPOP($key);
                } else {
                    $startTime = $roomBaseInfo['start_time'];
                    $countdown = time() - $startTime;
                    $limtiTime = 5 * 60; // 5分钟限制
                    if ($limtiTime <= $countdown) {
                        $echoMsg = '由' . $mastername . '(IP: ' . $ip . ')创建的擂台(id: ' .  $roomId . ')已结束(0人参加)。' . "\r\n";
                        // 游戏结束的处理

                        // 排行统计
                        $memberColl = array();
                        $memberScore = array();
                        $memberTime = array();
                        $members = $redis->smembers('g11_room_member_collect_' . $roomId);
                        foreach ($members as $memberid) {
                            $roomUserInfoKey = 'g11_room_user_info_' . $roomId . $memberid;
                            $memberInfo = json_decode($redis->get($roomUserInfoKey),true);
                            $memberColl[] = $memberInfo;
                            $memberScore[] = $memberInfo['score'];
                            $memberTime[] = $memberInfo['time'];
                        }
                        if (0 < count($memberColl)) {
                            array_multisort($memberScore, SORT_DESC, $memberTime, SORT_DESC, $memberColl);
                            $echoMsg = '由' . $mastername . '(IP: ' . $ip . ')创建的擂台(id: ' .  $roomId . ')已结束(' . count($memberColl) . '人参加)。' . "\r\n";
                        }
                        $redis->set('g11_result_' . $roomId, serialize($memberColl)); // 该擂台的结果存于redis

                        foreach ($memberColl as $keyi => $item) {
                            $openid = $item['openid'];
                            $userInfoI = unserialize($redis->get('g11_user_info_' . $openid));
                            $masternameI = $userInfoI['nickname'];
                            $redis->set('g11_not_read_messages_' . $openid, true); // 有未读消息
                            $inviteeCountRedis = $redis->get('g11_count_room_invitee_' . $roomId . $openid);
                            $inviteeCount = empty($inviteeCountRedis) ? 0 : $inviteeCountRedis; // 邀请的人数
                            if (8 < $inviteeCount) {
                                $inviteeMsg = '你在 ' . $mastername . ' 创建的擂台中邀请了'  . $inviteeCount . '名勇士参赛，获得一个邀请红包，快来拆开看看吧！';
                                $history = array(
                                    'randid' => FunBase::genRandomString(10),
                                    'openid' => $openid,
                                    'nickname' => $masternameI,
                                    'room' => $roomId,
                                    'msg' => $inviteeMsg,
                                    'time' => time(),
                                    'isRedEnvelope' => true,
                                    'count' => $inviteeCount,
                                    'type' => 1 // 邀请得红包
                                );
                                $redis->lPush('g11_user_history_' . $openid, json_encode($history));
                            }

                            $playerNum = $redis->get('g11_count_room_player_' . $roomId); // 参与人数
                            $isRedEnvelope = false;
                            $ranking = (int) ($keyi + 1);
                            if (1 == $ranking) {
                                $isRedEnvelope = true;
                                $msg = '恭喜你在 ' . $mastername . ' 创建的擂台中夺得冠军，获得一个擂台红包，快来拆开看看吧！';
                            } else {
                                $msg = '你在 ' . $mastername . ' 创建的擂台中夺得第'  . $ranking . '名，识货送你一张海淘现金券！';
                            }
                            $history = array(
                                'randid' => FunBase::genRandomString(10),
                                'openid' => $openid,
                                'nickname' => $masternameI,
                                'room' => $roomId,
                                'msg' => $msg,
                                'time' => time(),
                                'isRedEnvelope' => $isRedEnvelope,
                                'count' => $playerNum,
                                'rank' => $ranking,
                                'type' => 2 // 冠军得红包
                            );
                            $redis->lPush('g11_user_history_' . $openid, json_encode($history));
                        }
                        $redis->del('g11_in_premess_game_with_user_' . $masterid); // 删除擂台主人创建的正在进行中擂台
                        $redis->LPOP($key);
                        echo $echoMsg;
                    }
                }

            }
        }
    }

    private function _checkMemory() {
        $nowmem = (int) (memory_get_usage() / 1024 / 1024);
        if ($nowmem > 60) {  //如果内存超过128M 那么主动退出
            exit(0);
        }
    }
}