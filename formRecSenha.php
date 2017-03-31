<?php
	include_once "cabecalho_2.php";
?>



<div class='col-xs-12 col-md-offset-1 col-md-10'>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Recuperar Senha</h2>
		</div>

		<div class='panel-body'>
			<div class='row'>
			    <div class='col-md-6 '>
					<h4>Insira seu email no campo do formulário.<br> Seu login e senha serão enviados para o seu email.</h4>
			    </div>

				<div class='col-xs-12 col-md-offset-1 col-md-5 '>
					<form role='form' action='processa.php' method='post'>
				        <input type='hidden' name='acao' value='recuperar_senha'>
						<div class="form-group label-floating is-empty">
							<label class="control-label" for="email">E-mail</label>
							<input class="form-control" id="email" type="email" name="email">
						</div>

				        <div class='row'>
							<div class='col-xs-6 col-md-6'>
								<a href='login.php' class="btn btn-raised btn-default btn-block">Voltar</a>
							</div>
				            <div class='col-xs-6 col-md-6'>
				                <button type="submit" class="btn btn-raised btn-primary btn-block">Confirmar</button>
				            </div>
				        </div>

				    </form>
				</div>
			</div>
		</div>
	</div>

</div>

<?php include_once "rodape.php"; ?>
