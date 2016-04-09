<!DOCTYPE html>
<html>
<head>
	<title>Thank You!</title>
	<link rel="stylesheet" type="text/css" href="menu.css"/>
</head>

<body>
<?php
	$type = $_POST["type"];
	// type: pickup, returningdropoff, newdropoff, talk, 
	// queue: ID, type, refill, first_name, last_name, middle_initial, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, realation, returning_customer, insurance_card_number, allergies
	
	if (strcmp($type, "pickup") == 0) {
		// get form input
		$returning = "no";
		if(isset($_POST['returningcustomer'])) {
			$returning = "yes";
		}
		$firstname = strip_tags(stripslashes($_POST["firstname"]));
		$lastname = strip_tags(stripslashes($_POST["lastname"]));
		$DOB = strip_tags(stripslashes($_POST["DOB"]));
		
		// add form input to database
		// should do something to make the password more secure here...
		$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
		if ($db->connect_error):
		   die ("Could not connect to db " . $db->connect_error);
		endif;
		// queue: ID, type, refill, first_name, last_name, middle_initial, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies
		$query = "INSERT INTO queue VALUES (null, '$type', '', '$firstname', '$lastname', '', '$DOB', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$returning', '', '')";
		$db->query($query);
	}
	else if (strcmp($type, "returningdropoff") == 0) {
		// get form input
		$allergies = "0";
		if(isset($_POST['allergy'])) {
			$allergies = "1";
		}
		$returning = "yes";
		$relationship = strip_tags(stripslashes($_POST["relationship"]));
		$firstname = strip_tags(stripslashes($_POST["firstname"]));
		$lastname = strip_tags(stripslashes($_POST["lastname"]));
		$DOB = strip_tags(stripslashes($_POST["DOB"]));
		
		// add form input to database
		// should do something to make the password more secure here...
		$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
		if ($db->connect_error):
		   die ("Could not connect to db " . $db->connect_error);
		endif;
		$query = "INSERT INTO queue VALUES (null, '$type', '', '$firstname', '$lastname', '', '$DOB', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$returning', '', '$allergies')";
		$db->query($query);
	}
	else if (strcmp($type, "newdropoff") == 0) {
		// get form input
		$refill = "0";
		if(isset($_POST['refill'])) {
			$refill = "1";
		}
		$position = "null"; 
		if(isset($_POST['position'])) {
			$position = $_POST['position'];
		}

		$phone_type = "null";
		if(isset($_POST['phone_type']) && $_POST['phone_type'] == "home") {
			$phone_type = "home";
		}
		else if($_POST['phone_type'] == "mobile"){
			$phone_type = "mobile";
		} 
		else if($_POST['phone_type'] == "work"){
			$phone_type = "work";
		} 
		
		$notifications = "null"; 
		if(isset($_POST['notifications']) && $_POST['notifications'] == "text") {
			$notifications = "text";
		}
		else{
			$notifications = "voice";
		}
		
		$gender = "null";
		if(isset($_POST['gender']) && $_POST['gender']=="female"){
			$gender = "F";
		}
		else{
			$gender = "M";		
		}
		
		$firstname = strip_tags(stripslashes($_POST["firstname"]));
		$lastname = strip_tags(stripslashes($_POST["lastname"]));
		$middle = strip_tags(stripslashes($_POST["middlename"]));
		$DOB = strip_tags(stripslashes($_POST["DOB"]));
		$home = strip_tags(stripslashes($_POST["home"]));
		$city = strip_tags(stripslashes($_POST["city"]));
		$state = strip_tags(stripslashes($_POST["state"]));
		$zip = strip_tags(stripslashes($_POST["zip"]));
		$phone = strip_tags(stripslashes($_POST["phone"]));
		$allergies_list = strip_tags(stripslashes($_POST["allergies_list"]));
		$current_meds = strip_tags(stripslashes($_POST["current_meds"]));
		$signature = strip_tags(stripslashes($_POST["signature"]));
		$date = strip_tags(stripslashes($_POST["date"]));

		// add form input to database
		// should do something to make the password more secure here...
		$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
		if ($db->connect_error):
		   die ("Could not connect to db " . $db->connect_error);
		endif;
		
		$query = "INSERT INTO queue VALUES (null, '$type', '$refill', '$firstname', '$lastname', '$middle', '$DOB', '$gender', '$position', '$home', '$city', '$state', '$zip', '$phone', '$phone_type', '$notifications', '$allergies_list', '$current_meds', '$signature', '$date', '', '', '', '')";
		$db->query($query);
	}
	else if (strcmp($type, "talk") == 0) {
		// get form input
		$firstname = strip_tags(stripslashes($_POST["firstname"]));
		$lastname = strip_tags(stripslashes($_POST["lastname"]));

		// add form input to database
		// should do something to make the password more secure here...
		$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
		if ($db->connect_error):
		   die ("Could not connect to db " . $db->connect_error);
		endif;
		$query = "INSERT INTO queue VALUES (null, '$type', '', '$firstname', '$lastname', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
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
</body>
</html>