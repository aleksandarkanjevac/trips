<?php

namespace core\models;

use \core\Db;

trait ModelTrait
{
    /**
     * fetch data from database table based on primary key if record not found return null
     * @param int $pk
     * @param boolean $asModel 
     * @return mixed null | array | \core\models\className
     * @throws \Exception
     */
    public static function findByPk($pk, $asModel = true) {
        try {
            $conn = Db::getConn();
            $stmt = $conn->prepare('SELECT * FROM ' . self::table() . ' WHERE id = :id LIMIT 1');
            $stmt->bindValue(':id', $pk);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);

            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            if (!$asModel) {
                return $row;
            }

            $className = get_called_class();
            $model = new $className;
            foreach ($row as $attr => $val) {
                if (property_exists($model, $attr)) {
                    $model->{$attr} = $val;
                }
            }
            return $model;
        } catch (\PDOException $e) {
            // todo throw some our custom exception instead
            throw new \Exception("Error: {$e->getMessage()}");
        }
    }


    /**
    *populate model properties based on $attrs key=>value
    * @param array $attrs
    * @return $this/properties from array and forward for func. save which deciding to start functions update or insert
    *
    */
    public function setAttr(array $attrs)
    {
        
        foreach ($attrs as $attr => $value) {
            if (property_exists($this, $attr)) {
                $this->{$attr} = $value;
            }
        }
                return $this;
    }
     
    /**
     * handle saving model.if model id is not set run insert othervise run update
     */
    public function save()
    {
        if (isset($this->id)) {
            return $this->update();
        }
        return $this->insert();
    }
    
    /**
     * handle new record insert
     */
    protected function insert()
    {
        $conn = Db::getConn();
        $attrs = get_object_vars($this);
        
        try {
            $values = [];
            $attributes = [];
            foreach ($attrs as $attr => $value) {
                $values[] = $attr;
                $attributes[] = ":{$attr}";
            }
        
            $stmt = $conn->prepare('INSERT INTO ' . self::table() . ' (' . implode(',', $values) . ') VALUES (' . implode(',', $attributes) . ')');
            foreach ($attrs as $attr => $value) {
                $stmt->bindValue(":{$attr}", $value);
            }
            $stmt->execute();
            $this->id = $conn->lastInsertId();
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error: {$e->getMessage()}");
        }
    }
    
    /**
     * handle update record
     */
    protected function update()
    {
        $conn = Db::getConn();
        
        $attrs = get_object_vars($this);
        unset($attrs['id']);
        
        try {
            $cols = [];
        
            foreach ($attrs as $attr => $val) {
                $cols[] = "{$attr} = :{$attr}";
            }
        
            $stmt = $conn->prepare('UPDATE ' . self::table() . ' SET ' . implode(', ', $cols) . ' WHERE id = :id');
        
            foreach ($attrs as $attr => $val) {
                $stmt->bindValue(":{$attr}", $val);
            }
            $stmt->bindValue(':id', $this->id);
        
            return $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception("Error: {$e->getMessage()}");
        }
    }



    /**
    * @param $filter table columns to select, default all (*)
    * @param array $attrs where part of query
    * @param boolean $fetchAll true || false wheter to limit select to one or all records
    * @param boolean $asModel true || false depends we want query as array or object
    * @param array $options list of aditional query options, curently only pagination
    * @return array or object
    */
    public static function selectByAttributes($filter = '*', array $attrs,$fetchAll = false, $asModel = true, $options=[])
    {
        if (count($attrs) == 0) {
            throw new Exception('Please send some attributes');
        }
        
        try {
            $conn = Db::getConn();
            $where = [];
            foreach ($attrs as $attr => $val) {
                $where[] = "{$attr} = :{$attr}";
            }
            $sql = 'SELECT ' . $filter . ' FROM ' . self::table() . ' WHERE ' . implode(' AND ', $where);

            if (array_key_exists('pagination', $options) && array_key_exists('page', $options['pagination']) && array_key_exists('limit', $options['pagination'])) {
                $start = $options['pagination']['page'] * $options['pagination']['limit'];
                $sql .= " LIMIT {$start}, {$options['pagination']['limit']}";
            }

            $stmt = $conn->prepare($sql);
            foreach ($attrs as $attr => $val) {
                $stmt->bindValue(":{$attr}", $val);
            }
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            if (!$fetchAll) {
               $rows = $stmt->fetch();
            }else{
                $rows = $stmt->fetchAll();
            }
            
        
            if (!$rows) {
                return $fetchAll ? [] : false;
            }
        
            if (!$asModel) {
                return $rows;
            }
        
            $className = get_called_class();
            if(!$fetchAll){

                $model = new $className;
                foreach ($rows as $attr => $val) {
                    if (property_exists($model, $attr)) {
                        $model->{$attr} = $val;
                    }
                }
            
                return $model;
            }else{
                $models = [];
                foreach ($rows as $row) {
                    $model = new $className;
                    foreach ($row as $attr => $val) {
                        if (property_exists($model, $attr)) {
                            $model->{$attr} = $val;
                        }
                    }
                    $models[] = $model;
                }
                return $models;
            }
        } catch (\Exception $e) {
            throw new \Exception("Error: {$e->getMessage()}");
        }
    }

    /**
    * handle delete record 
    */
    public function delete() {
        if (!isset($this->id)) {
            return true;
        }

        $conn = Db::getConn();

        try {
            $stmt = $conn->prepare('DELETE FROM ' . self::table() . ' WHERE id = :id');
            $stmt->bindValue(':id', $this->id);

            return $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception("Error: {$e->getMessage()}");
        }
    }

    
}
