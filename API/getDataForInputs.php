<?

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// require_once($_SERVER['DOCUMENT_ROOT'] . "/classes/GeneratedFaceClass.php");
// require_once($_SERVER['DOCUMENT_ROOT'] . "/API/makeFaceJSONStructObj.php");
// require_once($_SERVER['DOCUMENT_ROOT'] . "/API/InputDataClass.php");

// function setArrInJSONObj(&$outputArr, $attributes)
//     {
//         foreach ($attributes as $key => $value) {
//             $outputArr[$value->get('title')] = $value->get('totalcount');
//         }
//     }

// function InputsDataStructToZero(&$data)
//     {
//         foreach ($data as $key => &$value) {
//             foreach ($value['elements'] as $key => &$attr) {
//                 $attr = 0;
//             }
//         }
//     }

// function makeInputDataStructFromData($faceInputParamData, $hatInputParamData, $backgroundInputParamData, $maskInputParamData, $jeweleryInputParamData, $eyesInputParamData, $lipsInputParamData, $shirtInputParamData)
// {
//     $inputsDataStruct = new InputsDataClass();

//     $paramArray = array(
//         'faceInputParam' => array(
//             "isSet" => false,
//             "getParams" => $faceInputParamData,
//             "idOfAttr" => NULL,
//             "whereClause" => ""
//         ),
//         'hatInputParam' => array(
//             "isSet" => false,
//             "getParams" => $hatInputParamData,
//             "idOfAttr" => NULL,
//             "whereClause" => ""
//         ),
//         'backgroundInputParam' => array(
//             "isSet" => false,
//             "getParams" => $backgroundInputParamData,
//             "idOfAttr" => NULL,
//             "whereClause" => ""
//         ),
//         'maskInputParam' => array(
//             "isSet" => false,
//             "getParams" => $maskInputParamData,
//             "idOfAttr" => NULL,
//             "whereClause" => ""
//         ),
//         'jeweleryInputParam' => array(
//             "isSet" => false,
//             "getParams" => $jeweleryInputParamData,
//             "idOfAttr" => NULL,
//             "whereClause" => ""
//         ),
//         'eyesInputParam' => array(
//             "isSet" => false,
//             "getParams" => $eyesInputParamData,
//             "idOfAttr" => NULL,
//             "whereClause" => ""
//         ),
//         'lipsInputParam' => array(
//             "isSet" => false,
//             "getParams" => $lipsInputParamData,
//             "idOfAttr" => NULL,
//             "whereClause" => ""
//         ),
//         'shirtInputParam' => array(
//             "isSet" => false,
//             "getParams" => $shirtInputParamData,
//             "idOfAttr" => NULL,
//             "whereClause" => ""
//         )
//     );

//     $f = false;
//     foreach ($paramArray as $key => $value) {
//         if ($value['getParams'][0] != "") {
//             $paramArray[$key]['isSet'] = true;
//             $f = true;
//         }
//     }

//     $faceAttrObj = new FaceClass();
//     $hatAttrObj = new HatClass();
//     $backgroundAttrObj = new BackgroundClass();
//     $maskAttrObj = new MaskClass();
//     $jeweleryAttrObj = new JeweleryClass();
//     $eyesAttrObj = new EyesClass();
//     $lipsAttrObj = new LipsClass();
//     $shirtAttrObj = new ShirtClass();

//     $faceInputAttrs = $faceAttrObj->selectArray();
//     $hatInputAttrs = $hatAttrObj->selectArray();
//     $backgroundInputAttrs = $backgroundAttrObj->selectArray();
//     $maskInputAttrs = $maskAttrObj->selectArray();
//     $jeweleryInputAttrs = $jeweleryAttrObj->selectArray();
//     $eyesInputAttrs = $eyesAttrObj->selectArray();
//     $lipsInputAttrs = $lipsAttrObj->selectArray();
//     $shirtInputAttrs = $shirtAttrObj->selectArray();

//     setArrInJSONObj($inputsDataStruct->faceInputParam['elements'],               $faceInputAttrs);
//     setArrInJSONObj($inputsDataStruct->hatInputParam['elements'],                 $hatInputAttrs);
//     setArrInJSONObj($inputsDataStruct->backgroundInputParam['elements'],   $backgroundInputAttrs);
//     setArrInJSONObj($inputsDataStruct->maskInputParam['elements'],               $maskInputAttrs);
//     setArrInJSONObj($inputsDataStruct->jeweleryInputParam['elements'],       $jeweleryInputAttrs);
//     setArrInJSONObj($inputsDataStruct->eyesInputParam['elements'],               $eyesInputAttrs);
//     setArrInJSONObj($inputsDataStruct->lipsInputParam['elements'],               $lipsInputAttrs);
//     setArrInJSONObj($inputsDataStruct->shirtInputParam['elements'],             $shirtInputAttrs);

