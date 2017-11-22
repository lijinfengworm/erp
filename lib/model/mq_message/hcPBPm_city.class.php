<?php
class hcPBPm_city extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["hcPBPm_city"]["1"] = "PBString";
    $this->values["1"] = "";
    self::$fieldNames["hcPBPm_city"]["1"] = "city";
    self::$fields["hcPBPm_city"]["2"] = "PBInt";
    $this->values["2"] = "";
    self::$fieldNames["hcPBPm_city"]["2"] = "uid_from";
    self::$fields["hcPBPm_city"]["3"] = "PBString";
    $this->values["3"] = "";
    self::$fieldNames["hcPBPm_city"]["3"] = "username_from";
    self::$fields["hcPBPm_city"]["4"] = "PBString";
    $this->values["4"] = "";
    self::$fieldNames["hcPBPm_city"]["4"] = "title";
    self::$fields["hcPBPm_city"]["5"] = "PBString";
    $this->values["5"] = "";
    self::$fieldNames["hcPBPm_city"]["5"] = "content";
    self::$fields["hcPBPm_city"]["6"] = "PBInt";
    $this->values["6"] = "";
    self::$fieldNames["hcPBPm_city"]["6"] = "sendtime";
    self::$fields["hcPBPm_city"]["7"] = "PBInt";
    $this->values["7"] = "";
    self::$fieldNames["hcPBPm_city"]["7"] = "isnew";
    self::$fields["hcPBPm_city"]["8"] = "PBInt";
    $this->values["8"] = "";
    self::$fieldNames["hcPBPm_city"]["8"] = "type";
  }
  function city()
  {
    return $this->_get_value("1");
  }
  function set_city($value)
  {
    return $this->_set_value("1", $value);
  } 
  function uid_from()
  {
    return $this->_get_value("2");
  }
  function set_uid_from($value)
  {
    return $this->_set_value("2", $value);
  }
  function username_from()
  {
    return $this->_get_value("3");
  }
  function set_username_from($value)
  {
    return $this->_set_value("3", $value);
  }
  function title()
  {
    return $this->_get_value("4");
  }
  function set_title($value)
  {
    return $this->_set_value("4", $value);
  }
  function content()
  {
    return $this->_get_value("5");
  }
  function set_content($value)
  {
    return $this->_set_value("5", $value);
  }
  function sendtime()
  {
    return $this->_get_value("6");
  }
  function set_sendtime($value)
  {
    return $this->_set_value("6", $value);
  }
  function isnew()
  {
    return $this->_get_value("7");
  }
  function set_isnew($value)
  {
    return $this->_set_value("7", $value);
  }
  function type()
  {
    return $this->_get_value("8");
  }
  function set_type($value)
  {
    return $this->_set_value("8", $value);
  }
  function setHcPBPm_city($city, $uid_from, $username_from, $title, $content, $sendtime, $isnew = 1, $type = 1){
      $this->set_city($city);
      $this->set_uid_from($uid_from);
      $this->set_username_from($username_from);
      $this->set_title($title);
      $this->set_content($content);
      $this->set_sendtime($sendtime);
      $this->set_isnew($isnew);
      $this->set_type($type); 
  }
}
?>