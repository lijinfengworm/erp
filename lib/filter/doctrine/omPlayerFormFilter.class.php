<?php

/**
 * omPlayer filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class omPlayerFormFilter extends BaseomPlayerFormFilter {

    public function configure() {
        $this->useFields(array('name', 'om_teams_list'));
        $this->setWidget('om_teams_list', new sfWidgetFormFilterInput());
        $this->setValidator('om_teams_list', new sfValidatorPass(array('required' => false)));
    }

    public function addOmTeamsListColumnQuery(Doctrine_Query $query, $field, $values) {
        if (is_array($values)) {
            $values = array_shift($values);
        }
        $values = trim($values);
        $query->leftJoin($query->getRootAlias() . '.omMembership omMembership')
                ->leftJoin('omMembership.omTeam t')
                ->andWhere('t.name like "%'.$values.'%" or t.id = "'.$values.'"');
    }

}