//     if (!$f) {
//        return $inputsDataStruct;
//         die();
//     }
//     $sqlWhereCond = "";
//     foreach ($paramArray as $key => $value) {

//         if ($value['isSet'] == false) {
//             continue;
//         }

//         switch ($key) {
//             case 'faceInputParam':
//                 $faceAttr = new FaceClass();
//                 $faceAttr->selectByTitle($paramArray['faceInputParam']['getParams']);
//                 if (!$faceAttr->isDone()) {
//                     echo "error in selecting FaceAttr with title: " . $paramArray['faceInputParam']['getParams'] . "<br>";
//                     break;
//                 }
//                 $paramArray['faceInputParam']['idOfAttr'] = $faceAttr->get('id');
//                 $paramArray['faceInputParam']['whereClause'] = "`faceAttr_link` = " . $faceAttr->get('id');
//                 break;
//             case 'hatInputParam':
//                 $hatAttr = new HatClass();
//                 $hatAttr->selectByTitle($paramArray['hatInputParam']['getParams']);
//                 if (!$hatAttr->isDone()) {
//                     echo "error in selecting HatAttr with title: " . $paramArray['hatInputParam']['getParams'] . "<br>";
//                     break;
//                 }
//                 $paramArray['hatInputParam']['idOfAttr'] = $hatAttr->get('id');
//                 $paramArray['hatInputParam']['whereClause'] = "`hatAttr_link` = " . $hatAttr->get('id');
//                 break;
//             case 'backgroundInputParam':
//                 $backgroundAttr = new BackgroundClass();
//                 $backgroundAttr->selectByTitle($paramArray['backgroundInputParam']['getParams']);
//                 if (!$backgroundAttr->isDone()) {
//                     echo "error in selecting backgroundAttr with title: " . $paramArray['backgroundInputParam']['getParams'] . "<br>";
//                     break;
//                 }
//                 $paramArray['backgroundInputParam']['idOfAttr'] = $backgroundAttr->get('id');
//                 $paramArray['backgroundInputParam']['whereClause'] = "`backgroundAttr_link` = " . $backgroundAttr->get('id');
//                 break;
//             case 'maskInputParam':
//                 $maskAttr = new MaskClass();
//                 $maskAttr->selectByTitle($paramArray['maskInputParam']['getParams']);
//                 if (!$maskAttr->isDone()) {
//                     echo "error in selecting maskAttr with title: " . $paramArray['maskInputParam']['getParams'] . "<br>";
//                     break;
//                 }
//                 $paramArray['maskInputParam']['idOfAttr'] = $maskAttr->get('id');
//                 $paramArray['maskInputParam']['whereClause'] = "`maskAttr_link` = " . $maskAttr->get('id');
//                 break;
//             case 'jeweleryInputParam':
//                 $jeweleryAttr = new JeweleryClass();
//                 $jeweleryAttr->selectByTitle($paramArray['jeweleryInputParam']['getParams']);
//                 if (!$jeweleryAttr->isDone()) {
//                     echo "error in selecting jeweleryAttr with title: " . $paramArray['jeweleryInputParam']['getParams'] . "<br>";
//                     break;
//                 }
//                 $paramArray['jeweleryInputParam']['idOfAttr'] = $jeweleryAttr->get('id');
//                 $paramArray['jeweleryInputParam']['whereClause'] = "`jeweleryAttr_link` = " . $jeweleryAttr->get('id');
//                 break;
//             case 'eyesInputParam':
//                 $eyesAttr = new EyesClass();
//                 $eyesAttr->selectByTitle($paramArray['eyesInputParam']['getParams']);
//                 if (!$eyesAttr->isDone()) {
//                     echo "error in selecting eyesAttr with title: " . $paramArray['eyesInputParam']['getParams'] . "<br>";
//                     break;
//                 }
//                 $paramArray['eyesInputParam']['idOfAttr'] = $eyesAttr->get('id');
//                 $paramArray['eyesInputParam']['whereClause'] = "`eyesAttr_link` = " . $eyesAttr->get('id');
//                 break;
//             case 'lipsInputParam':
//                 $lipsAttr = new LipsClass();
//                 $lipsAttr->selectByTitle($paramArray['lipsInputParam']['getParams']);
//                 if (!$lipsAttr->isDone()) {
//                     echo "error in selecting lipsAttr with title: " . $paramArray['lipsInputParam']['getParams'] . "<br>";
//                     break;
//                 }
//                 $paramArray['lipsInputParam']['idOfAttr'] = $lipsAttr->get('id');
//                 $paramArray['lipsInputParam']['whereClause'] = "`lipsAttr_link` = " . $lipsAttr->get('id');
//                 break;
//             case 'shirtInputParam':
//                 $shirtAttr = new ShirtClass();
//                 $shirtAttr->selectByTitle($paramArray['shirtInputParam']['getParams']);
//                 if (!$shirtAttr->isDone()) {
//                     echo "error in selecting shirtAttr with title: " . $paramArray['shirtInputParam']['getParams'] . "<br>";
//                     break;
//                 }
//                 $paramArray['shirtInputParam']['idOfAttr'] = $shirtAttr->get('id');
//                 $paramArray['shirtInputParam']['whereClause'] = "`shirtAttr_link` = " . $shirtAttr->get('id');
//                 break;
//         }
//     }

