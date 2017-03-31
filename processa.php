<?php

include_once 'Usuario.class.php';
session_start();
include_once 'funcoes.php';
include_once "ControleMain.class.php";

include_once 'Carro.class.php';
include_once "Denuncia.class.php";
include_once "Mensagem.class.php";
include_once 'Carona.class.php';

$controleMain = new ControleMain();

if(isset($_SESSION['user']) && $_SESSION['user']->getTipoUsuario() == "A"){
    require_once "ControleAdmin.class.php";
    $controle = new ControleAdmin();
}else{
    require_once "ControleUsuario.class.php";
    $controle = new ControleUsuario();
}

$acao = $_REQUEST['acao'];
if(isset($_POST)){
    $_SESSION['post']= $_POST;
}
if($acao=='cadastrarUsuario'){

    $cadastrar = true;

    if(!verificarPOST()){
		$cadastrar = false;
		$_SESSION['erros'] = '1';
		$_SESSION['post'] = $_POST;
		header('location: formCadUsuario.php');
	}

    if($cadastrar){
        $u = new Usuario($_POST['nome'], $_POST['sobrenome'], $_POST['login'], $_POST['senha'], $_POST['vinculo'], $_POST['tipo_usuario'], $_POST['email'], $_POST['cidade'], $_POST['logradouro'], $_POST['num_endereco'], $_POST['telefone']);
        $resultado = $controleMain->cadastrarUsuario($u);

        if($resultado){
            $controleMain->logar($u->getLogin(), $u->getSenha());
        }
        else{
			$_SESSION['erros'] = "2";
			header ('location: formCadUsuario.php');
        }
    }
}

else if($acao=='logar'){
	if(verificarPOST())
    	$controleMain->logar($_POST['login'], $_POST['senha']);
	else {
		alert('Preecha todos os campos', "location='login.php'");
	}
}

else if($acao == 'logout'){
    $controle->logout();
}

else if($acao == 'editar_usuario'){
    $controle->editarUsuario($_POST['cidade'], $_POST['logradouro'], $_POST['email'], $_POST['telefone'], $_POST['senha'], $_POST['num_endereco']);

    $_SESSION['user'] = $controle->getUsuario($_SESSION['user']->getId());

    header("location: perfil.php?id=".$_SESSION['user']->getId());
}

else if($acao == 'desatConta'){

	$resultado = $controle->desativarUsuario($_SESSION['user']);

	if($resultado){
		$controle->logout();
	}else{
		alert('nao deu');
	}
}

else if($acao == 'recuperar_senha'){
	$controleMain->recuperarSenha($_POST['email']);
}

