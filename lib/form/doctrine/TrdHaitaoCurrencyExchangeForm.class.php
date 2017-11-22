<?php

/**
 * TrdHaitaoCurrencyExchange form.
 *
 * @package    HC
 * @subpackage form
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TrdHaitaoCurrencyExchangeForm extends BaseTrdHaitaoCurrencyExchangeForm {

    public function configure() {
        unset($this['updated_at']);
        unset($this['created_at']);
        $this->setWidget('currency_from', new sfWidgetFormChoice(array('choices' => array('0' => '美元', '1' => '人民币', '2' => '欧元', '3' => '英镑', '4' => '日元', '5' => '港元')), array('required' => '内容必填')));
        $this->setWidget('currency_to', new sfWidgetFormChoice(array('choices' => array('0' => '美元', '1' => '人民币', '2' => '欧元', '3' => '英镑', '4' => '日元', '5' => '港元')), array('required' => '内容必填')));
        $this->setWidget('exchange_rate', new sfWidgetFormInput(array()));
        $this->setValidator('exchange_rate', new sfValidatorNumber(array('required' => true), array('required' => '内容必填')));
    }

}
