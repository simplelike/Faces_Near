<?

require_once($_SERVER['DOCUMENT_ROOT']."/classes/BackgroundClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/EyesClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/FaceClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/HatClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/JeweleryClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/LipsClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/MaskClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/ShirtClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/GeneratedFaceClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/API/FaceJSONStruct.php");

function makeFaceJSONStructObj($face) {

    $faceJsonObj = new FaceJSONStruct();

    $faceAttr           = $face->getFace();
    $hatAttr            = $face->getHat();
    $backgroundAttr     = $face->getBackground();
    $maskAttr           = $face->getMask();
    $jeweleryAttr       = $face->getJewelery();
    $eyesAttr           = $face->getEyes();
    $lipsAttr           = $face->getLips();
    $shirtAttr          = $face->getShirt();


    $faceJsonObj -> faceAttr['faceAttrTitle'] = $faceAttr->get('title');
    $faceJsonObj -> faceAttr['faceAttrTotalCount'] = $faceAttr->get('totalcount');
    $faceJsonObj -> faceAttr['faceAttrTotalPercent'] = $faceAttr->get('totalpercent');
    $faceJsonObj -> faceAttr['faceAttrExponentPercent'] = $faceAttr->get('exponentpercent');

    $faceJsonObj -> hatAttr['hatAttrTitle'] = $hatAttr->get('title');
    $faceJsonObj -> hatAttr['hatAttrTotalCount'] = $hatAttr->get('totalcount');
    $faceJsonObj -> hatAttr['hatAttrTotalPercent'] = $hatAttr->get('totalpercent');
    $faceJsonObj -> hatAttr['hatAttrExponentPercent'] = $hatAttr->get('exponentpercent');

    $faceJsonObj -> backgroundAttr['backgroundAttrTitle'] = $backgroundAttr->get('title');
    $faceJsonObj -> backgroundAttr['backgroundAttrTotalCount'] = $backgroundAttr->get('totalcount');
    $faceJsonObj -> backgroundAttr['backgroundAttrTotalPercent'] = $backgroundAttr->get('totalpercent');
    $faceJsonObj -> backgroundAttr['backgroundAttrExponentPercent'] = $backgroundAttr->get('exponentpercent');

    $faceJsonObj -> maskAttr['maskAttrTitle'] = $maskAttr->get('title');
    $faceJsonObj -> maskAttr['maskAttrTotalCount'] = $maskAttr->get('totalcount');
    $faceJsonObj -> maskAttr['maskAttrTotalPercent'] = $maskAttr->get('totalpercent');
    $faceJsonObj -> maskAttr['maskAttrExponentPercent'] = $maskAttr->get('exponentpercent');

    $faceJsonObj -> jeweleryAttr['jeweleryAttrTitle']           = $jeweleryAttr->get('title');
    $faceJsonObj -> jeweleryAttr['jeweleryAttrTotalCount']      = $jeweleryAttr->get('totalcount');
    $faceJsonObj -> jeweleryAttr['jeweleryAttrTotalPercent']    = $jeweleryAttr->get('totalpercent');
    $faceJsonObj -> jeweleryAttr['jeweleryAttrExponentPercent'] = $jeweleryAttr->get('exponentpercent');

    $faceJsonObj -> eyesAttr['eyesAttrTitle']           = $eyesAttr->get('title');
    $faceJsonObj -> eyesAttr['eyesAttrTotalCount']      = $eyesAttr->get('totalcount');
    $faceJsonObj -> eyesAttr['eyesAttrTotalPercent']    = $eyesAttr->get('totalpercent');
    $faceJsonObj -> eyesAttr['eyesAttrExponentPercent'] = $eyesAttr->get('exponentpercent');

    $faceJsonObj -> lipsAttr['lipsAttrTitle']           = $lipsAttr->get('title');
    $faceJsonObj -> lipsAttr['lipsAttrTotalCount']      = $lipsAttr->get('totalcount');
    $faceJsonObj -> lipsAttr['lipsAttrTotalPercent']    = $lipsAttr->get('totalpercent');
    $faceJsonObj -> lipsAttr['lipsAttrExponentPercent'] = $lipsAttr->get('exponentpercent');

    $faceJsonObj -> shirtAttr['shirtAttrTitle']           = $shirtAttr->get('title');
    $faceJsonObj -> shirtAttr['shirtAttrTotalCount']      = $shirtAttr->get('totalcount');
    $faceJsonObj -> shirtAttr['shirtAttrTotalPercent']    = $shirtAttr->get('totalpercent');
    $faceJsonObj -> shirtAttr['shirtAttrExponentPercent'] = $shirtAttr->get('exponentpercent');

    $faceJsonObj -> hash = $face->getHash();

    $faceJsonObj -> smallPreviewImg = $face ->get('smallPreview');
    $faceJsonObj -> midPreviewImg = $face ->get('midPreview');
    $faceJsonObj -> maxPreviewImg = $face ->get('maxPreview');
    $faceJsonObj -> id = $face -> get('id');
    $faceJsonObj -> number = $face -> get('number');
    $faceJsonObj -> rarity = $face -> get('rarity');

    return $faceJsonObj;
}