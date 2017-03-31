<?php
include_once "cabecalho.php";
include_once 'menu_lateral.php';
$caronas = $controle->getCaronasUsuarioUtiliza($_SESSION['user']);

?>
<div class='col-xs-12 col-md-9'>
	<div class='panel panel-default'>
        <div class='panel-heading'>
			<h3>Caronas que participo</h3>
		</div>

		<div class="table-responsive">
	    	<table class="table table-striped table-hover">
	    		<thead>
	    			<tr>
			            <th>Origem</th>
			            <th>Destino</th>
			            <th>Dia</th>
			            <th>Saída</th>
			            <th>Chegada</th>
	    			</tr>
	    		</thead>
	    		<tbody>
				</tbody>


<?php


foreach ($caronas as $i => $c) {

	$success = $i%2==0 ? 'success' : '';

	$temp='';
	$label = '';
	$id = $c->getIdCarona();
	$origem = $c->getOrigem();
	$destino = $c->getDestino();
	$chegada = $c->getHorarioChegada();
	$saida = $c->getHorarioSaida();
	$dias = explode('-', $c->getDias());

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

		$temp.= " <span class='col-md-1 dias label $label'>$dia</span> ";
	}


	echo "<tr data-href='carona.php?id=$id' ativo='$ativo' id_carona='$id'>";
	echo "<td class='link-row'>$origem</td>";
	echo "<td class='link-row'>$destino</td>";
	echo "<td class='link-row'>$temp</td>";
	echo "<td class='link-row'>$saida</td>";
	echo "<td class='link-row'>$chegada</td>";

	echo "</tr>";
}
echo"
</table></div></div></div>";

include_once "rodape.php"; ?>
