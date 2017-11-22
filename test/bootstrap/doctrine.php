<?php
require_once dirname(__FILE__).'/unit.php';
require_once dirname(__FILE__).'/../../config/ProjectConfiguration.class.php';
 
$configuration = ProjectConfiguration::getApplicationConfiguration( 'star', 'test', true);
 
new sfDatabaseManager($configuration);
sfContext::createInstance($configuration);
