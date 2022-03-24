<?

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
header('Access-Control-Allow-Origin: *');
require_once($_SERVER['DOCUMENT_ROOT'] . "/classes/GeneratedFaceClass.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/API/FaceJSONStruct.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/API/makeFaceJSONStructObj.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/API/getDataForInputs.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/API/InputDataClass.php");

function makeQueriesFor($objectArray, &$output, $type, $flag)
{

    $generatedFaceClassObj = new GeneratedFaceClass();
    foreach ($objectArray as $key => $value) {
        switch ($type) {
            case 'face':
                $queryString = $output->faceInputParam["query"][$value->get('id')];
                if (!$flag) {
                    $queryString = " `faceAttr_link` = " . $value->get('id');
                    $output->faceInputParam["query"][$value->get('id')] = $queryString;
                }
                $count = $generatedFaceClassObj->getArraySize($queryString);
                $output->faceInputParam['elements'][$value->get('title') . "$$$" . $value->get('id')] = $count;
                break;
            case 'hat':
                $queryString = $output->hatInputParam['query'][$value->get('id')];
                if (!$flag) {
                    $queryString = " `hatAttr_link` = " . $value->get('id');
                    $output->hatInputParam['query'][$value->get('id')] = $queryString;
                }
                $count = $generatedFaceClassObj->getArraySize($queryString);
                $output->hatInputParam['elements'][$value->get('title') . "$$$" . $value->get('id')] = $count;
                break;
            case 'background':
                $queryString =    $output->backgroundInputParam['query'][$value->get('id')];
                if (!$flag) {
                    $queryString = " `backgroundAttr_link` = " . $value->get('id');
                    $output->backgroundInputParam['query'][$value->get('id')] = $queryString;
                }
                $count = $generatedFaceClassObj->getArraySize($queryString);
                $output->backgroundInputParam['elements'][$value->get('title') . "$$$" . $value->get('id')] = $count;
                break;
            case 'mask':
                $queryString = $output->maskInputParam['query'][$value->get('id')];
                if (!$flag) {
                    $queryString = " `maskAttr_link` = " . $value->get('id');
                    $output->maskInputParam['query'][$value->get('id')] = $queryString;
                }
                $count = $generatedFaceClassObj->getArraySize($queryString);
                $output->maskInputParam['elements'][$value->get('title') . "$$$" . $value->get('id')] = $count;
                break;
            case 'jewelery':
                $queryString = $output->jeweleryInputParam['query'][$value->get('id')];
                if (!$flag) {
                    $queryString = " `jeweleryAttr_link` = " . $value->get('id');
                    $output->jeweleryInputParam['query'][$value->get('id')] = $queryString;
                }
                $count = $generatedFaceClassObj->getArraySize($queryString);
                $output->jeweleryInputParam['elements'][$value->get('title') . "$$$" . $value->get('id')] = $count;
                break;
            case 'eyes':
                $queryString = $output->eyesInputParam['query'][$value->get('id')];
                if (!$flag) {
                    $queryString = " `eyesAttr_link` = " . $value->get('id');
                    $output->eyesInputParam['query'][$value->get('id')] = $queryString;
                }
                $count = $generatedFaceClassObj->getArraySize($queryString);
                $output->eyesInputParam['elements'][$value->get('title') . "$$$" . $value->get('id')] = $count;
                break;
            case 'lips':
                $queryString = $output->lipsInputParam['query'][$value->get('id')];
                if (!$flag) {
                    $queryString = " `lipsAttr_link` = " . $value->get('id');
                    $output->lipsInputParam['query'][$value->get('id')] = $queryString;
                }
                $count = $generatedFaceClassObj->getArraySize($queryString);
                $output->lipsInputParam['elements'][$value->get('title') . "$$$" . $value->get('id')] = $count;
                break;
            case 'shirt':
                $queryString = $output->shirtInputParam['query'][$value->get('id')];
                if (!$flag) {
                    $queryString = " `faceAttr_link` = " . $value->get('id');
                    $output->shirtInputParam['query'][$value->get('id')] = $queryString;
                }
                $count = $generatedFaceClassObj->getArraySize($queryString);
                $output->shirtInputParam['elements'][$value->get('title') . "$$$" . $value->get('id')] = $count;
                break;
        }
    }
}


