<?php

include_once 'cabecalho.php';

$id_mensagem = $_GET['id'];

$m = $controle->visualizarMensagem($id_mensagem, $_SESSION['user']);

include_once 'menu_lateral.php';

if($m){
    $assunto = $m->getAssunto();
    $mensagem = $m->getMensagem();
    $data = date('H:i d/m/Y', strtotime($m->getData()));
    $destinatario = $m->getDestinatario();
    $remetente = $m->getRemetente();
	$lida = $m->getLida();

	$destinatarios = '';
	foreach ($destinatario as $d) {
		$destinatarios.= "{$d->getNome()} {$d->getSobrenome()}";
	}

    echo"
    <div class='col-xs-12 col-md-6'>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <h3>Assunto: $assunto</h3>
            </div>

            <div class='panel-body'>
	            Data: $data <br>
	            Remetente: {$remetente->getNome()} {$remetente->getSobrenome()} <br>
	            Destinatários: $destinatarios<br>
            	<br>

                <p>$mensagem</p>
            </div>
        </div>
    </div>

    ";
}else{
    header('location: index.php');
}


include_once 'rodape.php';

?>
