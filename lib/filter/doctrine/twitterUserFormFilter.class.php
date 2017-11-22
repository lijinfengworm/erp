<?php

/**
 * twitterUser filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class twitterUserFormFilter extends BasetwitterUserFormFilter
{
  public function configure()
  {
      $this->setWidget('twitter_tag_name',new sfWidgetFormInput());
      $this->setValidator('twitter_tag_name', new sfValidatorPass(array('required' => false)));
  }
  
      public function getFields() {
        $fields = parent::getFields();
        $fields['twitter_tag_name'] = 'Text';
        return $fields;
    }
    
    public function addTwitterTagNameColumnQuery($query, $field, $value){
        $rootAlias = $query->getRootAlias();
        return $query->innerJoin($rootAlias . '.twitterTag tt')
                     ->andWhere('tt.name like ?', "%".$value."%");     
    }    
}
