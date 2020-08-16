<?php
require("sqlConnect.php");

if($_POST['form-type']==='add') {
    
    $email = $fname = $lname = $contact = $password = "";
    
    $email = cleanStr($_POST['email']);
    $fname = cleanStr($_POST['first-name']);
    $lname = cleanStr($_POST['last-name']);
    $contact = cleanStr($_POST['contact']);
    $password = md5(cleanStr($_POST['password']));
    $questionId = cleanStr($_POST['question']);
    $answer = cleanStr($_POST['answer']);
    
    // Validation already done client side just empty check in case of hijack
    if(!empty($email) && !empty($fname) && !empty($lname) && !empty($contact) && !empty($password)) {
        // Validate email must not exist
        $email = strtolower($email);
        $answer = strtolower($answer);
        $exists = 0;
        
        $sql = "SELECT email FROM users WHERE email='$email';";
        $ext = $mConn->query($sql);
        if($ext->num_rows > 0) {
            $exists = 1; // if Email exists
        }
        if($exists == 0) {
            $sql = "INSERT INTO users (email, password, level, fname, lname, contact, securityid, answer) VALUES ('$email', '$password', '5', '$fname', '$lname', '$contact', '$questionId','$answer');";
            $ext = $mConn->query($sql);
            if($ext===FALSE) die("Error adding user " . $mConn->error);
            $response_arr['status'] = "success";
            $response_arr['msg'] = "Successfully added user! Please wait while we return you to the login page.";
            header('Content-type: application/json');
            echo json_encode($response_arr);
        } else {
            $response_arr['status'] = "error";
            $response_arr['msg'] = "Email already exists. Please use a different email.";
            header('Content-type: application/json');
            echo json_encode($response_arr);
        }
        
        
    } else {
        
        $response_arr['status'] = "error";
        $response_arr['msg'] = "One or more fields were invalid. Please try again.";
        header('Content-type: application/json');
        echo json_encode($response_arr);
        
    }
    
}


