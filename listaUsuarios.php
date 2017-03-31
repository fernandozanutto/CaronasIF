<?php
include_once "cabecalho.php";
include_once 'menu_lateral.php';
if($_SESSION['user']->getTipoUsuario() != "A"){
    alert("location:'index.php'");
}else{

    $usuarios = $controle->listar_usuarios();

echo "

    <div class='col-xs-12 col-md-9'>
		<div class='panel panel-default'>

	        <div class='panel-heading'>
				<h3>Usuários</h3>
			</div>

			<div class='panel-body'>
			    <table class='table table-striped table-hover'>
			        <thead>
			            <tr>
			                <th>Nome</th>
			                <th>Email</th>
			                <th>Vinculo</th>
			                <th>Tipo Usuario</th>
			                <th>Cidade</th>
			                <th>Logradouro</th>
			                <th>Numero</th>
			                <th>Telefone</th>
			                <th>Login</th>
			                <th>Senha</th>
							<th>Cadastro</th>
							<th>Ativo</th>
			             </tr>
			        </thead>
			        <tbody>";


    foreach ($usuarios as $i => $us) {
		//$nome, $sobrenome, $login, $senha, $vinculo, $tipo_usuario, $email, $cidade, $logradouro, $num_endereco, $telefone=null
		$success = '';
		if($i % 2 == 0){
			$success = 'success';
		}
		$ativo = $us->getAtivo() ? "Ativo" : "Inativo";
		$data = date("d/m/y H:m", strtotime($us->getDataCadastro()));
        echo"
            <tr class='$success'>
                <td><a href='perfil.php?id={$us->getId()}'>{$us->getNome()} {$us->getSobrenome()}</a></td>
                <td>{$us->getEmail()}</td>
                <td>{$us->getVinculo()}</td>
                <td>{$us->getTipoUsuario()}</td>
                <td>{$us->getCidade()}</td>
                <td>{$us->getLogradouro()}</td>
                <td>{$us->getNumEndereco()}</td>
                <td>{$us->getTelefone()}</td>
                <td>{$us->getLogin()}</td>
                <td>{$us->getSenha()}</td>
				<td>$data</td>
				<td>$ativo</td>
            </tr>
        ";
    }

    echo "
					</tbody>
    			</table>
			</div>
    	</div>
	</div>
";


}

include_once 'rodape.php';
 ?>
