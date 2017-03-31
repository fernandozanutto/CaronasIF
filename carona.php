<?php

include_once 'cabecalho.php';
include_once 'menu_lateral.php';

$id = $_GET['id'];
$caronaBase = $controle->getCarona($id);

$caronas = $controle->listarCaronaSub($id);
$strDias = $caronaBase->getDias();
$dias = explode('-', $caronaBase->getDias());

$checkbox_dias="<div class='form-group'><div class='checkbox'>";

foreach ($dias as $dia) {
	$checkbox_dias .= "
	<label>
		<input name='dia[]' class='dias_carona' value='$dia' type='checkbox'> $dia
	</label>";
}

$checkbox_dias .= "</div></div>";

$saida = $caronaBase->getHorarioSaida();
$chegada = $caronaBase->getHorarioChegada();
$dono = $caronaBase->getDono();
$origem = $caronaBase->getOrigem();
$destino = $caronaBase->getDestino();
$percurso = $caronaBase->getPercurso();

$ativo = $caronaBase->getAtivo() ? "" : "CARONA INATIVA";

$carro = $caronaBase->getCarro();
$ar = $carro->getArCondicionado() ? "Sim" : "Não";
$lugares = $carro->getLugares();
$placa = $carro->getPlaca();

$carro= $caronaBase->getMostrarCarro() ? "Carro: ".$caronaBase->getCarro()->getMarca() . " " . $caronaBase->getCarro()->getModelo()."</h4><h4>Ar Condicionado: $ar</h4><h4>Lugares: $lugares</h4><h4>Placa: $placa" : "";
$temp='';

foreach ($dias as $dia) {

	switch ($dia){
		case 'seg':
			$label = 'label-success';
			break;
		case 'ter':
			$label = 'label-warning';
			break;
		case 'qua':
			$label = 'label-danger';
			break;
		case 'qui':
			$label = 'label-info';
			break;
		case 'sex':
			$label = 'label-primary';
			break;
		case 'sab':
			$label = 'label-default';
			break;
		case 'dom':
			$label = 'label-default';
			break;
		default:
			$label = '';
	}
	$temp.= " <label class='dias label $label'>$dia</label> ";

}

echo"

<div class='col-xs-12 col-md-9'>

	<div class='panel panel-default'>
		<div class='panel-heading'>
        	<h3>$origem <span class='glyphicon glyphicon-arrow-right'></span> $destino $ativo</h3>
		</div>

		<div class='panel-body'>

			<div class='row'>
				<div class='col-md-6'>
					<h4>Dias: $temp</h4>
					<h4>Horário de Saída: $saida</h4>
					<h4>Horário de Chegada: $chegada</h4>
					<h4>Quem disponibiliza a carona: <a href='perfil.php?id={$dono->getId()}'>{$dono->getNome()} {$dono->getSobrenome()}</a></h4>
				</div>
				<div class='col-md-6'>
					<h4>$carro</h4>


				</div>
			</div>
			<div class='row'>
			<div class='col-md-12'>";

if($_SESSION['user'] != $dono){
	echo"
				<a href='#' id='pedir_carona' value='$id' class='btn btn-primary btn-raised'>Pedir para participar da Carona</a>
			";
}
else{
	echo"
				<button id='mensagem_carona' class='btn btn-raised btn-primary'>Enviar Mensagem aos usuários da carona</button>
			";
}
if($_SESSION['user']->getTipoUsuario() == "A"){
	echo "
				<button id='excluir_carona_admin' value='$id' class='btn btn-raised btn-primary'>Excluir (admin)</button>
			";
}


echo"</div></div>
		</div>
	</div>
	<div class='row'>";

