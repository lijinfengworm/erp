<?php

/**
 * voiceObjectTag filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class voiceObjectTagFormFilter extends BasevoiceObjectTagFormFilter
{
  public function configure()
  {
    $this->setWidget('voice_object_id', new sfWidgetFormInputText());
    $this->setWidget('voice_tag_id', new sfWidgetFormInputText());
    $this->setValidator('voice_object_id', new sfValidatorPass());
    $this->setValidator('voice_tag_id', new sfValidatorPass());
  }
}
