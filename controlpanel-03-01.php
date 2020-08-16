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
						<button type="button" class="btn btn-info" id="add-room-btn">Add Room</button>&nbsp;<button type="button" class="btn btn-info disabled" id="edit-room-btn">Edit Room</button>
						<div class="container">
							<div class="row">&nbsp;</div>
							<div class="row"><h3>Edit Rooms</h3></div>
							<div class="row">
								<table class="table table-hover table-responsive-md table-inverse">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Room ID</th>
                                            <th scope="col">Room Name</th>
                                            <th scope="col">Room Type</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php 
$sql = "SELECT rooms.roomID, rooms.roomName, rooms.roomDesc, rooms.roomType AS roomtypeID, (SELECT room_type.room_type FROM room_type WHERE rooms.roomType=room_type.idroom_type) AS roomType, rooms.roomQty, (SELECT price6 FROM room_price WHERE room_price.roomID = rooms.roomID) AS price6, (SELECT price12 FROM room_price WHERE room_price.roomID = rooms.roomID) AS price12, (SELECT price24 FROM room_price WHERE room_price.roomID = rooms.roomID) AS price24, placeholderID FROM rooms WHERE rooms.deleted='0';";
$result = $mConn->query($sql);
if($result->num_rows > 0) {
    while($row=$result->fetch_assoc()) {
        if($result->num_rows <= 1) {
            // Disable delete if only 1 room is available
            echo "\t\t\t\t\t\t\t\t\t\t\t\t<tr>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<th scope='row'>".$row['roomID']."</th>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<td>".$row['roomName']."</td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<td>".$row['roomType']."</td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<td>".$row['roomQty']."</td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<td><a href='#edit' class='btn btn-link btn-edit-custom' id='edit-room-table' data-id='".$row['roomID']."' data-placeholder='".$row['placeholderID']."' data-roomname='".$row['roomName']."' data-roomdesc='".$row['roomDesc']."' data-roomtype='".$row['roomtypeID']."' data-qty='".$row['roomQty']."' data-price6='".$row['price6']."' data-price12='".$row['price12']."' data-price24='".$row['price24']."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;<a href='#' class='btn btn-link btn-del-custom disabled' id='del-room-table' data-id='".$row['roomID']."' data-name='".$row['roomName']."'><i class='fa fa-trash' aria-hidden='true'></i></a></td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t</tr>\n";
        } else {
            echo "\t\t\t\t\t\t\t\t\t\t\t\t<tr>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<th scope='row'>".$row['roomID']."</th>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<td>".$row['roomName']."</td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<td>".$row['roomType']."</td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<td>".$row['roomQty']."</td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t\t<td><a href='#edit' class='btn btn-link btn-edit-custom' id='edit-room-table' data-id='".$row['roomID']."' data-placeholder='".$row['placeholderID']."' data-roomname='".$row['roomName']."' data-roomdesc='".$row['roomDesc']."' data-roomtype='".$row['roomtypeID']."' data-qty='".$row['roomQty']."' data-price6='".$row['price6']."' data-price12='".$row['price12']."' data-price24='".$row['price24']."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;<a href='#' class='btn btn-link btn-del-custom' id='del-room-table' data-id='".$row['roomID']."' data-name='".$row['roomName']."'><i class='fa fa-trash' aria-hidden='true'></i></a></td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t\t</tr>\n";
        }
    }
}
?>
                                    </tbody>
            					</table>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12" id="edit">
	            			    	<div id="form-error-text" class="text-danger text-center"></div>
                    				<div id="form-success-text" class="text-success text-center"></div>
									<form id="edit-room-form" class="edit-room-form">
										<input type="hidden" name="form-type" value="edit">
										<input type="hidden" name="uid" value="<?php echo $user_id; ?>">
										<input type="hidden" name="room-id" id="form-room-id">
										<div class="form-group">
											<label for="room-img">Select Image: </label>
											<select class="image-picker form-control" name="room-img" id="room-img">
