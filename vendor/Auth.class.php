<?php 

require_once "php-jwt/BeforeValidException.php";
require_once "php-jwt/ExpiredException.php";
require_once "php-jwt/SignatureInvalidException.php";
require_once "php-jwt/JWT.php";

use \Firebase\JWT\JWT;

class Auth
{
	private static $key = "SUA_KEY";
	
	public static function login()
	{
		/* SE EXISTE O MODEL USER */
		if(!class_exists('User') && !empty($_POST)){
			$_SESSION["message"] = "Model Users not found!";
			$_SESSION["class"]   = "danger";
		}

		/* REALIZANDO A AUTENTICAÇÃO */
		else if(isset($_POST['auth'])) {
			$user = User::first(['select' => 'id, password', 'conditions' => ['username = ?', $_POST['username'] ]]);
			if (isset($user) && crypt($_POST['password'], $user->password) == $user->password) {
				$config = array(
					"iat"  => time(), 					// time when the token was generated
			        "exp"  => time() + 60*60* 8,        // time when the token was expired	
					"iss"  => $_SERVER['SERVER_NAME'],	// A string containing the name or identifier of the application
				);

				$token = JWT::encode($config, self::$key, 'HS512');
				$_SESSION['auth'] 	 = true;
				$_SESSION['user_id'] = $user->id;
				setcookie('PHPLango', bin2hex($token), time() + 60*60* 8);
				return true;
			} else {
				$_SESSION["message"] = "Wrong username or password!";
				$_SESSION["class"]   = "danger";
			}
		}

		/* VERIFICANDO A AUTENTICAÇÃO */
		else if(isset($_SESSION['auth']) && isset($_COOKIE['PHPLango'])){
			$token = hex2bin($_COOKIE['PHPLango']);
			try {
				JWT::decode($token, self::$key, array('HS512'));
				return true;
			} catch (\Firebase\JWT\ExpiredException $e) {
				$_SESSION["message"] = "Authentication error occurred!";
				$_SESSION["class"]   = "danger";
			}
		} 

		self::logoff(false);
		return false;
	}

	public static function logoff($redirect = true)
	{
		setcookie('PHPLango', '', 1);
		unset($_SESSION['auth'], $_SESSION['user_id']);
		if($redirect)
			header("location:".ROOT);
	}

	public static function cryptPass($password)
	{
		$salt      = uniqid();
		$rounds    = '5042';
		$cryptSalt = '$6$rounds='.$rounds.'$'.$salt.'$';
		return crypt($password, $cryptSalt);
	}
}