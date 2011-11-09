$(document).ready(function(){
	console.log("Action!");
	
	function setUpPopover(){
		// Set up popover with localStorage
		function popoverContent(){
			trs = JSON.parse(localStorage['transactions']);
			$table = $("<table>").append($("<tr>").append($("<td>").html("Codigo"), $("<td>").html("Folio")));
			$.each(trs,function(i,v){
				$table.append($("<tr>").append($("<td>").html(v['tr_pr_code']), $("<td>").html(v['tr_folio'])));
			});			
			return $table;

		}
		// Set up the popover to call popoverContent to get a table of trs in localStorage
		$("#tr_popover").popover({"content":popoverContent, "html":1, "placement": "below"});
		// Set up click to clear LS for now
		$("#tr_popover").click(function(){
			localStorage.clear();
		});
	}	
	setUpPopover();
	
	var centerId = JSON.parse(localStorage["center"]);
	function setUpFolioNumbers(){
		if(centerId == "rancho grande"){
			folioStart = 0;
		}
		else if (centerId == "matagalpa"){
			folioStart = 5000;
		}
		else{
			folioStart = 10000;
		}
		// If there hasn't been a folio yet, set it to the start folio for this center
		if(localStorage["nextFolio"] == undefined){
			localStorage["nextFolio"] = folioStart;
		}
		currentFolio = localStorage["nextFolio"];
		$("#tr_folio").html(currentFolio);
	}
	setUpFolioNumbers();
	
	function setUpLocalStorage(){
		// Set the acopio center id
		localStorage['center'] = JSON.stringify(centerId);
		// Set up an array to hold local transactions if there are any, and take new ones
		trArray = [];
		if (JSON.parse(localStorage['transactions'] != null)){
			trArray = JSON.parse(localStorage['transactions']);
		}
		trLength = trArray.length;

		// tell us how many transactions are stored locally
		console.log(trLength + " transaction stored locally");
		$("#storedTransactions").html(trLength);
	}
	setUpLocalStorage();
	
	var online = 1;
	function setUpOnlineOrNot(){
		console.log("Online", online);
		// Add a listener to tell us whether we are online or not
		window.applicationCache.addEventListener("error", function(e) {
			console.log("offline!");
			$("#networkStatus").html("OFFLINE");
			$("#tr_popover").toggleClass("success");
			$("#tr_popover").toggleClass("danger");
			online = 0;
		});
	}
	setUpOnlineOrNot();
	
	function setUpSubmitButton(){
		// Gather values from the form and submit to database
		$('#submit').click(function(){
			console.log("Online" + online);
			data = {};
			// Collect fields from html into data dictionary
			$('.savedb').each(function(i){
				if ($(this).val()){
					data[$(this).attr("name")] = $(this).val();
				}
				else if($(this).html()){
					data[$(this).attr("name")] = $(this).html();
				}
			});
			// Make sure there are enough fields here
			if (data['tr_pr_code'] == undefined){
				alert("Codigo Necesario");
				return 0;
			}
			else{
				// De-activate submit button
				$("#submit").button("loading");
			}
			// If not, ask to fill required fields
			// Push that onto the list of transactions
			console.log(data);
			trArray.push(data);
			// Update the localStorage list of transactions
			localStorage['transactions'] = JSON.stringify(trArray);
			// Replace the last saved transaction with this one for follow up page
			localStorage['lastTransaction'] = JSON.stringify(data);
			// Increment and save the folio number
			localStorage['nextFolio'] = Number(localStorage['nextFolio']) + 1;
			// Everything in order send to db if online
			if(online){
				$.post('./recieveLocalStorage.php',{'transactions' : JSON.parse(localStorage['transactions']), "center": JSON.parse(localStorage['center'])}, function(response){
					console.log(response);
					if(response == "success"){
						console.log('clearing local storage');
						// Save the folio and last transaction
						nextFolio = localStorage['nextFolio'];
						lastTr = JSON.parse(localStorage['lastTransaction']);
						localStorage.clear();
						trArray = [];
						localStorage['center'] = JSON.stringify(centerId);
						localStorage['nextFolio'] = nextFolio;
						localStorage['lastTransaction'] = JSON.stringify(lastTr);
					}
					else if(response != undefined){
						// Response failed, send an email to us and alert the user
						alert("Error: " + response);
						// Re-activate submit button
						$("#submit").button("reset");
						return 1;
					}
					console.log('Changing Location');
					console.log("Open new window");
					window.open("imprimir.html", "Imprimir", "width=320,height=500");
					window.location = "dash.html";
				});
			}
			else{
				console.log("offline switch");
					window.open("imprimir.html");
					window.location = "dash.html";
			}
		}); // End Submit Funtion	
	}
	setUpSubmitButton();
	
	function setUpDatePicker(){
		// Set the datepicker and current date
		$('#tr_date').datepicker({ defaultDate: +7 });
		$('#tr_date').datepicker($.datepicker.regional['es']);
		$('#tr_date').datepicker('setDate', "+0");
	}
	setUpDatePicker();
	
	// Function that fills out fields when a producer is selected from an autocomplete list
	function fillInForProducer(mode,val){
		// Use the name or the code to get the database id
		var id;
		if (mode == "name"){
			id = producer_hash['nameToId'][val];
		}
		else if (mode == "code"){
			id = producer_hash['codeToId'][val];
		}
		console.log(producer_hash, val);
		// Get all the data for that producer
		data = producer_hash['idToAll'][id];
		// Set the values of the different html fields to that producer
		$("#tr_pr_name").val(data["pr_name"]);
		$("#tr_pr_code").val(data["pr_code"]);
		$("#pr_community").val(data["pr_community"]);
	}
	
	// Get the a list of producers and ids from the DB and store in producer_hash
	// Also set up autcomplete with list of names and codes
	var producer_hash = {};
	$.getJSON('list.json',function(data){
		// Build producer and code list
		producer_hash = data;
		name_keys = [];
		code_keys = [];
		$.each(data["nameToId"], function(i, v){
			name_keys.push(i);
		});
		$.each(data["codeToId"], function(i, v){
			code_keys.push(i);
		});
		alert(name_keys[0]);
		$( "#tr_pr_name" ).autocomplete({
			source: name_keys,
			close: function(event, ui) {
				fillInForProducer('name', $('#tr_pr_name').val());
			}
		});
		$( "#tr_pr_code" ).autocomplete({
			source: code_keys,
			close: function(event, ui) {
				fillInForProducer('code', $('#tr_pr_code').val());
			}
		});
//				$.each(data,function(i,v){
//					console.log(v['pr_code']);
//				});
		
		//console.log(data);
	});

});