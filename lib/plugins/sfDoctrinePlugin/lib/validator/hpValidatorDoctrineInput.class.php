<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorDoctrineChoice validates that the value is one of the rows of a table.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfValidatorDoctrineChoice.class.php 27736 2010-02-08 14:50:13Z Kris.Wallsmith $
 */
class hpValidatorDoctrineInput extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * model:      The model class (required)
   *  * query:      A query to use when retrieving objects
   *  * column:     The column name (null by default which means we use the primary key)
   *                must be in field name format
   *  * multiple:   true if the select tag must allow multiple selections
   *  * min:        The minimum number of values that need to be selected (this option is only active if multiple is true)
   *  * max:        The maximum number of values that need to be selected (this option is only active if multiple is true)
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('query', null);
    $this->addOption('column', null);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $value = array_pop($value);
    
    if (!trim($value))
    {
      return null;
    }
    
    if ($query = $this->getOption('query'))
    {
      $query = clone $query;
    }
    else
    {
      $query = Doctrine_Core::getTable($this->getOption('model'))->createQuery();
    }

    $result = $query->andWhere(sprintf('%s.%s = ?', $query->getRootAlias(), $this->getColumn()), $value)->execute();   
    
    if (!count($result))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }
    
    return $result[0]->getId();
  }

  /**
   * Returns the column to use for comparison.
   *
   * The primary key is used by default.
   *
   * @return string The column name
   */
  protected function getColumn()
  {
    $table = Doctrine_Core::getTable($this->getOption('model'));
    if ($this->getOption('column'))
    {
      $columnName = $this->getOption('column');
    }
    else
    {
      $identifier = (array) $table->getIdentifier();
      $columnName = current($identifier);
    }

    return $table->getColumnName($columnName);
  }
}
