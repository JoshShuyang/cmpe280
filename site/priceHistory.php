<?php
	$data = "([";
	$data2 = "[{";
	$yr = 1996;
	$mth = 5;
	$servername = "localhost";
	$username = "shuoranz_db";
	$password = "shuoran2468";
	$db = "shuoranz_272";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $db);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	if(isset($_GET["zipcode"])){
		$zipcode = $_GET["zipcode"];
		if(filter_var($zipcode, FILTER_VALIDATE_INT) and $zipcode>1000 and $zipcode<100000)
		{
			$SQL = "SELECT * FROM `280_final_home_value` where zipcode = $zipcode";
			$result = mysqli_query($conn, $SQL);
			while($row = mysqli_fetch_array($result)){
				$city = $row["city"];
				$state = $row["state"];
				$metro = $row["metro"];
				$county = $row["county"];
				$homeValue = $row["homeValue"];
				//echo $zipcode."<br>".$city."<br>".$state."<br>".$metro."<br>".$county."<br>".$homeValue."<br>";
				$pieces = explode(",", $homeValue);
				foreach($pieces as $p){
					$data .= "[Date.UTC($yr,$mth,1),$p],";
					$mth = $mth+1;
					if($mth==13){
						$mth=1;
						$yr = $yr+1;
					}
				}
			}
			$data = rtrim($data,",");
			$data .= "])";
		} else {
			//echo "input error";
		}
	}else if(isset($_GET["city"])){
		$city = $_GET["city"];
		$SQL = "SELECT * FROM `280_final_home_value` where city = '$city'";
		$result = mysqli_query($conn, $SQL);
		while($row = mysqli_fetch_array($result)){
			$zipcode = $row["zipcode"];
			$state = $row["state"];
			$metro = $row["metro"];
			$county = $row["county"];
			$homeValue = $row["homeValue"];
			//echo $zipcode."<br>".$city."<br>".$state."<br>".$metro."<br>".$county."<br>".$homeValue."<br>";
			$pieces = explode(",", $homeValue);
			$temp = "[";
			foreach($pieces as $p){
				$mth = $mth+1;
				if($mth==13){
					$mth=1;
					$yr = $yr+1;
				}
				if($mth==11){
					$temp .= $p . ",";
				}
			}
			$temp = rtrim($temp,",");
			$data2 .= "name:'$city, $state $zipcode',data: $temp]},{";
			
		}
		$data2 = rtrim($data2,"{");
		$data2 = rtrim($data2,",");
		$data2 .= "]";
	}else {
		echo "input error";
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
					<h1><a href="index.html">FangTe</a><span>Your Housing Manager</span></h1>
					<nav>
						<ul class="sf-menu">
							<li><a href="index.html">Home</a></li>
							<li class="current"><a>Price History</a></li>
							<li><a href="index-1.html">About</a></li>
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
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>

	<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<?php
	if(isset($_GET["zipcode"])){
?>
		<script type="text/javascript">
			data = <?php echo $data; ?>

    Highcharts.chart('container', {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: 'Price history chart of the Real Estate<br>zipcode <?php echo $zipcode;?><br>City: <?php echo $city;?>, State <?php echo $state; ?>'
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
        },
        xAxis: {
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'Exchange rate'
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: 'price history for zipcode',
            data: data
        }]
    });

		</script>
	<?php } else if (isset($_GET["city"])){ ?>
	<script type="text/javascript">
		Highcharts.chart('container', {

    title: {
        text: 'Medium Price of this city Real Estate, 1996-2017'
    },

    subtitle: {
        text: 'city: <?php echo $city; ?>'
    },

    yAxis: {
        title: {
            text: 'Medium Price'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 1996
        }
    },

    series: <?php echo $data2;?>,

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
	</script>
	<?php } ?>

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
				<pre>© <span id="copyright-year"></span> FangTe |  Privacy Policy</pre>
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