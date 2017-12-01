<?php
	header("Access-Control-Allow-Origin: *");
	if(!isset($_REQUEST["address"]) or !isset($_REQUEST["city"]) or !isset($_REQUEST["state"])){
		exit("error");
	} else {
		$address = str_replace(" ","+",$_REQUEST["address"]);
		
		$city = str_replace(" ","+",$_REQUEST["city"]);
		$state = str_replace(" ","+",$_REQUEST["state"]);
	}
	$map_url = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=X1-ZWz1g4kx4e6g3v_4u6i7&address=$address&citystatezip=$city+$state";
	if (($response_xml_data = file_get_contents($map_url))===false){
		exit("error");
	} else {
		libxml_use_internal_errors(true);
		$data = simplexml_load_string($response_xml_data);
		if (!$data) {
			exit("error");
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
			
			$useCode = $result->SingleFamily;
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
			
			$return = array(
				array("street"=>"$street"),
				array("zipcode"=>"$zipcode"),
				array("city"=>"$city"),
				array("state"=>"$state"),
				array("latitude"=>"$latitude"),
				array("longitude"=>"$longitude"),
				array("evaluation"=>"$evaluation"),
				array("evaluationChanged"=>"$evaluationChanged"),
				array("evaluationLow"=>"$evaluationLow"),
				array("evaluationHigh"=>"$evaluationHigh"),
				array("useCode"=>"$useCode"),
				array("taxAssessmentYear"=>"$taxAssessmentYear"),
				array("taxAssessment"=>"$taxAssessment"),
				array("yearBuilt"=>"$yearBuilt"),
				array("lotSizeSqFt"=>"$lotSizeSqFt"),
				array("finishedSqFt"=>"$finishedSqFt"),
				array("bathrooms"=>"$bathrooms"),
				array("bedrooms"=>"$bedrooms"),
				array("totalRooms"=>"$totalRooms"),
				array("lastSoldDate"=>"$lastSoldDate"),
				array("lastSoldPrice"=>"$lastSoldPrice")	
			);
			
			echo json_encode($return);
		} else {
			exit("error");
		}
	}
	

?>