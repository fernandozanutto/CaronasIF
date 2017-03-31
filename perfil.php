<?php
include_once "cabecalho.php";
include_once "menu_lateral.php";

$user = $controle->getUsuario($_GET['id']);

$denuncias="";

if($user){
	$id = $user->getId();
    $nome = $user->getNome();
    $sobrenome = $user->getSobrenome();
    $email = $user->getEmail();
    $vinculo = $user->getVinculo();
    $cidade = $user->getCidade();
    $logradouro = $user->getLogradouro();
    $num_endereco = $user->getNumEndereco();
    $telefone = $user->getTelefone();
	$tempo = date('d/m/Y', strtotime($user->getDataCadastro()));

    switch ($vinculo) {
        case 'A':
            $vinculo = "Aluno";
            break;
        case 'S':
            $vinculo = "Servidor";;
            break;
        default:
            $vinculo = "Inexistente";
            break;
    }

	//VENDO PERFIL DE OUTRO USUÁRIO
    if($user->getId() != $_SESSION['user']->getId()){

        $denuncias = $controle->getDenuncias($user);

		if($denuncias){
	        $temp = "<p>Denuncias contra o usuário: ".count($denuncias)."</p>";

			foreach ($denuncias as $denuncia) {
				$temp.="<button type='button' class='btn btn-default btn-warning' data-trigger='focus' data-container='body' data-toggle='popover' data-placement='top' data-content='{$denuncia->getDetalhes()}'>{$denuncia->getMotivo()}</button>";
			}

		}else{
			$temp="<p>Nenhuma denúncia contra o usuário</p>";
		}

    }

	//VENDO SEU PRÓPRIO PERFIL AQUI
	else{
		$denuncias = $controle->getDenuncias($user);

		if($denuncias){
			$qnt_denuncias = count($denuncias);
			$temp="<p>Denuncias contra você: $qnt_denuncias</p>";

		}else{
			$temp="<p>Nenhuma denúncia contra você</p>";
		}
	}

	echo"
        <div class='col-xs-12 col-md-6'>
			<div class='panel panel-default'>
                <div class='panel-heading'><h3>Informações do usuário</h3></div>

                <div class='panel-body'>
                    <h4>$nome $sobrenome</h4>
                    <p>Email: $email</p>
                    <p>Vínculo: $vinculo</p>
                    <p>Endereço: $cidade - $logradouro, $num_endereco</p>
                    <p>Telefone: $telefone</p>
					<p>Usuário desde: $tempo </p>
                    $temp
                </div>

			</div>
        </div>

		<div class='col-xs-12 col-md-3'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3>Opções</h3>
				</div>
				<div class='panel-body'>
            ";

    if($user->getId() == $_SESSION['user']->getId()){
        echo"
	            	<a href='formEditPerfil.php'><button class='btn btn-default btn-raised btn-primary btn-block'>Editar Perfil</button></a>
	                <a href='processa.php?acao=desatConta' onclick=\"return confirm('Tem certeza que deseja desativar sua conta?')\"><button class='btn btn-default btn-raised btn-primary btn-block'>DESATIVAR PERFIL</button></a>
				</div>
	        </div>
		</div>";

	}else{
		echo"
                    <a href='formCadDenuncia.php?id={$_GET['id']}'><button class='btn btn-primary btn-raised btn-block'>Denunciar Usuário</button></a>
		";
		if($_SESSION['user']->getTipoUsuario() == "A" && $user->getTipoUsuario() != "A"){
			echo"<a href='processa.php?acao=banir&id=$id' onclick=\"return confirm('Confirmar?')\" class='btn btn-primary btn-raised btn-block'>Desativar Conta</a>";
		}
		echo"		</div>
            </div>
		</div>";
	}

}else{
    echo"
        <div class='row'>
            <div class='col-md-12'>
                <h4>Não foi encontrado nenhum usuário com este ID</h4>
            </div>
        </div>
    ";
}

include_once "rodape.php";
?>
