<?php
include_once 'cabecalho.php';
include_once 'menu_lateral.php';
?>

<div class='col-xs-12 col-md-9'>

	<form method='post' action='processa.php'>
		<input type='hidden' name='acao' value='enviar_mensagem'>
		<input type='hidden' name='id_destinatario' id='id_destinatario'>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3>Enviar Mensagem</h3>
			</div>
			<div class="panel-body">
				<div class='row'>
					<div class='col-md-6'>
						<div class="form-group label-floating is-empty">
						<label class="control-label">Buscar nomes:</label>
							<input id='dest' class='form-control' type='text' list='lista_nomes' autocomplete="off">
							<datalist id='lista_nomes'><select name="lista_nomes"></select></datalist>
						</div>
					</div>
					<div class='col-md-2'>
						<button type='button' id='add' href="javascript:void(0)" class='btn btn-raised btn-sm btn-primary btn-block' class='ripple-container'>Adicionar</button>
					</div>
					<div class='col-md-4'>
					<div class='form-group'>
							<label>Destinatários: </label>

							<div id='ddd'>
							</div>
						</div>
					</div>
				</div>

				<div class='row'>
					<div class='col-md-6'>
						<div class="form-group label-floating is-empty">
						<label class="control-label">Assunto:</label>
							<textarea class="form-control" rows="2" name='assunto' required></textarea>
						</div>
					</div>

					<div class='col-md-6'>
						<div class="form-group label-floating is-empty">
						<label class="control-label" >Mensagem:</label>
							<textarea class="form-control" rows="2" name='mensagem'></textarea>
						</div>
					</div>


					<div class='input-group'>
						<span class="input-group-btn">
							<button class="btn btn-fab btn-fab-mini btn-primary">
								<i class="material-icons">send</i>
								<div class="ripple-container"></div>
							</button>
						</span>
					</div>

				</div>
			</div>
		</div>

	</form>
</div>


<?php
include_once 'rodape.php';
?>
