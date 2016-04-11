<?php
	$Adata = Array();
	$type = strip_tags($_POST["type"]);
	
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
		`city` TEXT NOT NULL, `state` TEXT NOT NULL, `zip` INT(11) NOT NULL, `phone` INT(11) NOT NULL, `phone_type` TEXT NOT NULL, `notifications` TEXT NOT NULL,
		`allergies_list` MEDIUMTEXT NOT NULL, `current_meds` MEDIUMTEXT NOT NULL, `signature` TEXT NOT NULL, `date` DATE NOT NULL, `relation` TEXT NOT NULL, `returning_customer` TEXT NOT NULL,
		`insurance_card_number` INT(99) NOT NULL, `allergies` TINYINT(1) NOT NULL, PRIMARY KEY (`id`))");
		
		$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
	}
	
	if ($type == 1) {
		$client_rows = strip_tags($_POST["rows"]);
		$newrows="";
		$rr = $db->query("SELECT * FROM queue");
		$database_rows = $rr->num_rows;
		$Adata["num"] = $client_rows . " " . $database_rows;
		$Adata["pickupContents"] = "OK";
		$Adata["dropoffContents"] = "OK";
		$Adata["talkContents"] = "OK";
		$Adata["crows"] = "$client_rows";
		$Adata["drows"] = "$database_rows";
		if ($client_rows != $database_rows) {
			$Adata["Type"] = "Update";
			$pickupContents = Array();
			$dropoffContents = Array();
			$talkContents = Array();
			for ($i = 0; $i < $database_rows; $i++) {
				$rr->data_seek($i);
				$curr = $rr->fetch_array();
				$currArr = Array();
				$currArr["id"] = $curr["id"];
				$currArr["type"] = $curr["type"];
				$currArr["refill"] = $curr["refill"];
				$currArr["first_name"] = $curr["first_name"];
				$currArr["last_name"] = $curr["last_name"];
				$currArr["middle"] = $curr["middle"];
				$currArr["date_of_birth"] = $curr["date_of_birth"];
				$currArr["gender"] = $curr["gender"];
				$currArr["position"] = $curr["position"];
				$currArr["home_address"] = $curr["home_address"];
				$currArr["city"] = $curr["city"];
				$currArr["state"] = $curr["state"];
				$currArr["zip"] = $curr["zip"];
				$currArr["phone"] = $curr["phone"];
				$currArr["phone_type"] = $curr["phone_type"];
				$currArr["notifications"] = $curr["notifications"];
				$currArr["allergies_list"] = $curr["allergies_list"];
				$currArr["current_meds"] = $curr["current_meds"];
				$currArr["signature"] = $curr["signature"];
				$currArr["date"] = $curr["date"];
				$currArr["relation"] = $curr["relation"];
				$currArr["returning_customer"] = $curr["returning_customer"];
				$currArr["insurance_card_number"] = $curr["insurance_card_number"];
				$currArr["allergies"] = $curr["allergies"];
				if (strcmp($curr["type"], 'pickup') == 0) {
					$pickupContents[] = $currArr;
					$Adata["pickupContents"] = $pickupContents;
				}
				else if ((strcmp($curr["type"], 'returningdropoff') == 0) || ((strcmp($curr["type"], 'newdropoff') == 0))){
					$dropoffContents[] = $currArr;
					$Adata["dropoffContents"] = $dropoffContents;
				}
				else{
					$talkContents[] = $currArr;
					$Adata["talkContents"] = $talkContents;
				}	
			}
		}
		else {
			$Adata["Type"] = "NA";
			$Adata["pickupContents"] = "OK";
			$Adata["dropoffContents"] = "OK";
			$Adata["talkContents"] = "OK";
		}
		
		$returndata = json_encode($Adata);
		echo $returndata;
	}
	else {
		$id = strip_tags($_POST["id"]);
		$rr = $db->query("DELETE FROM queue WHERE id='$id'");
	}
?>
