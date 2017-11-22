<?php

/**
 * BaseGameAllowedPartnerIp
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $partner_id
 * @property string $start_address
 * @property string $end_address
 * @property GamePaymentPartner $GamePaymentPartners
 * 
 * @method integer              getId()                  Returns the current record's "id" value
 * @method integer              getPartnerId()           Returns the current record's "partner_id" value
 * @method string               getStartAddress()        Returns the current record's "start_address" value
 * @method string               getEndAddress()          Returns the current record's "end_address" value
 * @method GamePaymentPartner   getGamePaymentPartners() Returns the current record's "GamePaymentPartners" value
 * @method GameAllowedPartnerIp setId()                  Sets the current record's "id" value
 * @method GameAllowedPartnerIp setPartnerId()           Sets the current record's "partner_id" value
 * @method GameAllowedPartnerIp setStartAddress()        Sets the current record's "start_address" value
 * @method GameAllowedPartnerIp setEndAddress()          Sets the current record's "end_address" value
 * @method GameAllowedPartnerIp setGamePaymentPartners() Sets the current record's "GamePaymentPartners" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGameAllowedPartnerIp extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('GameAllowedPartnerIps');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('partner_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('start_address', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             ));
        $this->hasColumn('end_address', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             ));

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('GamePaymentPartner as GamePaymentPartners', array(
             'local' => 'partner_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}