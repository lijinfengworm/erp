<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of hpWidgetFormInputCheckbox
 *
 * @author hcsyp
 */
class hpWidgetFormInputCheckbox extends sfWidgetFormInputCheckbox {

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        if (null !== $value && $value != false ) {
            $attributes['checked'] = 'checked';
        }

        if (!isset($attributes['value']) && null !== $this->getOption('value_attribute_value')) {
            $attributes['value'] = $this->getOption('value_attribute_value');
        }

        return parent::render($name, null, $attributes, $errors);
    }

}

?>
