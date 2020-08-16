<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['uid'];
$user_level = $_SESSION['level']; // 1-Admin 5-Guest 0-Unknown
require("./script/sqlConnect.php");
?>
<?php if(!empty($user_id)): ?>
<!DOCTYPE html>
<html lang="eng">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
	<title>Dashboard - Add/Edit Rooms - Play N' Display Inn</title>
	<link rel="stylesheet" href="style/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="style/bootstrap.min.css">
	<link rel="stylesheet" href="style/jquery-ui.min.css">
	<link rel="stylesheet" href="style/image-picker.css">
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
						<a class="nav-link active" href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add/Edit Rooms <span class="sr-only">(current)</span></a>
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
						<a class="nav-link" href="./controlpanel-02"><i class="fa fa-suitcase" aria-hidden="true"></i> History</a>
					</li>
					<?php endif ?>
				</ul>
				<?php if($user_level == 1): ?>
				<ul class="nav nav-pills flex-column">
					<!-- ADMIN ONLY ACCESS -->
					<li class="nav-item">
						<a class="nav-link active" href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add/Edit Rooms <span class="sr-only">(current)</span></a>
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
						<button type="button" class="btn btn-info disabled" id="add-room-btn">Add Room</button>&nbsp;<button type="button" class="btn btn-info" id="edit-room-btn">Edit Room</button>
						<div class="container">
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12" id="add-room">
		            			    <div id="form-error-text" class="text-danger text-center"></div>
                    				<div id="form-success-text" class="text-success text-center"></div>
									<form id="add-room-form" class="form-add">
										<h3 class="form-signin-heading">Add Room</h3>
										<br>
										<input type="hidden" name="form-type" value="add">
										<input type="hidden" name="uid" value="<?php echo $user_id; ?>">
										<div class="form-group">
											<label for="room-img">Select Image: </label>
											<select class="image-picker form-control" id="room-img" name="room-img">
<?php 
$sql = "SELECT * FROM placeholders;";
$result = $mConn->query($sql);
if($result->num_rows > 0) {
    while($row=$result->fetch_assoc()) {
        $img_name = $row['placeholder_name'];
        $img_id = $row['idplaceholder'];
        echo "\t\t\t\t\t\t\t\t\t\t\t\t<option data-img-src='./media/placeholder/$img_name.jpg' data-image-alt='$img_name' value='$img_id'>$img_name</option>\n";
    }
} else {
    echo "<option data-image-alt='null-error-db' value='1'>null-error-db</option>";
}
?>
											</select>
											<a href="#" class="btn btn-link btn-image-picker">..or upload a new image.</a>
										</div>
										<div class="form-group">
											<label for="room-type">Room Type: </label>
											<select id="room-type" class="form-control" name="room-type">
<?php 
$sql = "SELECT * FROM room_type;";
$result = $mConn->query($sql);

if($result->num_rows > 0) {
    $ctr = 0;
    while($row=$result->fetch_assoc()){
        if($ctr==0) {
            echo "\t\t\t\t\t\t\t\t\t\t\t\t<option value='" . $row['idroom_type'] . "' selected>" . $row['room_type'] . "</option>\n";
        } else {
        echo "\t\t\t\t\t\t\t\t\t\t\t\t<option value='" . $row['idroom_type'] . "'>" . $row['room_type'] . "</option>\n";
        }
        $ctr++;
    }
} else {
    echo "\t\t\t\t\t\t\t\t\t\t\t\t<option value='1'>Server returned null</option>\n";
}
?>
											</select>
										</div>
										<div class="form-row">
											<div class="form-group col-md-6">
    											<label for="room-name">Room Name: </label>
    											<input type="text" placeholder="Room Name" name="room-name" id="room-name" class="form-control" required autofocus>
    										</div>
    										<div class="form-group col-md-6">
    											<label for="room-quantity">Room Availability: </label>
    											<input type="text" placeholder="Availability of rooms" id="room-quantity" name="room-quantity" class="form-control" required>
    											<small id="room-help" class="form-text text-muted">Must contain only numbers. No letters, spaces or special characters. Number 0 is not valid.</small>
    										</div>
										</div>
										<div class="form-group">
											<label for="room-desc">Description: </label>
											<input type="text" placeholder="Room Description" id="room-desc" name="room-desc" class="form-control" required>
										</div>
										<div class="form-row">
    										<div class="form-group col-md-4">
    											<label for="price-6h">Price for 6 hours stay: </label>
    											<input type="text" placeholder="Price for 6 hours" id="price-6h" name="price-6h" class="form-control" required>
    											<small id="room-help" class="form-text text-muted">Must contain only numbers. Decimal point is allowed. Prices are already indicated in Philippine pesos.</small>
    										</div>
    										<div class="form-group col-md-4">
    											<label for="price-12h">Price for 12 hours stay: </label>
    											<input type="text" placeholder="Price for 12 hours" id="price-12h" name="price-12h" class="form-control" required>
    											<small id="room-help" class="form-text text-muted">Must contain only numbers. Decimal point is allowed. Prices are already indicated in Philippine pesos.</small>
    										</div>
    										<div class="form-group col-md-4">
    											<label for="price-1d">Price for 24 hours stay: </label>
    											<input type="text" placeholder="Price for 1 day" id="price-1d" name="price-24h" class="form-control" required>
    											<small id="room-help" class="form-text text-muted">Must contain only numbers. Decimal point is allowed. Prices are already indicated in Philippine pesos.</small>
    										</div>
										</div>
										<button type="submit" class="btn btn-success btn-submit">Add Room</button>
									</form>
									
									<br><br>
								</div>
							</div>
						</div>
				</div>
			</main>			
		</div>
	</div>

