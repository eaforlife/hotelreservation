<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['uid'];
$user_level = $_SESSION['level']; // 1-Admin 5-Guest 0-Unknown
require("./script/sqlConnect.php");

$security = 0; // 1 = required to enter security; 0 = security question already set.

?>
<?php if(!empty($user_id)): ?>
<!DOCTYPE html>
<html lang="eng">

<?php 
// Check if account does not have security question
$sql = "SELECT securityid FROM users WHERE idusers='$user_id';";
$rslt = $mConn->query($sql);
if($rslt->num_rows > 0) {
    while($row = $rslt->fetch_assoc()) {
        if($row['securityid']=="0") {
            $security = 1;
        } else {
            $security = 0;
        }
    }
}
?>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
	<title>Dashboard - Play N' Display Inn</title>
	<link rel="stylesheet" href="./style/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="./style/bootstrap.min.css">
	<link rel="stylesheet" href="./style/jquery-ui.min.css">
	<link rel="stylesheet" href="./style/playndisplay.dashboard.css">
	<style>
	.donut-scroll {
      overflow: scroll;
      overflow-x: scroll;
      overflow-y:hidden
    }
    .slide-row {
        background: #1C2833;
    }
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
						<a class="nav-link active" id="overview" href="#"><i class="fa fa-home" aria-hidden="true"></i> Overview <span class="sr-only">(current)</span></a>
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
						<a class="nav-link" href="./script/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
					</li>
				</ul>
        </div>
    </nav>
    
<!-- Contents -->
	<div class="container-fluid container-custom">
		<div class="row">
			<!-- Side Panel -->
			<nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
				<ul class="nav nav-pills flex-column">
					<!-- GUEST AND ADMIN ACCESS -->
					<li class="nav-item">
						<a class="nav-link active" id="overview" href="#"><i class="fa fa-home" aria-hidden="true"></i> Overview <span class="sr-only">(current)</span></a>
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
						<a class="nav-link" href="./script/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
					</li>
				</ul>
			</nav>
			
			
			<!-- Page Content -->
			<!-- Dynamic Content below. Jquery heavy -->
			<main class="col-sm-9 ml-sm-auto col-md-10 pt-3">
				<!-- Admin Overview - to be transferred to a PHP DOM -->
				<div id="content-page">
					<h3>Welcome to Play N' Display Online Portal</h3>
					<h6>To get started, navigate through the available options on the left!</h6>
					<hr>
					<div class="container-fluid">
						<div class="row slide-row">
							<div class="d-flex justify-content-md-center col-md-12">
								<div id="dashboard-hotel-preview" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                        	<img class="d-block w-100 img-slide" src="./media/img/dashboard-asset-0.jpg" alt="First slide">
                                        </div>
                                        <div class="carousel-item">
                                        	<img class="d-block w-100 img-slide" src="./media/img/dashboard-asset-1.jpg" alt="Second slide">
                                        </div>
                                        <div class="carousel-item">
                                        	<img class="d-block w-100 img-slide" src="./media/img/dashboard-asset-2.jpg" alt="Third slide">
                                        </div>
                                        <div class="carousel-item">
                                        	<img class="d-block w-100 img-slide" src="./media/img/dashboard-asset-3.jpg" alt="Fourth slide">
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
                    
					<hr>
					
					<!-- ADMIN ONLY -->
					<?php if($user_level == 1): ?>
					<div class="container">
						<div class="row">&nbsp;</div>
						<div class="row">
							<h5>Room Availability Today</h5>
							<div class="container-fluid">
								<div class="donut-scroll">
									<div id="room-avail-today"></div>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<h5>Room Availability Tomorrow</h5>
							<div class="container-fluid">
    							<div class="donut-scroll">
    								<div id="room-avail-tomorrow"></div>
    							</div>
							</div>
						</div>
						<hr>
						<div class="row">&nbsp;</div>
						<!-- Check Transaction -->
						<div class="row">
							<div class="col-md-12">
    							<form class="check-transact">
    								<input type="hidden" name="status" value="transact">
    								<h5 class="form-signin-heading">Transaction Look Up</h5>
    								<div class="form-group row">
    									<label for="input-transactId" class="col-md-2 col-form-label">Transaction ID: </label>
    									<div class="col-md-8"><input type="text" class="form-control" placeholder="Transaction ID" name="transactId"></div>
    									<div class="col-md-2"><button type="submit" class="btn btn-link btn-check-transact btn-sm"><i class="fa fa-search" aria-hidden="true"></i> Search</button></div>
    								</div>
    							</form>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12"><div id="transact-content"></div></div>
						</div>
						<div class="row">&nbsp;</div>
						<!-- Check Availability -->
						<div class="row">
							<div class="col-md-12">
    							<form class="check-avail">
    								<input type="hidden" name="status" value="custom">
    								<h5 class="form-signin-heading">Availability Look Up</h5>
    								<div class="form-group row">
    									<label for="input-transactId" class="col-md-2 col-form-label">Select Date: </label>
    									<div class="col-md-8"><input type="text" class="form-control" placeholder="Select Date" name="reserveDate" id="reserveDate" readonly></div>
    									<div class="col-md-2"><button type="submit" class="btn btn-link btn-check-avail btn-sm"><i class="fa fa-search" aria-hidden="true"></i> Search</button></div>
    								</div>
    							</form>
							</div>
						</div>
						<div class="row">&nbsp;</div>
					</div>
					<div class="modal fade modal-avail" tabindex="-1" role="dialog" aria-labelledby="modal-avail" aria-hidden="true">
					<!-- Modal To Check Availability -->
                        <div class="modal-dialog modal-lg">
                        	<div class="modal-content">
                        		<div class="modal-header">
                        			<h5 class="modal-title">Availability Check</h5>
                        			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                        		</div>
                        		<div class="modal-body">
                        			<div id="avail-content"></div>
                        			<div id="avail-content-2"></div>
                        		</div>
                        	</div>
                        </div>
                    </div>
					<?php endif ?>
				</div>
			</main>			
		</div>
	</div>
