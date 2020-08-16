	$('.modal-loading').modal('hide');
	$("#form-error-text").hide();
	$("#form-success-text").hide();

	$(".form-signin").validate({
		errorPlacement: function(error,element) {
			error.appendTo();
		},
		errorClass: "is-invalid",
		submitHandler: function(form) {
			$(".form-signin button").addClass("disabled");
			$(".form-signin input").prop("disabled",true);
			$(".form-signin select").prop("disabled",true);
			$(".form-signin input").prop("disabled",false); // Re-enables input form before calling ajax. This performs ajax call while preventing double submission since modal is active.
			$(".form-signin select").prop("disabled",false);
			$.ajax({
				type: "POST",
				url: "script/validate-signup.php",
				data: $(form).serialize(),
				success: function(data) {
					console.log("Success");
					console.log(data);
					if(data.status=="success") {
						$(".form-signin button").addClass("disabled");
						$(".form-signin input").prop("disabled",true);
						$(".form-signin select").prop("disabled",true);
						$("#form-success-text").html(data.msg);
						$("#form-success-text").fadeIn(300).delay(3000).fadeOut(100, function() {
							window.location.replace("./login");
						});
					} else {
						$(".form-signin button").removeClass("disabled");
						$("#form-error-text").html(data.msg);
						$("#form-error-text").fadeIn(300, function() {
							$(".form-signin button").removeClass("disabled");
						}).delay(3000).fadeOut(800);
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$(".form-signin button").removeClass("disabled");
					console.log("Error");
					console.log(textStatus);
					console.log(errorThrown);
					$("#form-error-text").html("Error in communicating to server. Please try again later.");
					$("#form-error-text").fadeIn(300).delay(3000).fadeOut(800);
				},
				complete: function(data) {
					console.log(data);
				}
			});
		}
	});
	$("#contact").rules("add", { digits: true, minlength: 7, maxlength: 17 });
	$("#first-name").rules("add", { minlength: 3, maxlength: 95 });
	$("#security-answer").rules("add", { minlength: 5, maxlength: 95 });
	$("#last-name").rules("add", { minlength: 3, maxlength: 55 });
	$("#inputPassword").rules("add", { minlength: 5, maxlength: 23 });
	$("#inputPassword2").rules("add", { minlength: 5, maxlength: 23, equalTo: "#inputPassword" });