<?php

/**
 * twitterTopic filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class twitterTopicFormFilter extends BasetwitterTopicFormFilter
{
  public function configure()
  {
      $this->useFields(array('title', 'description', 'slug', 'type'));
      $this->setWidget('type', new sfWidgetFormChoice(array('choices' => array('' =>'','USER' => '基于用户', 'MESSAGE' => '基于单条星声'))));
  }
}
