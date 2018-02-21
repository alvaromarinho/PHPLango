<?php 

require_once 'vendor/php-activerecord/ActiveRecord.php';

$user       = 'root';
$password   = '';
$database   = 'phplango';
$server     = 'localhost';

$connections = array(
    'database' => 'mysql://'.$user.':'.$password.'@'.$server.'/'.$database.'?charset=utf8'
);

ActiveRecord\Config::initialize(function($cfg) use ($connections)
{
	$cfg->set_model_directory(MODELS);
	$cfg->set_connections($connections);  
	$cfg->set_default_connection('database');
});
