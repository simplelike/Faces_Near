<?php

require_once('abstractTableClass.php');
require_once('dbAccessClass.php');

class AbstractHistoryTableClass extends AbstractTableClass{
    protected $groupField = '';
    public function AbstractHistoryTableClass($table, $fields, $groupField){
        if (array_search($groupField, $fields) === false){
            die('Group field must be in field list');
        }
        $this->groupField = $groupField;
        parent::AbstractTableClass($table, $fields, array());
    }
    protected function born(){
        return new AbstractHistoryTableClass($this->table, $this->fields, $this->groupField);
    }
    public function set(){
        die("metod 'set' is prohibited");
    }
    protected function selectFaceQueryPart(){
  	$select = '';
        foreach(array_keys($this->fields) as $key){
            if ($select != ''){
                $select .= ', ';
            }
            $select .= '`tbl`.`'.$key.'` as `'.$key.'`';
        }
        return $select;
    }
    public function selectFace($where = '', $order = ''){
        $this->checkIt();
        $dbAccess = new DBAccessClass();
        if ($this->forwardError($dbAccess)){
            return;
        }
        $query = "SELECT ".$this->selectFaceQueryPart()." FROM `".$this->table."` as `tbl`";
        $query .= " INNER JOIN (SELECT MAX(`id`) as `id` FROM `".$this->table."` GROUP BY `".$this->groupField."`) as `max`";
        $query .= " ON `max`.`id` = `tbl`.`id`";
        if (!empty($where)){
            $query .= " AND ".$where;
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
    public function selectFaceDistinct($field, $where = '', $order = ''){
        $this->checkIt();
        $dbAccess = new DBAccessClass();
        if ($this->forwardError($dbAccess)){
            return;
        }
        $query = "SELECT DISTINCT `tbl`.`".$field."` FROM `".$this->table."` as `tbl`";
        $query .= " INNER JOIN (SELECT MAX(`id`) as `id` FROM `".$this->table."` GROUP BY `".$this->groupField."`) as `max`";
        $query .= " ON `max`.`id` = `tbl`.`id`";
        if (!empty($where)){
            $query .= " AND ".$where;
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
    public function update (){
        die("metod 'update' is prohibited");
    }
    public function delete() {
        die("metod 'delete' is prohibited");
    }
}
