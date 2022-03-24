<?

class InputsDataClass implements \JsonSerializable{

    public $faceInputParam = array(
        "elements" => array(),
        "query" => array()
    );
    public $hatInputParam = array(
        "elements" => array(),
        "query" => array()
    );
    public $backgroundInputParam = array(
        "elements" => array(),
        "query" => array()
    );
    public $maskInputParam = array(
        "elements" => array(),
        "query" => array()
    );
    public $jeweleryInputParam = array(
        "elements" => array(),
        "query" => array()
    );
    public $eyesInputParam = array(
        "elements" => array(),
        "query" => array()
    );
    public $lipsInputParam = array(
        "elements" => array(),
        "query" => array()
    );
    public $shirtInputParam = array(
        "elements" => array(),
        "query" => array()
    );

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}