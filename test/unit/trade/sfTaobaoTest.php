<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');

$t = new lime_test(7, new lime_output_color());

$taobao = new TaobaoUtil();

$test_info = $taobao->getItemInfo('14141099761');
$t->is($test_info['num_iid'], '14141099761', "通过接口获取单个商品");

$test_info = $taobao->getItemInfo('100');
$t->ok(empty($test_info), "通过接口获取单个不存在的商品");

$test_info = $taobao->getItemInfo(array('14141099761','100','17954488479'));
$t->ok(( !empty($test_info['14141099761']['num_iid'])  &&  $test_info['100'] == array()  && !empty($test_info['17954488479']['num_iid'])), "通过接口获取多个商品");

$test_info = $taobao->gettaobaokeinfo('16146699252');
$t->ok( !empty($test_info['click_url']),'通过接口获取淘宝客信息');

$test_info = $taobao->gettaobaokeinfo(array('16146699252','17954488479'));
$t->ok((!empty($test_info['16146699252']['click_url']) && $test_info['17954488479'] == array() ),'通过接口获取多个淘宝客信息');

$test_info = $taobao->getShopIdByUrl('http://septwolves.tmall.com/');
$t->is($test_info,57300174,'通过header获取一个url 对应的商铺id');

$request = new TaobaoItemGetSoldCountRequest(16483156543);
$soldCount = $request->send();
$t->like($soldCount, '/\d+/','通过一个商品id获取 对应销售数量');


?>