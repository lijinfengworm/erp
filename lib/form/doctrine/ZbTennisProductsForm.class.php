<?php

/**
 * ZbTennisProducts form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ZbTennisProductsForm extends ZbProductsForm
{
    public function configure() {
        parent::configure();
        
        $this->setWidget('left_param', new sfWidgetFormTextarea(array(), array("maxlength" => 1000, 'rows' => 7, 'cols' => 50)));
        $this->setValidator('left_param', new sfValidatorString(array('max_length' => 1000, 'required' => false)));
        
        $this->setWidget('use_star', new sfWidgetFormInputText());
        $this->setValidator('use_star', new sfValidatorString(array('max_length' => 500, 'required' => false)));
        
        $this->setWidget('buy_url', new sfWidgetFormInputText(array(), array('size' => 70, 'maxlength' => 255)));
        $this->setValidator('buy_url', new sfValidatorString(array('max_length' => 255, 'required' => false)));
    }
    public function processValues($values) {
        
        return parent::processValues($values);
    }
}
