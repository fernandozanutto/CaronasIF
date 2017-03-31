<?php
include "BancoDeDados.class.php";
include "Configuracao.inc.php";

class MySQL extends BancoDeDados{

	public function __construct(){

		$this->conexao = mysqli_connect(HOST, USUARIO, SENHA);
		mysqli_select_db($this->conexao, BANCO);
	}

	public function __destruct(){
		mysqli_close($this->conexao);
	}

	public function executa($sql){
		$retornoExecucao = mysqli_query($this->conexao, $sql) ;
		return $retornoExecucao;
	}

	public function consulta($select){
		$query = mysqli_query($this->conexao, $select) ;

		$retorno = array();
		$dados = array();
		while($retorno = mysqli_fetch_array($query)){
			$dados[] = $retorno;
		}
		return $dados;
	}
}
?>
