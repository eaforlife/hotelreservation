<?php
require("sqlConnect.php");

$tID = $roomName = $duration = $reservationTime = "";
$email = $transactionDate = $amt = "";

if($_POST['status'] === "success") {
    $tID = cleanStr($_POST['transactionId']);
    
    if(!empty($tID)) {
       // Get Transaction Details now
       $sql = "SELECT transactionID, amountpaid, (SELECT email FROM users WHERE users.idusers = reservations.userID) AS email, (SELECT roomName FROM rooms WHERE rooms.roomID = reservations.roomID) AS roomName, duration, reservationDate, transactionDate FROM reservations WHERE transactionID='$tID' LIMIT 1;";
       $ext = $mConn->query($sql);
       if($ext->num_rows > 0) {
           $status = 1;
           while($row = $ext->fetch_assoc()) {
               $transactionDate = $row['transactionDate'];
               $roomName = $row['roomName'];
               $duration = $row['duration'] . " Hours";
               $reservationTime = $row['reservationDate'];
               $email = $row['email'];
               $amt = $row['amountpaid'];
           }
       } else {
           $status = 2;
       }
    }
}

function cleanStr($x) {
    $x = htmlspecialchars($x);
    $x = stripslashes($x);
    return trim($x);
}

?>

<?php if($status == 1): ?>
<div class="container">
	<div class="row">&nbsp;</div>
	<div class="row">
		<div class="col-sm-4">&nbsp;</div>
		<div class="col-sm-4"><h2 id="print-center">Play N' Display</h2></div>
		<div class="col-sm-1">&nbsp;</div>
		<div class="col-sm-3"><small id="print-right">Date: <?php echo $transactionDate; ?></small></div>
	</div>
	<div class="row">
		<table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Invoice ID</th>
              <th scope="col">Room Name</th>
              <th scope="col">Duration</th>
              <th scope="col">Reservation Time</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row"><?php echo $tID; ?></th>
              <td><?php echo $roomName; ?></td>
              <td><?php echo $duration; ?></td>
              <td><?php echo $reservationTime; ?></td>
            </tr>
          </tbody>
        </table>
        <h6>Amount Paid: <?php echo $amt; ?></h6>
        <hr>
        <small class="text-muted">* Please show this invoice to our front desk for us to guide you to your room.</small>
	</div>
	<div class="row">&nbsp;</div>
    <hr>
    <div class="row">
    	<div class="col-sm-4"><small id="print-left">E-Mail: <?php echo $email; ?></small></div>
    	<div class="col-sm-8">&nbsp;</div>
    </div>
    <div class="row">
    	<div class="col-sm-5">&nbsp;</div>
    	<div class="col-sm-3"><small class="text-muted" id="print-center">Play N' Display &copy; 2017</small></div>
    	<div class="col-sm-4">&nbsp;</div>
    </div>
    <div class="row">&nbsp;</div>
</div>
<?php elseif($status == 2): ?>
<div class="container">
	<h1>Transaction does not exist! If transaction has been processed, you may view transactions through the reservations menu. Otherwise please try again later.</h1>
</div>
<?php else: ?>
<div class="container">
	<h1>An unexpected error has occurred. If transaction has been processed, you may view transactions through the reservations menu. Otherwise please try again later.</h1>
</div>
<?php endif ?>