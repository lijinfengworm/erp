<?php
class hcPBSms extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["hcPBSms"]["1"] = "PBInt";
    $this->values["1"] = "";
    self::$fieldNames["hcPBSms"]["1"] = "id";
    self::$fields["hcPBSms"]["2"] = "PBString";
    $this->values["2"] = "";
    self::$fieldNames["hcPBSms"]["2"] = "phone_num";
    self::$fields["hcPBSms"]["3"] = "PBString";
    $this->values["3"] = "";
    self::$fieldNames["hcPBSms"]["3"] = "msg_text";
  }
  function id()
  {
    return $this->_get_value("1");
  }
  function set_id($value)
  {
    return $this->_set_value("1", $value);
  }
  function phone_num()
  {
    return $this->_get_value("2");
  }
  function set_phone_num($value)
  {
    return $this->_set_value("2", $value);
  }
  function msg_text()
  {
    return $this->_get_value("3");
  }
  function set_msg_text($value)
  {
    return $this->_set_value("3", $value);
  }
}
?>