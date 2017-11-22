<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');
//$b = new sfTestBrowser();
$browser = new sfTestFunctional(new sfBrowser());
$browser->test()->is($browser->get('/u?url=http%3A%2F%2Fwww.baidu.com%2F')->getResponse()->getHttpHeader('location'), 'http://www.baidu.com/', "通过识货跳转中心处理一个普通地址");

$taobaoConfig = sfConfig::get('app_taobao');
$browser->test()->like($browser->get('/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fid%3D18169496914%26spm%3D2014.12651499.0.0')->getResponse()->getHttpHeader('location'),'/spm=2014\.'.$taobaoConfig['taobaoke']['key'].'/','通过识货跳转中心处理一个淘宝商品的链接');

$browser->test()->like($browser->get('/u?url=http%3a%2f%2fitem.taobao.com%2fitem.htm%3fspm%3da1z10.1.w28.9.mxFvC3%26id%3d20448100012')->getResponse()->getHttpHeader('location'),'/spm=2014\.'.$taobaoConfig['taobaoke']['key'].'/','通过识货跳转中心处理一个淘宝客商品的链接');

$browser->test()->like($browser->get('/u?url=http%3a%2f%2fshop57299736.taobao.com%2f')->getResponse()->getHttpHeader('location'),'/s\.click\.taobao\.com/','通过识货跳转中心处理一个淘宝店铺链接');
//http://shihuo.hupu.com/u?url=http%3a%2f%2flining.tmall.com%2f%3fspm%3da1z10.4.w3.1.iNjKIV
$browser->test()->like($browser->get('/u?url=http%3a%2f%2fswsport.taobao.com%2f%3fspm%3da1z10.1.0.26')->getResponse()->getHttpHeader('location'),'/swsport\.taobao\.com/','通过识货跳转中心处理一个淘宝店铺链接（自定义二级域名非淘宝客）');

$browser->test()->like($browser->get('/u?url=http%3a%2f%2fwisefin.tmall.com/')->getResponse()->getHttpHeader('location'),'/s\.click\.taobao\.com/','通过识货跳转中心处理一个淘宝店铺链接（自定义二级域名淘宝客）');

$browser->test()->like($browser->get('/u?url=http%3a%2f%2fdetail.tmall.com%2fitem.htm%3fspm%3da220m.1000858.1000725.1%26id%3d9144982353%26is_b%3d1%26cat_id%3d2%26q%3d%25D6%25D0%25C1%25B8')->getResponse()->getHttpHeader('location'),'/spm=2014\.'.$taobaoConfig['taobaoke']['key'].'/','通过识货跳转中心处理一个天猫商品的链接');

$browser->test()->like($browser->get('/u?url=http%3a%2f%2fcofco.tmall.com/')->getResponse()->getHttpHeader('location'),'/s\.click\.taobao\.com/','通过识货跳转中心处理一个天猫商铺链接');

$browser->test()->unlike($browser->get('/u?url=http%3a%2f%2fcofco.tmall.com%2fsearch.htm%3fscid%3d515753961%26scname%3dvsbLrtL7wc8%253D%26checkedRange%3dtrue%26queryType%3dcat')->getResponse()->getHttpHeader('location'),'/s\.click\.taobao\.com/','通过识货跳转中心处理一个天猫商铺链接(非店铺首页)');

$browser->test()->unlike($browser->get('/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fspm%3Da1z10.3.17.15.6a5b79%26amp%3Bid%3D15930957992%26amp%3B')->getResponse()->getHttpHeader('location'),'/s\.click\.taobao\.com/','通过识货跳转中心处理一个天猫商铺链接(非店铺首页)');




//http://shihuo.hupu.com/u?url=http%3a%2f%2fkids.banggo.com%2fGoods%2f590822.shtml
//http://shihuo.hupu.com/u?url=http%3a%2f%2fwww.letao.com%2fchancechance%2fshoe-497727524.html
//http://shihuo.hupu.com/u?url=http%3a%2f%2fwww.ihush.com%2fproduct_575530_f1_gL3BzcF81MTA1Lmh0bWw.html
//有返利
//http://shihuo.hupu.com/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fid%3D16984719336%26spm%3D2014.12651499.0.0&item_id=4286
//趣玩
//http://shihuo.hupu.com/u?url=http%3A%2F%2Fwww.quwan.com%2Fgoods-32568.html%3Ffm%3Dnewarriv&item_id=4257
//有返利
//http://shihuo.hupu.com/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fspm%3Da1z10.3.0.79.hnejia%26amp%3Bid%3D20136256191
//有返利
//http://go.hupu.com/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fspm%3Da1z10.3.17.12.34e7e3%26amp%3Bid%3D17014231456
//无返利
//http://go.hupu.com/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fspm%3Da1z10.3.0.72.trnrlv%26id%3D17496315795%26
//个性化域名 的淘宝店铺无返利
//http://go.hupu.com/u?url=http%3A%2F%2Faj-jeans.taobao.com%2F
//个性化域名 的淘宝店铺有返利
//http://go.hupu.com/u?url=http%3a%2f%2ftj-xm.taobao.com%2f
//搜索页  应该正常跳转 没返利
//http://shihuo.hupu.com/u?url=http%3a%2f%2ftj-xm.taobao.com%2f%3fspm%3da1z10.1.0.569.ONT2Xq%26search%3dy%26orderType%3d_hotsell
//http://shihuo.hupu.com/u?url=http%3A%2F%2Fwww.quwan.com%2Fgoods-32568.html%3Ffm%3Dnewarriv&item_id=4257

