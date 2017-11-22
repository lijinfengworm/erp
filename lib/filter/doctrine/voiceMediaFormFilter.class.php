<?php

/**
 * voiceMedia filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class voiceMediaFormFilter extends BasevoiceMediaFormFilter {

    public function configure() {
        unset($this->widgetSchema['category'], $this->validatorSchema['category']);
        unset($this->widgetSchema['description'], $this->validatorSchema['description']);
        unset($this->widgetSchema['url'], $this->validatorSchema['url']);
        
        $this->setWidget('urls', new sfWidgetFormFilterInput(array('label' => 'Url')));
        $this->setValidator('urls', new sfValidatorPass());
    }
    
    public function getFields() {
        $fields = parent::getFields();
        $fields['urls'] = 'Text';
        return $fields;
    }
    
    public function addUrlsColumnQuery($query, $field, $value){
        $rootAlias = $query->getRootAlias();
        return $query->innerJoin($rootAlias . '.voiceMediaUrls u')
                     ->andWhere('u.url like ?', "%".$value['text']."%");     
    }


}
