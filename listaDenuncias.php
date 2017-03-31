<?php
include_once "cabecalho.php";
include 'menu_lateral.php';

$d = $controle->listarDenuncias();


echo"
	<div class='col-xs-12 col-md-6'; id='tabela'>
		<div class='panel panel-default'>
	        <div class='panel-heading'>
				<h3>Listagem de Denúncias</h3>
			</div>


            <table class='table table-striped table-hover' id='lista_denuncias'>
			    <thead>
			        <tr>
			            <th>Denunciado</th>
			            <th>Criador da denuncia</th>
			            <th>Motivo</th>
			            <th>Detalhes</th>
						<th>Status</th>
			        </tr>
			    </thead>
			    <tbody id='corpo'>
";

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

    echo "<tr $classe id_denuncia='{$denuncia->getIdDenuncia()}' status='$status'>";
    echo "<td><a href='perfil.php?id={$denuncia->getDenunciado()->getId()}'>{$denuncia->getDenunciado()->getNome()}</a></td>";
    echo "<td><a href='perfil.php?id={$denuncia->getRemetente()->getId()}'>{$denuncia->getRemetente()->getNome()}</a></td>";
    echo "<td>{$denuncia->getMotivo()}</td>";
    echo "<td>{$denuncia->getDetalhes()}</td>";
    if($status == 0){
    	echo "<td><a class='btn btn-default btn-raised btn-sm' href='processa.php?acao=analisar_denuncia&id=$id&a=1'>Aceitar</a><a class='btn btn-default btn-raised btn-sm' href='processa.php?acao=analisar_denuncia&id=$id&a=2'>Recusar</a></td>";}
    else if($status == 1){
    	echo "<td>Aceita</td>";
    }else if($status == 2){
		echo "<td>Recusada</td>";
	}
    echo "</tr>";
}

echo "</tbody></table></div>


</div>
	<div class='col-xs-12 col-md-3'>
        <div class='panel panel-default'>
			<div class='panel-heading'>
				<h3>Ordenar Denúncias</h3>
			</div>
			<div class='panel-body'>
					<div class='row'>
                    	<div class='radio'>
                        	<label>
                            	<input type='radio' name='order' value='id' checked><span class='circle'></span><span class='check'></span>
                            	Ordernar por data da Denúncia
                        	</label>
                    	</div>

	                    <div class='radio'>
	                        <label>
	                            <input type='radio' name='order' value='status' value='status'><span class='circle'></span><span class='check'></span>
	                            Ordernar por status
	                        </label>
	                    </div>
	                </div>

					<button id='filtrar_denuncias' type='button' class='btn btn-raised btn-primary'>Buscar<div class='ripple-container'></div></button>

            </div>
		</div>
	</div>


</div>
</div>
";

include_once "rodape.php";

?>
