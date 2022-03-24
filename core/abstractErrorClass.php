<?php

class AbstractErrorClass{
    private $lastError = 'Done';
    public function getLastError(){
        return $this->lastError;
    }
    public function isDone(){
        return $this->lastError == 'Done';
    }
    protected function error($message){
        $this->lastError = get_class($this).' -> '.$message;
    }
    protected function done(){
        $this->lastError = 'Done';
    }
    protected function forwardError($object){
        if (!$object->isDone()){
            $this->error($object->getLastError());
            return true;
        }
        return false;
    }
    protected function checkIt(){
        if (!$this->isDone()) {
            die(get_class($this)." is in error state");
        }
    }
}
