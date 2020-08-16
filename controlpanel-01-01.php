<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['uid'];
$user_level = $_SESSION['level']; // 1-Admin 5-Guest 0-Unknown
$room_id=0;
if(isset($_GET['rid'])) {
    if(!empty($_GET['rid'])) {
        $room_id = $_GET['rid'];
    }
}

require('./script/sqlConnect.php');
// Generate Invoice Id
$invoiceId = generateId();
$sql = "SELECT * FROM playndisplay.reservations WHERE transactionID='$invoiceId';";
$ext = $mConn->query($sql);
while($ext->num_rows != 0) {
    $invoiceId = generateId(); // Loop until no transact id in database
    $ext = $mConn->query($sql);
}

function generateId() {
    return rand(100000,199999);
}

?>
<?php if(!empty($user_id)): ?>
<!DOCTYPE html>
<html lang="eng">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Dashboard - Reservations - Play N' Display Inn</title>
	<link rel="stylesheet" href="./style/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="./style/bootstrap.min.css">
	<link rel="stylesheet" href="./style/jquery-ui.min.css">
	<link rel="stylesheet" href="./style/playndisplay.dashboard.css">
	<style>
	</style>
</head>
<body>
<!-- Navigation -->
    <nav class="navbar navbar-dark fixed-top bg-dark">
    	<a class="navbar-brand" href="./">    		<img src="./media/img/logo-asset-1.png" width="106" height="60" alt="">
    		Play N Display Inn Hotel</a>
    	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-top" aria-controls="navbar-top" aria-expanded="false" aria-label="Toggle navigation">
        	<span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-top">
        	<ul class="navbar-nav mr-auto">
					<!-- GUEST AND ADMIN ACCESS -->
					<li class="nav-item">
						<a class="nav-link" id="overview" href="./controlpanel"><i class="fa fa-home" aria-hidden="true"></i> Overview</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" id="reserve" href="#"><i class="fa fa-bed" aria-hidden="true"></i> Reservations <span class="sr-only">(current)</span></a>
					</li>
					<?php if($user_level == 5): ?>
					<li class="nav-item">
						<a class="nav-link" href="./controlpanel-02"><i class="fa fa-suitcase" aria-hidden="true"></i> History</a>
					</li>
					<?php endif ?>
					<?php if($user_level == 1): ?>
					<!-- ADMIN ONLY ACCESS -->
					<li class="nav-item">
						<a class="nav-link" href="./controlpanel-03"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add/Edit Rooms</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./controlpanel-04"><i class="fa fa-credit-card" aria-hidden="true"></i> Payments</a>
					</li>
					<?php endif ?>
					<li class="nav-item">
						<a class="nav-link" href="./controlpanel-05"><i class="fa fa-cog" aria-hidden="true"></i> Account</a>
					</li>
					<?php if($user_level == 1): ?>
					<li class="nav-item">
						<!-- ADMIN ONLY -->
						<a class="nav-link" href="./controlpanel-06"><i class="fa fa-file-text-o" aria-hidden="true"></i> Audit</a>
					</li>
					<?php endif ?>
					<li class="nav-item">
						<a class="nav-link" href="./script/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
					</li>
				</ul>
        </div>
    </nav>
    
