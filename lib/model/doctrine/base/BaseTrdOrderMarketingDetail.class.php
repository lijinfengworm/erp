<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('TrdOrderMarketingDetail', 'trade');

/**
 * BaseTrdOrderMarketingDetail
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $order_number
 * @property integer $marketing_id
 * @property string $attr
 * 
 * @method integer                 getId()           Returns the current record's "id" value
 * @method string                  getOrderNumber()  Returns the current record's "order_number" value
 * @method integer                 getMarketingId()  Returns the current record's "marketing_id" value
 * @method string                  getAttr()         Returns the current record's "attr" value
 * @method TrdOrderMarketingDetail setId()           Sets the current record's "id" value
 * @method TrdOrderMarketingDetail setOrderNumber()  Sets the current record's "order_number" value
 * @method TrdOrderMarketingDetail setMarketingId()  Sets the current record's "marketing_id" value
 * @method TrdOrderMarketingDetail setAttr()         Sets the current record's "attr" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTrdOrderMarketingDetail extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('trd_order_marketing_detail');
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('order_number', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('marketing_id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 10,
             ));
        $this->hasColumn('attr', 'string', 10000, array(
             'type' => 'string',
             'length' => 10000,
             ));

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}