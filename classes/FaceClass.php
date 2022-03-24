<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/abstractTableClass.php');

class FaceClass extends AbstractTableClass{
    public function FaceClass(){
        $userFields = array(
                'title',
                'totalcount',
                'totalpercent',
                'exponentpercent'
            );
        $userUpdatable = array();
        parent::AbstractTableClass('attr_face', $userFields, $userUpdatable);
    }

    protected function born(){
        return new FaceClass($this->table, $this->fields, $this->updatable);
    }
    public function selectByTitle($title){
        $dbAcces = new DBAccessClass();
        $this->selectByEscaped("title", "'".$dbAcces -> escape($title)."'");
    }

}