<?php
//TODO REVER AS COISAS QUE O ADMIN PODE FAZER
require_once "ControleUsuario.class.php";

class ControleAdmin extends ControleUsuario{

    public function listarDenuncias($order = 'id_denuncia'){

        $sql = "select * from denuncia order by $order";
        $resultado = $this->conexao->consulta($sql);

		if($resultado){

	        foreach ($resultado as $denuncia) {

	            $d = new Denuncia($denuncia['assunto'], $denuncia['mensagem'], $this->getUsuario($denuncia['id_denunciado']));
	            $d->setIdDenuncia($denuncia['id_denuncia']);
	            $d->setRemetente($this->getUsuario($denuncia['id_remetente']));
	            $d->setStatus($denuncia['status']);
	            $d->setData($denuncia['data']);

	            $denuncias[] = $d;
	        }
			return $denuncias;
		}else{
			return false;
		}
    }
	public function deletarCaronaAdmin($id_carona){
		$carona = $this->getCarona($id_carona);

		$sql = "update carona_base set ativo = 0 where id_carona_base = $id_carona";

		$resultado = $this->conexao->executa($sql);

		$sql2 = "select distinct id_usuario from usuario_carona where id_carona in (select id_carona from carona where id_carona_base = $id_carona)";

		if ($resultado){
			/*
			Esta mensagem estará informado o usuário que criou a carona,
			local de origem, carro utilizado,
			quem estava utilizando a carona, o turno, horário de saída e chegada,
			dias da carona e motivo de exclusão da carona.
			*/
			$nome = $carona->getDono()->getNome(). " " . $carona->getDono()->getSobrenome();

			$origem = $carona->getOrigem();
			$destino = $carona->getDestino();
			$carro = $carona->getCarro()->getMarca() . " " . $carona->getCarro()->getModelo();
			$saida = $carona->getHorarioSaida();
			$chegada = $carona->getHorarioChegada();
			$dias = $carona->getDias();
			$motivo = "teste";
			$mensagem = "Origem $origem<br>Destino: $destino<br>Saida: $saida<br>Chegada: $chegada<br>Dias: $dias<br>Carro: $carro";

			$resultado2 = $this->conexao->consulta($sql2);

			foreach ($resultado2 as $id) {
				$u[] = $this->getUsuario($id['id_usuario']);
			}
			$u[] = $carona->getDono();
			//TODO ARRUMAR A MENSAGEM
			//TODO ADICIONAR MOTIVO DA CAROA SER EXCLUIDA

			$m = new Mensagem($_SESSION['sistema'], "Carona excluída", "A carona que você usava, do usuário $nome foi excluída por um administrador pelo motivo: $motivo.<br>$mensagem", $u);
			$r = $this->enviarMensagem($m);

			return true;
		}else{
			return false;
		}
	}

    public function analisarDenuncia($id_denuncia, $status){

        $sql = "UPDATE denuncia SET status = $status where id_denuncia= $id_denuncia";

        $resultado = $this->conexao->executa($sql);
		return $resultado;
    }

    public function desativarUsuarioAdmin($user){
		$id = $user->getId();

		$nome = $user->getNome();

        $sql = "update usuario set ativo = 2 where id_usuario= $id";
        $resultado = $this->conexao->executa($sql);

		if($resultado){
			$r2 = $this->enviarEmail($user, "Desativação de Conta - Caronas IF", "Olá, $nome.<br> Sua conta foi desativada por um administrador do sistema. Caso queira, poderá entrar em contato respondendo este email, ou enviando um email para caronasifrs@gmail.com. <br><br>Esta é uma mensagem de envio automático do sistema.");

			if($r2){
				return 3;
			}else{
				return 2;
			}
		}else{
			return 0;
		}

    }

    public function deletar_carona($id_carona){

        $sql = "update carona set status = 'inativa' where id_carona= $id_carona";
        $resultado = $this->conexao->executa($sql);
        return $resultado;

    }

    public function visualizar_carona($id_carona){

        $sql = "select * from carona where id_carona = $id_carona";
        $resultado = $this->conexao->consulta($sql);
        return $resultado;

    }

    public function listar_usuarios(){

        $sql= "select * from usuario where id_usuario <> 0";

        $resultado = $this->conexao->consulta($sql);

        foreach ($resultado as $usuario) {

            $id = $usuario['id_usuario'];
            $nome = $usuario['nome'];
            $sobrenome= $usuario['sobrenome'];
            $login = $usuario['login'];
            $senha = $usuario['senha'];
            $tempo_cadastro = $usuario['tempo_cadastro'];
            $vinculo = $usuario['vinculo'];
            $tipo_usuario = $usuario['tipo_usuario'];
            $email = $usuario['email'];
            $cidade = $usuario['cidade'];
            $logradouro = $usuario['logradouro'];
            $num_endereco = $usuario['num_endereco'];
            $telefone = $usuario['telefone'];
			$ativo = $usuario['ativo'];

            $u = new Usuario($nome, $sobrenome, $login, $senha, $vinculo, $tipo_usuario, $email, $cidade, $logradouro, $num_endereco, $telefone);
            $u->setId($id);
            $u->setDataCadastro($tempo_cadastro);
			$u->setAtivo($ativo);

            $usuarios[] = $u;
        }
        return $usuarios;

    }

}

?>
