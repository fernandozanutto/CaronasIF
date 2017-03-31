<?php
ob_start( 'ob_gzhandler' );
 ?>
<!doctype html>
<html>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1.0' >
        <meta charset='utf-8' />
        <title>Caronas IF</title>

        <!-- FAVICON -->
		<link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
		<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="manifest.json">
		<link rel="mask-icon" href="safari-pinned-tab.svg" color="#009688">
		<meta name="theme-color" content="#009688">
		<!-- CSS -->
        <link rel='stylesheet' href='css/bootstrap.css' />
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" >
		<link href="css/jquery.dropdown.css" rel="stylesheet">
        <link rel='stylesheet' href='css/bootstrap-material-design.css' />
        <link rel='stylesheet' href='css/style.css' />
		<link href="css/ripples.min.css" rel="stylesheet">
    </head>
<?php
include_once 'funcoes.php';
echo"
<body>

    <nav class='navbar cabecalho'>
        <div class='container-fluid'>
            <div class='nav navbar-header'>
                <a id='logo' class='navbar-brand' href='index.php'>
                    <img src='imagens/logo.svg' style='display: inline-block; height: 3em;'>
                    <h2 style='display: inline-block; vertical-align:middle; margin:0;'>Caronas IF</h2>
                </a>
            </div>
        </div>
    </nav>

	<div class='container'>
		<div class='row'>
";


?>
