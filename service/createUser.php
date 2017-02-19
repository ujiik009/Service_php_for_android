<?php
include "../lib/phpqrcode/qrlib.php";
include '../config/config.php';

// "studentCode=" 
//  "username=" 
//  "password=" 
//  "BookCode=" 
//  "Domain=" 


    
if(count($_REQUEST)>0){

    $box = array();
    $json = array();
    $tempDir = "../tempQRcode/"; 
    $tempQrRequest = $_REQUEST["Domain"];
    $tempQRSERVER = $tempQrRequest."tempQRcode/";
    $codeContents = $_REQUEST["studentCode"]; 
    //$codeContents = 555; 
     
    // we need to generate filename somehow,  
    // with md5 or with database ID used to obtains $codeContents... 
    $fileName = "{$_REQUEST["studentCode"]}".md5($_REQUEST["username"]).'.png'; 
     
    $pngAbsoluteFilePath = $tempDir.$fileName; 
    $urlRelativeFilePath = $tempDir.$fileName; 
     
    // generating 
    if (!file_exists($pngAbsoluteFilePath)) { 
        
       
        $sql_check_username = "SELECT * FROM `stadent_info` WHERE `username` = '".$_REQUEST["username"]."' OR `student_code` = '".$_REQUEST["studentCode"]."' ;";
        
        if ($res = mysqli_query($obj_con, $sql_check_username)) {
            if(mysqli_num_rows($res)>0){
                $json["status"] = false;
                $json["pathQR"] = "";
                $json["massage"] = "Users are already";
            }else{
                 QRcode::png($codeContents, $pngAbsoluteFilePath); 
                $json["status"] = true;
                $json["pathQR"] = $tempQRSERVER.$fileName;
                $json["massage"] = "OK";
            }
            //echo $json["pathQR"] ;
            
        }else{
                $json["status"] = false;
                $json["pathQR"] = "";
                $json["massage"] = $sql_check_username." ===>".mysqli_error($obj_con);
        }

        array_push($box, $json);
        echo json_encode($box);

    } else { 
        $json["status"] = false;
        $json["pathQR"] = "";
        $json["massage"] = "user error";
        array_push($box, $json);
        echo json_encode($box);
    } 
}else{
    // no value


}
    
     
   
?>