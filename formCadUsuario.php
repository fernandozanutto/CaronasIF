<?php include_once "cabecalho_2.php";

if(isset($_SESSION['erros'])){
	$erro = $_SESSION['erros'];
    if($erro == '1'){
        alert("Todos os campos com asterisco devem ser preenchidos");
    }else if($erro == '2'){
		alert('Ocorreu um erro ao cadastrar seu usuário, verifique se o login e email estão disponíveis');
	}
	unset($_SESSION['erros']);
}
if(isset($_SESSION['post'])){

	$nome = $_SESSION['post']['nome'];
	$sobrenome = $_SESSION['post']['sobrenome'];
	$cidade = $_SESSION['post']['cidade'];
	$login = $_SESSION['post']['login'];
	$logradouro = $_SESSION['post']['logradouro'];
	$email = $_SESSION['post']['email'];
	$numEnd = $_SESSION['post']['num_endereco'];
	$numTel = $_SESSION['post']['telefone'];
	unset($_SESSION['post']);
}
else{
	$nome = $sobrenome = $logradouro = $numEnd = $numTel = $cidade = $email = $login = '';
}
?>
	<div class='col-md-12'>
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<h3>Cadastro de Usuário </h3>
			</div>

			<div class='panel-body'>
				<div class='col-xs-12 col-md-offset-1 col-md-10 form-login'>

				    <form id='cadastro' role='form' action='processa.php' method='post'>
				        <div class='row'>
							<span class="help-block">Todos os campos com '<span class='red'>*</span>' devem ser preenchidos.</span>
							<span class="help-block">A senha precisa ter no mínimo 6 caracteres.</span>
							<span class="help-block">Não há diferença de letras maiúsculas ou minúsculas no login e senha</span>
							<span class="help-block">Outros campos precisam ter no mínimo 2 caracteres.</span>

			                <input type='hidden' name='acao' value='cadastrarUsuario'>

			                <div class='row'>
			                    <div class='col-md-4'>
									<div class="form-group label-floating is-empty">
										<label class="control-label">Nome <span class='red'>*</span></label>
										<input class="form-control" type="text" name="nome" value='<?php echo $nome; ?>'>
									</div>
			                    </div>

			                    <div class='col-md-4'>
			                        <div class="form-group label-floating is-empty">
										<label class="control-label">Sobrenome <span class='red'>*</span></label>
										<input class="form-control" type="text" name="sobrenome" value='<?php echo $sobrenome; ?>'>
									</div>
			                    </div>

								<div class='col-md-4'>
			                    	<div class="form-group label-floating">
						                <label class='control-label'>Vínculo</label>
						                <select class="form-control select" name='vinculo'>
											<option value=''>Selecione</option>
						                	<option value="Aluno">Aluno</option>
						                  	<option value="Servidor">Servidor</option>
						                </select>
						            </div>
			                    </div>

			                </div>

			                <div class='row'>

			                    <div class='col-md-4'>
									<div class="form-group label-floating is-empty">
										<label class="control-label">E-mail <span class='red'>*</span></label>
										<input class="form-control" type="email" name="email" value='<?php echo $email; ?>'><span id='statusEmail'></span>
									</div>
			                     </div>

								 <div class='col-md-4'>
 			                        <div class="form-group label-floating is-empty">
 									<label class="control-label">Login <span class='red'>*</span></label>
 										<input class="form-control" type="text" name="login" value='<?php echo $login; ?>'><span id='statusLogin'></span>
 			                        </div>
 			                    </div>

								<div class='col-md-4'>
			                         <div class="form-group label-floating is-empty">
									<label class="control-label">Senha <span class='red'>*</span></label>
										<input class="form-control" type="password" name="senha">
									</div>
			                    </div>

			                </div>


			                <div class='row'>

								<div class='col-md-4'>
			                        <div class="form-group label-floating is-empty">
									<label class="control-label">Cidade <span class='red'>*</span></label>
										<input class="form-control" type="text" name="cidade" value='<?php echo $cidade; ?>'>
									</div>
			                    </div>

			                    <div class='col-md-4'>
			                        <div class="form-group label-floating is-empty">
									<label class="control-label">Endereço <span class='red'>*</span></label>
										<input class="form-control" type="text" name="logradouro" value='<?php echo $logradouro; ?>'>
									</div>
			                    </div>

			                    <div class='col-md-4'>
			                        <div class="form-group label-floating is-empty">
									<label class="control-label">Número de Endereço <span class='red'>*</span></label>
										<input class="form-control" type="text" name="num_endereco" value='<?php echo $numEnd; ?>'>
									</div>
			                    </div>

			                </div>

			                <div class='row'>

			                    <div class='col-md-4'>
			                        <div class="form-group label-floating is-empty">
									<label class="control-label">Telefone</label>
										<input class="form-control valido" type="tel" name="telefone" id='telefone' value='<?php echo $numTel; ?>'>
									</div>
			                    </div>

								<div class='col-md-4 col-md-offset-4'>
									<a href='login.php'class='btn btn-default btn-raised'>Voltar</a>
									<button type='submit' class="btn btn-raised btn-primary valido" id='botaoCad' disabled>Cadastrar<div class="ripple-container"></div></button>
								</div>
			                </div>

			                <input type='hidden' name='tipo_usuario' value='U' />

			            </div>
			        </div>
	    		</form>
			</div>
		</div>
	</div>

<?php include_once "rodape.php"; ?>
