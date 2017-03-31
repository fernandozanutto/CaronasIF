<?php


class Carro{

    private $idCarro;
    private $usuario;
    private $marca;
    private $modelo;
    private $lugares;
    private $arCondicionado;
    private $placa;
    private $cor;

    public function __construct($usuario, $marca, $modelo, $lugares, $ar, $placa, $cor){
        $this->usuario = $usuario;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->lugares = $lugares;
        $this->arCondicionado = $ar;
        $this->placa = $placa;
        $this->cor = $cor;
    }


    public function getIdCarro(){
		return $this->idCarro;
	}

	public function setIdCarro($idCarro){
		$this->idCarro = $idCarro;
	}

	public function getUsuario(){
		return $this->usuario;
	}

	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	public function getMarca(){
		return $this->marca;
	}

	public function setMarca($marca){
		$this->marca = $marca;
	}

	public function getModelo(){
		return $this->modelo;
	}

	public function setModelo($modelo){
		$this->modelo = $modelo;
	}

	public function getLugares(){
		return $this->lugares;
	}

	public function setLugares($lugares){
		$this->lugares = $lugares;
	}

	public function getArCondicionado(){
		return $this->arCondicionado;
	}

	public function setArCondicionado($arCondicionado){
		$this->arCondicionado = $arCondicionado;
	}

	public function getPlaca(){
		return $this->placa;
	}

	public function setPlaca($placa){
		$this->placa = $placa;
	}

	public function getCor(){
		return $this->cor;
	}

	public function setCor($cor){
		$this->cor = $cor;
	}

}

 ?>
