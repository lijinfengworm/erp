<?php

/**
 * voiceMediaUrl filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class voiceMediaUrlFormFilter extends BasevoiceMediaUrlFormFilter
{
  public function configure()
  {
      unset($this['voice_media_id']);
      $this->widgetSchema->setLabels(array('url' => '&nbsp;'));
  }
}
