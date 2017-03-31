<?php

class CaronaSub{

	private $idCarona;
	private $idCaronaBase;
	private $dia;
	private $lotada;
	private $usuarios;

	public function __construct($idCarona, $idCaronaBase, $dia){
		$this->idCarona = $idCarona;
		$this->idCaronaBase = $idCaronaBase;
		$this->dia = $dia;
	}


	public function getIdCarona(){
		return $this->idCarona;
	}

	public function setIdCarona($idCarona){
		$this->idCarona = $idCarona;
	}

	public function getLotada(){
		return $this->lotada;
	}

	public function setLotada($lotada){
		$this->lotada = $lotada;
	}

	public function getIdCaronaBase(){
		return $this->idCaronaBase;
	}

	public function setIdCaronaBase($idCaronaBase){
		$this->idCaronaBase = $idCaronaBase;
	}

	public function getDia(){
		return $this->dia;
	}

	public function setDia($dia){
		$this->dia = $dia;
	}

	public function getUsuarios(){
		return $this->usuarios;
	}

	public function setUsuarios($usuarios){
		$this->usuarios = $usuarios;
	}

}




 ?>
