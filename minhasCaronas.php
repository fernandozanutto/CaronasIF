<?php
include_once "cabecalho.php";
include_once 'menu_lateral.php';
$caronas = $controle->getCaronasUsuario($_SESSION['user']);

?>

<div class='col-xs-12 col-md-6'>
	<div class='panel panel-default'>
        <div class='panel-heading'>
			<h3>Caronas que ofereço</h3>
		</div>

		<div class="table-responsive">
	    	<table class="table table-striped table-hover" id='minhas_caronas'>
	    		<thead>
	    			<tr>
			            <th>Origem</th>
			            <th>Destino</th>
			            <th>Dias</th>
			            <th>Saída</th>
			            <th>Chegada</th>
						<th>Excluir</th>
	    			</tr>
	    		</thead>
	    		<tbody>

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
	$ativo = $c->getAtivo() ? true : false;

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

	if($ativo){
		echo"<td><button type='button' id='excluir_carona' value='$id' class='btn btn-primary btn-raised btn-sm'>Excluir</button></td>";
	}else{
		echo"<td><button type='button' class='btn btn-warning btn-raised btn-sm'>Carona Inativa</button></td>";
	}
	echo "</tr>";
}

echo "			</tbody>
			</table>
		</div>
	</div>
</div>
<div class='col-xs-12 col-md-3'>
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<h3>Ordenar Caronas</h3>
		</div>
		<div class='panel-body'>
			<form role='form' method='post'>
				<div class='row'>
					<div class='radio'>
						<label>
							<input type='radio' name='order' value='id' checked><span class='circle'></span><span class='check'></span>
							Ordenar por data de criação
						</label>
					</div>

					<div class='radio'>
						<label>
							<input type='radio' name='order' value='status' value='status'><span class='circle'></span><span class='check'></span>
							Ordernar por status
						</label>
					</div>
				</div>
				<div class='col-md-12'>
					<button id='filtrar_minhas_caronas' type='button' class='btn btn-raised btn-primary btn-block'>Buscar<div class='ripple-container'></div></button>
				</div>
			</div>
		</div>
	</div>
</div>
";




 ?>




 <?php include_once "rodape.php"; ?>
