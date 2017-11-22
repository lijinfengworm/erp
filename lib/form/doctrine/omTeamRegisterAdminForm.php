<?php
/*
 * Created on 2011-3-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class omTeamRegisterAdminForm extends sfForm
{
  public function configure()
  {
  	
  	$choiceData = Doctrine_Core::getTable('Location')->getSelectData();
    $url = sfContext::getInstance()->getController()->genUrl('om_team/getPlaceByAjax');
  	$this->setWidgets(array(
     
          'name'              => new sfWidgetFormInputText(),
          'mobile'            => new sfWidgetFormInputText(),
          'logo_url'		  => new sfWidgetFormInputFile(),
          'Location_id'      => new myWidgetsFormJqueryGangedSelect(array('url' =>$url ,'firstSelectData'=>$choiceData)),//new sfWidgetFormChoice(array('choices' =>$choiceData ,'expanded' => false)),
          'leader_id'		  => new sfWidgetFormInputText(),
  		  'leader_qq'		  => new sfWidgetFormInputText(),
  		  'leaderName'    	  => new sfWidgetFormInputText(),
  		));

    $this->setValidators(array(
     'name'             => new sfValidatorAnd(array(
	    						new  myValidatorString(
	    							array('min_length'=>4,'max_length'=>14), 
	    							array('min_length'=>'球队名长度为2-7个汉字','max_length'=>'最长为7个中文长度')),
	    						new  sfValidatorRegex(
	    							array('pattern'=>'/^([0-9a-zA-Z一-龥]+)$/u'),
	    							array('invalid'=>'球队名仅允许中英文和数字，长度为2-7个汉字'))
	    						),
	    						array('required'=>true),
	    						array('required'=>'球队名称不能为空')
    						) , 
	    							
      'mobile'	          => new sfValidatorRegex(array('pattern'=>'/^[1][0-9]{10}$/'),array('invalid'=>'手机为11位,请填写有效的手机号码','required'=>'球队电话不能为空')),
      'logo_url'          => new sfValidatorFile(array('mime_types'=>'web_images','path'=>'/uploads/teamlogo'),array('required'=>'请上传球队图片')),
      'Location_id'      => new sfValidatorString(array('required' => true),array('required'=>'所属区不能为空')),
      'leader_id'	      => new sfValidatorRegex(array('pattern'=>'/^\d*$/'),array('invalid'=>'请填写数字','required'=>'领队Id不能为空')),
      'leaderName'        => new sfValidatorAnd(array(
	    						new  myValidatorString(
	    							array('min_length'=>4,'max_length'=>14), 
	    							array('min_length'=>'领队名长度为2-7个汉字','max_length'=>'最长为7个中文长度')),
	    						new  sfValidatorRegex(
	    							array('pattern'=>'/^([0-9a-zA-Z一-龥]+)$/u'),
	    							array('invalid'=>'领队名仅允许中英文和数字，长度为2-7个汉字'))
	    						),
	    						array('required'=>true),
	    						array('required'=>'领队名称不能为空')
    						) ,     
      'leader_qq'    	  => new sfValidatorRegex(array('pattern'=>'/^[0-9]{5,12}$/'),array('invalid'=>'qq是由5-12位数字组成，请输入合法qq','required'=>'领队qq不能为空')),
    ));

    $this->widgetSchema->setNameFormat('team[%s]');
  	
  	
  	$this->widgetSchema->setLabels(array(
  		'name' 			  =>'球队名称',
  		'mobile' 		  =>'球队电话',
  		'logo_url' 		  => '球队图片',
  		'Location_id'	  => '地区',
  		'leader_id'		  => '领队Id',
  		'leader_qq'		  => '领队QQ',
  		'leaderName'	  => '领队名称',
  	));
  	
  }
}
 