<?php 
$sql = "SELECT * FROM placeholders;";
$result = $mConn->query($sql);
if($result->num_rows > 0) {
    while($row=$result->fetch_assoc()) {
        $img_name = $row['placeholder_name'];
        $img_id = $row['idplaceholder'];
        echo "\t\t\t\t\t\t\t\t\t\t\t\t<option data-img-src='./media/placeholder/".$img_name.".jpg' data-image-alt='" . $img_name . "' value='" . $img_id . "'>" . $img_name . "</option>\n";
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
											<select id="room-type" name="room-type" class="form-control disabled">
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
    											<input type="text" placeholder="Room Name" name="room-name" id="room-name" class="form-control" readonly>
    										</div>
    										<div class="form-group col-md-6">
    											<label for="room-quantity">Room Availability: </label>
    											<input type="text" placeholder="Availability of rooms" name="room-available" id="room-quantity" class="form-control" required>
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
										<button type="submit" class="btn btn-success btn-update-submit">Update Room</button>
									</form>
									<br><br>
								</div>
							</div>
						</div>
				</div>
<!-- Modal Dialog -->
<div class="modal fade modal-loading" tabindex="-1" role="dialog" aria-labelledby="pleasewait-modal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <p id="form-wait-text" class="bg-info text-white text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span> &nbsp;</p>
    </div>
</div>

<div class="modal fade" id="delete-row-modal" tabindex="-1" role="dialog" aria-labelledby="delete-row-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title">Confirm Delete</div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
			</div>
			<div class="modal-body">
				<p id="modal-delete-text">Are you sure you want to delete the following room <strong><span id="modal-table-name"></span></strong> with Room ID <span id="modal-table-id"></span>?</p>
				<form class="form-delete">
					<input type="hidden" name="form-type" value="delete">
					<input type="hidden" name="uid" value="<?php echo $user_id; ?>">
					<input type="hidden" name="room-id" id="modal-form-id">
					<input type="hidden" name="room-name" id="modal-form-name">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" aria-label="Cancel" class="btn btn-secondary" id="delete-modal-cancel">Cancel</button>
				<button type="button" class="btn btn-danger btn-submit-del"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
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

<div class="modal fade modal-success" tabindex="-1" role="dialog" aria-labelledby="pleasewait-modal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
          	<div class="modal-body">
          		<i class="fa fa-check" aria-hidden="true"></i> Successfully Edited Room! Please wait while we process changes.
          	</div>
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
<script src="script/image-picker.min.js"></script>
<script src="script/jquery.validate.min.js"></script>
<script type="text/javascript">
	$("#add-room-btn").click(function() {
		window.open('./controlpanel-03', '_self');
	});
	$("#edit-room-form").hide();
	$("#delete-row-modal").modal('hide');
	$(".image-modal").modal("hide");
	$("#room-img").imagepicker();
	$(".btn-image-picker").click(function() {
		$(".image-modal").modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	// Edit functions
	$(".btn-edit-custom").click(function() {
		clearFields();
		$("#room-img").val($(this).data("placeholder"));
		$("#room-name").val($(this).data("roomname"));
		$("#room-quantity").val($(this).data("qty"));
		$("#room-desc").val($(this).data("roomdesc"));
		$("#price-6h").val($(this).data("price6"));
		$("#price-12h").val($(this).data("price12"));
		$("#price-1d").val($(this).data("price24"));
		$("#form-room-id").val($(this).data("id"));
		$("#edit-room-form").show();
	});
	$(".edit-room-form").validate({
		errorPlacement: function(error, element) {
			error.appendTo();
		},
		errorClass: "is-invalid",
		submitHandler: function(form) {
			$(".edit-room-form button").addClass("disabled");
			$(".edit-room-form input").prop("disabled",true);
			$(".modal-loading").modal("toggle");
			$(".edit-room-form input").prop("disabled",false);
			$.ajax({
				type: "POST",
				url: "./script/room-management.php",
				data: $(".edit-room-form").serialize(),
				success: function(data) {
					if(data.status=="success") {
						$(".modal-success").modal("toggle");
						setTimeout(function() { location.reload(); }, 4000);
					} else {
						$("#form-error-text").html(data.msg);
						$("#form-error-text").fadeIn(400).delay(3000).fadeOut(800);
					}
					
				},
				error: function(data) {
					console.log("Error in establishing AJAX");
					$("#form-error-text").html("Error connecting to server. Please try again later.");
					$("#form-error-text").fadeIn(400).delay(3000).fadeOut(800);
				},
				complete: function(data) {
					console.log(data);
					$(".modal-loading").modal("toggle");
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

	// Delete functions
	$(".btn-del-custom").click(function() {
		clearFields();
		$("#edit-room-form").hide();
		$("#modal-table-name").html($(this).data("name"));
		$("#modal-table-id").html($(this).data("id"));
		$("#modal-form-id").val($(this).data("id"));
		$("#modal-form-name").val($(this).data("name"));
		$("#delete-row-modal").modal('show');
	});

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
	$(".btn-submit-del").click(function(e) {
		e.preventDefault();
		console.log($(".form-delete input[name=form-type]").val());
		console.log($(".form-delete input[name=uid]").val());
		console.log($(".form-delete input[name=room-id]").val());
		$.ajax({
			type: "POST",
			url: "./script/room-management.php",
			data: $(".form-delete").serialize(),
			success: function(data) {
				console.log("Success connecting to delete ajax");
			},
			error: function(data) {
				console.log("Error in connecting to delete ajax");
			},
			complete: function(data) {
				console.log(data);
				$("#delete-row-modal").modal('hide');
				location.reload();
			}
		});
	});

	function clearFields() {
		$("#modal-form-id").val("");
		$("#room-name").val("");
		$("#room-quantity").val("");
		$("#room-desc").val("");
		$("#price-6h").val("");
		$("#price-12h").val("");
		$("#price-1d").val("");
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