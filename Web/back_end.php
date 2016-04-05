<!DOCTYPE html>
<html>
<head>
<title>Kiosk Queue</title>
<script type="text/javascript" src="../php/tablednd.js"></script>
<script type="text/javascript">
	var pickupQueue = new Array(), dropoffQueue = new Array(), pickuptableCount = 0, dropoffQueueCount = 0, t;
	
	function Start() {
		refreshPage();
	}
	
	/* // Drag and drop
	function dragAndDropTableInit() {
		var table = document.getElementById('pickupTable');
		var tableDnD = new TableDnD();
		tableDnD.init(table);
	}
	
	
	function wrap(top, selector, bottom) {
		var matches = document.querySelectorAll(selector);
		for (var i = 0; i < matches.length; i++){
			var modified = top + matches[i].outerHTML + bottom;
			matches[i].outerHTML = modified;
			console.log(modified);
		}
	}
	*/
	
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
		var id = T.rows[1].id;
		var item = removeRowFromPickupList(id);
		displayData(item);
		T.deleteRow(1);

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
		var id = T.rows[1].id;
		console.log(id);
		var item = removeRowFromDropOffList(id);
		displayData(item);
		T.deleteRow(1);

		var data = 'type=' + type + '&id=' + id;
		
		// comment this out to disable database connection for easier testing
		//httpRequest.open('POST', 'back_end_php.php', true);
		//httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		//httpRequest.onreadystatechange = function() { displayData(); } ;
		//httpRequest.send(data);
		//
	}
	
	function displayData(item) {
		var table = document.getElementById("displayTable");
		
		var id = item.id;
		var first_name = item.first_name;
		var last_name = item.last_name;
		var date_of_birth = item.date_of_birth;
		var relation = item.relation;
		var returning_customer = item.returning_customer;
		var insurance_card_number = item.insurance_card_number;
		
		// returning customer checkbox
		var tr = document.createElement("tr");
		var label = document.createElement("label");
		label.innerHTML = "Returning Customer: ";
		
		var checkbox = document.createElement("input");
		checkbox.type = "checkbox";
		checkbox.name = "returningcustomer";
		if (returning_customer.localeCompare("yes")) {
			checkbox.checked = true;
		}
		checkbox.disabled = true;
		label.appendChild(checkbox);
		tr.appendChild(label);
		
		table.appendChild(tr);
		////
		
		// first name field
		var tr = document.createElement("tr");
		var label = document.createElement("label");
		label.innerHTML = "First Name: ";
		
		var firstname = document.createElement("input");
		firstname.type = "text";
		firstname.name = "firstname";
		firstname.value = first_name;
		firstname.disabled = true;
		label.appendChild(firstname);
		tr.appendChild(label);
		
		table.appendChild(tr);
		////
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
					var pickupRows = newData.pickupContents;
					var dropoffRows = newData.dropoffContents;
					
					if (pickupRows != "OK") {
						for (var i = 0; i < pickupRows.length; i++) {
							var theRow = pickupRows[i];
							addRowToList(theRow.id, theRow.type, theRow.first_name, theRow.last_name, theRow.date_of_birth, theRow.relation, theRow.returning_customer, theRow.insurance_card_number);
						}
					}
					if (dropoffRows != "OK") {
						for (var i = 0; i < dropoffRows.length; i++) {
							var theRow = dropoffRows[i];
							addRowToList(theRow.id, theRow.type, theRow.first_name, theRow.last_name, theRow.date_of_birth, theRow.relation, theRow.returning_customer, theRow.insurance_card_number);
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

	function Queue(id, type, first_name, last_name, date_of_birth, relation, returning_customer, insurance_card_number) {
		this.id = id;
		this.type = type;
		this.first_name = first_name;
		this.last_name = last_name;
		this.date_of_birth = date_of_birth;
		this.relation = relation;
		this.returning_customer = returning_customer;
		this.insurance_card_number = insurance_card_number;
	}

	function addRowToList(id, type, first_name, last_name, date_of_birth, relation, returning_customer, insurance_card_number) {
		var currItem = new Queue(id, type, first_name, last_name, date_of_birth, relation, returning_customer, insurance_card_number);
		
		if (type == "pickup") {
			pickupQueue[pickupQueueCount] = currItem;
			pickupQueueCount++;
		}
		else {
			dropoffQueue[dropoffQueueCount] = currItem;
			dropoffQueueCount++;
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

	function showQueueTable() {
		var P = document.getElementById("pickupTable");
		var D = document.getElementById("dropoffTable");
		var pParent = P.parentNode;
		var dParent = D.parentNode;
		console.log(pParent);
		console.log(dParent);
		var newPT = document.createElement('table');
		newPT.setAttribute('id', 'pickupTable');
		newPT.border = 1;
		newPT.className = 'pickuptable';
		var hprow = newPT.insertRow(0);
		hprow.align = 'left';
		
		var newDT = document.createElement('table');
		newDT.setAttribute('id', 'dropoffTable');
		newDT.border = 1;
		newDT.className = 'dropofftable';
		var hdrow = newDT.insertRow(0);
		hdrow.align = 'left';
		
		var currCell = hprow.insertCell(0);
		var currCell1 = hdrow.insertCell(0);
		var contents = document.createTextNode('Type');
		var contents1 = document.createTextNode('Type');
		currCell.appendChild(contents);
		currCell1.appendChild(contents1);

		var currCell = hprow.insertCell(1);
		contents = document.createTextNode('First Name');
		currCell.appendChild(contents);

		var currCell = hprow.insertCell(2);
		contents = document.createTextNode('Last Name');
		currCell.appendChild(contents);

		var currCell = hprow.insertCell(3);
		var currCell1 = hdrow.insertCell(1);
		contents = document.createTextNode('Date of Birth');
		contents1 = document.createTextNode('Date of Birth');
		currCell.appendChild(contents);
		currCell1.appendChild(contents1);

		var currCell = hdrow.insertCell(2);
		contents = document.createTextNode('Relation');
		currCell.appendChild(contents);

		var currCell = hprow.insertCell(4);
		var currCell1 = hdrow.insertCell(3);
		contents = document.createTextNode('Returning Customer');
		contents1 = document.createTextNode('Returning Customer');
		currCell.appendChild(contents);
		currCell1.appendChild(contents1);

		var currCell = hdrow.insertCell(4);
		contents = document.createTextNode('Insurance Card Number');
		currCell.appendChild(contents);

		pParent.replaceChild(newPT, P);
		dParent.replaceChild(newDT, D);

		for (var i = 0; i < pickupQueueCount; i++) {
			addRow(pickupQueue[i].id, pickupQueue[i].type, pickupQueue[i].first_name, pickupQueue[i].last_name, pickupQueue[i].date_of_birth, pickupQueue[i].relation, pickupQueue[i].returning_customer, pickupQueue[i].insurance_card_number);
		}
		for (var i = 0; i < dropoffQueueCount; i++) {
			addRow(dropoffQueue[i].id, dropoffQueue[i].type, dropoffQueue[i].first_name, dropoffQueue[i].last_name, dropoffQueue[i].date_of_birth, dropoffQueue[i].relation, dropoffQueue[i].returning_customer, dropoffQueue[i].insurance_card_number);
		}
		
		/* // Drag and drop
		hrow.setAttribute("NoDrag", "1");
		hrow.setAttribute("NoDrop", "1");
		dragAndDropTableInit();
		*/
	}

	function addRow(id, type, first_name, last_name, date_of_birth, relation, returning_customer, insurance_card_number) {
		if (type == 'pickup') {
			var T = document.getElementById("pickupTable");
		}
		else {
			var T = document.getElementById("dropoffTable");
		}
		
		var len = T.rows.length;
		var R = T.insertRow(len); 
		R.align = 'left';
		R.className = 'regular';
		R.id = id;
		
		if (type == 'pickup') {
			var C = R.insertCell(0);
			var txt = document.createTextNode(type);
			C.appendChild(txt);
			
			C = R.insertCell(1);
			txt = document.createTextNode(first_name);
			C.appendChild(txt);
			
			C = R.insertCell(2);
			txt = document.createTextNode(last_name);
			C.appendChild(txt);

			C = R.insertCell(3);
			txt = document.createTextNode(date_of_birth);
			C.appendChild(txt);
			
			C = R.insertCell(4);
			txt = document.createTextNode(returning_customer);
			C.appendChild(txt);
		}
		else {
			var C = R.insertCell(0);
			var txt = document.createTextNode(type);
			C.appendChild(txt);

			C = R.insertCell(1);
			txt = document.createTextNode(date_of_birth);
			C.appendChild(txt);
			
			C = R.insertCell(2);
			txt = document.createTextNode(relation);
			C.appendChild(txt);
			
			C = R.insertCell(3);
			txt = document.createTextNode(returning_customer);
			C.appendChild(txt);
			
			C = R.insertCell(4);
			txt = document.createTextNode(insurance_card_number);
			C.appendChild(txt);
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
        var pickupRows = document.getElementById("pickupTable").rows.length-1;
		var dropoffRows = document.getElementById("dropoffTable").rows.length-1;
		var rows = pickupRows + dropoffRows;

        if (rows == -1) {
			rows = 0;
		}
        var data = 'type=' + type + '&rows=' + rows;

        httpRequest.open('POST', 'back_end_php.php', true);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        httpRequest.onreadystatechange = function() { updateRows(httpRequest); } ;
        httpRequest.send(data);
        t = setTimeout("refreshPage()", 15000);
    }
</script>
</head>
<body onload = "Start()">
<table border = "1">
<td>
<table id = "pickupTable" border = "1" class="pickuptable">
</table>
<table id = "dropoffTable" border = "1" class="dropofftable">
</table>
</table>
<br>
<input type="button" onclick="nextPickup()" value="Next Pickup">
<input type="button" onclick="nextDropoff()" value="Next Dropoff">
</td>
<td id="display">
<table id = "displayTable">
</table>
</td>
</table>
</body>
</html>
