<?php

/**
 * KllAttachment form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KllAttachmentForm extends BaseKllAttachmentForm
{
  public function configure()
  {
      unset( $this['is_use'],$this['type'],$this['aid'],$this['is_use']);
  }
}