else if ($acao == "denunciar"){

	if(!verificarPOST()){
		$_SESSION['post']= $_POST;
		alert('É necessário que todos os campos sejam preenchidos', "location='formCadDenuncia.php'");

	}
    $d = new Denuncia ($_POST['assunto'], $_POST['texto'], $controle->getUsuario($_POST['id_denunciado']));
    $resultado = $controle->denunciarUsuario($d);


    if($resultado){
        alert("Denúncia realizada com sucesso!", "location='index.php'");
    }else{
        header("location: denunciar_usuario.php?id={$_POST['id_denunciado']}");
    }
}
else if ($acao == "analisar_denuncia"){

    $id= $_GET['id'];
    $status = $_GET['a'];

    $resultado = $controle->analisarDenuncia($id, $status);

    if($resultado){
        alert('Análise registrada', 'location=\'listaDenuncias.php\'');

    }else{
        alert('Ocorreu um erro', 'location=\'listaDenuncias.php\'');
    }

}
else if ($acao == "enviar_mensagem"){
	if(!verificarPOST()){
		$_SESSION['post']= $_POST;
		alert('É necessário que todos os campos sejam preenchidos', "location='formNovMensagem.php'");
	}

	$destinatarios = explode(',', $_POST['id_destinatario']);
	array_pop($destinatarios);
	foreach ($destinatarios as $d) {
		$dest[] = $controle->getUsuario($d);
	}
    $remetente = $_SESSION['user'];
    $assunto = $_POST['assunto'];
    $mensagem = $_POST['mensagem'];

    $m = new Mensagem($remetente, $assunto, $mensagem, $dest);

    $resultado = $controle->enviarMensagem($m);

	if($resultado){
		alert("Mensagem enviada", "location='index.php'");
	}else{
		alert('Ocorreu um erro, tente novamente', "location='index.php'");
	}
}
else if($acao == "cad_carro"){

    if(!verificarPOST()){
		$_SESSION['post']= $_POST;
		alert('Tente novamente!', "location='formCadCarro.php'");
	}

    $dono = $_SESSION['user'];
    $marca = $_POST['marca'];
    $cor = $_POST['cor'];
    $modelo = $_POST['modelo'];
    $ar = $_POST['ar'];
    $lugares = $_POST['lugares'];
    $placa = $_POST['placa'];

    $carro = new Carro($dono, $marca, $modelo, $lugares, $ar, $placa, $cor);
    $resultado = $controle->cadastrarCarro($carro);

    if($resultado){
        alert("Seu carro foi cadastrado com sucesso!", "location='index.php'");

    }else{
        alert('Tente novamente!', "location='formCadCarro.php'");
    }
}
else if($acao == "cad_carona"){

	if(!verificarPOST()){
		$_SESSION['post'] = $_POST;
		alert('Insira todos os campos', "location='formCadCarona.php'");
	}

	$origem = $_POST['origem'];
	$destino = $_POST['destino'];
	$horarioSaida = $_POST['saida'];
	$horarioChegada = $_POST['chegada'];
	$dono = $_SESSION['user'];
	$dias = $_POST['dias'];
	$carro = $controle->getCarro($_POST['carro']);
	$percurso = $_POST['percurso'];

	$carona = new Carona($dono, $origem, $destino, $carro, $horarioSaida, $horarioChegada, $dias);
	$carona->setPercurso($percurso);

	if(isset($_POST['mostrar_carro'])){
		$carona->setMostrarCarro(true);
	}

	$resultado = $controle->cadastrarCarona($carona);

	if($resultado){
		alert('Carona cadastrada com sucesso!', "location='index.php'");
	}else{
		alert('Aconteceu um erro, tente novamente!', "location='formCadCarona.php'");
	}

}
else if($acao == 'pedir_carona'){

	$tipo = $_POST['tipo'];
	$usuario = $_SESSION['user'];
	$carona = $controle->getCarona($_POST['id_carona']);

	if($tipo == 'um'){
		$dia = explode('/', $_POST['dia']);
		$dia = "'".$dia[2]."-".$dia[1]."-".$dia[0]."'";
	}

	else if($tipo == 'varios'){
		$dias = $_POST['dia'];

		foreach ($dias as $d) {
			$temp[] = strtoweekday($d);
		}

		$dia = implode(', ', $temp);
	}

	$resultado = $controle->pedirCarona($_SESSION['user'], $carona, $_POST['mensagem'], $dia, $tipo);

	if($resultado){
		alert('Pedido Enviado com sucesso!');
	}else{
		alert('Ocorreu um erro, tente novamente!');
	}
}