//     $flag = false;
//     foreach ($paramArray as $key => $value) {
//         if ($value['isSet'] == false) {
//             continue;
//         }
//         if (!$flag) {
//             $sqlWhereCond = $value['whereClause'];
//             $flag = true;
//             continue;
//         }
//         $sqlWhereCond .= " AND " . $value['whereClause'];
//     }

//     $generatedFaceObj = new GeneratedFaceClass();
//     InputsDataStructToZero($inputsDataStruct);

//     $returnArray = $generatedFaceObj->selectArray($sqlWhereCond);
//     foreach ($returnArray as $key => $face) {
//         $faceAttrObj = new FaceClass();
//         $hatAttrObj = new HatClass();
//         $backgroundAttrObj = new BackgroundClass();
//         $maskAttrObj = new MaskClass();
//         $jeweleryAttrObj = new JeweleryClass();
//         $eyesAttrObj = new EyesClass();
//         $lipsAttrObj = new LipsClass();
//         $shirtAttrObj = new ShirtClass();

//         $faceAttrObj->selectById($face->get('faceAttr_link'));
//         $hatAttrObj->selectById($face->get('hatAttr_link'));
//         $backgroundAttrObj->selectById($face->get('backgroundAttr_link'));
//         $maskAttrObj->selectById($face->get('maskAttr_link'));
//         $jeweleryAttrObj->selectById($face->get('jeweleryAttr_link'));
//         $eyesAttrObj->selectById($face->get('eyesAttr_link'));
//         $lipsAttrObj->selectById($face->get('lipsAttr_link'));
//         $shirtAttrObj->selectById($face->get('shirtAttr_link'));

//         //$inputsDataStruct -> faceInputParam['elements'][$faceAttrObj->get('title')] += 1;
//         $inputsDataStruct->faceInputParam['elements'][$faceAttrObj->get('title')] += 1;
//         $inputsDataStruct->hatInputParam['elements'][$hatAttrObj->get('title')] += 1;
//         $inputsDataStruct->backgroundInputParam['elements'][$backgroundAttrObj->get('title')] += 1;
//         $inputsDataStruct->maskInputParam['elements'][$maskAttrObj->get('title')] += 1;
//         $inputsDataStruct->jeweleryInputParam['elements'][$jeweleryAttrObj->get('title')] += 1;
//         $inputsDataStruct->eyesInputParam['elements'][$eyesAttrObj->get('title')] += 1;
//         $inputsDataStruct->lipsInputParam['elements'][$lipsAttrObj->get('title')] += 1;
//         $inputsDataStruct->shirtInputParam['elements'][$shirtAttrObj->get('title')] += 1;
//     }
//     return $inputsDataStruct;
// }

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

header('Access-Control-Allow-Origin: *');
require_once($_SERVER['DOCUMENT_ROOT'] . "/classes/GeneratedFaceClass.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/API/FaceJSONStruct.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/API/makeFaceJSONStructObj.php");
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
                    $queryString = " `shirtAttr_link` = " . $value->get('id');
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
        return $inputsDataStruct;
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