<?php
if ($_SERVER['HTTP_HOST'] == 'shihuo.hupu.com'){
    header('HTTP/1.1 301 Moved Permanently');
    header("Location:http://www.shihuo.cn".$_SERVER['REQUEST_URI']);// 301 跳转到设置的 url
    exit();
}
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('tradeadmin', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
