<?php

/**
 * TrdAdminUser filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdAdminUserFormFilter extends BaseTrdAdminUserFormFilter
{

  private $_status = array(
      '1'=>'正常',
      '2'=>'禁用',
      '3'=>'删除',
  );
  public function configure()
  {
    parent::configure();
    //$this->setWidget('status', new sfWidgetFormChoice(array("choices" => $this->_status)));
    //$this->setValidator('status', new sfValidatorInteger(array('required' => false)));
  }
}
