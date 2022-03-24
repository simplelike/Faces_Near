<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once($_SERVER['DOCUMENT_ROOT']."/classes/BackgroundClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/EyesClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/FaceClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/HatClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/JeweleryClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/LipsClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/MaskClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/classes/ShirtClass.php");

$file = fopen($_SERVER['DOCUMENT_ROOT']."/scripts/attrDataToUpload.csv", "r");
if ($file) {
    print "starting...<br>";
    $lineNumber = 0;
    while (($buffer = fgets($file, 4096)) !== false) {
        $lineNumber++;
        if ($lineNumber == 1){
            continue;
        }
        $buffer = iconv("CP1251", "UTF-8", $buffer);
        $buffer = str_replace(array("\n", "\r"), '', $buffer);
        list($titleFull,$totalcount,$totalpercent,$exponentpercent) = explode(";", $buffer);
        if  (   $titleFull == "" ||
            $totalcount == "" ||
            $totalpercent == "" ||
            $exponentpercent == "" 
        )
        {
            echo $lineNumber.") ".$buffer.": empty field<br>";
            //errorMessage($buffer, "empty field");
            continue;  
        }
        $tempArray = explode("/", $titleFull);
        $dirNum = $tempArray[count($tempArray)- 2];
        $titleAttr = $tempArray[count($tempArray) - 1];

        $classObj;
        switch ($dirNum) {
            case 1:
                $classObj = new FaceClass();
                break;
            case 2:
                $classObj = new HatClass();
                break;
            case 3:
                $classObj = new BackgroundClass();
                break;
            case 4:
                $classObj = new MaskClass();
                break;
            case 5:
                $classObj = new JeweleryClass();
                break;
            case 6:
                $classObj = new EyesClass();
                break;
            case 7:
                $classObj = new LipsClass();
                break;
            case 8:
                $classObj = new ShirtClass();
                break;
        }

        $values = array();
        $values["title"]            = $titleAttr;
        $values["totalcount"]       = $totalcount;
        $values["totalpercent"]     = $totalpercent;
        $values["exponentpercent"]  = $exponentpercent;
        $classObj -> insert($values, false);
        
        if (!$classObj->isDone()){
            echo $lineNumber.") ".$buffer.": ".$classObj->getLastError()."<br>";
            continue;  
        } else {
            echo $lineNumber.") ".$buffer.", new faculty was inserted<br>";
        }
    }
    if (!feof($file)) {
        echo "Error: unexpected fgets() fail<br>";
        //$errorMessage("file: foruploading/РІСЃРµСЃС‚СѓРґРµРЅС‚С‹.csv", 'Error: unexpected fgets() fail');
    }
    fclose($file);
}
else {
	echo "file doesn't not exists<br>";
}