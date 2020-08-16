<?php

require("sqlConnect.php");

$uid = cleanStr($_POST['uid']);
$rmID = cleanStr($_POST['room-id']);
$rsDate = $_POST['reserve-date'];
$duration = cleanStr($_POST['select-duration']);
$transactionID = cleanStr($_POST['transactionId']);
$rsTime = "";
if(isset($_POST['select-day'])) {
    $rsTime = cleanStr($_POST['select-day']);
} else {
    $rsTime = "09:00:00";
}
$amt = cleanStr($_POST['amount']);
$reserveDate = $rsDate . " " . $rsTime;
date_default_timezone_set('Asia/Manila');
$timestamp = date("Y-m-d H:i:s");

// Validate Room QTY
if(isset($_POST['status'])) {
    
    if($_POST['status']=='valid') {
        $errorMsg = "";
        $sql = "SELECT roomQty FROM rooms WHERE roomID='" . $rmID . "';";
        $ext = $mConn->query($sql);
        $quantity = 0;
        $reserved = 0;
        while($row=$ext->fetch_assoc()) {
            $quantity = $row['roomQty'];
        }
        $sql = "SELECT duration, TIME(reservationDate) as reserveTime FROM reservations WHERE roomID='" . $rmID . "' AND TIME(reservationDate)=TIME('" . $reserveDate . "') AND duration='" . $duration . "' AND DATE(reservationDate)=DATE('" . $reserveDate . "');";
        $ext = $mConn->query($sql);
        $reserved = $ext->num_rows;
        
        $assumeReserve = $reserved + 1;
        
        // Room Check
        if($quantity < $assumeReserve) {
            // If reservation is full
            $response_arr['status'] = "full";
            $response_arr['msg'] = "Room is full on your selected date or preferred duration of stay. Please try again.";
            $response_arr['debug'] = "Available: " . $quantity . " + Reserved: " . $reserved;
            header('Content-type: application/json');
            echo json_encode($response_arr);
        } else {
            // Vacancy available
            $response_arr['status'] = "vacant";
            $response_arr['msg'] = "Room is available on your selected date! Proceed to PayPal checkout to finalize reservation.";
            $response_arr['debug'] = "Available: " . $quantity . " + Reserved: " . $reserved;
            header('Content-type: application/json');
            echo json_encode($response_arr);
        }
    }
    
} else {
    $sql = "INSERT INTO reservations (transactionID, roomID, duration, transactionDate, userID, refundStatus, reservationDate, amountpaid) VALUES ('$transactionID', '$rmID', '$duration', '$timestamp', '$uid', '0', '$reserveDate', '$amt');";
    $ext = $mConn->query($sql);
    if($ext===FALSE) die("Error in reserving: ".htmlspecialchars($mConn->error));
    
    $audit_msg = "RESERVATION - ROOM ID: $rmID - DATE: $reserveDate - DURATION: $duration HRS - SUCCESS";
    $sql_audit = "INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES ('$uid','$audit_msg','$timestamp');";
    $rslt_audit = $mConn->query($sql_audit);
    if(false===$rslt_audit) die("An error has occurred while audting data: " . htmlspecialchars($mConn->error));
    
}

function cleanStr($x) {
    $x = htmlspecialchars($x);
    $x = stripslashes($x);
    return trim($x);
}

?>
