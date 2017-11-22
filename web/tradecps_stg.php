<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('tradecps', 'stg', false);
sfContext::createInstance($configuration)->dispatch();
