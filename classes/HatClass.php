<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/abstractTableClass.php');

class HatClass extends AbstractTableClass{
    public function HatClass(){
        $userFields = array(
                'title',
                'totalcount',
                'totalpercent',
                'exponentpercent'
            );
        $userUpdatable = array();
        parent::AbstractTableClass('attr_hat', $userFields, $userUpdatable);
    }

    protected function born(){
        return new HatClass($this->table, $this->fields, $this->updatable);
    }

    public function selectByTitle($title){
        $this->selectByEscaped("title", "'".DBAccessClass::escape($title)."'");
    }
    
}