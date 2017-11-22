<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpUpdateShopTask extends sfBaseTask
{
    private $interface_ip = 'http://121.43.167.40:3000';
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->addArgument('act', NULL, '请输入操作', 'update_shop_info');  //操作

        $this->namespace        = 'trade';
        $this->name             = 'updateShop';
        $this->briefDescription = '识货推荐店铺数据更新';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:updateShop|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit','128M');

        if(sfConfig::get('sf_environment') == 'dev') {
             $this->interface_ip = 'http://192.168.8.29:3000';
        }

        $act     = $arguments['act'];
        if('update_shop_info' == $act){
            $this->update_shop_info();
        }elseif('update_shop_userid' == $act){
            $this->update_shop_userid();
        }else{
            exit('没有此操作');
        }
    }

    //更新店铺信息
    private function update_shop_info(){
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(5);
        $redis_key = 'trade:shop:task:updateshopinfo:flag:key';
        $id = (int)$redis->get($redis_key);

        //max id
        $shopInfoMax = trdShopInfoTable::getInstance()
            ->createQuery()
            ->select('id')
            ->where('shop_user_id is not NULL')
            ->orderby('id desc')
            ->fetchOne();
        $shopInfoMaxId = $shopInfoMax['id'];

        //while($id <  $shopInfoMaxId){
        if($id >= $shopInfoMaxId) $id = 1;

        $shopInfoTable = trdShopInfoTable::getInstance()
            ->createQuery()
            ->select('id,shop_info')
            ->where('shop_user_id is not NULL')
            ->where('id > ?', $id)
            ->limit(10)
            ->execute();

        foreach($shopInfoTable as $shopInfoVal){
            $shopInfo = $shopInfoVal->getShopInfos();

            //请求接口 1
            $res = tradeCommon::requestUrl($this->interface_ip.'?userid='.$shopInfoVal->getShopUserId(), 'GET', NULL, NULL ,3);
            $res = json_decode($res, true);

            $shopInfoData = array();
            $itemId =  $i = 0;

            if(isset($res['status']) && isset($res['data'])){
                foreach($res['data'] as $resDataKey => $resDataVal){
                    if(isset($resDataVal['firstItem'])){
                        $shopInfoData[$i]['id']    = $resDataVal['firstItem']['itemId'];
                        $shopInfoData[$i]['title'] = $resDataVal['firstItem']['title'];
                        $shopInfoData[$i]['pic']   = $resDataVal['firstItem']['itemPic'];
                        $shopInfoData[$i]['price'] = $resDataVal['firstItem']['price'];
                        $shopInfoData[$i]['time']  = date('Y-m-d H:i', ($resDataVal['firstItem']['time'] / 100));

                        $i++;
                        $itemId = $resDataVal['firstItem']['itemId'];
                    }
                    if(isset($resDataVal['secondItem'])){
                        $shopInfoData[$i]['id']    = $resDataVal['secondItem']['itemId'];
                        $shopInfoData[$i]['title'] = $resDataVal['secondItem']['title'];
                        $shopInfoData[$i]['pic']   = $resDataVal['secondItem']['itemPic'];
                        $shopInfoData[$i]['price'] = $resDataVal['secondItem']['price'];
                        $shopInfoData[$i]['time']  = date('Y-m-d H:i', ($resDataVal['secondItem']['time'] / 100));

                        $i++;
                    }
                }
            }

            if($itemId && $shopInfoData){
                //请求接口 2
                $itemInfo = tradeCommon::requestUrl('http://hws.m.taobao.com/cache/wdetail/5.0/?id='.$itemId.'&qq-pf-to=pcqq.c2c', 'GET', NULL, NULL ,3);
                $itemInfo = json_decode($itemInfo, true);

                $shopInfo['data'] = $shopInfoData;
                if(isset($itemInfo['data']['seller']['actionUnits'][0]['value'])){
                    $shopInfo['goods_num']     =  $itemInfo['data']['seller']['actionUnits'][0]['value'];
                    $shopInfo['update_goods']  =  $itemInfo['data']['seller']['actionUnits'][1]['value'];
                    $shopInfo['goodrate']      =  $itemInfo['data']['seller']['goodRatePercentage'];
                    $shopInfo['evaluateInfo']  =  $itemInfo['data']['seller']['evaluateInfo'];

                }

                $shopInfoVal->setShopInfos($shopInfo);
                $shopInfoVal->save();
            }

            $id = $shopInfoVal->getId();
            $redis->set($redis_key, $id, 3600*24*7);

            sleep(1);
            echo $id.PHP_EOL;
        }

       /*unset($shopInfoTable);
       }*/
    }

    //更新店铺店主淘宝uid
    private function update_shop_userid(){
        $shopInfoTable = trdShopInfoTable::getInstance()->createQuery()->where('shop_user_id is NULL')->limit(100)->execute();
        foreach($shopInfoTable as $shopInfoVal){
            if($shopInfoVal->getLink()){
                $html = tradeCommon::requestUrl($shopInfoVal->getLink(), 'GET', NULL, NULL ,3);
                if(trim($html)){
                    $pattern = '/<meta[^>]*content="[^>]*userId=(\d*)"/iUs';
                    preg_match($pattern, $html, $match);

                    if(isset($match[1])){
                        $shopInfoVal->setShopUserId($match[1]);
                        $shopInfoVal->save();
                    }

                    ECHO $shopInfoVal->getLink().PHP_EOL;
                    usleep(1000);
                }
            }
        }
    }
}
