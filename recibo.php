<!DOCTYPE html>
<html lang="en">
<html>
<head>
	<? 
		include('./php/connect.php');
		links();
	?>
	
	<script>
		$(document).ready(function(){
		
			// Producer hash has 3 arrays codeToId, nameToId, and idToAll
			// You can use any producer name or code to get the database id, then use that id to get the rest of the producer's information
			var producer_hash = [];
			var name_keys = [];
			var code_keys = [];
			
			function fillInForProducer(mode,val){
				var id;
				if (mode == "name"){
					id = producer_hash['nameToId'][val];
				}
				else if (mode == "code"){
					id = producer_hash['codeToId'][val];
				}
				data = producer_hash['idToAll'][id];
				console.log(data);
			}

			// Set up the datepicker
			// Try to set up in spanish
			//$('#datepicker').datepicker($.datepicker.regional['fr']);
			//$('#datepicker').datepicker({ defaultDate: +0 });
			
			// Set a list of names for autocomplete
			$.getJSON("./php/getList.php",function(data){
				console.log(data);
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
					select: function(event, ui) {
						fillInForProducer('name', $('#pr_name').val());
					}
				});
				$( "#pr_code" ).autocomplete({
					source: code_keys,
					select: function(event, ui) {
						fillInForProducer('code', $('#pr_code').val());
					}
				});
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
					FOLIO&deg;: <span id="transaction_folio" class="label notice" style="font-size:15px;">1234</span>
				</div>
			</div>
			<div id="producer-left" class="well span8">
				<form>
					<fieldset>
						<label for="producer_name">PRODUCTOR</label>
						<div class="input">
							<input class="xlarge" id="pr_name" name="producer" size="30" type="text">
						</div>
						
						<label for="producer_id">CODIGO</label>
						<div class="input">
							<input class="xlarge" id="pr_code" name="producer_id" size="30" type="text">
						</div>		
					
						<label for="transaction_date">FECHA</label>
						<div class="input">
							<input class="xlarge" id="transaction_date" name="transaction_date" size="30" type="text">
						</div>		
					</fieldset>
				</form>
			</div><!-- producer-left -->

			<div id="producer-right"class="well span8">
				<form>
					<fieldset>
						
						<label for="producer_name">COMITE</label>
						<div class="input">
							<input class="xlarge" id="producer" name="producer" size="30" type="text">
						</div>
										
						<label for="producer_id" >CENTRO DE ACOPIO</label>
						<div class="input">
							<input class="xlarge" id="producer_id" name="producer_id" size="30" type="text">
						</div>		
						
						<label for="transaction_date">HORA DE ENTREGA</label>
						<div class="input" id="datepicker">
							<input class="xlarge" id="transaction_date" name="transaction_date" size="30" type="text">
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
							<label for="transaction_lot_num">N&deg; LOTE</label>
							<div class="input">
								<input class="small" id="transaction_lot_num" name="transaction_lot_num" size="30" type="text">
							</div>
							<label for="transaction_sack_num">N&deg; SACO</label>
							<div class="input">
								<input class="small" id="transaction_sack_num" name="transaction_sack_num" size="30" type="text">
							</div>
							<br>
							<div class="well">
								<span class="label">DETALLES DE PESO</span><br>
								<label for="transaction_lot_num">PESO BRUTO</label>
								<div class="input">
									<input class="mini" id="transaction_lot_num" name="transaction_lot_num" size="30" type="text">
								</div>
								<label for="transaction_sack_num">TARA</label>
								<div class="input">
									<input class="mini" id="transaction_sack_num" name="transaction_sack_num" size="30" type="text">
								</div>		
								<label for="transaction_quality">PESO NETO</label>
								<div class="input">
									<input class="mini" id="transaction_quality" name="transaction_quality" size="30" type="text">
								</div>	
							</div>
							<label for="transaction_quality">CALIDAD</label>
							<div class="input">
								<input class="small" id="transaction_quality" name="transaction_quality" size="30" type="text">
							</div>		
					</fieldset>
				</form>
			</div><!-- weight and price -->
			<div id="quality" class="well span5">
				<span class="label">CALIDAD</span>
				<form>
					<fieldset>
							<label for="transaction_sf">S/F</label>
							<div class="input">
								<input class="mini" id="transaction_sf" name="transaction_sf" size="30" type="text">
							</div>
							<label for="transaction_mordido">MORDIDO</label>
							<div class="input">
								<input class="mini" id="transaction_mordido" name="transaction_mordido" size="30" type="text">
							</div>		
							<label for="transaction_pelado">PELADO</label>
							<div class="input">
								<input class="mini" id="transaction_pelado" name="transaction_pelado" size="30" type="text">
							</div>
							<label for="transaction_verde">VERDE</label>
							<div class="input">
								<input class="mini" id="transaction_verde" name="transaction_verde" size="30" type="text">
							</div>
							<label for="transaction_broca">BROCA</label>
							<div class="input">
								<input class="mini" id="transaction_broca" name="transaction_broca" size="30" type="text">
							</div>
							<label for="transaction_moho">MOHO</label>
							<div class="input">
								<input class="mini" id="transaction_moho" name="transaction_moho" size="30" type="text">
							</div>
							<label for="transaction_gqmd">G. QMD</label>
							<div class="input">
								<input class="mini" id="transaction_gqmd" name="transaction_gqmd" size="30" type="text">
							</div>
							<label for="transaction_total">TOTAL</label>
							<div class="input">
								<input class="mini" id="transaction_total" name="transaction_total" size="30" type="text">
							</div>	
					</fieldset>
				</form>
			</div><!-- quality -->
			<div id="observations" class="well span5">
				<span class="label">OBSERVACIONES</span><br>
				<label for="transaction_observations">OBSERVACIONES</label>
				<div class="input">
					<textarea class="span5" id="transaction_observations" name="transaction_observations" rows="9"></textarea>
				</div>
				<label for="transaction_recibido">RECIBIDO POR:</label>
				<div class="input">
					<input class="xlarge" id="transaction_recibido" name="transaction_recibido" type="text" size="30"></textarea>
				</div>
			</div><!-- observations -->
		</div> <!-- middle-row -->
		
		<div id="bottom-row" class="row span18">
			<div id="signature" class="well span15">
				<div id="sign" class="input large span-two-thirds">
					<label for="sign-here" style="width:150px;">ENTREGUE CONFIRME</label>
					<input id="sign-here" class="xlarge span8" style="border-bottom-color: black;">
				</div>
				<div id="actions" class="span3">
					<button name="submit" class="btn large success" type="reset">SUBMIT</button>
				</div>
			</div>
		</div> <!-- bottom-row -->
		
	</div> <!-- container -->
</body>

</html>
