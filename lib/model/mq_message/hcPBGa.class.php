<?php
class hcPBGa extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["hcPBGa"]["1"] = "PBString";
    $this->values["1"] = "";
    self::$fieldNames["hcPBGa"]["1"] = "url";
  }
  function url()
  {
    return $this->_get_value("1");
  }
  function set_url($value)
  {
    return $this->_set_value("1", $value);
  }
}
?>