<?php if($security=="1"): ?>
<!-- /* Security Question not set on account. This will disappear after being set */ -->
<div class="modal fade modal-set-security" tabindex="-1" role="dialog" aria-labelledby="modal-set-security" aria-hidden="true" data-dismiss="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
          	  <div class="modal-header">
          	  	<h5 class="modal-title">Information</h5>
        	  </div>
        	  <div class="modal-body">
        	  	<h6 class="text-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> We have updated our account security</h6>
        	  	<p><small>As a precaution, kindly update the following fields below. This will be used in the near future for security purposes.</small></p>
        	  	<form class="security-form">
        	  		<input type="hidden" name="form-type" value="security">
        	  		<input type="hidden" name="modal-id" value="<?php echo $user_id; ?>">
        	  		<p class="text-warning"><small><span id="error-text"></span></small></p>
        	  		<div class="form-group margin-bottom-sm">
                        <label for="security-question">Security Question: </label>
                        <select class="form-control form-control-sm" id="security-question" name="question" required>
                        	<?php 
                        	require("./script/sqlConnect.php");
                        	$sql = "SELECT * FROM security_template;";
                        	$ext = $mConn->query($sql);
                        	$ctr = 0;
                        	if($ext->num_rows > 0){
                        	    while($row = $ext->fetch_assoc()) {
                        	        if($ctr==0) {
                        	            echo "<option value='" . $row['securityID'] . "' selected>" . $row['security_question'] . "</option>";
                        	        } else {
                        	            echo "<option value='" . $row['securityID'] . "'>" . $row['security_question'] . "</option>";
                        	        }
                        	        $ctr++;
                        	    }
                        	}
                        	?>
                        </select>
                    </div>
                    <div class="form-group margin-bottom-sm">
                        <label for="security-answer">Security Answer: </label>
                        <input type="text" name="answer" id="security-answer" class="form-control form-control-sm" placeholder="Security Answer" required>
                    </div>
                    <button type="submit" class="btn btn-warning btn-security">Update</button>
        	  	</form>
        	  	
        	  	<div id="security-end" class="text-success"></div>
        	  </div>
    	  </div>
	</div>
</div>
<?php endif; ?>

<!-- General Scripts -->
<script src="script/jquery.min.js"></script>
<script src="script/jquery-ui.min.js"></script>
<script src="script/jquery.validate.min.js"></script>
<script src="script/popper.min.js"></script>
<script src="script/bootstrap.min.js"></script>
<?php if($security=="1"): ?>
<script src="script/js-control-security.js"></script>
<?php endif; ?>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
$('#dashboard-hotel-preview').carousel({
	  interval: 5000,
	  keyboard: false,
	  pause: false,
	  wrap: true
});

<?php if($user_level==1):?>
$('#reserveDate').datepicker({
	minDate: "today",
	maxDate: "+6M",
	dateFormat: "yy-mm-dd",
	defaultDate: "today"
});

$("#reserveDate").datepicker("setDate","today");

$("#reserveDate").change(function() {
	$(".btn-check-avail").click();
});
checkRoomAvail("today");
checkRoomAvail("tomorrow");

var autoRefresh;
var autoRefreshModal;

function checkRoomAvail(x) {
	// Today
	if(x=="today") {
		$.ajax({
			type: "POST",
			data: { "status": "current" },
			url: "./script/dashboard-core.php",
			success: function(data) {
				$("#room-avail-today").html(data);
			},
			complete: function(data) {
				console.log(data);
			}
		});
	}
	// Tomorrow
	if(x=="tomorrow") {
		$.ajax({
			type: "POST",
			data: { "status": "tomorrow" },
			url: "./script/dashboard-core.php",
			success: function(data) {
				$("#room-avail-tomorrow").html(data);
			},
			complete: function(data) {
				console.log(data);
			}
		});
	}
	// Custom
	if(x=="custom") {
		$.ajax({
			type: "POST",
			url: "./script/dashboard-core.php",
			data: $(".check-avail").serialize(),
			success: function(data) {
				$("#avail-content").html(data);
			},
			complete: function(data) {
				console.log(data);
			}
		});

	}
}

$(".check-transact").submit(function(e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: "./script/dashboard-core.php",
		data: $(this).serialize(),
		success: function(data) {
			console.log("success");
			$("#transact-content").html(data);
		},
		error: function(data) {
			console.log("error");
		},
		complete: function(data) {
			console.log(data);
			
		}
	});
});

$(".check-avail").submit(function(e) {
	e.preventDefault();
	$(".modal-avail").modal("toggle");
	checkRoomAvail("custom");
});

$('.modal-avail').on('show.bs.modal', function (e) {
	clearInterval(autoRefresh); // Stop interval on main page to save resources while modal is open
	autoRefreshModal = setInterval(function() {
		checkRoomAvail("custom");
	}, 5000);
});
$('.modal-avail').on('hide.bs.modal', function (e) {
	clearInterval(autoRefreshModal);
	autoRefresh = setInterval(function() {
		checkRoomAvail("today");
		checkRoomAvail("tomorrow");
	}, 5000);
});

autoRefresh = setInterval(function() {
	checkRoomAvail("today");
	checkRoomAvail("tomorrow");
}, 5000);

<?php endif;?>
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