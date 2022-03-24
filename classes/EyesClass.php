<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/abstractTableClass.php');

class EyesClass extends AbstractTableClass{
    public function EyesClass(){
        $userFields = array(
                'title',
                'totalcount',
                'totalpercent',
                'exponentpercent'
            );
        $userUpdatable = array();
        parent::AbstractTableClass('attr_eyes', $userFields, $userUpdatable);
    }

    protected function born(){
        return new EyesClass($this->table, $this->fields, $this->updatable);
    }
    public function selectByTitle($title){
        $this->selectByEscaped("title", "'".DBAccessClass::escape($title)."'");
    }
    
}