$(document).ready(function(){
	console.log("Action!");
	var online = 1;
	// Add a listener to tell us whether we are online or not
	window.applicationCache.addEventListener("error", function(e) {
		$("#networkStatus").html("OFFLINE");
		online = 0;
	});
	
	// Gather values from the form and submit to database
	$('#submit').click(function(){
		data = {};
		$('.savedb').each(function(i){
			if ($(this).val()){
				data[$(this).attr("name")] = $(this).val();
			}
			else if($(this).html()){
				data[$(this).attr("name")] = $(this).html();
			}
		});
		
		console.log(data);
		// If online, send to db and store locally
		
		// If not online store locally and get ready to send to db later
	/*	
		$.post('./php/addTransaction.php', {"fields": data}, function(response){
			console.log(response);
		})
	*/
	});
	
	// Set the datepicker and current date
	$('#tr_date').datepicker({ defaultDate: +7 });
	$('#tr_date').datepicker($.datepicker.regional['es']);
	$('#tr_date').datepicker('setDate', "+0");
	
	// Fill out fields when a producer is selected from an autocomplete list
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




	// If app is offline, store in localStorage
	
	// If not store in localStorage and server

	maxFolio = 0;
	// Find out what the highest folio num is so far
/*	$.each(JSON.parse(localStorage['transactions']), function(i,v){
		if(v['folio'] > maxFolio){
			maxFolio = v['folio'];
		}
	});
	
	console.log(maxFolio);*/
});