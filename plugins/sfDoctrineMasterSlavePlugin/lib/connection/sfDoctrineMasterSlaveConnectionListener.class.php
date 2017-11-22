<?php

/**
 * Emulates a read-only database connection by throwing exceptions.
 * 
 * @package    sfDoctrineMasterSlavePlugin
 * @subpackage connection
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version    SVN: $Id: sfDoctrineMasterSlaveDebugListener.class.php 28144 2010-02-20 01:11:48Z Kris.Wallsmith $
 */
class sfDoctrineMasterSlaveConnectionListener extends Doctrine_EventListener
{
  public function postConnect(Doctrine_Event $event)
  {
    if(php_sapi_name() == 'cli')
    {
      //当cli环境运行时每个 数据库连接 连接上的时候 设置当前 wait_timeout 为一天
      $event->getInvoker()->execute('set wait_timeout=86400');
    }
  }
}
