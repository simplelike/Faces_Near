<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/abstractTableClass.php');

class BackgroundClass extends AbstractTableClass{
    public function BackgroundClass(){
        $userFields = array(
                'title',
                'totalcount',
                'totalpercent',
                'exponentpercent'
            );
        $userUpdatable = array();
        parent::AbstractTableClass('attr_background', $userFields, $userUpdatable);
    }

    protected function born(){
        return new BackgroundClass($this->table, $this->fields, $this->updatable);
    }
    public function selectByTitle($title){
        $this->selectByEscaped("title", "'".DBAccessClass::escape($title)."'");
    }
    
}