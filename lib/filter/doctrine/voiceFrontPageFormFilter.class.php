<?php

/**
 * voiceFrontPage filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class voiceFrontPageFormFilter extends BasevoiceFrontPageFormFilter
{
  public function configure()
  {
      $this->setWidget('voice_objects_list', new myVoiceObjectListInputWidget(array('separator'=>',')));
      $this->setValidator('voice_objects_list', new myVoiceObjectListInputValidator(array('model' => 'voiceObject', 'required' => false,'separator'=>',','replace'=>'，')));

  }
}
