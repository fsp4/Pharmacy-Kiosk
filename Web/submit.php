<?php
	$type = $_POST["type"];
	
	$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
	if ($db->connect_error) {
		die ("Could not connect to db " . $db->connect_error);
	}
	
	if (strcmp($type, "pickup") == 0) {
		// get form input
		$returning = "no";
		if(isset($_POST['returningcustomer'])) {
			$returning = "yes";
		}
		$firstname = strip_tags(stripslashes($_POST["firstname"]));
		$lastname = strip_tags(stripslashes($_POST["lastname"]));
		$DOB = array_key_exists('DOB', $_POST) ? strip_tags(stripslashes($_POST["DOB"])) : null;
		
		// add form input to database
		$query = "INSERT INTO queue VALUES (null, '$type', '$firstname', '$lastname', '$DOB', '', '$returning', '')";
		$db->query($query);
	}
	else if (strcmp($type, "dropoff") == 0) {
		// get form input
		$returning = "no";
		if(isset($_POST['returningcustomer'])) {
			$returning = "yes";
		}
		$relationship = strip_tags(stripslashes($_POST["relationship"]));
		$insurance_number = array_key_exists('insurance', $_POST) ? strip_tags(stripslashes($_POST["insurance"])) : 0;
		$DOB = array_key_exists('DOB', $_POST) ? strip_tags(stripslashes($_POST["DOB"])) : null;
		
		// add form input to database
		$query = "INSERT INTO queue VALUES (null, '$type', '', '', '$DOB', '$relationship', '$returning', $insurance_number)";
		$db->query($query);
	}
	else {
		echo "error";
	}
	
	// set session variable to show queue number
	$rr = $db->query("SELECT * FROM queue");
	$database_rows = $rr->num_rows;
	$_SESSION["num"] = $database_rows;
	
	include("thankyou.php");
?>