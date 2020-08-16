<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
	<title>Home Page</title>
	<link rel="stylesheet" href="style/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="style/bootstrap.min.css">
	<style>
	   body {
	       background-color: #EAEAEA;
	       position: relative;
	       margin-top:57px;
	   }
	   .front-home {
            background-image: url('media/img/homepage-asset-0.jpg');
            height: 800px;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
	   }
	   .front-block {
	       height: 400px;
	   }
	   .front-service {
            background-image: url('media/img/homepage-asset-1.jpg');
            height: 700px;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
	   }
	   .img-text {
	       color: #eaeaea;
	       text-shadow: 2px 2px 2px 2px #000000;
	   }
        .navbar {
            min-height: 60px;
        }
        
        .navbar-brand {
            padding: 0 15px;
            height: 60px;
            line-height: 60px;
        }
        
        .navbar-toggle {
            /* (60px - button height 34px) / 2 = 13px */
            margin-top: 13px;
            padding: 9px 10px !important;
        }
        
        @media (min-width: 768px) {
            .navbar-nav > li > a {
                /* (60px - line-height of 27px) / 2 = 16.5px */
                padding-top: 16.5px;
                padding-bottom: 16.5px;
                line-height: 27px;
            }
        }
	</style>
</head>

<body id="page-top" data-spy="scroll" class="body">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    	<a class="navbar-brand" href="#page-top">
    		<img src="./media/img/logo-asset-1.png" width="106" height="60" alt="">
    		Play N Display Inn Hotel
		</a>
    	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#hotelNav" aria-controls="hotelNav" aria-expanded="false" aria-label="Toggle navigation">
    		<span class="navbar-toggler-icon"></span>
    	</button>
    	<div class="collapse navbar-collapse" id="hotelNav">
    		<ul class="navbar-nav ml-auto">
    			<li class="nav-item">
    				<a class="nav-link" href="#login">Reservations</a>
    			</li>
    			<li class="nav-item">
    				<a class="nav-link" href="#about">About</a>
    			</li>
    			<li class="nav-item">
    				<a class="nav-link" href="#services">Service</a>
    			</li>
    		</ul>
    	</div>
    </nav>
	<!-- End of Navigation -->
	
	<!-- Content -->
	<div class="front-home scroll-content" id="login">
		<div class="container">
    		<div class="d-flex align-items-left flex-column justify-content-center" style="height:800px">
            	<div class="row">
            		<div class="col-md-6 text-white">
            			<h2 class="text-uppercase">Play N' Display Inn Hotel</h2>
        				<p class="font-weight-bold text-capitalize">Reserve and check rooms for your needs online!</p>
        				<a class="btn btn-info btn-lg" href="./login">Check rooms now!</a>
            		</div>
            	</div>
            </div>
		</div>
	</div>
	
	<div class="front-block scroll-content" id="about">
    	<div class="container">
    		<div class="d-flex align-items-center flex-column justify-content-center" style="height:400px">
        		<div class="row">
        			<div class="col-md-12 text-center">
        				<h1 class="display-4">About our Hotel</h1>
        				<p align="justify">Establishment that provides paid lodging on a short-term basis. The provision of basic accomodation, on time pass, consisting only in a room with bed, a cupboard, a small table and washstand has largely been replaced by rooms with modern facilities, including en-suite bathrooms and air-conditioning or climate control. The cost and quality are usually indicative of the range and type of services available.</p>
        			</div>
        		</div>
    		</div>
    	</div>
	</div>
	
	<div class="front-service" style="height:550px">
		&nbsp;
	</div>
	
	<div class="front-block scroll-content" id="services">
    	<div class="container-fluid">
    		<div class="d-flex align-items-center justify-content-center" style="height:300px">
        		<div class="row">
        			<div class="col-md-12 text-center">
        				<h3>We Offer</h3>
                        <ul class="list-unstyled">
                            <li><h5>Accessible Location.</h5></li>
                            <li><h5>Open 24/7.</h5></li>
                            <li><h5>Friendly Staff.</h5></li>
                        </ul>
                	</div>
        		</div>
    		</div>
    	</div>
	</div>
	
	<div class="front-home" style="height:630px">
		&nbsp;
	</div>
	<!-- End of Content -->
	
	<footer class="text-center mt-3" style="height:100px">
		<div class="container">
			<div class="row">
				<div class="col-md-12">Play N' Display Inn Hotel &copy; 2017. Powered by Bootstrap v4</div>
				<div class="col-md-12">heidi.s.vinoya@yahoo.com <small>(Heidi Vinoya - Fairfields Manager)</small></div>
				<div class="col-md-12">Fairfields San Carlos Rizal St., San Carlos City, Pangasinan 2420</div>
			</div>
		</div>
	</footer>

<!-- Scripts should be placed below this point for optimized page load -->
<script src="script/jquery.min.js"></script>
<script src="script/popper.min.js"></script>
<script src="script/bootstrap.min.js"></script>
<script type="text/javascript">
	// TODO: Fade In effects on front page
	$('body').scrollspy({target: ".navbar", offset: 50});
    $("#hotelNav a").on('click', function(event) {
        if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top
                }, 800, function(){
                window.location.hash = hash;
            });
        }
    });
</script>
</body>

</html>