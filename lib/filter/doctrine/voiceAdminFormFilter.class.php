<?php

/**
 * voiceAdmin filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class voiceAdminFormFilter extends BasevoiceAdminFormFilter
{
  public function configure()
  {
      parent::configure();
      $this->setWidget('uid',new sfWidgetFormInput());
      $this->setValidator('uid', new sfValidatorPass(array('required' => false)));
  }
  
  public function getFields() {
      $fields = parent::getFields();
      $fields['uid'] = 'Number';
      return $fields;
  }
  
  public function addUidColumnQuery($query, $field, $value){
      $uid = (int)trim($value);   
      $rootAlias = $query->getRootAlias();
      return $query->andWhere($rootAlias . '.user_id = ?', $uid);
  }
  
}
