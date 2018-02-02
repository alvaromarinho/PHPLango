<?php 

class Auth
{
	public static function login()
	{
		if(isset($_SESSION['email']) && isset($_COOKIE['PHPLAP'])){
			$user = User::first(['conditions' => ['username = ?', $_SESSION['email']] ]);
			if($user->password == hex2bin($_COOKIE['PHPLAP']))
				return true;
			else
				$_SESSION["message"] = "Authentication error occurred!";
		} else if(!empty($_POST)) {
			$user = User::first(['conditions' => ['username = ?', $_POST['username'] ]]);
			if (crypt($_POST['password'], $user->password) == $user->password) {
				$_SESSION['auth']  = true;
				$_SESSION['email'] = $user->username;
				setcookie('PHPLAP', bin2hex($user->password), time()+60*60*2);
				return true;
			} else 
				$_SESSION["message"] = "Wrong username or password!";
		}
		$_SESSION["class"] = "danger";
		return false;
	}

	public static function logoff()
	{
		unset($_SESSION['auth'], $_SESSION['email']);
		setcookie('PHPLAP', '', 1);
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