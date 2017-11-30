<?php

	if(!isset($_REQUEST["address"]) or !isset($_REQUEST["city"]) or !isset($_REQUEST["state"])){
		//exit("error");
	} else {
		$address = str_replace(" ","+",$_REQUEST["address"]);
		
		$city = str_replace(" ","+",$_REQUEST["city"]);
		$state = str_replace(" ","+",$_REQUEST["state"]);
	}
	$map_url = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1g4kx4e6g3v_4u6i7&address=$address&citystatezip=$city+$state";
	if (($response_xml_data = file_get_contents($map_url))===false){
		//exit("error");
	} else {
		libxml_use_internal_errors(true);
		$data = simplexml_load_string($response_xml_data);
		if (!$data) {
			//exit("error");
		} else if($data->message->code==0){
			$result = $data->response->results->result;
			$address = $result->address;
			
			$street = $address->street;
			$zipcode = $address->zipcode;
			$city = $address->city;
			$state = $address->state;
			$latitude = $address->latitude;
			$longitude = $address->longitude;
			
			$evaluation = $result->zestimate->amount;
			$evaluationChanged = $result->zestimate->valueChange;
			$evaluationLow = $result->zestimate->valuationRange->low;
			$evaluationHigh = $result->zestimate->valuationRange->high;
			
			$useCode = $result->useCode;
			$taxAssessmentYear = $result->taxAssessmentYear;
			$taxAssessment = $result->taxAssessment;
			$yearBuilt = $result->yearBuilt;
			$lotSizeSqFt = $result->lotSizeSqFt;
			$finishedSqFt = $result->finishedSqFt;
			$bathrooms = $result->bathrooms;
			$bedrooms = $result->bedrooms;
			$totalRooms = $result->totalRooms;
			$lastSoldDate = $result->lastSoldDate;
			$lastSoldPrice = $result->lastSoldPrice;
			
			//echo json_encode($return);
		} else {
			//exit("error");
		}
	}
	

?>

<!DOCTYPE html>
<html lang="en">
	<head>
	<title>Specific Address</title>
	<meta charset="utf-8">
	<meta name = "format-detection" content = "telephone=no" />
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="css/grid.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/map.css">


	<script src="js/jquery.js"></script>
	<script src="js/jquery-migrate-1.2.1.js"></script>
	<script src="js/jquery.stellar.js"></script>
	<script src="js/script.js"></script>
	<!--[if (gt IE 9)|!(IE)]><!-->
	<script src="js/wow.js"></script>
	<script>
		$(document).ready(function () {
			if ($('html').hasClass('desktop')) {
				new WOW().init();
			}
		});
	</script>
	<!--<![endif]-->
	<!--[if lt IE 8]>
	<div style=' clear: both; text-align:center; position: relative;'>
	 <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
		 <img src="images/ie8-panel/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
	 </a>
	</div>
	<![endif]-->
	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="css/ie.css">
	<![endif]-->
	</head>
<body class="index-1">
<!--==============================header=================================-->
<header id="header">
	<div id="stuck_container">
		<div class="container">
			<div class="row">
				<div class="grid_12">
					<h1><a href="index.html">Build-Up</a><span>your advertising agency</span></h1>
					<nav>
						<ul class="sf-menu">
							<li><a href="index.html">Home</a>
								<ul>
									<li><a href="index-1.html">About</a></li>
									<li><a href="index-2.html">Projects</a>
										<ul>
											<li><a href="#">Lorem ipsum</a></li>
											<li><a href="#">Lorem ipsum</a></li>
											<li><a href="#">Lorem ipsum</a></li>
										</ul>
									</li>
									<li><a href="index-3.html">Services</a></li>
									<li><a href="#">index-4.html</a></li>
								</ul>
							</li>
							<li class="current"><a href="index-1.html">About</a></li>
							<li><a href="index-2.html">Projects</a></li>
							<li><a href="index-3.html">Services</a></li>
							<li><a href="index-4.html">Contacts</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</header>

<!--=======content================================-->

<section id="content">
	<div>
		<div id="map"></div>
		<script>
		  function initMap() {
			// Create a map object and specify the DOM element for display.
			var map = new google.maps.Map(document.getElementById('map'), {
			  center: {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>},
			  // Set mapTypeId to SATELLITE in order
			  // to activate satellite imagery.
			  mapTypeId: 'satellite',
			  zoom: 30
			});
		  }

		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFZTB9CIHh5Sile-jx5czOk6nyBdZxA7g&callback=initMap"
			async defer></script>
		<div style="height:43vw; text-align:left; width:40%; float:right;background:#fff;">
			<div style="margin:50px 40px;">
			<h3 style="text-align:left;color:#444;"><?php echo $street;?>,</h3>
			<h3 style="text-align:left;color:#444;"><?php echo $city.", ".$state." ".$zipcode;?></h3>
			<p style="font: 20px/30px 'Open Sans', sans-serif;"><?php echo $bedrooms." beds ".$bathrooms." baths ".$finishedSqFt." sqft";?></p>
			<p style="font: 20px/30px 'Open Sans', sans-serif;">Estimated value: $<?php echo $evaluation;?></p>
			</br></br>
			<p style="font: 20px/30px 'Open Sans', sans-serif;">
			<?php echo "$street, $city, $state is a $useCode home that contains $finishedSqFt sq ft and was built in $yearBuilt. It contains $bedrooms bedrooms and $bathrooms bathrooms. This home last sold for $".$lastSoldPrice." in $lastSoldDate. The Zestimate for this house is $".$evaluation.", which has changed by $$evaluationChanged in the last 30 days.";?>
			</p>
			</div>
		</div>
	</div>
</section>

<!--=======footer=================================-->
<footer id="footer">
	<div class="container">
		<div class="row">
			<div class="grid_12 copyright">
				<h2><span>Follow Us</span></h2>
				<a href="#" class="btn bd-ra"><span class="fa fa-facebook"></span></a>
				<a href="#" class="btn bd-ra"><span class="fa fa-tumblr"></span></a>
				<a href="#" class="btn bd-ra"><span class="fa fa-google-plus"></span></a>
				<pre>© <span id="copyright-year"></span> |  Privacy Policy</pre>
			</div>
		</div>
	</div>
</footer>
<script>
	$(document).ready(function() { 
			if ($('html').hasClass('desktop')) {
				$.stellar({
					horizontalScrolling: false,
					verticalOffset: 20,
					resposive: true,
					hideDistantElements: true,
				});
			}
		});	
</script>
</body>
</html>