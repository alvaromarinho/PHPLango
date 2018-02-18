<?php 

class Auth
{
	function __construct()
	{
		ini_set('session.use_cookies', 1);
		ini_set('session.use_only_cookies', 1);
		ini_set('session.use_trans_sid', 0);
	}
	
	public static function login()
	{

		/* SE EXISTE O MODEL USER */
		if(!class_exists('User') && !empty($_POST)){
			$_SESSION["message"] = "Model Users not found!";
			$_SESSION["class"]   = "danger";
		}

		/* REALIZANDO A AUTENTICAÇÃO */
		else if(isset($_POST['_auth'])) {
			$user = User::first(['select' => 'id, username, password', 'conditions' => ['username = ?', $_POST['username'] ]]);
			if (crypt($_POST['password'], $user->password) == $user->password) {
				$_SESSION['auth']    = true;
				$_SESSION['email']   = $user->username;
				$_SESSION['user_id'] = $user->id;
				setcookie('PHPLAP', bin2hex($user->password), time()+60*60*1, '/'.PROJECT);
				return true;
			} else {
				$_SESSION["message"] = "Wrong username or password!";
				$_SESSION["class"]   = "danger";
			}
		}

		/* VERIFICANDO A AUTENTICAÇÃO */
		else if(isset($_SESSION['auth'])){
			if(isset($_COOKIE['PHPLAP'])) {
				$user = User::first(['select' => 'id, username, password', 'conditions' => ['username = ?', $_SESSION['email']] ]);
				if($user->password == hex2bin($_COOKIE['PHPLAP'])) {
					setcookie('PHPLAP', bin2hex($user->password), time()+60*60*1, '/'.PROJECT);
					return true;
				} 
			} else {
				$_SESSION["message"] = "Authentication error occurred!";
				$_SESSION["class"]   = "danger";
				self::logoff(false);
			}
		}

		return false;
	}

	public static function logoff($redirect = true)
	{
		unset($_SESSION['auth'], $_SESSION['email'], $_SESSION['user_id']);
		setcookie('PHPLAP', '', 1);
		if($redirect)
			header("location:".ROOT);
	}

	public static function cryptPass($password)
	{
		$salt      = uniqid();
		$algo      = '6'; // CRYPT_SHA512
		$rounds    = '5042';
		$cryptSalt = '$'.$algo.'$rounds='.$rounds.'$'.$salt.'$';
		return crypt($password, $cryptSalt);
	}
}