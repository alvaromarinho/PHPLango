<?php

if (!isset($_SESSION)) { session_start(); }

error_reporting(E_ALL);
ini_set("display_errors", 1);

header('Content-Type: text/html; charset=utf-8'); 
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

require_once "../vendor/path.php";
require_once "../config.php";
require_once "../vendor/Mvc.php";
require_once "../vendor/PHPLango.class.php";
require_once "../vendor/Template.class.php";
require_once "../vendor/Auth.class.php";

$_mvc = Mvc::getInstance();
// $_uri = str_replace("/PHPLango", "", $_SERVER['REQUEST_URI']);
$_uri = $_SERVER['REQUEST_URI'];
$_uri = explode("/", trim($_uri, "/"));

if(PHPLango::checkUrl($_mvc, $_uri))
	if(Auth::login())
		if(empty($_uri[0]))
			require_once VIEWS."start.php";
		else
			PHPLango::redirect($_mvc, $_uri);
	else
		require_once VIEWS."login.php";
else
	require_once VIEWS."error.php";
