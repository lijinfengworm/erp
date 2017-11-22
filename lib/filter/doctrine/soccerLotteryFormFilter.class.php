<?php

/**
 * soccerLottery filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class soccerLotteryFormFilter extends BasesoccerLotteryFormFilter
{
  public function configure()
  {
      parent::configure();

      $this->setValidator('match_num', new mySoccerLotteryValidator(array('required'=>false,'trim'=>true)));
  }

}
