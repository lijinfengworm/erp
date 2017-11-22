<?php
 /*
 * 验证邮编
 */
class myValidatorPostcode extends sfValidatorRegex
{
  const REGEX_POSTCODE = '/^\d{6}$/';
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', self::REGEX_POSTCODE);
  }
}
