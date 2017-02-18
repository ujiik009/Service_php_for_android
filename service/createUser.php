<?php
include "../lib/phpqrcode/qrlib.php";
	$box = array();
	$json = array();
	$tempDir = "../tempQRcode/"; 
	$tempQRSERVER = "http://192.168.1.37/Service_php_for_android/tempQRcode/";

	$codeContents = $_REQUEST["studentCode"]; 
	//$codeContents = 555; 
     
    // we need to generate filename somehow,  
    // with md5 or with database ID used to obtains $codeContents... 
    $fileName = "{$_REQUEST["studentCode"]}".md5($_REQUEST["username"]).'.png'; 
     
    $pngAbsoluteFilePath = $tempDir.$fileName; 
    $urlRelativeFilePath = $tempDir.$fileName; 
     
    // generating 
    if (!file_exists($pngAbsoluteFilePath)) { 
        QRcode::png($codeContents, $pngAbsoluteFilePath); 
        $json["status"] = true;
        $json["pathQR"] = $tempQRSERVER.$fileName;
        //echo $json["pathQR"] ;
        array_push($box, $json);
        echo json_encode($box);
    } else { 
        $json["status"] = false;
        $json["pathQR"] = "";
        array_push($box, $json);
        echo json_encode($box);
    } 
     
   
?>