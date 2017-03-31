<?php
require_once "Usuario.class.php";
require_once "Denuncia.class.php";
require_once 'Carona.class.php';
require_once 'CaronaSub.class.php';
require_once 'Mensagem.class.php';
require_once 'Carro.class.php';
require_once "./BD/MySQL.class.php";
require_once 'funcoes.php';

class ControleUsuario{

    protected $conexao;

    public function __construct(){
        $this->conexao = new MySQL();
    }

	public function getCaronasUsuarioUtiliza($usuario){
		$idUsuario = $usuario->getId();

		$sql = "select id_carona_base from carona_base where id_carona_base in (select distinct id_carona_base from carona where id_carona in (select id_carona from usuario_carona where id_usuario = $idUsuario)) and ativo = true";

		$resultado = $this->conexao->consulta($sql);

		foreach ($resultado as $carona) {
			$retorno[] = $this->getCarona($carona['id_carona_base']);
		}

		if($resultado){
			return $retorno;
		}else{
			return false;
		}
	}

    public function editarUsuario($cidade, $logradouro, $email, $telefone, $senha, $num_endereco){

        $id = $_SESSION['user']->getId();

		$sql = "update usuario set num_endereco = '$num_endereco', cidade = '$cidade', senha = '$senha', logradouro = '$logradouro', email = '$email', telefone = '$telefone' where id_usuario =  $id";

		$resultado = $this->conexao->executa($sql);
		return $resultado;
	}

	public function desativarUsuario($usuario){

		$id = $usuario->getId();

		$sql = "update usuario set ativo = 0 where id_usuario = $id";

		//acha os usuarios que dao carona pra quem ta excluindo o perfil
		$sql2 = "SELECT DISTINCT id_usuario from carona_base where id_carona_base in (
					SELECT DISTINCT id_carona_base from carona where id_carona in (
						select id_carona from usuario_carona where id_usuario = $id))";

