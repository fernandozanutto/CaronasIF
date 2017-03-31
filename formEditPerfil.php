<?php
include_once "cabecalho.php";
include_once 'menu_lateral.php';
$u = $_SESSION['user'];

$email = $u->getEmail();
$cidade = $u->getCidade();
$logradouro = $u->getLogradouro();
$num_endereco = $u->getNumEndereco();
$senha = $u->getSenha();
$telefone = $u->getTelefone();
$id = $u->getId();


echo"
	<div class='col-xs-12 col-md-9'>
		<div class='panel panel-default'>
			<div class='panel-heading'><h3>Informações do usuário</h3></div>

			<div class='panel-body'>

		        <form role='form' action='processa.php' method='post'>

	                <input type='hidden' name='acao' value='editar_usuario'>

					<div class='row'>

	                    <div class='col-md-6'>
	                        <div class='form-group'>
	                            <label for='email'>Email:</label>
	                            <input class='form-control' type='email' name='email' value='$email' required>
	                        </div>
	                    </div>

	                    <div class='col-md-6'>
	                        <div class='form-group'>
	                            <label>Senha:</label>
	                            <input class='form-control' type='password' name='senha' value='$senha' required>
	                        </div>
	                    </div>
					</div>


	                <div class='row'>

	                    <div class='col-md-6'>
	                        <div class='form-group'>
	                            <label>Cidade: </label>
	                            <input class='form-control' type='text' name='cidade' value='$cidade' required>
	                        </div>
	                    </div>

	                    <div class='col-md-6'>
	                        <div class='form-group'>
	                            <label>Endereço: </label>
	                            <input class='form-control' type='text' name='logradouro' value='$logradouro' required>
	                        </div>
	                    </div>

	                </div>

	                <div class='row'>
                        <div class='col-md-6'>
                            <div class='form-group'>
                                <label>Número de Endereço:</label>
                                <input class='form-control' type='text' name='num_endereco' value='$num_endereco' required>
                            </div>
                        </div>

                        <div class='col-md-6'>
                            <div class='form-group'>
                                <label>Telefones:</label>
                                <input class='form-control' id='telefone' type='tel' name='telefone' value='$telefone'>
                            </div>
                        </div>

                    </div>

                    <div class='row'>

                        <div class='col-xs-12 col-md-6'>
                            <button class='btn btn-default btn-raised btn-primary btn-block'>Salvar</button>
                        </div>

                        <div class='col-xs-12 col-md-6'>
                            <a href='perfil.php?id=$id' class='btn btn-default btn-raised btn-primary btn-block'>Cancelar</a>
                        </div>

                    </div>
            	</div>
            </div>
        </form>
	</div>
</div>
";

include_once "rodape.php";
?>
