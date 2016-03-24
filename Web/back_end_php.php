<?php
	$db = new mysqli('localhost', 'root', '', 'kiosk_queue');
	if ($db->connect_error):
	   die ("Could not connect to db " . $db->connect_error);
	endif;
	
	$Adata = Array();
	$type = strip_tags($_POST["type"]);
	
	if ($type == 1) {
		$client_rows = strip_tags($_POST["rows"]);
		$newrows="";
		$rr = $db->query("select * from queue");
		$database_rows = $rr->num_rows;
		$Adata["num"] = $client_rows . " " . $database_rows;
		if ($client_rows != $database_rows) {
			$Adata["Type"] = "Update";
			$contents = Array();
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
				$contents[] = $currArr;
			}
			$Adata["Contents"] = $contents;
		}
		else {
			$Adata["Type"] = "NA";
			$Adata["Contents"] = "OK";
		}
		
		$returndata = json_encode($Adata);
		echo $returndata;
	}
	else {
		$id = strip_tags($_POST["id"]);
		$rr = $db->query("DELETE FROM queue WHERE id='$id'");
	}
?>
