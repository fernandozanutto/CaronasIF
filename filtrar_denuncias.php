<?php

include_once "./BD/MySQL.class.php";
include_once "Denuncia.class.php";
include_once "ControleAdmin.class.php";


if(isset($_POST['order'])){

    $order = $_POST['order'];

    $conexao = new MySQL();

    $sql = "select * from denuncia order by $order";

    $resultado = $conexao->consulta($sql);

    foreach ($resultado as $denuncia) {

        $d = new Denuncia($denuncia['assunto'], $denuncia['mensagem'], $denuncia['id_denunciado']);
        $d->setIdDenuncia($denuncia['id_denuncia']);
        $d->setIdRemetente($denuncia['id_remetente']);
        $d->setStatus($denuncia['status']);
        $d->setData($denuncia['data']);

        $denuncias[] = $d;
    }

        $tabela = "";

        foreach ($denuncias as $denuncia) {

            $id = $denuncia->getIdDenuncia();
            $status = $denuncia->getStatus();

            $status = $denuncia->getStatus();
            if($status == 0){
                $classe = "class='warning'";
            }else if($status == 1){
                $classe = "class='info'";
            }else {
                $classe = "class='danger'";
            }


            $tabela.= "<tr $classe>";
            $tabela.= "<td>{$denuncia->getIdDenuncia()}</td>";
            $tabela.= "<td>{$denuncia->getIdDenunciado()}</td>";
            $tabela.= "<td>{$denuncia->getIdRemetente()}</td>";
            $tabela.= "<td>{$denuncia->getMotivo()}</td>";
            $tabela.= "<td><textarea readonly>{$denuncia->getDetalhes()}</textarea><td></td>";

            if($status == 0){
            $tabela.="<td><button><a href='processa.php?acao=analisar_denuncia&id=$id&a=1'>Aceitar Denúncia</a></button><button><a href='processa.php?acao=analisar_denuncia&id=$id&a=2'>Recusar Denúncia</a></button></td>";}
            else{
            $tabela.="<td>$status</td>";
            }

            $tabela.= "</tr>";
        }

        echo $tabela;

}


?>
