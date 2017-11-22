<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('KllBBOrderSynApi', 'kaluliBB');

/**
 * BaseKllBBOrderSynApi
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property int $id
 * @property integer $zt
 * @property varchar $order_number
 * @property integer $send_gj
 * @property timestamp $send_gj_date
 * @property integer $send_hg
 * @property timestamp $send_hg_date
 * @property integer $send_zz
 * @property timestamp $send_zz_date
 * @property timestamp $syn_date
 * @property integer $send_yb_gj
 * @property timestamp $send_yb_gj_date
 * @property varchar $logisticJSON
 * @property integer $send_yb_hg
 * @property timestamp $send_yb_hg_date
 * @property integer $pay_type
 * @property integer $send_nr
 * @property timestamp $send_nr_date
 * @property varchar $source
 * @property varchar $edi_orderno
 * @property integer $send_jd_pay
 * @property timestamp $send_jd_pay_date
 * 
 * @method int              getId()               Returns the current record's "id" value
 * @method integer          getZt()               Returns the current record's "zt" value
 * @method varchar          getOrderNumber()      Returns the current record's "order_number" value
 * @method integer          getSendGj()           Returns the current record's "send_gj" value
 * @method timestamp        getSendGjDate()       Returns the current record's "send_gj_date" value
 * @method integer          getSendHg()           Returns the current record's "send_hg" value
 * @method timestamp        getSendHgDate()       Returns the current record's "send_hg_date" value
 * @method integer          getSendZz()           Returns the current record's "send_zz" value
 * @method timestamp        getSendZzDate()       Returns the current record's "send_zz_date" value
 * @method timestamp        getSynDate()          Returns the current record's "syn_date" value
 * @method integer          getSendYbGj()         Returns the current record's "send_yb_gj" value
 * @method timestamp        getSendYbGjDate()     Returns the current record's "send_yb_gj_date" value
 * @method varchar          getLogisticJSON()     Returns the current record's "logisticJSON" value
 * @method integer          getSendYbHg()         Returns the current record's "send_yb_hg" value
 * @method timestamp        getSendYbHgDate()     Returns the current record's "send_yb_hg_date" value
 * @method integer          getPayType()          Returns the current record's "pay_type" value
 * @method integer          getSendNr()           Returns the current record's "send_nr" value
 * @method timestamp        getSendNrDate()       Returns the current record's "send_nr_date" value
 * @method varchar          getSource()           Returns the current record's "source" value
 * @method varchar          getEdiOrderno()       Returns the current record's "edi_orderno" value
 * @method integer          getSendJdPay()        Returns the current record's "send_jd_pay" value
 * @method timestamp        getSendJdPayDate()    Returns the current record's "send_jd_pay_date" value
 * @method KllBBOrderSynApi setId()               Sets the current record's "id" value
 * @method KllBBOrderSynApi setZt()               Sets the current record's "zt" value
 * @method KllBBOrderSynApi setOrderNumber()      Sets the current record's "order_number" value
 * @method KllBBOrderSynApi setSendGj()           Sets the current record's "send_gj" value
 * @method KllBBOrderSynApi setSendGjDate()       Sets the current record's "send_gj_date" value
 * @method KllBBOrderSynApi setSendHg()           Sets the current record's "send_hg" value
 * @method KllBBOrderSynApi setSendHgDate()       Sets the current record's "send_hg_date" value
 * @method KllBBOrderSynApi setSendZz()           Sets the current record's "send_zz" value
 * @method KllBBOrderSynApi setSendZzDate()       Sets the current record's "send_zz_date" value
 * @method KllBBOrderSynApi setSynDate()          Sets the current record's "syn_date" value
 * @method KllBBOrderSynApi setSendYbGj()         Sets the current record's "send_yb_gj" value
 * @method KllBBOrderSynApi setSendYbGjDate()     Sets the current record's "send_yb_gj_date" value
 * @method KllBBOrderSynApi setLogisticJSON()     Sets the current record's "logisticJSON" value
 * @method KllBBOrderSynApi setSendYbHg()         Sets the current record's "send_yb_hg" value
 * @method KllBBOrderSynApi setSendYbHgDate()     Sets the current record's "send_yb_hg_date" value
 * @method KllBBOrderSynApi setPayType()          Sets the current record's "pay_type" value
 * @method KllBBOrderSynApi setSendNr()           Sets the current record's "send_nr" value
 * @method KllBBOrderSynApi setSendNrDate()       Sets the current record's "send_nr_date" value
 * @method KllBBOrderSynApi setSource()           Sets the current record's "source" value
 * @method KllBBOrderSynApi setEdiOrderno()       Sets the current record's "edi_orderno" value
 * @method KllBBOrderSynApi setSendJdPay()        Sets the current record's "send_jd_pay" value
 * @method KllBBOrderSynApi setSendJdPayDate()    Sets the current record's "send_jd_pay_date" value
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseKllBBOrderSynApi extends sfDoctrineMasterSlaveRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('kll_order_syn_api');
        $this->hasColumn('id', 'int', 10, array(
             'type' => 'int',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 10,
             ));
        $this->hasColumn('zt', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('order_number', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('send_gj', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('send_gj_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('send_hg', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('send_hg_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('send_zz', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('send_zz_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('syn_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('send_yb_gj', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('send_yb_gj_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('logisticJSON', 'varchar', 500, array(
             'type' => 'varchar',
             'length' => 500,
             ));
        $this->hasColumn('send_yb_hg', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => 11,
             ));
        $this->hasColumn('send_yb_hg_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('pay_type', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('send_nr', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('send_nr_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('source', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
        $this->hasColumn('edi_orderno', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
        $this->hasColumn('send_jd_pay', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('send_jd_pay_date', 'timestamp', null, array(
             'type' => 'timestamp',
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