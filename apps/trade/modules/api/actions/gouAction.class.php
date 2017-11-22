<?php

class gouAction extends sfAction{
    private $use_num_key = 'trade.yijiangou.useNum';
    private $hot_daigou_key = 'trade.yijiangou.hotDaigou';
    private $init_use_num = 27738;                                                                      //初始化次数
    public function execute($request)
    {
        sfConfig::set('sf_web_debug', false);
       // $this->setLayout(false);
        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(2);

        /*热门代购*/
        $daigou_arr = unserialize($redis->get($this->hot_daigou_key));
        if(!$daigou_arr){
            $daigou_obj = TrdProductAttrTable::getInstance()->getProductByHits(15);
            $daigou_arr = array();
            foreach($daigou_obj as $k => $v){
                $daigou_arr[$k]['id'] = $v->getId();
                $daigou_arr[$k]['img_path'] = $v->getImgPath();
                $daigou_arr[$k]['title'] = $v->getTitle();
                $daigou_arr[$k]['price'] = $v->getPrice();
                if($v->getGoodsId())
                    $daigou_arr[$k]['goods_id'] = $v->getGoodsId();
                else
                    $daigou_arr[$k]['goods_id'] = 0;
            }

            $redis->set($this->hot_daigou_key,serialize($daigou_arr),3*60);
        }


        /*使用人数*/
        $useNum = $redis->get($this->use_num_key);
        if(!$useNum){
            $useNum = $this->init_use_num;
            $redis->set($this->use_num_key,$useNum);
        }else{
            $currentNum = $this->addNum($useNum);                                                   //页面访问时开始随机增加
            $redis->set($this->use_num_key,$currentNum);
        }

        $this->daigou_arr = $daigou_arr;
        $this->useNum = number_format((int)$useNum);

        $this->getResponse()->setTitle('识货海淘 - 值得信赖的海外商口购物网站');
        $this->getResponse()->addHttpMeta('keywords', '识货海淘 - 值得信赖的海外商口购物网站');
        $this->getResponse()->addHttpMeta('description', '识货海淘 - 值得信赖的海外商口购物网站');
    }

    /*随机增加次数*/
    private function addNum($useNum){
        $minNum = 2;
        $maxNum = 8;
        $randNum = rand($minNum,$maxNum);

        return (int)$useNum+$randNum;
    }

}