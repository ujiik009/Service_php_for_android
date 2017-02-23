
<?php
include "../lib/phpqrcode/qrlib.php";
include '../config/config.php';

  function search_TA_by_sec_id($obj_con,$sec_id){
     $sql_search_ta = "SELECT `nickname` as `name`,`ta_tel`,`img_address` FROM `ta_account` WHERE `ta_sec` = '{$sec_id}'";
    $datas_ta = array();
    if (  !!$res = mysqli_query($obj_con,$sql_search_ta)) {
      while ($data_ta = mysqli_fetch_assoc($res)) {
        $datas_ta[] = $data_ta;
      }
      return $datas_ta;
    }else{
      return fase;
    }
  }


  function Function_login($obj_con,$username,$password){
    $sql = "SELECT `student_list`.number_student,`student_list`.student_code,`student_list`.student_name,`student_list`.student_sec,student_account.book_code,student_account.address_QR";
    $sql.= " FROM student_account INNER JOIN student_list ON (student_account.student_code = student_list.student_code)";
    $sql.= "WHERE `std_user` = '{$username}' AND `std_pass` = '{$password}' ;";
    $return = array();
    if ($res = mysqli_query($obj_con,$sql)) {

      if (mysqli_num_rows($res) == 1 ) {

          $row = mysqli_fetch_assoc($res);
          $student_sec = $row['student_sec'];
          $data_ta  = search_TA_by_sec_id($obj_con,$student_sec);
          if (is_array($data_ta)) {
            $return["status"] = true;
            $return["data_user"] = $row;
            $return['data_ta'] = $data_ta;
            $return["message"] = "found user";

          }else{
            $return["status"] = true;
            $return["data_user"] = $row;
            $return['data_ta'] = array();
            $return["message"] = "found user";
          }

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
