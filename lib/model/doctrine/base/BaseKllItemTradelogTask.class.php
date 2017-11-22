<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllItemTradelogTask', 'kaluli');

/**
 * BaseKllItemTradelogTask
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $product_id
 * @property integer $total_num
 * @property integer $current_num
 * @property integer $end_time
 * @property integer $updated_time
 * @property integer $status
 * 
 * @method integer             getId()           Returns the current record's "id" value
 * @method integer             getProductId()    Returns the current record's "product_id" value
 * @method integer             getTotalNum()     Returns the current record's "total_num" value
 * @method integer             getCurrentNum()   Returns the current record's "current_num" value
 * @method integer             getEndTime()      Returns the current record's "end_time" value
 * @method integer             getUpdatedTime()  Returns the current record's "updated_time" value
 * @method integer             getStatus()       Returns the current record's "status" value
 * @method KllItemTradelogTask setId()           Sets the current record's "id" value
 * @method KllItemTradelogTask setProductId()    Sets the current record's "product_id" value
 * @method KllItemTradelogTask setTotalNum()     Sets the current record's "total_num" value
 * @method KllItemTradelogTask setCurrentNum()   Sets the current record's "current_num" value
 * @method KllItemTradelogTask setEndTime()      Sets the current record's "end_time" value
 * @method KllItemTradelogTask setUpdatedTime()  Sets the current record's "updated_time" value
 * @method KllItemTradelogTask setStatus()       Sets the current record's "status" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllItemTradelogTask extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_item_tradelog_task');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('product_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('total_num', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('current_num', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('end_time', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('updated_time', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 1,
             ));


        $this->index('status_idx', array(
             'fields' => 
             array(
              0 => 'status',
             ),
             ));
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}