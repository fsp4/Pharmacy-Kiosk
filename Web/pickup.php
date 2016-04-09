<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height">
	<title>Pick Up</title>
	<link rel="stylesheet" type="text/css" href="menu.css"/>
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
	<script>
		//only disabling date of birth. assume names still needed.
		function disableFields(cb) {
			document.getElementById("dob").disabled = cb.checked ? true : false;
		}
	</script>
</head>
<body>
	<header>
		<p><img src="pitt_logo.png"></p>
	</header>
	<div id="box">
		<legend><b> PICK UP FORM </b></legend>
		<form class="pure-form pure-form-aligned" method="POST" action="submit.php" onsubmit="return validateForm()">
			<fieldset>
				<div class="pure-control-group">
					<label id="checkbox" class="pure-checkbox">
						<input id="cb" onchange = "disableFields(this)" type="checkbox" name="returningcustomer" > 
							Returning customer to this store
					</label>
					<p>
						<label for="FirstName">First Name</label>
						<input id="FirstName" type="text" placeholder="First Name" name="firstname" required>
					</p>
					<p>
						<label for="LastName">Last Name</label>
						<input id="LastName" type="text" placeholder="Last Name" name="lastname" required>
					</p>
					<p>
						<label for="dob">Date of Birth</label>
						<input id="dob" type="date" placeholder="DOB" name="DOB" required>
					</p>
				</div>
				<div>
					<button type="submit" class="btnTypeTwo">SUBMIT</button>
        			<button type="button" onclick="location.href='main_menu.html'" class="btnTypeTwo">BACK</button>
        		</div>
        		<input type="hidden" name="type" value="pickup">
    		</fieldset>
		</form>
	</div>
</body>
</html>