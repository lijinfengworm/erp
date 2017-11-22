<?php
 /*
 * 验证身份证号码
 */
class myValidatorIdentityNumber extends sfValidatorRegex
{
  const REGEX_IdentityNumber = '/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/';
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', self::REGEX_IdentityNumber);
  }
}
