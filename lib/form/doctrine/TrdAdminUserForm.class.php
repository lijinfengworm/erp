<?php

/**
 * 后台用户 form
 * About 梁天
 */
class TrdAdminUserForm extends BaseTrdAdminUserForm {
    private $_status = array(
        '1'=>'正常',
        '2'=>'禁用',
        '3'=>'删除',
    );


  public function configure() {
      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['last_login_time']);
      unset($this['last_login_ip']);
      unset($this['verify']);



      $this->setWidget('password',
          new sfWidgetFormInputPassword(array('always_render_empty' => true),
              array('class'=>' w360',"autocomplete"=>"off",'autocomplete'=>'off','placeholder'=>"密码已被隐藏，如果要【修改|添加】密码请直接输入")));


      $this->widgetSchema->setHelp('password','<span class="c-red">密码已被隐藏，如果要【修改|添加】密码请直接输入</span>');

      /*
      $this->setWidget('role', new sfWidgetFormSelectDoubleList(array('choices'=>
          FunBase::DesignateArrayTwoToOne(TrdAdminRoleTable::getInstance()->getNormalRole('id,name','all'),array('id','name'))

       $this->setValidator('role', new sfValidatorChoice(array('choices'=>
          array_keys(FunBase::DesignateArrayTwoToOne(
              TrdAdminRoleTable::getInstance()->getNormalRole('id,name','all'),array('id','name'))),
          'required'=>true,'multiple' => true),array('required'=>'用户组不得为空!')));
      )));*/
      // 渠道
      /*$this->setWidget('channel', new sfWidgetFormChoice(array(
        'multiple' => 'true',
        'expanded' => true,
        'choices' => TrdAdminChannelTable::getInstance()->getNormalChannel('id,channel', 'all'),

      )));*/
      $this->setValidator('channel',
        new sfValidatorString(
            array('required' => false, 'trim' => true, 'max_length' => 200)
            ));

      
      // $this->widgetSchema['channel']->setAttribute('checked', "true");


      $this->setWidget('role', new sfWidgetFormChoice(array('choices'=>
          FunBase::DesignateArrayTwoToOne(TrdAdminRoleTable::getInstance()->getNormalRole('id,name','all'),array('id','name'))
      )));


      $this->setValidator('role', new sfValidatorChoice(array('choices'=>
          array_keys(FunBase::DesignateArrayTwoToOne(
              TrdAdminRoleTable::getInstance()->getNormalRole('id,name','all'),array('id','name'))),
          'required'=>true),array('required'=>'用户组不得为空!')));

      $this->setWidget('user_status', new sfWidgetFormChoice(array("choices" => $this->_status)));

      $this->setValidator('hupu_uid',
        new sfValidatorInteger(
            array('min' => 1,'required' => true, 'trim' => true),
            array('required' => '虎扑ID必填！','min' => 'hupu_uid 错误，最小值为1')));

      $this->setValidator('username',
        new sfValidatorString(
            array('required' => true, 'trim' => true, 'max_length' => 20),
            array('required' => '用户名必填！', 'invalid' => '最大不得超过20个字')));


      if($this->isNew()) {
          $this->setValidator('password',
              new sfValidatorString(
                  array('required' => true, 'trim' => true, 'min_length' => 6),
                  array('required' => '密码必填！', 'min_length' => '最小不得小于6个字符')));
      } else {
          $this->setValidator('password', new sfValidatorString(array('required' => false)));
      }
      $this->setValidator('email', new sfValidatorEmail(array('trim' => true),array('required'=>'email不得为空!')));

      $this->setValidator('mobile',
        new myValidatorMobile(array('trim' => true),array('invalid'=>'手机号格式不正确！','required'=>'手机号不得为空!')));

      $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
      );
  }




    /**
     * 回调验证
     */
    public function myCallback($validator, $values) {
        if ($this->isNew()) {
            $user_flag = TrdAdminUserTable::getInstance()->hasUserField('username',$values['username'],0);
            $uid_flag = TrdAdminUserTable::getInstance()->hasUserField('hupu_uid',$values['hupu_uid'],0);
        } else {
            $user_flag = TrdAdminUserTable::getInstance()->hasUserField('username',$values['username'],$values['id']);
            $uid_flag = TrdAdminUserTable::getInstance()->hasUserField('hupu_uid',$values['hupu_uid'],$values['id']);
        }
        if($user_flag) throw new sfValidatorError($validator, '用户名已存在，请换一个！');
        if($uid_flag) throw new sfValidatorError($validator, '虎扑uid已存在，请换一个！');
        return $values;
    }



    /**
     * 新增或者修改会自动触发
     * 提示：form 如果想获取当前记录的obj $this->getObject()->getXXX()
     */
  public function processValues($values) {
    $values = parent::processValues($values);
    if($this->isNew()) {  //判断是否新增
        //加盐码
        $values['verify'] = FunBase::genRandomString();
        //save password
        $values['password'] = md5($values['password'] . md5($values['verify']));
    } else {
        $values['verify'] = $this->getObject()->getVerify();
        if(empty($values['password'])) {
            $values['password'] = $this->getObject()->getPassword();
        } else {
            $values['password'] = md5($values['password'] . md5($values['verify']));
        }
    }
    $values['role'] = '-'.$values['role'].'-';
    return $values;
  }


}
