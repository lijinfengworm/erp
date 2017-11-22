<?php

class myTradeTagListInputWidget extends sfWidgetFormInput {

    public function configure($options = array(), $attributes = array()) {
        $this->addRequiredOption('separator');
        $this->setOption('separator', $options['separator']);
        parent::configure($options, $attributes);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $visible_value = '';        
        if (is_string($value)) {
            $visible_value = $value;
            
        } elseif (!empty($value)) {
            $tags = trdProductTagTable::getTagsByIds($value);
            foreach ($tags as $v) {
                if(!$visible_value){
                    $visible_value .= $v->getName();
                }else{
                    if($v['TrdNewsTag'][0]['is_default']){
                        $visible_value = $v->getName().$this->getOption('separator').$visible_value;
                    }else{
                        $visible_value .= $this->getOption('separator').$v->getName();
                    }
                }
            }
        }
        return parent::render($name, $visible_value, $attributes, $errors);
    }

}
