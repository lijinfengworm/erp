<?php

/**
 * TrdStore form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdStoreForm extends BaseTrdStoreForm
{
  public function configure()
  { 
      $this->setWidget('name', new sfWidgetFormInput(array(), array('size' => 50, 'maxlength' => 100)));
      $this->setValidator('name', new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 30), array('required' => '标题必填',  'max_length' => '标题不大于15个字')));
      
      $this->setWidget('sort', new sfWidgetFormInput(array(), array('maxlength' => 19, 'size' => 20)));
      $this->setValidator('sort', new sfValidatorString(array('required' => true, 'trim' => true)));

      $this->setWidget('is_index', new sfWidgetFormInputHidden());
      $this->setWidget('is_haitao', new sfWidgetFormInputHidden());
      $this->setWidget('is_display', new sfWidgetFormInputHidden());
      $this->setWidget('is_delete', new sfWidgetFormInputHidden());

      $this->widgetSchema->setHelp('type','0代表海淘，1代表国内');
  }
  
 
  public function processValues($values) {
        $values = parent::processValues($values);
        $values['is_index'] = 1;
        return $values;
    }
}