<div class="modal fade modal-loading" tabindex="-1" role="dialog" aria-labelledby="pleasewait-modal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <p id="form-wait-text" class="bg-info text-white text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span> &nbsp;</p>
    </div>
</div>

<div class="modal fade modal-success" tabindex="-1" role="dialog" aria-labelledby="pleasewait-modal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
          	<div class="modal-body">
          		<i class="fa fa-check" aria-hidden="true"></i> Successfully Added Room! Please wait while we redirect you back to your dashboard.
          	</div>
          </div>
    </div>
</div>

<div class="modal fade image-modal" tabindex="-1" role="dialog"aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload an image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="image-upload">
                    <div class="input-group">
                    	<label for="image-upload-input"></label>
                    	<input type="file" class="form-control-file" id="image-upload-input" name="image-upload-input">
                    </div>
                    <p class="text-muted"><small>* 1MB MAX. Supported image type: jpg,png or gif.</small></p>
                    <button type="submit" class="btn btn-link">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
	
<!-- General Scripts -->
<script src="script/jquery.min.js"></script>
<script src="script/jquery-ui.min.js"></script>
<script src="script/jquery.validate.min.js"></script>
<script src="script/popper.min.js"></script>
<script src="script/bootstrap.min.js"></script>
<script src="script/image-picker.min.js"></script>
<script src="script/jquery.validate.min.js"></script>
<script type="text/javascript">
	$("#edit-room-btn").click(function() {
		window.open('./controlpanel-03-01', '_self');
	});
	$(".image-modal").modal("hide");
	$('.modal-loading').modal('hide');
	$("#room-img").imagepicker();
	$(".btn-image-picker").click(function() {
		$(".image-modal").modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	// Functions
	$("#add-room-form").validate({
		errorPlacement: function(error, element) {
			error.appendTo();
		},
		errorClass: "is-invalid",
		submitHandler: function(form) {
			$(".modal-loading").modal("toggle");
			$.ajax({
				type: "POST",
				url: "./script/room-management.php",
				data: $(form).serialize(),
				success: function(data) {
					console.log("Successfully established AJAX");
					console.log(data);
					if(data.status=="success") {
						$(".modal-success").modal("toggle");
						setTimeout(function() {
							window.location.replace("./controlpanel");
						}, 3000);
					} else {
						$("#form-error-text").html(data.msg);
						$("#form-error-text").fadeIn(400).delay(4000).fadeOut(800);
					}
				},
				complete: function(data) {
					console.log(data);
					$(".modal-loading").modal('toggle');
				}
				
			});
		}
	});
	$("#room-quantity").rules("add", { digits: true });
	$("#price-6h").rules("add", { number: true });
	$("#price-12h").rules("add", { number: true });
	$("#price-24h").rules("add", { number: true });
	$("#room-desc").rules("add", { minlength: 5 });
	$("#room-name").rules("add", { minlength: 5 });
	
	$("#image-upload").submit(function(e) {
		e.preventDefault();
		var img_form = e.target;
		var img_data = new FormData(img_form);
		$.ajax({
			url: "./script/image-upload.php",
			type: "POST",
			dataType: "text",
			cache: false,
			contentType: false,
			processData: false,
			data: img_data,
			complete: function(ee) {
				console.log(ee);
				$(".image-modal").modal("hide");
				$("#image-upload-input").val("");
				location.reload();
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