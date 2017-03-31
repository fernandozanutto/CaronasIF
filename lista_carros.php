<?php

include_once 'cabecalho.php';
include_once 'menu_lateral.php';

$carros = $controle->listarCarros($_SESSION['user']);

if($carros){
	echo"

	    <div class='col-md-9 col-xs-12'>
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3>Meus Carros</h3>
				</div>
	        <table class='table table-stripped'>
	            <thead>
	                <tr>
	                    <th>Marca</th>
	                    <th>Modela</th>
	                    <th>Placa</th>
	                    <th>Lugares</th>
	                    <th>Ação</th>
	                </tr>
	            </thead>
	            <tbody>
	";


	foreach ($carros as $carro) {
	    $modelo = $carro->getModelo();
	    $placa = $carro->getPlaca();
	    $marca = $carro->getMarca();
	    $lugares = $carro->getLugares();
	    $id = $carro->getIdCarro();

	    echo"<tr>";
	    echo"<td>$marca</td>";
	    echo"<td>$modelo</td>";
	    echo"<td>$placa</td>";
	    echo"<td>$lugares</td>";
	    echo "<td><a href='processa.php?acao=del_carro&id=$id'>Excluir</a></td>";
		echo "</tr>";


	}

	echo"</tbody></table></div></div>";

}else{
	echo"

		<div class='bs-component'>
			<div id='myModal' class='modal fade' role='dialog'>
			  <div class='modal-dialog'>

			  <div class='modal-content'>
			<div class='panel panel-warning'>
			  <div class='panel-heading'>
				<h3 class='panel-title'>Nenhum carro cadastrado</h3>
			  </div>
			  <div class='panel-body'>
						<div class='modal-body'>
						<p>Você ainda não possui um carro cadastrado! </p>
					</div>
					<div class='modal-footer'>
						<a href='formCadCarro.php' class='btn btn-primary' >Ir para página de cadastro de carros</a>
					</div>
				</div>

			  </div>
			</div>

			</div>
			</div>
		";
}


include_once "rodape.php";

?>
