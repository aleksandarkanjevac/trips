<?php

namespace core\exceptions;

use Throwable;

class NotValidDbException extends BaseException 
{
    
    public function __construct( $message = "", $code=self::CODE_DEFAULT) 
    {
        parent::__construct($message, $code);
    }
}
