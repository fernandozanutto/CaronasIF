<?php
include_once "cabecalho.php";
include 'menu_lateral.php';

$carros = $controle->listarCarros($_SESSION['user']);

if(isset($_SESSION['post'])){
	@$origem = $_SESSION['post']['origem'];
	@$destino = $_SESSION['post']['destino'];
	@$saida = $_SESSION['post']['saida'];
	@$chegada = $_SESSION['post']['chegada'];
	@$percurso = $_SESSION['post']['percurso'];
	unset($_SESSION['post']);
}
else{
	$origem = $destino = $saida = $chegada = $percurso = '';
}


$temp="<select class='form-control' name='carro'><option>Selecione</option>";
$disabled='';
if(!$carros){
	echo"
	<div class='bs-component'>
		<div id='myModal' class='modal fade' role='dialog'>
			<div class='modal-dialog'>
				<div class='modal-content'>

					<div class='panel panel-warning'>

						<div class='panel-heading'>
							<h3 class='panel-title'>Nenhum carro cadastrado</h3>
						</div>

						<div class='modal-body'>
						    <p>Para poder cadastrar uma carona é necessário que você tenha um carro cadastrado.</p>
							<p>Você ainda poderá preencher o formulário, mas não poderá submetê-lo</p>
						</div>

						<div class='modal-footer'>
							<a href='formCadCarro.php' class='btn btn-primary'>Ir para página de cadastro de carros</a>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>";
	$disabled = 'disabled';
}
else{

	foreach ($carros as $carro) {
	    $id = $carro->getIdCarro();
	    $nome = $carro->getMarca() . " " . $carro->getModelo();
	    $temp.= "<option value='$id'>$nome</option>";
	}
}

$temp.="</select>";
?>

<div class='col-xs-12 col-md-9'>
    <form role='form' action='processa.php' method='post' onsubmit='salvarWp()'>

        <input type='hidden' name='acao' value='cad_carona'>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3>Cadastro de Caronas</h3>
			</div>

		  	<div class="panel-body">

				<div class='row'>
					<div class='col-md-4'>
						<div class='form-group label-floating'>
							<label class='control-label'>Ida ou Volta do Campus? </label>
							<select id='ida_volta' class='form-control' name='ida_volta'>
								<option>Selecione</option>
								<option value='ida'>Ida</option>
								<option value='volta'>Volta</option>
							</select>
						</div>
					</div>
					<div class='col-md-4'>
				 		<div class="form-group label-floating">
    						<label class="control-label">Origem</label>
    						<input class="form-control" type="text" name="origem" id='origem' value="<?php echo $origem; ?>">
    					</div>
					</div>

					<div class='col-md-4'>
                 		<div class="form-group label-floating">
    						<label class="control-label">Destino</label>
    						<input class="form-control" type="text" name="destino" id='destino' value="<?php echo $destino; ?>">
    					</div>
					</div>
				</div>

				<div class='row'>
					<div class='col-md-2'>
						<div class="form-group label-floating">
	    					<label class="control-label">Horário saída:</label>
							<input class="form-control tempo" type="time" name="saida" value="<?php echo $saida; ?>">
	    				</div>
					</div>
					<div class='col-md-2'>
						<div class="form-group label-floating">
	    					<label class="control-label">Horário chegada:</label>
							<input class="form-control tempo" type="time" name="chegada" value="<?php echo $chegada; ?>">
		    			</div>
					</div>

					<div class='col-md-4'>
						<div class='form-group label-floating'>
							<label class='control-label'>Carro: </label>
							<?php echo $temp; ?>
						</div>
					</div>

					<div class='col-md-4'>
						<div class='checkbox'>
							<label>
								<input type='checkbox' name='mostrar_carro' value='sim'> Mostrar Carro?
							</label>
						</div>
					</div>
				</div>

				<div class='row'>
					<div class='col-md-12'>
						<label class="control-label">
							<h4>Dias da carona:</h4>
						</label>

						<div class="checkbox">
							<label>
								<input type="checkbox" name="dias[]" value="seg" > Segunda
							</label>

							<label>
								<input type="checkbox" name="dias[]" value="ter"> Terça
							</label>

							<label>
								<input type="checkbox" name="dias[]" value="qua"> Quarta
							</label>

							<label>
								<input type="checkbox" name="dias[]" value="qui"> Quinta
							</label>

							<label>
								<input type="checkbox" name="dias[]" value="sex"> Sexta
							</label>

							<label>
								<input type="checkbox" name="dias[]" value="sab"> Sábado
							</label>
							<label>
								<input type="checkbox" name="dias[]" value="dom"> Domingo
							</label>
						</div>
		            </div>
				</div>

				<div class='row'>
					<div class='col-md-6'>
						<span class=red>* clique duas vezes no local de origem</span>
						<div id="map" style="width:100%; height:350px; margin-bottom: 2em;"></div>

						<button type='button' onclick="mudarModo('wp');" >Marcar pontos de caminho alternativo</button>
						<button type='button' onclick="mudarModo('rota');" >Mudar local da rota</button>
						<button type='button' onclick="resetWayPoints()" >Resetar a rota</button>
					</div>
					<div class='col-md-6'>
						<div id="painel" style="width: 100%"></div>
					</div>
				</div>
				<div class='row'>
					<div class='col-md-12'>
						<button type='submit' <?php echo $disabled; ?> class='btn btn-raised btn-primary' id='cadastrar' value='Cadastrar'>Cadastrar<div class='ripple-container'></div></button>
					</div>
				</div>

				<input type='hidden' name='percurso' value='a' id='wayp'>
				<input type='hidden' id='temp'>
				<input type='hidden' id='temp2'>

			</div>
		</div>
	</form>
