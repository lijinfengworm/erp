<?php

abstract class paymentAction extends sfAction
{
    // TODO: Remove all the user query code,
    //       we have already checked the existence
    //       of the user when place the order, 
    //       no need to check it again here.

    protected function transferMoney($order)
    {
        $url = '';
        $gamesConfig = sfConfig::get('app_games');
        $debug = (sfContext::getInstance()->getConfiguration()->getEnvironment() != 'prod' && sfConfig::get('sf_debug'));

        //如果已经支付，直接返回原来的状态
        if ($order->getStatus() > wpOrderTable::STATUS_TRANSFER_PENDING) {
            return $order->getStatus() - 4;
        }

        // 3 - Paying ...
        $order->setStatus(wpOrderTable::STATUS_TRANSFER_PENDING);
        $order->save();

        switch ($order->getWpgame()->getId()) {
            case wpGameTable::XI_YOU_ZHENG_TU:
                $time = time();
                $xyztConfig = $gamesConfig['xiyouzhengtu'];

                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                $userParams['accountId'] = $order->getTargetUserId();
                $userParams['accountName'] = $order->getTargetUserName();
                $userParams['money'] = $this->getGameCredits($order);
                $userParams['tstamp'] = $time;

                $arguments = array_merge($xyztConfig, $userParams);
                $pay = new XyztPay($arguments);
                $response = $pay->pay();

                if ($response->isSuccess()) {
                    $r = 5;
                } else {
                    $r = 4;
                }

                $this->logMessage('Url ->' . $pay, 'err');
                $this->logMessage('Result ' . $response->getErrorMessage(), 'err');

                $url = (string)$pay;
                $result = $response->getErrorMessage();
                break;

            case wpGameTable::RE_XUE_QIU_QIU:
                $time = time();

                if ($debug) {
                    $money = 1;
                } else {
                    $money = intval($this->getGameCredits($order));
                }

                $rechargeUrl = str_replace('%%serverNumber%%', trim($order->getWpserver()->getServerNo()), $gamesConfig['rexueqiuqiu']['payUrl']);

                $str = 'platform=' . $gamesConfig['rexueqiuqiu']['platform']
                    . '&username=' . $order->getTargetUserName()
                    . '&money=' . $money
                    . '&bonus=0'
                    . '&orderid=' . $this->getOrderIdByEnv($order->getId())
                    . '&time=' . date('YmdHis');

                $url = $rechargeUrl
                    . $str
                    . '&sig=' . strtoupper(sha1($str . '&key=' . $gamesConfig['rexueqiuqiu']['key']));

                $result = @file_get_contents($url);

                // Read failed
                if ($result === false) {
                    $r = 4;
                } else {
                    $r = ((int)trim($result) == 0 ? 5 : 4);
                }

                break;

            case wpGameTable::WANG_ZHE_TIAN_XIA:
                $wztxConfig = $gamesConfig['wangzhetianxia'];

                $userParams = array();
                $userParams['userId'] = $order->getTargetUserId();
                $userParams['serverId'] = $order->getWpserver()->getServerNo();

                $queryUserArguments = array_merge($wztxConfig, $userParams);
                $checker = UserCheckerFactory::getChecker($order->getWpgameId(), $queryUserArguments);

                if (!$checker->exists()) {
                    $url = $checker->__toString();
                    $result = $checker->getErrorCode();
                    $r = 4;

                    break;
                }

                $roles = $checker->getGameRoles();
                $this->logMessage('Roles are: ' . print_r($roles, true), 'err');

                list($roleId, $roleName) = each($roles);

                $userParams['gameRoleId'] = $roleId;
                $userParams['userIP'] = $order->getIp();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());

                if ($debug) {
                    $userParams['payPoint'] = 1;
                    $userParams['gamePoint'] = intval(1 * $order->getWpgame()->getCurrency());
                } else {
                    $userParams['payPoint'] = $this->getAmount($order);
                    $userParams['gamePoint'] = $this->getGameCredits($order);
                }

                $userParams['memo'] = '';

                $arguments = array_merge($wztxConfig, $userParams);
                $wztx = new WangzhetianxiaPay($arguments);

                $this->logMessage('dfsfd-- ' . $wztx, 'err');
                $this->logMessage('Paypoint ' . $userParams['payPoint'], 'err');

                if ($wztx->pay()) {
                    $r = 5;
                } else {
                    $r = 4;
                }

                $url = (string)$wztx;
                $result = $wztx->getErrorCode();

                break;

            case wpGameTable::YING_XIONG_ZHI_CHENG:
                $yxzcConfig = $gamesConfig['yingxiongzhicheng'];

                $gameCurrency = sfConfig::get('app_gameCurrency');
                $yxzcCurrency = $gameCurrency['yingxiongzhicheng'];

                $userParams = array();
                $userParams['account'] = $order->getTargetUserId();
                $userParams['orderNo'] = $this->getOrderIdByEnv($order->getId());
                $userParams['price'] = (int)$order->getAmount();
                $userParams['amount'] = 1;
                $userParams['totalPrice'] = $yxzcCurrency[(int)$order->getAmount()] * $userParams['amount'];
                $userParams['clientIP'] = $order->getIp();

                $arguments = array_merge($yxzcConfig, $userParams);

                $sign = new SnailGameSign($arguments);

                $billingService = new BillingService(array(), $yxzcConfig['payUrl']);
                $requestMsg = new imprestAccount($arguments['partnerId']
                    , $arguments['partnerPwd']
                    , $arguments['account']
                    , $arguments['orderNo']
                    , $arguments['gameId']
                    , $arguments['gameAreaId']
                    , $arguments['price']
                    , $arguments['amount']
                    , $arguments['totalPrice']
                    , $arguments['clientIP'],
                    $sign->sign(),
                    array());
                ob_start();
                var_dump($requestMsg);
                $contents = ob_get_contents();
                ob_end_clean();
                $this->logMessage('Contents=>>' . $contents, 'err');

                $res = $billingService->imprestAccount($requestMsg);

                $this->logMessage('Result-> ' . $res->out, 'err');

                $response = new SnailGamePayResponseMessage($res->out);
                $url = $yxzcConfig['payUrl'];
                $result = $response->getErrorCode();

                if ($response->isSuccess()) {
                    $r = 5;
                } else {
                    $r = 4;
                }

                break;

            case wpGameTable::DI_GUO_WEN_MING:
                $dgwmConfig = $gamesConfig['diguowenming'];
                $checkerParams = array_merge($dgwmConfig, array('userId' => $order->getTargetUserId()));
                $checker = UserCheckerFactory::getChecker($order->getWpgameId(), $checkerParams);

                if (!$checker->exists()) {
                    $url = $dgwmConfig['checkUserUrl'];
                    $result = $checker->getErrorCode() . '|' . $checker->getErrorMessage();
                    $r = 4;

                    break;
                }

                $gameCurrency = sfConfig::get('app_gameCurrency');
                $dgwmCurrency = $gameCurrency['diguowenming'];

                $userParams = array();
                $userParams['account'] = $order->getTargetUserId();
                $userParams['orderNo'] = $this->getOrderIdByEnv($order->getId());

                if ((int)$order->getAmount() < 20) {
                    $userParams['price'] = (int)$order->getAmount();
                    $userParams['amount'] = 1;
                    $userParams['totalPrice'] = $dgwmCurrency[(int)$order->getAmount()] * $userParams['amount'];
                } else {
                    $userParams['price'] = 1;
                    $userParams['amount'] = (int)$order->getAmount();
                    $userParams['totalPrice'] = $userParams['price']
                        * (int)$userParams['amount']
                        * (int)$order->getWpgame()->getCurrency();
                }

                $userParams['clientIP'] = $order->getIp();

                $gameAreaId = $order->getWpserver()->getServerNo();

                $userParams['gameAreaId'] = $gameAreaId;

                $arguments = array_merge($dgwmConfig, $userParams);

                $sign = new SnailGameSign($arguments);

                $billingService = new BillingService(array(), $dgwmConfig['payUrl']);
                $requestMsg = new imprestAccount($arguments['partnerId'],
                    $arguments['partnerPwd'],
                    $arguments['account'],
                    $arguments['orderNo'],
                    $arguments['gameId'],
                    $gameAreaId,
                    $arguments['price'],
                    $arguments['amount'],
                    $arguments['totalPrice'],
                    $arguments['clientIP'],
                    $sign->sign(),
                    array());
                ob_start();
                var_dump($requestMsg);
                $contents = ob_get_contents();
                ob_end_clean();
                $this->logMessage('Request Message is: ' . strip_tags($contents), 'err');

                $res = $billingService->imprestAccount($requestMsg);

                $this->logMessage('Raw result is: ' . $res->out, 'err');

                $response = new SnailGamePayResponseMessage($res->out);

                $url = $dgwmConfig['payUrl'];
                $result = $response->getErrorCode();
                if ($response->isSuccess()) {
                    $r = 5;
                } else {
                    $r = 4;
                }
                break;

            case wpGameTable::FTS_BASKEETBALL:
                $ftsConfig = $gamesConfig['fantasyBaskeetball'];

                $userParams = array();
                $userParams['accountName'] = $order->getTargetUserName();

                $checkerParams = array_merge($ftsConfig, $userParams);

                $checker = UserCheckerFactory::getChecker($order->getWpgameId(), $checkerParams);

                // Exit when the user doesn't exist
                if (!$checker->exists()) {
                    $url = (string)$checker;
                    $result = $checker->getErrorCode();
                    $r = 4;

                    break;
                }

                $userParams['partnerOrderNo'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['amount'] = $this->getGameCredits($order);
                $userParams['time'] = time();

                $arguments = array_merge($ftsConfig, $userParams);

                $ftsPay = new FantasyPay($arguments);
                $response = $ftsPay->pay();

                if (!$response->isSuccess()) {
                    $r = 4;
                } else {
                    $r = 5;
                }

                $url = (string)$ftsPay;
                $result = $response->getRawResult();

                break;

            case wpGameTable::XIONG_DI_LAN_QIU:
                $xiongdilanqiuConfig = $gamesConfig['xiongdilanqiu'];

                $userParams = array();
                $userParams['uid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                // TODO: Do replace this to a better one later!!!
                if ($order->getWpserver()->getServerNo() >= 7) {
                    $xiongdilanqiuConfig['queryRoleUrl'] = $xiongdilanqiuConfig['hunfuQueryRoleUrl'];
                    $xiongdilanqiuConfig['payUrl'] = $xiongdilanqiuConfig['hunfuPayUrl'];
                }

                $requestArguments = array_merge($xiongdilanqiuConfig, $userParams);

                $roleQueryRequest = new XiongdilanqiuRoleQueryRequest($requestArguments);
                $roleQueryResponse = $roleQueryRequest->execute();

                if (!$roleQueryResponse->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$roleQueryRequest;
                    $result = $roleQueryResponse->getErrorInfo();

                    break;
                }

                //$role = $roleQueryResponse->getRole();

                $userParams['uname'] = $order->getTargetUserName();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['time'] = date('YmdHis');
                $userParams['amount'] = $this->getAmount($order);
                $userParams['point'] = $this->getGameCredits($order);
                $userParams['roleid'] = $order->getRoleUserId();
                $userParams['rolename'] = $order->getRoleUserName();

                $payRequestArguments = array_merge($xiongdilanqiuConfig, $userParams);

                $payRequest = new XiongdilanqiuPayRequest($payRequestArguments);
                $payResponse = $payRequest->pay();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::SAN_GUO_SHA:
                $sanguoshaConfig = $gamesConfig['sanguosha'];

                $userParams = array();
                $userParams['user_id'] = $order->getTargetUserId();
                $userParams['area_id'] = $order->getWpserver()->getServerNo();
                $userParams['timestamp'] = time();

                $requestArguments = array_merge($sanguoshaConfig, $userParams);
                $userQueryRequest = new SanguoshaUserQueryRequest($requestArguments);
                $userQueryResposne = $userQueryRequest->send();

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getErrorInfo();

                    break;
                }

                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());
                $userParams['amount'] = $this->getAmount($order);
                $userParams['timestamp'] = time();

                $requestArguments = array_merge($sanguoshaConfig, $userParams);
                $payRequest = new SanguoshaPayRequest($requestArguments);
                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::MO_SHEN_ZHAN_JI:
                $moshenzhanjiConfig = $gamesConfig['moshenzhanji'];

                $userParams = array();
                $userParams['username'] = $order->getTargetUserName();
                $userParams['server_id'] = $order->getWpserver()->getServerNo();

                if (in_array($order->getWpserver()->getServerNo(), $gamesConfig['moshenzhanji']['hunfu']['servers'])) {
                    $moshenzhanjiConfig = $moshenzhanjiConfig['hunfu'];
                    $userParams['username'] = $order->getTargetUserId();

                    $queryUserArguments = array_merge($moshenzhanjiConfig, $userParams);

                    $userQueryRequest = new MoshenzhanjiHunFuUserQueryRequest($queryUserArguments);
                } else {
                    $moshenzhanjiConfig['userQueryUrl'] = 'http://' . strtolower($order->getWpserver()->getServerNo()) . $moshenzhanjiConfig['userQueryUrl'];

                    $queryUserArguments = array_merge($moshenzhanjiConfig, $userParams);

                    $userQueryRequest = new MoshenzhanjiUserQueryRequest($queryUserArguments);
                }

                $userQueryResposne = $userQueryRequest->send();

                $this->logMessage((string)$userQueryRequest, 'err');

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getErrorInfo();

                    break;
                }

