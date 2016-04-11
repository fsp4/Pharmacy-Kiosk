
<html>
<head>
	<title>Kiosk Queue</title>
	<link rel="stylesheet" type="text/css" href="backend.css"/>	
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
 	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

	<script type="text/javascript">
		$(function() {
			$( "#tabs" ).tabs({
				collapsible: true
			});
		});
		$(function() { 
			var tabs = $( "#tabs" ).tabs();
			// close icon: removing the tab on click
			tabs.delegate( "span.ui-icon-close", "click", function() {
			  var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
			  $( "#" + panelId ).remove();
			  tabs.tabs( "refresh" );
			});
 
			tabs.bind( "keyup", function( event ) {
			  if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) {
				var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
				$( "#" + panelId ).remove();
				tabs.tabs( "refresh" );
			  }
			});
		  });
		var pickupQueue = new Array(), dropoffQueue = new Array(), talkQueue = new Array(), pickuptableCount = 0, dropoffQueueCount = 0, talkQueueCount = 0, tabsCount = 0, t;
		function Start() {
			refreshPage();
		}

		function nextPickup() {
			var httpRequest;

			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				httpRequest = new XMLHttpRequest();
				if (httpRequest.overrideMimeType) {
					httpRequest.overrideMimeType('text/xml');
				}
			}
			else if (window.ActiveXObject) { // IE
				try {
					httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e) {
					try {
						httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (e) {}
				}
			}
			if (!httpRequest) {
				alert('Cannot create an XMLHTTP instance');
			}

			var type = 2; 
			var T = document.getElementById("pickupTable");
			var id = T.getElementsByTagName("li")[0].className;
			var item = removeRowFromPickupTable(id);			
			displayData(item);
			jQuery("#pickupTable li:first-child").remove();


			var data = 'type=' + type + '&id=' + id;
		
			// comment this out to disable database connection for easier testing
			//httpRequest.open('POST', 'back_end_php.php', true);
			//httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			//httpRequest.onreadystatechange = function() { displayData(); } ;
			//httpRequest.send(data);
			//
		}
	
		function nextDropoff() {
			var httpRequest;

			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				httpRequest = new XMLHttpRequest();
				if (httpRequest.overrideMimeType) {
					httpRequest.overrideMimeType('text/xml');
				}
			}
			else if (window.ActiveXObject) { // IE
				try {
					httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e) {
					try {
						httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (e) {}
				}
			}
			if (!httpRequest) {
				alert('Cannot create an XMLHTTP instance');
			}

			var type = 3; 
			var T = document.getElementById("dropoffTable");
			var id = T.getElementsByTagName("li")[0].className;
			console.log(id);
			var item = removeRowFromDropOffTable(id);
			displayData(item);
			jQuery("#dropoffTable li:first-child").remove();

			var data = 'type=' + type + '&id=' + id;
		
			// comment this out to disable database connection for easier testing
			//httpRequest.open('POST', 'back_end_php.php', true);
			//httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			//httpRequest.onreadystatechange = function() { displayData(); } ;
			//httpRequest.send(data);
			//
		}
		
		function nextTalk() {
			var httpRequest;

			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				httpRequest = new XMLHttpRequest();
				if (httpRequest.overrideMimeType) {
					httpRequest.overrideMimeType('text/xml');
				}
			}
			else if (window.ActiveXObject) { // IE
				try {
					httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e) {
					try {
						httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (e) {}
				}
			}
			if (!httpRequest) {
				alert('Cannot create an XMLHTTP instance');
			}

			var type = 4; 
			var T = document.getElementById("talkTable");
			var id = T.getElementsByTagName("li")[0].className;
			console.log(id);
			var item = removeRowFromTalkTable(id);
			displayData(item);
			jQuery("#talkTable li:first-child").remove();

			var data = 'type=' + type + '&id=' + id;
		
			// comment this out to disable database connection for easier testing
			//httpRequest.open('POST', 'back_end_php.php', true);
			//httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			//httpRequest.onreadystatechange = function() { displayData(); } ;
			//httpRequest.send(data);
			//
		}
	
		function displayData(item) {
			var id = item.id;
			var type = item.type;
			var first_name = item.first_name;
			var last_name = item.last_name;
			var date_of_birth = item.date_of_birth;
			var relation = item.relation;
			var returning_customer = item.returning_customer;
			var allergies = item.allergies;
			if(allergies == "1")
				allergies = "Yes";
			else
 				allergies= "No";
 			
 			// returning customer checkbox			
			var label = document.createElement("label");
			label.innerHTML = "<b>Returning Customer: </b>";
			var checkbox = document.createElement("input");
			checkbox.type = "checkbox";
			checkbox.name = "returningcustomer";
			if (returning_customer == ("yes")) {
				checkbox.checked = true;
			}
			checkbox.disabled = true; 			
 							
			var num_tabs = $("div#tabs ul li").length + 1;
			$("div#tabs ul").append(
				"<li><a href='#" + id + "'>" + last_name + "</a><span class=\"ui-icon ui-icon-close\"></span></li>"
			);
			
			if(type != "talk"){
				$("div#tabs").append(
					"<div id='" + id + "'>" + 
					"<p><b>First Name: </b>" + first_name + "</p>" +
					"<p><b>Last Name: </b>" + last_name + "</p>" +
					"<p><b>Date of Birth: </b>" + date_of_birth + "</p>" +
					"<p><b>Allergies: </b>" + allergies + "</p>" + 

					"</div>"
				);
			}
			else{
				$("div#tabs").append(
					"<div id='" + id + "'>" + 
					"<p><b>First Name: </b>" + first_name + "</p>" +
					"<p><b>Last Name: </b>" + last_name + "</p>" +

					"</div>"
				);
			}
			$("div#tabs").tabs("refresh");
		}
	
		function updateRows(httpRequest) {
			if (httpRequest.readyState == 4) {
				if (httpRequest.status == 200) {
					var data = httpRequest.responseText;
					var newData = JSON.parse(data);
					var rettype = newData.Type;
					console.log(rettype);
					if (rettype == "Update") {
						pickupQueueCount = 0;
						dropoffQueueCount = 0;
						talkQueueCount = 0;
						var pickupRows = newData.pickupContents;
						var dropoffRows = newData.dropoffContents;
						var talkRows = newData.talkContents;

						
						if (pickupRows != "OK") {
							for (var i = 0; i < pickupRows.length; i++) {
								var theRow = pickupRows[i];
								addRowToList(theRow.id, theRow.type, theRow.refill, theRow.first_name, theRow.last_name, theRow.middle, theRow.date_of_birth, theRow.gender, theRow.position, theRow.home_address, theRow.city, theRow.state, theRow.zip, theRow.phone, theRow.phone_type, theRow.notifications, theRow.allergies_list, theRow.current_meds, theRow.signature, theRow.date, theRow.relation, theRow.returning_customer, theRow.insurance_card_number, theRow.allergies);
							}
						}
						if (dropoffRows != "OK") {
							for (var i = 0; i < dropoffRows.length; i++) {
								var theRow = dropoffRows[i];
								addRowToList(theRow.id, theRow.type, theRow.refill, theRow.first_name, theRow.last_name, theRow.middle, theRow.date_of_birth, theRow.gender, theRow.position, theRow.home_address, theRow.city, theRow.state, theRow.zip, theRow.phone, theRow.phone_type, theRow.notifications, theRow.allergies_list, theRow.current_meds, theRow.signature, theRow.date, theRow.relation, theRow.returning_customer, theRow.insurance_card_number, theRow.allergies);
							}
						}
						if (talkRows != "OK") {
							for (var i = 0; i < talkRows.length; i++) {
								var theRow = talkRows[i];
								addRowToList(theRow.id, theRow.type, theRow.refill, theRow.first_name, theRow.last_name, theRow.middle, theRow.date_of_birth, theRow.gender, theRow.position, theRow.home_address, theRow.city, theRow.state, theRow.zip, theRow.phone, theRow.phone_type, theRow.notifications, theRow.allergies_list, theRow.current_meds, theRow.signature, theRow.date, theRow.relation, theRow.returning_customer, theRow.insurance_card_number, theRow.allergies);
							}
						}
					
						showQueueTable();
					}
					else if (rettype == "NEW") {
						showQueueTable();
					}
				}
				else {
					alert('Problem with request');
				}
			}
		}

		function Queue(id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies) {
			this.id = id;
			this.type = type;
			this.refill = refill;
			this.first_name = first_name;
			this.last_name = last_name;
			this.middle = middle;
			this.date_of_birth = date_of_birth;
			this.gender = gender;
			this.position = position;
			this.home_address = home_address;
			this.city = city;
			this.state = state;
			this.zip = zip;
			this.phone = phone;
			this.phone_type = phone_type;
			this.notifications = notifications;
			this.allergies_list = allergies_list;
			this.current_meds = current_meds;
			this.signature = signature;
			this.date = date; 
			this.relation = relation;
			this.returning_customer = returning_customer;
			this.insurance_card_number = insurance_card_number;
			this.allergies = allergies;
		}

		function addRowToList(id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies) {
			var currItem = new Queue(id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies);		
			if (type == "pickup") {
				pickupQueue[pickupQueueCount] = currItem;
				pickupQueueCount++;
			}
			else if(type == "returningdropoff" || type == "newdropOff") {
				dropoffQueue[dropoffQueueCount] = currItem;
				dropoffQueueCount++;
			}
			else{
				talkQueue[talkQueueCount] = currItem;
				talkQueueCount++;
			}
		}
	
		function removeRowFromPickupTable(id) {
			var ret = 0;
			for (var i = 0; i < pickupQueue.length; i++) {
				if (pickupQueue[i].id == id) {
					ret = pickupQueue[i];
					pickupQueue.splice(i, 1);
					break;
				}
			}
			pickupQueueCount--;
			return ret;
		}
	
		function removeRowFromDropOffTable(id) {
			var ret = 0;
			for (var i = 0; i < dropoffQueue.length; i++) {
				if (dropoffQueue[i].id == id) {
					ret = dropoffQueue[i];
					dropoffQueue.splice(i, 1);
					break;
				}
			}
			dropoffQueueCount--;
			return ret;
		}
		
		function removeRowFromTalkTable(id) {
			var ret = 0;
			for (var i = 0; i < talkQueue.length; i++) {
				if (talkQueue[i].id == id) {
					ret = talkQueue[i];
					talkQueue.splice(i, 1);
					break;
				}
			}
			talkQueueCount--;
			return ret;
		}

		function showQueueTable() {
			for (var i = 0; i < pickupQueueCount; i++) {
				addRow(pickupQueue[i].id, pickupQueue[i].type, pickupQueue[i].refill, pickupQueue[i].first_name, pickupQueue[i].last_name, pickupQueue[i].middle, pickupQueue[i].date_of_birth, pickupQueue[i].gender, pickupQueue[i].position, pickupQueue[i].home_address, pickupQueue[i].city, pickupQueue[i].state, pickupQueue[i].zip, pickupQueue[i].phone, pickupQueue[i].phone_type, pickupQueue[i].notifications, pickupQueue[i].allergies_list, pickupQueue[i].current_meds, pickupQueue[i].signature, pickupQueue[i].date, pickupQueue[i].relation, pickupQueue[i].returning_customer, pickupQueue[i].insurance_card_number, pickupQueue[i].allergies);
			}
			for (var i = 0; i < dropoffQueueCount; i++) {
				addRow(dropoffQueue[i].id, dropoffQueue[i].type, dropoffQueue[i].refill, dropoffQueue[i].first_name, dropoffQueue[i].last_name, dropoffQueue[i].middle, dropoffQueue[i].date_of_birth, dropoffQueue[i].gender, dropoffQueue[i].position, dropoffQueue[i].home_address, dropoffQueue[i].city, dropoffQueue[i].state, dropoffQueue[i].zip, dropoffQueue[i].phone, dropoffQueue[i].phone_type, dropoffQueue[i].notifications, dropoffQueue[i].allergies_list, dropoffQueue[i].current_meds, dropoffQueue[i].signature, dropoffQueue[i].date, dropoffQueue[i].relation, dropoffQueue[i].returning_customer, dropoffQueue[i].insurance_card_number, dropoffQueue[i].allergies);
			}
			for (var i = 0; i < talkQueueCount; i++) {
				addRow(talkQueue[i].id, talkQueue[i].type, talkQueue[i].refill, talkQueue[i].first_name, talkQueue[i].last_name, talkQueue[i].middle, talkQueue[i].date_of_birth, talkQueue[i].gender, talkQueue[i].position, talkQueue[i].home_address, talkQueue[i].city, talkQueue[i].state, talkQueue[i].zip, talkQueue[i].phone, talkQueue[i].phone_type, talkQueue[i].notifications, talkQueue[i].allergies_list, talkQueue[i].current_meds, talkQueue[i].signature, talkQueue[i].date, talkQueue[i].relation, talkQueue[i].returning_customer, talkQueue[i].insurance_card_number, talkQueue[i].allergies);
			}
		}

		function addRow(id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies) {
			if (type == 'pickup') {
				var T = document.getElementById("pickupTable");
			}
			else if (type == 'returningdropoff' || type == 'newdropoff'){
				var T = document.getElementById("dropoffTable");

			}
			else
				var T = document.getElementById("talkTable");
		
			var li = document.createElement("li");
			li.id = "ui-state-default";
			li.className = id;
		
			if (type == 'pickup') {
				li.appendChild(document.createTextNode(last_name));
				T.appendChild(li);
			}
			else if (type == 'returningdropoff' || type == 'newdropoff'){
				if (type == 'returningdropoff') {				
					li.appendChild(document.createTextNode(last_name));
					T.appendChild(li);
				}
				else {
					li.appendChild(document.createTextNode(last_name));
					T.appendChild(li);
				}
			}
			else {
				li.appendChild(document.createTextNode(last_name));
				T.appendChild(li);
			}
		}

		function refreshPage() {
			var httpRequest;
 
			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				httpRequest = new XMLHttpRequest();
				if (httpRequest.overrideMimeType) {
					httpRequest.overrideMimeType('text/xml');
				}
			}
			else if (window.ActiveXObject) { // IE
				try {
					httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
					}
				catch (e) {
					try {
						httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (e) {}
				}
			}
			if (!httpRequest) {
				alert('Cannot create an XMLHTTP instance');
			}
 
			var type = 1; 
			var pickupRows = document.getElementById("pickupTable").length-1;
			var dropoffRows = document.getElementById("dropoffTable").length-1;
			var talkRows = document.getElementById("talkTable").length-1;
	
			var rows = pickupRows + dropoffRows + talkRows;

			if (rows == -1) {
				rows = 0;
			}
			var data = 'type=' + type + '&rows=' + rows;

			httpRequest.open('POST', 'back_end_php.php', true);
			httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			httpRequest.onreadystatechange = function() { updateRows(httpRequest); } ;
			httpRequest.send(data);
// 			t = setTimeout("refreshPage()", 15000);
		}
		
		$(function() {
    		$( "#pickupTable" ).sortable({
      			revert: true
    		});
    		$( "#dropoffTable" ).sortable({
      			revert: true
    		});
    		$( "#talkTable" ).sortable({
      			revert: true
    		});
			$( "ul, li" ).disableSelection();
		  });
		
	$(function() {
    		$( "#pickupTable" ).draggable({revert: true});
    		$( "#dropoffTable" ).draggable({revert: true});
    		$( "#talkTable" ).draggable({revert: true});
  		});  
		 
		$(function() {
    		$( "#tabs" ).droppable({
      			activeClass: "ui-state-highlight",
      			drop: function( event, ui ) {
      				var draggableId = ui.draggable.attr('class').split(" ")[0];
      				var table = ui.draggable.parent().attr('id');
      				next(draggableId, table);
             		$(ui.draggable).remove()
        		}
    		});
  		});
  			
		function next(dropId, table) {
			var httpRequest;

			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				httpRequest = new XMLHttpRequest();
				if (httpRequest.overrideMimeType) {
					httpRequest.overrideMimeType('text/xml');
				}
			}
			else if (window.ActiveXObject) { // IE
				try {
					httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e) {
					try {
						httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (e) {}
				}
			}
			if (!httpRequest) {
				alert('Cannot create an XMLHTTP instance');
			}
			if(table.includes('pickup')){
				var item = removeRowFromPickupTable(dropId);
				displayData(item);
				var data = 'type=pickup' + '&id=' + dropId;
			}
			if(table.includes('dropoff')){
				var item = removeRowFromDropOffTable(dropId);
				displayData(item);
				var data = 'type=dropoff' + '&id=' + dropId;
			}
			if(table.includes('talk')){
				var item = removeRowFromTalkTable(dropId);
				displayData(item);
				var data = 'type=talk' + '&id=' + dropId;
			}
			// comment this out to disable database connection for easier testing
			//httpRequest.open('POST', 'back_end_php.php', true);
			//httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			//httpRequest.onreadystatechange = function() { displayData(); } ;
			//httpRequest.send(data);
		}
	</script>
</head>
<body onload = "Start()">
	<div id="header"> <p><img src="pitt_logo.png"></p> </div>
	<div id="main-wrap">
		<div id="sidebar">			
			<label for="pickupTable">Pick Ups</label>	
			<ul id ="pickupTable"></ul>
			<br>
			<label for="dropoffTable">Drop Offs</label>
			<ul id = "dropoffTable"></ul>
			<br>
			<label for="talkTable">Questions</label>
			<ul id = "talkTable"></ul>
		</div>
		<div id="content-wrap">
			<div id="tabs">
				<ul id="list" class="ui-tabs-nav">
				</ul>
			</div>
		</div>
	</div>
	<div id="footer">
		<input type="button" class="btn" onclick="nextPickup()" value="Next Pickup">
		<input type="button" class="btn" onclick="nextDropoff()" value="Next Dropoff">
		<input type="button" class="btn" onclick="nextTalk()" value="Questions">
	</div>
</body>
</html>