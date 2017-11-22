<?php
interface HCPayResultInterface
{
  /**
   * To determine wether the payment is success or not
   *
   * @return Boolean
   */
  public function isSuccess();  
}