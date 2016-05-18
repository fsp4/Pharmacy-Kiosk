// create new arrays to hold each type of queue
var pickupQueue = new Array(), dropoffQueue = new Array(), talkQueue = new Array();
var pickupArchiveQueue = new Array(), dropoffArchiveQueue = new Array(), talkArchiveQueue = new Array();
// set initial queue counts to 0
var pickupQueueCount = 0, dropoffQueueCount = 0, talkQueueCount = 0, tabsCount = 0;
var pickupArchiveQueueCount = 0, dropoffArchiveQueueCount = 0, talkArchiveQueueCount = 0;

// fix for 'includes' function in IE
if (!String.prototype.includes) {
	String.prototype.includes = function() {
		'use strict';
		return String.prototype.indexOf.apply(this, arguments) !== -1;
	};
}

// Remove item from queue, open in tab, and move item from queue table to queue_archive table
// @param int type
//		  2,3, or 4 = from html buttons on page
//		  These coorespond to the else statement in back_end_php.php
function nextItem(type) {
	var httpRequest;
	
	// XMLHttpRequest checking
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
	
	// check which queue to remove from
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
	
	// get first item in queue
	var T = document.getElementById(name + "Table");
	var id = T.getElementsByTagName("li")[0].firstChild.id;
	
	// remove "item" from id
	id = id.substring(4);
	
	// remove item from queue and add to queue archive on page and in database
	var item = removeRowFromList(id, name);
	var toRemove = document.getElementById(id);
	$(toRemove).remove()
	displayData(item);
	var data = 'type=' + type + '&id=' + id;
	httpRequest.open('POST', 'back_end_php.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.onreadystatechange = function() { refreshPage(); } ;
	httpRequest.send(data);
}

// adds comment to database and displays on page
function addComment(id, addRemove) {
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
	
	var comment = document.getElementById("textareaID" + id).value;
	var newComment = "<p id=\"comment" + id + "\"><b>Comment: </b>" + comment + "</p>";
	
	// ignore if comment is empty and adding
	if (comment || addRemove == 1) {
		console.log("pass");
		// add comment
		if (addRemove == 0) {
			
			// if comment element already exists
			if (document.getElementById("comment" + id)) {
				var commentID = "comment" + id;
				$( "p#" + commentID + "" ).replaceWith( newComment );
			}
			
			// if no comment exists
			else {
				//var tabSize = document.getElementById(id).childNodes.length;
				//var lastElement = document.getElementById(id).childNodes[tabSize - 1];
				var commentDiv = document.getElementById("commentDiv" + id)
				$( newComment ).insertBefore( commentDiv );
			}
			
			// change item's className to change its background color to red
			var commentElement = document.getElementById("item" + id);
			commentElement.className = "queueItemComment";
		}
		
		// remove comment
		if (addRemove == 1) {
			// remove comment element from page
			var commentID = "comment" + id;
			$( "p#" + commentID + "" ).remove();
			
			// change item's className to change its background color to default
			var commentElement = document.getElementById("item" + id);
			commentElement.className = "queueItem";
		}
		
		// update comment in item object
		var item = getArchiveRowFromListNoTable(id);
		var itemComment = comment;
		if (addRemove == 1) {
			itemComment = "";
		}
		item.comment = itemComment;
		
		// update comment in database
		var type = 5;
		var data = 'type=' + type + '&id=' + id + '&comment=' + itemComment;
		httpRequest.open('POST', 'back_end_php.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		httpRequest.send(data);
	}
}

function backToQueue(id, type) {
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
	
	var item = removeRowFromArchiveList(id, type);
	
	// remove item from queue and add to queue archive on page and in database
	var data = 'type=7' + '&id=' + id;
	var toRemove = document.getElementById(id);
	$(toRemove).remove();
	
	// remove tab
	var tabs = $( "#tabs" ).tabs();
	$( ".ui-state-active" ).closest( "li" ).remove().attr( "aria-controls" );
	$( "#" + id ).remove();
	tabs.tabs( "refresh" );
	
	//displayData(item);
	httpRequest.open('POST', 'back_end_php.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.onreadystatechange = function() { refreshPage(); } ;
	httpRequest.send(data);
}

// function called when opening new tab
// creates new tab and adds paramenters data
function displayData(item) {
	// get data from item object
	var id = item.id;
	
	var type = item.type;
	var actualType = type;
	var displayType = "Unknown";
	if (type == "pickup") displayType = "Pick Up";
	else if (type == "returningdropoff" || type == "newdropoff") {
		displayType = "Drop Off";
		actualType = "dropoff";
	}
	else if (type == "talk") displayType = "Talk to Technician";
	
	var first_name = item.first_name;
	var last_name = item.last_name;
	
	var date_of_birth = item.date_of_birth;
	if (date_of_birth == "0000-00-00") {
		date_of_birth = "N/A";
	}
	
	var relation = item.relation;
	var timestamp = formatTime(item.timestamp);
	var returning_customer = item.returning_customer;
	
	var allergies = item.allergies;
	if (allergies == "1") {
		allergies = "Yes";
	}
	else {
		allergies= "No";
	}
	
	// if no comment, don't show in tab
	var comment = item.comment;
	if (comment) {
		comment = "<p id=\"comment" + id + "\"><b>Comment: </b>" + comment + "</p>";
	}
	
	// creates and adds new elements to new tab
	var num_tabs = $("div#tabs ul li").length + 1;
	$("div#tabs ul").append(
		"<li><a href='#" + id + "'>" + last_name + "</a><span class=\"ui-icon ui-icon-close\"></span></li>"
	);
	
	if (actualType == "pickup") {
		$("div#tabs").append(
			"<div id='" + id + "'>" + 
			"<p><b>Type: </b>" + displayType + "</p>" + 
			"<p><b>First Name: </b>" + first_name + "</p>" + 
			"<p><b>Last Name: </b>" + last_name + "</p>" + 
			"<p><b>Date of Birth: </b>" + date_of_birth + "</p>" + 
			"<p><b>Time Submitted: </b>" + timestamp + "</p>" + 
			"<p><b>Relation to Patient: </b>" + relation + "</p>" + 
			comment + 
			"<div id=\"commentDiv" + id +"\" class=\"commentDiv\">" + 
			"<textarea placeholder=\"Comment\" class=\"commentarea\" rows=\"3\" cols=\"18\" id=\"textareaID" + id + "\">" + 
			"</textarea>" + 
			"<br>" + 
			"<input class=\"commentareabutton\" type=\"button\" onclick=\"addComment(" + id + ",0)\" value=\"Add\" style=\"font-family: Palatino Linotype;\">" + 
			"<input class=\"commentareabutton\" type=\"button\" onclick=\"addComment(" + id + ",1)\" value=\"Remove\" style=\"font-family: Palatino Linotype;\">" + 
			"</div>" + 
			"<p><input class=\"toQueueButton\" type=\"button\" onclick=\"backToQueue(" + id + ", '" + actualType + "')\" value=\"Return Item to Queue\" style=\"font-family: Palatino Linotype;\"></p>" + 
			"</div>"
		);
	}
	else if (actualType == "dropoff"){
		$("div#tabs").append(
			"<div id='" + id + "'>" + 
			"<p><b>Type: </b>" + displayType + "</p>" + 
			"<p><b>First Name: </b>" + first_name + "</p>" + 
			"<p><b>Last Name: </b>" + last_name + "</p>" + 
			"<p><b>Date of Birth: </b>" + date_of_birth + "</p>" + 
			"<p><b>Time Submitted: </b>" + timestamp + "</p>" + 
			"<p><b>Allergies: </b>" + allergies + "</p>" + 
			"<p><b>Relation to Patient: </b>" + relation + "</p>" + 
			comment + 
			"<div id=\"commentDiv" + id +"\" class=\"commentDiv\">" + 
			"<textarea placeholder=\"Comment\" class=\"commentarea\" rows=\"3\" cols=\"18\" id=\"textareaID" + id + "\">" + 
			"</textarea>" + 
			"<br>" + 
			"<input class=\"commentareabutton\" type=\"button\" onclick=\"addComment(" + id + ",0)\" value=\"Add\" style=\"font-family: Palatino Linotype;\">" + 
			"<input class=\"commentareabutton\" type=\"button\" onclick=\"addComment(" + id + ",1)\" value=\"Remove\" style=\"font-family: Palatino Linotype;\">" + 
			"</div>" + 
			"<p><input class=\"toQueueButton\" type=\"button\" onclick=\"backToQueue(" + id + ", '" + actualType + "')\" value=\"Return Item to Queue\" style=\"font-family: Palatino Linotype;\"></p>" + 
			"</div>"
		);
	}
	// type == talk
	else {
		$("div#tabs").append(
			"<div id='" + id + "'>" + 
			"<p><b>Type: </b>" + displayType + "</p>" + 
			"<p><b>First Name: </b>" + first_name + "</p>" + 
			"<p><b>Last Name: </b>" + last_name + "</p>" + 
			"<p><b>Time Submitted: </b>" + timestamp + "</p>" + 
			comment + 
			"<div id=\"commentDiv" + id +"\" class=\"commentDiv\">" + 
			"<textarea placeholder=\"Comment\" class=\"commentarea\" rows=\"3\" cols=\"18\" id=\"textareaID" + id + "\">" + 
			"</textarea>" + 
			"<br>" + 
			"<input class=\"commentareabutton\" type=\"button\" onclick=\"addComment(" + id + ",0)\" value=\"Add\" style=\"font-family: Palatino Linotype;\">" + 
			"<input class=\"commentareabutton\" type=\"button\" onclick=\"addComment(" + id + ",1)\" value=\"Remove\" style=\"font-family: Palatino Linotype;\">" + 
			"</div>" + 
			"<p><input class=\"toQueueButton\" type=\"button\" onclick=\"backToQueue(" + id + ", '" + actualType + "')\" value=\"Return Item to Queue\" style=\"font-family: Palatino Linotype;\"></p>" + 
			"</div>"
		);
	}
	$("div#tabs").tabs("refresh");
	
	// make new tab active
	var tabsCount = $("#tabs >ul >li").size();
	$( "div#tabs" ).tabs( "option", "active", tabsCount - 1);
}

// function called on return from refreshPage function
// parses database data returned from server and displays on page
function updateRows(httpRequest) {
	if (httpRequest.readyState == 4) {
		if (httpRequest.status == 200) {
			// get and parge JSON data from back_end_php.php
			var data = httpRequest.responseText;
			var newData = JSON.parse(data);
			
			// if rettype == "Update" then reload all queues
			var rettype = newData.Type;
			if (rettype == "Update") {
				// reset all queue counts
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
				
				// add each item to an array
				if (pickupRows != "OK") {
					for (var i = 0; i < pickupRows.length; i++) {
						var theRow = pickupRows[i];
						addRowToList(theRow.id, theRow.type, theRow.first_name, theRow.last_name, theRow.date_of_birth, theRow.allergies, theRow.comment, theRow.relation, theRow.timestamp);
					}
				}
				if (dropoffRows != "OK") {
					for (var i = 0; i < dropoffRows.length; i++) {
						var theRow = dropoffRows[i];
						addRowToList(theRow.id, theRow.type, theRow.first_name, theRow.last_name, theRow.date_of_birth, theRow.allergies, theRow.comment, theRow.relation, theRow.timestamp);
					}
				}
				if (talkRows != "OK") {
					for (var i = 0; i < talkRows.length; i++) {
						var theRow = talkRows[i];
						addRowToList(theRow.id, theRow.type, theRow.first_name, theRow.last_name, theRow.date_of_birth, theRow.allergies, theRow.comment, theRow.relation, theRow.timestamp);
					}
				}
				if (pickupArchiveRows != "OK") {
					for (var i = 0; i < pickupArchiveRows.length; i++) {
						var theRow = pickupArchiveRows[i];
						addRowToArchiveList(theRow.id, theRow.type, theRow.first_name, theRow.last_name, theRow.date_of_birth, theRow.allergies, theRow.comment, theRow.relation, theRow.timestamp);
					}
				}
				if (dropoffArchiveRows != "OK") {
					for (var i = 0; i < dropoffArchiveRows.length; i++) {
						var theRow = dropoffArchiveRows[i];
						addRowToArchiveList(theRow.id, theRow.type, theRow.first_name, theRow.last_name, theRow.date_of_birth, theRow.allergies, theRow.comment, theRow.relation, theRow.timestamp);
					}
				}
				if (talkArchiveRows != "OK") {
					for (var i = 0; i < talkArchiveRows.length; i++) {
						var theRow = talkArchiveRows[i];
						addRowToArchiveList(theRow.id, theRow.type, theRow.first_name, theRow.last_name, theRow.date_of_birth, theRow.allergies, theRow.comment, theRow.relation, theRow.timestamp);
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

// Object for each item in queue
// Note: many of these variables are not displayed and therefore currently unused
function Item(id, type, first_name, last_name, date_of_birth, allergies, comment, relation, timestamp) {
	this.id = id;
	this.type = type;
	this.first_name = first_name;
	this.last_name = last_name;
	this.date_of_birth = date_of_birth;
	this.allergies = allergies;
	this.comment = comment;
	this.relation = relation;
	this.timestamp = timestamp;
}

// creates new object for the argument data and adds to cooresponding queue array
function addRowToList(id, type, first_name, last_name, date_of_birth, allergies, comment, relation, timestamp) {
	var currItem = new Item(id, type, first_name, last_name, date_of_birth, allergies, comment, relation, timestamp);		
	
	if (type == "pickup") {
		pickupQueue[pickupQueueCount] = currItem;
		pickupQueueCount++;
	}
	else if (type == "returningdropoff" || type == "newdropoff") {
		dropoffQueue[dropoffQueueCount] = currItem;
		dropoffQueueCount++;
	}
	else {
		talkQueue[talkQueueCount] = currItem;
		talkQueueCount++;
	}
}

// creates new object for the argument data and adds to cooresponding queue_archive array
function addRowToArchiveList(id, type, first_name, last_name, date_of_birth, allergies, comment, relation, timestamp) {
	var currItem = new Item(id, type, first_name, last_name, date_of_birth, allergies, comment, relation, timestamp);		
	
	if (type == "pickup") {
		pickupArchiveQueue[pickupArchiveQueueCount] = currItem;
		pickupArchiveQueueCount++;
	}
	else if (type == "returningdropoff" || type == "newdropoff") {
		dropoffArchiveQueue[dropoffArchiveQueueCount] = currItem;
		dropoffArchiveQueueCount++;
	}
	else {
		talkArchiveQueue[talkArchiveQueueCount] = currItem;
		talkArchiveQueueCount++;
	}
}

// removes item given by argument from its array then returns it
function removeRowFromList(id, type) {
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

// removes item given by argument from its archive array then returns it
function removeRowFromArchiveList(id, type) {
	var name;
	var ret = 0;
	
	if (type == "pickup") {
		name = pickupArchiveQueue;
		pickupArchiveQueueCount--;
	}
	else if (type == "dropoff") {
		name = dropoffArchiveQueue;
		dropoffArchiveQueueCount--;
	}
	else {
		name = talkArchiveQueue;
		talkArchiveQueueCount--;
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

// returns item given by id and table from ArchiveQueue array
function getArchiveRowFromList(id, type) {
	var name;
	var ret = 0;
	
	if (type == "pickup") {
		name = pickupArchiveQueue;
	}
	else if (type == "dropoff") {
		name = dropoffArchiveQueue;
	}
	else {
		name = talkArchiveQueue;
	}
	
	for (var i = 0; i < name.length; i++) {
		if (name[i].id == id) {
			ret = name[i];
			break;
		}
	}
	return ret;
}

// returns item given by id from ArchiveQueue array
function getArchiveRowFromListNoTable(id) {
	for (var i = 0; i < pickupArchiveQueue.length; i++) {
		if (pickupArchiveQueue[i].id == id) {
			return pickupArchiveQueue[i];
		}
	}
	for (var i = 0; i < dropoffArchiveQueue.length; i++) {
		if (dropoffArchiveQueue[i].id == id) {
			return dropoffArchiveQueue[i];
		}
	}
	for (var i = 0; i < talkArchiveQueue.length; i++) {
		if (talkArchiveQueue[i].id == id) {
			return talkArchiveQueue[i];
		}
	}
	return "null";
}

function formatTime(timestamp) {
	var split = timestamp.split(" ");
	var date = split[0];
	var time = split[1];
	
	var timeSplit = time.split(":");
	var hh = timeSplit[0];
	var m = timeSplit[1];
	var s = timeSplit[2];
	var dd = "AM";
	var h = hh;
	
	if (h >= 12) {
		h = hh - 12;
		dd = "PM";
	}
	if (h == 0) {
		h = 12;
	}
	
	if (h < 10) {
		h = parseInt(h);
	}
	
	var newTime = h + ":" + m + " " + dd;
	
	return (newTime);
}

// displays queue on page
function showQueueTable() {
	// remove old queue items
	$('ul#pickupTable>').remove();
	$('ul#dropoffTable>').remove();
	$('ul#talkTable>').remove();
	$('ul#pickupTable2>').remove();
	$('ul#dropoffTable2>').remove();
	$('ul#talkTable2>').remove();
	
	// add each item from each queue onto page
	for (var i = 0; i < pickupQueueCount; i++) {
		addRow(0, pickupQueue[i].id, pickupQueue[i].type, pickupQueue[i].last_name, pickupQueue[i].comment);
	}
	for (var i = 0; i < dropoffQueueCount; i++) {
		addRow(0, dropoffQueue[i].id, dropoffQueue[i].type, dropoffQueue[i].last_name, dropoffQueue[i].comment);
	}
	for (var i = 0; i < talkQueueCount; i++) {
		addRow(0, talkQueue[i].id, talkQueue[i].type, talkQueue[i].last_name, talkQueue[i].comment);
	}
	for (var i = 0; i < pickupArchiveQueueCount; i++) {
		addRow(1, pickupArchiveQueue[i].id, pickupArchiveQueue[i].type, pickupArchiveQueue[i].last_name, pickupArchiveQueue[i].comment);
	}
	for (var i = 0; i < dropoffArchiveQueueCount; i++) {
		addRow(1, dropoffArchiveQueue[i].id, dropoffArchiveQueue[i].type, dropoffArchiveQueue[i].last_name, dropoffArchiveQueue[i].comment);
	}
	for (var i = 0; i < talkArchiveQueueCount; i++) {
		addRow(1, talkArchiveQueue[i].id, talkArchiveQueue[i].type, talkArchiveQueue[i].last_name, talkArchiveQueue[i].comment);
	}
}

// function displays new item in queue
function addRow(pageType, id, type, last_name, comment) {
	var labelType = 0;
	
	// get elements for new queues from page
	if (pageType == 0) {
		if (type == 'pickup') {
			var tableName = "pickupTable";
			var T = document.getElementById(tableName);
		}
		else if (type == 'returningdropoff' || type == 'newdropoff'){
			var tableName = "dropoffTable";
			var T = document.getElementById(tableName);
		}
		else {
			var tableName = "talkTable";
			var T = document.getElementById(tableName);
		}
	}
	// get elements for archive queues from page
	else {
		labelType = 1;
		if (type == 'pickup') {
			var tableName = "pickupTable2";
			var T = document.getElementById(tableName);
		}
		else if (type == 'returningdropoff' || type == 'newdropoff'){
			var tableName = "dropoffTable2";
			var T = document.getElementById(tableName);
		}
		else {
			var tableName = "talkTable2";
			var T = document.getElementById(tableName);
		}
	}
	
	// create new li element 
	var li = document.createElement("li");
	
	// create new input button for current item given by argument data
	var bu = document.createElement("input");
	bu.onclick = function() {displayItem(id, tableName)};
	bu.id = "item" + id;
	bu.type = "button";
	bu.className = "queueItem";
	if (comment) {
		bu.className = "queueItemComment";
	}
	bu.value = last_name;
	
	// append new element to page
	li.appendChild(bu);
	T.appendChild(li);
	
	// if adding to archive queue move scrollbar to bottom to see new item
	if (pageType == 1) {
		T.scrollTop = T.scrollHeight;
	}
}

// function called on page load and again every 5 seconds
// Counts number of items in each queue and sends them to back_end_php.php then calls updateRows function
function refreshPage() {
	var httpRequest;

	// XMLHttpRequest checking
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
	httpRequest.open('POST', 'back_end_php.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.onreadystatechange = function() { updateRows(httpRequest); } ;
	httpRequest.send(data);
	t = setTimeout("refreshPage()", 5000);
}

// jquery function to set tabs as collapsible
$(function() {
	$( "#tabs" ).tabs({
		collapsible: true
	});
});

// jquery function for closing tabs
$(function() {
	var tabs = $( "#tabs" ).tabs();
	// close icon: removing the tab on click
	tabs.delegate( "span.ui-icon-close", "click", function() {
		var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
		$( "#" + panelId ).remove();
		tabs.tabs( "refresh" );
	});
 });

// function called onclick from each item in either table
function displayItem(id, table) {
	var item;
	
	// archive queue table
	if (table.includes('2')) {
		var alreadyExistsBoolean = "false";
		var alreadyExistsPosition = 0;
		
		// check if item already displayed in tab
		$('#tabs .ui-tabs-nav a').each(function() {
		  var tabID = $(this).attr('href');
		  if (tabID.includes(id)) {
			  alreadyExistsBoolean = "true";
			  
			  // if item is open in a tab, make that tab active
			  $( "div#tabs" ).tabs( "option", "active", alreadyExistsPosition);
		  }
		  alreadyExistsPosition++;
		});
		
		// if item not yet open in a tab
		if (alreadyExistsBoolean == "false") {
			if (table.includes('pickup')) {
				item = getArchiveRowFromList(id, "pickup");
			}
			if (table.includes('dropoff')) {
				item = getArchiveRowFromList(id, "dropoff");
			}
			if (table.includes('talk')) {
				item = getArchiveRowFromList(id, "talk");
			}
			
			displayData(item);
		}
	}
	
	// queue table
	else {
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
		
		if (table.includes('pickup')) {
			item = removeRowFromList(id, "pickup");
			var data = 'type=2' + '&id=' + id;
		}
		if (table.includes('dropoff')) {
			item = removeRowFromList(id, "dropoff");
			var data = 'type=3' + '&id=' + id;
		}
		if (table.includes('talk')) {
			item = removeRowFromList(id, "talk");
			var data = 'type=4' + '&id=' + id;
		}
		
		// remove item from queue and add to queue archive on page and in database
		var toRemove = document.getElementById(id);
		$(toRemove).remove()
		displayData(item);
		httpRequest.open('POST', 'back_end_php.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		httpRequest.onreadystatechange = function() { refreshPage(); } ;
		httpRequest.send(data);
	}
}

// function to delete all items in archive queue
function flushArchive() {
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
	
	// confirm flushing database
	var ret = confirm("Are you sure you want to delete all items in the Archived Queue?");
	if (ret == false) return;
	
	var data = "type=6";
	httpRequest.open('POST', 'back_end_php.php', true);
	httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	httpRequest.onreadystatechange = function() { refreshPage(); } ;
	httpRequest.send(data);
}