<?php
class Usuario{

	private $id;
	private $nome;
    private $sobrenome;
	private $login;
	private $senha;
    private $dataCadastro;
    private $vinculo;
    private $tipoUsuario;
    private $email;
	private $cidade;
	private $logradouro;
	private $numEndereco;
	private $telefone;
	private $ativo;

	public function __construct($nome, $sobrenome, $login, $senha, $vinculo, $tipoUsuario, $email, $cidade, $logradouro, $num_endereco, $telefone=null){
		$this->setNome(ucfirst(strtolower($nome)));
        $this->setSobrenome(ucfirst(strtolower($sobrenome)));
        $this->setLogin(strtolower($login));
        $this->setSenha(strtolower($senha));
        $this->setVinculo($vinculo);
        $this->setTipoUsuario($tipoUsuario);
		$this->setEmail($email);
		$this->setCidade($cidade);
		$this->setLogradouro($logradouro);
		$this->setNumEndereco($num_endereco);
		$this->setTelefone($telefone);
	}

	public function getAtivo(){
		 return $this->ativo;
	 }

	public function setAtivo($id){
		 return $this->ativo = $id;

		 return $this;
	 }

   public function getId(){
        return $this->id;
    }

   public function setId($id){
        return $this->id = $id;

        return $this;
    }

   public function getNome(){
        return $this->nome;
    }

   public function setNome($nome){
        $this->nome = $nome;

        return $this;
    }

   public function getSobrenome(){
        return $this->sobrenome;
    }

   public function setSobrenome($sobrenome){
        $this->sobrenome = $sobrenome;

        return $this;
    }

   public function getLogin(){
        return $this->login;
    }

   public function setLogin($login){
        $this->login = $login;

        return $this;
    }

   public function getSenha(){
        return $this->senha;
    }

   public function setSenha($senha){
        $this->senha = $senha;

        return $this;
    }

   public function getDataCadastro(){
        return $this->tempo_cadastro;
    }

   public function setDataCadastro($tempo_cadastro){
        $this->tempo_cadastro = $tempo_cadastro;
        return $this;
    }


   public function getVinculo(){
        return $this->vinculo;
    }

   public function setVinculo($vinculo){
        $this->vinculo = $vinculo;

        return $this;
    }

   public function getTipoUsuario(){
        return $this->tipo_usuario;
    }

   public function setTipoUsuario($tipoUsuario){
        $this->tipo_usuario = $tipoUsuario;

        return $this;
    }

   public function getEmail(){
        return $this->email;
    }

   public function setEmail($email){
        $this->email = $email;

        return $this;
    }

   public function getCidade(){
        return $this->cidade;
    }

   public function setCidade($cidade){
        $this->cidade = $cidade;

        return $this;
    }

   public function getLogradouro(){
        return $this->logradouro;
    }

   public function setLogradouro($logradouro){
        $this->logradouro = $logradouro;

        return $this;
    }

   public function getNumEndereco(){
        return $this->numEndereco;
    }

   public function setNumEndereco($numEndereco){
        $this->numEndereco = $numEndereco;

        return $this;
    }

   public function getTelefone(){
        return $this->telefone;
    }

   public function setTelefone($telefone){
        $this->telefone = $telefone;

        return $this;
    }

}


?>
