
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
	
// Drag and drop
// 		function dragAndDropTableInit() {
// 			var table = document.getElementById('pickupTable');
// 			var tableDnD = new TableDnD();
// 			tableDnD.init(table);
// 		}
// 	
// 	
// 		function wrap(top, selector, bottom) {
// 			var matches = document.querySelectorAll(selector);
// 			for (var i = 0; i < matches.length; i++){
// 				var modified = top + matches[i].outerHTML + bottom;
// 				matches[i].outerHTML = modified;
// 				console.log(modified);
// 			}
// 		}
		
	
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
// 			var id = T.rows[1].id;
			var id = T.getElementsByTagName("li")[0].className;
			var item = removeRowFromPickupList(id);			
			displayData(item);
			// T.deleteRow(1);
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
			// var id = T.rows[1].id;
			var id = T.getElementsByTagName("li")[0].className;
			console.log(id);
			var item = removeRowFromDropOffList(id);
			displayData(item);
			jQuery("#dropoffTable li:first-child").remove();
// 			T.deleteRow(1);

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
			var item = removeRowFromTalkList(id);
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
	
		function removeRowFromPickupList(id) {
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
	
		function removeRowFromDropOffList(id) {
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
		
		function removeRowFromTalkList(id) {
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
// 			var P = document.getElementById("pickup");
// 			var D = document.getElementById("dropoff");
// 			var Q = document.getElementById("talk");
// 			var pParent = P.parentNode;
// 			var dParent = D.parentNode;
// 			var qParent = Q.parentNode;
// 			console.log(pParent);
// 			console.log(dParent);
// 			console.log(qParent);
			// var newPT = document.createElement('table');
// 			newPT.setAttribute('id', 'pickupTable');
//  		newPT.border = 1;
// 			newPT.className = 'pickuptable';
// 			var hprow = newPT.insertRow(0);
// 			hprow.align = 'left';
// 			var newPT = document.createElement('ul');
// 			newPT.setAttribute('id', 'pickupTable');
// 			newPT.className = 'pickuptable';
		
// 			var newDT = document.createElement('table');
// 			newDT.setAttribute('id', 'dropoffTable');
//  		newDT.border = 1;
// 			newDT.className = 'dropofftable';
// 			var hdrow = newDT.insertRow(0);
// 			hdrow.align = 'left';
// 			var newDT = document.createElement('ul');
// 			newDT.setAttribute('id', 'dropoffTable');
// 			newDT.className = 'dropofftable';
// 			
// 			var newQT = document.createElement('table');
// 			newQT.setAttribute('id', 'talkTable');
// 			newQT.border = 1;
// 			newQT.className = 'talktable';
// 			var hqrow = newQT.insertRow(0);
// 			hqrow.align = 'left';
// 			var newQT = document.createElement('ul');
// 			newQT.setAttribute('id', 'talkTable');
// 			newQT.className = 'talktable';
		
// 			var currCell = hprow.insertCell(0);
// 			var currCell1 = hdrow.insertCell(0);
// 			var currCell2 = hqrow.insertCell(0);
// 			var contents = document.createTextNode('Type');
// 			var contents1 = document.createTextNode('Type');
// 			var contents2 = document.createTextNode('Type');
// 			currCell.appendChild(contents);
// 			currCell1.appendChild(contents1);
// 			currCell2.appendChild(contents2);

// 			var currCell = hprow.insertCell(1);
// 			var currCell1 = hdrow.insertCell(1);
// 			var currCell2 = hqrow.insertCell(1);
// 			contents = document.createTextNode('First Name');
// 			contents1 = document.createTextNode('First Name');
// 			contents2 = document.createTextNode('First Name');
// 			currCell.appendChild(contents);
// 			currCell1.appendChild(contents1);
// 			currCell2.appendChild(contents2);

// 			var currCell = hprow.insertCell(1);
// 			var currCell1 = hdrow.insertCell(1);
// 			var currCell2 = hqrow.insertCell(1);
// 			contents = document.createTextNode('Last Name');
// 			contents1 = document.createTextNode('Last Name');
// 			contents2 = document.createTextNode('Last Name');
// 			currCell.appendChild(contents);
// 			currCell1.appendChild(contents1);
// 			currCell2.appendChild(contents2);

// 			var currCell = hprow.insertCell(3);
// 			var currCell1 = hdrow.insertCell(3);
// 			contents = document.createTextNode('Date of Birth');
// 			contents1 = document.createTextNode('Date of Birth');
// 			currCell.appendChild(contents);
// 			currCell1.appendChild(contents1);

// 			var currCell1 = hdrow.insertCell(4);
// 			contents1 = document.createTextNode('Relation');
// 			currCell1.appendChild(contents1);
// 
// 			var currCell = hprow.insertCell(4);
// 			contents = document.createTextNode('Returning Customer');
// 			currCell.appendChild(contents);
// 
// 			var currCell1 = hdrow.insertCell(5);
// 			contents1 = document.createTextNode('Allergies');
// 			currCell1.appendChild(contents1);

// 			pParent.replaceChild(newPT, P);
// 			dParent.replaceChild(newDT, D);
// 			qParent.replaceChild(newQT, Q);

			for (var i = 0; i < pickupQueueCount; i++) {
				addRow(pickupQueue[i].id, pickupQueue[i].type, pickupQueue[i].refill, pickupQueue[i].first_name, pickupQueue[i].last_name, pickupQueue[i].middle, pickupQueue[i].date_of_birth, pickupQueue[i].gender, pickupQueue[i].position, pickupQueue[i].home_address, pickupQueue[i].city, pickupQueue[i].state, pickupQueue[i].zip, pickupQueue[i].phone, pickupQueue[i].phone_type, pickupQueue[i].notifications, pickupQueue[i].allergies_list, pickupQueue[i].current_meds, pickupQueue[i].signature, pickupQueue[i].date, pickupQueue[i].relation, pickupQueue[i].returning_customer, pickupQueue[i].insurance_card_number, pickupQueue[i].allergies);
			}
			for (var i = 0; i < dropoffQueueCount; i++) {
				addRow(dropoffQueue[i].id, dropoffQueue[i].type, dropoffQueue[i].refill, dropoffQueue[i].first_name, dropoffQueue[i].last_name, dropoffQueue[i].middle, dropoffQueue[i].date_of_birth, dropoffQueue[i].gender, dropoffQueue[i].position, dropoffQueue[i].home_address, dropoffQueue[i].city, dropoffQueue[i].state, dropoffQueue[i].zip, dropoffQueue[i].phone, dropoffQueue[i].phone_type, dropoffQueue[i].notifications, dropoffQueue[i].allergies_list, dropoffQueue[i].current_meds, dropoffQueue[i].signature, dropoffQueue[i].date, dropoffQueue[i].relation, dropoffQueue[i].returning_customer, dropoffQueue[i].insurance_card_number, dropoffQueue[i].allergies);
			}
			for (var i = 0; i < talkQueueCount; i++) {
				addRow(talkQueue[i].id, talkQueue[i].type, talkQueue[i].refill, talkQueue[i].first_name, talkQueue[i].last_name, talkQueue[i].middle, talkQueue[i].date_of_birth, talkQueue[i].gender, talkQueue[i].position, talkQueue[i].home_address, talkQueue[i].city, talkQueue[i].state, talkQueue[i].zip, talkQueue[i].phone, talkQueue[i].phone_type, talkQueue[i].notifications, talkQueue[i].allergies_list, talkQueue[i].current_meds, talkQueue[i].signature, talkQueue[i].date, talkQueue[i].relation, talkQueue[i].returning_customer, talkQueue[i].insurance_card_number, talkQueue[i].allergies);
			}
		
			/* // Drag and drop
			hrow.setAttribute("NoDrag", "1");
			hrow.setAttribute("NoDrop", "1");
			dragAndDropTableInit();
			*/
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

// 			li.style.background = "#e6dbbe";
// 			li.style.margin = "0 5px 5px 5px";
// 			li.style.padding = "5px";

			li.className = id;
		
			if (type == 'pickup') {
// 				var C = R.insertCell(0);
// 				var txt = document.createTextNode(type);
// 				C.appendChild(txt);
			
// 				C = R.insertCell(1);
// 				txt = document.createTextNode(first_name);
// 				C.appendChild(txt);
			
// 				C = R.insertCell(0);
// 				txt = document.createTextNode(last_name);
// 				C.appendChild(txt);
				li.appendChild(document.createTextNode(last_name));
				T.appendChild(li);

// 				C = R.insertCell(3);
// 				if(date_of_birth == "0000-00-00")
// 					txt = document.createTextNode("Database");
// 				else
// 					txt = document.createTextNode(date_of_birth);
// 				C.appendChild(txt);
// 			
// 				C = R.insertCell(4);
// 				txt = document.createTextNode(returning_customer);
// 				C.appendChild(txt);
			}
			else if (type == 'returningdropoff' || type == 'newdropoff'){
				if (type == 'returningdropoff') {				
				// 	C = R.insertCell(0);
// 					txt = document.createTextNode(last_name);
// 					C.appendChild(txt);
				li.appendChild(document.createTextNode(last_name));
				T.appendChild(li);
				}
				else {
					// C = R.insertCell(0);
// 					var span = document.createElement('span');
// 					span.setAttribute('class',"ui-icon ui-icon-notice");
// 					$(".ui-icon ui-icon-notice").text(last_name);
// 					C.appendChild(span);
				li.appendChild(document.createTextNode(last_name));
				T.appendChild(li);
				}
				
// 				var C = R.insertCell(1);
// 				var txt = document.createTextNode(type);
// 				C.appendChild(txt);
				
// 				C = R.insertCell(1);
// 				txt = document.createTextNode(first_name);
// 				C.appendChild(txt);

// 				C = R.insertCell(3);
// 				txt = document.createTextNode(date_of_birth);
// 				C.appendChild(txt);
// 			
// 				C = R.insertCell(4);
// 				txt = document.createTextNode(relation);
// 				C.appendChild(txt);
// 			
// 				C = R.insertCell(5);
// 				if(allergies == "1")
// 					txt = document.createTextNode("yes");
// 				else
// 					txt = document.createTextNode("no");
// 				C.appendChild(txt);
			}
			else {
// 				var C = R.insertCell(0);
// 				var txt = document.createTextNode(type);
// 				C.appendChild(txt);
			
// 				C = R.insertCell(1);
// 				txt = document.createTextNode(first_name);
// 				C.appendChild(txt);
			
				// C = R.insertCell(0);
// 				txt = document.createTextNode(last_name);
// 				C.appendChild(txt);
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
// 			var pickupRows = document.getElementById("pickupTable").rows.length-1;
// 			var dropoffRows = document.getElementById("dropoffTable").rows.length-1;
// 			var talkRows = document.getElementById("talkTable").rows.length-1;
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
		
	// 	$(function() {
//     		$( "#pickupTable" ).draggable({revert: true});
//     		$( "#dropoffTable" ).draggable({revert: true});
//     		$( "#talkTable" ).draggable({revert: true});
//   		});  
// 		 
// 		$(function() {
//     		$( "#tabs" ).droppable({
//       			activeClass: "ui-state-highlight",
//       			drop: function( event, ui ) {
//       				var draggableId = $(ui.draggable).attr("class");
//       				next(draggableId);
//              		$(ui.draggable).remove()
//         		}
//     		});
//   		});
  			
		function next(dropId) {
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
			alert(dropId);
			var item = removeRowFromPickupList(dropId);
			displayData(item);
			jQuery("#pickupTable li:first-child").remove();

			var data = 'type=' + type + '&id=' + dropId;
		
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