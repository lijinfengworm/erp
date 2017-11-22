<?php

/**
 * KllKol form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllKolForm extends BaseKllKolForm
{
  public function configure()
  {

    $this->setWidget("abstract",new sfWidgetFormInput(array(),array("class"=>"w460")));

    $this->setWidget('channel_id', new sfWidgetFormChoice(array( "choices" => self::getKolChannel())));
    $this->setValidator('channel_id', new sfValidatorChoice(
        array('choices'=>array_keys(self::getKolChannel())),array('required' => '必填')));

  }


  public static function getKolChannel() {
      $channels = KllKolChannelTable::getInstance()->createQuery()->where("status = 1")->fetchArray();
      $returns = array();
      foreach ($channels as $k => $v) {
          $returns[$v['id']] = $v['title'];
      }
      return $returns;
  }
}
