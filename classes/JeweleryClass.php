<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/abstractTableClass.php');

class JeweleryClass extends AbstractTableClass{
    public function JeweleryClass(){
        $userFields = array(
                'title',
                'totalcount',
                'totalpercent',
                'exponentpercent'
            );
        $userUpdatable = array();
        parent::AbstractTableClass('attr_jewelery', $userFields, $userUpdatable);
    }

    protected function born(){
        return new JeweleryClass($this->table, $this->fields, $this->updatable);
    }
    public function selectByTitle($title){
        $this->selectByEscaped("title", "'".DBAccessClass::escape($title)."'");
    }
    
}