<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
require_once(dirname(__FILE__).'/../vendor/autoload.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('trademobile', 'prod', false);
sfContext::createInstance($configuration)->dispatch();