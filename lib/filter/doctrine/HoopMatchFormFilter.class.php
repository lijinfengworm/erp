<?php

/**
 * HoopMatch filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class HoopMatchFormFilter extends BaseHoopMatchFormFilter
{
    public function setup(){
        parent::setup();
        
        $this->setWidget('id',new sfWidgetFormFilterInput());
        $this->setWidget('china_time',new sfWidgetFormFilterDate(
                array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'template'=>'开始%from_date%<br />结束%to_date%')
                ));
        
        $this->setValidator('id', new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false, 'trim'=>true))));
        $this->setValidator('home_team_name', new sfValidatorSchemaFilter('text', new sfValidatorString(array('required' => false, 'trim'=>true))));
        $this->setValidator('away_team_name', new sfValidatorSchemaFilter('text', new sfValidatorString(array('required' => false, 'trim'=>true))));
    }
    public function configure()
    {
    }
}
