<?

class FaceJSONStruct implements \JsonSerializable{

    public $hash = "";
    public $id = "";
    public $rarity = "";
    public $number = ""; 
    public $faceAttr = array(
        "faceAttrTitle" => "",
        "faceAttrTotalCount" => "",
        "faceAttrTotalPercent" => "",
        "faceAttrExponentPercent" => ""
    );
    public $hatAttr = array(
        "hatAttrTitle" => "",
        "hatAttrTotalCount" => "",
        "hatAttrTotalPercent" => "",
        "hatAttrExponentPercent" => ""
    );
    public $backgroundAttr = array(
        "backgroundAttrTitle" => "",
        "backgroundAttrTotalCount" => "",
        "backgroundAttrTotalPercent" => "",
        "backgroundAttrExponentPercent" => ""
    );
    public $maskAttr = array(
        "maskAttrTitle" => "",
        "maskAttrTotalCount" => "",
        "maskAttrTotalPercent" => "",
        "maskAttrExponentPercent" => ""
    );
    public $jeweleryAttr = array(
        "jeweleryAttrTitle" => "",
        "jeweleryAttrTotalCount" => "",
        "jeweleryAttrTotalPercent" => "",
        "jeweleryAttrExponentPercent" => ""
    );
    public $lipsAttr = array(
        "lipsAttrTitle" => "",
        "lipsAttrTotalCount" => "",
        "lipsAttrTotalPercent" => "",
        "lipsAttrExponentPercent" => ""
    );
    public $eyeseAttr = array(
        "eyeseAttrTitle" => "",
        "eyeseAttrTotalCount" => "",
        "eyeseAttrTotalPercent" => "",
        "eyeseAttrExponentPercent" => ""
    );
    public $shirtAttr = array(
        "shirtAttrTitle" => "",
        "shirtAttrTotalCount" => "",
        "shirtAttrTotalPercent" => "",
        "shirtAttrExponentPercent" => ""
    );

    public $smallPreviewImg = "";
    public $midPreviewImg = "";
    public $maxPreviewImg = "";

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
    
    public $doesDataPresent = true;

}