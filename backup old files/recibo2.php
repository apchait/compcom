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
			$('#centro_fecha').datepicker({ defaultDate: +7 });
			$('#centro_fecha').datepicker($.datepicker.regional['es']);
			$('#centro_fecha').datepicker('setDate', "+0");

			// Set the datepicker and current date
			$('#pp_fecha').datepicker({ defaultDate: +7 });
			$('#pp_fecha').datepicker($.datepicker.regional['es']);
			$('#pp_fecha').datepicker('setDate', "+0");

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
				<li>&nbsp
				<li><strong> REMISION DE TRASLADO DE CAFÉ A BENEFICIO SECO </strong></li>
				
		</div>
	</div>
	
	
	<div id="top-row" class="row span18">
			<div id="row" style="margin-top:5px; margin-bottom 5px;">
				<div class="span14">
					<span class="label">INFORMACION DE PRODUCTOR</span>
				</div>
				<div class="span3">
					Remisión N&deg;: <span id="tr_remision" name="tr_remision" class="label notice savedb" style="font-size:15px;">1234</span>
				</div>
			</div>
			<div id="producer-left" class="well span8">
				<form>
					<fieldset>
						<label for="">PRODUCTOR</label>
						<div class="input">
							<input class="xlarge savedb" id="pr_name" name="pr_names" size="30" type="text">
						</div>
						
						<label for="pr_zona">ZONA</label>
						<div class="input">
							<input class="xlarge savedb" id="pr_zona" name="pr_zona" size="30" type="text">
						</div>		
					
						<label for="pr_responsable_de_acopio">RESPONSABLE DE ACOPIO</label>
						<div class="input">
							<input class="xlarge savedb" id="pr_responsable_de_acopio" name="pr_responsable_de_acopio" size="30" type="text">
						</div>		
					</fieldset>
				</form>
			</div><!-- producer-left -->

			<div id="producer-right"class="well span8">
				<form>
					<fieldset>
						
						<label for="pr_ciclo">CICLO</label>
						<div class="input">
							<input class="xlarge savedb" id="pr_ciclo" name="pr_ciclo" size="30" type="text">
						</div>
										
						<label for="tr_center" >CENTRO DE ACOPIO</label>
						<div class="input">
							<input class="xlarge savedb" id="tr_center" name="tr_center" size="30" type="text">
						</div>		
						
					</fieldset>
				</form>
			</div><!-- producer-right -->
		</div> <!-- top-row -->
		
		<!-- middle-row --!>
		<div id="middle-row" class="row span16">

			<!-- middle section --!>
			<div id="middle" class="well span16">

				<!-- centro --!>
				<div id="centro" class="span16">

				<span class="label">CENTRO</span>
				<div class="row"> <!-- row (centro) --!>
				<div id="centro_date" class="span8"><!-- centro date--!>
				<form>
					<fieldset>
						
						<label for="centro_fecha">FECHA DE SALIDA</label>
							<div class="input" id="datepicker">
								<input class="xlarge savedb" id="centro_fecha" name="centro_fecha" size="30" type="text">
							</div> <!-- date picker--!>
							<label for="centro_hora">HORA</label>
							<div class="input">
								<input class="large savedb" id="centro_hora" name="centro_hora" size="30" type="text">
							</div>
					</fieldset>
				</form>
				</div> <!-- centro date--!>
				
			
				<div class="centro_info" class="span8"><!-- centro info --!>
				<form>
					<fieldset>
					<label for="centro_comite">COMITE</label>
						<div class="input">
							<input class="large savedb" id="centro_comite" name="centro_comite" size="30" type="text">
						</div>


					<label for="centro_calidad">CALIDAD</label>
						<div class="input">
							<input class="small savedb" id="centro_calidad" name="centro_calidad" size="30" type="text">
						</div>

					<label for="centro_no_de_lotes">N&deg; DE LOTES</label>
						<div class="input">
							<input class="medium savedb" id="centro_no_de_lotes" name="centro_no_de_lotes" size="30" type="text">
						</div>

					<label for="centro_sacos">SACOS</label>
						<div class="input">
							<input class="mini savedb" id="centro_sacos" name="centro_sacos" size="30" type="text">
						</div>
					<label for="centro_calidad">PESO NETO</label>
						<div class="input">
							<input class="small savedb" id="centro_peso_neto" name="centro_peso_neto" size="30" type="text">
						</div>
					<label for="centro_calidad">MARCA</label>
						<div class="input">
							<input class="small savedb" id="centro_marca" name="centro_marca" size="30" type="text">
						</div>
					</fieldset>
				</form>
				</div><!-- centro info --!>

				</div> <!-- row (centro) --!>
			</div> <!-- centro --!>
		
			<div id="planta_procesadora" class="span16"><!-- planta_procesadora --!>
				<span class="label">PLANTA PROCESADORA</span><br>
				<div class="row">
				<div id="pp_date" class="span8"><!--pp_date--!>
				<form>
					<fieldset>
						<label for="pp_fecha">FECHA DE ENTRADA</label>
							<div class="input" id="datepicker">
								<input class="xlarge savedb" id="pp_fecha" name="pp_fecha" size="30" type="text">
							</div> <!-- date picker--!>
					</fieldset>
				</form>
				</div> <!--pp_date--!>

				<div class="planta_procesadora_info" class="span8"><!-- pp_info --!>
				<form>
					<fieldset>
					<label for="pp_quality">CALIDAD</label>
						<div class="input">
							<input class="mini savedb" id="pp_quality" name="pp_quality" size="30" type="text">
						</div>
					<label for="pp_lot_num">N&deg; LOTE</label>
						<div class="input">
							<input class="mini savedb" id="pp_lot_num" name="pp_lot_num" size="30" type="text">
						</div>		
					<label for="pp_sacos">SACOS</label>
						<div class="input">
							<input class="mini savedb" id="pp_sacos" name="pp_sacos" size="30" type="text">
						</div>	
					<label for="pp_peso_neto">PESO NETO</label>
						<div class="input">
							<input class="mini savedb" id="pp_peso_neto" name="pp_peso_neto" size="30" type="text">
						</div>
					</fieldset>
				</form>
				</div> <!-- pp_info --!>
				</div> <!-- row --!>
				</div> <!-- planta procesadora --!>

				<div id="actions" class="span3 offset10">
					<button name="submit" class="btn large default" id="submit" type="reset">add transaction</button>
				</div>

			</div><!-- middle section -->
		
		<div id="bottom-row" class="row span16">

			<div id="signature_input" class="well span15">
				<div id="entregado" class="input large">
					<label for="entregado_por" style="width:250px;">ENTREGADO POR:</label>
					<input class="xlarge span8 savedb" style="border-bottom-color: black;">
				</div>
				<div id="recibido" class="input large">
					<label for="recibido_por" style="width:250px;">RECIBIDO POR:</label>
					<input class="xlarge span8 savedb" style="border-bottom-color: black;">
				</div>
				<div id="responsable" class="input large">
					<label for="responsable_de_transporte" style="width:250px;">RESPONSABLE DE TRANSPORTE:</label>
					<input class="xlarge span8 savedb" style="border-bottom-color: black;">
				</div>
				<div id="placa" class="input large">
					<label for="placa" style="width:250px;">PLACA:</label>
					<input class="xlarge span8 savedb" style="border-bottom-color: black;">
				</div>
				
				<br><br>
				
				<div id="actions" class="span2 offset7">
					<button name="submit" class="btn large success" id="submit" type="reset">SUBMIT</button>
				</div>
				
				</div>
				
			</div>

		</div> <!-- bottom-row -->
		
	</div> <!-- container -->
</body>

</html>
