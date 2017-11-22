<?php
interface HCPayInterface
{
  /**
   * Pay for the bill
   *
   * @return object An object the implements HCPayResultInterface interface
   */
  public function pay();  
}