<!-- Contents -->
	<div class="container-fluid">
		<div class="row">
			<!-- Side Panel -->
			<nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
				<ul class="nav nav-pills flex-column">
					<!-- GUEST AND ADMIN ACCESS -->
					<li class="nav-item">
						<a class="nav-link" id="overview" href="./controlpanel"><i class="fa fa-home" aria-hidden="true"></i> Overview</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" id="reserve" href="#"><i class="fa fa-bed" aria-hidden="true"></i> Reservations <span class="sr-only">(current)</span></a>
					</li>
					<?php if($user_level == 5): ?>
					<li class="nav-item">
						<a class="nav-link" href="./controlpanel-02"><i class="fa fa-suitcase" aria-hidden="true"></i> History</a>
					</li>
					<?php endif ?>
				</ul>
				<?php if($user_level == 1): ?>
				<ul class="nav nav-pills flex-column">
					<!-- ADMIN ONLY ACCESS -->
					<li class="nav-item">
						<a class="nav-link" href="./controlpanel-03"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add/Edit Rooms</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="./controlpanel-04"><i class="fa fa-credit-card" aria-hidden="true"></i> Payments</a>
					</li>
				</ul>
				<?php endif ?>
				<ul class="nav nav-pills flex-column">
					<li class="nav-item">
						<a class="nav-link" href="./controlpanel-05"><i class="fa fa-cog" aria-hidden="true"></i> Account</a>
					</li>
					<?php if($user_level == 1): ?>
					<li class="nav-item">
						<!-- ADMIN ONLY -->
						<a class="nav-link" href="./controlpanel-06"><i class="fa fa-file-text-o" aria-hidden="true"></i> Audit</a>
					</li>
					<?php endif ?>
					<li class="nav-item">
						<a class="nav-link" href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
					</li>
				</ul>
			</nav>
			
			<!-- Page Content -->
			<!-- Dynamic Content below. Jquery heavy -->
			<main class="col-sm-9 ml-sm-auto col-md-10 pt-3">
				<div id="content-page">
					<div class="container">
						<div class="row">&nbsp;</div>
						<div class="row">
        			    	<div id="form-error-text" class="text-danger text-center"></div>
            				<div id="form-success-text" class="text-success text-center"></div>
    						<form class="checkout-room">
    							<h1 class="form-signin-heading">Reserve Room</h1>
    							<input type='hidden' readonly class='form-control'  name='uid' value='<?php echo $user_id; ?>'>
    							<input type='hidden' readonly class='form-control' name='room-id' value='<?php echo $room_id; ?>'>
    							<input type='hidden' readonly class='form-control' name='transactionId' id="transactionId" value="<?php echo $invoiceId; ?>">
    							<div class="form-group">
    								<label for="user-name">Signed in as: </label>
<?php 
$sql = "SELECT lname, fname FROM users WHERE idusers='" . $user_id . "';";
$rslt = $mConn->query($sql);
if($rslt->num_rows > 0) {
    while($row=$rslt->fetch_assoc()) {
        $user_name = strtoupper($row['lname']) . ", " . strtoupper($row['fname']);
        echo "\t\t\t\t\t\t\t\t\t\t<input type='text' readonly class='form-control' id='user-name' value='" . $user_name . "'>\n";
    }
}
?>
    							</div>
    							<div class="form-group">
    								<label for="room-type">Selected Room</label>
    								<select id="room-type" class="form-control" disabled>
    									<option selected>Undefined Room</option>
<?php 
$sql_room = "SELECT rooms.roomID, room_type.room_type, rooms.roomName, rooms.roomType FROM rooms, room_type WHERE rooms.roomType = room_type.idroom_type;";
$rslt = $mConn->query($sql_room);
if($rslt->num_rows > 0) {
    while($row=$rslt->fetch_assoc()) {
        if($room_id == $row['roomID']) {
            echo "\t\t\t\t\t\t\t\t\t\t<option value='" . $row['roomID'] . "' selected>" . $row['roomName'] . "</option>\n";
        } else {
            echo "\t\t\t\t\t\t\t\t\t\t<option value='" . $row['roomID'] . "'>" . $row['roomName'] . "</option>\n";
        }
    }
} else {
    echo "<option value='0' selected>Error fetching data</option>\n";
}
?>
    								</select>
    							</div>
    							<div class="form-row">
    								<div class="col-md-4">
                                        <label for="select-date">Select Date: </label>
                                        <input type="text" class="form-control" id="select-date" name="reserve-date" placeholder="Select Date.." readonly>
                                        <small class="form-text text-muted">Due to some limitations, same day reservation is not available. To check availability for today, contact us. </small>
    								</div>
    								<div class="col-md-4">
    									<label for="select-duration">Duration of stay: </label>
    									<select id="select-duration" name="select-duration" class="form-control">
    										<option value="6" selected>6 Hours</option>
    										<option value="12">12 Hours</option>
    										<option value="24">24 Hours</option>
    									</select>
    								</div>
    								<div class="col-md-4" id="select-day-form">
    									<label for="select-day">Time</label>
    									<select id="select-day" name="select-day" class="form-control">
    										<option value="09:00:00" selected>Morning (9AM)</option>
    										<option value="15:00:00">Afternoon (3PM)</option>
    									</select>
    								</div>
    							</div>
    							<div class="form-group row">
    								<label for="reservation-fee" class="col-sm-3">Online reservation fee: </label>
    								<input type="text" readonly class="col form-control" id="reservation-fee" value="600">
    							</div>
    							<div class="form-group row">
    								<label for="room-price" class="col-sm-3">Room Price: </label>
    								<input type="text" readonly class="col form-control" id="room-price" value="0">
    							</div>
    							<div class="form-group row">
    								<label for="total-amount" class="col-sm-3">Total Amount: </label>
    								<input type="text" readonly class="col form-control" id="total-amount" name="amount" value="0">
    							</div>
    							<div class="form-group row d-flex justify-content-end">
    								<button class="btn btn-primary btn-block btn-checkout" type="submit">Proceed</button>
    							</div>
    						</form>
						</div>
						<div class="row">&nbsp;</div>
					</div>
				</div>
			</main>			
		</div>
	</div>

