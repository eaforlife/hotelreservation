<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require("sqlConnect.php");

if(!empty($_POST['email']) && !empty($_POST['password'])) {
    // Basic validation already done from javascript.
    // Just clean inputs to avoid any potential injections.
    
    $email = cleanStr($_POST['email']);
    $pwd = md5(cleanStr($_POST['password']));
    
    $email = strtolower($email);
    $sql = "SELECT idusers, level FROM users WHERE email='$email' AND password='$pwd';";
    $rslt = $mConn->query($sql);
    if($rslt->num_rows > 0) {
        while($row = $rslt->fetch_assoc()) {
            $uid = $row['idusers'];
            $_SESSION['uid'] = $uid;
            $_SESSION['level'] = $row['level'];
            
            // Send to audit
            $ip_add = get_client_ip();
            $audit_msg = "LOGIN - USER ID: $uid - IP ADDRESS: $ip_add - SUCCESS";
            date_default_timezone_set('Asia/Manila');
            $timestamp = date("Y-m-d H:i:s");
            $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
            $mConn->query($sql_audit);
            
            $response_arr['status'] = "success";
            $response_arr['msg'] = "<i class='fa fa-check' aria-hidden='true'></i> Thank you for logging in! Please wait while we are setting up things for you..";
            header('Content-type: application/json');
            echo json_encode($response_arr);
        }
    } else {
        $response_arr['status'] = "error";
        $response_arr['msg'] = "<i class='fa fa-exclamation' aria-hidden='true'></i> Invalid username or password. Please try again";
        header('Content-type: application/json');
        echo json_encode($response_arr);
    }

}

function cleanStr($x) {
    $x = htmlspecialchars($x);
    $x = stripslashes($x);
    return trim($x);
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    else if(isset($_SERVER['HTTP_FORWARDED']))
                        $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        else if(isset($_SERVER['REMOTE_ADDR']))
                            $ipaddress = $_SERVER['REMOTE_ADDR'];
                            else
                                $ipaddress = 'UNKNOWN';
                                return $ipaddress;
}

?>