<?php
namespace Core;

class Db {
    private static $pdo;
    
    public static function init($dsn, $user, $password) {
        if (!self::$pdo) {
            self::$pdo = new \PDO($dsn, $user, $password);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }
    
    public static function isInit() {
        return boolval(self::$pdo);
    }
    
    public static function select($sql, $parameters = null) {
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        return $pdostmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public static function exec($sql, $parameters = null) {
       /* var_dump($sql);*/
        $pdostmt = self::$pdo->prepare($sql);
        return $pdostmt->execute($parameters);
    }
    
    public static function count($sql, $parameters = null) {
        $pdostmt = self::$pdo->prepare($sql);
        $pdostmt->execute($parameters);
        $row = $pdostmt->fetch(\PDO::FETCH_NUM);
        return $row[0];
    }
}



