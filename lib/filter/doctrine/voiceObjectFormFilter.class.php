<?php

/**
 * voiceObject filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class voiceObjectFormFilter extends BasevoiceObjectFormFilter
{
      public function configure()
      {
          //赛事直播话题类型筛选
          $this->setWidget('is_live_event', new sfWidgetFormChoice(array('choices' => array('1' => '是'), 'expanded' => TRUE, 'multiple' => FALSE, 'label' => '是否为赛事直播话题')));
          $this->setValidator('is_live_event', new sfValidatorPass());
      }

    public function getFields() {
        $fields = parent::getFields();
        $fields['is_live_event'] = 'Text';
        return $fields;
    }

    public function addIsLiveEventColumnQuery($query, $field, $value){
        if ($value['text'] == 1){
            $rootAlias = $query->getRootAlias();

            return $query->andWhere($rootAlias . '.type = ?', voiceObjectTable::$live_event_type);
        }else{
            return $query;
        }
    }


}
