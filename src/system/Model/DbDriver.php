<?php

namespace App\system\Model;

class DbDriver implements IDbDriver {

    private static $instance;
    private $ins_db;

    private function __construct(){

    }

    public function get_instance(){
        if (self::$instance instanceof static) {
            return self::$instance;
        }
        return self::$instance = new static();
    }

//    3rd video 10:35

      public function setConnection($host, $user, $pass, $dbname){

        try{
            $this->ins_db = new \mysqli($host, $user, $pass, $dbname);

            if ($this->ins_db->connect_error) {
                throw new \Exception('Connection error : '.$this->ins_db->connect_errno.'|'.iconv('CP1251', 'UTF-8', $this->ins_db->connect_error));
            }

            $this->ins_db->query("SET NAME 'UTF8'");
        }
        catch(\Exception $e){
            exit('DB connection error');
        }
}
    public function query($sql){
        return $this->ins_db->query($sql);
}
    public function getInsDb(){
        return $this->ins_db;

}
}