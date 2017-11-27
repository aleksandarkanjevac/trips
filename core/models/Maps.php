<?php

namespace core\models;

class Maps implements ModelDbInterface
{

    use ModelTrait;
    
    /**
     *@var int @id;
     */
    public $id;
     /**
     *@var int $user_id;
     */
    public $user_id;
    /**
     * @var string $title
     */
    public $title;
    /**
     * @var string $map_name
     */
    public $map_name;
    /**
     * @var string $map_identificator
     */
    public $map_identificator;
     /**
     * @var string $upload_at DATE and TIME string
     */
    public $upload_at;
    
    
    public static function table()
    {
        return 'maps';
    }
}
