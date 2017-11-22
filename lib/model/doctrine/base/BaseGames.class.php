<?php

/**
 * BaseGames
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property Doctrine_Collection $GameServers
 * 
 * @method integer             getId()          Returns the current record's "id" value
 * @method string              getName()        Returns the current record's "name" value
 * @method integer             getStatus()      Returns the current record's "status" value
 * @method Doctrine_Collection getGameServers() Returns the current record's "GameServers" collection
 * @method Games               setId()          Sets the current record's "id" value
 * @method Games               setName()        Sets the current record's "name" value
 * @method Games               setStatus()      Sets the current record's "status" value
 * @method Games               setGameServers() Sets the current record's "GameServers" collection
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGames extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('games');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', 100, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 100,
             ));
        $this->hasColumn('status', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             ));

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('GameServers', array(
             'local' => 'id',
             'foreign' => 'game_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}