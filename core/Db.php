<?php
namespace core;

class Db
{

    private static $conn;
    /**
     * return db connection
     */
    public static function getConn()
    {

        if (static::$conn === null) {
            static::$conn = new \PDO('mysql:host=localhost;dbname=trips', 'phplay', 'zx');
            static::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            static::$conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        }

        return static::$conn;
    }

    /**
     * handle custom query
     */
    public static function query($sql, $fetchAll = false)
    {
        if (empty($sql)) {
           return false;
        }
        $conn = self::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        if (!$fetchAll) {
            return $stmt->fetch();
        } else {
            return $stmt->fetchAll();
        }
          
    }
}
