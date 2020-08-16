<?php
$mConn = new mysqli("localhost","root","","playndisplay");
if($mConn->connect_error) {
    die("Connection Failed: " . $mConn->connect_error);
}
?>