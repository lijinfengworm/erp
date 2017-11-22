<?php

/**
 * 后台用户组 form
 * About 梁天
 */
class TrdAdminRoleForm extends BaseTrdAdminRoleForm
{
  public function configure() {
    unset($this['updated_at']);
    unset($this['created_at']);


      //FunBase::myDebug($this->getObject()->getRoleStatus());

    $this->setWidget('is_super', new sfWidgetFormChoice(array("choices" =>array(0=>'否',1=>'是'))));
    $this->widgetSchema->setHelp('is_super',
        '<span class="c-green">超级组无视任何权限！</span>');


    $this->setWidget('role_status', new sfWidgetFormChoice(
        array(
            'expanded' => true, 'default'   => '1',
            'choices'  => array(1=>'可用',2=> '禁用'
            ))));

    $this->setValidator('name',
        new sfValidatorString(
            array('required' => true, 'trim' => true),
            array('required' => '组名称必填！')));
  }




}
