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
	
	// type 1 = check refresh
	if ($type == 1) {
		$client_rows = strip_tags($_POST["rows"]);
		$client_archive_rows = strip_tags($_POST["archive_rows"]);
		// get rows from database, ordering by insert_time
		$queue_rows = $db->query("SELECT * FROM queue ORDER BY insert_time");
		$queue_archive_rows =  $db->query("SELECT * FROM queue_archive ORDER BY insert_time");
		
		// get number of rows
		$queue_rows_num = $queue_rows->num_rows;
		$queue_archive_rows_num = $queue_archive_rows->num_rows;
		
		$Adata["num"] = $client_rows . " " . $queue_rows_num;
		$Adata["pickupContents"] = "OK";
		$Adata["dropoffContents"] = "OK";
		$Adata["talkContents"] = "OK";
		$Adata["pickupArchiveContents"] = "OK";
		$Adata["dropoffArchiveContents"] = "OK";
		$Adata["talkArchiveContents"] = "OK";
		$Adata["crows"] = "$client_rows";
		$Adata["drows"] = "$queue_rows_num";
		
		// if new item(s) in database
		if (($client_rows != $queue_rows_num) || $client_archive_rows != $queue_archive_rows_num) {
			$Adata["Type"] = "Update";
			
			// get queue items
			$pickupContents = Array();
			$dropoffContents = Array();
			$talkContents = Array();
			for ($i = 0; $i < $queue_rows_num; $i++) {
				$queue_rows->data_seek($i);
				$curr = $queue_rows->fetch_array();
				$currArr = Array();
				
				$currArr["id"] = $curr["id"];
				$currArr["type"] = $curr["type"];
				$currArr["first_name"] = $curr["first_name"];
				$currArr["last_name"] = $curr["last_name"];
				$currArr["date_of_birth"] = $curr["date_of_birth"];
				$currArr["allergies"] = $curr["allergies"];
				$currArr["comment"] = $curr["comment"];
				$currArr["relation"] = $curr["relation"];
				$currArr["timestamp"] = $curr["timestamp"];
				
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
			
			// get queue archive items
			$pickupArchiveContents = Array();
			$dropoffArchiveContents = Array();
			$talkArchiveContents = Array();
			for ($i = 0; $i < $queue_archive_rows_num; $i++) {
				$queue_archive_rows->data_seek($i);
				$curr = $queue_archive_rows->fetch_array();
				$currArr = Array();
				
				$currArr["id"] = $curr["id"];
				$currArr["type"] = $curr["type"];
				$currArr["first_name"] = $curr["first_name"];
				$currArr["last_name"] = $curr["last_name"];
				$currArr["date_of_birth"] = $curr["date_of_birth"];
				$currArr["allergies"] = $curr["allergies"];
				$currArr["comment"] = $curr["comment"];
				$currArr["relation"] = $curr["relation"];
				$currArr["timestamp"] = $curr["timestamp"];
				
				if (strcmp($curr["type"], 'pickup') == 0) {
					$pickupArchiveContents[] = $currArr;
					$Adata["pickupArchiveContents"] = $pickupArchiveContents;
				}
				else if ((strcmp($curr["type"], 'returningdropoff') == 0) || ((strcmp($curr["type"], 'newdropoff') == 0))){
					$dropoffArchiveContents[] = $currArr;
					$Adata["dropoffArchiveContents"] = $dropoffArchiveContents;
				}
				else{
					$talkArchiveContents[] = $currArr;
					$Adata["talkArchiveContents"] = $talkArchiveContents;
				}	
			}
		}
		// no new item(s) in database
		else {
			$Adata["Type"] = "NA";
			$Adata["pickupContents"] = "OK";
			$Adata["dropoffContents"] = "OK";
			$Adata["talkContents"] = "OK";
			$Adata["pickupArchiveContents"] = "OK";
			$Adata["dropoffArchiveContents"] = "OK";
			$Adata["talkArchiveContents"] = "OK";
		}
		
		$returndata = json_encode($Adata);
		echo $returndata;
	}
	// flag/comment
	else if ($type == 5) {
		$id = strip_tags($_POST["id"]);
		$comment = strip_tags($_POST["comment"]);
		$db->query("UPDATE queue_archive SET comment='$comment' WHERE id='$id'");
	}
	// delete all items in queue_archive database table
	else if ($type == 6) {
		$db->query("truncate queue_archive");
	}
	// move archive queue item back to queue
	else if ($type == 7) {
		$id = strip_tags($_POST["id"]);
		$db->query("INSERT INTO queue SELECT * FROM queue_archive WHERE id='$id'");
		// update insert_time
		$time = date("Y-m-d H:i:s");
		$db->query("UPDATE queue SET insert_time='$time' WHERE id='$id'");
		$db->query("DELETE FROM queue_archive WHERE id='$id'");
	}
	// next pickup/dropoff/question type = 2/3/4
	else {
		$id = strip_tags($_POST["id"]);
		$db->query("INSERT INTO queue_archive SELECT * FROM queue WHERE id='$id'");
		// update insert_time
		date_default_timezone_set('US/Eastern');
		$time = date("Y-m-d H:i:s");
		$db->query("UPDATE queue_archive SET insert_time='$time' WHERE id='$id'");
		$db->query("DELETE FROM queue WHERE id='$id'");
	}
?>