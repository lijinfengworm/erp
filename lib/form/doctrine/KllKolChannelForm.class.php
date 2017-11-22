<?php

/**
 * KllKolChannel form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllKolChannelForm extends BaseKllKolChannelForm
{
  public function configure()
  {
    $this->setValidator('title',
        new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 24),
            array('required' => '标题必填！',  'max_length' => '不大于24个字')));

    $this->setValidator("channel_code",new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 14),
        array('required' => '渠道标识必填',  'max_length' => '不大于14个字')));

    $this->setValidator("commision",new sfValidatorInteger(array('required' => true, 'trim' => true),
        array('required' => '佣金比例必填','invalid'=>'不能是小数')));

  }
}
