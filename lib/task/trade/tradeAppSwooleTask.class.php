<?php
class tradeAppSwooleTask extends sfBaseTask
{
    private $_routeMapping = array(
        '/app/haitaoIndex' => '/app_product/getHaitaoIndex',
        '/app/countCart'    => '/app_order/countCart',
        '/app/getCartList'  => '/app_order/getCartList',
        '/app/getMostPurchase' => '/app_product/getMostPurchase',
        '/app/getAllMenu' => '/app_product/getAllMenu',
        '/app/orderInfo' => '/app_order/orderDetailInfo',
        '/app/getLipinkaList' => '/app_order/lipinkaList',
        '/app/deleteCart'   => '/app_order/deleteCart'
    );

    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
            new sfCommandOption('port', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', ''),
        ));

        $this->namespace            = 'trade';
        $this->name                 = 'AppSwoole';
        $this->briefDescription     = '';
        $this->detailedDescription  = <<<EOF
The [trade:AppSwoole|INFO] task does things.
Call it with:

  [php symfony trade:AppSwoole --port=8991|INFO]
EOF;
    }

    private $_publicData = array();

    protected function execute($arguments = array(), $options = array())
    {
        ini_set('memory_limit', '80M');
        sfContext::createInstance($this->configuration);

        $port = $options['port'];
        if (empty($port)) {
            $this->log("缺少端口号");
            exit;
        }
        $http = new swoole_http_server("0.0.0.0", $port);
        $http->set(
            array(
                'worker_num' => 30,
                'max_request' => 10000,
                'dispatch_mode' => 3,
                'daemonize' => true,
                'log_file' => '/data0/log-data/swoole_error.log'
            )
        );
        $http->on('request', function ($request, $response) {

            if ('/favicon.ico' == $request->server['request_uri']) {
                $response->end('');
                return;
            }

            if ('/swoole_stop' == $request->server['request_uri']) {
                $this->_publicData['serv']->shutdown();
                $response->end('close');
                return;
            }

            $uriInfo = $request->server['request_uri'];
            if (isset($this->_routeMapping[$uriInfo])) {
                $uriInfo = $this->_routeMapping[$uriInfo];
            }
            $uris = explode('/', $uriInfo);
            $moduleName = isset($uris[1]) ? $uris[1] : '';
            $actionName = isset($uris[2]) ? $uris[2] : '';

            $sfresponse = clone sfContext::getInstance()->getResponse();

            if (!empty($request->cookie) && !empty($request->cookie['u'])) {
                $passport = new PassportClientForSwoole('shihuo.cn', $request->cookie);
                if ($passport->iflogin()) {
                    $userInfo = $passport->userinfo();
                    sfContext::getInstance()->getUser()->setAttribute('uid', $userInfo['uid']);
                    sfContext::getInstance()->getUser()->setAttribute('username', $userInfo['username']);
                    sfContext::getInstance()->getUser()->setAuthenticated(true);
                }
            }
            $swooleRequest = new sfSwooleRequest(sfContext::getInstance()->getEventDispatcher(), array(), array(), sfConfig::get('sf_factory_request_parameters', array(
                'logging' => '1',
                'path_info_array' => 'SERVER',
                'path_info_key' => 'PATH_INFO',
                'relative_url_root' => NULL,
                'formats' =>
                    array(
                        'txt' => 'text/plain',
                        'js' =>
                            array(
                                0 => 'application/javascript',
                                1 => 'application/x-javascript',
                                2 => 'text/javascript',
                            ),
                        'css' => 'text/css',
                        'json' =>
                            array(
                                0 => 'application/json',
                                1 => 'application/x-json',
                            ),
                        'xml' =>
                            array(
                                0 => 'text/xml',
                                1 => 'application/xml',
                                2 => 'application/x-xml',
                            ),
                        'rdf' => 'application/rdf+xml',
                        'atom' => 'application/atom+xml',
                    ),
                'no_script_name' => true,
            )), $request);
            sfContext::getInstance()->set('request', $swooleRequest);
            try {
                $actionInstance = sfContext::getInstance()->getController()->getAction($moduleName, $actionName);
            } catch (Exception $e) {
                $this->log($e->getMessage());
                $response->status(404);
                $response->end('Not found module or action (swoole/' . php_uname('n') . ')');
            }
            if (isset($actionInstance)) {
                $actionToRun = 'execute' . ucfirst($actionName);
                try {
                    set_time_limit(10);
                    $actionInstance->preExecute();
                    $actionInstance->$actionToRun($swooleRequest);
                    $sfWebResponse = sfContext::getInstance()->getResponse();
                    $response->status($sfWebResponse->getStatusCode());
                    $headers = $sfWebResponse->getHttpHeaders();
                    foreach ($headers as $key => $value) {
                        $response->header($key, $value);
                    }
                    $response->header('Source', 'swoole/' . php_uname('n'));
                    $cookies = $sfWebResponse->getCookies();
                    foreach ($cookies as $cookie) {
                        $response->cookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httpOnly']);
                    }
                    $response->end(sfContext::getInstance()->getResponse()->getContent());
                    set_time_limit(1800);
                } catch (Exception $e) {
                    set_time_limit(1800);
                    $this->log($e->getMessage());
                    $response->status(500);
                    $response->end('Internal Server Error (swoole/' . php_uname('n') . ')');
                }
            }
            sfContext::getInstance()->setResponse($sfresponse);
            sfContext::getInstance()->getUser()->getAttributeHolder()->clear();
            sfContext::getInstance()->getUser()->setAuthenticated(false);

            //防止mysql has go away
//            foreach (Doctrine_Manager::getInstance()->getConnections() as $connection) {
//                if ($connection->isConnected() && empty($this->_publicData['connect_wait_timeout_' . $connection->getName()]) ) {
//                    $connection->execute('set wait_timeout=1000');
//                    $this->_publicData['connect_wait_timeout_'.$connection->getName()] = 1;
//                }
//            }
            //内存大于60mb的时候退出
            if (memory_get_usage()/1024/1024 > 70) {
                $this->log(date('Ymd H:i:s')." 内存 超过60mb 退出");
                exit;
            }
//            $this->log('id--'.$this->_publicData['serv']->worker_id.'---time---'.time().'---start--'.$this->_publicData['startTime']);
            if(time() - $this->_publicData['startTime'] > (60*30))
            {
                $this->log(date('Ymd H:i:s')." 超时退出");
                exit;
            }
        });
        /*        $http->on('start', function($serv) {
                    print_r($serv);
                });*/
        $http->on('WorkerStart', function ($serv, $work_id) {
            //每个进程运行15分钟退出
//            $serv->after(900*1000, function () {
//                $this->log(date('Ymd H:i:s').' stop');
//                exit;
//            });
            $this->_publicData['serv'] = $serv;
            $this->_publicData['startTime'] = time();
        });
        $http->start();
    }
}