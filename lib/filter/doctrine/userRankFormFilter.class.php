<?php

/**
 * userRank filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userRankFormFilter extends BaseuserRankFormFilter
{
    public function configure()
    {
      parent::configure();
      $this->setWidget('query_user_id',new sfWidgetFormInput());
      $this->setValidator('query_user_id', new sfValidatorPass(array('required' => false)));
    }

    public function getFields(){
      $fields = parent::getFields();
      $fields['query_user_id'] = 'Text';
      return $fields;
    }

    public function addQueryUserIdColumnQuery($query, $field, $value){
        if(is_numeric($value) && ($value > 0)){
            $rootAlias = $query->getRootAlias();
            return $query->andWhere($rootAlias . '.user_id = ?', (int)$value);
        }else{
            return $query;
        }
    }

}
