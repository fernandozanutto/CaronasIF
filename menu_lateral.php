<?php

$admin_lateral = "";

if($_SESSION['user']->getTipoUsuario() == "A"){
    $admin_lateral = "
		<h4><a href='#adm' data-toggle='collapse'><span class='glyphicon glyphicon-plus-sign'></span> Administrador</a></h4>
		<div id='adm' class='collapse'>
			<a href='listaUsuarios.php'> Lista de Usuários</a><br>
            <a href='listaDenuncias.php'> Lista de Denúncias</a>
		</div>
	";
}
$temp = $controle->contarNovasMensagens($_SESSION['user']);

$novas_mensagens = $temp>0 ? "<span class='badge'>$temp</span>" : "";

?>


<div class='col-xs-12 col-md-3'>

	<div class='panel panel-default'>
		<div class='panel-heading'>
        	<h3>Menu</h3>
		</div>

		<div class='panel-body' id='menu'>
			<h4><a href='index.php'> Home </a></h4>

			<h4><a href='formCadCarona.php'> Cadastrar carona</a></h4>

			<h4><a href='minhasCaronas.php'> Caronas que ofereço </a></h4>

			<h4><a href='caronaParticipo.php'> Caronas que participo</a></h4>

			<?php echo $admin_lateral; ?>

			<h4><a href='#carro' data-toggle='collapse'><span class='glyphicon glyphicon-plus-sign'></span> Carros</a></h4>
			<div id='carro' class='collapse'>
                <a href='formCadCarro.php'> Cadastrar Carro</a><br>
				<a href='lista_carros.php'> Meus Carros</a>
            </div>

			<h4><a href='#mensagem' data-toggle='collapse'><span class='glyphicon glyphicon-plus-sign'></span> Mensagens <?php echo $novas_mensagens; ?></a></h4>
			<div id='mensagem' class='collapse'>
                <a href='formNovMensagem.php'> Enviar mensagem</a><br>
				<a href='lista_mensagem.php'> Minhas mensagens</a>
            </div>

		</div>
	</div>

</div>
