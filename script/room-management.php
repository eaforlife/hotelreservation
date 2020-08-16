<?php
require("sqlConnect.php");


if($_POST['form-type'] == 'add') {
    // Add Room
    $rmType = $rmName = $rmAvailable = $rmDesc = $rmImg = "";
    $rmPrice6 = $rmPrice12 = $rmPrice24 = "";
    $err_msg = "";
    $uid = $_POST['uid'];
    $rmType = cleanStr($_POST['room-type']);
    $rmName = cleanStr($_POST['room-name']);
    $rmAvailable = cleanStr($_POST['room-quantity']);
    $rmDesc = cleanStr($_POST['room-desc']);
    $rmImg = cleanStr($_POST['room-img']);
    $rmPrice6 = cleanStr($_POST['price-6h']);
    $rmPrice12 = cleanStr($_POST['price-12h']);
    $rmPrice24 = cleanStr($_POST['price-24h']);
    
    if(empty($rmType) && empty($rmName) && empty($rmAvailable) && empty($rmDesc) && empty($rmImg) && empty($rmPrice6) && empty($rmPrice6) && empty($rmPrice12) && empty($rmPrice24)) {
        replyStatus(0,"Please make sure all fields are filled");
    } else {
        $roomID = 0;
        $sql = "INSERT INTO rooms (roomName, roomDesc, roomType, roomQty, placeholderID, deleted) VALUES ('$rmName', '$rmDesc', '$rmType', '$rmAvailable', '$rmImg','0');";
        $rslt = $mConn->query($sql);
        if($rslt===FALSE) {
            $audit_msg = "ROOM MANAGEMENT - USER ID: $uid - ADD ROOM - ERROR";
            date_default_timezone_set('Asia/Manila');
            $timestamp = date("Y-m-d H:i:s");
            $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
            $rslt_audit = $mConn->query($sql_audit);
            if(false===$rslt_audit) die("An error has occurred while audting data: " . htmlspecialchars($mConn->error));
            
            $response_arr['status'] = "error";
            $response_arr['msg'] = "Error in adding room: " . htmlspecialchars($mConn->error);
            header('Content-type: application/json');
            echo json_encode($response_arr);
        } else {
            $roomID = $mConn->insert_id;
            
            $sql_price = "INSERT INTO room_price (roomID, price6, price12, price24) VALUES ('$roomID', '$rmPrice6', '$rmPrice12', '$rmPrice24');";
            $rslt_price = $mConn->query($sql_price);
            if($rslt_price===FALSE) {
                $audit_msg = "ROOM MANAGEMENT - USER ID: $uid - ADD ROOM - ERROR";
                date_default_timezone_set('Asia/Manila');
                $timestamp = date("Y-m-d H:i:s");
                $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
                $rslt_audit = $mConn->query($sql_audit);
                if(false===$rslt_audit) die("An error has occurred while audting data: " . htmlspecialchars($mConn->error));
                
                $response_arr['status'] = "error";
                $response_arr['msg'] = "Error in adding room: " . htmlspecialchars($mConn->error);
                header('Content-type: application/json');
                echo json_encode($response_arr);
            } else {
                $audit_msg = "ROOM MANAGEMENT - USER ID: $uid - ADD ROOM - SUCCESS";
                date_default_timezone_set('Asia/Manila');
                $timestamp = date("Y-m-d H:i:s");
                $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
                $rslt_audit = $mConn->query($sql_audit);
                if(false===$rslt_audit) die("An error has occurred while audting data: " . htmlspecialchars($mConn->error));
                
                $response_arr['status'] = "success";
                $response_arr['msg'] = "Successfully added room to database!";
                header('Content-type: application/json');
                echo json_encode($response_arr);
            }
        }
        
        
    }
    
}

if($_POST['form-type'] == 'delete') {
    // Delete Room
    $roomID = cleanStr($_POST['room-id']);
    $roomName = cleanStr($_POST['room-name']);
    $uid = cleanStr($_POST['uid']);
    
    $sql = "UPDATE rooms SET deleted='1' WHERE `roomID`='$roomID';";
    $ext = $mConn->query($sql);
    if($ext===FALSE) {
        $response_arr['status'] = "error";
        $response_arr['msg'] = "Something went wrong while deleting room. Try again later.";
        header('Content-type: application/json');
        echo json_encode($response_arr);
        die("An error has occurred while audting data: " . htmlspecialchars($mConn->error));
    }
    
    $audit_msg = "ROOM MANAGEMENT - USER ID: $uid - DELETE ROOM - " . $roomID . ": " . strtoupper($roomName) . "  - SUCCESS";
    date_default_timezone_set('Asia/Manila');
    $timestamp = date("Y-m-d H:i:s");
    $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
    $rslt_audit = $mConn->query($sql_audit);
    if(false===$rslt_audit) die("An error has occurred while audting data: " . htmlspecialchars($mConn->error));
    
    $response_arr['status'] = "success";
    $response_arr['msg'] = "Successfully deleted room from database!";
    header('Content-type: application/json');
    echo json_encode($response_arr);
}

if($_POST['form-type'] == 'edit') {
    $rmType = $rmName = $rmAvailable = $rmDesc = $rmImg = "";
    $rmPrice6 = $rmPrice12 = $rmPrice24 = "";
    $err_msg = "";
    $uid = $_POST['uid'];
    $rmID = cleanStr($_POST['room-id']);
    $rmType = cleanStr($_POST['room-type']);
    $rmName = cleanStr($_POST['room-name']);
    $rmAvailable = cleanStr($_POST['room-available']);
    $rmDesc = cleanStr($_POST['room-desc']);
    $rmImg = cleanStr($_POST['room-img']);
    $rmPrice6 = cleanStr($_POST['price-6h']);
    $rmPrice12 = cleanStr($_POST['price-12h']);
    $rmPrice24 = cleanStr($_POST['price-24h']);
    
    if(empty($rmType) && empty($rmName) && empty($rmAvailable) && empty($rmDesc) && empty($rmImg) && empty($rmPrice6) && empty($rmPrice6) && empty($rmPrice12) && empty($rmPrice24)) {
        replyStatus(0,"Please make sure all fields are filled");
    } else {
        $sql = "UPDATE rooms SET roomDesc='$rmDesc', roomType='$rmType', roomQty='$rmAvailable', placeholderID='$rmImg' WHERE roomID='$rmID';";
        $ext = $mConn->query($sql);
        if($ext===FALSE) die("Error updating rooms " . htmlspecialchars($mConn->error));
        
        $sql = "UPDATE room_price SET price6='$rmPrice6', price12='$rmPrice12', price24='$rmPrice24' WHERE roomID='$rmID';";
        $ext = $mConn->query($sql);
        if($ext===FALSE) die("Error updating room price " . htmlspecialchars($mConn->error));
        
        $audit_msg = "ROOM MANAGEMENT - USER ID: $uid - EDIT ROOM - " . $rmID . ": " . strtoupper($rmName) . "  - SUCCESS";
        date_default_timezone_set('Asia/Manila');
        $timestamp = date("Y-m-d H:i:s");
        $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
        $rslt_audit = $mConn->query($sql_audit);
        if(false===$rslt_audit) die("An error has occurred while audting data: " . htmlspecialchars($mConn->error));
        
        $response_arr['status'] = "success";
        $response_arr['msg'] = "Successfully edit room from database!";
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