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
		//assume drugFAQ.xml is file name with FAQ contents.
		xhttp.open("GET", "drugFAQ.xml", true); 
		xhttp.send();

		function parseFAQ(xml) {
		    var x, y, z, d, drug, i, xmlDoc, txt;
		    xmlDoc = xml.responseXML;
		    txt = "";
		    x = xmlDoc.getElementsByTagName('drug');
		    for (i = 0 ; i < x.length; i++) {
		    	d = xmlDoc.getElementsByTagName("drug")[i];
		    	drug = d.attributes.getNamedItem("name").nodeValue;
		    	
		    	//append drug name as heading
		    	//TODO accordion for heading
		    	var node = document.createElement("h3")
		    	var textnode = document.createTextNode(drug);
		    	node.appendChild(textnode);
		    	document.getElementById("accordion").appendChild(node);
		    	var divNode = document.createElement("div");
    			var pNode = document.createElement("p");
    			var faq = "faq" + i.toString();
    			pNode.setAttribute("id", faq);
    			divNode.appendChild(pNode);
    			document.getElementById("accordion").appendChild(divNode);

				y = d.getElementsByTagName('Question');
		    	z = d.getElementsByTagName('Answer');
		    	txt = ""
		    	//assume: lengths of x and y SHOULD logically be equivalent for question and answer format.
		    	for (j = 0 ; j < y.length; j++){
        			txt += "<b>" + y[j].childNodes[0].nodeValue + "<br></b>"
        					+ z[j].childNodes[0].nodeValue + "<br><br>";
		    	}
		    	document.getElementById(faq).innerHTML = txt;
			}
		    
		}
	</script>
</body>
</html>
