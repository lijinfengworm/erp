<?php

/**
 * KaluliLipinkaCard form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KaluliLipinkaCardForm extends BaseKaluliLipinkaCardForm
{

    public static $_status = array(
        0 => '未发放',
        1 => '已发放',
        2 => '已使用',
    );


    public function configure()
  {
  }
}
