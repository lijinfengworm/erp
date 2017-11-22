<?php

/**
 * 后台菜单form
 * About 梁天
 */
class TrdAdminMenuForm extends BaseTrdAdminMenuForm
{

  private $_status = array(
      '1'=>'正常',
      '2'=>'禁用',
  );


  public function configure() {
    unset($this['updated_at']);
    unset($this['created_at']);
    unset($this->widgetSchema['pid']);
    unset($this->widgetSchema['child_attr']);
    $this->disableLocalCSRFProtection();

    $this->setWidget('name', new sfWidgetFormInput(array(), array('class'=>'w180')));
    $this->setValidator('name',
        new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 32),
        array('required' => '栏目名称必填',  'max_length' => '不大于32个字')));

    $this->setWidget('controller', new sfWidgetFormInput(array(), array('class'=>'w240')));
    $this->setValidator('controller',
        new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 64),
            array('required' => '控制器名称必填',  'max_length' => '不大于64个字')));

    $this->setWidget('action_name', new sfWidgetFormInput(array(), array('class'=>'w240')));
    $this->setValidator('action_name',
        new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 64),
            array('required' => 'action名称必填',  'max_length' => '不大于64个字')));

    $this->setWidget('menu_group', new sfWidgetFormInput(array(), array('class'=>'w160')));
    $this->setValidator('menu_group', new sfValidatorString(array('required' => false), array()));


    $this->setWidget('is_public', new sfWidgetFormChoice(array( "choices" =>array(1=>'是',0=>'否'))));


    $this->setWidget('is_hide', new sfWidgetFormChoice(array( "choices" =>array(1=>'是',0=>'否'))));


    $this->setWidget('menu_status', new sfWidgetFormChoice(array( "choices" => $this->_status)));


    $this->setWidget('listorder', new sfWidgetFormInput(array(), array('class'=>'w20')));
    $this->setValidator('listorder', new sfValidatorString(array('required' => false), array()));

    $this->widgetSchema->setLabels(array(
        'name' => '菜单名称',
        'controller' => '控制器',
        'action_name' => 'action名',
        'is_public' => '是否公开',
        'menu_group' => '分组',
        'child_attr' => '权限集合',
        'menu_status' => '状态',
        'listorder' => '排序',
        'is_hide' => '是否隐藏',
    ));

      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }

    public function myCallback($validator, $values) {
        //开始验证child
        $_child = sfContext::getInstance()->getRequest()->getParameter('child_access');
        /* 获取 access  */
        $_child = $this->_filterChildAccess($validator,$_child);
        $values['child_attr'] = serialize($_child);
        return $values;
    }

    protected function _filterChildAccess($validator,$_child) {
        $_return = array();
        $_length = count($_child['sign']);
        for($i=0;$i<$_length;$i++) {
            if(empty($_child['default'][$i]) || empty($_child['sign'][$i]) || empty($_child['value'][$i]) || !preg_match('/^[A-Za-z_]{1,30}$/',$_child['sign'][$i])) {
                unset($_child['sign'][$i]);
                unset($_child['name'][$i]);
                unset($_child['default'][$i]);
                unset($_child['value'][$i]);
            }
        }
        if(empty($_child['sign'])) return '';
        $_length = count($_child['sign']);
        for($i=0;$i<$_length;$i++) {
            $_return[$_child['sign'][$i]]['name'] = trim(htmlspecialchars($_child['name'][$i]));
            $_return[$_child['sign'][$i]]['sign'] = trim(htmlspecialchars($_child['sign'][$i]));
            $_value = $this->_setChildAccessValue($_child['value'][$i]);
            if(empty($_value['data']) || empty($_value['data'])) {
                throw new sfValidatorError($validator,'标识：'.$_child['sign'][$i] .' 的权限值填写错误！');
            }
            $_return[$_child['sign'][$i]]['value'] = $_value['data'];
            if(!in_array($_child['default'][$i],$_value['value_num'])) {
                throw new sfValidatorError($validator,'标识：'.$_child['sign'][$i] .' 的权限默认值不在权限值中，请检查！');
            }
            $_return[$_child['sign'][$i]]['default'] = trim(htmlspecialchars($_child['default'][$i]));
        }
        return $_return;
    }

    protected function _setChildAccessValue($_value) {
        $_return = array();
        $_value = explode('|',$_value);
        foreach($_value as $k=>$v) {
            $_tmp = explode('=',$v);
            if(empty($_tmp[1]) || empty($_tmp[0])) continue;
            $_return['data'][$k]['text'] = $_tmp[1];
            $_return['data'][$k]['value'] = $_tmp[0];
            $_return['value_num'][] = $_tmp[0];
        }
        return $_return;
    }


















}
