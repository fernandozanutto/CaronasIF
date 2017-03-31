<?php
include_once "cabecalho_2.php";
session_start();
session_destroy();
?>

	<div class='col-xs-12 col-md-offset-1 col-md-10'>
		<div class='panel panel-default'>
	        <div class='panel-heading'><h2 id='bemvindo'>Bem Vindo ao Caronas IF!</h2></div>
			<div class="panel-body">
				<div class='row'>
					<div class='hidden-xs col-md-6'>
						<h4>Agora ficou muito mais fácil conseguir uma carona!<br><br>
							Você poderá oferecer e pegar caronas!<br>
							Nosso sistema estará sempre cuidando se as caronas estão acontecendo da maneira correta, disponibilizando ao usuário maior confiança!<br>
						</h4>
					</div>

					<div class='col-xs-12 col-md-offset-1 col-md-5 '>
						<form role='form' action='processa.php' method='post'>
							<input type='hidden' name='acao' value='logar'>

							<div class="form-group label-floating is-empty">
								<label class="control-label" for='login'>Login</label>
								<input class="form-control" type="text" id='login' name="login">
							</div>

							<div class="form-group label-floating is-empty">
								<label class="control-label" for="senha">Senha</label>
								<input class="form-control" type="password" id='senha' name="senha">
							</div>

							<div class='row'>
								<div class='col-xs-6 col-md-6'>
									<a href='formCadUsuario.php' class="btn btn-raised btn-primary btn-block">Nova conta<div class="ripple-container"></div></a>
									<a href='formRecSenha.php'>Esqueceu sua senha?</a>
								</div>
								<div class='col-xs-6 col-md-6'>
									<button type="submit" class="btn btn-raised btn-primary btn-block">Entrar<div class="ripple-container"></div></button>
								</div>

							</div>

						</form>
					</div>
				</div>
            </div>
        </div>
    </div>

<?php include_once "rodape.php"; ?>
