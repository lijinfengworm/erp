<?php

//namespace Ehking/Excation;
require_once 'ExceptionInterface.php';


class InvalidResponseException extends InvalidArgumentException implements ExceptionInterface
{
    public function __construct($message = array(), $code = 400 )
    {
        $message['error'] = 'invalid_response';
        parent::__construct(serialize($message), $code);
    }
}
