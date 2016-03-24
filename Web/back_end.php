<!DOCTYPE html>
<html>
<head>
<title>Kiosk Queue</title>
<script type="text/javascript" src="../php/tablednd.js"></script>
<script type="text/javascript">
	var theQueue = new Array(), queueCount = 0, t;
	
	function Start() {
		refreshPage();
	}
	
	/* // Drag and drop
	function dragAndDropTableInit() {
		var table = document.getElementById('theTable');
		var tableDnD = new TableDnD();
		tableDnD.init(table);
	}
	*/
	
	function wrap(top, selector, bottom) {
		var matches = document.querySelectorAll(selector);
		for (var i = 0; i < matches.length; i++){
			var modified = top + matches[i].outerHTML + bottom;
			matches[i].outerHTML = modified;
			console.log(modified);
		}
	}
	
	function next() {
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
		var T = document.getElementById("theTable");
		var id = T.rows[1].id;
		console.log(id);
		var item = removeRowFromList(id);
		displayData(item);
		T.deleteRow(1);

		var data = 'type=' + type + '&id=' + id;
		
		// comment this out to disable database connection for easier testing
		httpRequest.open('POST', 'back_end_php.php', true);
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		httpRequest.onreadystatechange = function() { displayData(); } ;
		httpRequest.send(data);
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
				if (rettype == "Update") {
					queueCount = 0;
					var newRows = newData.Contents;
					for (var i = 0; i < newRows.length; i++) {
						var theRow = newRows[i];
						addRowToList(theRow.id, theRow.type, theRow.first_name, theRow.last_name, theRow.date_of_birth, theRow.relation, theRow.returning_customer, theRow.insurance_card_number);
					}
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
		theQueue[queueCount] = currItem;
		queueCount++;
	}
	
	function removeRowFromList(id) {
		var ret = 0;
		for (var i = 0; i < theQueue.length; i++) {
			if (theQueue[i].id == id) {
				ret = theQueue[i];
				theQueue.splice(i, 1);
				break;
			}
		}
		queueCount--;
		return ret;
	}

	function showQueueTable() {
		var T = document.getElementById("theTable");
		var tParent = T.parentNode;

		var newT = document.createElement('table');
		newT.setAttribute('id', 'theTable');
		newT.border = 1;
		newT.className = 'thetable';
		var hrow = newT.insertRow(0);
		hrow.align = 'left';

		var currCell = hrow.insertCell(0);
		var contents = document.createTextNode('Type');
		currCell.appendChild(contents);

		var currCell = hrow.insertCell(1);
		contents = document.createTextNode('First Name');
		currCell.appendChild(contents);

		var currCell = hrow.insertCell(2);
		contents = document.createTextNode('Last Name');
		currCell.appendChild(contents);

		var currCell = hrow.insertCell(3);
		contents = document.createTextNode('Date of Birth');
		currCell.appendChild(contents);

		var currCell = hrow.insertCell(4);
		contents = document.createTextNode('Relation');
		currCell.appendChild(contents);

		var currCell = hrow.insertCell(5);
		contents = document.createTextNode('Returning Customer');
		currCell.appendChild(contents);

		var currCell = hrow.insertCell(6);
		contents = document.createTextNode('Insurance Card Number');
		currCell.appendChild(contents);

		tParent.replaceChild(newT, T);

		for (var i = 0; i < queueCount; i++) {
			addRow(theQueue[i].id, theQueue[i].type, theQueue[i].first_name, theQueue[i].last_name, theQueue[i].date_of_birth, theQueue[i].relation, theQueue[i].returning_customer, theQueue[i].insurance_card_number);
		}
		
		/* // Drag and drop
		hrow.setAttribute("NoDrag", "1");
		hrow.setAttribute("NoDrop", "1");
		dragAndDropTableInit();
		*/
	}

	function addRow(id, type, first_name, last_name, date_of_birth, relation, returning_customer, insurance_card_number) {
		var T = document.getElementById("theTable");
		var len = T.rows.length;
		var R = T.insertRow(len); 
		R.align = 'left';
		R.className = 'regular';
		R.id = id;

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
		txt = document.createTextNode(relation);
		C.appendChild(txt);
		
		C = R.insertCell(5);
		txt = document.createTextNode(returning_customer);
		C.appendChild(txt);
		
		C = R.insertCell(6);
		txt = document.createTextNode(insurance_card_number);
		C.appendChild(txt);
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
        var rows = document.getElementById("theTable").rows.length-1;;

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
<table id = "theTable" border = "1" class="thetable">
</table>
<br>
<input type="button" onclick="next()" value="Next">
</td>
<td id="display">
<table id = "displayTable">
</table>
</td>
</table>
</body>
</html>
