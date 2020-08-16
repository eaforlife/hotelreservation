<?php
// Destroy whatever session stored
if (session_status() === PHP_SESSION_NONE) session_start();
session_destroy();
session_unset();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
	<title>Login</title>
	<link rel="stylesheet" href="./style/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="./style/bootstrap.min.css">
	<style>
	   body {
	       background-color: #EAEAEA;
	   }
	</style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    	<a class="navbar-brand" href="./">Play N' Display Inn Hotel</a>
    </nav>
	<!-- End of Navigation -->
	
	<!-- Content -->
	<div class="container-fluid mt-50">
		<div class="d-flex align-items-center justify-content-md-center" style="height:700px">
			
			<div class="row">
				<div class="col-md-12">
						<form class="form-signin">
							<input type="hidden" name="form-type" value="add">
                			<h3 class="form-signin-heading text-center">Register Profile</h3>
            			    <div id="form-error-text" class="text-danger text-center"></div>
                        	<div id="form-success-text" class="text-success text-center"></div>
                        	<br>
                            <div class="form-group margin-bottom-sm">
                                <label for="inputEmail" class="sr-only">Email: </label>
                                <input type="email" name="email" id="inputEmail" class="form-control form-control-sm" placeholder="Email address" required autofocus>
                            </div>
                            <div class="form-group margin-bottom-sm">
                                <label for="inputPassword" class="sr-only">Password: </label>
                                <input type="password" name="password" id="inputPassword" class="form-control form-control-sm" placeholder="Password" required>
                                <small class="text-muted">Password must be between of 6 to 23 characters.</small>
                            </div>
                            <div class="form-group margin-bottom-sm">
                                <label for="inputPassword2" class="sr-only">Confirm Password: </label>
                                <input type="password" name="password2" id="inputPassword2" class="form-control form-control-sm" placeholder="Confirm Password" required>
                            </div>
                            <div class="form-group margin-bottom-sm">
                                <label for="first-name" class="sr-only">First Name: </label>
                                <input type="text" name="first-name" id="first-name" class="form-control form-control-sm" placeholder="First Name" required>
                            </div>
                            <div class="form-group margin-bottom-sm">
                                <label for="first-name" class="sr-only">Last Name: </label>
                                <input type="text" name="last-name" id="last-name" class="form-control form-control-sm" placeholder="Last Name" required>
                            </div>
                            <div class="form-group margin-bottom-sm">
                                <label for="contact" class="sr-only">Contact Number: </label>
                                <input type="text" name="contact" id="contact" class="form-control form-control-sm" placeholder="Contact Number" required>
                            </div>
                            <div class="form-group margin-bottom-sm">
                                <label for="security-question" class="sr-only">Security Question: </label>
                                <select class="form-control-sm" id="security-question" name="question" required>
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
                                <label for="security-answer" class="sr-only">Security Answer: </label>
                                <input type="text" name="answer" id="security-answer" class="form-control form-control-sm" placeholder="Security Answer" required>
                            </div>
                            <br>
                            <button class="btn btn-primary btn-block btn-submit" type="submit">Register</button>
                		</form>
                    		
                		<p><a class="btn text-info text-center" href="./login"><small>If you already have an account click here to login.</small></a></p>
				</div>
			</div>
			
			<div class="modal fade modal-loading" tabindex="-1" role="dialog" aria-labelledby="pleasewait-modal" aria-hidden="true" data-dismiss="modal">
                <div class="modal-dialog modal-sm">
                      <p id="form-wait-text" class="bg-info text-white text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span> &nbsp;</p>
                </div>
            </div>
		</div>
	</div>
	<!-- End of Content -->
	
	<footer class="text-center mt-3" style="height:100px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">Play N' Display &copy; 2017. Powered by Bootstrap v4</div>
				<div class="col-md-12">Fairfields San Carlos Rizal St., San Carlos City, Pangasinan 2420</div>
			</div>
		</div>
	</footer>

<!-- Scripts should be placed below this point for optimized page load -->
<script src="./script/jquery.min.js"></script>
<script src="./script/popper.min.js"></script>
<script src="./script/jquery.validate.min.js"></script>
<script src="./script/bootstrap.min.js"></script>
<script src="./script/js-signup.js"></script>
</body>
</html>