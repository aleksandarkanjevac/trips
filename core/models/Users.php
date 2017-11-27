<?php

namespace core\models;

class Users implements ModelDbInterface
{

    use ModelTrait;
    
    /**
     *@var int @id;
     */
    public $id;
    /**
     * @var string $name
     */
    public $name;
    /**
     * @var string $email
     */
    public $email;
    /**
     * @var string $password
     */
    public $password;
    /**
     * @var string $remember_token
     */
    public $remember_token;
     /**
     *@var int $status;
     */
    public $status;
     /**
     * @var string $created_at DATE and TIME string
     */
    public $created_at;

    public static function table()
    {
        return 'users';
    }
}
