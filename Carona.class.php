<?php

class Carona{

	private $idCarona;
	private $dono;
	private $origem;
	private $destino;
	private $carro;
	private $horarioSaida;
	private $horarioChegada;
	private $percurso;
	private $dias;
	private $mostrarCarro;
	private $ativo;

	public function __construct($dono, $origem, $destino, $carro, $horarioSaida, $horarioChegada, $dias, $mostrar=0){
		$this->dono = $dono;
		$this->origem = $origem;
		$this->destino = $destino;
		$this->carro = $carro;
		$this->horarioSaida = $horarioSaida;
		$this->horarioChegada = $horarioChegada;
		$this->dias = $dias;
		$this->mostrarCarro = $mostrar;

	}

	public function getPercurso(){
		return $this->percurso;
	}

	public function setPercurso($percurso){

		if (strpos($percurso, '|') === true) {
			$percurso = explode('|', $percurso);
			array_pop($percurso);

			$temp = implode('|', $percurso);

			$this->percurso = $temp;
		}else{
			$this->percurso = $percurso;
		}
	}

	public function getMostrarCarro(){
		return $this->mostrarCarro;
	}

	public function setMostrarCarro($mostrarCarro){
		$this->mostrarCarro = $mostrarCarro;
	}

	public function getIdCarona(){
		return $this->idCarona;
	}

	public function setIdCarona($idCarona){
		$this->idCarona = $idCarona;
	}

	public function getDono(){
		return $this->dono;
	}

	public function setDono($dono){
		$this->dono = $dono;
	}

	public function getOrigem(){
		return $this->origem;
	}

	public function setOrigem($origem){
		$this->origem = $origem;
	}

	public function getDestino(){
		return $this->destino;
	}

	public function setDestino($destino){
		$this->destino = $destino;
	}

	public function getCarro(){
		return $this->carro;
	}

	public function setCarro($carro){
		$this->carro = $carro;
	}

	public function getHorarioSaida(){
		return $this->horarioSaida;
	}

	public function setHorarioSaida($horarioSaida){
		$this->horarioSaida = $horarioSaida;
	}

	public function getHorarioChegada(){
		return $this->horarioChegada;
	}

	public function setHorarioChegada($horarioChegada){
		$this->horarioChegada = $horarioChegada;
	}

	public function getDias(){
		return $this->dias;
	}

	public function setDias($dias){
		$this->dias = $dias;
	}

	public function getAtivo(){
		return $this->ativo;
	}

	public function setAtivo($dias){
		$this->ativo = $dias;
	}

}

 ?>
