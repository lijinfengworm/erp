<?php
class changeStoreAction extends sfAction
{
    public function execute($request) {
        $id = $request->getParameter('id');
        if(!$id)return false;

        $res = trdNewsTable::getMessageById($id);

        $taobao = 'taobao.com';
        $tmall = 'tmall.com';
        $amazon = 'amazon.cn';
        $amazon2 = 'z.cn';
        $yougou = 'yougou.com';
        $dangdang = 'dangdang.com';
        $yixun = 'yixun.com';
        $jd = 'jd.com';
        $jd2 = '360buy.com';
        $guomei = 'gome.com.cn';
        $suging ='suning.com';
        $yintai = 'yintai.com';

        $pattern = array($taobao=>10,$tmall=>11,$amazon=>12,$amazon2=>12,$yougou=>13,$yixun=>14,$dangdang=>15,$jd=>17,$jd2=>17,$guomei=>16,$suging=>18,$yintai=>20);
        foreach($pattern as $k=>$v){
            if(preg_match('/'.$k.'/iUs',$res['orginal_url'])){
                $res->setStoreId($v);
                $res->save();
                break;
            }
        }

        exit;

    }

}
