<?php

/**
 * replyBlackList filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class replyBlackListFormFilter extends BasereplyBlackListFormFilter
{
  public function configure()
  {
        parent::configure();
        $this->setValidator('user_id', new sfValidatorPass(array('required' => false)));
  }
}
