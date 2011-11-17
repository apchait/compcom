$(document).ready(function(){
		console.log("raincoat");
		function fillSection(sectionid, list, size, length){
			$.each(list, function(key,name){
				$div = $("<div>").addClass("clearfix");
				$label = $("<label>").attr("for",key);
				$label.html(name);
				$inputDiv = $("<div>").addClass("input");
				$input = $("<input>").addClass(length);
				$input.attr("id",key);
				$input.attr("name",name);
				$input.attr("size",size);
				$input.attr("type","text");
				// add savedb to the ones that should be saved
				if(key != "tr_pr_name"){
					$input.addClass("savedb");
				}
				$inputDiv.append($input);
				$div.append($label);
				$div.append($inputDiv);
				$(sectionid).append($div);
			});
		}
		function createListsAndInputs(){
			producerInfo = {"tr_pr_code":"Codigo","tr_pr_name":"Nombre","tr_date":"Fecha","pr_community":"Comite","tr_center":"Centro de Acopio","tr_time":"Hora De Entrega"};
			fillSection("#producerFields", producerInfo, 30, "medium");
			trInfo = {"tr_lot_num":"N Lote","tr_sack_num":"N Saco","tr_total_weight":"Peso Bruto","tr_tare":"Tara","tr_net_weight":"Peso Neto","tr_quality":"Calidad"};
			fillSection("#trFields", trInfo, 30, "mini");
			qualityInfo = {"tr_quality_sf":"S/F","tr_quality_mordido":"Mordido","tr_quality_pelado":"Pelado","tr_quality_verde":"Verde","tr_quality_broca":"Broca","tr_quality_moho":"Moho","tr_quality_gqmd":"G. QMD","tr_quality_total":"Total"}
			fillSection("#qualityFields", qualityInfo, 30, "mini");
		}
		createListsAndInputs();
		function setUpDatePicker(){
			// Set the datepicker and current date
			$('#tr_date').datepicker({ defaultDate: +7 });
			$('#tr_date').datepicker($.datepicker.regional['es']);
			$('#tr_date').datepicker('setDate', "+0");
		}
		setUpDatePicker();
		var online = 1;
		function setUpLocalStorage(){
			function setUpCenterAndCheckForPrice(){
				if(localStorage['marketPrices'] != undefined){
					if(JSON.parse(localStorage['marketPrices'])[myDate()] == undefined){
						console.log("no price for today");
						window.location = "dash.html";
					}
				}
				else{
					window.location = "dash.html";
				}

				if(localStorage["center"] == undefined){
					localStorage["center"] = JSON.stringify("undefined");
				};
			}
			setUpCenterAndCheckForPrice();
			function setUpCenterId(){
				var centerId = JSON.parse(localStorage["center"]);
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
			setUpCenterId();
			online = 1;
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
			function setUpFolioNumbers(){
				var centerId = JSON.parse(localStorage["center"]);
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
		}
		setUpLocalStorage();
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
							// Store it in the log
							console.log('Storing in log');
							if (localStorage['log'] == undefined){
								localStorage['log'] = JSON.stringify([data]);
							}
							else{
								log = JSON.parse(localStorage['log']);
								console.log('log before', log);
								$.each(JSON.parse(localStorage['transactions']),function(i,v){
									log.push(v);
								});
								console.log('log after', log);
								localStorage['log'] = JSON.stringify(log);
							}

							console.log('clearing local storage');
							// Save the folio and last transaction
							/*nextFolio = localStorage['nextFolio'];
							lastTr = JSON.parse(localStorage['lastTransaction']);
							biglog = JSON.parse(localStorage['log']);
							localStorage.clear();
							trArray = [];
							// Reset the necessary basiscs after clearing
							localStorage['center'] = JSON.stringify(centerId);
							localStorage['nextFolio'] = nextFolio;
							localStorage['lastTransaction'] = JSON.stringify(lastTr);
							localStorage['log'] = JSON.stringify(biglog);
							*/
							// clear offline 'transactions'
							if (localStorage['transactions'] != undefined){
								localStorage['transactions'] = JSON.stringify([]);
							}
						}
						else if(response != undefined){
							// Response failed, send an email to us and alert the user
							alert("Error: " + response);
							// Let them click ok, store the error, it has already been added to localStorage 'transactions' for offline storage, so send them to dashboard
							if (localStorage["errorLog"] != undefined){
								errorLog = JSON.parse(localStorage["errorLog"]);
							}
							else{
								errorLog = [];
							}
							d = new Date();
							errorLog.push({"message":response, "time": d});
							localStorage["errorLog"] = JSON.stringify(errorLog); 
						}
						console.log('Changing Location');
						console.log("Open new window");
						window.open("imprimir.html", "Imprimir", "width=320,height=500");
						window.location = "dash.html";
					});
				}
				else{
					console.log("offline switch");
						window.open("imprimir.html", "Imprimir", "width=320,height=500");
						window.location = "dash.html";
				}
			}); // End Submit Funtion	
		}
		setUpSubmitButton();
		// Get the a list of producers and ids from the DB and store in producer_hash
		// Also set up autcomplete with list of names and codes
		function fillInForProducer(mode,val){
			console.log("Fill in being called for " + mode + val);
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
		var producer_hash = {};
		$.getJSON('list.json',function(data){
			console.log("data", data);
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
			console.log(name_keys);
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