<?php
include_once "cabecalho.php";
$destinatario = $controle->getUsuario($_GET['id']);
$idDestinatario = $destinatario->getId();
$id_remetente = $_SESSION['user']->getId();

if(isset($_SESSION['post'])){
	$assunto = $_SESSION['post']['assunto'];
	$texto = $_SESSION['post']['texto'];
	unset($_SESSION['post']);
}else{
	$assunto = '';
	$texto = '';
}
?>

<div class='col-xs-12 col-md-6 col-md-offset-3'>
    <div class='panel panel-default'>
		<div class='panel-heading'>
			<h3>Denúncia contra o usuário: <?php echo $destinatario->getNome()." ".$destinatario->getSobrenome(); ?></h3>
		</div>
		<div class='panel-body'>

			<form action='processa.php' method='post' role='form'>
	            <input type='hidden' value='denunciar' name='acao' />
	            <input type='hidden' value='<?php echo $idDestinatario; ?>' name='id_denunciado'>
	            <div class='form-group'>
	                <label>Assunto: </label>
	                <input class='form-control' type='text' name='assunto' maxlength='50' value='<?php echo $assunto; ?>' />
					<span class='help-texto'>Máximo de 50 caracteres</span>
	            </div>
	            <div class='form-group'>
	                <label>Descrição: </label>
	                <textarea class='form-control' type='text' name='texto' wrap="hard" maxlength="200"><?php echo $texto; ?></textarea>
					<span class='help-texto'>Máximo de 200 caracteres</span>
	            </div>
	            <button type='submit' class="btn btn-raised btn-primary">Enviar<div class="ripple-container"></div></button>
	        </form>

		</div>

    </div>
</div>


<?php
include_once "rodape.php";
?>
