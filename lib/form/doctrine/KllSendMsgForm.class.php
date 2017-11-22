<?php

/**
 * KllSendMsg form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllSendMsgForm extends BaseKllSendMsgForm
{
  public function configure()
  {
        $this->setWidget('mobile', new sfWidgetFormInput(array()));
        $this->setValidator('mobile',
            new sfValidatorRegex(array('pattern'=>'/^[1][0-9]{10}$/'),array('invalid'=>'手机为11位,请填写有效的手机号码','required'=>'电话不能为空')));
  }
}
