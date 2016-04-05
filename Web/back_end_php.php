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
		$db->query("CREATE TABLE `kiosk_queue`.`queue` ( `id` INT NOT NULL AUTO_INCREMENT, `type` TEXT NOT NULL, `first_name` TEXT NOT NULL,
		`last_name` TEXT NOT NULL, `date_of_birth` DATE NOT NULL, `relation` TEXT NOT NULL, `returning_customer` TEXT NOT NULL,
		`insurance_card_number` INT(99) NOT NULL, PRIMARY KEY (`id`))");
		
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
		if ($client_rows != $database_rows) {
			$Adata["Type"] = "Update";
			$pickupContents = Array();
			$dropoffContents = Array();
			for ($i = 0; $i < $database_rows; $i++) {
				$rr->data_seek($i);
				$curr = $rr->fetch_array();
				$currArr = Array();
				$currArr["id"] = $curr["id"];
				$currArr["type"] = $curr["type"];
				$currArr["first_name"] = $curr["first_name"];
				$currArr["last_name"] = $curr["last_name"];
				$currArr["date_of_birth"] = $curr["date_of_birth"];
				$currArr["relation"] = $curr["relation"];
				$currArr["returning_customer"] = $curr["returning_customer"];
				$currArr["insurance_card_number"] = $curr["insurance_card_number"];
				if (strcmp($curr["type"], 'pickup') == 0) {
					$pickupContents[] = $currArr;
					$Adata["pickupContents"] = $pickupContents;
				}
				else {
					$dropoffContents[] = $currArr;
					$Adata["dropoffContents"] = $dropoffContents;
				}
			}
			
		}
		else {
			$Adata["Type"] = "NA";
			$Adata["pickupContents"] = "OK";
			$Adata["dropoffContents"] = "OK";
		}
		
		$returndata = json_encode($Adata);
		echo $returndata;
	}
	else {
		$id = strip_tags($_POST["id"]);
		$rr = $db->query("DELETE FROM queue WHERE id='$id'");
	}
?>
