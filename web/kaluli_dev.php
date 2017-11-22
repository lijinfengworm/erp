<?php
/**
$flag = false;
if(isset($_GET['type']) && $_GET['type'] == 'beta'){
    $flag = true;
    setcookie('expert', 1, time()+86400*7, '/');
}
if(isset($_GET['type']) && $_GET['type'] == 'stable'){
    setcookie('expert', '', time()-3600, '/');
}
$expert = $flag || isset($_COOKIE['expert']) ? 1 : '';
$path = $_SERVER['REQUEST_URI'];
//系统维护
if(!preg_match("/api/",$path) && !preg_match("/order/",$path) && empty($expert)){
    require 'kaluli_hot.html';
    exit;
}
**/

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
require_once(dirname(__FILE__).'/../vendor/autoload.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('tradeadmin', 'dev', true);
sfContext::createInstance($configuration)->dispatch();

