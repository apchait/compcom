<!DOCTYPE html>
<html lang="en">
<html>
<head>
	<? 
		include('./php/connect.php');
		links();
	?>
	<script src="./js/jquery.ui.datepicker-es.js"></script>
	
	<script>
		$(document).ready(function(){
			
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
				$.post('./php/addTransaction.php', {"fields": data}, function(response){
					console.log(response);
				})
			});
		
			// Producer hash has 3 arrays codeToId, nameToId, and idToAll
			// You can use any producer name or code to get the database id, then use that id to get the rest of the producer's information
			var producer_hash = [];
			var name_keys = [];
			var code_keys = [];
			
			// Set the datepicker and current date
			$('#tr_date').datepicker({ defaultDate: +7 });
			$('#tr_date').datepicker($.datepicker.regional['es']);
			$('#tr_date').datepicker('setDate', "+0");
			
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
				console.log(id);
				// Get all the data for that producer
				data = producer_hash['idToAll'][id];
				console.log(data);
				// Set the values of the different html fields to that producer
				$("#pr_name").val(data["pr_name"]);
				$("#pr_code").val(data["pr_code"]);
				$("#pr_community").val(data["pr_community"]);
			}
				
			// Set a list of names for autocomplete
			$.getJSON("./php/getList.php",function(data){
				// Get the names (keys of the array)
				producer_hash = data;
				$.each(data["nameToId"], function(i, v){
					name_keys.push(i);
				});
				$.each(data["codeToId"], function(i, v){
					code_keys.push(i);
				});
				$( "#pr_name" ).autocomplete({
					source: name_keys,
					close: function(event, ui) {
						fillInForProducer('name', $('#pr_name').val());
					}
				});
				$( "#pr_code" ).autocomplete({
					source: code_keys,
					close: function(event, ui) {
						fillInForProducer('code', $('#pr_code').val());
					}
				});
			});
			
			$( "#pr_code" ).change(function(event, ui) {
				fillInForProducer('code', $('#pr_code').val());
			});
			
			
			
		});
	</script>
	
</head>

