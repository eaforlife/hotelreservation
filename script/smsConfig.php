<?php

//
// Using iTexMo API: https://www.itexmo.com/Developers/Packages.php
// Trial Package: 10 sms per day. 100 max characters. Expiry 7 days after inactivity of system.
//

// Editable Field Starts here
$apicode = "TR-PLAYN527363_MF8WM"; // Found on email from API after registering.
// Editable Field Ends here

// Do not touch below
require("sqlConnect.php");

if($_POST['type']=='invoice') {
    // Generate Notification of Reservation
    $transactionId = $_POST['transactionID'];
    $contact = $reserve = "";
    
    $sql = "SELECT (SELECT contact FROM users WHERE users.idusers = reservations.userID) AS contact, DATE(reservationDate) as reservationDate FROM reservations WHERE transactionID='$transactionId';";
    $ext = $mConn->query($sql);
    if($ext->num_rows > 0) {
        while($rows = $ext->fetch_assoc()) {
            $contact = $rows['contact'];
            $reserve = $rows['reservationDate'];
        }
    }
    
    $contact = checkMobile($contact); // Check if number is PH mobile number. Otherwise don't attempt to send SMS.
    
    if(!empty($contact)) {
        $msg = "Transaction Complete. Transaction ID: $transactionId. Reservation Date: $reserve. Thank you."; // SMS Message
        $msg = substr($msg, 0, 99); // Concat message to 100 characters due to limitation. Character index starts at 0.
        
        // Reference: CURL-LESS https://itexmo.com/Developers/apidocs.php
        $status = itexmo($contact,$msg,$apicode);
        if($status=="") {
            $response_arr['status'] = "error";
            $response_arr['msg'] = "<p class='text-warning'><i class='fa fa-exclamation' aria-hidden='true'></i> Unable to connect to SMS provider.</p>";
            header('Content-type: application/json');
            echo json_encode($response_arr);
        } elseif($status=="0") {
            $response_arr['status'] = "success";
            $response_arr['msg'] = "<p class='text-success'><i class='fa fa-check' aria-hidden='true'></i> A copy of reservation has been sent to your number.</p>";
            header('Content-type: application/json');
            echo json_encode($response_arr);
        } else {
            $response_arr['status'] = "invalid";
            $response_arr['msg'] = "<p class='text-warning'><i class='fa fa-exclamation' aria-hidden='true'></i> SMS provider encountered an error. Code: " . $status . "!</p>";
            header('Content-type: application/json');
            echo json_encode($response_arr);
        }
    } else {
        // Not a mobile number
        $response_arr['status'] = "stop";
        $response_arr['msg'] = "SMS Notification not sent. Number in profile is not a valid number.";
        $response_arr['debug'] = "Message: $msg,   Contact: $contact";
        header('Content-type: application/json');
        echo json_encode($response_arr);
    }
}

function itexmo($number,$message,$code) {
    $url = 'https://www.itexmo.com/php_api/api.php';
    $itexmo = array('1' => $number, '2' => $message, '3' => $code);
    $param = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($itexmo),
        ),
    );
    $context  = stream_context_create($param);
    return file_get_contents($url, false, $context);
}

function checkMobile($x) {
    // Simple validation if number is a local mobile phone
    $counter = strlen($x);
    $cleanNumber = $number = "";
    if($counter == "11") { // Example: 09151234567
        $number = $x;
    } 
    if ($counter == "10") { // Example: 9151234567
        $number = "0" . $x; // Add 0 before number assuming input is same as example.
    }
    
    // Check if start digit is 09. Otherwise return null.
    if(substr($number, 0, 2) == "09") {
        $cleanNumber = $number;
    }
    return $cleanNumber;
}

?>