<?php

/**
 * 返回中英文数字混合长度
 * myValidatorString validates a string. It also converts the input value to a string.
 *
 * @package    symfony
 * @subpackage validator
 * @author     feixue <feixue@gmail.com>
 * @version    
 */
class myValidatorString extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * max_length: The maximum length of the string
   *  * min_length: The minimum length of the string
   *
   * Available error codes:
   *
   *  * max_length
   *  * min_length
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('max_length', '"%value%" is too long (%max_length% characters max).');
    $this->addMessage('min_length', '"%value%" is too short (%min_length% characters min).');

    $this->addOption('max_length');
    $this->addOption('min_length');

    $this->setOption('empty_value', '');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $clean = (string) $value;

    $length = (strlen($clean)+mb_strlen($clean,'utf8'))/2;
    if ($this->hasOption('max_length') && $length > $this->getOption('max_length'))
    {
      throw new sfValidatorError($this, 'max_length', array('value' => $value, 'max_length' => $this->getOption('max_length')));
    }

    if ($this->hasOption('min_length') && $length < $this->getOption('min_length'))
    {
      throw new sfValidatorError($this, 'min_length', array('value' => $value, 'min_length' => $this->getOption('min_length')));
    }

    return $clean;
  }
}
