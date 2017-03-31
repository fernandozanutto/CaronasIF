<?php
$soma;
$arquivos=(scandir('./'));
foreach ($arquivos  as $a ) {
  $name="./".$a;
  if(substr($name, -3) == 'php'){
	  $linhas = count( file( $name ) );
	  print_r($linhas);
	  echo "<br>";
	  $soma+=$linhas;
  }

}
echo $soma;
?>
