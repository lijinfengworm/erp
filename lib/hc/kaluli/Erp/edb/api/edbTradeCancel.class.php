<?php
//E店宝撤销订单
class edbTradeCancel
{
    public $fields = '';

    public $xmlValues = '';



    public function __construct()
	{

	}

	public function setFields( $fields )
	{
		$this->fields = $fields;
		return $this;
	}

	public function setTid( $tid )
	{
        $this->xmlValues['tid'] = $tid;
		return $this;
	}


    private function _parame($val) {
        if(empty($val)) return '';
        return $val;
    }

    public  function setXmlValues() {
        $xml  = "<order>
                    <orderInfo>
                        <tid>".$this->_parame(isset($this->xmlValues['tid']) ? $this->xmlValues['tid'] : '')."</tid>
                    </orderInfo>
                </order>";
        $xmlValues = str_replace(array(" ","　","\t","\n","\r"),array("","","","",""), $xml);
        $this->xmlValues=trim($xmlValues);
        return $this;
    }





}