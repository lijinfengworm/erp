<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('tradeadmin', 'stg', false);
sfContext::createInstance($configuration)->dispatch();