else if($acao == 'del_carro'){

	$carro = $controle->getCarro($_GET['id']);

	$resultado = $controle->deletarCarro($carro);

	if($resultado){
		alert('Carro excluído com sucesso!', "location='lista_carros.php'");
	}else{
		alert('Ocorreu um erro, tente novamente!', "location='lista_carros.php'");
	}
}
else if($acao == 'banir'){
	$usuario = $controle->getUsuario($_GET['id']);
	$resultado = $controle->desativarUsuario($usuario);

	if($resultado == 3){
		alert('Usuário desativado e email enviado', "location='index.php'");
	}else if($resultado == 2){
		alert('Usuário desativado, mas email não foi enviado');
	}else if($resultado == 0){
		alert('Ocorreu um erro. Tente novamente');
	}
}
else if($acao == 'filtrarCaronas'){

	$dia = $_POST['dias'];
	$turno = $_POST['turno'];

	$filtro = "";
	$temp = '';
	if(isset($dia)){
        foreach ($dia as $i => $d) {
			$filtro.= $i == 0 ? "and (dias like '%$d%' " : " or dias like '%$d%'";
        }
		$filtro.=")";

	}else{
		$filtro .= "'%%'";
	}

	if(isset($turno)){

		foreach ($turno as $t) {
			if ($t == 'manha'){
				$turno_i = "06:00";
				break;
			}
			else if($t == 'tarde'){
				$turno_i = "12:00";
				break;
			}
			else if($t == 'noite'){
				$turno_i = "18:00";
			}
		}

		foreach ($turno as $t) {
			if ($t == 'manha'){
				$turno_f = "11:59";
				break;
			}
			else if($t == 'tarde'){
				$turno_f = "17:59";
				break;
			}
			else if($t == 'noite'){
				$turno_f = "05:59";
			}
		}

		$filtro.="and cast(horario_saida as time) between '$turno_i' and '$turno_f'";
	}

	$caronas = $controle->listarCaronas($filtro);

	if($caronas){

		$tabela = "";
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

		    $tabela.= "<tr class='link-row' data-href='carona.php?id=$id'>";
			$tabela.= "<td>$origem</td>";
			$tabela.= "<td>$destino</td>";
			$tabela.= "<td>$temp</td>";
			$tabela.= "<td>$saida</td>";
			$tabela.= "<td>$chegada</td>";
		    $tabela.= "</tr>";
		}
		echo $tabela;

	}else{
		echo false;
	}
}

else if($acao == 'dias_carona'){

	$id = $_POST['carona'];

	$carona = $controle->getCarona($id);
	$dias = $carona->getDias();

	$caronaSub = $controle->listarCaronaSub($id);

	foreach ($caronaSub as $c) {
		$dia[] = $c->getDia();
	}

	$max = max(array_map('strtotime', $dia));
	print_r($dia);
	$dia = date('Y-m-d', $max);

	echo $dias . "/" . $dia;
}

