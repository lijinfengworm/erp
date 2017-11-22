<?php
class hcPBPm extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["hcPBPm"]["1"] = "PBInt";
    $this->values["1"] = "";
    self::$fieldNames["hcPBPm"]["1"] = "uid_to";
    self::$fields["hcPBPm"]["2"] = "PBString";
    $this->values["2"] = "";
    self::$fieldNames["hcPBPm"]["2"] = "username_to";
    self::$fields["hcPBPm"]["3"] = "PBInt";
    $this->values["3"] = "";
    self::$fieldNames["hcPBPm"]["3"] = "uid_from";
    self::$fields["hcPBPm"]["4"] = "PBString";
    $this->values["4"] = "";
    self::$fieldNames["hcPBPm"]["4"] = "username_from";
    self::$fields["hcPBPm"]["5"] = "PBString";
    $this->values["5"] = "";
    self::$fieldNames["hcPBPm"]["5"] = "title";
    self::$fields["hcPBPm"]["6"] = "PBString";
    $this->values["6"] = "";
    self::$fieldNames["hcPBPm"]["6"] = "content";
    self::$fields["hcPBPm"]["7"] = "PBInt";
    $this->values["7"] = "";
    self::$fieldNames["hcPBPm"]["7"] = "sendtime";
    self::$fields["hcPBPm"]["8"] = "PBInt";
    $this->values["8"] = "";
    self::$fieldNames["hcPBPm"]["8"] = "isnew";
    self::$fields["hcPBPm"]["9"] = "PBInt";
    $this->values["9"] = "";
    self::$fieldNames["hcPBPm"]["9"] = "type";
  }
  function uid_to()
  {
    return $this->_get_value("1");
  }
  function set_uid_to($value)
  {
    return $this->_set_value("1", $value);
  }
  function username_to()
  {
    return $this->_get_value("2");
  }
  function set_username_to($value)
  {
    return $this->_set_value("2", $value);
  }
  function uid_from()
  {
    return $this->_get_value("3");
  }
  function set_uid_from($value)
  {
    return $this->_set_value("3", $value);
  }
  function username_from()
  {
    return $this->_get_value("4");
  }
  function set_username_from($value)
  {
    return $this->_set_value("4", $value);
  }
  function title()
  {
    return $this->_get_value("5");
  }
  function set_title($value)
  {
    return $this->_set_value("5", $value);
  }
  function content()
  {
    return $this->_get_value("6");
  }
  function set_content($value)
  {
    return $this->_set_value("6", $value);
  }
  function sendtime()
  {
    return $this->_get_value("7");
  }
  function set_sendtime($value)
  {
    return $this->_set_value("7", $value);
  }
  function isnew()
  {
    return $this->_get_value("8");
  }
  function set_isnew($value)
  {
    return $this->_set_value("8", $value);
  }
  function type()
  {
    return $this->_get_value("9");
  }
  function set_type($value)
  {
    return $this->_set_value("9", $value);
  }
  function setHcPM($uid_to, $username_to, $uid_from, $username_from, $title, $content, $sendtime, $isnew = 1, $type = 1){
      $this->set_uid_to($uid_to);
      $this->set_username_to($username_to);
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