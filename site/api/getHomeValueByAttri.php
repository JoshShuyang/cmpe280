<?php
	header("Access-Control-Allow-Origin: *");
	$servername = "localhost";
	$username = "shuoranz_db";
	$password = "shuoran2468";
	$db = "shuoranz_272";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $db);
	$isEmpty = true;
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
				echo $zipcode."<br>".$city."<br>".$state."<br>".$metro."<br>".$county."<br>".$homeValue."<br>";
				$isEmpty = false;
			}
		} else {
			exit("error");
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
			echo $zipcode."<br>".$city."<br>".$state."<br>".$metro."<br>".$county."<br>".$homeValue."<br>";
			$isEmpty = false;
		}
	}else {
		exit("error");
	}
	if($isEmpty){
		exit("error");
	}
	

?>