<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height">
	<title>Returning Drop Off</title>
	<link rel="stylesheet" type="text/css" href="menu.css"/>
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
  		$(function() {
    		$( "#dialog" ).dialog({
      		autoOpen: false,
      		dialogClass: 'title',
      		show: {
        		effect: "drop",
        		duration: 100
     		},
      		hide: {
        		effect: "drop",
        		duration: 100
      		}
    		});
    		$( "#open" ).click(function() {
      			$( "#dialog" ).dialog( "open" );
    		});
  		});
	</script>
</head>

<body>
	<header>
		<p><img src="pitt_logo.png"></p>
	</header>
	<div id="box">
		<legend><b> DROP OFF FORM </b></legend>
		<form class="pure-form pure-form-aligned" method="POST" action="submit.php">
			<fieldset>
				<div class="pure-control-group">				
					<span>Relationship to Patient</span>
						<select id="relation" class="pure-input-1-2" name="relationship">
							<option>Self</option>
							<option>Relative</option>
							<option>Friend</option>
							<option>Other</option>
						</select>
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
					<label id="checkbox" class="pure-checkbox">
						<input type="checkbox" name="allergy" > 
						Do you have any new allergies?
					</label>	
					<br>				
					<span id="open">
						<u>Insurance card not present?</u>
					</span>					
				</div>
				<div id="dialog" title="Insurance">
					<p>
						If you do not have your insurance card with you at the moment, you are still able to
						purchase your medications, however the University strives to make this process as affordable as possbile
						You can ask your parents or come up to the counter to ask the staff the best approach to retrieving the necessary
						information from your insurance card.
					</p>
				</div>
				
				<div id="section">
					<button type="submit" class="btnTypeTwo">SUBMIT</button>
        			<button onclick="location.href='dropoff.php'" class="btnTypeTwo">BACK</button>
        		</div>
        		<input type="hidden" name="type" value="returningdropoff">
    		</fieldset>
		</form>
	</div>
</body>
</html>