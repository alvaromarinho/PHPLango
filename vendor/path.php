<?php 

$arrayDir = explode(DIRECTORY_SEPARATOR, dirname(__DIR__));

define('DS', DIRECTORY_SEPARATOR);
define('PROJECT', end($arrayDir));
define('PROTOCOL', isset($_SERVER['HTTPS']) ? 'https://' : 'http://');

/* PROD */
// define('ROOT', '/');

/* DEV */
define('SERVER', PROTOCOL.substr($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'], 0, strpos($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'], PROJECT)-1));
define('ROOT', SERVER.DS.PROJECT.DS);

define('PATH', dirname(__DIR__).DS);
define('CSS', ROOT.'public'.DS.'css'.DS);
define('JS', ROOT.'public'.DS.'js'.DS);

define('CONTROLLERS', PATH.'app'.DS.'Controllers'.DS);
define('MODELS', PATH.'app'.DS.'Models'.DS);
define('VIEWS', PATH.'app'.DS.'Views'.DS);
define('IMG', PATH.'public'.DS.'img'.DS);
define('ELEMENTS', VIEWS.'Elements'.DS);
