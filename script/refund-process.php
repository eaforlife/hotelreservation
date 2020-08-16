<?php
require("sqlConnect.php");
echo "Got PHP File: " . print_r($_POST, true);
echo "Got UID" . $_POST['uid'];
$uid = htmlspecialchars($_POST['uid']);
$transactionID = htmlspecialchars($_POST['transactionID']);
$uid = trim($uid);
$transactionID = trim($transactionID);
echo "Got 2nd round of UID" . $_POST['uid'];
$sql = $mConn->prepare("UPDATE reservations SET refundStatus='1' WHERE transactionID=?;");
if(false===$sql) {
    die("Statement Error: " . htmlspecialchars($mConn->error));
}

$prep_st = $sql->bind_param("s",$t_ID);
if(false===$prep_st) {
    die("Binding param error: " . htmlspecialchars($sql->error));
}
$t_ID = $transactionID;

$prep_st = $sql->execute();
if(false===$prep_st) {
    die("Execute Error: " . htmlspecialchars($sql->error));
}
$sql->close();
echo "Auditing --";
$audit_msg = "REFUND TRANSACTION - TRANSACTION ID: " . $transactionID . " - SUCCESS";
date_default_timezone_set('Asia/Manila');
$timestamp = date("Y-m-d H:i:s");
$sql = $mConn->prepare("INSERT INTO audit_trail (user_id, activity, time_stamp) VALUES (?,?,?);");
$sql->bind_param("sss",$user_id,$msg,$dt);
$user_id=$uid;
$msg=$audit_msg;
$dt = $timestamp;
$sql->execute();

echo "Process Complete";
?>