function makeInputDataStructFromData($_faceInputParam, $_hatInputParam, $_backgroundInputParam, $_maskInputParam, $_jeweleryInputParam, $_eyesInputParam, $_lipsInputParam, $_shirtInputParam)
{
    $faceInputParam         =  $_faceInputParam;
    $hatInputParam          =  $_hatInputParam;
    $backgroundInputParam   =  $_backgroundInputParam;
    $maskInputParam         =  $_maskInputParam;
    $jeweleryInputParam     =  $_jeweleryInputParam;
    $eyesInputParam         =  $_eyesInputParam;
    $lipsInputParam         =  $_lipsInputParam;
    $shirtInputParam        =  $_shirtInputParam;

    $faceAttrObj = new FaceClass();
    $hatAttrObj = new HatClass();
    $backgroundAttrObj = new BackgroundClass();
    $maskAttrObj = new MaskClass();
    $jeweleryAttrObj = new JeweleryClass();
    $eyesAttrObj = new EyesClass();
    $lipsAttrObj = new LipsClass();
    $shirtAttrObj = new ShirtClass();

    $faceInputAttrs = $faceAttrObj->selectArray();
    $hatInputAttrs = $hatAttrObj->selectArray();
    $backgroundInputAttrs = $backgroundAttrObj->selectArray();
    $maskInputAttrs = $maskAttrObj->selectArray();
    $jeweleryInputAttrs = $jeweleryAttrObj->selectArray();
    $eyesInputAttrs = $eyesAttrObj->selectArray();
    $lipsInputAttrs = $lipsAttrObj->selectArray();
    $shirtInputAttrs = $shirtAttrObj->selectArray();


    $inputsDataStruct = new InputsDataClass();

    makeQueriesFor($faceInputAttrs, $inputsDataStruct, "face", false);
    makeQueriesFor($hatInputAttrs, $inputsDataStruct, "hat", false);
    makeQueriesFor($backgroundInputAttrs, $inputsDataStruct, "background", false);
    makeQueriesFor($maskInputAttrs, $inputsDataStruct, "mask", false);
    makeQueriesFor($jeweleryInputAttrs, $inputsDataStruct, "jewelery", false);
    makeQueriesFor($eyesInputAttrs, $inputsDataStruct, "eyes", false);
    makeQueriesFor($lipsInputAttrs, $inputsDataStruct, "lips", false);
    makeQueriesFor($shirtInputAttrs, $inputsDataStruct, "shirt", false);


    $paramArray = array(
        'faceInputParam' => array(
            "isSet" => false,
            "getParam" => $faceInputParam
        ),
        'hatInputParam' => array(
            "isSet" => false,
            "getParam" => $hatInputParam
        ),
        'backgroundInputParam' => array(
            "isSet" => false,
            "getParam" => $backgroundInputParam
        ),
        'maskInputParam' => array(
            "isSet" => false,
            "getParam" => $maskInputParam
        ),
        'jeweleryInputParam' => array(
            "isSet" => false,
            "getParam" => $jeweleryInputParam
        ),
        'eyesInputParam' => array(
            "isSet" => false,
            "getParam" => $eyesInputParam
        ),
        'lipsInputParam' => array(
            "isSet" => false,
            "getParam" => $lipsInputParam
        ),
        'shirtInputParam' => array(
            "isSet" => false,
            "getParam" => $shirtInputParam
        )
    );

    $f = false;
    foreach ($paramArray as $key => $value) {
        if ($value['getParam'][0] != "") {
            $paramArray[$key]['isSet'] = true;
            $f = true;
        }
    }

    if (!$f) {

        die();
    }
    $returnArray  = get_object_vars($inputsDataStruct);
    foreach ($paramArray as $key => $value) {
        if ($value['isSet'] == false) {
            continue;
        }
        foreach ($returnArray as $key1 => &$value1) {
            if ($key == $key1) continue;
            foreach ($value1['query'] as $key2 => &$value2) {
                $str = str_replace("InputParam", "Attr_link", $key);
                $value2 .= " AND `" . $str . "` = " . $value["getParam"];
            }
        }
    }

    foreach ($returnArray as $key => $value) {
        $inputsDataStruct->$key = $value;
    }

    makeQueriesFor($faceInputAttrs, $inputsDataStruct, "face", true);
    makeQueriesFor($hatInputAttrs, $inputsDataStruct, "hat", true);
    makeQueriesFor($backgroundInputAttrs, $inputsDataStruct, "background", true);
    makeQueriesFor($maskInputAttrs, $inputsDataStruct, "mask", true);
    makeQueriesFor($jeweleryInputAttrs, $inputsDataStruct, "jewelery", true);
    makeQueriesFor($eyesInputAttrs, $inputsDataStruct, "eyes", true);
    makeQueriesFor($lipsInputAttrs, $inputsDataStruct, "lips", true);
    makeQueriesFor($shirtInputAttrs, $inputsDataStruct, "shirt", true);

    return $inputsDataStruct;
} 



//var_dump($inputsDataStruct);
//echo json_encode($inputsDataStruct,JSON_UNESCAPED_UNICODE);