<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height">
	<title>New Drop Off</title>
	<link rel="stylesheet" type="text/css" href="menu.css"/>
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="icon" type="image/ico" href="favicon.ico?">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
	// adds tooltip to allergies field
	$(function() {
		$( "#allergies" ).tooltip({
			position: { my: "left+5center", at: "right center" }
		});
	});
	</script>
</head>
<body>
	<header>
		<p><img src="university-of-pittsburgh-logo-long.svg"></p>
	</header>
	<div id="box">
		<legend><b> DROP OFF FORM </b></legend>
		<form class="pure-form pure-form-aligned" method="POST" action="submit.php">
			<fieldset>
				<div class="pure-control-group">				
					<label id="checkbox" class="pure-checkbox">
						<input type="checkbox" name="refill" > 
							Please enroll me in Automatic Refill
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
						<label for="MiddleName">Middle Initial</label>
						<input id="MiddleName" type="text" placeholder="Middle Initial" name="middlename" required>
					</p>
					<p>
						<label for="dob">Date of Birth</label>
						<input id="dob" type="date" placeholder="DOB" name="DOB" required>
					</p>
					<p>
						<input type="radio" name="gender" value="male" required> Male
 		 				<input type="radio" name="gender" value="female" required> Female
					</p>
					<p>
						<input type="radio" name="position" value="student" required> Student
 		 				<input type="radio" name="position" value="faculty" required> Faculty
 		 				<input type="radio" name="position" value="athlete" required> Athlete
					</p>
					<p>
						<label for="homeAddress">Home Address</label>
						<input id="homeAddress" type="text" placeholder="Street" name="home" required>	
					</p>
					<p id="address">
						<input id="city" type="text" placeholder="City" name="city" required>
						<input id="state" type="text" maxlength="2" size="5" placeholder="State" name="state" required>
						<input id="zip" type="number" maxlength="5" size="5" name="zip" placeholder="Zip" required>
					</p>
					<p>
						<label for="phone">Phone Number</label>
						<input id="phone" type="text" placeholder="Phone Number" name="phone" required><br>
						<input type="radio" name="phone_type" value="mobile" required> Mobile
 		 				<input type="radio" name="phone_type" value="home" required> Home
 		 				<input type="radio" name="phone_type" value="work" required> Work

					</p>
					<p>
						I would like to receive pharmacy notifications via <br>
						<input type="radio" name="notifications" value="text" required> Text
 		 				<input type="radio" name="notifications" value="voice" required> Voice
					</p>
					<p>
						<label for="allergies">Medication Allergies</label>
						<input id="allergies" type="text" name="allergies_list" title="Please also describe the reaction.">
					</p>
					<p>
						<label for="med">Current Medications</label>
						<input id="med" type="text" name="current_meds">
					</p>
					<p>
						<label for="sign">Signature</label>
						<input id="sign" type="text" name="signature" required>
					</p>
					<p>
						<label for="date">Date</label>
						<input id="date" type="date" name="date" value="<?php print(date("Y-m-d")); ?>" required>
					</p>
				</div>
				<div class="stackingBtns">
					<input type="hidden" name="type" value="newdropoff">
					<button type="submit" class="btt">SUBMIT</button>
				</form>
					<button onclick="location.href='dropoff.html'" class="btt">BACK</button>
				</div>
		</fieldset>
	</div>
</body>
</html>