<?php

require_once dirname(__FILE__).'/../lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins(array(
        'sfDoctrineMasterSlavePlugin',
        'sfDoctrinePlugin',
        'sfJqueryReloadedPlugin',
        'hcRabbitMQPlugin',
        'hcProtobufPlugin',
        'sfFormExtraPlugin',
        'sfTaskExtraPlugin',
        'sfCookieSessionStoragePlugin',
        'sfDoctrinePlugin',
        'csDoctrineActAsSortablePlugin'));

    /*
    if($this instanceof sfApplicationConfiguration && !$this->isDebug()){
        require_once sfConfig::get('sf_symfony_lib_dir').'/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine.compiled.php';
    }
    */

//    $this->enablePlugins('sfLightboxPlugin');
//    $this->enablePlugins('sfJqueryTreeDoctrineManagerPlugin');
    $this->enablePlugins('sfCaptchaGDPlugin');
  }
}
