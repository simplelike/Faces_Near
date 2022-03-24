<?php

require_once('abstractErrorClass.php');
require_once('settings.php');

class DBAccessClass extends AbstractErrorClass {
    static private $dbConnection = NULL; 
    private $lastResult;
    public function DBAccessClass(){
        if (DBAccessClass::$dbConnection){
            return;
        }
        DBAccessClass::$dbConnection = new mysqli(Settings::dbHost.':'.Settings::dbPort, Settings::dbUser, Settings::dbPass);
        if (DBAccessClass::$dbConnection -> connect_error) {
            $this->error(DBAccessClass::$dbConnection -> connect_error);
            return;
        }
        if(!mysqli_select_db(DBAccessClass::$dbConnection, Settings::dbName)) {
            $this->error(DBAccessClass::$dbConnection -> connect_error);
        }

        //DBAccessClass::$dbConnection = @mysql_connect(Settings::dbHost.':'.Settings::dbPort, Settings::dbUser, Settings::dbPass);
        // if (!DBAccessClass::$dbConnection){ 
        //     $this->error(mysql_error());
        //     return;
        // }
        
        // if (@!mysql_select_db(Settings::dbName, DBAccessClass::$dbConnection)){
        //     $this->error(mysql_error());
        // }
        $this->runQuery("SET NAMES utf8");
    }
    public function runQuery($sql){
        $this->checkIt();
        $this->lastResult = DBAccessClass::$dbConnection->query($sql);
        //$this->lastResult = @mysql_db_query(Settings::dbName, $sql, DBAccessClass::$dbConnection);
        if (!$this->lastResult){
            //die(mysql_error());
                $this->error(mysqli_error(DBAccessClass::$dbConnection)." in ".$sql);
                return;
        }
        return;
        
    }
    public function getInsertedId(){
        $this->checkIt();
        return (int)mysqli_insert_id(DBAccessClass::$dbConnection);
    }
    public function getRowsNumber(){
        $this->checkIt();
        if (!$this->lastResult){
            die('It is necessary to run query before getting rows number');
        }
        return (int)mysqli_num_rows($this->lastResult);
    }
    public function getNextRow(){
        $this->checkIt();
        if (!$this->lastResult){
            die('It is necessary to run query before getting next row');
        }
        return mysqli_fetch_assoc($this->lastResult);
    }
    public function escape($string) {
        $result = mysqli_real_escape_string(DBAccessClass::$dbConnection, $string);
        return $result;
    }
}