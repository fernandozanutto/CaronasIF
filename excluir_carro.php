<?

include_once 'cabecalho.php';


$id_usuario = $_SESSION['user']->getId();

$id_carona = $_GET['id'];

$controle->deletar_carro($id_carona, $id_usuario);

?>
