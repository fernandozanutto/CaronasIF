<?php
abstract class BancoDeDados{
	
	private $conexao;
	
	abstract public function __construct();
	abstract public function executa($sql);
	abstract public function consulta($select);
	abstract public function __destruct();
}
?>