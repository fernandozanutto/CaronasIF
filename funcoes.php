<?php
function print_array($array){
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

function alert($string, $op=null){
	echo "<script>alert('$string'); $op</script>";
}

function verificarPOST(){
    $retorno = true;
	foreach ($_POST as $i => $x) {
		if(!is_array($x)){
	        if($i != 'telefone' && $i != 'percurso' && $x == ''){
	            $retorno = false;
	        }else{
				$_POST[$i] = addslashes($x);
			}
		}
    }
	return $retorno;
}

function weektostr($w){
	switch ($w){
		case '1':
			$dia = "Segunda";
			break;
		case '2':
			$dia = "Terça";
			break;
		case '3':
			$dia = "Quarta";
			break;
		case '4':
			$dia = "Quinta";
			break;
		case '5':
			$dia = "Sexta";
			break;
		case '6':
			$dia = "Sábado";
			break;
		case '0':
			$dia = "Domingo";
			break;
		default:
			$dia = '';
	}
	return $dia;
}

function strtoweekday($day){
	switch ($day){
		case 'dom':
			$dia = 0;
			break;
		case 'seg':
			$dia = 1;
			break;
		case 'ter':
			$dia = 2;
			break;
		case 'qua':
			$dia = 3;
			break;
		case 'qui':
			$dia = 4;
			break;
		case 'sex':
			$dia = 5;
			break;
		case 'sab':
			$dia = 6;
			break;
		default:
			$dia = '';
	}
	return $dia;
}

?>
