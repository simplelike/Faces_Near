<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/abstractTableClass.php');

class MaskClass extends AbstractTableClass{
    public function MaskClass(){
        $userFields = array(
                'title',
                'totalcount',
                'totalpercent',
                'exponentpercent'
            );
        $userUpdatable = array();
        parent::AbstractTableClass('attr_mask', $userFields, $userUpdatable);
    }

    protected function born(){
        return new MaskClass($this->table, $this->fields, $this->updatable);
    }
    public function selectByTitle($title){
        $this->selectByEscaped("title", "'".DBAccessClass::escape($title)."'");
    }
}