                $this->logMessage('Chauser url ' . (string)$userQueryRequest);

                $this->logMessage('Yonghu ' . $userQueryResposne->getRole(), 'err');

                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['type'] = $order->getWppayment()->getName();
                $userParams['passport'] = $order->getTargetUserName();
                $userParams['money'] = $this->getAmount($order);
                $userParams['gold'] = $this->getGameCredits($order);
                $userParams['coin'] = 0;
                $userParams['role'] = $order->getRoleUserName();
                $userParams['server_id'] = $order->getWpserver()->getServerNo();

                if (in_array($order->getWpserver()->getServerNo(), $gamesConfig['moshenzhanji']['hunfu']['servers'])) {
                    $userParams['passport'] = $order->getTargetUserId();

                    $payArguments = array_merge($moshenzhanjiConfig, $userParams);

                    $payRequest = new MoshenzhanjiHunFuPayRequest($payArguments);
                } else {
                    $moshenzhanjiConfig['payUrl'] = 'http://' . strtolower($order->getWpserver()->getServerNo()) . $moshenzhanjiConfig['payUrl'];
                    $payArguments = array_merge($moshenzhanjiConfig, $userParams);

                    $payRequest = new MoshenzhanjiPayRequest($payArguments);
                }

                $this->logMessage((string)$payRequest, 'err');

                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $this->logMessage('dasfd-----------------------------');

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::SHEN_XIAN_DAO:
                $shenxiandaoConfig = $gamesConfig['shenxiandao'];

                $userParams = array();
                $userParams['user'] = $order->getTargetUserId();

                $userParams['domain'] = $order->getWpserver()->getServerNo() . $shenxiandaoConfig['domain'];
                if ((int)trim($order->getWpserver()->getServerNo(), 's') >= 7) {
                    $userParams['domain'] = $order->getWpserver()->getServerNo() . $shenxiandaoConfig['domain7'];
                }

                $userQueryArguments = array_merge($shenxiandaoConfig, $userParams);
                $userQueryRequest = new ShenxiandaoUserQueryRequest($userQueryArguments);

                $userQueryResposne = $userQueryRequest->send();

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getErrorInfo();

