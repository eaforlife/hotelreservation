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
    
	<title>Dashboard - Account Management - Play N' Display Inn</title>
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
						<a class="nav-link" href="./controlpanel-04"><i class="fa fa-credit-card" aria-hidden="true"></i> Payments</a>
					</li>
					<?php endif ?>
					<li class="nav-item">
						<a class="nav-link active" href="#"><i class="fa fa-cog" aria-hidden="true"></i> Account <span class="sr-only">(current)</span></a>
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
						<a class="nav-link" href="./controlpanel-04"><i class="fa fa-credit-card" aria-hidden="true"></i> Payments</a>
					</li>
				</ul>
				<?php endif ?>
				<ul class="nav nav-pills flex-column">
					<li class="nav-item">
						<a class="nav-link active" href="#"><i class="fa fa-cog" aria-hidden="true"></i> Account <span class="sr-only">(current)</span></a>
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
            <div class="modal fade modal-loading" tabindex="-1" role="dialog" aria-labelledby="pleasewait-modal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                      <p id="form-wait-text" class="bg-info text-white text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span> &nbsp;</p>
                </div>
            </div>
			<!-- Page Content -->
			<!-- Dynamic Content below. Jquery heavy -->
			<main class="col-sm-9 ml-sm-auto col-md-10 pt-3">
				<!-- Admin Overview - to be transferred to a PHP DOM -->
				<div id="content-page">
					<div class="container">
						<div class="row">&nbsp;</div>
						<div class="row">
							<div class="col-sm-12">
								<form id="edit-profile">
<?php 
$email = $fname = $lname = $contact = "";
$sql = "SELECT email, fname, lname, contact FROM users WHERE idusers='" . $user_id . "';";
$rslt = $mConn->query($sql);
if($rslt->num_rows > 0) {
    while($row=$rslt->fetch_assoc()) {
        $email = $row['email'];
        $fname = $row['fname'];
        $lname = $row['lname'];
        $contact = $row['contact'];
    }
}
?>
									<h3 class="form-signin-heading text-center">Account management</h3>
                					<br>
    								<div class="form-group row">
    									<label for="username" class="col-sm-2 col-form-label">Email: </label>
    									<input type="text" name="email" readonly class="col form-control" placeholder="noreply@email.com" value="<?php echo $email; ?>">
    								</div>
    								<div class="form-group row">
    									<label for="user-fname" class="col-sm-2 col-form-label">First Name: </label>
    									<input type="text" name="first-name" class="col-sm-4 form-control" placeholder="First Name" id="user-fname" value="<?php echo $fname; ?>">
    									<label for="user-lname" class="col-sm-2 col-form-label">Last Name: </label>
    									<input type="text" name="last-name" class="col-sm-4 form-control" placeholder="Last Name" id="user-lname" value="<?php echo $lname; ?>">
    								</div>
    								<div class="form-group row">
    									<label for="contact-phone" class="col-sm-3">Contact Number: </label>
    									<input type="text" name="contact" class="col-sm-9 form-control" id="contact-phone" value="<?php echo $contact; ?>">
    								</div>
    								<div class="form-group row">
    									<input type="hidden" name="form-type" value="edit">
    									<input type="hidden" name="uid" value="<?php echo $user_id; ?>">
    								</div>
    								<div class="form-group row">
    									<label for="password-old" class="col-sm-3">Current Password: </label>
    									<input type="password" name="cur-password" class="col-sm-9 form-control" id="password-old">
    								</div>
    								<div class="form-group row">
    									<label for="password-new" class="col-sm-3">New Password: </label>
    									<input type="password" name="new-password" class="col-sm-9 form-control" id="password-new" disabled>
    								</div>
    								<div class="form-group row">
    									<label for="password-new1" class="col-sm-3">Confirm New Password: </label>
    									<input type="password" name="confirm-password" class="col-sm-9 form-control" id="password-new1" disabled>
    								</div>
    								<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Update</button>
    							</form>
							</div>
						</div>
					</div>
				</div>
			<div class="row">&nbsp;</div>
            <div class="row">
            	<div class="col-md-12">
            		<div class="alert alert-danger" role="alert" id="form-error">
                    	<span id="form-error-text"></span>
                    </div>
                    <div class="alert alert-success" role="alert" id="form-success">
                    	<span id="form-success-text"></span>
                    </div>
            	</div>
            </div>	
			</main>
		</div>
	</div>
	
<!-- General Scripts -->
<script src="script/jquery.min.js"></script>
<script src="script/jquery-ui.min.js"></script>
<script src="script/popper.min.js"></script>
<script src="script/bootstrap.min.js"></script>
<script type="text/javascript">
	$("#form-error").hide();
	$("#form-success").hide();
	$('.modal-loading').modal('hide');
	
	// Functions
	$("#password-old").on("change keyup paste", function() {
		if($(this).val().length > 1) {
			$("#password-new").prop("disabled", false);
			$("#password-new1").prop("disabled", false);
		} else {
			$("#password-new").val("");
			$("#password-new1").val("");
			$("#password-new").prop("disabled", true);
			$("#password-new1").prop("disabled", true);
		}
	});
	$("#edit-profile").submit(function(e) {
		e.preventDefault();
		// Prevent double submissions
		$("#edit-profile input").prop("disabled", true);
		$("#edit-profile button").prop("disabled", true);
		$(".modal-loading").modal({
			backdrop: 'static',
			keyboard: false
		});
		$("#edit-profile input").prop("disabled", false);
		$("#edit-profile button").prop("disabled", false);
		$.ajax({
			type: "POST",
			url: "./script/validate-signup.php",
			data: $(this).serialize(),
			success: function(data) {
				if(data.status == 'error') {
					console.log("Error in submitting data");
					console.log(data);
					$("#password-old").val("");
					$("#password-new").val("");
					$("#password-new1").val("");
					$("#form-error-text").html(data.msg);
					$("#form-error").fadeIn(300).delay(3000).fadeOut(600);
				} else {
					console.log("Successfully submitted data");
					console.log(data);
					$("#form-success-text").html(data);
					$("#form-success").fadeIn(300).delay(4000).fadeOut(700, function () {
						window.location.replace("./script/logout");
					});
				}
				
			}
		});
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