$urls = array(
    array('url'=>'/u?url=http%3A%2F%2Fwww.baidu.com%2F','title'=>'百度'),
    array('url'=>'/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fid%3D18169496914%26spm%3D2014.12651499.0.0','title'=>'通过识货跳转中心处理一个淘宝商品的链接'),
    array('url'=>'/u?url=http%3a%2f%2fitem.taobao.com%2fitem.htm%3fspm%3da1z10.1.w28.9.mxFvC3%26id%3d20448100012','title'=>'通过识货跳转中心处理一个淘宝客商品的链接'),
    array('url'=>'/u?url=http%3a%2f%2fshop57299736.taobao.com%2f','title'=>'通过识货跳转中心处理一个淘宝店铺链接'),
    array('url'=>'/u?url=http%3a%2f%2fswsport.taobao.com%2f%3fspm%3da1z10.1.0.26','title'=>'通过识货跳转中心处理一个淘宝店铺链接（自定义二级域名非淘宝客）'),
    array('url'=>'/u?url=http%3a%2f%2flining.tmall.com%2f%3fspm%3da1z10.4.w3.1.iNjKIV','title'=>'通过识货跳转中心处理一个天猫店铺链接'),
    array('url'=>'/u?url=http%3a%2f%2fswsport.taobao.com%2f%3fspm%3da1z10.1.0.26','title'=>'通过识货跳转中心处理一个淘宝店铺链接（自定义二级域名非淘宝客）'),
    array('url'=>'/u?url=http%3a%2f%2fdetail.tmall.com%2fitem.htm%3fspm%3da220m.1000858.1000725.1%26id%3d9144982353%26is_b%3d1%26cat_id%3d2%26q%3d%25D6%25D0%25C1%25B8','title'=>'通过识货跳转中心处理一个天猫商品的链接'),
    array('url'=>'/u?url=http%3a%2f%2fcofco.tmall.com/','title'=>'通过识货跳转中心处理一个天猫商铺链接'),
    array('url'=>'/u?url=http%3a%2f%2fcofco.tmall.com%2fsearch.htm%3fscid%3d515753961%26scname%3dvsbLrtL7wc8%253D%26checkedRange%3dtrue%26queryType%3dcat','title'=>'通过识货跳转中心处理一个天猫商铺链接(非店铺首页)'),
    array('url'=>'/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fspm%3Da1z10.3.17.15.6a5b79%26amp%3Bid%3D15930957992%26amp%3B','title'=>'通过识货跳转中心处理一个天猫商铺链接(非店铺首页)'),
    array('url'=>'/u?url=http%3a%2f%2fkids.banggo.com%2fGoods%2f590822.shtml','title'=>'邦购'),
    array('url'=>'/u?url=http%3a%2f%2fwww.letao.com%2fchancechance%2fshoe-497727524.html','title'=>'乐淘'),
    array('url'=>'/u?url=http%3a%2f%2fwww.ihush.com%2fproduct_575530_f1_gL3BzcF81MTA1Lmh0bWw.html','title'=>'飘飘无语'),
    array('url'=>'/u?url=http%3A%2F%2Fwww.quwan.com%2Fgoods-32568.html%3Ffm%3Dnewarriv&item_id=4257','title'=>'趣玩'),
    array('url'=>'/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fspm%3Da1z10.3.0.79.hnejia%26amp%3Bid%3D20136256191','title'=>'有返利'),
    array('url'=>'/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fspm%3Da1z10.3.17.12.34e7e3%26amp%3Bid%3D17014231456','title'=>'有返利'),
    array('url'=>'/u?url=http%3A%2F%2Fitem.taobao.com%2Fitem.htm%3Fspm%3Da1z10.3.0.72.trnrlv%26id%3D17496315795%26','title'=>'无返利'),
    array('url'=>'/u?url=http%3A%2F%2Faj-jeans.taobao.com%2F','title'=>'个性化域名 的淘宝店铺无返利'),
    array('url'=>'/u?url=http%3a%2f%2ftj-xm.taobao.com%2f','title'=>'个性化域名 的淘宝店铺有返利'),
    array('url'=>'/u?url=http%3a%2f%2ftj-xm.taobao.com%2f%3fspm%3da1z10.1.0.569.ONT2Xq%26search%3dy%26orderType%3d_hotsell','title'=>'搜索页  应该正常跳转 没返利'),
    array('url'=>'/u?url=http%3A%2F%2Fwww.quwan.com%2Fgoods-32568.html%3Ffm%3Dnewarriv&item_id=4257','title'=>'趣玩'),
);
foreach ($urls as $url){
    echo "<a href=".$url['url']." target=_blank>".$url['title']."</a><br />";
}