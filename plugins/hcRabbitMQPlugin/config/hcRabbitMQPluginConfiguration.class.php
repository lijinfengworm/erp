<?php

//require_once(sfConfig::get('sf_root_dir') . '/plugins/hcRabbitMQPlugin/lib/phpamqp/amqp.inc');

//include(sfContext::getInstance('backend')->getConfigCache()->checkConfig(sfConfig::get('sf_app_config_dir_name') . '/custom/rabbitmq.yml'));

//exit();


class hcRabbitMQPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      require_once(sfConfig::get('sf_root_dir') . '/plugins/hcRabbitMQPlugin/lib/phpamqp/amqp.inc');
  }
}