</div>

<script>

var modo = 'rota';
var wp = [];
var inicio = '';
var destino = '';

function mudarModo(x){
	modo = x;
}

function iniciarMapa() {

	var feliz = new google.maps.LatLng (-29.455143906645727, -51.310272216796875);

	var mapCanvas = document.getElementById("map");

	var mapOptions = {
		center: feliz,
		zoom: 11
	};

	map = new google.maps.Map(mapCanvas, mapOptions);

	directionsService = new google.maps.DirectionsService;
	directionsDisplay = new google.maps.DirectionsRenderer;
	geocoder = new google.maps.Geocoder;


	map.addListener('dblclick', function(event) {

		var latlngStr = event.latLng.lat() +", " + event.latLng.lng();
		var x =  new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());

		if( modo == 'rota'){
		    setRota(x);
			document.getElementById('temp2').value = latlngStr;
			getNomePonto();
		}
		else if (modo == 'wp'){
			wp.push({location : x, stopover:true});
			setRota();
		}
	});
}

function setRota(){

	directionsDisplay.setMap(null);

	if(modo == 'rota'){
		wp=[];

		if (arguments.length == 1){
			if(document.getElementById('temp').value == "ida"){
				inicio = arguments[0];
				destino = document.getElementById('destino').value;
			}
			else if (document.getElementById('temp').value == "volta"){
				inicio = document.getElementById('origem').value;
				destino = arguments[0];
			}
		}
		else if(arguments.length == 2){
			inicio = arguments[0];
			destino = arguments[1];
		}
	}

	document.getElementById('painel').innerHTML = '';

	directionsDisplay.setMap(map);
	directionsDisplay.setPanel(document.getElementById('painel'));

	var request = {
		origin: inicio,
		destination: destino,
		waypoints: wp,
		optimizeWaypoints: true,
		travelMode: google.maps.DirectionsTravelMode.DRIVING
	};
	console.log(wp);

	directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
		}else{
			alert("Selecione se é ida ou volta do Campus");
		}
	});
}

function getNomePonto(){
	var input = document.getElementById('temp2').value;
	var latlngStr = input.split(',');

	var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
	geocoder.geocode({'location': latlng, 'region' : "BR"}, function(results, status) {
	if (status === google.maps.GeocoderStatus.OK) {
		if (results[1]) {
			if(document.getElementById('temp').value == "ida"){
				document.getElementById('origem').value = results[0].formatted_address;
			}
			else if (document.getElementById('temp').value == "volta"){
				document.getElementById('destino').value = results[0].formatted_address;
			}
	    } else {
	        alert('No results found');
	    }
	    } else {
	      	alert('Geocoder failed due to: ' + status);
	    }
	});
}

function resetWayPoints(){
	wp = [];
	setRota();
}

function salvarWp(){
	var temp = '';

	for (var x=0; x<wp.length; x++){
		temp = temp + wp[x].location + "|";
	}

	document.getElementById('wayp'). value=temp;
}

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GX1R6eI0Ut1R4tQzGaTyb8RqVheTVQI&callback=iniciarMapa" async></script>
<?php include_once "rodape.php"; ?>
