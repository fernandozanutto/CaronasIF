<?php
include_once "cabecalho.php";
include_once "menu_lateral.php";


if(isset($_SESSION['post'])){
	$modelo = $_SESSION['post']['modelo'];
	$cor = $_SESSION['post']['cor'];
	$placa = $_SESSION['post']['placa'];
	$marca = $_SESSION['post']['marca'];
	$lugares = $_SESSION['post']['lugares'];
	unset($_SESSION['post']);
}
else{
	$modelo = $cor = $placa = $marca = $lugares = '';
}





?>

<div class='col-xs-12 col-md-9'>
	<form role='form' action='processa.php' method='post'>

        <input type='hidden' name='acao' value='cad_carro'>
		<div class="panel panel-default">
			<div class="panel-heading"><h3>Cadastro de Carros</h3></div>
				<div class="panel-body">
					<div class='row'>

						<div class='col-md-6'>
							<div class="form-group label-floating is-empty">
								<label class="control-label">Marca</label>
								<input class="form-control" type="text" name="marca" value = '<?php echo $marca; ?>'>
							</div>
						</div>

						<div class='col-md-6'>
							<div class="form-group label-floating is-empty">
								<label class="control-label">Cor</label>
								<input class="form-control" type="text" name="cor" value = '<?php echo $cor; ?>'>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-6'>
						<div class="form-group label-floating">
							<label class="control-label">Possui Ar Condicionado:</label>
							<select name='ar' class='form-control'>
								<option>Selecione</option>
								<option value='1'>Sim</option>
								<option value='0'>Não</option>
							</select>
						</div>
						</div>
						<div class='col-md-6'>
						<div class="form-group label-floating is-empty">
							<label class="control-label">Modelo</label>
							<input class="form-control" type="text" name="modelo" value = '<?php echo $modelo; ?>'>
						</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-6'>
							<div class="form-group label-floating is-empty">
								<label class="control-label">Placa</label>
								<input class="form-control" type="text" name="placa" id='placa' value = '<?php echo $placa; ?>'>
							</div>
						</div>
						<div class='col-md-6'>
							<div class="form-group label-floating is-empty">
								<label class="control-label">Número de lugares</label>
								<input class="form-control" type="text" name="lugares" value = '<?php echo $lugares; ?>'>
							</div>
						</div>

					</div>
					<input type="submit" class="btn btn-default btn-raised" value='Cadastrar'>
				</div>

			</div>
		</div>
	</form>
</div>


<?php include_once "rodape.php"; ?>
