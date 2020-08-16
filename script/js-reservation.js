var fee = 600;
$("#checkout-room :input").prop("disabled", true);
$(".sms-status").hide();
$("#paypal-checkout").hide();
// Paypal Functions
paypal.Button.render({
    env: 'sandbox', // Or 'production',
    client: {
		sandbox: "AeJX2BvbNBOEZlLaX9WC-4yywrYgkTxthv4TIEqz6J4K3GPcA30R6W372axExOcaqiSi6fFfFUxA-SF9",
		production: "ATPboUTSQVF38iNwdHZvfkhpt4etrWukTv3fwEMdhVSOnGIkMnPkplvf2HHpfSbz6L0hYEn4sV-pLrv8"
    },
    commit: true,
    style: {
        label: 'checkout',
        color: 'blue',
        size: 'medium',
        shape: 'rect'
    },
    payment: function(data, actions) {
        return actions.payment.create({
           payment: {
               transactions: [
            	   {
                	   amount: { total: $("#total-amount").val(), currency: "PHP" },
                	   description: $("#room-type option:selected").text(),
                	   custom: "PLAYNDISPLAY-POS-"+$("#transactionId").val(),
                	   invoice_number: $("#transactionId").val()
            	   }
				]
           } 
        });
    },
    onAuthorize: function(data, actions) {
     	return actions.payment.execute().then(function(payment) {
         	submitForm();
     	});
     	
    },
    onCancel: function(data, actions) {
        $(".modal-end-title").html("Transaction Cancelled");
        $(".modal-end-text").html("No changes has been made.");
        $(".modal-end").modal("toggle");
    },
    onError: function(err) {
    	$(".modal-end-title").html("Error");
        $(".modal-end-text").html("An unexpected error has occurred. No transaction has been made.");
        $(".modal-end").modal("toggle");
    }
}, '#paypal-checkout');
// End of Paypal functions

// Functions
$(".btn-goback").click(function() {
	window.open("./controlpanel-01","_self");
});
$(".modal-print").on("hide.bs.modal", function() {
	window.open("./controlpanel","_self");
});
$(".print-btn").click(function() {
	window.print();
});

$(".checkout-room").submit(function(e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: "./script/reservations.php",
		data: $(".checkout-room").serialize() + "&status=valid",
		success: function(data) {
			$(".modal-checkout").modal("toggle");
			if(data.status=="full") {
				$("#paypal-checkout").hide();
				$(".body-checkout").html(data.msg);
				$(".checkout-title").html("Room Full");
			} else if(data.status=="vacant") {
				$("#paypal-checkout").fadeIn(500);
				$(".body-checkout").html(data.msg);
				$(".checkout-title").html("Reservation Ready");
			}
		},
		complete: function(data) {
			console.log(data);
		}
	});
});

function submitForm() {
	$(".modal-checkout").modal("hide");
	$.ajax({
		type: "POST",
		url: "./script/reservations.php",
		data: $(".checkout-room").serialize(),
		success: function(data) {
			pushSMS($("#transactionId").val()); // Send SMS
			modalPrint($("#transactionId").val(), "success");
		},
		error: function(data) {
			console.log(data);
			modalPrint("0", "error");
		}
	});
}
function pushSMS(x) {
	$.ajax({
		type: "POST",
		url: "./script/smsConfig.php",
		data: { type: "invoice", transactionID: x },
		success: function(data) {
			$("#sms-status").html(data.msg);
			$(".sms-status").fadeIn(500).delay(9500).fadeOut(2000);
		},
		error: function(data) {
			console.log(data);
			console.log("sms push ajax error");
		}
	});
}

function modalPrint(x,y) {
	if(y == "success") {
		$.post("./script/print-invoice.php", { transactionId: x, status: "success"}, function(response) {
			$(".print-modal").html(response);
			$(".modal-print").modal("toggle");
		});
	} else {
		$.post("./script/print-invoice.php", { status: "error"}, function(response) {
			$(".print-modal").html(response);
			$(".modal-print").modal("toggle");
		});
	}
}