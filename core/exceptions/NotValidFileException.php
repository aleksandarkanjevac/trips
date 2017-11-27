<?php

namespace core\exceptions;

use Throwable;

class NotValidFileException extends BaseException 
{

    public function __construct($message = "", $code = self::CODE_DEFAULT) 
    {
        parent::__construct($message, $code);
    }

}
