<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/abstractTableClass.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/BackgroundClass.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/EyesClass.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/FaceClass.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/HatClass.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/JeweleryClass.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/LipsClass.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/MaskClass.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/ShirtClass.php');

class GeneratedFaceClass extends AbstractTableClass{
    public function GeneratedFaceClass(){
        $userFields = array(
                'generatedPic_hash',
                'title',
                'faceAttr_link',
                'hatAttr_link',
                'backgroundAttr_link',
                'maskAttr_link',
                'jeweleryAttr_link',
                'eyesAttr_link',
                'lipsAttr_link',
                'shirtAttr_link',
                'smallPreview',
                'midPreview',
                'maxPreview',
                'rarity',
                'number'

            );
        $userUpdatable = array('generatedPic_hash');
        parent::AbstractTableClass('generated_faces', $userFields, $userUpdatable);
    }

    protected function born(){
        return new GeneratedFaceClass($this->table, $this->fields, $this->updatable);
    }

    public function selectByHash($hash){
        $this->selectByEscaped("generatedPic_hash", "'".DBAccessClass::escape($hash)."'");
    }
    public function selectByTitle($title){
        $this->selectByEscaped("title", "'".DBAccessClass::escape($title)."'");
    }

    public function getHash(){
        return $this->get('generatedPic_hash');
    }

    public function getFace() {
        $this ->checkIt();
        if (!$this->fields['id']){
            die ('For getting face it`s neccessary to select ');
        }
        $face = new FaceClass();
        $face->selectById($this->get('faceAttr_link'));
        return $face;
    }
    public function getHat() {
        $this ->checkIt();
        if (!$this->fields['id']){
            die ('For getting hat it`s neccessary to select ');
        }
        $hat = new HatClass();
        $hat->selectById($this->get('hatAttr_link'));
        return $hat;
    }
    public function getBackground() {
        $this ->checkIt();
        if (!$this->fields['id']){
            die ('For getting background it`s neccessary to select ');
        }
        $background = new BackgroundClass();
        $background->selectById($this->get('backgroundAttr_link'));
        return $background;
    }
    public function getMask() {
        $this ->checkIt();
        if (!$this->fields['id']){
            die ('For getting Mask it`s neccessary to select ');
        }
        $Mask = new MaskClass();
        $Mask->selectById($this->get('maskAttr_link'));
        return $Mask;
    }
    public function getJewelery() {
        $this ->checkIt();
        if (!$this->fields['id']){
            die ('For getting jewelery it`s neccessary to select ');
        }
        $jewelery = new JeweleryClass();
        $jewelery->selectById($this->get('jeweleryAttr_link'));
        return $jewelery;
    }
    public function getEyes() {
        $this ->checkIt();
        if (!$this->fields['id']){
            die ('For getting eyes it`s neccessary to select ');
        }
        $eyes = new EyesClass();
        $eyes->selectById($this->get('eyesAttr_link'));
        return $eyes;
    }
    public function getLips() {
        $this ->checkIt();
        if (!$this->fields['id']){
            die ('For getting lips it`s neccessary to select ');
        }
        $lips = new LipsClass();
        $lips->selectById($this->get('lipsAttr_link'));
        return $lips;
    }
    public function getShirt() {
        $this ->checkIt();
        if (!$this->fields['id']){
            die ('For getting shirt it`s neccessary to select ');
        }
        $shirt = new ShirtClass();
        $shirt->selectById($this->get('shirtAttr_link'));
        return $shirt;
    }

    
}