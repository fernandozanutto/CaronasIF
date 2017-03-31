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
        <link rel='stylesheet' href='css/bootstrap-material-design.css' />
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" >
		<link rel="stylesheet" href="css/jquery.dropdown.css">
		<link rel='stylesheet' href='css/bootstrap-datepicker3.min.css' />
        <link rel='stylesheet' href='css/style.css' />
        <link rel="stylesheet" href="css/ripples.min.css">
    </head>
<?php


include_once 'Usuario.class.php';
session_start();


if(!isset($_SESSION['user'])){
	header('location: login.php');
}
include_once 'funcoes.php';
if($_SESSION['user']->getTipoUsuario() == "A"){
    require_once "ControleAdmin.class.php";
    $controle = new ControleAdmin();
}else{
    require_once "ControleUsuario.class.php";
    $controle = new ControleUsuario();
}

$nome = $_SESSION['user']->getNome();
$id = $_SESSION['user']->getId();

$admin='';

if($_SESSION['user']->getTipoUsuario() == "A"){
    $admin = "<span><h6 id='admin' style='display: inline; margin:0;'>Administrador</h6></span>";
}
 
echo"
<body>

    <nav class='navbar cabecalho'>
        <div class='container-fluid'>
            <div class='nav navbar-header'>
                <button id='botao' type='button' class='navbar-toggle' data-toggle='collapse' data-target='#myNavbar'>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </button>

                <a id='logo' class='navbar-brand' href='index.php'>
                    <img src='imagens/logo.svg'>
                    <h2>Caronas IF $admin</h2>
                </a>
            </div>

            <div class='collapse navbar-collapse' id='myNavbar'>
                <ul class='nav navbar-nav navbar-right'>

                    <li class='dropdown'>
                        <a href='perfil.php?id=$id'><span class='glyphicon glyphicon-user'></span> <u>Bem Vindo, $nome</u></a>
                    	<li><a href='processa.php?acao=logout'><span class='glyphicon glyphicon-log-out'></span> Logout</a>
					</li>
                </ul>
            </div>
			<!--
            <ol class='breadcrumb' style='margin-bottom: 5px;'>
				<li class='breadcrumb-item active'>Home</li>
			</ol>
			<ol class='breadcrumb'>
				<li class='breadcrumb-item javascript:void(0)'><a href='#'>Home</a></li>
				<li class='breadcrumb-item active'>Library</li>
			</ol>
			<ol class='breadcrumb'>
				<li class='breadcrumb-item'><a href='#'>Home</a></li>
				<li class='breadcrumb-item'><a href='#'>Library</a></li>
				<li class='breadcrumb-item active'>Data</li>
			</ol>
			--!>
        </div>

    </nav>
	<div class='container-fluid'>
		<div class='row'>
";


?>
