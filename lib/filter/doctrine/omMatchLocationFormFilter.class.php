<?php

/**
 * omMatchLocation filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class omMatchLocationFormFilter extends BaseomMatchLocationFormFilter
{
	public function configure()
	{
		$choiceData = Doctrine_Core::getTable('Location')->getSelectData();
		$url = sfContext::getInstance()->getController()->genUrl('om_match_location/getPlaceByAjax');
		 
		$this->useFields(array('name','address','lat','lon'));
		$this->setWidget('Location_id', new myWidgetsFormJqueryGangedSelect(array('url' =>$url ,'firstSelectData'=>$choiceData)));
		$this->setValidator('Location_id',new sfValidatorString(array( 'required' => true)));
	 $this->setValidators(array(
      'name'                   => new sfValidatorPass(array('required' => false)),
      'address'                => new sfValidatorPass(array('required' => false)),
      'lat'                    => new sfValidatorPass(array('required' => false)),
      'lon'                    => new sfValidatorPass(array('required' => false)),
      'Location_id'           => new sfValidatorString(array('required' => false)),
	 ));

	}

	public function buildQuery(array $values){
		if(!empty($values)){
		$name = $values['name']['text'];
		$address = $values['address']['text'];
		$lat = $values['lat']['text'];
		$lon = $values['lon']['text'];
		$Location_id = $values['Location_id'];
		$selected_options_1 = $values['selected_options_1'];
		$selected_options_2 = $values['selected_options_2'];
		$childreIds = array();
		if($Location_id>0){
			$childreIds = LocationTable::getChildens($Location_id);
		}elseif($selected_options_2>0){
			$childreIds = LocationTable::getChildens($selected_options_2);
		}elseif ($selected_options_1>0){
			$childreIds = LocationTable::getChildens($selected_options_1);
		}
		
		$q = omMatchLocationTable::getInstance()
		->createQuery('om')
		->where('1=1');
		if(!empty($name)){
			$q->andWhere('name LIKE ?',$name );
		}
		if(!empty($address)){
			$q->andWhere('address LIKE ?',$address );
		}
		if(!empty($lat)){
			$q->andWhere('lat LIKE ?',$lat );
		}
		if(!empty($lon)){
			$q->andWhere('lon LIKE ?',$lon );
		}
		if(count($childreIds)>0){
			$q->andWhere('location_id IN('.implode(",", $childreIds).')',array() );
		}
		return $q;
		}
		return null;
	}
}
