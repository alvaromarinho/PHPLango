<?php

if (!isset($_SESSION)) { session_start(); }

error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR| E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR);
// error_reporting(E_ALL);
ini_set("display_errors", 1);

header('Content-Type: text/html; charset=utf-8'); 
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

require_once "../vendor/path.php";
require_once "../config.php";
require_once "../vendor/PHPLango.class.php";
require_once "../vendor/Template.class.php";
require_once "../vendor/Mvc.php";

$_mvc = new Mvc();
$_url = explode("/", str_replace("/usuario/alvaro", "", $_SERVER['REQUEST_URI']));

if(PHPLango::checkUrl($_mvc, $_url))
	if(PHPLango::login())
		if(empty($_url[2]))
			require_once VIEWS."start.php";
		else
			PHPLango::redirect($_mvc, $_url);
	else
		require_once VIEWS."login.php";
else
	require_once VIEWS."error.php";
