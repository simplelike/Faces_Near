<?php

require_once('abstractErrorClass.php');
require_once('dbAccessClass.php');

class AbstractTableClass extends AbstractErrorClass{
    protected $table = '';
    protected $fields = array('id' => 0);
    protected $updatable = array();
    public function AbstractTableClass($table, $fields, $updatable){
        $this->table = $table;
        if (isset($updatable['id'])){
            die('Field `id` can not be updateable');
        }
        if (isset($updatable['create_at'])){
            die('Field `create_at` can not be updateable');
        }
        if (isset($updatable['content'])){
            die('Field `content` can not be updateable');
        }
        foreach($fields as $key => $val){
            $this->fields[$val] = '';
        }
        foreach($updatable as $key => $val){
            if (!isset($this->fields[$val])){
                die('Updateable field `'.$val.'` must be setted in field list');
            }
        }
        $this->updatable = $updatable;
    }
    protected function born(){
        return new AbstractTableClass($this->table, $this->fields, $this->updatable);
    }
    static public function unique($array){
        $ret = array();
        foreach($array as $value){
            $ret[$value->get('id')] = $value;
        }
        return $ret;
    }
    public function get($fieldName){
        $this->checkIt();
        $id = (int)$this->fields['id'];
        if (empty($id)){
            die('It`s neccessary to select before getting');
        }
        if (!isset($this->fields[$fieldName])){
            die('Field `'.$fieldName.'` is unknown field');
        }
        if ($fieldName == "content"){
            $dbAccess = new DBAccessClass();
            if ($this->forwardError($dbAccess)){
                return;
            }
            $dbAccess->runQuery("SELECT `content` FROM `".$this->table."` WHERE `id`=".$id);
            if ($this->forwardError($dbAccess)){
                return;
            }
            if ($dbAccess->getRowsNumber() == 0){
                $this->error( 'Unknown id');
                return;
            }
            $row = $dbAccess->getNextRow();
            if (isset($row['content'])){
                $this->fields['content'] = $row['content'];
            } else {
                $this->fields['content'] = 'NULL';
            }
        }
        return $this->fields[$fieldName];
    }
    public function set($fieldName, $value){
        $this->checkIt();
        if (!isset($this->fields[$fieldName])){
            die('Field `'.$fieldName.'` is unknown field');
        }
        if (array_search($fieldName, $this->updatable) === false){
            die('Field `'.$fieldName.'` is not updatable');
        }
        $this->fields[$fieldName] = $value;       
    }
    protected function selectQueryPart(){
  	$select = '';
        foreach(array_keys($this->fields) as $key){
            if ($key == "content"){
                continue;
            }
            if ($select != ''){
                $select .= ', ';
            }
            $select .= '`'.$key.'`';
        }
        return $select;
    }
    protected function selectByEscaped($field, $escaped){
  	$this->checkIt();
      
        if (!isset($this->fields[$field])){
            die('Field `'.$field.'` is unknown field');
        }
        $dbAccess = new DBAccessClass();
        if ($this->forwardError($dbAccess)){
            return;
        }
        $selectingFields = $this->selectQueryPart();
        $dbAccess->runQuery("SELECT ".$selectingFields." FROM `".$this->table."` WHERE `".$field."`=".$escaped);
        
        if ($this->forwardError($dbAccess)){
            return;
        }
        if ($dbAccess->getRowsNumber() == 0){
            $this->error( 'Unknown '.$field.' = '.$escaped);
            return;
        }
        $row = $dbAccess->getNextRow();
        foreach($row as $key => $value){
            if (isset($value)){
                $this->fields[$key] = $value;
            } else {
                $this->fields[$key] = 'NULL';
            }
        }
        return;
    }
    public function selectById($id){
        $this->checkIt();
        if (!is_numeric($id)){
            die('Field `id` must be numerical for selecting');
        }
        $dbAccess = new DBAccessClass();
        $this->selectByEscaped('id', $dbAccess -> escape($id));
        return;
    }
    public function getArraySize($where = ''){
        $this->checkIt();
        $dbAccess = new DBAccessClass();
        if ($this->forwardError($dbAccess)){
            return;
        }
        $query = "SELECT COUNT(*) as `count` FROM `".$this->table."`";
        if (!empty($where)){
            $query .= " WHERE ".$where;
        }
        $dbAccess->runQuery($query);
        if ($this->forwardError($dbAccess)){
            return;
        }
        $row = $dbAccess->getNextRow();
        return $row['count'];
    }
    public function selectArray($where = '', $order = '', $joins=''){
        $this->checkIt();
        $dbAccess = new DBAccessClass();
        if ($this->forwardError($dbAccess)){
            return;
        }
        $query = "SELECT ".$this->selectQueryPart()." FROM `".$this->table."`";
        if (!empty($joins)){
            $query .= " ".$joins." ";
        }
        if (!empty($where)){
            $query .= " WHERE ".$where;
        }
        if (!empty($order)){
            $query .= " ORDER BY ".$order;
        }
        $dbAccess->runQuery($query);
        if ($this->forwardError($dbAccess)){
            return;
        }
        $result = array();
        while($row = $dbAccess->getNextRow()){
            $newInst = $this->born();
            foreach($row as $key => $value){
                if (isset($value)){
                    $newInst->fields[$key] = $value;
                } else {
                    $newInst->fields[$key] = 'NULL';
                }
            }
            $result[] = $newInst;
        }
        return $result;
    }
    public function selectLast($where = ''){
        $this->checkIt();
        list($result) = $this->selectArray($where, '`id` DESC LIMIT 1');
        if (!$this->isDone()){
            return;
        }
        if (is_null($result)){
            $this->error( 'Where is no last row');
                return;
        }
        foreach($result->fields as $key => $value){
            $this->fields[$key] = $value;
        }
        return;
    }
    public function selectDistinct($field, $where = '', $order = ''){
        $this->checkIt();
        $dbAccess = new DBAccessClass();
        if ($this->forwardError($dbAccess)){
            return;
        }
        $query = "SELECT DISTINCT `".$field."` FROM `".$this->table."`";
        if (!empty($where)){
            $query .= " WHERE ".$where;
        }
        if (!empty($order)){
            $query .= " ORDER BY ".$order;
        }
        $dbAccess->runQuery($query);
        if ($this->forwardError($dbAccess)){
            return;
        }
        $result = array();
        while($row = $dbAccess->getNextRow()){
            if (isset($row[$field])){
                $result[] = $row[$field];
            } else {
                $result[] = 'NULL';
            }
        }
        return $result;
    }
    public function insert($values, $justInsert = true){
        $this->checkIt();
        if (isset($values['id'])){
            die('Field `id` can`t be used for inserting');
        }
        if (isset($values['create_at'])){
            die('Field `create_at` can`t be used for inserting');
        }
        foreach($this->fields as $key => $val){
            if ($key == 'id' || $key == 'create_at'){
                continue;
            }
            if (!isset($values[$key])){
                die('Value of field `'.$key.'` is necessary for inserting');
            }
        }
        $insertkeys = '';
        $insertvalues = '';
        if (isset($this->fields['create_at'])){
            $insertkeys = '`create_at`';
            $insertvalues = 'CURRENT_TIMESTAMP()';
        }
        $dbAccess = new DBAccessClass();
        foreach($values as $key => $val){
            if (!isset($this->fields[$key])){
                die('Unknown field `'.$key.'` for inserting');
            }
            if ($insertkeys != ''){
                $insertkeys .= ', ';
            }
            $insertkeys .= '`'.$dbAccess -> escape($key).'`';
            if ($insertvalues != ''){
                $insertvalues .= ', ';
            }
            if ($val === 'NULL'){
                $insertvalues .= "NULL";
            } else {
                $insertvalues .= "'".$dbAccess -> escape($val)."'";
            }
        }
        
        if ($this->forwardError($dbAccess)){
            return;
        }
        $dbAccess->runQuery("INSERT INTO `".$this->table."` (".$insertkeys.") VALUES(".$insertvalues.")");
        if ($this->forwardError($dbAccess)){
            return;
        }
        if (!$justInsert){
            $this->selectById($dbAccess->getInsertedId());
        }
        return;
    }
    public function update (){
        $this->checkIt();
        $id = (int)$this->fields['id'];
        if (empty($id)){
            die('For updating it`s neccessary to select');
        }
        $dbAccess = new DBAccessClass();
        if ($this->forwardError($dbAccess)){
            return;
        }
        $update = '';
        foreach($this->updatable as $val){
            if ($update != ''){
                $update .= ', ';
            }
            $update .= "`".$dbAccess -> escape($val)."` = ";
            if ($this->fields[$val] === 'NULL'){
                $update .= "NULL";
            } else {
                $update .= "'".$dbAccess -> escape($this->fields[$val])."'";
            }
        }
        $dbAccess->runQuery("UPDATE `".$this->table."` SET ".$update." WHERE `id`=".$dbAccess -> escape((int)$this->fields['id']));
        if ($this->forwardError($dbAccess)){
            return;
        }
        return;
    }
    public function delete(){
        $this->checkIt();
        $id = (int)$this->fields['id'];
        if (empty($id)){
            die('For deleting it`s neccessary to select');
        }
        $dbAccess = new DBAccessClass();
        if ($this->forwardError($dbAccess)){
            return;
        }
        $dbAccess->runQuery("DELETE FROM `".$this->table."` WHERE `id`=".$dbAccess -> escape((int)$this->fields['id']));
        if ($this->forwardError($dbAccess)){
            return;
        }
        $this->fields['id'] = 0;
        return;
    }
}
