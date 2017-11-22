<?php

/**
 * Location filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class LocationFormFilter extends BaseLocationFormFilter
{
  public function configure()
  {
  }
  
  function doBuildQuery(array $values){
      $query = parent::doBuildQuery($values);
      return $query->andWhere($query->getRootAlias().'.root_id !=1');
  }
}
