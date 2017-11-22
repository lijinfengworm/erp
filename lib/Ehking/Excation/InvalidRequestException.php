<?php

//namespace Ehking/Excation;

$dir=dirname(__FILE__);

require_once dirname(__FILE__).'/ExceptionInterface.php';

class InvalidRequestException extends InvalidArgumentException implements ExceptionInterface
{
    public function __construct($message = array(), $code = 400)
    {
        $message['error'] = 'invalid_request';
        parent::__construct(serialize($message), $code);
    }
}
