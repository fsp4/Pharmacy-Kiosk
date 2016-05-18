<?php
	// connect to database, if does not exist then create
	$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
	if ($db->connect_error) {
		$db = new mysqli('localhost', 'root', '');
		if ($db->connect_error) {
			die ("Could not connect to db " . $db->connect_error);
		}
		
		$db->query("CREATE DATABASE kiosk_queue");
		$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
		$db->query("CREATE TABLE `kiosk_queue`.`queue` ( `id` INT NOT NULL AUTO_INCREMENT, `type` TEXT NOT NULL, `refill` TINYINT(1) NOT NULL, `first_name` TEXT NOT NULL,
		`last_name` TEXT NOT NULL, `middle` TEXT NOT NULL, `date_of_birth` DATE NOT NULL, `gender` TEXT NOT NULL, `position` TEXT NOT NULL, `home_address` TEXT NOT NULL,
		`city` TEXT NOT NULL, `state` TEXT NOT NULL, `zip` INT(11) NOT NULL, `phone` VARCHAR(32) NOT NULL, `phone_type` TEXT NOT NULL, `notifications` TEXT NOT NULL,
		`allergies_list` MEDIUMTEXT NOT NULL, `current_meds` MEDIUMTEXT NOT NULL, `signature` TEXT NOT NULL, `date` DATE NOT NULL, `relation` TEXT NOT NULL, `returning_customer` TEXT NOT NULL,
		`insurance_card_number` INT(99) NOT NULL, `allergies` TINYINT(1) NOT NULL, `comment` TEXT NOT NULL, `timestamp` DATETIME NOT NULL, `insert_time` DATETIME NOT NULL, PRIMARY KEY (`id`))");
		
		$db->query("CREATE TABLE `kiosk_queue`.`queue_archive` ( `id` INT NOT NULL, `type` TEXT NOT NULL, `refill` TINYINT(1) NOT NULL, `first_name` TEXT NOT NULL,
		`last_name` TEXT NOT NULL, `middle` TEXT NOT NULL, `date_of_birth` DATE NOT NULL, `gender` TEXT NOT NULL, `position` TEXT NOT NULL, `home_address` TEXT NOT NULL,
		`city` TEXT NOT NULL, `state` TEXT NOT NULL, `zip` INT(11) NOT NULL, `phone` VARCHAR(32) NOT NULL, `phone_type` TEXT NOT NULL, `notifications` TEXT NOT NULL,
		`allergies_list` MEDIUMTEXT NOT NULL, `current_meds` MEDIUMTEXT NOT NULL, `signature` TEXT NOT NULL, `date` DATE NOT NULL, `relation` TEXT NOT NULL, `returning_customer` TEXT NOT NULL,
		`insurance_card_number` INT(99) NOT NULL, `allergies` TINYINT(1) NOT NULL, `comment` TEXT NOT NULL, `timestamp` DATETIME NOT NULL, `insert_time` DATETIME NOT NULL, PRIMARY KEY (`id`))");
		
		$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
	}

	$type = $_POST["type"];
	// type: pickup, returningdropoff, newdropoff, talk, 
	// queue: ID, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, 
	// allergies_list, current_meds, signature, date, realation, returning_customer, insurance_card_number, allergies, comment, timestamp, insert_time
	
	// set eastern timezone for timestamps
	date_default_timezone_set('US/Eastern');
	
	if (strcmp($type, "pickup") == 0) {
		// get form input
		$returning = "no";
		if(isset($_POST['returningcustomer'])) {
			$returning = "yes";
		}
		$DOB = null;
		if($returning == "no") {
			$DOB = strip_tags(stripslashes($_POST["DOB"]));
		}
		$relationship = strip_tags(stripslashes($_POST["relationship"]));
		$firstname = strip_tags(stripslashes($_POST["firstname"]));
		$lastname = strip_tags(stripslashes($_POST["lastname"]));
		$timestamp = date("Y-m-d H:i:s");
		
		$query = "INSERT INTO queue VALUES (null, '$type', '', '$firstname', '$lastname', '', '$DOB', '', '', '', '', '', 
				  '', '', '', '', '', '', '', '', '$relationship', '$returning', '', '', '', '$timestamp', '$timestamp')";
		$db->query($query);
	}
	else if (strcmp($type, "returningdropoff") == 0) {
		// get form input
		$allergies = "null";
		if(isset($_POST['allergies']) && $_POST['allergies']=="Yes"){
			$allergies = "1";
		}
		else {
			$allergies = "0";
		}
		
		$returning = "yes";
		$relationship = strip_tags(stripslashes($_POST["relationship"]));
		$firstname = strip_tags(stripslashes($_POST["firstname"]));
		$lastname = strip_tags(stripslashes($_POST["lastname"]));
		$DOB = strip_tags(stripslashes($_POST["DOB"]));
		$timestamp = date("Y-m-d H:i:s");
		
		$query = "INSERT INTO queue VALUES (null, '$type', '', '$firstname', '$lastname', '', '$DOB', '', '', '', '', '', '', 
				  '', '', '', '', '', '', '', '$relationship', '$returning', '', '$allergies', '', '$timestamp', '$timestamp')";
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
		
		// parse phone number
		$phone = $_POST["phone"];
		preg_match_all('!\d+!', $phone, $temp_phone);
		$temp_phone = implode(' ', $temp_phone[0]);
		$real_phone = str_replace(' ', '', $temp_phone);
		
		// if user entered only letters, set phone number to 0
		if (!strcmp($real_phone, '')) {
			$real_phone = 0;
		}
		
		$firstname = strip_tags(stripslashes($_POST["firstname"]));
		$lastname = strip_tags(stripslashes($_POST["lastname"]));
		$middle = strip_tags(stripslashes($_POST["middlename"]));
		$DOB = strip_tags(stripslashes($_POST["DOB"]));
		$home = strip_tags(stripslashes($_POST["home"]));
		$city = strip_tags(stripslashes($_POST["city"]));
		$state = strip_tags(stripslashes($_POST["state"]));
		$zip = strip_tags(stripslashes($_POST["zip"]));
		$allergies_list = strip_tags(stripslashes($_POST["allergies_list"]));
		$current_meds = strip_tags(stripslashes($_POST["current_meds"]));
		$signature = strip_tags(stripslashes($_POST["signature"]));
		$date = strip_tags(stripslashes($_POST["date"]));
		$timestamp = date("Y-m-d H:i:s");
		
		$query = "INSERT INTO queue VALUES (null, '$type', '$refill', '$firstname', '$lastname', '$middle', '$DOB', '$gender', '$position', '$home', '$city', '$state', '$zip', 
				  $real_phone, '$phone_type', '$notifications', '$allergies_list', '$current_meds', '$signature', '$date', '', '', '', '', '', '$timestamp', '$timestamp')";
		$db->query($query);
	}
	else if (strcmp($type, "talk") == 0) {
		// get form input
		$firstname = strip_tags(stripslashes($_POST["firstname"]));
		$lastname = strip_tags(stripslashes($_POST["lastname"]));
		$timestamp = date("Y-m-d H:i:s");
		
		$query = "INSERT INTO queue VALUES (null, '$type', '', '$firstname', '$lastname', '', '', '', '', '', 
				  '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$timestamp', '$timestamp')";
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