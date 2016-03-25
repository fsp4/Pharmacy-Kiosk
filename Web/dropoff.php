<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Drop Off</title>
	<link rel="stylesheet" type="text/css" href="menu.css"/>
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
	<script>
		function goBack() {
			window.history.back();
		}

		//disabling insurance card number, date of birth fields. 
		function disableFields(cb) {
			document.getElementById("insurance").disabled = cb.checked ? true : false;
			document.getElementById("dob").disabled = cb.checked ? true : false;
		}
	</script>
</head>

<body>
	<header>
		<p><img src="pitt_logo.png" alt=""></p>
	</header>
	<div id="box">
		<legend><b> DROP OFF FORM </b></legend>
		<form class="pure-form pure-form-aligned" method="POST" action="submit.php">
			<fieldset style="background-color: 	#e6dbbe; mid-height:100px; border-radius: 25px;">
				<div class="pure-control-group" style="font-family: Palatino Linotype;">
					<label for="cb" class="pure-checkbox" style="width:270px">
						<input id="cb" onchange = "disableFields(this)" type="checkbox" name="returningcustomer" > 
						<span class="checkboxtext">
							Returning Customer to this store
						</span>
					</label>
					<br></br>
					
					<label for="relation">Relationship to Patient</label>
					<select id="relation" class="pure-input-1-2" name="relationship">
						<option>Self</option>
						<option>Relative</option>
						<option>Friend</option>
						<option>Other</option>
					</select>
					<br></br>
					
					<label for="insurance">Insurance Card Number</label>
					<input id="insurance" type="text" placeholder="Insurance Card" name="insurance" required>
					<br></br>
					
					<label for="dob">Date of Birth</label>
					<input id="dob" type="date" placeholder="DOB" name="DOB" required>
					<br></br>
				</div>
				<div id="section">
					<button type="submit" class="btnTypeTwo">SUBMIT</button>
        			<button onclick="goBack()" class="btnTypeTwo">BACK</button>
        			<input type="hidden" name="type" value="dropoff">
        		</div>
    		</fieldset>
		</form>
	</div>
</body>
</html>
