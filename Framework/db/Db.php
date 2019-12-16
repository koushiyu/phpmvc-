<?php


namespace framework\db;



class Db
{
    private  static $DB = null;
    public static function factory(){
        if (self::$DB== null) {
            //如果没有,则创建当前类的实例
            $dsn    = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8';
            self::$DB = new \PDO($dsn, DB_USER, DB_PASS);
        }
        return self::$DB;
    }

}