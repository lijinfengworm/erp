<?php

/**
 * TrdSpecialVanclQuestion form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdSpecialVanclQuestionForm extends BaseTrdSpecialVanclQuestionForm
{
  public function configure()
  {
    unset($this["created_at"]);
    unset($this["updated_at"]);
    unset($this["deleted_at"]);       
  }
}
