<?php
namespace luka\model;

abstract class Model
{
    private static $db = null;

    protected $errors = [];
 
    protected function getDb(): \PDO 
    {
        if (self::$db === null) {
            $config = include __DIR__ . '/../config.php';
            $db = $config['db'];
            $dsn = "pgsql:host=".$db['host'] . ";port=5432;dbname=" . $db['database'] ;                        
            
            self::$db = new \PDO($dsn, $db['username'], $db['password'], [ 
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
            ]);
        }
        return self::$db;        
    }

    public function getByCol($table, $col, $val)
    {
        $sql = "SELECT * FROM $table WHERE $col=?";
        $stm = $this->getDb()->prepare($sql);
        $stm->bindValue(1, $val);
        $stm->execute();
        return $stm->fetch();                  
    }        
    
    public function getById($table, $id) 
    {
        return $this->getByCol($table, 'id', $id);
    }
    
    protected abstract function validate();
    
    public function getErrors()
    {
        return $this->errors;
    }    
    
    
}