<body>
	<?php include("header.php")?>


	<!-- Start Overall Container -->
	<div class="container span18">
	<div id="logo-row" class="row span18">
		<div class="span3">
			<img src="compcom_logo.png"/>
		</div>
		<div id="address" class="span12">
			<ul style="list-style-type:none; text-align:center;">			
				<li><strong>COOPERATIVA MULTISECTORIAL DE PRODUCTORES DE CAFE ORGANICO DE MATAGALPA</strong></li>
				<li>Biblioteca del Banco Central 3 C. Este 20 Mts al Sur.   TEL: 2772 3486</li>
				<li>&nbsp</li>
				<li><strong>RECIBO DE CAFE ORGANICO 2011/2012</strong></li>
				
		</div>
	</div>
	
	
	<div id="top-row" class="row span18">
			<div id="row" style="margin-top:5px; margin-bottom 5px;">
				<div class="span15">
					<span class="label">INFORMACION DE PRODUCTOR</span>
				</div>
				<div class="span2">
					FOLIO&deg;: <span id="tr_folio" name="tr_folio" class="label notice savedb" style="font-size:15px;">1234</span>
				</div>
			</div>
			<div id="producer-left" class="well span8">
				<form>
					<fieldset>
						<label for="">PRODUCTOR</label>
						<div class="input">
							<input class="xlarge savedb" id="pr_name" name="pr_names" size="30" type="text">
						</div>
						
						<label for="pr_code">CODIGO</label>
						<div class="input">
							<input class="xlarge savedb" id="pr_code" name="pr_code" size="30" type="text">
						</div>		
					
						<label for="tr_date">FECHA</label>
						<div class="input">
							<input class="xlarge savedb" id="tr_date" name="tr_date" size="30" type="text">
						</div>		
					</fieldset>
				</form>
			</div><!-- producer-left -->

			<div id="producer-right"class="well span8">
				<form>
					<fieldset>
						
						<label for="pr_community">COMITE</label>
						<div class="input">
							<input class="xlarge savedb" id="pr_community" name="pr_community" size="30" type="text">
						</div>
										
						<label for="tr_center" >CENTRO DE ACOPIO</label>
						<div class="input">
							<input class="xlarge savedb" id="tr_center" name="tr_center" size="30" type="text">
						</div>		
						
						<label for="tr_hour">HORA DE ENTREGA</label>
						<div class="input" id="datepicker">
							<input class="xlarge savedb" id="tr_date" name="tr_date" size="30" type="text">
						</div>		
						
					</fieldset>
				</form>
			</div><!-- producer-right -->
		</div> <!-- top-row -->
		
		<div id="middle-row" class="row span18">
			<div id="weight-and-price" class="well span5">
				<span class="label">PESO Y PRECIO</span>
				<form>
					<fieldset>
							<label for="tr_lot_num">N&deg; LOTE</label>
							<div class="input">
								<input class="small savedb" id="tr_lot_num" name="tr_lot_num" size="30" type="text">
							</div>
							<label for="tr_sack_num">N&deg; SACO</label>
							<div class="input">
								<input class="small savedb" id="tr_sack_num" name="tr_sack_num" size="30" type="text">
							</div>
							<br>
							<div class="well">
								<span class="label">DETALLES DE PESO</span><br>
								<label for="tr_total_weight">PESO BRUTO</label>
								<div class="input">
									<input class="mini savedb" id="tr_total_weight" name="tr_total_weight" size="30" type="text">
								</div>
								<label for="tr_sack_num">TARA</label>
								<div class="input">
									<input class="mini savedb" id="tr_sack_num" name="tr_sack_num" size="30" type="text">
								</div>		
								<label for="tr_net_weight">PESO NETO</label>
								<div class="input">
									<input class="mini savedb" id="tr_net_weight" name="tr_net_weight" size="30" type="text">
								</div>	
							</div>
							<label for="tr_quality">CALIDAD</label>
							<div class="input">
								<input class="small savedb" id="tr_quality" name="tr_quality" size="30" type="text">
							</div>		
					</fieldset>
				</form>
			</div><!-- weight and price -->
			<div id="quality" class="well span5">
				<span class="label">CALIDAD</span>
				<form>
					<fieldset>
							<label for="tr_qualitysf">S/F</label>
							<div class="input">
								<input class="mini savedb" id="tr_quality_sf" name="tr_quality_sf" size="30" type="text">
							</div>
							<label for="tr_quality_mordido">MORDIDO</label>
							<div class="input">
								<input class="mini savedb" id="tr_quality_mordido" name="tr_quality_mordido" size="30" type="text">
							</div>		
							<label for="tr_quality_pelado">PELADO</label>
							<div class="input">
								<input class="mini savedb" id="tr_quality_pelado" name="tr_quality_pelado" size="30" type="text">
							</div>
							<label for="tr_quality_verde">VERDE</label>
							<div class="input">
								<input class="mini savedb" id="tr_quality_verde" name="tr_quality_verde" size="30" type="text">
							</div>
							<label for="tr_quality_broca">BROCA</label>
							<div class="input">
								<input class="mini savedb" id="tr_quality_broca" name="tr_quality_broca" size="30" type="text">
							</div>
							<label for="tr_quality_moho">MOHO</label>
							<div class="input">
								<input class="mini savedb" id="tr_quality_moho" name="tr_quality_moho" size="30" type="text">
							</div>
							<label for="tr_quality_gqmd">G. QMD</label>
							<div class="input">
								<input class="mini savedb" id="tr_quality_gqmd" name="tr_quality_gqmd" size="30" type="text">
							</div>
							<label for="tr_quality_total">TOTAL</label>
							<div class="input">
								<input class="mini savedb" id="tr_quality_total" name="tr_quality_total" size="30" type="text">
							</div>	
					</fieldset>
				</form>
			</div><!-- quality -->
			<div id="observations" class="well span5">
				<span class="label">OBSERVACIONES</span><br>
				<label for="tr_observations">OBSERVACIONES</label>
				<div class="input">
					<textarea class="span5 savedb" id="tr_observations" name="tr_observations" rows="9"></textarea>
				</div>
				<label for="tr_reciever">RECIBIDO POR:</label>
				<div class="input">
					<input class="xlarge savedb" id="tr_reciever" name="tr_reciever" type="text" size="30"></textarea>
				</div>
			</div><!-- observations -->
		</div> <!-- middle-row -->
		
		<div id="bottom-row" class="row span18">
			<div id="signature" class="well span15">
				<div id="sign" class="input large span-two-thirds">
					<label for="sign-here" style="width:150px;">ENTREGUE CONFIRME</label>
					<input class="xlarge span8 savedb" style="border-bottom-color: black;">
				</div>
				<div id="actions" class="span3">
					<button name="submit" class="btn large success" id="submit" type="reset">SUBMIT</button>
				</div>
			</div>
		</div> <!-- bottom-row -->
		
	</div> <!-- container -->
</body>

</html>
