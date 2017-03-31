<?php
include_once "cabecalho.php";
include 'menu_lateral.php';
?>


<div class='col-xs-12 col-md-6'>
	<div class='panel panel-default' >
        <div class='panel-heading'>
			<h3>Listagem de caronas</h3>
		</div>

    	<div class="panel-body">
			<div class='table-responsive'>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                          <th>Origem</th>
                          <th>Destino</th>
                          <th>Dias</th>
                          <th>Partida</th>
                          <th>Chegada</th>
                        </tr>
                    </thead>
                    <tbody id='corpo_caronas'>

<?php

    $caronas = $controle->listarCaronas();

	if($caronas){

	    foreach ($caronas as $c) {
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
				$temp.= " <label class='col-xs-1 col-md-1 dias label $label'>$dia</label> ";

			}


	        echo "<tr class='link-row' data-href='carona.php?id=$id'>";
			echo "<td>$origem</td>";
			echo "<td>$destino</td>";
			echo "<td>$temp</td>";
			echo "<td>$saida</td>";
			echo "<td>$chegada</td>";
	        echo "</tr>";
	    }
	}
 ?>

        			</tbody>
      			</table>
			</div>
		</div>
	</div>
</div>


<div class='col-xs-12 col-md-3'>
    <div class='panel panel-default'>
		<div class='panel-heading'>
    		<h3>Filtro de Caronas</h3>
		</div>

		<div class='panel-body'>
	        <form role='form' method='post'>
	            <div class='form-group'>
					<h4>Dias da semana:</h4>
	                <div class='row'>
	                    <div class='col-md-6'>
							<div class="checkbox">
								<label>
									<input name='dias[]' value='seg' class='dias' type="checkbox"> Segunda
								</label>
								<label>
									<input name='dias[]' value='ter' class='dias'  type="checkbox"> Terça
								</label>

								<label>
									<input name='dias[]' value='qua' class='dias' type="checkbox"> Quarta
								</label>

								<label>
									<input name='dias[]' value='qui' class='dias' type="checkbox"> Quinta
								</label>
							</div>
						</div>

						<div class='col-md-6'>
							<div class="checkbox">
								<label>
									<input name='dias[]' value='sex' class='dias'  type="checkbox"> Sexta
								</label>
								<label>
									<input name='dias[]' value='sab' class='dias'  type="checkbox"> Sábado
								</label>
								<label>
									<input name='dias[]' value='dom'  class='dias' type="checkbox"> Domingo
								</label>
	                    	</div>
	                	</div>
	            	</div>
				</div>


	            <div class='form-group'>
	                <h4>Turnos da carona:</h4>
					<div class="checkbox">

						<label>
							<input name='turno[]' type="checkbox" class='turno' value='manha'> Manhã
						</label>

						<label>
							<input name='turno[]' type="checkbox" class='turno' value='tarde'> Tarde
						</label>

						<label>
							<input name='turno[]' type="checkbox" class='turno' value='noite'> Noite
						</label>

					</div>
					<button type='button' class="btn btn-raised btn-primary" id='filtrar_caronas'>Buscar<div class="ripple-container"></div></button>
				</div>
			</form>

		</div>
	</div>
</div>

<?php include_once "rodape.php"; ?>
