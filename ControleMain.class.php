<?php
include_once "Usuario.class.php";
include_once "./BD/MySQL.class.php";
include_once 'funcoes.php';


class ControleMain{

    public function logar($login, $senha){

	    $conexao = new MySQL();

		$sql = "select * from usuario where login = '$login' and senha = '$senha'";
		$resultado = $conexao->consulta($sql);

		if($resultado){

			if($resultado[0]['ativo'] == 1){

				$id = $resultado[0]['id_usuario'];
	            $nome = $resultado[0]['nome'];
	            $sobrenome= $resultado[0]['sobrenome'];
	            $login = $resultado[0]['login'];
	            $senha = $resultado[0]['senha'];
				$tempo_cadastro = $resultado[0]['tempo_cadastro'];
				$vinculo = $resultado[0]['vinculo'];
				$tipo_usuario = $resultado[0]['tipo_usuario'];
				$email = $resultado[0]['email'];
				$cidade = $resultado[0]['cidade'];
				$logradouro = $resultado[0]['logradouro'];
				$num_endereco = $resultado[0]['num_endereco'];
				$telefone = $resultado[0]['telefone'];

				$u = new Usuario($nome, $sobrenome, $login, $senha, $vinculo, $tipo_usuario, $email, $cidade, $logradouro, $num_endereco, $telefone);
				$u->setId($id);
				$u->setDataCadastro($tempo_cadastro);
				session_start();
				$_SESSION['user'] = $u;
				$_SESSION['sistema'] = new Usuario("Sistema", "Caronas IF", "", "", "", "A", "caronasifrs@gmail.com", "", "", "", "", "");
				$_SESSION['sistema']->setId(0);
				
				header('location: index.php');

			}
			else if($resultado[0]['ativo'] == 2){
				alert('Sua conta foi desativada pelo administrador. Uma mensagem foi enviada ao seu email avisando o motivo. Caso queira, pode entrar em contato pelo email: caronasifrs@gmail.com', "location='index.php'");
			}
			else{
				echo "
				<script>
					alert('Conta desativada, entre em contato com o administrator caso deseje ativá-la');
					window.location.href='login.php';
				</script>";
			}
		}else{

            alert('Não foi encontrado nenhum login e senha correspondentes', "location='login.php'");

		}
	}

	public function cadastrarUsuario($u){
		$conexao = new MySQL();

		$nome = $u->getNome();
		$sobrenome = $u->getSobrenome();
		$login = $u->getLogin();
		$senha = $u->getSenha();
		$vinculo = $u->getVinculo();
		$tipo_usuario = $u->getTipoUsuario();
		$email= $u->getEmail();
		$cidade = $u->getCidade();
		$logradouro = $u->getLogradouro();
		$num_endereco = $u->getNumEndereco();
		$telefone = $u->getTelefone();

		$sql = "insert into usuario (nome, sobrenome, login, senha, vinculo, tipo_usuario, email, cidade, logradouro, num_endereco, telefone)
		values ('$nome', '$sobrenome', '$login', '$senha', '$vinculo', '$tipo_usuario', lcase('$email'), '$cidade', '$logradouro', $num_endereco, '$telefone')";
		$resultado = $conexao->executa($sql);

		return $resultado;
	}

	public function recuperarSenha($email){

		$conexao = new MySQL();
		$sql = "select * from usuario where email = '$email'";
		$resultado = $conexao->consulta($sql);

		if($resultado){

			$nome = $resultado[0]['nome'];
			$login = $resultado[0]['login'];
			$senha = $resultado[0]['senha'];
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

			//$mail->addReplyTo('info@example.com', 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Recuperação de Senha - Caronas IF';
			$mail->Body    = "Olá $nome, <br /><br />Seu login é: $login <br>Sua senha é: $senha";
			$mail->AltBody = 'Login: '.$login. '  Senha: '.$senha;

			if(!$mail->send()) {

				//echo 'Mailer Error: ' . $mail->ErrorInfo;
				echo "<script>alert('Ocorreu um erro ao tentar enviar o email, tente novamente');
                window.location.href='recuperar_senha.php';</script>";
			} else {
				echo "<script>alert('Um email foi enviado contendo seu nome de usuário e senha');
                window.location.href='login.php';</script>";
			}
		}else {
			echo "<script>alert('Email não encontrado. Talvez este não seja o email que você cadastrou');
			window.location.href='login.php';</script>";
		}
	}

}

?>
