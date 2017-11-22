<?php
use PhpAmqpLib\Connection\AMQPConnection;
class tradeAmqpCommodityGrouponTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
        ));

        $this->addArgument('act', NULL, '请输入操作', 'inStock');  //操作

        $this->namespace = 'trade';
        $this->name = 'AmqpCommodityGroupon';
        $this->briefDescription = '识货商品库团购特殊处理';
        $this->detailedDescription = <<<EOF
The [trade:CreateCiTieForWP|INFO] task does things.
Call it with:

  [php symfony trade:AmqpCommodityGroupon]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        ini_set('memory_limit', '128M');

        $act     = $arguments['act'];
        if('inStock' == $act){
            $this->inStock();
        }elseif('outStock' == $act){
            $this->outStock();
        }else{
            exit('没有此操作');
        }

    }

    //改为有库存 上架
    private function inStock(){
        $grouponArr = trdGoodsSupplierTable::getInstance()
            ->createQuery()
            ->where('store = ?', '识货团购')
            ->andWhere('status = ?', 1)
            ->andWhere('(update_time < "'.date('Y-m-d H:i:s',strtotime(date('Y-m-d'))) .'" or update_time is null)')
            ->limit(50)
            ->execute();

        foreach($grouponArr as $groupon){
            if($groupon->getFromId()){
                $t = trdGrouponTable::getInstance()->find($groupon->getFromId());
                if($t
                    && $t->getStartTime() <=  date('Y-m-d H:i:s')
                    && $t->getEndTime() >  date('Y-m-d H:i:s')
                ){
                    if($t->getStatus() == 6){
                        echo 'inStock: '.$groupon->getFromId().PHP_EOL;
                        $groupon->setStatus(0);
                    }else{
                        $groupon->delete();
                    }
                }else{
                    echo 'wait'.PHP_EOL;
                }

                $groupon->setUpdateTime(date('Y-m-d H:i:s'));
                $groupon->save();
            }else{
                $groupon->delete();
            }
        }

    }

    //改为普通商品
    private function outStock(){
        $grouponArr = trdGoodsSupplierTable::getInstance()
            ->createQuery()
            ->where('store = ?', '识货团购')
            ->andWhere('status = ?', 0)
            ->andWhere('(update_time < "'.date('Y-m-d H:i:s',strtotime(date('Y-m-d'))) .'" or update_time is null)')
            ->limit(50)
            ->execute();

        foreach($grouponArr as $groupon){
            if($groupon->getFromId()){
                $t = trdGrouponTable::getInstance()->find($groupon->getFromId());
                if($t
                    && $t->getEndTime() < date('Y-m-d H:i:s')
                ){
                    $groupon->setName(TrdGoodsSupplierForm::getShopName($t->getUrl()));
                    $groupon->setUrl($t->getUrl());
                    $groupon->setStore(TrdGoodsSupplierForm::getStoreName($t->getUrl()));
                    echo 'outStock: '.$groupon->getFromId().PHP_EOL;
                }else{
                    echo 'wait'.PHP_EOL;
                }

                $groupon->setUpdateTime(date('Y-m-d H:i:s'));
                $groupon->save();
            }else{
                $groupon->delete();
            }
        }
    }
}
