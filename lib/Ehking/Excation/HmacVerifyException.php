<?php
/**
 *
 *
 * PHP Version 5
 *
 * @category  Class
 * @file      HmacVerifyException.php
 * @package Ehking/Excation
 * @author    chao.ma <chao.ma@ehking.com>
 */

//namespace Ehking/Excation;
require_once 'ExceptionInterface.php';


class HmacVerifyException extends InvalidArgumentException implements ExceptionInterface
{

    public function __construct($message = array(), $code = 400)
    {
        $message['error'] = 'invalid_hmac';
        parent::__construct(serialize($message), $code);
    }
} 