<?
header('Access-Control-Allow-Origin: *');
$faceId = $_GET['id'];


require_once($_SERVER['DOCUMENT_ROOT']."/classes/GeneratedFaceClass.php");

require_once($_SERVER['DOCUMENT_ROOT']."/API/FaceJSONStruct.php");
require_once($_SERVER['DOCUMENT_ROOT']."/API/makeFaceJSONStructObj.php");

$face = new GeneratedFaceClass();
 

$face->selectById($faceId);
if (!$face->isDone()){
    $faceJsonObj -> doesDataPresent = false;
    echo json_encode($faceJsonObj,JSON_UNESCAPED_UNICODE);
    die;
}

$faceJsonObj = makeFaceJSONStructObj($face);

echo json_encode($faceJsonObj,JSON_UNESCAPED_UNICODE);