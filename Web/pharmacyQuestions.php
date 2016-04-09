<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height">
	<title>Pharmacy Questions</title>
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
	<div id="accordion">
		<h3> Hours </h3>
		<div>
			<h3> Fall & Spring Terms </h3>
			<p> (September thru April) </p>
			<ul>
      			<li>Monday: 8:30 a.m. - 7 p.m. </li>
      			<li>Tuesday: 8:30 a.m. - 5 p.m. </li>
      			<li>Wednesday: 8:30 a.m. - 7 p.m. </li>
      			<li>Thursday: 8:30 a.m. - 7 p.m. </li>
      			<li>Friday: 8:30 a.m. - 5 p.m. </li>
      			<li>Saturday: 10 a.m. - 3 p.m. </li>
      			<li>Sunday: CLOSED </li>
    		</ul>
    		<h3> Summer Terms </h3>
    		<p> (May thru August) </p>
			<ul>
      			<li>Monday - Friday: 8:30 a.m. - 5 p.m. </li>
      			<li>Saturday: CLOSED </li>
      			<li>Sunday: CLOSED </li>
    		</ul>
		</div>
		
		<h3> Directions </h3>
		<div>
			<h3> Campus Address </h3>
			<ul>
				<li><b> University of Pittsburgh </b></li>
				<li>Student Health Service</li>
				<li>Wellness Center, Nordenberg Hall</li>
				<li>119 University Place</li>
				<li>Pittsburgh, PA 15260</li>
				<li>412.383.1800</li>
			</ul>
			<p><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2552.1143637648897!2d-79.95819508501539!3d40.444043479361774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8834f22918fce585%3A0xe5b41637e02575f4!2s119+University+Pl%2C+Pittsburgh%2C+PA+15213!5e1!3m2!1sen!2sus!4v1458164579366" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></p>
		</div>
		
		<h3> Contact Information </h3>
		<div>
			<h3> Telephone </h3>
			<p><b> To Schedule Appointments for Clinic</b> <span style="margin-left:2em">412.383.1800</span></p>
			<p><b> To Request Medical Records </b> <span style="margin-left:6em">412.383.1800</span></p>
			<p><b> Pharmacy </b><span style="margin-left:14.6em">412.383.1850</span></p>
			<p><span style="margin-left:19.3em">412.383.1851</span></p>
			<p><b> Health Education and Promotion </b><span style="margin-left:4em">412.383.1830</span></p>
			<p><b> Administration </b><span style="margin-left:12em">412.383.1832</span></p>
		</div>
		
		<h3> Questions </h3>
		<div>
			<p id = "faq"></p>
			<script>
				//Script currently assumes xml file as stored on server, and this file on server can be manipulated by the pharmacy.
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				    if (xhttp.readyState == 4) {
				    	if (xhttp.status == 200 || xhttp.status == 0) {
				        	parseFAQ(xhttp);
						}
						else {
							alert("XML specifications for FAQ not found.");
						}
				    }
				};
				xhttp.open("GET", "faq.xml", true); 
				xhttp.send();
	
				function parseFAQ(xml) {
				    var x, i, xmlDoc, txt;
				    xmlDoc = xml.responseXML;
				    txt = "";
				    x = xmlDoc.getElementsByTagName('Question');
				    y = xmlDoc.getElementsByTagName('Answer')
				    //assume: lengths of x and y SHOULD logically be equivalent for question and answer format.
				    for (i = 0 ; i <x.length; i++) {
		        		txt += "<b>" + x[i].childNodes[0].nodeValue + "<br></b>"
		        				+ y[i].childNodes[0].nodeValue + "<br>";
				    }
				    document.getElementById("faq").innerHTML = txt;
				}
			</script>
		</div>	
	</div>
</body>
</html>
