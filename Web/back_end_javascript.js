var pickupQueue = new Array(), dropoffQueue = new Array(), talkQueue = new Array();
var pickupArchiveQueue = new Array(), dropoffArchiveQueue = new Array(), talkArchiveQueue = new Array();
var pickupQueueCount = 0, dropoffQueueCount = 0, talkQueueCount = 0, tabsCount = 0;
var pickupArchiveQueueCount = 0, dropoffArchiveQueueCount = 0, talkArchiveQueueCount = 0;
var t;

function nextItem(type) {
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
	
	var name;
	if (type == 2) {
		name = "pickup";
	}
	else if (type == 3) {
		name = "dropoff";
	}
	else {
		name = "talk";
	}
	var T = document.getElementById(name + "Table");
	var id = T.getElementsByTagName("li")[0].className;
	var item = removeRowFromTable(id, name);
	displayData(item);
	jQuery("#" + name + "Table li:first-child").remove();

	var data = 'type=' + type + '&id=' + id;
	
	httpRequest.open('POST', 'back_end_php.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.send(data);
}

function addComment(id) {
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
	
	var type = 5; 
	var newComment = document.getElementById("textareaID" + id).value;
	var data = 'type=' + type + '&id=' + id + '&comment=' + newComment;
	
	var comment = "<p id=\"comment" + id + "\"><b>Comment: </b>" + newComment + "</p>";
	// if comment element already exists
	if (document.getElementById("comment" + id)) {
		var commentID = "comment" + id;
		$( "p#" + commentID + "" ).replaceWith( comment );
	}
	// if no comment exists
	else {
		var tabSize = document.getElementById(id).childNodes.length;
		var lastElement = document.getElementById(id).childNodes[tabSize - 1];
		$( comment ).insertBefore( lastElement );
	}
	
	httpRequest.open('POST', 'back_end_php.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.send(data);
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
	if (allergies == "1") {
		allergies = "Yes";
	}
	else {
		allergies= "No";
	}
	var comment = item.comment;
	// if no comment, don't show in tab
	if (comment) {
		comment = "<p id=\"comment" + id + "\"><b>Comment: </b>" + comment + "</p>";
	}
	
	var num_tabs = $("div#tabs ul li").length + 1;
	$("div#tabs ul").append(
		"<li><a href='#" + id + "'>" + last_name + "</a><span class=\"ui-icon ui-icon-close\"></span></li>"
	);
	
	if (type != "talk") {
		$("div#tabs").append(
			"<div id='" + id + "'>" + 
			"<p><b>First Name: </b>" + first_name + "</p>" +
			"<p><b>Last Name: </b>" + last_name + "</p>" +
			"<p><b>Date of Birth: </b>" + date_of_birth + "</p>" +
			"<p><b>Allergies: </b>" + allergies + "</p>" + 
			comment +
			"<div class=\"dialog\">" +
			"<textarea rows=\"4\" cols=\"15\" id=\"textareaID" + id + "\">" +
			"</textarea>" +
			"<br>" +
			"<input type=\"button\" onclick=\"addComment(" + id + ")\" value=\"Add Comment\">" + 
			"</div>" + 
			"</div>"
		);
	}
	else {
		$("div#tabs").append(
			"<div id='" + id + "'>" + 
			"<p><b>First Name: </b>" + first_name + "</p>" +
			"<p><b>Last Name: </b>" + last_name + "</p>" +
			comment +
			"<div class=\"dialog\">" +
			"<textarea rows=\"4\" cols=\"15\" id=\"textareaID" + id + "\">" +
			"</textarea>" +
			"<br>" +
			"<input type=\"button\" onclick=\"addComment(" + id + ")\" value=\"Add Comment\">" + 
			"</div>" + 
			"</div>"
		);
	}
	$("div#tabs").tabs("refresh");
	
	// open new tab
	var tabsCount = $("#tabs >ul >li").size();
	$( "div#tabs" ).tabs( "option", "active", tabsCount - 1);
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
				pickupArchiveQueueCount = 0;
				dropoffArchiveQueueCount = 0;
				talkArchiveQueueCount = 0;
				var pickupRows = newData.pickupContents;
				var dropoffRows = newData.dropoffContents;
				var talkRows = newData.talkContents;
				var pickupArchiveRows = newData.pickupArchiveContents;
				var dropoffArchiveRows = newData.dropoffArchiveContents;
				var talkArchiveRows = newData.talkArchiveContents;
				console.log("updating");

				if (pickupRows != "OK") {
					for (var i = 0; i < pickupRows.length; i++) {
						var theRow = pickupRows[i];
						addRowToList(theRow.id, theRow.type, theRow.refill, theRow.first_name, theRow.last_name, theRow.middle, theRow.date_of_birth, theRow.gender, theRow.position, theRow.home_address, theRow.city, theRow.state, theRow.zip, theRow.phone, theRow.phone_type, theRow.notifications, theRow.allergies_list, theRow.current_meds, theRow.signature, theRow.date, theRow.relation, theRow.returning_customer, theRow.insurance_card_number, theRow.allergies, theRow.comment);
					}
				}
				if (dropoffRows != "OK") {
					for (var i = 0; i < dropoffRows.length; i++) {
						var theRow = dropoffRows[i];
						addRowToList(theRow.id, theRow.type, theRow.refill, theRow.first_name, theRow.last_name, theRow.middle, theRow.date_of_birth, theRow.gender, theRow.position, theRow.home_address, theRow.city, theRow.state, theRow.zip, theRow.phone, theRow.phone_type, theRow.notifications, theRow.allergies_list, theRow.current_meds, theRow.signature, theRow.date, theRow.relation, theRow.returning_customer, theRow.insurance_card_number, theRow.allergies, theRow.comment);
					}
				}
				if (talkRows != "OK") {
					for (var i = 0; i < talkRows.length; i++) {
						var theRow = talkRows[i];
						addRowToList(theRow.id, theRow.type, theRow.refill, theRow.first_name, theRow.last_name, theRow.middle, theRow.date_of_birth, theRow.gender, theRow.position, theRow.home_address, theRow.city, theRow.state, theRow.zip, theRow.phone, theRow.phone_type, theRow.notifications, theRow.allergies_list, theRow.current_meds, theRow.signature, theRow.date, theRow.relation, theRow.returning_customer, theRow.insurance_card_number, theRow.allergies, theRow.comment);
					}
				}
				if (pickupArchiveRows != "OK") {
					for (var i = 0; i < pickupArchiveRows.length; i++) {
						var theRow = pickupArchiveRows[i];
						addRowToArchiveList(theRow.id, theRow.type, theRow.refill, theRow.first_name, theRow.last_name, theRow.middle, theRow.date_of_birth, theRow.gender, theRow.position, theRow.home_address, theRow.city, theRow.state, theRow.zip, theRow.phone, theRow.phone_type, theRow.notifications, theRow.allergies_list, theRow.current_meds, theRow.signature, theRow.date, theRow.relation, theRow.returning_customer, theRow.insurance_card_number, theRow.allergies, theRow.comment);
					}
				}
				if (dropoffArchiveRows != "OK") {
					for (var i = 0; i < dropoffArchiveRows.length; i++) {
						var theRow = dropoffArchiveRows[i];
						addRowToArchiveList(theRow.id, theRow.type, theRow.refill, theRow.first_name, theRow.last_name, theRow.middle, theRow.date_of_birth, theRow.gender, theRow.position, theRow.home_address, theRow.city, theRow.state, theRow.zip, theRow.phone, theRow.phone_type, theRow.notifications, theRow.allergies_list, theRow.current_meds, theRow.signature, theRow.date, theRow.relation, theRow.returning_customer, theRow.insurance_card_number, theRow.allergies, theRow.comment);
					}
				}
				if (talkArchiveRows != "OK") {
					for (var i = 0; i < talkArchiveRows.length; i++) {
						var theRow = talkArchiveRows[i];
						addRowToArchiveList(theRow.id, theRow.type, theRow.refill, theRow.first_name, theRow.last_name, theRow.middle, theRow.date_of_birth, theRow.gender, theRow.position, theRow.home_address, theRow.city, theRow.state, theRow.zip, theRow.phone, theRow.phone_type, theRow.notifications, theRow.allergies_list, theRow.current_meds, theRow.signature, theRow.date, theRow.relation, theRow.returning_customer, theRow.insurance_card_number, theRow.allergies, theRow.comment);
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

function Queue(id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies, comment) {
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
	this.comment = comment;
}

function addRowToList(id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies, comment) {
	var currItem = new Queue(id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies, comment);		
	if (type == "pickup") {
		pickupQueue[pickupQueueCount] = currItem;
		pickupQueueCount++;
	}
	else if(type == "returningdropoff" || type == "newdropoff") {
		dropoffQueue[dropoffQueueCount] = currItem;
		dropoffQueueCount++;
	}
	else{
		talkQueue[talkQueueCount] = currItem;
		talkQueueCount++;
	}
}

function addRowToArchiveList(id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies, comment) {
	var currItem = new Queue(id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies, comment);		
	if (type == "pickup") {
		pickupArchiveQueue[pickupArchiveQueueCount] = currItem;
		pickupArchiveQueueCount++;
	}
	else if(type == "returningdropoff" || type == "newdropoff") {
		dropoffArchiveQueue[dropoffArchiveQueueCount] = currItem;
		dropoffArchiveQueueCount++;
	}
	else{
		talkArchiveQueue[talkArchiveQueueCount] = currItem;
		talkArchiveQueueCount++;
	}
}

function removeRowFromTable(id, type) {
	var name;
	var ret = 0;
	
	if (type == "pickup") {
		name = pickupQueue;
		pickupQueueCount--;
	}
	else if (type == "dropoff") {
		name = dropoffQueue;
		dropoffQueueCount--;
	}
	else {
		name = talkQueue;
		talkQueueCount--;
	}
	
	for (var i = 0; i < name.length; i++) {
		if (name[i].id == id) {
			ret = name[i];
			name.splice(i, 1);
			break;
		}
	}
	return ret;
}

function showQueueTable() {
	// remove old queue items
	$('ul#pickupTable>').remove();
	$('ul#dropoffTable>').remove();
	$('ul#talkTable>').remove();
	$('ul#pickupTable2>').remove();
	$('ul#dropoffTable2>').remove();
	$('ul#talkTable2>').remove();
	
	for (var i = 0; i < pickupQueueCount; i++) {
		addRow(0, pickupQueue[i].id, pickupQueue[i].type, pickupQueue[i].refill, pickupQueue[i].first_name, pickupQueue[i].last_name, pickupQueue[i].middle, 
			   pickupQueue[i].date_of_birth, pickupQueue[i].gender, pickupQueue[i].position, pickupQueue[i].home_address, pickupQueue[i].city, pickupQueue[i].state, 
			   pickupQueue[i].zip, pickupQueue[i].phone, pickupQueue[i].phone_type, pickupQueue[i].notifications, pickupQueue[i].allergies_list, pickupQueue[i].current_meds, 
			   pickupQueue[i].signature, pickupQueue[i].date, pickupQueue[i].relation, pickupQueue[i].returning_customer, pickupQueue[i].insurance_card_number, pickupQueue[i].allergies, pickupQueue[i].comment);
	}
	for (var i = 0; i < dropoffQueueCount; i++) {
		addRow(0, dropoffQueue[i].id, dropoffQueue[i].type, dropoffQueue[i].refill, dropoffQueue[i].first_name, dropoffQueue[i].last_name, 
			   dropoffQueue[i].middle, dropoffQueue[i].date_of_birth, dropoffQueue[i].gender, dropoffQueue[i].position, dropoffQueue[i].home_address, 
			   dropoffQueue[i].city, dropoffQueue[i].state, dropoffQueue[i].zip, dropoffQueue[i].phone, dropoffQueue[i].phone_type, dropoffQueue[i].notifications, 
			   dropoffQueue[i].allergies_list, dropoffQueue[i].current_meds, dropoffQueue[i].signature, dropoffQueue[i].date, dropoffQueue[i].relation, 
			   dropoffQueue[i].returning_customer, dropoffQueue[i].insurance_card_number, dropoffQueue[i].allergies, dropoffQueue[i].comment);
	}
	for (var i = 0; i < talkQueueCount; i++) {
		addRow(0, talkQueue[i].id, talkQueue[i].type, talkQueue[i].refill, talkQueue[i].first_name, talkQueue[i].last_name, talkQueue[i].middle, 
			   talkQueue[i].date_of_birth, talkQueue[i].gender, talkQueue[i].position, talkQueue[i].home_address, talkQueue[i].city, talkQueue[i].state, 
			   talkQueue[i].zip, talkQueue[i].phone, talkQueue[i].phone_type, talkQueue[i].notifications, talkQueue[i].allergies_list, talkQueue[i].current_meds, 
			   talkQueue[i].signature, talkQueue[i].date, talkQueue[i].relation, talkQueue[i].returning_customer, talkQueue[i].insurance_card_number, talkQueue[i].allergies, talkQueue[i].comment);
	}
	for (var i = 0; i < pickupArchiveQueueCount; i++) {
		addRow(1, pickupArchiveQueue[i].id, pickupArchiveQueue[i].type, pickupArchiveQueue[i].refill, pickupArchiveQueue[i].first_name, pickupArchiveQueue[i].last_name, pickupArchiveQueue[i].middle, 
			   pickupArchiveQueue[i].date_of_birth, pickupArchiveQueue[i].gender, pickupArchiveQueue[i].position, pickupArchiveQueue[i].home_address, pickupArchiveQueue[i].city, pickupArchiveQueue[i].state, 
			   pickupArchiveQueue[i].zip, pickupArchiveQueue[i].phone, pickupArchiveQueue[i].phone_type, pickupArchiveQueue[i].notifications, pickupArchiveQueue[i].allergies_list, pickupArchiveQueue[i].current_meds, 
			   pickupArchiveQueue[i].signature, pickupArchiveQueue[i].date, pickupArchiveQueue[i].relation, pickupArchiveQueue[i].returning_customer, pickupArchiveQueue[i].insurance_card_number, pickupArchiveQueue[i].allergies, pickupArchiveQueue[i].comment);
	}
	for (var i = 0; i < dropoffArchiveQueueCount; i++) {
		addRow(1, dropoffArchiveQueue[i].id, dropoffArchiveQueue[i].type, dropoffArchiveQueue[i].refill, dropoffArchiveQueue[i].first_name, dropoffArchiveQueue[i].last_name, 
			   dropoffArchiveQueue[i].middle, dropoffArchiveQueue[i].date_of_birth, dropoffArchiveQueue[i].gender, dropoffArchiveQueue[i].position, dropoffArchiveQueue[i].home_address, 
			   dropoffArchiveQueue[i].city, dropoffArchiveQueue[i].state, dropoffArchiveQueue[i].zip, dropoffArchiveQueue[i].phone, dropoffArchiveQueue[i].phone_type, dropoffArchiveQueue[i].notifications, 
			   dropoffArchiveQueue[i].allergies_list, dropoffArchiveQueue[i].current_meds, dropoffArchiveQueue[i].signature, dropoffArchiveQueue[i].date, dropoffArchiveQueue[i].relation, 
			   dropoffArchiveQueue[i].returning_customer, dropoffArchiveQueue[i].insurance_card_number, dropoffArchiveQueue[i].allergies, dropoffArchiveQueue[i].comment);
	}
	for (var i = 0; i < talkArchiveQueueCount; i++) {
		addRow(1, talkArchiveQueue[i].id, talkArchiveQueue[i].type, talkArchiveQueue[i].refill, talkArchiveQueue[i].first_name, talkArchiveQueue[i].last_name, talkArchiveQueue[i].middle, 
			   talkArchiveQueue[i].date_of_birth, talkArchiveQueue[i].gender, talkArchiveQueue[i].position, talkArchiveQueue[i].home_address, talkArchiveQueue[i].city, talkArchiveQueue[i].state, 
			   talkArchiveQueue[i].zip, talkArchiveQueue[i].phone, talkArchiveQueue[i].phone_type, talkArchiveQueue[i].notifications, talkArchiveQueue[i].allergies_list, talkArchiveQueue[i].current_meds, 
			   talkArchiveQueue[i].signature, talkArchiveQueue[i].date, talkArchiveQueue[i].relation, talkArchiveQueue[i].returning_customer, talkArchiveQueue[i].insurance_card_number, talkArchiveQueue[i].allergies, talkArchiveQueue[i].comment);
	}
}

function addRow(pageType, id, type, refill, first_name, last_name, middle, date_of_birth, gender, position, home_address, city, state, zip, phone, 
				phone_type, notifications, allergies_list, current_meds, signature, date, relation, returning_customer, insurance_card_number, allergies, comment) {
	var labelType = 0;
	if (pageType == 0) {
		if (type == 'pickup') {
			var T = document.getElementById("pickupTable");
		}
		else if (type == 'returningdropoff' || type == 'newdropoff'){
			var T = document.getElementById("dropoffTable");

		}
		else {
			var T = document.getElementById("talkTable");
		}
	}
	else {
		labelType = 1;
		if (type == 'pickup') {
			var T = document.getElementById("pickupTable2");
		}
		else if (type == 'returningdropoff' || type == 'newdropoff'){
			var T = document.getElementById("dropoffTable2");

		}
		else {
			var T = document.getElementById("talkTable2");
		}
	}
	
	var li = document.createElement("li");
	li.id = "ui-state-default";
	li.setAttribute("type", labelType);
	li.className = id;
	if (comment) {
		console.log(comment);
		li.setAttribute("list-style-type", "comment");
	}
	
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
	var pickupCount = $('ul#pickupTable>').length;
	var dropoffCount = $('ul#dropoffTable>').length;
	var talkCount = $('ul#talkTable>').length;
	var pickupArchiveCount = $('ul#pickupTable2>').length;
	var dropoffArchiveCount = $('ul#dropoffTable2>').length;
	var talkArchiveCount = $('ul#talkTable2>').length;
	
	var rows = pickupCount + dropoffCount + talkCount;
	if (rows == -1) {
		rows = 0;
	}
	var archive_rows = pickupArchiveCount + dropoffArchiveCount + talkArchiveCount;
	if (archive_rows == -1) {
		archive_rows = 0;
	}
	var data = 'type=' + type + '&rows=' + rows + '&archive_rows=' + archive_rows;
	console.log(data);
	httpRequest.open('POST', 'back_end_php.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.onreadystatechange = function() { updateRows(httpRequest); } ;
	httpRequest.send(data);
	t = setTimeout("refreshPage()", 5000);
}

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
	$( "#pickupTable2" ).sortable({
		revert: true
	});
	$( "#dropoffTable2" ).sortable({
		revert: true
	});
	$( "#talkTable2" ).sortable({
		revert: true
	});
		$( "ul, li" ).disableSelection();
	});

$(function() {
	$( "#pickupTable" ).draggable({revert: true});
	$( "#dropoffTable" ).draggable({revert: true});
	$( "#talkTable" ).draggable({revert: true});
	$( "#pickupTable2" ).draggable({revert: true});
	$( "#dropoffTable2" ).draggable({revert: true});
	$( "#talkTable2" ).draggable({revert: true});
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

//$( "#ui-state-default" ).click(function() {
//	console.log("test");
//});

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
		var item = removeRowFromTable(dropId, "pickup");
		displayData(item);
		var data = 'type=pickup' + '&id=' + dropId;
	}
	if(table.includes('dropoff')){
		var item = removeRowFromTable(dropId, "dropoff");
		displayData(item);
		var data = 'type=dropoff' + '&id=' + dropId;
	}
	if(table.includes('talk')){
		var item = removeRowFromTable(dropId, "talk");
		displayData(item);
		var data = 'type=talk' + '&id=' + dropId;
	}
	// comment this out to disable database connection for easier testing
	httpRequest.open('POST', 'back_end_php.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.send(data);
}