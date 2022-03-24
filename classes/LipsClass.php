<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/abstractTableClass.php');

class LipsClass extends AbstractTableClass{
    public function LipsClass(){
        $userFields = array(
                'title',
                'totalcount',
                'totalpercent',
                'exponentpercent'
            );
        $userUpdatable = array();
        parent::AbstractTableClass('attr_lips', $userFields, $userUpdatable);
    }

    protected function born(){
        return new LipsClass($this->table, $this->fields, $this->updatable);
    }
    public function selectByTitle($title){
        $this->selectByEscaped("title", "'".DBAccessClass::escape($title)."'");
    }
    
}