		//acha os usuarios que pegam carona de quem ta ecluindo o perfil
		$sql3 = "SELECT DISTINCT id_usuario from usuario_carona WHERE id_carona in (
					SELECT id_carona from carona where id_carona_base in (
						SELECT id_carona_base from carona_base where id_usuario = $id))";

		$resultado = $this->conexao->executa($sql);

		if($resultado){

			$r2 = $this->conexao->executa($sql2);
			$r3 = $this->conexao->executa($sql3);
			$desativou = $_SESSION['user'];
			$nome = $desativou->getNome()." ".$desativou->getSobrenome();
			if($r2){

				foreach($r2 as $id_usuario){
					$u[] = $this->getUsuario($id_usuario['id_usuario']);
				}

				$m = new Mensagem($_SESSION['sistema'], "Usuário desativou a conta", "Olá, estamos enviando esta mensagem para informar que o usuário $nome que pegava carona com você e desativou a conta. (Mensagem de envio automático do sistema)", $u);
				$this->enviarMensagem($m);
			}
			if($r3){
				foreach ($r3 as $id_usuario) {
					$u1[] = $this->getUsuario($id_usuario['id_usuario']);
				}
				$m = new Mensagem($_SESSION['sistema'], "Usuário desativou a conta", "Olá, estamos enviando esta mensagem para informar que o usuário $nome que dava carona pra você e acabou de desativar a conta. (Mensagem de envio automático do sistema)", $u1);
				$this->enviarMensagem($m);
			}

			return $resultado;
		}else{
			return false;
		}
	}

	public function getUsuario($id){

		$sql = "select * from usuario where id_usuario = $id";

		$resultado = $this->conexao->consulta($sql);

        if($resultado){
            $nome = ucfirst(strtolower($resultado[0]['nome']));
            $sobrenome = ucfirst(strtolower($resultado[0]['sobrenome']));
            $email = $resultado[0]['email'];
            $vinculo = $resultado[0]['vinculo'];
            $login = $resultado[0]['login'];
            $tipo_usuario = $resultado[0]['tipo_usuario'];
            $cidade = $resultado[0]['cidade'];
            $logradouro = $resultado[0]['logradouro'];
            $num_endereco = $resultado[0]['num_endereco'];
            $senha = $resultado[0]['senha'];
            $telefone=$resultado[0]['telefone'];
			$tempo = $resultado[0]['tempo_cadastro'];

        	$u = new Usuario($nome, $sobrenome, $login, $senha, $vinculo, $tipo_usuario, $email, $cidade, $logradouro, $num_endereco, $telefone);
            $u->setId($id);
			$u->setDataCadastro($tempo);

        	return $u;
        }else{
			return false;
		}
	}

    public function denunciarUsuario($denuncia){

        $id_remetente = $_SESSION['user']->getId();
        $id_destinatario = $denuncia->getDenunciado()->getId();
        $assunto = $denuncia->getMotivo();
        $mensagem = $denuncia->getDetalhes();

        $sql = "insert into denuncia (id_remetente, id_denunciado, assunto, mensagem) values($id_remetente, $id_destinatario, '$assunto', '$mensagem')";

        $resultado = $this->conexao->executa($sql);

        return $resultado;

    }

    public function getDenuncias($usuario){
		$idUsuario = $usuario->getId();

        $sql = "select * from denuncia where id_denunciado = $idUsuario and status = 1";

        $resultado = $this->conexao->consulta($sql);

		if($resultado){

			foreach ($resultado as $denuncia){

				$idDenuncia = $denuncia['id_denuncia'];
				$remetente = $this->getUsuario($denuncia['id_remetente']);
				$denunciado = $this->getUsuario($denuncia['id_denunciado']);
				$assunto = $denuncia['assunto'];
				$mensagem = $denuncia['mensagem'];
				$tempo = $denuncia['data'];
				$status = $denuncia['status'];

				$d = new Denuncia($assunto, $mensagem, $denunciado, $remetente, $status);
				$d->setData($tempo);
				$d->setIdDenuncia($idDenuncia);

				$retorno[] = $d;
			}

	        return $retorno;
		}else{
			return false;
		}
    }

    public function enviarMensagem($mensagem){

        $id_remetente = $mensagem->getRemetente()->getId();
        $assunto=$mensagem->getAssunto();
        $m = addslashes($mensagem->getMensagem());

        $pre_sql = "select max(id_mensagem) from mensagem";
        $resultado = $this->conexao->consulta($pre_sql);
        $id = ++$resultado[0][0];

        $sql = "insert into mensagem (id_mensagem, id_remetente, assunto, mensagem) values ($id, $id_remetente, '$assunto', \"$m\")";

        $resultado2 = $this->conexao->executa($sql);
		if($resultado2){

            $destinatarios = $mensagem->getDestinatario();

            foreach ($destinatarios as $destinatario) {
                $sql2 = "insert into destinatario_mensagem (id_mensagem, id_destinatario) values ($id, {$destinatario->getId()})";

                $resultado3 = $this->conexao->executa($sql2);
            }

			return $resultado2;
		}
		else{
			return false;
		}

    }

    public function visualizarMensagem($id_mensagem, $usuario){

		$idUsuario = $usuario->getId();

        $sql = "select id_mensagem, assunto, mensagem, id_remetente, data, id_destinatario, lida
		from mensagem natural join destinatario_mensagem
		where id_mensagem = $id_mensagem and (id_destinatario=$idUsuario or id_remetente=$idUsuario)";

        $resultado = $this->conexao->consulta($sql);

        if($resultado){

            foreach ($resultado as $m) {
                $destinatario[] = $this->getUsuario($m['id_destinatario']);
            }

			$idMensagem = $resultado[0]['id_mensagem'];
            $assunto = $resultado[0]['assunto'];
            $mensagem = $resultado[0]['mensagem'];
            $remetente = $this->getUsuario($resultado[0]['id_remetente']);
            $data = $resultado[0]['data'];
			$lida = $resultado[0]['lida'];

            $m = new Mensagem($remetente, $assunto, $mensagem, $destinatario);
            $m->setData($data);
			$m->setLida($lida);
			$m->setIdMensagem($idMensagem);

            $sql2 = "update destinatario_mensagem set lida = 1 where id_mensagem = $id_mensagem and id_destinatario = $idUsuario";
            $resultado2 = $this->conexao->executa($sql2);

            return $m;
        }else{
            return false;
        }
    }

    public function contarNovasMensagens($usuario){

        $id = $usuario->getId();

        $sql = "SELECT count(*) from mensagem, destinatario_mensagem where mensagem.id_mensagem = destinatario_mensagem.id_mensagem and id_destinatario = $id and lida = 0";
        $resultado = $this->conexao->consulta($sql);

        return $resultado[0][0];
    }

    public function listarMensagens($usuario){

		$id = $usuario->getId();

        $enviadas=null;
        $recebidas=null;


         #     # ####### #     #  #####     #     #####  ####### #     #    ####### #     # #     # ###    #    ######     #     #####     ######  ####### #       #######    #     #  #####  #     #    #    ######  ### #######
         ##   ## #       ##    # #     #   # #   #     # #       ##   ##    #       ##    # #     #  #    # #   #     #   # #   #     #    #     # #       #       #     #    #     # #     # #     #   # #   #     #  #  #     #
         # # # # #       # #   # #        #   #  #       #       # # # #    #       # #   # #     #  #   #   #  #     #  #   #  #          #     # #       #       #     #    #     # #       #     #  #   #  #     #  #  #     #
         #  #  # #####   #  #  #  #####  #     # #  #### #####   #  #  #    #####   #  #  # #     #  #  #     # #     # #     #  #####     ######  #####   #       #     #    #     #  #####  #     # #     # ######   #  #     #
         #     # #       #   # #       # ####### #     # #       #     #    #       #   # #  #   #   #  ####### #     # #######       #    #       #       #       #     #    #     #       # #     # ####### #   #    #  #     #
         #     # #       #    ## #     # #     # #     # #       #     #    #       #    ##   # #    #  #     # #     # #     # #     #    #       #       #       #     #    #     # #     # #     # #     # #    #   #  #     #
         #     # ####### #     #  #####  #     #  #####  ####### #     #    ####### #     #    #    ### #     # ######  #     #  #####     #       ####### ####### #######     #####   #####   #####  #     # #     # ### #######


		$sql = "select * from mensagem where id_remetente = $id  order by data desc";
        $resultado = $this->conexao->consulta($sql);


        foreach ($resultado as $mensagem) {

            $destinatario = Array();

            $id_mensagem = $mensagem['id_mensagem'];
            $sql3 = "select id_destinatario from destinatario_mensagem, usuario where id_destinatario=id_usuario and id_mensagem = $id_mensagem";

            $resultado3 = $this->conexao->consulta($sql3);

            foreach ($resultado3 as $dest) {
                $destinatario[] = $this->getUsuario($dest['id_destinatario']);

            }

            $remetente=$this->getUsuario($mensagem['id_remetente']);
            $assunto=$mensagem['assunto'];
            $texto = $mensagem['mensagem'];
            $data = $mensagem['data'];

            $m = new Mensagem($remetente, $assunto, $texto, $destinatario);
            $m->setIdMensagem($id_mensagem);
            $m->setData($data);

            $enviadas[] = $m;
        }


         #     # ####### #     #  #####     #     #####  ####### #     #    ######  #######  #####  ####### ######  ### ######     #     #####     ######  ####### #       #######    #     #  #####  #     #    #    ######  ### #######
         ##   ## #       ##    # #     #   # #   #     # #       ##   ##    #     # #       #     # #       #     #  #  #     #   # #   #     #    #     # #       #       #     #    #     # #     # #     #   # #   #     #  #  #     #
         # # # # #       # #   # #        #   #  #       #       # # # #    #     # #       #       #       #     #  #  #     #  #   #  #          #     # #       #       #     #    #     # #       #     #  #   #  #     #  #  #     #
         #  #  # #####   #  #  #  #####  #     # #  #### #####   #  #  #    ######  #####   #       #####   ######   #  #     # #     #  #####     ######  #####   #       #     #    #     #  #####  #     # #     # ######   #  #     #
         #     # #       #   # #       # ####### #     # #       #     #    #   #   #       #       #       #     #  #  #     # #######       #    #       #       #       #     #    #     #       # #     # ####### #   #    #  #     #
         #     # #       #    ## #     # #     # #     # #       #     #    #    #  #       #     # #       #     #  #  #     # #     # #     #    #       #       #       #     #    #     # #     # #     # #     # #    #   #  #     #
         #     # ####### #     #  #####  #     #  #####  ####### #     #    #     # #######  #####  ####### ######  ### ######  #     #  #####     #       ####### ####### #######     #####   #####   #####  #     # #     # ### #######f


		$sql2 = "select id_mensagem, assunto, data, mensagem, id_remetente, id_destinatario, lida from mensagem natural join destinatario_mensagem, usuario where id_usuario=id_remetente and mensagem.id_mensagem in (SELECT id_mensagem from destinatario_mensagem where id_destinatario=$id) and id_destinatario= $id order by data desc";

        $resultado2 = $this->conexao->consulta($sql2);

        foreach ($resultado2 as $mensagem) {

            $id = $mensagem['id_mensagem'];
            $remetente=$this->getUsuario($mensagem['id_remetente']);
            $assunto=$mensagem['assunto'];
            $texto = $mensagem['mensagem'];
            $destinatario = $this->getUsuario($mensagem['id_destinatario']);

            $data = $mensagem['data'];
			$lida = $mensagem['lida'];

            $m = new Mensagem($remetente, $assunto, $texto, $destinatario);
            $m->setData($data);
            $m->setIdMensagem($id);
			$m->setLida($lida);

            $recebidas[] = $m;
        }

        return Array($enviadas, $recebidas);

    }

    public function cadastrarCarro($carro){

        $dono = $carro->getUsuario()->getId();
        $placa = $carro->getPlaca();
        $modelo = $carro->getModelo();
        $cor = $carro->getCor();
        $lugares = $carro->getLugares();
        $ar = $carro->getArCondicionado();
        $marca = $carro->getMarca();

        $sql = "insert into carro (id_usuario, placa, modelo, cor, lugares, ar_condicionado, marca)
		values  ($dono, '$placa', '$modelo', '$cor', $lugares, $ar, '$marca')";

        $resultado = $this->conexao->executa($sql);

        return $resultado;

    }

    public function getCarro($idCarro){

		$sql = "select * from carro where id_carro = $idCarro";

        $resultado = $this->conexao->consulta($sql);

		if($resultado){
			$resultado=$resultado[0];
			$idCarro = $resultado['id_carro'];
			$dono = $this->getUsuario($resultado['id_usuario']);
			$placa = $resultado['placa'];
			$ar = $resultado['ar_condicionado'];
			$lugares = $resultado['lugares'];
			$modelo = $resultado['modelo'];
			$cor = $resultado['cor'];
			$marca = $resultado['marca'];

			$carro = new Carro($dono, $marca, $modelo, $lugares, $ar, $placa, $cor);
			$carro->setIdCarro($idCarro);

			return $carro;
		}else{
			return false;
		}

    }

    public function listarCarros($usuario){

		$idUsuario = $usuario->getId();
        $sql = "select * from carro where id_usuario = $idUsuario and ativo = true";

        $resultado = $this->conexao->consulta($sql);
		if($resultado){

	        foreach ($resultado as $carro) {
	            $modelo=$carro['modelo'];
	            $placa = $carro['placa'];
	            $cor = $carro['cor'];
	            $dono = $this->getUsuario($carro['id_usuario']);
	            $id = $carro['id_carro'];
	            $lugares = $carro['lugares'];
	            $ar = $carro['ar_condicionado'];
	            $marca = $carro['marca'];

	            $c = new Carro($dono, $marca, $modelo, $lugares, $ar, $placa, $cor);
	            $c->setIdCarro($id);

	            $carros[]=$c;
	        }

	        return $carros;
		}else{
			return false;
		}
    }

    public function deletarCarro($carro){

		$idCarro = $carro->getIdCarro();

        $sql = "update carro set ativo = 0 where id_carro = $idCarro";

        $resultado = $this->conexao->executa($sql);
		if($resultado){
        	return $resultado;
        }else{
            return false;
        }
    }

    public function logout(){
        session_destroy();
        header('location: login.php');

    }

	public function cadastrarCarona($carona){

		$idDono = $carona->getDono()->getId();
		$origem = $carona->getOrigem();
		$destino = $carona->getDestino();
		$dias = implode("-",$carona->getDias());
		$horario_saida = $carona->getHorarioSaida();
		$horario_chegada = $carona->getHorarioChegada();
		$idCarro = $carona->getCarro()->getIdCarro();
		$mostrar = $carona->getMostrarCarro();
		$carro = $carona->getCarro()->getIdCarro();
		$percurso = $carona->getPercurso();

        $sql = "insert into carona_base (id_usuario, origem, destino, dias, horario_saida, horario_chegada, id_carro, mostrar_carro, percurso) values ($idDono, '$origem', '$destino', '$dias', '$horario_saida', '$horario_chegada', $carro, $mostrar, '$percurso')";

		$resultado = $this->conexao->executa($sql);

		$sql2 = "select max(id_carona_base) as id from carona_base";
		$resultado2 = $this->conexao->consulta($sql2);


		$idCaronaBase = $resultado2[0]['id'] != null ? $resultado2[0]['id'] : 1;

		foreach ($carona->getDias() as $dia) {

			if($dia == 'seg'){
				$data = strtotime('next monday');
			}
			else if($dia == 'ter'){
				$data = strtotime('next tuesday');
			}
			else if($dia == 'qua'){
				$data = strtotime('next wednesday');
			}
			else if($dia == 'qui'){
				$data = strtotime('next thursday');
			}
			else if($dia == 'sex'){
				$data = strtotime('next friday');
			}
			else if($dia == 'sab'){
				$data = strtotime('next saturday');
			}
			else if($dia == 'dom'){
				$data = strtotime('next sunday');
			}

			for($cont = 0; $cont < 4; $cont++){

				$data2 = date('Y-m-d', ($data + $cont * 7 * 24 * 60 * 60));

				$sql3 = "insert into carona (id_carona_base, dia) values ($idCaronaBase, '$data2')";
				$resultado3 = $this->conexao->executa($sql3);
			}
		}
		return $resultado;
    }

    public function listarCaronas($filtro = null){


		$sql = "SELECT *, id_carona_base as id, (SELECT max(dia) from carona WHERE carona.id_carona_base = id) as dia_final from carona_base where ativo = true $filtro ";

		$resultado = $this->conexao->consulta($sql);

		if($resultado){

			foreach ($resultado as $carona) {

				$dono = $this->getUsuario($carona['id_usuario']);
				$idCaronaBase = $carona['id_carona_base'];
				$dias = $carona['dias'];
				$origem = $carona['origem'];
				$destino = $carona['destino'];
				$horarioSaida = $carona['horario_saida'];
				$horarioChegada = $carona['horario_chegada'];
				$carro = $this->getCarro($carona['id_carro']);

				$c = new Carona($dono, $origem, $destino, $carro, $horarioSaida, $horarioChegada, $dias);
				$c->setIdCarona($idCaronaBase);
				$retorno[] = $c;
			}

			return $retorno;

		}else{
			return false;
	 	}

	}

	public function getCarona($idCarona){

        $sql = "select * from carona_base where id_carona_base = $idCarona;";

		$carona = $this->conexao->consulta($sql);


		$dono = $this->getUsuario($carona[0]['id_usuario']);
		$idCaronaBase = $carona[0]['id_carona_base'];
		$dias = $carona[0]['dias'];
		$origem = $carona[0]['origem'];
		$destino = $carona[0]['destino'];
		$saida = $carona[0]['horario_saida'];
		$chegada = $carona[0]['horario_chegada'];
		$carro = $this->getCarro($carona[0]['id_carro']);
		$mostrarCarro = $carona[0]['mostrar_carro'];
		$percurso = $carona[0]['percurso'];
		$ativo = $carona[0]['ativo'];

		$c = new Carona($dono, $origem, $destino, $carro, $saida, $chegada, $dias, $mostrarCarro);
		$c->setIdCarona($idCaronaBase);
		$c->setPercurso($percurso);
		$c->setAtivo($ativo);

        return $c;

	}

	public function listarCaronaSub($idCaronaBase){

		$sql = "select * from carona where id_carona_base = $idCaronaBase and dia>= CURDATE() order by dia";

		$resultado = $this->conexao->consulta($sql);

		if($resultado){

			foreach ($resultado as $carona) {
				$dia = $carona['dia'];
				$idCarona = $carona['id_carona'];
				$lotada = $carona['lotada'];

				$c = new CaronaSub($idCarona, $idCaronaBase, $dia);
				$c->setLotada($lotada);
				$sql2 = "select id_usuario from usuario_carona natural join usuario where id_carona = $idCarona and ativo = true";
				$resultado2= $this->conexao->consulta($sql2);

				if($resultado2){
					$u = Array();
					foreach ($resultado2 as $usuario) {
						$u[] = $this->getUsuario($usuario[0]);
					}
					$c->setUsuarios($u);
				}

				$retorno[] = $c;
			}

	        return $retorno;

		}else{
			return false;
		}
	}

    public function pedirCarona($usuario, $carona, $mensagem, $dia, $tipo){

		include_once "Mensagem.class.php";
		$assunto = "Pedido de Carona";

		$m = $mensagem."<form action='processa.php' method='post'>
						<input type='hidden' name='acao' value='aceitar_pedido'>
						<input type='hidden' name='id_carona' value='{$carona->getIdCarona()}'>
						<input type='hidden' name='id_pedindo' value='{$usuario->getId()}'>
						<input type='hidden' name='dia' value=\"$dia\">
						<input type='hidden' name='tipo' value='$tipo'>
						<input type='submit' class='btn btn-raised btn-primary' value='Aceitar'>
						</form>";

		$destinatario[] = $carona->getDono();

		$mensagem = new Mensagem($usuario, $assunto, $m, $destinatario);

		$resultado = $this->enviarMensagem($mensagem);

		if($resultado){
			alert("Pedido enviado", "location='index.php'");
		}else{
			alert("ocorreu um erro");
		}

    }

	public function getCaronasUsuario($usuario){
		$idUsuario = $usuario->getId();
        $sql = "select * from carona_base where id_usuario = $idUsuario";

		$caronas = $this->conexao->consulta($sql);

		foreach ($caronas as $carona) {
			$dono = $this->getUsuario($carona['id_usuario']);
			$idCaronaBase = $carona['id_carona_base'];
			$dias = $carona['dias'];
			$origem = $carona['origem'];
			$destino = $carona['destino'];
			$saida = $carona['horario_saida'];
			$chegada = $carona['horario_chegada'];
			$carro = $this->getCarro($carona['id_carro']);
			$ativo = $carona['ativo'];
			$percurso = $carona['percurso'];

			$c = new Carona($dono, $origem, $destino, $carro, $saida, $chegada, $dias);
			$c->setIdCarona($idCaronaBase);
			$c->setAtivo($ativo);
			$c->setPercurso($percurso);

			$retorno[] = $c;
		}

        return $retorno;

	}

	public function deletarCarona($id_carona){

		$sql = "update carona_base set ativo = 0 where id_carona_base = $id_carona";

		$resultado = $this->conexao->executa($sql);

		$sql2 = "select distinct id_usuario from usuario_carona where id_carona in (select id_carona from carona where id_carona_base = $id_carona)";

		if ($resultado){

			$nome = $_SESSION['user']->getNome(). " " . $_SESSION['user']->getSobrenome();
			$resultado2 = $this->conexao->consulta($sql2);

			foreach ($resultado2 as $id) {
				$u[] = $this->getUsuario($id['id_usuario']);
			}
			$m = new Mensagem($_SESSION['sistema'], "Caronas excluída", "A carona que você usava, do usuário $nome foi excluída", $u);
			$r = $this->enviarMensagem($m);

			return true;
		}else{
			return false;
		}
	}

	public function enviarEmail($destinatario, $assunto, $mensagem){

		$email = $destinatario->getEmail();
		$nome = $destinatario->getNome();

		require 'PHPMailer/PHPMailerAutoload.php';

		$mail = new PHPMailer;
		$mail->CharSet = 'utf-8';
		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'caronasifrs@gmail.com';                 // SMTP username
		$mail->Password = 'fernandoetais';                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;                                    // TCP port to connect to

		$mail->setFrom('caronasifrs@gmail.com', 'Caronas IF');
		$mail->addAddress($email, $nome);     // Add a recipient

		$mail->addReplyTo('caronasifrs@gmail.com', 'Caronas IF');
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');

		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = $assunto;
		$mail->Body    = $mensagem;

		if(!$mail->send()) {

			//echo 'Mailer Error: ' . $mail->ErrorInfo;
			return false;
		} else {
			return true;
		}
	}

	public function avisarSaida($carona){

		$id = $carona->getIdCarona();
		$sql = "select id_usuario from usuario_carona where id_carona in (select id_carona from carona where id_carona_base = $id)";

		$resultado = $this->conexao->consulta($sql);

		foreach ($resultado as $dest) {
			$destinatario[] = $this->getUsuario($dest[0]);
		}

		$mensagem = "Olá, estou saindo. Logo passarei aí.";

		$assunto = "Saindo para carona";

		$remetente = $_SESSION['user'];

		$m = new Mensagem($remetente, $assunto, $mensagem, $destinatario);

		$resultado2 = $this->enviarMensagem($m);

		if($resultado2){
			return true;
		}else{
			return false;
		}

	}

	public function aceitarPedido($id_carona, $id_usuario, $dia, $tipo){

		if($tipo == 'um'){

			$sql = "select id_carona, lugares from carona, carona_base, carro where carona_base.id_carona_base = $id_carona and dia = $dia and carona_base.id_carro = carro.id_carro  and carona.id_carona_base = carona_base.id_carona_base";

			$resultado = $this->conexao->consulta($sql);

			$id_carona_final = $resultado[0]['id_carona'];

			$sql2 = "insert into usuario_carona values ($id_carona_final, $id_usuario)";

			$resultado2 = $this->conexao->executa($sql2);

		}
		else if($tipo == 'varios'){

			$d = $dia==0 ? 0 : --$dia;

			$sql = "select id_carona from carona where id_carona_base = $id_carona and weekday(dia) in ($d)";

			$resultado = $this->conexao->consulta($sql);

			foreach ($resultado as $r) {
				$id_carona_final = $r['id_carona'];

				$sql2 = "insert into usuario_carona values ($id_carona_final, $id_usuario)";

				$resultado2 = $this->conexao->executa($sql2);
			}
		}

		if($resultado2){
			return true;
		}else{
			return false;
		}

	}

}

?>
