<?php
 /*
 * 验证手机号
 */
class myValidatorMobile extends sfValidatorRegex
{
  const REGEX_MOBILE = '/^1\d{10}$/';
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', self::REGEX_MOBILE);
  }
}
