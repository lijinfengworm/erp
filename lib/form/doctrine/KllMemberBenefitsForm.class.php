<?php

/**
 * KllMemberBenefits form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllMemberBenefitsForm extends BaseKllMemberBenefitsForm
{
  public function configure()
  {
    $this->validatorSchema->setPostValidator(
        new sfValidatorCallback(array('callback' => array($this, 'myCallback')))
    );
  }
  //调用callback校验code是否重复

  /**
   * 回调验证
   */
  public function myCallback($validator, $values) {
    //校验是否存在相同的code
    $benefitsInfo = KllMemberBenefitsTable::getInstance()->findOneByCode($values['code']);
    if($benefitsInfo) {
       $id = $benefitsInfo->getId();
       if(isset($values['id']) && ($id != $values['id'])) {
         throw new sfValidatorError($validator, '优惠码不能重复！');
       }
    }

    return $values;
  }
}
