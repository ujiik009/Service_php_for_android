<?php
include "../lib/phpqrcode/qrlib.php";
include '../config/config.php';
// "studentCode="
//  "username="
//  "password="
//  "BookCode="
//  "Domain="



    $box = array();
    $json = array();
// create function check_student_code()
function check_student_code($obj_con,$student_code){
    $sql_check_username = "SELECT * FROM `student_list` WHERE `student_code` = '{$student_code}'";

    if($res = mysqli_query($obj_con, $sql_check_username)){
        if(mysqli_num_rows($res) == 1 ){

            return true;
        }else {

            return false;
        }
    }else{
        return false;
    }

}//end function check_student_code()

// create function register
function register($obj_con,$studentCode,$username,$password,$BookCode,$address_QR){

	$sql_insert_user = " INSERT INTO `student_account`(`std_user`, `std_pass`, `student_code`, `book_code`, `address_QR`)
	VALUES ('{$username}','{$password}','{$studentCode}','{$BookCode}','{$address_QR}')";
      if(mysqli_query($obj_con,$sql_insert_user)){
        return true;
      }else{
        return false;
      }
}// end function register


if(count($_REQUEST)>0){// check send request

    if(check_student_code($obj_con,$_REQUEST["studentCode"]) === true){ //call function for check student


            $tempDir = "../tempQRcode/";
            $tempQrRequest = $_REQUEST["Domain"];
            $tempQRSERVER = $tempQrRequest."tempQRcode/";
            $codeContents = $_REQUEST["studentCode"];


            // we need to generate filename somehow,
            // with md5 or with database ID used to obtains $codeContents...
            $fileName = "{$_REQUEST["studentCode"]}".md5($_REQUEST["studentCode"]).'.png';

            $pngAbsoluteFilePath = $tempDir.$fileName;
            $urlRelativeFilePath = $tempDir.$fileName;

            // generating
            if (!file_exists($pngAbsoluteFilePath)) {// check file image

                // sql command check user
                $sql_check_username = "SELECT * FROM `student_account` WHERE `std_pass` = '".$_REQUEST["username"]."' OR `student_code` = '".$_REQUEST["studentCode"]."' ;";
                // check execute command $sql_check_username
                if ($res = mysqli_query($obj_con, $sql_check_username)) {
                    if(mysqli_num_rows($res)>0){ //check user in table student_account
                        $json["status"] = false;
                        $json["pathQR"] = "";
                        $json["massage"] = "The user then can not create user.";
                    }else{
                        QRcode::png($codeContents, $pngAbsoluteFilePath); // gen img QR
                        // check status function register()
                        if (register($obj_con,$_REQUEST['studentCode'],$_REQUEST['username'],$_REQUEST['password'],$_REQUEST['BookCode'],$tempQRSERVER.$fileName)===true) {
                          $json["status"] = true;
                          $json["pathQR"] = $tempQRSERVER.$fileName;
                          $json["massage"] = "Create User success full";
                        }else {
                          $json["status"] = false;
                          $json["pathQR"] = $tempQRSERVER.$fileName;
                          $json["massage"] = "การสร้าง User ขัดข้อง";
                        }
                    }

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
                $json["massage"] = "รหัสนักศึกษานี้ได้ทำการลงทะเบียนแล้ว";
                array_push($box, $json);
                echo json_encode($box);
            }

    }else{
        $json["status"] = false;
        $json["pathQR"] = "";
        $json["massage"] = "คุณไม่ใช้นักศึกษาที่มีสิทธิ์ใช้งาน";
        array_push($box, $json);
        echo json_encode($box);
    }




}else{
    // no value
}



?>