// Edit Profile
if($_POST['form-type']==='edit') {
    $uid = $fname = $lname = $contact = "";
    $old_pass = $new_pass = $new_pass_2 = "";
    $err_msg = "";
    
    // Change profile only
    // Validation
    if(empty($_POST['cur-password']) && empty($_POST['new-password']) && empty($_POST['confirm-password'])) {
        $uid = $_POST['uid'];
        $fname = $_POST['first-name'];
        $lname = $_POST['last-name'];
        $contact = $_POST['contact'];
        if(empty($uid) && empty($fname) && empty($lname) && empty($contact)) {
            echo "One or more fields are empty";
        } else {
            $uid = cleanStr($_POST['uid']);
            $fname = cleanStr($_POST['first-name']);
            $lname = cleanStr($_POST['last-name']);
            $contact = cleanStr($_POST['contact']);
            if(strlen($fname) < 2 && !preg_match ("/^[a-zA-Z\s]+$/",$fname)) {
                $err_msg .= " Invalid first name. ";
            }
            if(strlen($lname) < 2 && !preg_match ("/^[a-zA-Z\s]+$/",$lname)) {
                $err_msg .= " Invalid last name. ";;
            }
            if(is_numeric($contact)===TRUE) {
                if(strlen($contact) < 7 && strlen($contact) > 15) {
                    $err_msg .= " Invalid Contact Number ";
                }
            } else {
                $err_msg .= " Only numbers are allowed in contact field ";
            }
            
            if(empty($err_msg)) {
                $sql = "UPDATE users SET fname='$fname', lname='$lname', contact='$contact' WHERE idusers='$uid';";
                $rslt = $mConn->query($sql);
                if(false===$rslt) die("An error has occurred while preparing data: " . htmlspecialchars($mConn->error));
                
                $audit_msg = "ACCOUNT MANAGEMENT - USER ID: $uid - EDIT PROFILE ONLY - SUCCESS";
                date_default_timezone_set('Asia/Manila');
                $timestamp = date("Y-m-d H:i:s");
                $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
                $rslt_audit = $mConn->query($sql_audit);
                if(false===$rslt_audit) die("An error has occurred while audting data: " . htmlspecialchars($mConn->error));
                
                echo "Successfully updated data to database. Logging you out after 5 seconds.";
            } else {
                $response_arr['status'] = "error";
                $response_arr['msg'] = "One or more errors found: " . $err_msg;
                header('Content-type: application/json');
                echo json_encode($response_arr);
            }
        }
    }
    // Change password and profile
    if(!empty($_POST['cur-password']) && !empty($_POST['new-password']) && !empty($_POST['confirm-password'])) {
        $uid = $_POST['uid'];
        $fname = $_POST['first-name'];
        $lname = $_POST['last-name'];
        $contact = $_POST['contact'];
        $old_pass = $_POST['cur-password'];
        $new_pass = $_POST['new-password'];
        $new_pass_2 = $_POST['confirm-password'];
        if(empty($uid) && empty($fname) && empty($lname) && empty($contact)) {
            echo "One or more fields are empty";
        } else {
            $uid = cleanStr($_POST['uid']);
            $fname = cleanStr($_POST['first-name']);
            $lname = cleanStr($_POST['last-name']);
            $contact = cleanStr($_POST['contact']);
            $old_pass = md5(cleanStr($_POST['cur-password']));
            $new_pass = md5(cleanStr($_POST['new-password']));
            $new_pass_2 = md5(cleanStr($_POST['confirm-password']));
            $pass = "";
            $sql = "SELECT password FROM users WHERE idusers='$uid'";
            $checkPass = $mConn->query($sql);
            if($checkPass->num_rows > 0) {
                while($row = $checkPass->fetch_assoc()) {
                    $pass = $row['password'];
                }
            }
            
            if(md5($pass) == $old_pass) {
                if($new_pass == $old_pass) {
                    $err_msg .= " New password must not match with new password ";
                } else {
                    if(strlen($new_pass) < 6) {
                        $err_msg .= " New password should be atleast 6 characters long ";
                    }
                    if($new_pass != $new_pass_2) {
                        $err_msg .= " New password does not match with confirm new password field ";
                    }
                }
            } else {
                $err_msg .= " Incorrect Password.";
            }
            
            
            
            if(empty($err_msg)) {
                $fname = strtoupper($fname);
                $lname = strtoupper($lname);
                $sql = "UPDATE users SET fname='$fname', lname='$lname', contact='$contact', password='$new_pass' WHERE idusers='$uid';";
                $rslt = $mConn->query($sql);
                if(false===$rslt) die("An error has occurred while preparing data: " . htmlspecialchars($mConn->error));
                
                $audit_msg = "ACCOUNT MANAGEMENT - USER ID: $uid - EDIT PASSWORD - SUCCESS";
                date_default_timezone_set('Asia/Manila');
                $timestamp = date("Y-m-d H:i:s");
                $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
                $rslt_audit = $mConn->query($sql_audit);
                if(false===$rslt_audit) die("An error has occurred while audting data: " . htmlspecialchars($mConn->error));
                
                echo "Successfully updated data to database. Logging you out after 5 seconds.";
            } else {
                $response_arr['status'] = "error";
                $response_arr['msg'] = "One or more errors found: " . $err_msg;
                header('Content-type: application/json');
                echo json_encode($response_arr);
            }
        }
    }
}

// Update profile if security question is not set. Important! This will only show up upon login.
if($_POST['form-type']==='security') {
    $questionId = $answer = $uid = "";
    $uid = cleanStr($_POST['modal-id']);
    $questionId = cleanStr($_POST['question']);
    $answer = cleanStr($_POST['answer']);
    
    if(!empty($uid) && !empty($questionId) && !empty($answer)) {
        $sql = "UPDATE users SET securityid='$questionId', answer='$answer' WHERE idusers='$uid';";
        $ext = $mConn->query($sql);
        if(false===$ext) {
            $response_arr['status'] = "error";
            $response_arr['msg'] = "Error updating your account. Please try again later.";
            header('Content-type: application/json');
            die(json_encode($response_arr));
        } 
        
        $response_arr['status'] = "success";
        $response_arr['msg'] = "Thank you for updating your account! You will be redirected shortly.";
        header('Content-type: application/json');
        echo json_encode($response_arr);
        
    }
}


function cleanStr($x) {
    $x = htmlspecialchars($x);
    $x = stripslashes($x);
    return trim($x);
}

?>