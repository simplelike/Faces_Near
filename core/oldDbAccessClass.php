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
        DBAccessClass::$dbConnection = @mysql_connect(Settings::dbHost.':'.Settings::dbPort, Settings::dbUser, Settings::dbPass);
        if (!DBAccessClass::$dbConnection){ 
            $this->error(mysql_error());
            return;
        }
        if (@!mysql_select_db(Settings::dbName, DBAccessClass::$dbConnection)){
            $this->error(mysql_error());
        }
        $this->runQuery("SET NAMES utf8");
    }
    public function runQuery($sql){
        $this->checkIt();
        $this->lastResult = @mysql_db_query(Settings::dbName, $sql, DBAccessClass::$dbConnection);
        if (!$this->lastResult){
            //die(mysql_error());
                $this->error(mysql_error(DBAccessClass::$dbConnection)." in ".$sql);
                return;
        }
        return;
        
    }
    public function getInsertedId(){
        $this->checkIt();
        return (int)mysql_insert_id(DBAccessClass::$dbConnection);
    }
    public function getRowsNumber(){
        $this->checkIt();
        if (!$this->lastResult){
            die('It is necessary to run query before getting rows number');
        }
        return (int)mysql_num_rows($this->lastResult);
    }
    public function getNextRow(){
        $this->checkIt();
        if (!$this->lastResult){
            die('It is necessary to run query before getting next row');
        }
        return mysql_fetch_assoc($this->lastResult);
    }
    static public function escape($string) {
        $result = mysql_escape_string($string);
        return $result;
    }
}