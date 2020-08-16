<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['uid'];
$user_level = $_SESSION['level']; // 1-Admin 5-Guest 0-Unknown
require('./script/sqlConnect.php');
?>
<?php if(!empty($user_id)): ?>
<!DOCTYPE html>
<html lang="eng">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
	<title>Dashboard - Payment History - Play N' Display Inn</title>
	<link rel="stylesheet" href="style/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="style/bootstrap.min.css">
	<link rel="stylesheet" href="style/jquery-ui.min.css">
	<link rel="stylesheet" href="./style/playndisplay.dashboard.css">
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
						<a class="nav-link" id="reserve" href="./controlpanel-01"><i class="fa fa-bed" aria-hidden="true"></i> Reservations</a>
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
						<a class="nav-link active" href="#"><i class="fa fa-credit-card" aria-hidden="true"></i> Payments <span class="sr-only">(current)</span></a>
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
						<a class="nav-link" id="overview" href="./controlpanel"><i class="fa fa-home" aria-hidden="true"></i> Overview <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="reserve" href="./controlpanel-01"><i class="fa fa-bed" aria-hidden="true"></i> Reservations</a>
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
						<a class="nav-link active" href="#"><i class="fa fa-credit-card" aria-hidden="true"></i> Payments <span class="sr-only">(current)</span></a>
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
						<a class="nav-link" href="./script/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
					</li>
				</ul>
			</nav>
			
			<!-- Page Content -->
			<!-- Dynamic Content below. Jquery heavy -->
			<main class="col-sm-9 ml-sm-auto col-md-10 pt-3">
				<!-- Admin Overview - to be transferred to a PHP DOM -->
				<div id="content-page">
					<h3>Payment Dashboard</h3>
					<h6><a href="https://www.sandbox.paypal.com/" data-toggle='tooltip' title="This will redirect you to PayPal page.">To refund a transaction please click here!</a></h6>
					<div class="container">
						<div class="row">&nbsp;</div>
						<div class="row">
							<table class="table table-dark">
								<thead>
									<tr>
										<th scope="col">Transaction ID</th>
										<th scope="col">Room Name</th>
										<th scope="col">Username</th>
										<th scope="col">Amount Paid</th>
										<th scope="col">Transaction Date</th>
										<th scope="col">Options</th>
									</tr>
								</thead>
								<tbody>
<?php 
$sql_price = "SELECT roomID, price6, price12, price24 FROM room_price;";
$rslt_price = $mConn->query($sql_price);
$room_price = array();
if($rslt_price->num_rows > 0) {
    while($row=$rslt_price->fetch_assoc()) {
        $room_price[] = $row; // Get Prices for All rooms and store them to array for later use.
    }
}

$sql = "SELECT reservations.transactionID, reservations.roomID, (SELECT rooms.roomName FROM rooms WHERE rooms.roomID = reservations.roomID) AS roomName, (SELECT rooms.deleted FROM rooms WHERE rooms.roomID = reservations.roomID) AS deleted, (SELECT users.email FROM users WHERE users.idusers=reservations.userID) AS userName, reservations.duration, reservations.transactionDate, reservations.refundStatus, reservations.amountpaid, reservations.reservationDate FROM reservations ORDER BY reservations.transactionDate DESC;";
$rslt = $mConn->query($sql);
if($rslt->num_rows > 0) {
    while($row=$rslt->fetch_assoc()) {
        $total_amount;
        if($row['refundStatus'] == '1') echo "\t\t\t\t\t\t\t\t<tr class='table-danger'>\n";
        if($row['refundStatus'] == '0') echo "\t\t\t\t\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t\t\t\t\t<th scope='row'>" . $row['transactionID'] . "</th>\n";
        if($row['deleted']=='0') {
            echo "\t\t\t\t\t\t\t\t\t<td>" . $row['roomName'] . "</td>\n";
        } else {
            echo "\t\t\t\t\t\t\t\t\t<td><a href='#' data-toggle='tooltip' title='This room seems to be defunct.'><i class='fa fa-exclamation-circle delete-info' aria-hidden='true'></i> " . $row['roomName'] . "</a></td>\n";
        }
        echo "\t\t\t\t\t\t\t\t\t<td>" . $row['userName'] . "</td>\n";
        echo "\t\t\t\t\t\t\t\t\t<td>" . $row['amountpaid'] . "</td>\n";
        echo "\t\t\t\t\t\t\t\t\t<td>" . $row['transactionDate'] . "</td>\n";
        if($row['refundStatus'] == '0') {
            $today = date("Y-m-d");
            if(strtotime($row['reservationDate']) > $today) {
                echo "\t\t\t\t\t\t\t\t\t<td><a href='#' class='btn btn-link refund-btn' id='refund-btn' data-id='" . $row['transactionID'] . "' data-amt='" . $row['amountpaid'] . "'><i class='fa fa-trash-o' aria-hidden='true'></i></a></td>\n";
            } else {
                echo "\t\t\t\t\t\t\t\t\t<td><a href='#' data-toggle='tooltip' data-placement='bottom' title='Refund is not possible due to reservation date has already passed.'><i class='fa fa-trash-o' aria-hidden='true'></i></a></td>\n";
            }
        }
        if($row['refundStatus'] == '1') echo "\t\t\t\t\t\t\t\t\t<td>Refunded</td>\n";
        echo "\t\t\t\t\t\t\t\t</tr>\n";
    }
}
?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</main>
			<!-- Modal Delete -->
			<div class="modal" tabindex="-1" role="dialog" id="refund-modal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Refund Transaction</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            	<span aria-hidden="true">&times;</span>
                            </button>
						</div>
						<form id="refund-form">
    						<div class="modal-body">
    							<p>You are about to refund P <span id="refund-amount"></span> with transaction ID <span id="refund-ID"></span>.<br><small>Please note that this will only tag the transaction as "Refunded" for reference or archive purposes only. Should you wish to actually refund the transaction, <a href="https://www.sandbox.paypal.com/" data-toggle='tooltip' title="This will redirect you to PayPal page.">proceed to this link</a> before tagging this transaction as "Refunded".</small><br><br> Do you still wish to continue?
    							<input type="hidden" name="transactionID" id="transactionID">
    							<input type="hidden" name="uid" value="<?php echo $user_id; ?>" id="uid">
    						</div>
    						<div class="modal-footer">
    							<button type="button" class="btn btn-secondary" class="close" data-dismiss="modal" aria-label="Close">Cancel</button>
    							<button type="submit" class="btn btn-info">Refund</button>
    						</div>
						</form>
					</div>
				</div>
			</div>		
		</div>
	</div>
	
<!-- General Scripts -->
<script src="script/jquery.min.js"></script>
<script src="script/jquery-ui.min.js"></script>
<script src="script/popper.min.js"></script>
<script src="script/bootstrap.min.js"></script>
<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip()

	$(".refund-btn").click(function() {
		$("#refund-amount").html($(this).data("amt"));
		$("#refund-ID").html($(this).data("id"));
		$("#transactionID").val($(this).data("id"));
		$("#refund-modal").modal("show");
	});
	$("#refund-form").submit(function(e) {
		e.preventDefault();
		console.log("Checklist: uid-" + $("#uid").val() + " ID-" + $("#transactionID").val());
		console.log("Refund Form Submitted");
		$.ajax({
			type: "POST",
			url: "./script/refund-process.php",
			data: $(this).serialize(),
			success: function(data) {
				console.log("Submit success");
				console.log(data);
			},
			error: function(data) {
				console.log("Submit Error");
				console.log(data);
			}
		});
		
		$("#refund-modal").modal("toggle");
		location.reload();
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