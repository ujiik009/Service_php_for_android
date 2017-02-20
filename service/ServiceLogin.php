<?php
include "../lib/phpqrcode/qrlib.php";
include '../config/config.php';




  function Function_login($obj_con,$username,$password){
    $sql = "SELECT * FROM `student_account` WHERE `std_user` = '{$username}' AND `std_pass` = '{$password}' ;";
    $return = array();
    if ($res = mysqli_query($obj_con,$sql)) {
      if (mysqli_num_rows($res) == 1 ) {
          $row = mysqli_fetch_assoc($res);
          $return["status"] = true;
          $return["data_user"] = $row;
          $return["message"] = "found user";
      }else {
          $return["status"] = false;
          $return["data_user"] = array();
          $return["message"] = "ไม่พบผู้ใช้อยู่ในระบบ โปรดลองใหม่";
      }
    } else {
          $return["status"] = false;
          $return["data_user"] = array();
          $return["message"] = "ไม่สามารถ เชื่อมต่อฐานข้อมูลได้่";
    }

    return $return;
  }


if(isset($_REQUEST['username']) && isset($_REQUEST['password'])){
    $str_username = $_REQUEST['username'];
    $str_password = $_REQUEST['password'];
    $res_function_login = Function_login($obj_con,$str_username,$str_password);
    if($res_function_login["status"] === true){
         echo "[".json_encode($res_function_login)."]";
    }else{
        echo "[".json_encode($res_function_login)."]";
    }
}else{
  echo "no value";
}

 ?>
