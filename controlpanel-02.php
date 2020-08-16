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
    
	<title>Dashboard - History - Play N' Display Inn</title>
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
						<a class="nav-link active" href="#"><i class="fa fa-suitcase" aria-hidden="true"></i> History <span class="sr-only">(current)</span></a>
					</li>
					<?php endif ?>
					<?php if($user_level == 1): ?>
					<!-- ADMIN ONLY ACCESS -->
					<li class="nav-item">
						<a class="nav-link" href="./controlpanel-03"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add/Edit Rooms</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#"><i class="fa fa-credit-card" aria-hidden="true"></i> Payments</a>
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
						<a class="nav-link" id="reserve" href="./controlpanel-01"><i class="fa fa-bed" aria-hidden="true"></i> Reservations</a>
					</li>
					<?php if($user_level == 5): ?>
					<li class="nav-item">
						<a class="nav-link active" href="#"><i class="fa fa-suitcase" aria-hidden="true"></i> History <span class="sr-only">(current)</span></a>
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
						<a class="nav-link" href="./script/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
					</li>
				</ul>
			</nav>
			
			<!-- Page Content -->
			<!-- Dynamic Content below. Jquery heavy -->
			<main class="col-sm-9 ml-sm-auto col-md-10 pt-3">
				<!-- Admin Overview - to be transferred to a PHP DOM -->
				<div id="content-page">
					<h3>Reservation History</h3>
					
					<table class="table table-hover table-responsive-md table-inverse">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Transaction ID</th>
                                <th scope="col">Room Name</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Date</th>
                                <th scope="col">Option</th>
                            </tr>
                        </thead>
                        <tbody>
<?php 
// TODO deleted 
$sql = "SELECT reservations.transactionID, (SELECT rooms.roomName FROM rooms WHERE reservations.roomID = rooms.roomID) AS roomName, (SELECT rooms.deleted FROM rooms WHERE reservations.roomID = rooms.roomID) AS deleted, reservations.duration, reservations.transactionDate FROM reservations WHERE reservations.userID = '".$user_id."'  ORDER BY reservations.transactionDate DESC;";
$result = $mConn->query($sql);
if($result->num_rows > 0) {
    while($row=$result->fetch_assoc()) {
        $dt = new DateTime($row['transactionDate']);
        $convertedDt = $dt->format("jS M, Y");
        echo "\t\t\t\t\t\t\t\t\t\t<tr>\n";
        echo "\t\t\t\t\t\t\t\t\t\t\t<th scope='row'>" . $row['transactionID'] . "</th>\n";
        echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $row['roomName'] . "</td>\n";
        echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $row['duration'] . " Hours</td>\n";
        echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $convertedDt . "</td>\n";
        echo "\t\t\t\t\t\t\t\t\t\t\t<td><a href='#' data-id='" . $row['transactionID'] . "' class='btn btn-link btn-print'><i class='fa fa-print' aria-hidden='true'></i></a></td>\n";
        echo "\t\t\t\t\t\t\t\t\t\t</tr>\n";
    }
} else {
    echo "<br><h4>No results found. If you think this does not feel right, free to contact us.</h4><br>";
}
?>
                        </tbody>
					</table>
				</div>
			</main>			
		</div>
	</div>

<div class="modal fade modal-print" tabindex="-1" role="dialog"aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice Copy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body print-modal">
                <!-- Print Content -->
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-dark print-btn" aria-label="Print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
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
$(".btn-print").click(function() {
	var tID = $(this).data("id");
	modalPrint(tID);
});

$(".print-btn").click(function() {
	window.print();
});

function modalPrint(x) {
	$.post("./script/print-invoice.php", { transactionId: x, status: "success"}, function(response) {
		$(".print-modal").html(response);
		$(".modal-print").modal("toggle");
	});
}
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