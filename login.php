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
		<div class="d-flex align-items-center justify-content-md-center" style="height:600px">
			
			<div class="row">
				<div class="col-md-12">
						<form class="form-signin">
                			<h3 class="form-signin-heading text-center">Please Sign-In</h3>
            			    <div id="form-error-text" class="text-danger text-center"></div>
                        	<div id="form-success-text" class="text-success text-center"></div>
                        	<br>
                            <div class="input-group margin-bottom-sm">
                                <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                            </div>
                            <div class="input-group margin-bottom-sm">
                                <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
                            </div>
                            <br>
                            <button class="btn btn-primary btn-block btn-submit" type="submit">Sign in</button>
                		</form>
                		<br>
                		<div class="text-center">
                			<a class="text-info forgot-password" href="#"><small>Forgot Password?</small></a><br>
                			<a class="text-info" href="./signup"><small>If you don't have an account click here to sign up</small></a>
                		</div>
				</div>
			</div>
			
			
			<div class="modal fade modal-loading" tabindex="-1" role="dialog" aria-labelledby="pleasewait-modal" aria-hidden="true" data-dismiss="modal">
                <div class="modal-dialog modal-sm">
                      <p id="form-wait-text" class="bg-info text-white text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span> &nbsp;</p>
                </div>
            </div>
            <div class="modal fade modal-forgot-pass" tabindex="-1" role="dialog" aria-labelledby="modal-forgot-pass" aria-hidden="true" data-dismiss="modal">
                <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                      	  <div class="modal-header">
                      	  	<h5 class="modal-title">Reset Password</h5>
                    	  	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                    	  </div>
                    	  
                    	  <div class="modal-body">
                    	  	<div class="container-fluid">
                        	  	<form class="form-check-email">
                        	  		<input type="hidden" name="formType" value="check-email">
                    	  			<div class="form-group">
                    	  				<label for="check-email">Please enter your email address: </label>
                              			<input type="text" class="form-control form-control-sm" name="email" id="email" placeholder="Email Address">
                              			<small class="form-text text-danger email-error"></small>
                    	  			</div>
                              		<div class="form-group">
                              			<button type="submit" class="btn btn-info btn-check-email">Next</button>
                              		</div>
                              	</form>
                              	
                              	<form class="form-forgot-pass">
                              		<input type="hidden" name="formType" value="change-password">
                              		<input type="hidden" name="userid" id="userid">
                              		<input type="hidden" name="question" id="security-question-id">
                              		<div class="form-group">
                              			<p class="text-danger text-center"><small class="forgot-pass-error"></small></p>
                              		</div>
                              		<div class="form-group">
                              			<label for="security-answer" id="security-question">Security Question: </label>
                              			<input type="text" class="form-control form-control-sm" name="answer" id="security-answer" placeholder="Securiy Answer" required>
                              			<small class="form-text text-danger security-error"></small>
                              		</div>
                              		<div class="form-group">
                              			<label for="new-password">New Password: </label>
                              			<input type="password" class="form-control form-control-sm" name="pass1" id="new-password" placeholder="" required>
                              			<small class="form-text text-danger new-pass"></small>
                              		</div>
                              		<div class="form-group">
                              			<label for="new-password-1">Confirm New Password: </label>
                              			<input type="password" class="form-control form-control-sm" name="pass2" id="new-password-1" placeholder="" required>
                              			<small class="form-text text-danger new-pass-confirm"></small>
                              		</div>
                              		<button type="submit" class="btn btn-info btn-sm btn-newpass">Change Password</button>
                              	</form>
                              	
                              	<div id="end-modal-message" class="text-success"></div>
                          	</div>
                    	  </div>
                      </div>
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
<script src="./script/js-login.js"></script>
</body>
</html>