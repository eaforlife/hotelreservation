var errorPoint = "<i class='fa fa-exclamation-circle' aria-hidden='true'></i>&nbsp;";
$(".modal-set-security").modal('toggle');

$('.modal-set-security').on('show.bs.modal', function (e) {
	  $(".container-custom").addClass("blur");
});

$(".security-form").validate({
	rules: {
		answer: { required: true, minlength: 5, maxlength: 50 }
	},
	messages: { answer: { required: errorPoint+"This field should not be empty", minlength: errorPoint+"Field should be atleast 5 characters long", maxlength: errorPoint+"Field is too long! Max characters allowed is 50" } },
	errorPlacement: function(error,element) {
		error.appendTo("#error-text");
	},
	errorClass: "is-invalid",
	submitHandler: function(form) {
		$(".btn-security").addClass("disabled");
		$(".security-form input").prop("disabled",true);
		$(".security-form select").prop("disabled",true);
		// Prevent double submission
		$(".btn-security").removeClass("disabled");
		$(".security-form input").prop("disabled",false);
		$(".security-form select").prop("disabled",false);
		$.ajax({
			type: "POST",
			url: "./script/validate-signup.php",
			data: $(form).serialize(),
			success: function(data) {
				$(".security-form").fadeOut(400, function() {
					$("#security-end").html(data.msg);
					$("#security-end").fadeIn(800).delay(1500).fadeOut(400, function() {
						location.reload(); //reload page
					});
				});
				console.log("Ajax Success");
			},
			error: function(data) {
				$("#error-text").html("Error in transmitting data to server. Please try again later.");
				console.log("Error in AJAX");
			},
			complete: function(data) {
				console.log(data);
			}
		});
	}
});

// This will only show up when an account doesn't have a security question set as a result of database change.