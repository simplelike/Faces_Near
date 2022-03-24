<?

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
header('Access-Control-Allow-Origin: *');

$startIndex = $_GET['start'];
$endIndex = $_GET['end'];
require_once($_SERVER['DOCUMENT_ROOT']."/classes/GeneratedFaceClass.php");

require_once($_SERVER['DOCUMENT_ROOT']."/API/FaceJSONStruct.php");
require_once($_SERVER['DOCUMENT_ROOT']."/API/makeFaceJSONStructObj.php");
require_once($_SERVER['DOCUMENT_ROOT']."/API/getDataForInputs.php");

class FacesJSONStruct implements \JsonSerializable {
    public $faces = array();
    public $inputs;

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
$facesObj = new FacesJSONStruct();
$face = new GeneratedFaceClass();

$where = array(
    "faceAttr_link" => "",
    "hatAttr_link" => "",
    "backgroundAttr_link" => "",
    "maskAttr_link" => "",
    "jeweleryAttr_link" => "",
    "eyesAttr_link" => "",
    "lipsAttr_link" => "",
    "shirtAttr_link" => "",
);

$where["faceAttr_link"]         = $_GET['faceInputParam']       == "" ? "" :  "`faceAttr_link` = ".$_GET['faceInputParam'];
$where["hatAttr_link"]          = $_GET['hatInputParam']        == "" ? "" :  "`hatAttr_link` = ".$_GET['hatInputParam'];
$where["backgroundAttr_link"]   = $_GET['backgroundInputParam'] == "" ? "" :  "`backgroundAttr_link` = ".$_GET['backgroundInputParam'];
$where["maskAttr_link"]         = $_GET['maskInputParam']       == "" ? "" :  "`maskAttr_link` = ".$_GET['maskInputParam'];
$where["jeweleryAttr_link"]     = $_GET['jeweleryInputParam']   == "" ? "" :  "`jeweleryAttr_link` = ".$_GET['jeweleryInputParam'];
$where["eyesAttr_link"]         = $_GET['eyesInputParam']       == "" ? "" :  "`eyesAttr_link` = ".$_GET['eyesInputParam'];
$where["lipsAttr_link"]         = $_GET['lipsInputParam']       == "" ? "" :  "`lipsAttr_link` = ".$_GET['lipsInputParam'];
$where["shirtAttr_link"]        = $_GET['shirtInputParam']      == "" ? "" :  "`shirtAttr_link` = ".$_GET['shirtInputParam'];



$whereStr = implode(" AND ", array_filter($where));


$tempArray = $face -> selectArray($whereStr, ' `number` LIMIT '.$startIndex.','.$endIndex);
$facesObj -> inputs = makeInputDataStructFromData($_GET['faceInputParam'], $_GET['hatInputParam'], $_GET['backgroundInputParam'], $_GET['maskInputParam'], $_GET['jeweleryInputParam'], $_GET['eyesInputParam'], $_GET['lipsInputParam'], $_GET['shirtInputParamData']);
//var_dump($facesObj -> inputs);
foreach ($tempArray as $key => $face) {

    $faceJsonObj = makeFaceJSONStructObj($face);
    array_push($facesObj -> faces, $faceJsonObj);
    
}

echo json_encode($facesObj,JSON_UNESCAPED_UNICODE);
