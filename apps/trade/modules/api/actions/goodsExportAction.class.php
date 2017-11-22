<?php
/**
 * 各种统计
 * @author: 韩晓林
 * @date: 2015/5/6  11:54
 */
Class goodsExportAction extends sfAction
{
    private $root = array(
        1=> '运动户外',
        2=>'家居用品',
        3=>'男装',
        4=>'家电',
        5=>'鞋包',
        6=>'食品',
        7=>'珠宝配饰',
        8=>'数码',
        9=>'内衣'
    );

    public function execute($request) {
        error_reporting(2047);
        //redis key
        $goods = 'trade_api_flip_goodsId';
        $goodsInfo = 'trade_api_flip_goodsInfo{0}';
        $classifiedGoods = 'trade_api_flip_classified{0}_goodsId';
        $classifiedGoodsInfo = 'trade_api_flip_classified{0}_goodsInfo{1}';
        $classification = 'trade_api_flip_classification';

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(1);
        $redis->hmset($classification,$this->root);

        $path = $_SERVER['DOCUMENT_ROOT'].'/uploads/trade/tmp/2.csv';
        $handle = fopen($path,"r");
        $n = 0;
        while ($data = fgetcsv($handle,500,",")){
            if($n == 0){
                $n ++;
                continue;
            }

            $url = "http://hws.m.taobao.com/cache/wdetail/5.0/?id=".$data[0]."&ttid=2013@taobao_h5_1.0.0&exParams=&qq-pf-to=pcqq.c2c";
            $taobaoRes = tradeCommon::getContents($url);
            $taobaoRes = json_decode($taobaoRes,true);

            //转成utf-8
            $data[6] = iconv('gb2312','utf-8',$data[6]);
            $data[1] = iconv('gb2312','utf-8',$data[1]);


            //有图片 且 一级分类存在
            if(isset($taobaoRes['data']['itemInfoModel']['picsPath'][0]) && ($root_id = array_search($data[6] ,$this->root))){
                //写入redis
                $redis->sadd($goods,$n);
                $redis->hmset(str_replace('{0}',$n,$goodsInfo),array(
                        "id"=>$data[0],
                        "price"=> $data[4],
                        "pic"=>$taobaoRes['data']['itemInfoModel']['picsPath'][0],
                        "name"=>$data[1],
                        "link"=>$data[9],
                    )
                );

                $redis->sadd(str_replace('{0}',$root_id,$classifiedGoods),$n);
                $redis->hmset(str_replace(array('{0}','{1}'),array($root_id,$n) , $classifiedGoodsInfo),array(
                        "id"=>$data[0],
                        "price"=> $data[4],
                        "pic"=>$taobaoRes['data']['itemInfoModel']['picsPath'][0],
                        'ori_price'=> $data[4],
                        'classification'=> $root_id,
                        "name"=>$data[1],
                        "link"=>$data[9],
                    )
                );
            }

            $n++;
        }


        return sfView::NONE;
    }

    private $data = array(

    );
}