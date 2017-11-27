<?php

namespace core\exceptions;

class BaseException extends \Exception
{

    // Email
    const CODE_EMAIL_DEFAULT = 1;
    const CODE_EMAIL_FORMAT = 2;
    const CODE_EMAIL_EXIST = 3;
    // DB
    const CODE_DB_DEFAULT = 4;
    const CODE_DB_FORMAT = 5;
    const CODE_DB_EXIST = 6;
    // Password
    const CODE_PWD_DEFAULT = 7;
    const CODE_PWD_FORMAT = 8;
    const CODE_PWD_EXIST = 9;
    // Files
    const CODE_FILE_DEFAULT = 10;
    const CODE_FILE_FORMAT = 11;
    const CODE_FILE_EXIST = 12;

    protected static $messages = [
        self::CODE_EMAIL_DEFAULT => 'Please enter your email',
        self::CODE_EMAIL_FORMAT => 'Please enter valid email address.',
        self::CODE_EMAIL_EXIST => 'Email is already used.',
        self::CODE_DB_DEFAULT => 'Please fill all required files',
        self::CODE_DB_FORMAT => 'Db not responding, please try leater',
        self::CODE_DB_EXIST => 'Db error.Data dont exist',
        self::CODE_PWD_DEFAULT => 'Please enter valid password',
        self::CODE_PWD_FORMAT => 'Password must contain 6 characters.',
        self::CODE_PWD_EXIST => 'Password confirmation error',
        self::CODE_FILE_DEFAULT => 'Please upload the file',
        self::CODE_FILE_FORMAT => 'File is to large.',
        self::CODE_FILE_EXIST => 'Not valid file format',
    ];

    public function getCustomMessage()
    {
        if (array_key_exists($this->getCode(), static::$messages)) {
            return static::$messages[$this->getCode()];
        }

        return $this->getMessage();
    }
}
