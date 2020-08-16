<?php

require("sqlConnect.php");

if($_POST['formType']=="check-email") {
    $email = cleanStr($_POST['email']);
    $email = strtolower($email);
    $questionId = $userId = $question = "";
    
    if(!empty($email)) {
        $sql = "SELECT idusers, securityid, (SELECT security_question FROM security_template WHERE securityID = users.securityid) AS question FROM users WHERE email='$email';";
        $ext = $mConn->query($sql);
        if($ext->num_rows > 0) {
            while($row = $ext->fetch_assoc()) {
                $userId = $row['idusers'];
                $questionId = $row['securityid'];
                $question = $row['question'];
                
                if($questionId == "0") {
                    // No security question set. Not possible to retrieve account.
                    $response_arr['status'] = "error";
                    $response_arr['msg'] = "System has detected that this account doesn't have a security feature set. The security feature is usually set upon successful login. For assistance kindly contact us or try again.";
                    header('Content-type: application/json');
                    echo json_encode($response_arr);
                    
                } else {
                    $response_arr['status'] = "success";
                    $response_arr['userid'] = $userId;
                    $response_arr['questionid'] = $questionId;
                    $response_arr['question'] = $question;
                    header('Content-type: application/json');
                    echo json_encode($response_arr);
                }
            }
        } else {
            // Return email not found
            $response_arr['status'] = "error";
            $response_arr['msg'] = "Email not found in our records!";
            header('Content-type: application/json');
            echo json_encode($response_arr);
        }
    }
}

if($_POST['formType']=="change-password") {
    $answer = $uid = $password = "";
    $answer = cleanStr($_POST['answer']);
    $newPass = md5(cleanStr($_POST['pass1']));
    $uid = cleanStr($_POST['userid']);
    
    if(!empty($answer) && !empty($newPass) && !empty($uid)) {
        $answer = strtolower($answer);
        
        // Check if answer is correct first!
        $sql = "SELECT * FROM users WHERE idusers='$uid' AND answer LIKE '$answer';";
        $ext = $mConn->query($sql);
        if(false===$ext) {
            $response_arr['status'] = "error";
            $response_arr['msg'] = "Unable to initiate server validation. Please try again later.";
            $response_arr['log'] = $mConn->error;
            header('Content-type: application/json');
            die(json_encode($response_arr));
        }
        if($ext->num_rows > 0) {
            // If answer matches in database change password
            $sqledit = "UPDATE users SET password='$newPass' WHERE idusers='$uid';";
            $editPass = $mConn->query($sqledit);
            if(false===$editPass) {
                // Show error if there is an error in database: syntax error OR database down.
                $response_arr['status'] = "error";
                $response_arr['msg'] = "Unable to update password due to error from the server. Please try again later.";
                $response_arr['log'] = $mConn->error;
                header('Content-type: application/json');
                die(json_encode($response_arr));
            }
            
            // Audit Trail
            $ip_add = get_client_ip();
            $audit_msg = "RESET PASSWORD - USER ID: $uid - IP ADDRESS: $ip_add - SUCCESS";
            date_default_timezone_set('Asia/Manila');
            $timestamp = date("Y-m-d H:i:s");
            $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
            $mConn->query($sql_audit);
            
            $response_arr['status'] = "success";
            $response_arr['msg'] = "Successfully changed password! You may now try to login.";
            header('Content-type: application/json');
            echo json_encode($response_arr);
        } else {
            $response_arr['status'] = "security";
            $response_arr['msg'] = "Security answer does not match on our records. Please try again.";
            header('Content-type: application/json');
            echo json_encode($response_arr);
        }
        
        
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