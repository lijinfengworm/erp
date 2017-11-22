<?php

/**
 * Overrides certain methods that require a master connection.
 * 
 * @package    sfDoctrineMasterSlavePlugin
 * @subpackage record
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 * @version    SVN: $Id: sfDoctrineMasterSlaveRecord.class.php 28144 2010-02-20 01:11:48Z Kris.Wallsmith $
 */
abstract class sfDoctrineMasterSlaveRecord extends sfDoctrineRecord
{
  /**
   * Saves the current record.
   * 
   * Forces a master connection.
   * 
   * @see Doctrine_Record
   */
  public function save(Doctrine_Connection $conn = null)
  {
    $conn = ProjectConfiguration::getActive()->getMasterConnection($conn ? $conn : $this->getTable()->getConnection());

    return parent::save($conn);
  }

  /**
   * Replaces the current record.
   * 
   * Forces a master connection.
   * 
   * @see Doctrine_Record
   */
  public function replace(Doctrine_Connection $conn = null)
  {
    $conn = ProjectConfiguration::getActive()->getMasterConnection($conn ? $conn : $this->getTable()->getConnection());

    return parent::replace($conn);
  }

  /**
   * Deletes the current record from the database.
   * 
   * Forces a master connection.
   * 
   * @see Doctrine_Record
   */
  public function delete(Doctrine_Connection $conn = null)
  {
    $conn = ProjectConfiguration::getActive()->getMasterConnection($conn ? $conn : $this->getTable()->getConnection());

    return parent::delete($conn);
  }
}
