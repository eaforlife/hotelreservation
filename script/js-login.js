	var errorPoint = "<i class='fa fa-exclamation-circle' aria-hidden='true'></i>&nbsp;";
	$('.modal-loading').modal('hide');
	$('.modal-forgot-pass').modal('hide');
	$('.form-forgot-pass').hide();

	$(".forgot-password").click(function() {
		$('.modal-forgot-pass').modal('toggle');
		resetForgotModal();
		$('.form-forgot-pass').hide();
		$('.form-check-email').fadeIn(500);
	});
	

	var validateEmail = $(".form-check-email").validate({
		rules: {
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			email: {
				required: errorPoint+"This field can't be left empty!",
				email: errorPoint+"Please enter a valid email address"
			}
		},
		errorPlacement: function(error,element) {
			error.appendTo(".email-error");
		},
		errorClass: 'is-invalid',
		submitHandler: function(form) {
			// Prevent Double Submission
			$.ajax({
				type: "POST",
				url: "./script/forgot-password.php",
				data: $(form).serialize(),
				success: function(data) {
					if(data.status == "error") {
						validateEmail.showErrors({ email: errorPoint + data.msg });
					}
					if(data.status == "success") {
						$(".form-check-email").fadeOut(500, function() {
							$('#security-question').html(data.question);
							$("#userid").val(data.userid);
							$("#security-question-id").val(data.questionid);
							$(".form-forgot-pass").fadeIn(800);
						});
					}
				},
				error: function(data) {
					validateEmail.showErrors({ email: errorPoint + "Error in communicating to server. Please try again later" });
				},
				complete: function(data) {
					console.log(data); // Debug
				}
			});
		}
	});
	
	validatePass = $(".form-forgot-pass").validate({
		rules: {
			answer: { required: true, minlength: 5, maxlength: 95 },
			pass1: { required: true, minlength: 5, maxlength: 23 },
			pass2: { required: true, minlength: 5, maxlength: 23, equalTo: "#new-password" },
		},
		messages: {
			answer: {
				required: errorPoint + "Field can't be empty.",
				minlength: errorPoint + "Field too short! Field has to have atleast 5 characters.",
				maxlength: errorPoint + "Field too long! Field needs to have no more than 95 characters."
			},
			pass1: { required: errorPoint + "Field can't be empty!", minlength: errorPoint + "Password field should be atleast between 5-23 characters long!", maxlength: errorPoint + "Password field should be atleast between 5-23 characters long!" },
			pass2: { required: errorPoint + "Field can't be empty!", minlength: errorPoint + "Password field should be atleast between 5-23 characters long!", maxlength: errorPoint + "Password field should be atleast between 5-23 characters long!", equalTo: errorPoint + "Password fields does not match!" }
		},
		errorPlacement: function(error,element) {
			if(element.attr("name")=="answer") {
				error.appendTo(".security-error");
			}
			if(element.attr("name")=="pass1") {
				error.appendTo(".new-pass");
			}
			if(element.attr("name")=="pass2") {
				error.appendTo(".new-pass-confirm");
			}
		},
		errorClass: "is-invalid",
		submitHandler: function(form) {
			$.ajax({
				type: "POST",
				url: "./script/forgot-password.php",
				data: $(form).serialize(),
				success: function(data) {
					if(data.status=="security") {
						validatePass.showErrors({ answer: errorPoint + data.msg });
					}
					if(data.status=="success") {
						$("#end-modal-message").html(data.msg);
						$(".form-forgot-pass").fadeOut(200, function() {
							$("#end-modal-message").fadeIn(800).delay(2000).fadeOut(200, function() {
								$(".modal-forgot-pass").modal("toggle");
							});
						});
					}
					if(data.status=="error") {
						$(".forgot-pass-error").html(data.msg);
					}
					console.log("Success in Ajax");
				},
				error: function(data) {
					$(".forgot-pass-error").html("Error in communicating to the server. Please try again later");
					console.log("Error in Ajax");
					console.log(data);
				},
				complete: function(data) {
					console.log(data);
					console.log(data.log);
				}
			});
		}
	});

	$(".form-signin").validate({
		errorPlacement: function(error,element) {
			error.appendTo();
		},
		errorClass: 'is-invalid',
		submitHandler: function(form) {
			// We prevent double submission.
			$(".form-signin button").addClass("disabled");
			$(".form-signin input").prop("disabled",true);
			$(".form-signin input").prop("disabled",false); // Re-enables input form before calling ajax. This performs ajax call while preventing double submission since modal is active.
			$.ajax({
				type: "POST",
				url: "./script/validate-login.php",
				data: $(form).serialize(),
				success: function(data) {
					if(data.status == "error") {
						$(".form-signin button").removeClass("disabled");
						console.log("Sign-in Error");
						console.log(data.msg);
						$("#inputPassword").val("");
						$("#form-error-text").html(data.msg);
						$("#form-error-text").fadeIn(400).delay(4000).fadeOut(800);
					}
					if(data.status == "success") {
						$(".form-signin button").addClass("disabled");
						$(".form-signin input").prop("disabled",true);
						console.log("Successfully signed in");
						console.log(data.msg);
						$("#form-success-text").html(data.msg);
						$("#form-success-text").fadeIn(300).delay(3000).fadeOut(100, function() {
							window.location.replace("./controlpanel");
						});
					}
				},
				error: function(data) {
					$(".form-signin button").removeClass("disabled");
					console.log("Error in AJAX");
					console.log(data);
					$("#inputPassword").val("");
					$("#form-error-text").html("<i class='fa fa-exclamation' aria-hidden='true'></i> Something went wrong while signing you in. Please try again later.");
					$("#form-error-text").fadeIn(400).delay(3000).fadeOut(800);
				}
			});
			return false;
		}
	});

	$('.modal-forgot-pass').on('hidden.bs.modal', function (e) {
		// If modal is closed on whatever reason, clear fields.
		resetForgotModal();
	});

	function resetForgotModal() {
		$(".form-check-email")[0].reset(); // javascript reset form
		$(".form-check-email input").removeClass("is-invalid"); // remove bootstrap invalid class
		validateEmail.resetForm(); // reset validation errors.
		$(".form-forgot-pass")[0].reset(); // javascript reset form
		$(".form-forgot-pass input").removeClass("is-invalid"); // remove bootstrap invalid class
		validatePass.resetForm(); // reset validation errors.
		$(".forgot-pass-error").html("");
		$("#end-modal-message").html("");
	}