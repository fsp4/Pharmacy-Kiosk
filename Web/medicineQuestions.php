<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height">
	<title>Medicine Questions</title>
	<link rel="stylesheet" type="text/css" href="menu.css"/>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
		$(function() {
    		$( "#accordion" ).accordion({
      			collapsible: true,
      			active: false,
      			heightStyle: "content",
				autoFill: false
    		});
  		});
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
	<button onclick="location.href='main_menu.html'" class="btnTypeThree">HOME</button>
	<button id="open" class="btnTypeThree">TALK TO TECHNICIAN</button>
	<br></br>	
	<div id="dialog" title="TALK TO TECHNICIAN">
  		<p>Please enter your name to talk to a technician</p>
  		<form class="pure-form pure-form-aligned" method="POST" action="submit.php" onsubmit="location.href='thankyou.php'">
			<div class="pure-control-group">
				<p>
				<input id="FirstName" type="text" placeholder="First Name" name="firstname" required>
				</p>
				<p>
				<input id="LastName" type="text" placeholder="Last Name" name="lastname" required>
				</p>
			</div>
			<div id="section">
				<button type="submit" class="btnTypeTwo">SUBMIT</button>
				<input type="hidden" name="type" value="talk">
			</div>
  		</form>
	</div>
	<br></br>
	<div id="accordion">
		<h3> Diabetes Drugs </h3>
		<div>
			<p></p>
		</div>
		
		<h3> Antibiotics </h3>
		<div>
			<p></p>
		</div>
		
		<h3> Birth Control </h3>
		<div>
			<p></p>
		</div>	
		
		<h3> Blood Pressure </h3>
		<div>
			<p></p>
		</div>	
	</div>
</body>
</html>
