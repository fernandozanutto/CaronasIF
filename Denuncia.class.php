<?php

Class Denuncia{

    private $idDenuncia;
    private $remetente;
	private $motivo;
    private $detalhes;
	private $denunciado;
    private $status;
    private $data;

    public function __construct($motivo, $detalhes, $denunciado){
        $this->setMotivo($motivo);
        $this->setDetalhes($detalhes);
        $this->setDenunciado($denunciado);
        $this->setStatus('analise');
    }

    public function getIdDenuncia(){
        return $this->idDenuncia;
    }

    public function setIdDenuncia($idDenuncia){
        $this->idDenuncia = $idDenuncia;
    }

    public function getRemetente(){
        return $this->remetente;
    }

    public function setRemetente($remetente){
        $this->remetente = $remetente;
    }

    public function getDenunciado(){
        return $this->denunciado;
    }

    public function setDenunciado($denunciado){
        $this->denunciado = $denunciado;
    }

    public function getDetalhes(){
        return $this->detalhes;
    }

    public function setDetalhes($detalhes){
        $this->detalhes = $detalhes;
    }

    public function getMotivo(){
        return $this->motivo;
    }

    public function setMotivo($motivo){
        $this->motivo = $motivo;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getData(){
        return $this->data;
    }

    public function setData($data){
        $this->data = $data;
}
}

 ?>
