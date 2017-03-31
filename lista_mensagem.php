<?php

include_once 'cabecalho.php';
include_once 'menu_lateral.php';
$mensagens = $controle->listarMensagens($_SESSION['user']);
$recebidas = $mensagens[1];
$enviadas = $mensagens[0];


echo "
<div class='col-xs-12 col-md-6'>
        <div class='panel panel-default'>

			<div class='panel-heading'>
				<h3>Mensagens</h3>
			</div>

				<table class='table' id='recebidas'>
					<thead>
						<tr class='success'>
							<th colspan='10'>Mensagens Recebidas</th>
						</tr>

						<tr>
							<th>Remetente</th>
							<th>Assunto</th>
							<th>Data</th>
						</tr>
					</thead>
					<tbody>
";

if($recebidas){
    foreach ($recebidas as $m) {

        $id= $m->getIdMensagem();
        $remetente = $m->getRemetente()->getNome() . " " . $m->getRemetente()->getSobrenome();
        $assunto = $m->getAssunto();
        $mensagem = $m->getMensagem();
        $data = date('H:i d/m/y', strtotime($m->getData()));
		$lida = $m->getLida();
		$idRemetente= $m->getRemetente()->getId();

		if(!$lida){
			$assunto = "<b> $assunto </b>";
		}

        echo "<tr id_mensagem='$id' id_remetente='$idRemetente' nome_remetente='$remetente' lida='$lida' data-href='mensagem.php?id=$id'>";
        echo "<td>$remetente</td>";
        echo "<td style='cursor:pointer' class='link-row' >$assunto</td>";
        echo "<td>$data</td>";
        echo "</tr>";

    }
}
echo "
</tbody>
</table>


<table class='table'>
    <thead>
		<tr class='success'>
			<th colspan='10'>Mensagens Enviadas</th>
		</tr>

		<tr>
	        <th>Destinatário</th>
	        <th>Assunto</th>
	        <th>Data</th>
	    </tr>
    </thead>
    <tbody>
";
if($enviadas){
    foreach ($enviadas as $m) {
        $destinatarios = $m->getDestinatario();
        $d='';
        foreach ($destinatarios as $i => $dest) {
			if($i == 0){
				$d.=$dest->getNome()." ".$dest->getSobrenome();
			}else{
				$d.= ", ".$dest->getNome()." ".$dest->getSobrenome();
			}
        }
        $id_mensagem = $m->getIdMensagem();
        $destinatario = $m->getDestinatario();
        $assunto = $m->getAssunto();
        $mensagem = $m->getMensagem();
        $data = date('H:i d/m/y', strtotime($m->getData()));

        echo "<tr data-href='mensagem.php?id=$id_mensagem'>";
        echo "<td>$d</td>";
        echo "<td style='cursor:pointer' class='link-row' >$assunto</td>";
        echo "<td>$data</td>";
        echo "</tr>";

    }
}

echo "</tbody></table></div></div>

<div class='col-xs-12 col-md-3'>
	<div class='panel panel-default'>

		<div class='panel-heading'>
			<h3>Filtro de Mensagens</h3>
		</div>
		<div class='panel-body'>

			<div class='row'>
				<div class='radio'>
					<label>
						<input type='radio' name='order' value='id' checked><span class='circle'></span><span class='check'></span>
						Ordernar por data da mensagem
					</label>
				</div>

				<div class='radio'>
					<label>
						<input type='radio' name='order' value='lida'><span class='circle'></span><span class='check'></span>
						Não lidas primeiro
					</label>
				</div>

				<div class='radio'>
					<label>
						<input type='radio' name='order' value='sistema'><span class='circle'></span><span class='check'></span>
						Ordenar por remetente (mensagens do sistema primeiro)
					</label>
				</div>
				<div class='radio'>
					<label>
						<input type='radio' name='order' value='usuario'><span class='circle'></span><span class='check'></span>
						Ordenar por remetente (ordem alfabética)
					</label>
				</div>
			</div>

			<button id='filtrar_mensagens' type='button' class='btn btn-raised btn-primary'>Buscar<div class='ripple-container'></div></button>


		</div>
";

include_once 'rodape.php';
?>
