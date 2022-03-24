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
require_once($_SERVER['DOCUMENT_ROOT']."/classes/GeneratedFaceClass.php");

$file = fopen($_SERVER['DOCUMENT_ROOT']."/scripts/dataForMySql.csv", "r");

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
        list($genImgPath,$faceAttrPath,$hatAttrPath,$backgroundAttrPath, $maskAttrPath, $jeweleryAttrPath, $eyesAttrPath, $lipsAttrPath, $shirtAttrPath, $rarity) = explode(";", $buffer);

        if  (   $genImgPath == "" ||
                $faceAttrPath == "" ||
                $hatAttrPath == "" ||
                $backgroundAttrPath == "" ||
                $maskAttrPath == "" ||
                $jeweleryAttrPath == "" ||
                $eyesAttrPath == "" ||
                $lipsAttrPath == "" ||
                $shirtAttrPath == "" ||
                $rarity == ""
            )
        {
            echo $lineNumber.") ".$buffer.": empty field<br>";
            //errorMessage($buffer, "empty field");
            continue;  
        }
       // echo $faceAttrPath;
        $genImgTitle         = mb_substr($genImgPath, mb_strripos($genImgPath,"/")+1, NULL);
        $faceAttrTitle       = mb_substr($faceAttrPath, mb_strripos($faceAttrPath,"/")+1, NULL);
        $hatAttrTitle        = mb_substr($hatAttrPath, mb_strripos($hatAttrPath,"/")+1, NULL, "UTF-8");
        $backgroundAttrTitle = mb_substr($backgroundAttrPath, mb_strripos($backgroundAttrPath,"/")+1, NULL);
        $maskAttrTitle       = mb_substr($maskAttrPath, mb_strripos($maskAttrPath,"/")+1, NULL);
        $jeweleryAttrTitle   = mb_substr($jeweleryAttrPath, mb_strripos($jeweleryAttrPath,"/")+1, NULL);
        $eyesAttrTitle       = mb_substr($eyesAttrPath, mb_strripos($eyesAttrPath,"/")+1, NULL);
        $lipsAttrTitle       = mb_substr($lipsAttrPath, mb_strripos($lipsAttrPath,"/")+1, NULL);
        $shirtAttrTitle      = mb_substr($shirtAttrPath, mb_strripos($shirtAttrPath,"/")+1, NULL);
        //echo $genImgTitle.";".$faceAttrTitle.";".$hatAttrTitle.";".$backgroundAttrTitle.";".$maskAttrTitle.";".$jeweleryAttrTitle.";".$eyesTitle.";".$lipsAttrTitle.";".$shirtAttrTitle."<br>";

        $number = str_replace(".png", "", $genImgTitle);
        
        $faceAttr = new FaceClass();
        $faceAttr->selectByTitle($faceAttrTitle);
        if (!$faceAttr->isDone()) {
            echo $lineNumber.") ".$buffer.": There is no such faceAttr <br>";
            continue;
        }

        $hatAttr = new HatClass();
        $hatAttr->selectByTitle($hatAttrTitle);
        if (!$faceAttr->isDone()) {
            echo $lineNumber.") ".$buffer.": There is no such hatAttr <br>";
            continue;
        }

        $backgroundAttr = new BackgroundClass();
        $backgroundAttr->selectByTitle($backgroundAttrTitle);
        if (!$backgroundAttr->isDone()) {
            echo $lineNumber.") ".$buffer.": There is no such backgroundAttr <br>";
            continue;
        }

        $maskAttr = new MaskClass();
        $maskAttr->selectByTitle($maskAttrTitle);
        if (!$maskAttr->isDone()) {
            echo $lineNumber.") ".$buffer.": There is no such maskAttr <br>";
            continue;
        }

        $jeweleryAttrAttr = new JeweleryClass();
        $jeweleryAttrAttr->selectByTitle($jeweleryAttrTitle);
        if (!$jeweleryAttrAttr->isDone()) {
            echo $lineNumber.") ".$buffer.": There is no such jeweleryAttrAttr <br>";
            continue;
        }

        $eyesAttr = new EyesClass();
        $eyesAttr->selectByTitle($eyesAttrTitle);
        if (!$eyesAttr->isDone()) {
            echo $lineNumber.") ".$buffer.": There is no such eyesAttr <br>";
            continue;
        }

        $lipsAttr = new LipsClass();
        $lipsAttr->selectByTitle($lipsAttrTitle);
        if (!$lipsAttr->isDone()) {
            echo $lineNumber.") ".$buffer.": There is no such lipsAttr <br>";
            continue;
        }

        $shirtAttr = new ShirtClass();
        $shirtAttr->selectByTitle($shirtAttrTitle);
        if (!$shirtAttr->isDone()) {
            echo $lineNumber.") ".$buffer.": There is no such shirtAttr <br>";
            continue;
        }
    

        $genFace = new GeneratedFaceClass();
        $genFace->selectByTitle($genImgTitle);
        if (!$genFace->isDone()) {
            $genFace = new GeneratedFaceClass();
            $values = array();
            $values['generatedPic_hash']    = "";
            $values['title']                = $genImgTitle;
            $values['faceAttr_link']        = $faceAttr->get('id');
            $values['hatAttr_link']         = $hatAttr->get('id');
            $values['backgroundAttr_link']  = $backgroundAttr->get('id');
            $values['maskAttr_link']        = $maskAttr->get('id');
            $values['jeweleryAttr_link']    = $jeweleryAttrAttr->get('id');
            $values['eyesAttr_link']        = $eyesAttr->get('id');
            $values['lipsAttr_link']        = $lipsAttr->get('id');
            $values['shirtAttr_link']       = $shirtAttr->get('id');
            $values['smallPreview']         = '/previewData/smallPreview/'.$genImgTitle;
            $values['midPreview']           = '/previewData/midPreview/'.$genImgTitle;
            $values['maxPreview']           = '/previewData/maxPreview/'.$genImgTitle;
            $values['rarity']               =  $rarity;
            $values['number']               =  $number;
            $genFace->insert($values);
            if (!$genFace->isDone()){
                echo $lineNumber.") ".$buffer.": ".$genFace->getLastError()."<br>";
                //errorMessage($buffer, $studentsHistoryNote->getLastError());
                continue;   
            } else {
                echo $lineNumber.") ".$buffer.": genFace was inserted<br>";
            }
        }
        else {
            echo "<h1>IMG WITH CURRENT TITLE PERSISTS ".$genImgTitle."!!</h1>";
            continue;
        }
    }
    if (!feof($file)) {
        echo "Error: unexpected fgets() fail<br>";
    }
    fclose($file);

    $hashStatFile = fopen($_SERVER['DOCUMENT_ROOT']."/scripts/hashStat.csv", "r");

    if ($hashStatFile) {
        print "starting...<br>";
        $lineNumber = 0;
        while (($buffer = fgets($hashStatFile, 4096)) !== false) {
            $lineNumber++;
            if ($lineNumber == 1){
                continue;
            }
            $buffer = iconv("CP1251", "UTF-8", $buffer);
            $buffer = str_replace(array("\n", "\r"), '', $buffer);
            list($hash,$title) = explode(";", $buffer);
    
            if  (   $hash == "" ||
                    $title == ""
                )
            {
                echo $lineNumber.") ".$buffer.": empty field<br>";
                //errorMessage($buffer, "empty field");
                continue;  
            }

            $genFace = new GeneratedFaceClass();
            $genFace->selectByTitle($title);
            if (!$genFace->isDone()){
                echo $lineNumber.") ".$buffer.": ".$genFace->getLastError()."<br>";
                //errorMessage($buffer, $studentsHistoryNote->getLastError());
                continue;   
            }
            else {
                $genFace->set('generatedPic_hash', $hash);
                $genFace->update();
                if (!$genFace->isDone()){
                    echo $lineNumber.") ".$buffer.": ".$genFace->getLastError()."<br>";
                    continue;   
                } else {
                    echo $lineNumber.") ".$buffer.": face hash was changed<br>";
                }
            }  
        }
        if (!feof($hashStatFile)) {
            echo "Error: unexpected fgets() fail<br>";
        }
        fclose($hashStatFile); 
    }
    else {
        echo "hashStatFile hashStat.csv not exists<br>";
    }
}
else {
    echo "file dataForMySql.csv not exists<br>";
}