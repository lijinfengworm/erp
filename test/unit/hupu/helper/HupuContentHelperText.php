<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/../../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../../apps/hupu/lib/helper/HupuStringHelper.php');
require_once(dirname(__FILE__).'/../../../../apps/hupu/lib/helper/HupuContentHelper.php');

$t = new lime_test(9);

$items = array ( 1 => array ( 'id' => 'knicks_1', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3715857.html', 
                              'title' => '<img src="http://w3.hoopchina.com.cn/index/images/play.gif">JR史密斯本季五佳球', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 2 => array ( 'id' => 'knicks_2', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711877.html', 
                              'title' => '林书豪和斯派克李出席活动', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 3 => array ( 'id' => 'knicks_3', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3716626.html', 
                              'title' => '香珀特入选新秀第一阵容', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 4 => array ( 'id' => 'knicks_4', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711480.html', 
                              'title' => '经纪人不保证林会留在纽约', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), );

$expectedOutput = '<li><a target="_blank" href="http://bbs.hupu.com/3715857.html">JR史密斯本季五佳球</a></li><li><a target="_blank" href="http://bbs.hupu.com/3711877.html">林书豪和斯派克李出席活动</a></li><li><a target="_blank" href="http://bbs.hupu.com/3716626.html">香珀特入选新秀第一阵容</a></li><li><a target="_blank" href="http://bbs.hupu.com/3711480.html">经纪人不保证林会留在纽约</a></li>';                         
$t->diag('news()');
$output = news($items);
$t->is($output, $expectedOutput, "news() takes an array and output a proper HTML snipet(0, 0, 0, 0): \n".$output);

$items = array ( 1 => array ( 'id' => 'knicks_1', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3715857.html', 
                              'title' => '<img src="http://w3.hoopchina.com.cn/index/images/play.gif">JR史密斯本季五佳球', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 2 => array ( 'id' => 'knicks_2', 
                              'name' => '', 
                              'href' => '', 
                              'title' => 'null', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => '', ), 
                 3 => array ( 'id' => 'knicks_3', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3716626.html', 
                              'title' => '香珀特入选新秀第一阵容', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 4 => array ( 'id' => 'knicks_4', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711480.html', 
                              'title' => '经纪人不保证林会留在纽约', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), );

$expectedOutput = '<li class="oneLine"><a target="_blank" href="http://bbs.hupu.com/3715857.html">JR史密斯本季五佳球</a></li><li><a target="_blank" href="http://bbs.hupu.com/3716626.html">香珀特入选新秀第一阵容</a></li><li><a target="_blank" href="http://bbs.hupu.com/3711480.html">经纪人不保证林会留在纽约</a></li>';
$output = news($items);
$t->is($output, $expectedOutput, "news() takes an array and output 3 news(1, -, 0, 0): \n".$output);

$items = array ( 1 => array ( 'id' => 'knicks_1', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3715857.html', 
                              'title' => '<img src="http://w3.hoopchina.com.cn/index/images/play.gif">JR史密斯本季五佳球', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 2 => array ( 'id' => 'knicks_2', 
                              'name' => '', 
                              'href' => '', 
                              'title' => 'null', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => '', ), 
                 3 => array ( 'id' => 'knicks_3', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3716626.html', 
                              'title' => '香珀特入选新秀第一阵容', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 4 => array ( 'id' => 'knicks_4', 
                              'name' => '', 
                              'href' => '', 
                              'title' => 'null', 
                              'time' => '', 
                              'author' => '', 
                              'photo' => '', ), );

$expectedOutput = '<li class="oneLine"><a target="_blank" href="http://bbs.hupu.com/3715857.html">JR史密斯本季五佳球</a></li><li class="oneLine"><a target="_blank" href="http://bbs.hupu.com/3716626.html">香珀特入选新秀第一阵容</a></li>';
$output = news($items);
$t->is($output, $expectedOutput, "news() takes an array and output 2 news, one per line(1, -, 1, -): \n".$output);

$items = array ( 1 => array ( 'id' => 'knicks_1', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3715857.html', 
                              'title' => '<img src="http://w3.hoopchina.com.cn/index/images/play.gif">JR史密斯本季五佳球', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 2 => array ( 'id' => 'knicks_2', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711877.html', 
                              'title' => '林书豪和斯派克李出席活动', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 3 => array ( 'id' => 'knicks_3', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3716626.html', 
                              'title' => '香珀特入选新秀第一阵容', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 4 => array ( 'id' => 'knicks_4', 
                              'name' => '', 
                              'href' => '', 
                              'title' => '', 
                              'time' => '', 
                              'author' => '', 
                              'photo' => '', ), );
$expectedOutput = '<li><a target="_blank" href="http://bbs.hupu.com/3715857.html">JR史密斯本季五佳球</a></li><li><a target="_blank" href="http://bbs.hupu.com/3711877.html">林书豪和斯派克李出席活动</a></li><li class="oneLine"><a target="_blank" href="http://bbs.hupu.com/3716626.html">香珀特入选新秀第一阵容</a></li>';                         
$output = news($items);
$t->is($output, $expectedOutput, "news() takes an array and output 3 news, the third one with a oneLine class(0, 0, 1, -): \n".$output);

$items = array ( 1 => array ( 'id' => 'knicks_1', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3715857.html', 
                              'title' => '<img src="http://w3.hoopchina.com.cn/index/images/play.gif">JR史密斯本季五佳球', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 2 => array ( 'id' => 'knicks_2', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711877.html', 
                              'title' => '林书豪和斯派克李出席活动', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 3 => array ( 'id' => 'knicks_3', 
                              'name' => '', 
                              'href' => '', 
                              'title' => '', 
                              'time' => '', 
                              'author' => '', 
                              'photo' => '', ), 
                 4 => array ( 'id' => 'knicks_4', 
                              'name' => '', 
                              'href' => '', 
                              'title' => '', 
                              'time' => '', 
                              'author' => '', 
                              'photo' => '', ), );
$expectedOutput = '<li><a target="_blank" href="http://bbs.hupu.com/3715857.html">JR史密斯本季五佳球</a></li><li><a target="_blank" href="http://bbs.hupu.com/3711877.html">林书豪和斯派克李出席活动</a></li>';                         
$output = news($items);
$t->is($output, $expectedOutput, "Only two news, no oneLine class presented(0, 0, -, -): \n".$output);

$items = array ( 1 => array ( 'id' => 'knicks_1', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3715857.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 2 => array ( 'id' => 'knicks_2', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711877.html', 
                              'title' => '林书豪和斯派克李出席活动', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 3 => array ( 'id' => 'knicks_3', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3716626.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 4 => array ( 'id' => 'knicks_4', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711480.html', 
                              'title' => '经纪人不保证林会留在纽约', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), );

$expectedOutput = '<li><a target="_blank" href="http://bbs.hupu.com/3711877.html">林书豪和斯派克李出席活动</a></li><li><a target="_blank" href="http://bbs.hupu.com/3711480.html">经纪人不保证林会留在纽约</a></li>';                         
$t->diag('news()');
$output = news($items);
$t->is($output, $expectedOutput, "news() takes an array and output a proper HTML snipet(-, 0, -, 0): \n".$output);

$items = array ( 1 => array ( 'id' => 'knicks_1', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3715857.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 2 => array ( 'id' => 'knicks_2', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711877.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 3 => array ( 'id' => 'knicks_3', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3716626.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 4 => array ( 'id' => 'knicks_4', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711480.html', 
                              'title' => '经纪人不保证林会留在纽约', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), );

$expectedOutput = '<li><a target="_blank" href="http://bbs.hupu.com/3711480.html">经纪人不保证林会留在纽约</a></li>';                         
$t->diag('news()');
$output = news($items);
$t->is($output, $expectedOutput, "news() takes an array and output a proper HTML snipet(-, -, -, 0): \n".$output);

$items = array ( 1 => array ( 'id' => 'knicks_1', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3715857.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 2 => array ( 'id' => 'knicks_2', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711877.html', 
                              'title' => '林书豪和斯派克李出席活动', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 3 => array ( 'id' => 'knicks_3', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3716626.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 4 => array ( 'id' => 'knicks_4', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711480.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), );

$expectedOutput = '<li><a target="_blank" href="http://bbs.hupu.com/3711877.html">林书豪和斯派克李出席活动</a></li>';                         
$t->diag('news()');
$output = news($items);
$t->is($output, $expectedOutput, "news() takes an array and output a proper HTML snipet(-, 0, -, -): \n".$output);

$items = array ( 1 => array ( 'id' => 'knicks_1', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3715857.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 2 => array ( 'id' => 'knicks_2', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711877.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 3 => array ( 'id' => 'knicks_3', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3716626.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), 
                 4 => array ( 'id' => 'knicks_4', 
                              'name' => '', 
                              'href' => 'http://bbs.hupu.com/3711480.html', 
                              'title' => '', 
                              'time' => '2012-05-23 09:29:57', 
                              'author' => '申少鹏', 
                              'photo' => 'http://w2.hoopchina.com.cn/static/www/2012-05-23/1337736595133768872091561.jpg', ), );

$expectedOutput = '';                         
$t->diag('news()');
$output = news($items);
$t->is($output, $expectedOutput, "news() takes an array and output a proper HTML snipet(-, -, -, -): \n".$output);