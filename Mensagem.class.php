<?php

class Mensagem{

    private $idMensagem;
    private $assunto;
    private $mensagem;
    private $remetente;
    private $destinatario;
    private $data;
	private $lida;

    public function __construct($remetente, $assunto, $mensagem, $destinatario){
        $this->setRemetente($remetente);
        $this->setAssunto($assunto);
        $this->setMensagem($mensagem);
        $this->setDestinatario($destinatario);
    }
	public function getLida(){
        return $this->lida;
    }

    public function setLida($lida){
        $this->lida = $lida;
    }

    public function getIdMensagem(){
        return $this->idMensagem;
    }

    public function setIdMensagem($id){
        $this->idMensagem = $id;
    }

    public function getAssunto(){
        return $this->assunto;
    }

    public function setAssunto($assunto){
        $this->assunto = $assunto;
    }

    public function getMensagem(){
        return $this->mensagem;
    }

    public function setMensagem($mensagem){
        $this->mensagem = $mensagem;
    }

    public function getRemetente(){
        return $this->remetente;
    }

    public function setRemetente($remetente){
        $this->remetente = $remetente;
    }

    public function getDestinatario(){
        return $this->destinatario;
    }

    public function setDestinatario($destinatario){
        $this->destinatario = $destinatario;
    }

    public function getData(){
        return $this->data;
    }

    public function setData($data){
        $this->data = $data;
    }
}
?>
