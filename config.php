<?php 

require_once 'vendor/php-activerecord/ActiveRecord.php';

$user       = 'root';
$password   = '';
$database   = 'phplango';
$server     = 'localhost';

$connections = array(
    'database' => 'mysql://'.$user.':'.$password.'@'.$server.'/'.$database
);

ActiveRecord\Config::initialize(function($cfg) use ($connections)
{
	$cfg->set_model_directory(MODELS);
	$cfg->set_connections($connections);  
	$cfg->set_default_connection('database');
});

// function autoLoadClass($Class)
// {
//     $cDir = [CONTROLLERS];
//     $iDir = null;

//     foreach ($cDir as $dirName) {	
//         if (!$iDir && file_exists($dirName.$Class.'.php') && !is_dir($dirName.$Class.'.php')){
//         	include_once($dirName.$Class.'.php');
//             $iDir = true;
//         }
//     }
// }

// spl_autoload_register("autoLoadClass");