                    break;
                }

                $userParams['gold'] = $this->getGameCredits($order);
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());

                $payRequestArguments = array_merge($shenxiandaoConfig, $userParams);

                $payRequest = new ShenxiandaoPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::HAI_ZEI_WANG:
                $haizeiwangConfig = $gamesConfig['haizeiwang'];

                $userParams = array();
                $userParams['user'] = $order->getTargetUserId();

                $userQueryArguments = array_merge($haizeiwangConfig, $userParams);

                $userQueryRequest = new HaizeiwangUserQueryRequest($userQueryArguments);
                $userQueryResposne = $userQueryRequest->send();

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getErrorInfo();

                    break;
                }

                $userParams['gold'] = $this->getAmount($order);
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverId'] = $order->getWpserver()->getServerNo();

                $payRequestArguments = array_merge($haizeiwangConfig, $userParams);

                $payRequest = new HaizeiwangPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Error code is ' . $payResponse->getErrorCode(), 'err');
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            case wpGameTable::TIAN_YI_JUE:
                $tianyijueConfig = $gamesConfig['tianyijue'];

                $userParams = array();
                $userParams['AccountName'] = $order->getTargetUserId();

                /* Disabled, because our partners are not ready.
        $userQueryArguments = array_merge($tianyijueConfig, $userParams);
        
        $userQueryRequest = new TianyijueUserQueryRequest($userQueryArguments);
        $userQueryResposne = $userQueryRequest->send();
        
        if (!$userQueryResposne->exists())
        {
          $r = wpOrderTable::STATUS_TRANSFER_FAILED;
          $url = (string)$userQueryRequest;
          $result = $userQueryResposne->getErrorInfo();

          break;
        } 
        */

                $userParams['Money'] = $this->getAmount($order);
                $userParams['Res_money'] = $this->getGameCredits($order);
                $userParams['Pay_day'] = date('Y-m-d');
                $userParams['Pay_time'] = date('H:i:s');
                $userParams['Order_id'] = $this->getOrderIdByEnv($order->getId());

                $payRequestArguments = array_merge($tianyijueConfig, $userParams);

                $payRequest = new TianyijuePayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Error code is ' . $payResponse->getErrorCode(), 'err');
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            case wpGameTable::MENG_HUI_SHEN_GUO:
                $menghuishenguoConfig = $gamesConfig['menghuishenguo'];

                $userParams = array();
                $userParams['account'] = $order->getTargetUserId();
                $userParams['pid'] = $menghuishenguoConfig['p_id'];
                $userParams['server'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $menghuishenguoConfig['server']);

                $userQueryArguments = array_merge($menghuishenguoConfig, $userParams);

                $userQueryRequest = new MenghuishenguoUserQueryRequest($userQueryArguments);
                $userQueryResposne = $userQueryRequest->send();

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getErrorInfo();

                    break;
                }

                $userParams['user_account'] = $order->getTargetUserId();
                $userParams['fee_money'] = $this->getAmount($order);
                $userParams['log_date'] = date('Y-m-d H:i:s');
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());

                $payRequestArguments = array_merge($menghuishenguoConfig, $userParams);

                $payRequest = new MenghuishenguoPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Error code is ' . $payResponse->getErrorCode(), 'err');
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            case wpGameTable::HUO_YING_SHI_JIE:
                $huoyingshijieConfig = $gamesConfig['huoyingshijie'];

                $userParams = array();
                $userParams['userName'] = $order->getTargetUserId();
                $userParams['gatewayId'] = $order->getWpserver()->getServerNo();

                $userQueryArguments = array_merge($huoyingshijieConfig, $userParams);

                $userQueryRequest = new HuoyingshijieUserQueryRequest($userQueryArguments);
                $userQueryResposne = $userQueryRequest->send();

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getErrorInfo();

                    break;
                }

                $userParams['chargeMoney'] = $this->getAmount($order);
                $userParams['chargeAmount'] = $this->getGameCredits($order);
                $userParams['chargeTime'] = date('YmdHis');
                $userParams['clientIp'] = $order->getIp();
                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());

                $payRequestArguments = array_merge($huoyingshijieConfig, $userParams);

                $payRequest = new HuoyingshijiePayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Error code is ' . $payResponse->getErrorCode(), 'err');
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            case wpGameTable::SHENG_SHI_SAN_GUO:
                $shengshisanguoConfig = $gamesConfig['shengshisanguo'];

                $userParams = array();
                $userParams['user'] = $order->getTargetUserId();
                $userParams['time'] = time();

                $userParams['userQueryUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $shengshisanguoConfig['userQueryUrl']);

                $userQueryArguments = array_merge($shengshisanguoConfig, $userParams);

                $userQueryRequest = new ShengshisanguoUserQueryRequest($userQueryArguments);
                $userQueryResposne = $userQueryRequest->send();

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getErrorInfo();

                    break;
                }

                $userParams['gold'] = $this->getGameCredits($order);
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $shengshisanguoConfig['payUrl']);

                $payRequestArguments = array_merge($shengshisanguoConfig, $userParams);

                $payRequest = new ShengshisanguoPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Error code is ' . $payResponse->getErrorCode(), 'err');
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            case wpGameTable::ZU_QIU_TIAN_XIA:
                $zuqiutianxiaConfig = $gamesConfig['zuqiutianxia'];

                $userParams = array();
                $userParams['userId'] = $order->getTargetUserId();

                if ($order->getWpserver()->getreason()) {
                    $zqtxQueryArr = explode('%%', $order->getWpserver()->getreason());
                    $userParams['userQueryUrl'] = $zqtxQueryArr[1];
                } else {
                    $userParams['userQueryUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $zuqiutianxiaConfig['userQueryUrl']);
                }

                $userQueryArguments = array_merge($zuqiutianxiaConfig, $userParams);

                $userQueryRequest = new ZuqiutianxiaUserQueryRequest($userQueryArguments);
                $userQueryResposne = $userQueryRequest->send();

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getErrorInfo();

                    break;
                }

                $userParams['UserID'] = $order->getTargetUserId();
                $userParams['Point'] = $this->getGameCredits($order);
                $userParams['OrderID'] = $this->getOrderIdByEnv($order->getId());
                $userParams['SubTime'] = time();

                if ($order->getWpserver()->getreason()) {
                    $zqtxQueryArr = explode('%%', $order->getWpserver()->getreason());
                    $userParams['payUrl'] = $zqtxQueryArr[0];
                } else {
                    $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $zuqiutianxiaConfig['payUrl']);
                }

                $payRequestArguments = array_merge($zuqiutianxiaConfig, $userParams);

                $payRequest = new ZuqiutianxiaPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Error code is ' . $payResponse->getErrorCode(), 'err');
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            case wpGameTable::GANG_DA_ZHAN_JI:
                $gangdazhanjiConfig = $gamesConfig['gangdazhanji'];

                $userParams = array();

                $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $gangdazhanjiConfig['payUrl']);
                $userParams['orderID'] = $this->getOrderIdByEnv($order->getId());
                $userParams['payType'] = 1;
                $userParams['payMoney'] = $this->getGameCredits($order);
                $userParams['passportID'] = $order->getTargetUserId();

                $payArguments = array_merge($gangdazhanjiConfig, $userParams);

                $payRequest = new GangdazhanjiPayRequest($payArguments);
                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::WO_LONG_YIN:
                $config = $gamesConfig['wolongyin'];

                $userParams = array();

                $userParams['roleQueryUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['roleQueryUrl']);

                $userParams['type'] = 1;
                $userParams['name'] = $order->getTargetUserName();

                $queryParameters = array_merge($config, $userParams);

                $queryRequest = new WolongyinRoleQueryRequest($queryParameters);
                $queryResponse = $queryRequest->send();

                if (!$queryResponse->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$queryRequest;
                    $result = $queryResponse->getStatus();

                    break;
                }

                $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['payUrl']);
                $userParams['loginname'] = $order->getTargetUserName();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['golden'] = $this->getGameCredits($order);
                $userParams['tstamp'] = time();

                $queryParameters = array_merge($config, $userParams);

                $payRequest = new WolongyinPayRequest($queryParameters);
                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getStatus();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getStatus();
                break;

            case wpGameTable::SHAN_HAI_CHUANG_SHI_LU:
                $config = $gamesConfig['shanhaichuangshilu'];

                $userParams = array();

                $userParams['UserID'] = $order->getTargetUserId();
                $userParams['UserIP'] = $order->getIp();
                $userParams['GroupID'] = $order->getWpserver()->getServerNo();
                $userParams['Memo'] = 'HoopChina rocks';

                $userQueryArguments = array_merge($config, $userParams);

                $userQueryRequest = new ShanhaichuangshiluUserQueryRequest($userQueryArguments);
                $userQueryResposne = $userQueryRequest->send();

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getStatus();

                    break;
                }

                $userParams['GameRole'] = '';
                $userParams['Order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['PayPoint'] = $this->getAmount($order);
                $userParams['GamePoint'] = $this->getGameCredits($order);

                $payRequestArguments = array_merge($config, $userParams);

                $payRequest = new ShanhaichuangshiluPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getStatus();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getStatus();
                break;

            case wpGameTable::DAO_MU_BI_JI:
                $config = $gamesConfig['daomubiji'];

                $userParams = array();

                $userParams['uid'] = $order->getTargetUserId();
                $userParams['serverid'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['serverUrl']);

                $userQueryArguments = array_merge($config, $userParams);

                $userQueryRequest = new DaomubijiUserQueryRequest($userQueryArguments);
                $userQueryResposne = $userQueryRequest->send();

                if (!$userQueryResposne->exists()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$userQueryRequest;
                    $result = $userQueryResposne->getStatus();

                    break;
                }

                $userParams['uid'] = $order->getTargetUserId();
                $userParams['uname'] = $order->getTargetUserName();
                $userParams['amount'] = $this->getAmount($order);
                $userParams['point'] = $this->getGameCredits($order);
                $userParams['oid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['time'] = date('YmdHis');
                $userParams['format'] = 'json';

                $payRequestArguments = array_merge($config, $userParams);

                $payRequest = new DaomubijiPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getStatus();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getStatus();
                break;

            case wpGameTable::XING_JI_SHI_JIE:
                $config = $gamesConfig['xingjishijie'];

                $userParams = array();

                $userParams['gserver'] = $order->getWpserver()->getServerNo();
                $userParams['id'] = $order->getTargetUserId();
                $userParams['t'] = time();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['point'] = $this->getAmount($order);

                $payArguments = array_merge($config, $userParams);
                $payRequest = new XingjishijiePayRequest($payArguments);
                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getStatus();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getStatus();

                break;

            case wpGameTable::WU_LIN_ZHI_WANG:
                $config = $gamesConfig['wulinzhiwang'];

                $userParams = array();

                $userParams['username'] = $order->getTargetUserId();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['gamemoney'] = $this->getGameCredits($order);

                $payArguments = array_merge($config, $userParams);

                $payRequest = new WulinzhiwangPayRequest($payArguments);
                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getStatus();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getStatus();

                break;

            case wpGameTable::HANG_HAI_ZHI_WANG:
                $config = $gamesConfig['hanghaizhiwang'];

                $userParams = array();

                $userParams['accid'] = $order->getTargetUserId();
                $userParams['accname'] = $order->getTargetUserId();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['coin'] = $this->getGameCredits($order);
                $userParams['money'] = $this->getAmount($order);

                $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['payUrl']);

                $payArguments = array_merge($config, $userParams);

                $payRequest = new HanghaizhiwangPayRequest($payArguments);
                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getStatus();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getStatus();
                break;

            case wpGameTable::YI_QI_DANG_XIAN:
                $config = $gamesConfig['yiqidangxian'];

                $userParams = array();

                $userParams['sid'] = $order->getWpserver()->getServerNo();
                $userParams['account'] = $order->getTargetUserId();
                $userParams['oid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['gold'] = $this->getGameCredits($order);
                $userParams['money'] = $this->getAmount($order);
                $userParams['ip'] = $order->getIp();

                $payArguments = array_merge($config, $userParams);

                $payRequest = new YiqidangxianPayRequest($payArguments);
                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getStatus();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getStatus();
                break;

            case wpGameTable::SHENG_JIA_CHUAN_QI:
                $config = $gamesConfig['shengjiachuanqi'];

                $userParams = array();

                $userParams['sid'] = 'S' . $order->getWpserver()->getServerNo();
                $userParams['qid'] = $order->getTargetUserId();
                $userParams['oid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['order_amount'] = $this->getAmount($order);
                $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['payUrl']);

                $payArguments = array_merge($config, $userParams);

                $payRequest = new ShengjiachuanqiPayRequest($payArguments);
                $payResponse = $payRequest->send();

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getStatus();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getStatus();
                break;

            case wpGameTable::REN_REN_DOU_DI_ZHU:
                $config = $gamesConfig['renrendoudizhu'];

                $userParams = array();

                $userParams['gamecode'] = $order->getWpserver()->getServerNo();
                $userParams['account'] = $order->getTargetUserId();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['amount'] = $this->getAmount($order);

                $payArguments = array_merge($config, $userParams);

                $payRequest = new RenrendoudizhuPayRequest($payArguments);
                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');
                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getStatus();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getStatus();
                break;

            case wpGameTable::MENG_HUAN_LAN_QIU:
                $config = $gamesConfig['menghuanlanqiu'];

                $userParams = array();

                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['username'] = $order->getTargetUserName();
                $userParams['gameCoins'] = $this->getGameCredits($order);

                $payArguments = array_merge($config, $userParams);

                $payRequest = new MenghuanlanqiuPayRequest($payArguments);
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');
                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');
                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::XIANG_LONG_SHI_BA_ZHANG:
                $config = $gamesConfig['xianglongshibazhang'];

                $userParams = array();

                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['username'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['yb'] = $this->getGameCredits($order);
                $userParams['rmb'] = $this->getAmount($order);
                $userParams['payUrl'] = str_replace('%%serverNumber%%', $userParams['serverid'], $config['payUrl']);

                $payArguments = array_merge($config, $userParams);

                $payRequest = new XianglongshibazhangPayRequest($payArguments);
                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');
                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::DA_TANG_XING_BIAO:
                $config = $gamesConfig['datangxingbiao'];

                $userParams = array();

                $userParams['username'] = $order->getTargetUserId();
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());
                $userParams['server_id'] = $order->getWpserver()->getServerNo();
                $userParams['coins'] = $this->getGameCredits($order);
                $userParams['rmb'] = $this->getAmount($order);

                $payArguments = array_merge($config, $userParams);

                $payRequest = new DatangxingbiaoPayRequest($payArguments);
                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            case wpGameTable::LONG_JIANG:
                $config = $gamesConfig['longjiang'];

                $userParams = array();

                $userParams['money'] = $this->getAmount($order) * 100;
                $userParams['gold'] = $this->getGameCredits($order);
                $userParams['coupon'] = 0;
                $userParams['coin'] = 0;
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['username'] = $order->getTargetUserId();
                $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['payUrl']);

                $payArguments = array_merge($config, $userParams);

                $payRequest = new LongjiangPayRequest($payArguments);
                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //英雄王座
            case wpGameTable::YING_XIONG_WANG_ZUO:

                $config = $gamesConfig['yingxiongwangzuo'];
                $userParams = array();

                $userParams['PayNum'] = $this->getOrderIdByEnv($order->getId());//订单号
                $userParams['PayToUser'] = $order->getTargetUserName();//充值的帐号名
                $userParams['time'] = time();
                $userParams['PayGold'] = $this->getGameCredits($order);//充值元宝
                $userParams['PayToPlayer'] = 0;//充值的角色名 系统默认=0

                $payArguments = array_merge($config, $userParams);
                $payRequest = new YingxiongwangzuoPayRequest($payArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //热血三国
            case wpGameTable::RE_XUE_SAN_GUO:
                $config = $gamesConfig['rexuesanguo'];
                $userParams = array();

                $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['payUrl']);
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());//订单号
                $userParams['type'] = 0;//充值类型：直充(0)OR兑换(1)
                $userParams['passport'] = $order->getTargetUserId();//用户ID
                $userParams['money'] = $this->getAmount($order);//充值金额
                $userParams['coin'] = $this->getGameCredits($order);//兑换游戏币数
                $userParams['free_coin'] = 0;//赠送游戏币数
                $userParams['extra'] = '';//预留字段
                $userParams['method'] = 'game.pay';
                $userParams['req_time'] = time();

                $payArguments = array_merge($config, $userParams);
                $payRequest = new RexuesanguoPayRequest($payArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();

                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //欧冠足球
            case wpGameTable::OU_GUAN_ZU_QIU:
                $config = $gamesConfig['ouguanzuqiu'];
                $userParams = array();

                $userParams['uid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $ogzqQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $ogzqQueryArr[0];

                $userParams['uname'] = $order->getTargetUserName();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['time'] = date('YmdHis');
                $userParams['amount'] = $this->getAmount($order);
                $userParams['point'] = $this->getGameCredits($order);
                $userParams['roleid'] = $order->getRoleUserId();
                $userParams['rolename'] = $order->getRoleUserName();

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new OguanzuqiuPayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //吞食三国
            case wpGameTable::TUN_SHI_SAN_GUO:
                $config = $gamesConfig['tunshisanguo'];
                $userParams = array();

                $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['payUrl']);
                $userParams['uid'] = $order->getTargetUserId();
                $userParams['gold'] = $this->getGameCredits($order);//兑换游戏币数
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new TunshisanguoPayRequest($payRequestArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //博雅扑克
            case wpGameTable::POKER:
                $config = $gamesConfig['poker'];
                $userParams = array();

                $userParams['app_user_id'] = $order->getTargetUserId();
                $userParams['app_site_id'] = $order->getWpserver()->getServerNo();
                $userParams['app_create'] = time();
                $userParams['method'] = 'pay.changebyb';
                $userParams['order_id'] = 0;
                $userParams['order_pmount'] = $this->getGameCredits($order);
                $userParams['order_pdealno'] = $this->getOrderIdByEnv($order->getId());
                $userParams['order_pbank'] = 'alipay';
                $userParams['order_status'] = 1;

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new PokerPayRequest($payRequestArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;
            //凡人修真
            case wpGameTable::FAN_REN_XIU_ZHEN:
                $config = $gamesConfig['fanrenxiuzhen'];
                $userParams = array();

                $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['payUrl']);
                $userParams['username'] = $order->getTargetUserId();
                $userParams['gold'] = $this->getGameCredits($order);//兑换游戏币数
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());
                $userParams['sid'] = 'S' . $order->getWpserver()->getServerNo();

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new FanrenxiuzhenPayRequest($payRequestArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //龙城
            case wpGameTable::LONG_CHENG:
                $config = $gamesConfig['longcheng'];
                $userParams = array();

                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['sid'] = $order->getWpserver()->getServerNo();
                $userParams['userid'] = $order->getTargetUserId();
                $userParams['username'] = $order->getTargetUserId();
                $userParams['actorid'] = '';
                $userParams['actorname'] = '';
                $userParams['money'] = $this->getAmount($order);//人民币
                $userParams['time'] = time();

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new LongchengPayRequest($payRequestArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');
                $this->logMessage('Pay response is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //热血球球2
            case wpGameTable::RE_XUE_QIU_QIU_2:
                $config = $gamesConfig['rexueqiuqiu2'];
                $userParams = array();

                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['hupu_uid'] = $order->getTargetUserId();
                $userParams['amount'] = $this->getGameCredits($order);//充值点券
                $userParams['time'] = time();
                $userParams['s'] = $order->getWpserver()->getServerNo();
                if ((int)$order->getWpserver()->getServerNo() > 1) {
                    $userParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $config['greenUrl']);
                }

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new Rexueqiuqiu2PayRequest($payRequestArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //篮球传奇
            case wpGameTable::LAN_QIU_CHUAN_QI:
                $lqcqConfig = $gamesConfig['lanqiuchuanqi'];

                $payParams = array();
                $payParams['UserID'] = $order->getTargetUserId();
                $payParams['Point'] = $this->getGameCredits($order);  //游戏币
                $payParams['OrderID'] = $this->getOrderIdByEnv($order->getId());
                $payParams['SubTime'] = date('Y-m-d H:i:s', time());

                if ($order->getWpserver()->getreason()) {
                    $lqcqQueryArr = explode('%%', $order->getWpserver()->getreason());
                    $payParams['payUrl'] = $lqcqQueryArr[0];
                } else {
                    $payParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $lqcqConfig['payUrl']);
                }

                $payRequestArguments = array_merge($lqcqConfig, $payParams);

                $payRequest = new LanqiuchuanqiPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //青蛇
            case wpGameTable::QING_SHE:
                $qsConfig = $gamesConfig['qingshe'];

                $payParams = array();
                $payParams['uid'] = $order->getTargetUserId();
                $payParams['uname'] = $order->getTargetUserName();
                $payParams['point'] = $this->getGameCredits($order);
                $payParams['amount'] = $this->getAmount($order);
                $payParams['order'] = $this->getOrderIdByEnv($order->getId());
                $payParams['serverid'] = $order->getWpserver()->getServerNo();
                $payParams['rolename'] = '';
                $payParams['roleid'] = '';
                $payParams['time'] = date('YmdHis', time());
                $payParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $qsConfig['payUrl']);

                $payRequestArguments = array_merge($qsConfig, $payParams);

                $payRequest = new QingshePayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::HUPU_WORLD:
                $config = $gamesConfig['hupuworld'];
                $userParams = array();

                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['username'] = $order->getTargetUserName();
                $userParams['coins'] = $this->getGameCredits($order);//充值点券
                $userParams['time'] = time();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new HupuworldPayRequest($payRequestArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse . 'Url: ' . $payRequest->getUrl(), 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //梦幻飞仙
            case wpGameTable::MENG_HUAN_FEI_XIAN:
                $config = $gamesConfig['menghuanfeixian'];
                $userParams = array();

                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['uid'] = $order->getTargetUserId();
                $userParams['username'] = $order->getTargetUserName();
                $userParams['amount'] = $this->getAmount($order);//人民币
                $userParams['time'] = time();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new MenghuanfeixianPayRequest($payRequestArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay response is ' . (string)$payResponse . 'Url: ' . $payRequest->getUrl(), 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //热血篮球
            case wpGameTable::RE_XUE_LAN_QIU:
                $rxlqConfig = $gamesConfig['rexuelanqiu'];

                $payParams = array();
                $payParams['account'] = $order->getTargetUserId();
                $payParams['coin'] = $this->getGameCredits($order);
                $payParams['rmb'] = $this->getAmount($order);
                $payParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $payParams['ts'] = time();
                $payParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $rxlqConfig['payUrl']);

                $payRequestArguments = array_merge($rxlqConfig, $payParams);

                $payRequest = new RexuelanqiuPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //植物大战僵尸
            case wpGameTable::ZHI_WU_JIANG_SHI:
                $zwjsConfig = $gamesConfig['zhiwudazhanjiangshi'];

                $payParams = array();
                $payParams['uid'] = $order->getTargetUserId();
                $payParams['money'] = $this->getGameCredits($order);
                $payParams['serverid'] = $order->getWpserver()->getServerNo();
                $payParams['gameid'] = wpGameTable::ZHI_WU_JIANG_SHI;
                $payParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $payParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $zwjsConfig['payUrl']);

                $payRequestArguments = array_merge($zwjsConfig, $payParams);

                $payRequest = new ZwjsPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //冠军足球
            case wpGameTable::GUAN_JUN_ZU_QIU:
                $gjzqConfig = $gamesConfig['guanjunzuqiu'];

                $payParams = array();
                $payParams['userid'] = $order->getTargetUserId();
                $payParams['gold'] = $this->getGameCredits($order);//充值点券
                $payParams['serverid'] = $order->getWpserver()->getServerNo();
                $payParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $payParams['call_id'] = time();
                //$payParams['payUrl'] = $gjzqConfig['payUrl'];

                $payRequestArguments = array_merge($gjzqConfig, $payParams);

                $payRequest = new GjzqPayRequest($payRequestArguments);
                $payResponse = $payRequest->send();

                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //名将传说
            case wpGameTable::MING_JIANG_CHUAN_SHUO:
                $mjcsConfig = $gamesConfig['mingjiangchuanshuo'];
                $payParams = array();

                $payParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $mjcsConfig['payUrl']);
                $payParams['userid'] = $order->getTargetUserId();
                $payParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $payParams['tp'] = time();
                $payParams['gold'] = $this->getGameCredits($order);
                $payParams['playerid'] = $order->getRoleUserId();

                $payRequestArguments = array_merge($mjcsConfig, $payParams);
                $payRequest = new MjcsPayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //水浒豪侠
            case wpGameTable::SHUI_HU_HAO_XIA:
                $shhxConfig = $gamesConfig['shuihuhaoxia'];
                $payParams = array();

                $payParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $shhxConfig['payUrl']);
                $payParams['qid'] = $order->getTargetUserId();
                $payParams['act'] = 'pay';
                $payParams['server_id'] = $order->getWpserver()->getServerNo();
                $payParams['orderno'] = $this->getOrderIdByEnv($order->getId());
                $payParams['time'] = time();
                $payParams['money'] = $this->getGameCredits($order);
                $payParams['ip'] = '';

                $payRequestArguments = array_merge($shhxConfig, $payParams);
                $payRequest = new ShhxPayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //热血海贼王
            case wpGameTable::RE_XUE_HAI_ZEI_WANG:
                $rxhzwConfig = $gamesConfig['rexuehaizeiwang'];
                $payParams = array();

                $payParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $rxhzwConfig['payUrl']);
                $payParams['qid'] = $order->getTargetUserId();
                $payParams['server_id'] = $order->getWpserver()->getServerNo();
                $payParams['order_id'] = $this->getOrderIdByEnv($order->getId());
                $payParams['order_amount'] = $this->getAmount($order);//rmb

                $payRequestArguments = array_merge($rxhzwConfig, $payParams);
                $payRequest = new RxhzwPayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //封神无敌
            case wpGameTable::FENG_SHEN_WU_DI:
                $fswdConfig = $gamesConfig['fengshenwudi'];
                $payParams = array();

                $payParams['serverid'] = $order->getWpserver()->getServerNo();
                $payParams['username'] = $order->getTargetUserId();
                $payParams['eventtime'] = date('YmdHis');
                $payParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $payParams['gamemoney'] = $this->getGameCredits($order);//游戏币

                $payRequestArguments = array_merge($fswdConfig, $payParams);
                $payRequest = new fswdPayRequest($payRequestArguments);

                $payResponse = $payRequest->send();
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //三国演义
            case wpGameTable::SAN_GUO_YAN_YI:
                $sgyyConfig = $gamesConfig['sanguoyanyi'];
                $payParams = array();

                $payParams['payUrl'] = str_replace('%%serverNumber%%', $order->getWpserver()->getServerNo(), $sgyyConfig['payUrl']);
                $payParams['userName'] = $order->getTargetUserId();
                $payParams['serverId'] = 'S' . $order->getWpserver()->getServerNo();
                $payParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                $payParams['chargeMoney'] = $this->getAmount($order);//rmb
                $payParams['clientIp'] = '127.0.0.1';

                $payRequestArguments = array_merge($sgyyConfig, $payParams);
                $payRequest = new SgyyPayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //胜利11人
            case wpGameTable::SHENG_LI_11_REN:
                $sl11Config = $gamesConfig['shengli11ren'];
                $payParams = array();

                $payParams['accountName'] = $order->getTargetUserId();
                $payParams['serverId'] = $order->getWpserver()->getServerNo();
                $payParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                $payParams['amount'] = $this->getAmount($order);//rmb
                $payParams['time'] = time() . substr(microtime(), 2, 3);

                $payRequestArguments = array_merge($sl11Config, $payParams);
                $payRequest = new SL11PayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay url is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //水煮篮球
            case wpGameTable::SHUI_ZHU_LAN_QIU:
                $szlqConfig = $gamesConfig['shuizhulanqiu'];
                $payParams = array();

                $payParams['payUrl'] = $szlqConfig['payUrl'];
                $payParams['U'] = $order->getTargetUserId();
                $payParams['T'] = time();
                $payParams['P'] = $szlqConfig['P'];
                $payParams['UT'] = $szlqConfig['UT'];
                $payParams['Type'] = $szlqConfig['payType'];
                $payParams['S'] = $order->getWpserver()->getServerNo();
                $payParams['C'] = $order->getRoleUserId();
                $payParams['CN'] = $order->getRoleUserName();
                $payParams['PM'] = $this->getAmount($order);//rmb
                $payParams['PC'] = $this->getGameCredits($order);//金币
                $payParams['PT'] = $szlqConfig['PT'];
                $payParams['OI'] = $this->getOrderIdByEnv($order->getId());
                $payParams['key'] = $szlqConfig['paykey'];

                $payRequest = new ShuizhulanqiuPayRequest($payParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            case wpGameTable::DA_XIA_ZHUAN:
                $dxzConfig = $gamesConfig['daxiazhuan'];
                $payParams = array();

                $payParams['payUrl'] = $dxzConfig['payUrl'];
                $payParams['op_id'] = $dxzConfig['op_id'];
                $payParams['sid'] = $order->getWpserver()->getServerNo();
                $payParams['game_id'] = $dxzConfig['game_id'];
                $payParams['account'] = $order->getTargetUserId();
                $payParams['order_id'] = $this->getOrderIdByEnv($order->getId());
                $payParams['game_money'] = $this->getGameCredits($order);//金币
                $payParams['u_money'] = $this->getAmount($order);//rmb
                $payParams['time'] = time();
                $payParams['key'] = $dxzConfig['key'];

                $payRequest = new DaxiazhuanPayRequest($payParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;


            case wpGameTable::WU_XIA_QUAN_MING_XING:
                $wxqmxConfig = $gamesConfig['wuxiaquanmingxing'];
                $payParams = array();

                $payParams['payUrl'] = $wxqmxConfig['payUrl'];
                $payParams['user'] = $order->getTargetUserId();
                $payParams['point'] = $this->getGameCredits($order);//金币
                $payParams['dateTime'] = time();
                $payParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $payParams['serverid'] = $order->getWpserver()->getServerNo();
                $payParams['key'] = $wxqmxConfig['key'];

                $payRequest = new WuxiaquanmingxingPayRequest($payParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            case wpGameTable::LV_YIN_HAO_MEN:

                $lyhmconfig = $gamesConfig['lvyinhaomen'];
                $payParams = array();

                //$payParams['payUrl'] = $lyhmconfig['payUrl'];
                $payParams['openid'] = $order->getTargetUserId();
                $payParams['sid'] = $order->getWpserver()->getServerNo();
                $payParams['gid'] = $lyhmconfig['gid'];
                $payParams['rid'] = '-1';  //角色id未知时填 -1
                $payParams['pf'] = $lyhmconfig['pf'];
                $payParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $payParams['money'] = $this->getAmount($order);//rmb
                $payParams['gameb'] = $this->getGameCredits($order);//金币
                $payParams['t'] = time();
                $payParams['userip'] = '127.0.0.1';
                //$payParams['key']    = $lyhmconfig['key'];	

                $payRequest = new LvyinhaomenPayRequest($lyhmconfig['payUrl'], $payParams, $lyhmconfig['key']);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payRequest, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::SHEN_JIANG_SAN_GUO:

                $sjsgconfig = $gamesConfig['shenjiangsanguo'];
                $payParams = array();

                $sjsgQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjsgQueryArr[0];

                $userParams['game'] = $sjsgconfig['game'];
                $userParams['agent'] = $sjsgconfig['agent'];
                $userParams['user'] = $order->getTargetUserId();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['money'] = $this->getAmount($order);//rmb
                $userParams['server'] = $order->getWpserver()->getServerNo();
                $userParams['time'] = time();
                $userParams['key'] = $sjsgconfig['paykey'];

                $payRequest = new ShenjiangsanguoPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payRequest, 'err');
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::TIAN_JIE:

                $tjconfig = $gamesConfig['tianjie'];
                $payParams = array();

                $tjQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $tjQueryArr[0];

                $userParams['pid'] = $tjconfig['pid'];
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());
                $userParams['uid'] = $order->getTargetUserId();
                $userParams['gid'] = $tjconfig['gid'];
                $userParams['sid'] = $order->getWpserver()->getServerNo();
                $userParams['money'] = $this->getAmount($order);//rmb
                $userParams['order_amount'] = $this->getAmount($order);//rmb
                $userParams['point'] = $this->getGameCredits($order);//金币
                $userParams['key'] = $tjconfig['key'];

                $payRequest = new TianjiePayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::SAN_GUO_LI_ZHI_ZHUAN:

                $tjconfig = $gamesConfig['sanguolizhizhuan'];
                $payParams = array();

                $tjQueryArr = explode('%%', $order->getWpserver()->getreason());

                list($s1, $s2) = explode(' ', microtime());
                $time1 = $s2 . ($s1 * 1000);
                $time2 = explode(".", $time1);
                $time = $time2[0];  //获取当前毫秒时间戳

                $userParams['payUrl'] = $tjQueryArr[0];
                $userParams['oid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['uid'] = $order->getTargetUserId();
                $userParams['amount'] = $this->getAmount($order);//rmb
                $userParams['coins'] = $this->getGameCredits($order);//金币
                $userParams['time'] = $time;
                $userParams['key'] = $tjconfig['key'];

                $payRequest = new SanguolizhizhuanPayRequest($userParams);
                $payResponse = $payRequest->pay();
                //var_dump($payResponse);
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::HAN_JIANG_CHUAN_SHI:
                $hjcsconfig = $gamesConfig['hanjiangchuanshi'];
                $payParams = array();

                $tjQueryArr = explode('%%', $order->getWpserver()->getreason());

                $userParams['payUrl'] = $tjQueryArr[0];
                $userParams['method'] = $hjcsconfig['method'];
                $userParams['version'] = $hjcsconfig['version'];
                $userParams['client_ip'] = $_SERVER["REMOTE_ADDR"];
                $userParams['serial_no'] = md5($this->getOrderIdByEnv($order->getId())); //订单号
                $userParams['user_domain'] = $hjcsconfig['user_domain'];
                $userParams['matrix_id'] = $hjcsconfig['matrix_id'];
                $userParams['paytype_id'] = $hjcsconfig['paytype_id'];
                $userParams['topup_point'] = $this->getGameCredits($order);//金币
                $userParams['topup_time'] = date('Y-m-d H:i:s');
                $userParams['secretkey'] = $hjcsconfig['secretkey'];
                $userParams['user_id'] = $order->getTargetUserId();

                $payRequest = new HanjiangchuanshiPayRequest($userParams);
                $payResponse = $payRequest->pay();
                //var_dump($payResponse);
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::WU_SHUANG_SAN_GUO:
                $hjcsconfig = $gamesConfig['wushuangsanguo'];
                $payParams = array();

                $tjQueryArr = explode('%%', $order->getWpserver()->getreason());

                $userParams['payUrl'] = $tjQueryArr[0];
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId()); //订单号
                $userParams['order_amount'] = $this->getAmount($order);//rmb
                $userParams['pid'] = $hjcsconfig['pid'];
                $userParams['server_id'] = $order->getWpserver()->getServerNo();
                $userParams['time'] = time();
                $userParams['key'] = $hjcsconfig['key'];
                $userParams['qid'] = $order->getTargetUserId();

                $payRequest = new WushuangsanguoPayRequest($userParams);
                $payResponse = $payRequest->pay();
                //var_dump($payResponse);
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::JIU_FA_ZHONG_YUAN:

                $jfzyconfig = $gamesConfig['jiufazhongyuan'];
                $payParams = array();

                $jfzyQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $jfzyQueryArr[0];
                $userParams['u'] = $order->getTargetUserId();
                $userParams['op'] = $jfzyconfig['op'];
                $userParams['g'] = $jfzyconfig['g'];
                $userParams['s'] = $order->getWpserver()->getServerNo();
                $userParams['role_id'] = $jfzyconfig['role_id'];
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['amount'] = $this->getGameCredits($order);//金币
                $userParams['time'] = time();
                $userParams['op_key'] = $jfzyconfig['op_key'];

                $payRequest = new JiufazhongyuanPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::BA_YU:

                $bayuconfig = $gamesConfig['bayu'];
                $payParams = array();

                $bayuQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $bayuQueryArr[0];

                $userParams['uid'] = $order->getTargetUserId();
                $userParams['server_id'] = $order->getWpserver()->getServerNo();
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());
                $userParams['order_amount'] = $this->getGameCredits($order);//金币
                $userParams['time'] = time();
                $userParams['key'] = $bayuconfig['key'];

                $payRequest = new BayuPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::ZHAN_SAN_GUO:

                $zhansanguoconfig = $gamesConfig['zhansanguo'];
                $payParams = array();

                $zhansanguoQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $zhansanguoQueryArr[0];

                $userParams['from'] = $zhansanguoconfig['from'];
                $userParams['game'] = $zhansanguoconfig['game'];
                $userParams['user_id'] = $order->getTargetUserId();
                $userParams['server'] = $order->getWpserver()->getServerNo();
                $userParams['amount'] = ($this->getAmount($order) * 100); //rmb,单位分
                $userParams['order_number'] = $this->getOrderIdByEnv($order->getId());  //订单号     
                $userParams['t'] = time();
                $userParams['transfer_secret_signature'] = $zhansanguoconfig['transfer_secret_signature'];

                $payRequest = new ZhansanguoPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::GOU_ZU_QIU:

                $gouzuqiuconfig = $gamesConfig['gouzuqiu'];
                $payParams = array();

                $gouzuqiuQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $gouzuqiuQueryArr[0];
                $userParams['UserIP'] = $_SERVER["REMOTE_ADDR"];
                $userParams['UserID'] = $order->getTargetUserId();
                $userParams['ServerID'] = $order->getWpserver()->getServerNo();
                $userParams['PayPoint'] = $this->getAmount($order); //rmb,单位分
                $userParams['OrderID'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['GamePoint'] = $this->getGameCredits($order);//金币        
                $userParams['key'] = $gouzuqiuconfig['key'];

                $payRequest = new GouzuqiuPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::SAN_GUO_LUN_JIAN:

                $sanguolunjianconfig = $gamesConfig['sanguolunjian'];
                $payParams = array();

                $sanguolunjianQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sanguolunjianQueryArr[0];
                $userParams['user'] = $order->getTargetUserId();
                $userParams['server'] = $order->getWpserver()->getServerNo();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['tombo'] = $this->getGameCredits($order);//金币        
                $userParams['key'] = $sanguolunjianconfig['key'];
                $userParams['opt'] = $sanguolunjianconfig['opt'];

                $payRequest = new SanguolunjianPayRequest($userParams);
                //echo $payRequest;
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::KAN_QIU_BAN_LV:
                $kanqiubanlvconfig = $gamesConfig['kanqiubanlv'];

                $queryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $queryArr[0];
                $userParams['pid'] = $order->getCreateUserName();
                $userParams['money'] = $this->getAmount($order); //rmb,单位分
                $userParams['time'] = time();
                $userParams['status'] = 1; //1成功，2失败
                $userParams['key'] = $kanqiubanlvconfig['key'];
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());  //订单号   

                $payRequest = new KanqiubanlvPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');
                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            case wpGameTable::QUAN_MING_XING_ZU_QIU:

                $qmxzqconfig = $gamesConfig['quanmingxingzuqiu'];
                $payParams = array();

                $qmxzqQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $qmxzqQueryArr[0];
                $userParams['qid'] = $order->getTargetUserId();
                $userParams['server_id'] = $order->getWpserver()->getServerNo();
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                //$userParams['tombo']  = $this->getGameCredits($order);//金币        
                $userParams['order_amount'] = $this->getAmount($order); //rmb
                $userParams['key'] = $qmxzqconfig['key'];
                $userParams['pid'] = $qmxzqconfig['pid'];
                $userParams['time'] = time();

                $payRequest = new QuanmingxingzuqiuPayRequest($userParams);
                //echo $payRequest;
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;


            case wpGameTable::WAN_ZHUAN_NBA:
                $wanzhuannbaconfig = $gamesConfig['wanzhuannba'];

                $queryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $queryArr[0];
                $userParams['id'] = $order->getTargetUserId();
                $userParams['amount'] = $this->getAmount($order); //rmb
                $userParams['pid'] = $order->getCreateUserName();
                $userParams['time'] = time();
                $userParams['key'] = $wanzhuannbaconfig['key'];
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());  //订单号   

                $payRequest = new wanzhuannbaPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');
                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;
            //街机三国
            case wpGameTable::JIE_JI_SAN_GUO:

                $jjsgconfig = $gamesConfig['jiejisanguo'];
                $payParams = array();

                $jjsgQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $jjsgQueryArr[0];
                $userParams['user'] = $order->getTargetUserId();
                $userParams['server_id'] = $order->getWpserver()->getServerNo();
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['pay_amount'] = $this->getGameCredits($order);//金币        
                $userParams['amount'] = $this->getAmount($order); //rmb
                $userParams['key'] = $jjsgconfig['key'];
                $userParams['platform'] = $jjsgconfig['platform'];
                $userParams['time'] = time();

                $payRequest = new JiejisanguoPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //烈焰
            case wpGameTable::LIE_YAN:

                $lyconfig = $gamesConfig['lieyan'];
                $payParams = array();

                $lyQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $lyQueryArr[0];
                $userParams['user'] = $order->getTargetUserId();
                $userParams['server'] = $order->getWpserver()->getServerNo();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['coin'] = $this->getGameCredits($order);//金币        
                $userParams['money'] = $this->getAmount($order); //rmb
                $userParams['key'] = $lyconfig['key'];
                $userParams['game'] = $lyconfig['game'];
                $userParams['agent'] = $lyconfig['agent'];
                $userParams['time'] = time();

                $payRequest = new LieyanPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //空中大灌篮
            case wpGameTable::DA_GUAN_LAN:

                $dglconfig = $gamesConfig['daguanlan'];
                $payParams = array();

                $dglQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $dglQueryArr[0];
                $userParams['uid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['billno'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['amount'] = $this->getGameCredits($order);//金币        
                $userParams['pay_amount'] = $this->getAmount($order); //rmb
                $userParams['key'] = $dglconfig['key'];
                $userParams['platform'] = $dglconfig['platform'];
                $userParams['time'] = time();

                $payRequest = new daguanlanPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //奇迹篮球
            case wpGameTable::QI_JI_LAN_QIU:

                $qjlqconfig = $gamesConfig['qijilanqiu'];
                $payParams = array();

                $qjlqQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $qjlqQueryArr[0];
                $userParams['userid'] = $order->getTargetUserId();
                // $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                // $userParams['pay_amount']  = $this->getGameCredits($order);//金币        
                $userParams['gold'] = $this->getAmount($order); //rmb
                $userParams['key'] = $qjlqconfig['key'];
                // $userParams['platform'] = $qjlqconfig['platform'];
                $userParams['time'] = time();

                $payRequest = new qijilanqiuPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //江湖风云
            case wpGameTable::JIANG_HU_FENG_YUN:

                $jhfyconfig = $gamesConfig['jianghufengyun'];
                $payParams = array();

                $jhfyQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $jhfyQueryArr[0];
                $userParams['userName'] = $order->getTargetUserId();
                //$userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['billno'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['gold'] = $this->getGameCredits($order);//金币        
                $userParams['money'] = $this->getAmount($order); //rmb
                $userParams['key'] = $jhfyconfig['key'];
                $userParams['time'] = time();

                $payRequest = new jianghufengyunPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //中超足球
            case wpGameTable::ZHONG_CHAO_ZU_QIU:
                $config = $gamesConfig['zhongchaozuqiu'];
                $userParams = array();

                $userParams['uid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $zczqQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $zczqQueryArr[0];

                $userParams['uname'] = $order->getTargetUserName();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['time'] = date('YmdHis');
                $userParams['amount'] = $this->getAmount($order);//rmb
                $userParams['point'] = $this->getGameCredits($order);//金币
                $userParams['roleid'] = $order->getRoleUserId();
                $userParams['rolename'] = $order->getRoleUserName();

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new zhongchaozuqiuPayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //女神联盟
            case wpGameTable::NV_SHEN_LIAN_MENG:

                $nslmconfig = $gamesConfig['nvshenlianmeng'];
                $payParams = array();

                $nslmQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $nslmQueryArr[0];
                $userParams['account'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['game_money'] = $this->getGameCredits($order);//金币        
                $userParams['u_money'] = $this->getAmount($order); //rmb
                $userParams['key'] = $nslmconfig['key'];
                $userParams['op_id'] = $nslmconfig['op_id'];
                $userParams['sid'] = $nslmconfig['sid'];
                $userParams['game_id'] = $nslmconfig['game_id'];
                $userParams['time'] = time();

                $payRequest = new nvshenlianmengPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //兄弟篮球2
            case wpGameTable::XIONG_DI_LAN_QIU_2:
                $xdlq2Config = $gamesConfig['xiongdilanqiu2'];
                $payParams = array();

                $xdlq2QueryArr = explode('%%', $order->getWpserver()->getreason());
                $payParams['payUrl'] = $xdlq2QueryArr[0];
                $payParams['U'] = $order->getTargetUserId();
                $payParams['T'] = time();
                $payParams['P'] = $xdlq2Config['P'];
                $payParams['UT'] = $xdlq2Config['UT'];
                $payParams['Type'] = $xdlq2Config['payType'];
                $payParams['S'] = $order->getWpserver()->getServerNo();
                $payParams['C'] = $order->getRoleUserId();
                $payParams['CN'] = $order->getRoleUserName();
                $payParams['PM'] = $this->getAmount($order);//rmb
                $payParams['PC'] = $this->getGameCredits($order);//金币
                $payParams['PT'] = $xdlq2Config['PT'];
                $payParams['OI'] = $this->getOrderIdByEnv($order->getId());
                $payParams['key'] = $xdlq2Config['paykey'];

                $payRequest = new Xiongdilanqiu2PayRequest($payParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //武易传奇
            case wpGameTable::WU_YI:

                $wyconfig = $gamesConfig['wuyi'];
                $payParams = array();

                $wyQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $wyQueryArr[0];
                $userParams['userName'] = $order->getTargetUserId();
                $userParams['server_num'] = $order->getWpserver()->getServerNo();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['gold'] = $this->getGameCredits($order);//金币        
                $userParams['rmb'] = $this->getAmount($order); //rmb
                $userParams['key'] = $wyconfig['key'];
                $userParams['spid'] = $wyconfig['spid'];

                $payRequest = new WyPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //天才樱木
            case wpGameTable::TIAN_CAI_YING_MU:

                $tcymconfig = $gamesConfig['tiancaiyingmu'];
                $payParams = array();

                $tcymQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $tcymQueryArr[0];
                $userParams['username'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                //$userParams['gold']  = $this->getGameCredits($order);//金币        
                $userParams['money'] = $this->getAmount($order); //rmb
                $userParams['payKey'] = $tcymconfig['payKey'];

                $payRequest = new TiancaiyingmuPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //萌卡篮球
            case wpGameTable::MENG_KA_LAN_QIU:
                $mengkalanqiuconfig = $gamesConfig['mengkalanqiu'];

                $queryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $queryArr[0];
                $userParams['userid'] = $order->getTargetUserId();
                $userParams['rmb'] = $this->getAmount($order); //rmb
                $userParams['partnerid'] = 'hupu';
                $userParams['eventtime'] = date('YmdHis', time());
                $userParams['gameid'] = 81;
                $userParams['key'] = $mengkalanqiuconfig['key'];
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());  //订单号  
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $payRequest = new mengkalanqiuPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');
                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //奇迹来了
            case wpGameTable::QI_JI_LAI_LE:

                $qjllconfig = $gamesConfig['qijilaile'];
                $payParams = array();

                $qjllQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $qjllQueryArr[0];
                $userParams['uid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['billno'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['amount'] = $this->getGameCredits($order);//金币        
                $userParams['pay_amount'] = $this->getAmount($order); //rmb
                $userParams['key'] = $qjllconfig['key'];
                $userParams['platform'] = $qjllconfig['platform'];
                $userParams['time'] = time();

                $payRequest = new qijilailePayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //巅峰美职篮
            case wpGameTable::MEI_ZHI_LAN:

                $mzlconfig = $gamesConfig['meizhilan'];
                $payParams = array();

                $mzlQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $mzlQueryArr[0];
                $userParams['userName'] = $order->getTargetUserId();
                $userParams['platform'] = 'hupu';
                $userParams['serverName'] = $order->getWpserver()->getServerNo();
                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['money'] = $this->getGameCredits($order);//金币        
                $userParams['rmb'] = $this->getAmount($order); //rmb
                $userParams['payKey'] = $mzlconfig['payKey'];

                $payRequest = new MeizhilanPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //新篮球
            case wpGameTable::XIN_LAN_QIU:
                $xinlanqiuconfig = $gamesConfig['xinlanqiu'];

                $queryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $queryArr[0];
                $userParams['userid'] = $order->getTargetUserId();
                $userParams['rmb'] = $this->getAmount($order); //rmb
                $userParams['partnerid'] = 'hupu';
                $userParams['eventtime'] = date('YmdHis', time());
                $userParams['gameid'] = 85;
                $userParams['key'] = $xinlanqiuconfig['key'];
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());  //订单号  
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $payRequest = new xinlanqiuPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');
                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //仙侠道
            case wpGameTable::XIAN_XIA_DAO:
                $xianxiadaoconfig = $gamesConfig['xianxiadao'];

                $queryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $queryArr[0];
                $userParams['user'] = $order->getTargetUserId();
                $userParams['money'] = $this->getAmount($order); //rmb
                $userParams['key'] = $xianxiadaoconfig['key'];
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());  //订单号  
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $payRequest = new XianxiadaoPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');
                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //巴西世界杯
            case wpGameTable::BA_XI_SHI_JIE_BEI:
                $baxishijiebeiconfig = $gamesConfig['baxishijiebei'];

                $queryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $queryArr[0];
                $userParams['userid'] = $order->getTargetUserId();
                $userParams['rmb'] = $this->getAmount($order); //rmb
                $userParams['partnerid'] = 'hupu';
                $userParams['eventtime'] = date('YmdHis', time());
                $userParams['gameid'] = 87;
                $userParams['key'] = $baxishijiebeiconfig['key'];
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());  //订单号  
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $payRequest = new baxishijiebeiPayRequest($userParams);
                $payResponse = $payRequest->pay();
                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');
                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //辉煌足球
            case wpGameTable::HUI_HUANG_ZU_QIU:

                $hhzqconfig = $gamesConfig['huihuangzuqiu'];
                $payParams = array();

                $hhzqQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $hhzqQueryArr[0];
                $userParams['user'] = $order->getTargetUserId();
                $userParams['server_id'] = $order->getWpserver()->getServerNo();
                $userParams['order_id'] = $this->getOrderIdByEnv($order->getId());  //订单号   
                $userParams['pay_amount'] = $this->getGameCredits($order);//金币        
                $userParams['amount'] = $this->getAmount($order); //rmb
                $userParams['key'] = $hhzqconfig['key'];
                $userParams['platform'] = $hhzqconfig['platform'];
                $userParams['time'] = time();

                $payRequest = new huihuangzuqiuPayRequest($userParams);

                $payResponse = $payRequest->pay();

                $this->logMessage('Pay Request is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();

                break;

            //巴西世界杯 页游 
            case wpGameTable::SHI_JIE_BEI:
                $config = $gamesConfig['shijiebei'];
                $userParams = array();

                $userParams['uid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0];

                $userParams['uname'] = $order->getTargetUserName();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['time'] = date('YmdHis');
                $userParams['amount'] = $this->getAmount($order);//rmb
                $userParams['point'] = $this->getGameCredits($order);//金币
                $userParams['roleid'] = $order->getRoleUserId();
                $userParams['rolename'] = $order->getRoleUserName();

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new shijiebeiPayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //非凡足球 
            case wpGameTable::FEI_FAN_ZU_QIU:
                $config = $gamesConfig['feifanzuqiu'];
                $userParams = array();

                $userParams['uid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0];

                $userParams['uname'] = $order->getTargetUserName();
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['time'] = time();
                $userParams['amount'] = $this->getAmount($order);//rmb
                $userParams['point'] = $this->getGameCredits($order);//金币

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new feifanzuqiuPayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //范特西篮球2 
            case wpGameTable::FAN_TE_XI_2:
                $config = $gamesConfig['fantexi2'];
                $userParams = array();

                // $userParams['uid'] = $order->getTargetUserId(); //虎扑id
                $userParams['accountName'] = $order->getTargetUserId(); //虎扑用户名

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL

                $userParams['serverId'] = $sjbQueryArr[2];
                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                $userParams['payTime'] = $this->getMicroTimestamp();
                $userParams['chargeMoney'] = $this->getAmount($order);//rmb
                $userParams['chargeAmount'] = $this->getGameCredits($order);//金币

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new fantexi2PayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //热血11人
            case wpGameTable::RE_XUE_11:
                $config = $gamesConfig['rexue11'];
                $userParams = array();

                $userParams['username'] = $order->getTargetUserId(); //虎扑用户id

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL

                // $userParams['serverId'] = $sjbQueryArr[2];
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['time'] = date('YmdHis', time());
                $userParams['money'] = $this->getAmount($order);//rmb
                // $userParams['chargeAmount'] = $this->getGameCredits($order);//金币

                $payRequestArguments = array_merge($config, $userParams);
                $payRequest = new rexue11PayRequest($payRequestArguments);

                $payResponse = $payRequest->pay();
                $this->logMessage('Pay response is ' . (string)$payResponse, 'err');

                if (!$payResponse->isSuccess()) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    $url = (string)$payRequest;
                    $result = $payResponse->getErrorInfo();
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                $url = (string)$payRequest;
                $result = $payResponse->getErrorInfo();
                break;

            //天下足球
            //充值时需要角色id
            case 93:
                $userParams = array();
                $userParams['uid'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['sid'] = $order->getWpserver()->getServerNo();
                $userParams['rid'] = $order->getRoleUserId();//角色id
                $userParams['fk'] = $this->getGameCredits($order);//饭卡
                // $userParams['money'] = $this->getAmount($order);//rmb

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new tianxiazuqiuPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //辉煌足球 手游
            case 94:
                $userParams = array();
                $userParams['userid'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                // $userParams['fk'] = $this->getGameCredits($order);//欧元
                $userParams['rmb'] = $this->getAmount($order);//rmb
                $userParams['gameid'] = 94;

                $userParams['transparent'] = $order->getTransparent();//透传参数

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new huihuangzuqiumobilePayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //足球梦之队 手游
            case 95:
                $userParams = array();
                $userParams['userid'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                // $userParams['fk'] = $this->getGameCredits($order); //欧元
                $userParams['rmb'] = $this->getAmount($order);//rmb
                $userParams['gameid'] = 95;

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new zuqiumengzhiduimobilePayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //夺冠之路 页游
            case 96:
                $userParams = array();
                $userParams['userid'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                // $userParams['fk'] = $this->getGameCredits($order); //欧元
                $userParams['rmb'] = $this->getAmount($order);//rmb
                $userParams['gameid'] = 96;

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new duoguanzhiluPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //欧冠2 页游
            case 97:
                $userParams = array();
                $userParams['uid'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['point'] = $this->getGameCredits($order); //欧元
                $userParams['amount'] = $this->getAmount($order);//rmb
                $userParams['roleid'] = $order->getRoleUserId();
                $userParams['gameid'] = 97;

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new ouguanzuqiu2PayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //风云无双 页游
            case 98:
                $userParams = array();
                $userParams['uid'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['order'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['point'] = $this->getGameCredits($order); //欧元
                $userParams['amount'] = $this->getAmount($order);//rmb
                $userParams['roleid'] = $order->getRoleUserId();
                $userParams['gameid'] = 98;

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new fengyunwushuangPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                } else {
                    $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                }

                break;

            //绝杀2014 手游
            case 99:
                $userParams = array();
                $userParams['userid'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                // $userParams['fk'] = $this->getGameCredits($order); //欧元
                $userParams['rmb'] = $this->getAmount($order);//rmb
                $userParams['gameid'] = 99;
                $userParams['transparent'] = $order->getTransparent();//透传参数

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new juesha2014PayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //狂野足球 页游
            case 100:
                $userParams = array();
                $userParams['userName'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['billno'] = $this->getOrderIdByEnv($order->getId());
                // $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['gold'] = $this->getGameCredits($order);
                $userParams['money'] = $this->getAmount($order) * 100; //单位是分
                // $userParams['transparent'] = $order->getTransparent();//透传参数
                $userParams['uid'] = $order->getRoleUserId(); //角色id
                // $userParams['rolename'] = $order->getRoleUserName();
                // $userParams['userIP']        = $order->getIp();
                // $userParams['ip'] = '60.12.147.108';
                $userParams['ip'] = $order->getIp();

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new kuangyezuqiuPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //攻城掠地 页游
            case 101:
                $userParams = array();
                $userParams['userId'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                // $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['gold'] = $this->getGameCredits($order);
                // $userParams['money'] = $this->getAmount($order);
                // $userParams['transparent'] = $order->getTransparent();//透传参数
                $userParams['playerId'] = $order->getRoleUserId(); //角色id
                // $userParams['rolename'] = $order->getRoleUserName();
                // $userParams['userIP']        = $order->getIp();
                // $userParams['ip'] = '60.12.147.108';
                // $userParams['ip'] = $order->getIp();

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new gongchengluediPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //全民大灌篮 手游
            case 102:
                $userParams = array();
                $userParams['userid'] = $order->getTargetUserId(); //虎扑用户id
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                // $userParams['fk'] = $this->getGameCredits($order); //欧元
                $userParams['rmb'] = $this->getAmount($order);//rmb
                $userParams['gameid'] = 102;
                $userParams['transparent'] = $order->getTransparent();//透传参数

                $arrPayUserUrl = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $arrPayUserUrl[0]; //获取支付URL

                $payResponse = new quanmindaguanlanPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //cba篮球经理 
            case 103:
                $userParams = array();

                // $userParams['uid'] = $order->getTargetUserId(); //虎扑id
                $userParams['accountName'] = $order->getTargetUserId(); //虎扑用户名

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL

                $userParams['serverId'] = $sjbQueryArr[2];
                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                $userParams['payTime'] = $this->getMicroTimestamp();
                $userParams['chargeMoney'] = $this->getAmount($order);//rmb
                $userParams['chargeAmount'] = $this->getGameCredits($order);//金币

                $payResponse = new cbalqPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //倍儿爽三国
            case 104:
                $userParams = array();

                $userParams['uid'] = $order->getTargetUserId();

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL

                $userParams['sid'] = $order->getWpserver()->getServerNo();
                $userParams['billno'] = $this->getOrderIdByEnv($order->getId());
                // $userParams['chargeMoney'] = $this->getAmount($order);//rmb
                $userParams['amount'] = $this->getGameCredits($order);//金币

                $payResponse = new beishuangsanguoPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //超级足球先生2
            case 105:
                $userParams = array();

                $userParams['uid'] = $order->getTargetUserId();

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL

                $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                $userParams['amount'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币

                $payResponse = new zqxs2PayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //实况俱乐部
            case 106:
                $userParams = array();

                $userParams['uid'] = $order->getTargetUserId();

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL

                $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                $userParams['amount'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币

                $payResponse = new czxs2PayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //天才足球经理
            case 107:
                $userParams = array();

                $userParams['userid'] = $order->getTargetUserId();

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL

                // $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['rmb'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                $userParams['transparent'] = $order->getTransparent();//透传参数

                $payResponse = new tczqjlPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //篮球飞人
            case 108:
                $userParams = array();

                $userParams['uid'] = $order->getTargetUserId();

                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL

                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['pay_amount'] = $this->getAmount($order) * 100;//rmb
                $userParams['amount'] = $this->getGameCredits($order);//金币

                $payResponse = new lqfrPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //网易融合sdk
            case 109:
                $userParams = array();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                $userParams['userid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['rmb'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                $userParams['transparent'] = $order->getTransparent();//透传参数

                $payResponse = new wangyisdkPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //绿茵战神
            case 110:
                $userParams = array();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                $userParams['UserID'] = $order->getTargetUserId();
                $userParams['ServerID'] = $order->getWpserver()->getServerNo();
                $userParams['OrderID'] = $this->getOrderIdByEnv($order->getId());
                $userParams['Money'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                //$userParams['transparent'] = $order->getTransparent();//透传参数手游使用

                $payResponse = new lyzsPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            // 一球成名3
            case 111:
                $userParams = array();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                $userParams['UserID'] = $order->getTargetUserId();
                $userParams['ServerID'] = $order->getWpserver()->getServerNo();
                $userParams['OrderID'] = $this->getOrderIdByEnv($order->getId());
                $userParams['Money'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                //$userParams['transparent'] = $order->getTransparent();//透传参数手游使用

                $payResponse = new yqcmPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;
            // NBA英雄
            case 112:
                $userParams = array();
                $userParams['userid'] = $order->getTargetUserId();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                // $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['rmb'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                $userParams['transparent'] = $order->getTransparent();//透传参数
                $payResponse = new nbayxPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;
                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }
                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            // 足球小将
            case 113:
                $userParams = array();
                $userParams['userid'] = $order->getTargetUserId();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                // $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['rmb'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                $userParams['transparent'] = $order->getTransparent();//透传参数
                $payResponse = new zqxjPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;
                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }
                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            // 篮球飞人手游
            case 114:
                $userParams = array();
                $userParams['userid'] = $order->getTargetUserId();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                // $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['rmb'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                $userParams['transparent'] = $order->getTransparent();//透传参数
                $payResponse = new sylqfrPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;
                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }
                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            // 天才樱木手游
            case 115:
                $userParams = array();
                $userParams['userid'] = $order->getTargetUserId();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                // $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['rmb'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                $userParams['transparent'] = $order->getTransparent();//透传参数
                $payResponse = new sytcymPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;
                $this->logMessage('Pay response is ' . $result, 'err');
                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }
                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            // 乔峰传
            case 116:
                $userParams = array();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                $userParams['UserID'] = $order->getTargetUserId();
                $userParams['ServerID'] = $order->getWpserver()->getServerNo();
                $userParams['OrderID'] = $this->getOrderIdByEnv($order->getId());
                $userParams['Money'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                //$userParams['transparent'] = $order->getTransparent();//透传参数手游使用

                $payResponse = new qfzPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            // 足球大师
            case 117:
                $userParams = array();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                $userParams['userid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                // $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['orderid'] = 'HC' . $order->getOrderNo();
                $userParams['rmb'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                $userParams['transparent'] = $order->getTransparent();//透传参数手游使用

                $payResponse = new zqdsPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            // 卡牌篮球
            case 118:
                $userParams = array();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                $userParams['UserID'] = $order->getTargetUserId();
                $userParams['ServerID'] = $order->getWpserver()->getServerNo();
                $userParams['OrderID'] = $this->getOrderIdByEnv($order->getId());
                $userParams['Money'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                //$userParams['transparent'] = $order->getTransparent();//透传参数手游使用

                $payResponse = new kplqPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //少年三国志
            case 119:
                $userParams = array();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                $userParams['userid'] = $order->getTargetUserId();
                $userParams['serverid'] = $order->getWpserver()->getServerNo();
                $userParams['orderid'] = $this->getOrderIdByEnv($order->getId());
                $userParams['rmb'] = $this->getAmount($order);//rmb
                // $userParams['amount'] = $this->getGameCredits($order);//金币
                $userParams['transparent'] = $order->getTransparent();//透传参数手游使用

                $payResponse = new snsgzPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            //莽荒纪
            case 136:
                $userParams = array();
                $sjbQueryArr = explode('%%', $order->getWpserver()->getreason());
                $userParams['payUrl'] = $sjbQueryArr[0]; //获取支付URL
                $userParams['gameId'] = $sjbQueryArr[1]; //获取gameid
                $userParams['serverId'] = $order->getWpserver()->getServerNo();
                $userParams['platformId'] = $sjbQueryArr[2]; //获取platformid
                $userParams['key'] = $sjbQueryArr[4]; //获取秘钥
                $userParams['userId'] = $order->getTargetUserId();
                $userParams['orderId'] = $this->getOrderIdByEnv($order->getId());
                $userParams['money'] = $this->getAmount($order);//rmb

                $payResponse = new mhjPayRequest($userParams);
                $url = $payResponse->requestUrl;
                $result = $payResponse->message;

                $this->logMessage('Pay response is ' . $result, 'err');

                if (!$payResponse->is_success) {
                    $r = wpOrderTable::STATUS_TRANSFER_FAILED;
                    break;
                }

                $r = wpOrderTable::STATUS_TRANSFER_SUCCESS;
                break;

            default:
                break;
        }

        if ($r != wpOrderTable::STATUS_TRANSFER_SUCCESS) {
            gameLog::error('transfer error', array(
                'orderid' => $order->getId(),
                'url' => $payResponse->getUrl()
            ));
        }
        // Save payment result
        $order->setStatus($r);
        $order->save();

        $wpserver_id = $order->getWpserver()->getId();
        $wporder_id = $order->getId();
        $intGameid = $order->getWpgame()->getId();
        $intOrderStatus = $order->getStatus();

        // Write history
        wpTransferHistoryTable::addLog(array('wpserver_id' => $wpserver_id,
            'wporder_id' => $wporder_id,
            'action' => $url,
            'return_value' => $result,
            'result' => $r - 4));

        //同步出错的订单信息
        if ($intOrderStatus == 2 || $intOrderStatus == 3 || $intOrderStatus == 4) {
            $wporder_no = $order->getOrderNo();
            $create_user_name = $order->getCreateUserName();
            $create_user_id = $order->getCreateUserId();
            $target_user_name = $order->getTargetUserName();
            $target_user_id = $order->getTargetUserId();

            $error_info = array(
                'gameid' => $intGameid,
                'serverid' => $wpserver_id,
                'orderno' => $wporder_no,
                'status' => $intOrderStatus,
                'create_user_name' => $create_user_name,
                'create_user_id' => $create_user_id,
                'target_user_name' => $target_user_name,
                'target_user_id' => $target_user_id,
                'url' => $url,
                'result' => $result
            );
            $objPublicFun = new PublicFun;
            $objPublicFun->curl_post('http://youxi.hupu.com/gamepayInterface/payerror', $error_info);
        }

        return $r - 4;
    }

    /**
     * Switch order number according to environment
     *
     *  In the test environment, the prefix of the
     *  order id is 'TSA', 'TS' means test,
     *  'A' means server A or whatever, it's just for
     *  future use, you can use 'A-Z' here.
     *
     *  'HCA', the same with 'TSA', except that 'TS'
     *   is replaced with 'HC'(HoopChina), and it's
     *   used in prod environment
     *
     * @param int Order number
     * @todo Change parameter from an integer 'orderId' to an 'order' object
     *       to completely isolate the order number generating method
     *       from other parts of the code, thus keeps a consist interface
     *       with other methods that following.
     *
     *
     *
     * @returns string Order number based on environment
     */
    protected function getOrderIdByEnv($orderId)
    {
        $debug = (sfContext::getInstance()->getConfiguration()->getEnvironment() != 'prod' && sfConfig::get('sf_debug'));

        if ($debug) {
            return 'TSA' . $orderId;
        }

        return 'HCA' . $orderId;
    }

    protected function getGameCredits($order)
    {
        $credits = $this->getAmount($order) * $order->getWpgame()->getCurrency();

        return (int)$credits;
    }

    protected function getAmount($order)
    {
        $exchangeRate = sfConfig::get('app_exchangeRate');

        if (isset($exchangeRate[$order->getWppaymentId()])) {
            if (isset($exchangeRate[$order->getWppaymentId()][$order->getWpgameId()])) {
                $percent = $exchangeRate[$order->getWppaymentId()][$order->getWpgameId()];
            } else {
                $percent = $exchangeRate[$order->getWppaymentId()]['default'];
            }

            $amount = (int)$order->getAmount() * $percent;
        } else {
            $amount = (int)$order->getAmount();
        }

        return (int)$amount;
    }

    protected function getMicroTimestamp()
    {
        list($usec, $sec) = explode(' ', microtime());

        $usec2msec = $usec * 1000;  //计算微秒部分的毫秒数(微秒部分并不是微秒,这部分的单位是秒)
        $sec2msec = $sec * 1000;    //计算秒部分的毫秒数
        $usec2msec2float = (float)$usec2msec;
        $sec2msec2float = (float)$sec2msec;
        $msec = $usec2msec2float + $sec2msec2float; //加起来就对了
        $arrMsc = explode('.', $msec);
        return $arrMsc[0];
    }

    public function transferToButterfly($order)
    {
        $configs = sfConfig::get('app_html5');
        $pubfun = new PublicFun();

        $params = array();
        $params['orderNo'] = $order->getOrderNo();
        $params['timeStamp'] = time();
        $params['sign'] = md5($params['orderNo'].$configs['secret'].$params['timeStamp']);
        $apiRet = $pubfun->curl_post($configs['notifyUrl'],http_build_query($params));

        //将接口返回值存储到数据库表中以便后期的检查
        wpTransferResultTable::generateTransferResult(array(
                'orderNo' => $order->getOrderNo(),
                'apiRet' => $apiRet
            )
        );

        $apiRet = json_decode($apiRet, true);
        if(array_key_exists('status', $apiRet) && $apiRet['status'] == 1)
        {
            if($order->getStatus() == wpHtmlOrderTable::STATUS_PAY_SUCCESS)
            {
                $order->setStatus(wpHtmlOrderTable::STATUS_TRANSFER_SUCCESS);
                $order->save();
            }
        }else{
            $order->setStatus(wpHtmlOrderTable::STATUS_TRANSFER_FAILED);
            $order->save();
            gameLog::error('transfer to Butterfly error', array(
                'order' => $order,
                'apiRet' => $apiRet,
                'configs' => $configs,
                'orderNo' => $order->getOrderNo()
            ));
        }
    }

    public function transferToLegang($order)
    {
        $parameters = sfConfig::get('app_legang');
        $pubfun = new PublicFun();
        $apiRet = $pubfun->curl_post($parameters['notifyUrl'],array('orderNo' => $order->getOrderNo()));

        //将接口返回值存储到数据库表中以便后期的检查
        wpTransferResultTable::generateTransferResult(array(
                'orderNo' => $order->getOrderNo(),
                'apiRet' => $apiRet
            )
        );

        $apiRet = json_decode($apiRet, true);
        if(array_key_exists('status', $apiRet) && $apiRet['status'] == 1)
        {
            if($order->getStatus() == wpPayOrderTable::STATUS_PAY_SUCCESS)
            {
                $order->setStatus(wpZhifuOrderTable::STATUS_TRANSFER_SUCCESS);
                $order->save();
            }
        }else{
            $order->setStatus(wpZhifuOrderTable::STATUS_TRANSFER_FAILED);
            $order->save();
            gameLog::error('transfer to legang error', array(
                'order' => $order,
                'apiRet' => $apiRet,
                'parameters' => $parameters,
                'orderNo' => $order->getOrderNo()
            ));
        }
    }
}