else if($acao == 'aceitar_pedido'){

	$id_carona = $_POST['id_carona'];
	$id_usuario = $_POST['id_pedindo'];
	$dia = $_POST['dia'];
	$tipo = $_POST['tipo'];

	$resultado = $controle->aceitarPedido($id_carona, $id_usuario, $dia, $tipo);

	if($resultado){
		alert("Pedido aceito", "location='index.php'");
	}else{
		alert("Pedido já aceito!", "location='lista_mensagem.php'");
	}
}
else if($acao == 'avisar_saida'){

	$carona = $controle->getCarona($_POST['id_carona']);

	$resultado = $controle->avisarSaida($carona);

	if($resultado){
		echo true;
	}else{
		echo false;
	}

}
else if($acao == 'excluir_carona_admin'){

	$id = $_POST['id'];

	$resultado = $controle->deletarCaronaAdmin($id);

	if($resultado){
		echo true;
	}else{
		echo false;
	}
}
else if ($acao == 'excluir_carona'){
	$id = $_POST['id'];

	$resultado = $controle->deletarCarona($id);

	if($resultado){
		echo true;
	}else{
		echo false;
	}
}
else if($acao == 'filtrar_denuncias'){

    $order = $_POST['order'];

    $d = $controle->listarDenuncias($order);
	$temp='';
	foreach ($d as $denuncia) {
	    $id = $denuncia->getIdDenuncia();
	    $status = $denuncia->getStatus();
	    if($status == 0){
	        $classe = "class='warning'";
	    }else if($status == 1){
	        $classe = "class='info'";
	    }else {
	        $classe = "class='danger'";
	    }

	    $temp.= "<tr $classe>";
	    $temp.= "<td>{$denuncia->getIdDenuncia()}</td>";
	    $temp.= "<td>{$denuncia->getDenunciado()->getNome()}</td>";
	    $temp.= "<td>{$denuncia->getRemetente()->getNome()}</td>";
	    $temp.= "<td>{$denuncia->getMotivo()}</td>";
	    $temp.= "<td><textarea readonly>{$denuncia->getDetalhes()}</textarea><td></td>";
	    if($status == 0){
	    $temp.= "<td><a href='processa.php?acao=analisar_denuncia&id=$id&a=1'>Aceitar Denúncia</a></div><a class='btn btn-default btn-raised' href='processa.php?acao=analisar_denuncia&id=$id&a=2'>Recusar Denúncia</a></td>";}
	    else{
	    $temp.= "<td>$status</td>";
	    }
	    $temp.= "</tr>";
	}

	echo $temp;

}
else if ($acao == 'listar_nomes'){

	include_once "./BD/MySQL.class.php";
	$conexao = new MySQL();
	$nome = $_POST['nome'];

	$sql = "select concat(nome, ' ', sobrenome) as completo, id_usuario from usuario where concat(nome, ' ', sobrenome) like '%$nome%'";

	$resultado = $conexao->consulta($sql);

	if($resultado){

		foreach ($resultado as $nome) {

			$temp[] = $nome['completo'];
			$temp[] = $nome['id_usuario'];
			$nomes[] = $temp;
			$temp = Array();
		}

		echo json_encode($nomes);

	}
	else{
		echo null;
	}

}
else if($acao == 'verificar_email'){
	include_once './BD/MySQL.class.php';

	$conexao = new MySQL();

	$email = $_POST['email'];

	$sql = "select * from usuario where email = '$email'";

	$resultado = $conexao->consulta($sql);

	if ($resultado) {
        echo true;
    } else {
        echo false;
    }

}
else if($acao == 'verificar_usuario'){
	include_once './BD/MySQL.class.php';

	$conexao = new MySQL();

	$login = $_POST['login'];

	$sql = "select * from usuario where login = '$login'";

	$resultado = $conexao->consulta($sql);

	if ($resultado) {
	    echo true;
	} else {
	    echo false;
	}

}
else if($acao == "mensagem_carona"){
	include_once './BD/MySQL.class.php';

	$conexao = new MySQL();


	$idCarona = $_POST['id_carona'];
	$mensagem = $_POST['mensagem'];

	$sql = "select distinct id_usuario from usuario_carona where id_carona in (select id_carona from carona where id_carona_base = $idCarona)";

	$resultado = $conexao->consulta($sql);

	foreach ($resultado as $u) {
		$d[]=$controle->getUsuario($u['id_usuario']);
	}
	$m = new Mensagem($_SESSION['user'], "titulo", "teste", $d);
	$resultado2 = $controle->enviarMensagem($m);

	if($resultado2){
		echo true;
	}else{
		echo false;
	}
}
else if($acao == 'carona_lotada'){
	include_once './BD/MySQL.class.php';

	$conexao = new MySQL();

	$id = $_POST['id_carona'];

	$sql = "update carona set lotada = 1 where id_carona = $id";
	$sql2 = "select id_usuario from usuario_carona where id_carona = $id";

	$resultado = $conexao->executa($sql);
	$resultado2 =$conexao->consulta($sql2);

	foreach ($resultado2 as $usuario) {
		$u[] = $controle->getUsuario($usuario['id_usuario']);

	}
	//TODO ARRUMAR A MENSAGEM QUE APARECE AQUI
	$resultado = $controle->enviarMensagem(new Mensagem($_SESSION['sistema'], "Carona marcada como lotada", "A carona (coloca a caroa aqui ou sla) foi marcada como lotada", $u));

	if($resultado){
		echo true;
	}else{
		echo false;
	}

}
else if($acao == 'carona_nlotada'){
	include_once './BD/MySQL.class.php';

	$conexao = new MySQL();

	$id = $_POST['id_carona'];

	$sql = "update carona set lotada = 0 where id_carona = $id";
	$sql2 = "select id_usuario from usuario_carona where id_carona = $id";

	$resultado = $conexao->executa($sql);
	$resultado2 =$conexao->consulta($sql2);

	foreach ($resultado2 as $usuario) {
		$u[] = $controle->getUsuario($usuario['id_usuario']);

	}
	//TODO ARRUMAR A MENSAGEM QUE APARECE AQUI
	$resultado = $controle->enviarMensagem(new Mensagem($_SESSION['sistema'], "Carona não está mais lotada", "A carona não está mais marcada como lotada", $u));

	if($resultado){
		echo true;
	}else{
		echo false;
	}

}
