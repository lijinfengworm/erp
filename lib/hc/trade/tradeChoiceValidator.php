<?php
class tradeChoiceValidator extends sfValidatorChoice {

    protected function doClean($value) {
        return "," . join(",", $value) . ",";
    }

}

