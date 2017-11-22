<?php

/**
 * voiceHotEvent filter form.
 *
 * @package    HC
 * @subpackage filter
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class voiceHotEventFormFilter extends BasevoiceHotEventFormFilter {

    public function configure() {
        unset($this['parent_id']);
        unset($this['category']);
        unset($this['show_order']);
        $type = sfContext::getInstance()->getRequest()->getParameter('type');
        if ($type == 1){
            unset($this['voice_tag_id']);
        }
        $this->setWidget('type', new sfWidgetFormInputHidden());
        $this->setValidator('type', new sfValidatorPass());
        $this->setDefault('type', $type);
    }

}