<div class="modal fade modal-end" tabindex="-1" role="dialog" aria-labelledby="end-modal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
    	<div class="modal-content">
    		<div class="modal-header">
    			<h5 class="modal-title modal-end-title">Confirmation</h5>
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
    		</div>
    		<div class="modal-body">
    			<div class="modal-end-text"></div>
    		</div>
    	</div>
    </div>
</div>

<div class="modal fade modal-print" tabindex="-1" role="dialog"aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reservation Success!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body print-modal">
                <!-- Print Content -->
            </div>
            <div class="modal-footer">
            	<div class="sms-status"><small><span id="sms-status"></span></small></div>
            	<button type="button" class="btn btn-dark print-btn" aria-label="Print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-checkout" tabindex="-1" role="dialog"aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title checkout-title">Verify Reservation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body body-checkout">
                
            </div>
            <div class="modal-footer">
            	<div id="paypal-checkout"></div>
            </div>
        </div>
    </div>
</div>

<?php 
// Get Amount
$price6 = 0;
$price12 = 0;
$price24 = 0;

$sql = "Select roomID, price6, price12, price24 FROM room_price WHERE roomID='".$room_id."';";
$rslt = $mConn->query($sql);
if($rslt->num_rows > 0) {
    while($row=$rslt->fetch_assoc()) {
        $price6 = $row['price6'];
        $price12 = $row['price12'];
        $price24 = $row['price24'];
    }
}
?>
<!-- General Scripts -->
<script src="./script/jquery.min.js"></script>
<script src="./script/popper.min.js"></script>
<script src="./script/jquery-ui.min.js"></script>
<script src="./script/bootstrap.min.js"></script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script src="./script/js-reservation.js"></script>
<script type="text/javascript">
var room_price = ['<?php echo $price6; ?>','<?php echo $price12; ?>','<?php echo $price24; ?>'];
var dates = [];
<?php 
$sql = "SELECT DATE(reservationDate) AS fullDate FROM reservations LEFT JOIN rooms ON reservations.roomID = rooms.roomID WHERE reservations.roomID='".$room_id."' AND (SELECT COUNT(*) FROM reservations) >= rooms.roomQty AND DATE(reservations.reservationDate) BETWEEN CURDATE() + INTERVAL 2 DAY AND CURDATE() + INTERVAL 6 MONTH GROUP BY reservations.reservationDate;";
$rslt = $mConn->query($sql);
if($rslt->num_rows > 0) {
    while($row=$rslt->fetch_assoc()) {
        echo "dates.push('".$row['fullDate']."');";
    }
}
?>
$('#select-date').datepicker({
	minDate: "+2D",
	maxDate: "+6M",
	dateFormat: "yy-mm-dd",
	defaultDate: "+2D"
});
$("#select-date").datepicker("setDate","+2D");

if($("#select-duration").val() == "6") {
	$("#room-price").val(room_price[0]);
}
if($("#select-duration").val() == "12") {
	$("#room-price").val(room_price[1]);
}
if($("#select-duration").val() == "24") {
	$("#room-price").val(room_price[2]);
}
$("#total-amount").val(parseFloat($("#room-price").val()) + parseFloat($("#reservation-fee").val()));
$("#select-duration").change(function() {
	if($("#select-duration").val() == "24") {
		$("#select-day").prop("disabled", true);
	} else {
		$("#select-day").prop("disabled", false);
	}

	if($("#select-duration").val() == "6") {
		$("#room-price").val(room_price[0]);
	}
	if($("#select-duration").val() == "12") {
		$("#room-price").val(room_price[1]);
	}
	if($("#select-duration").val() == "24") {
		$("#room-price").val(room_price[2]);
	}
	$("#total-amount").val(parseFloat($("#room-price").val()) + parseFloat($("#reservation-fee").val()));
});

</script>
</body>
</html>
<?php else: ?>
<?php header("Location: ./login"); ?>
<?php endif ?>
<?php 
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // request 30 minates ago
    session_destroy();
    session_unset();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time
?>