if($dono == $_SESSION['user']){

	foreach ($caronas as $i => $c) {

		$id = $c->getIdCarona();
		$dia = $c->getDia();
		$w = weektostr(date('w', strtotime($dia)));
		$data = $w ." " .date('d/m', strtotime($dia));

		$lotada = $c->getLotada() ? "<h6>Lotada</h6>" : "";

		$temp= "Nenhum usuário na carona";
		$temp2 = '';

		$usuarios = $c->getUsuarios();
		if($usuarios){
			$temp = "Usuários: ";
			foreach ($usuarios as $i=>$u) {
				if($i == count($usuarios)-1){
					$temp.= "<a href='perfil.php?id={$u->getId()}'>{$u->getNome()} {$u->getSobrenome()}</a> ";
				}else{
					$temp.= "<a href='perfil.php?id={$u->getId()}'>{$u->getNome()} {$u->getSobrenome()}</a>, ";
				}

				$temp2 .= $u->getNome();
			}
		}

		$timestamp_carona = strtotime("$dia, $saida");
		$timestamp_agora = time();
		$botao = "";

		if(($timestamp_carona - $timestamp_agora) < (2*60*60)){
			$botao = "<a href='#' title='Avisar que está saindo' id='saindo' users='$temp2' dia='$dia' hora='$saida'><span class='glyphicon glyphicon-road'></span></a>";
		}

		$botao .= $lotada == "" ? "<a href='#' title='Marcar Carona como lotada' class='lotada' id_carona='$id'><span class='glyphicon glyphicon-ok-circle'></span></a>" : "<a href='#' title='Desmarcar Carona como lotada' class='nlotada' id_carona='$id'><span class='glyphicon glyphicon-ok-circle'></span></a>";

		echo"
		<div class='col-md-4'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3>$data $botao $lotada</h3>
				</div>
				<div class='panel-body'>
					$temp
				</div>
			</div>
		</div>";

		if($i+1 % 3 == 3){
			echo"</div><div class='row'>";
		}
	}

	echo"
		<div id='modal_mensagem_carona' class='modal fade' role='dialog'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<div class='panel panel-success'>

						<div class='panel-heading'>
							<h3 class='panel-title'>Enviar Mensagem</h3>
						</div>

						<div class='panel-body'>
							<div class='form-group label-floating'>
								<label class='control-label'>Digite sua mensagem:</label>
								<textarea class='form-control' id='mensagem'></textarea>

							</div>
							<button id='enviar_mensagem_carona' class='btn btn-success btn-raised'>Enviar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	";
}

echo"
</div>
<div class='row'>
	<div class='col-md-12'>
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<h3>Mapa</h3>
			</div>
			<div class='panel-body'>
				<div class='row'>
					<div class='col-md-6'>
						<div id='map' style='height:500px;'></div>
					</div>
					<div class='col-md-6'>
						<div id='painel'></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>";

if($dono != $_SESSION['user']){
	//MODAL
	echo"
		<div id='modalPedirCarona' class='modal fade' role='dialog'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<div class='panel panel-success'>

						<div class='panel-heading'>
							<h3 class='panel-title'>Pedir carona</h3>
						</div>

						<div class='panel-body'>

							Pedir esta carona para:
							<select name='pedido' id='pedido'>
								<option>Selecione</option>
								<option value='um'>Apenas para um dia</option>
								<option value='varios'>Uma certa frequência</option>
							</select>

							<form method='post' action='processa.php' id='form_pedir'>
								<input type='hidden' name='id_carona' id='id_carona' value='$id'>
								<input type='hidden' name='mensagem' id='input_mensagem' value=''>
								<input type='hidden' id='input_nome' value='{$dono->getNome()}'>
								<input type='hidden' id='input_dias' value='$strDias'>
								<input type='hidden' name='acao' value='pedir_carona'>
								<input type='hidden' id='origem' value='$origem'>
								<input type='hidden' id='destino' value='$destino'>
								<input type='hidden' id='saida' value='$saida'>
								<input type='hidden' id='chegada' value='$chegada'>

								<div id='campos'>
									<div id='check-dias' style='display:none'>
										$checkbox_dias
									</div>
								</div>

								<input type='submit' value='Avançar'>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>";
}
echo"
</div>
<script>
function iniciarMapa() {
  	var mapCanvas = document.getElementById('map');

	var directionsService = new google.maps.DirectionsService;
	var directionsDisplay = new google.maps.DirectionsRenderer;

	var origem = '$origem';
	console.log(origem);
	var destino = '$destino';
	var wp = [];

	var temp = '$percurso';

	var coordenadas = temp.split('|');
	coordenadas.pop();

	for(var x=0; x<coordenadas.length; x++){
		var temp = coordenadas[x].substring(1, coordenadas[x].length-1).split(',');
		wp.push ({location : {lat: parseFloat(temp[0]), lng: parseFloat(temp[1])}, stopover:true});
	}

	var mapOptions = {
		center: {lat: -31, lng: -50},
		zoom:11,
		mapTypeId:google.maps.MapTypeId.HYBRID
	};

	var map = new google.maps.Map(mapCanvas, mapOptions);

	directionsDisplay.setMap(map);
	directionsDisplay.setPanel(document.getElementById('painel'));

	var request = {
		origin: origem,
		destination: destino,
		waypoints: wp,
		optimizeWaypoints: true,
		travelMode: google.maps.DirectionsTravelMode.DRIVING
	};

	directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
		}else{
			alert('erro');
		}
	});
}
</script>
";
?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2GX1R6eI0Ut1R4tQzGaTyb8RqVheTVQI&callback=iniciarMapa" async></script>

<?php
include_once 'rodape.php';
?>
