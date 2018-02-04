<?php 

class PHPLango
{
	public static function checkUrl($_mvc, $_url)
	{
		if(!isset($_url[1]) && $_url[0] == PROJECT)
			return true;
		elseif ($_url[1] == 'logoff')
			Auth::logoff();
		else {
			$_mvc->setViewFolder(ActiveRecord\classify($_url[1]));
			$_mvc->setModel(ActiveRecord\classify($_url[1], true));
			$_mvc->setController($_mvc->getViewFolder()."Controller");
			$_mvc->setAction(isset($_url[2]) && !empty($_url[2]) ? $_url[2] : "index");

			if (!empty($_mvc->getController()) && file_exists(CONTROLLERS.$_mvc->getController().".php")){
				if(file_exists(MODELS.$_mvc->getModel().".php")) {
					require_once CONTROLLERS.$_mvc->getController().".php";
					if(in_array($_mvc->getAction(), get_class_methods($_mvc->getController()))) {
						return true;
					} else
						$_SESSION["message"] = "Action not found on the controller!";
				} else
					$_SESSION["message"] = "Model not found!";
			} else
				$_SESSION["message"] = "Controller not found!";
			$_SESSION["class"] = "danger";
		}
		return false;
	}

	public static function redirect($_mvc, $_url)
	{
		if(isset($_url[3]))
			$_mvc->setParameters($_url[3]);
		
		require_once CONTROLLERS.$_mvc->getController().".php";

		try{
			$_controller = $_mvc->getController();
			$_action 	 = $_mvc->getAction();
			$_parameters = $_mvc->getParameters();
			$_result 	 = $_controller::$_action($_parameters);
			unset($_controller, $_action, $_parameters, $_url);
		} catch (Exception $e) {
			return array("title" => "Erro", "message" => $e->getMessage());
		}
		
		if(isset($_result["message"])){
			$_SESSION["message"] = $_result["message"];
			$_SESSION["class"]   = $_result["class"] ?: "dark";
		}

		if(isset($_result["redirect"])){
			$_redirect = $_result["redirect"] ?: ROOT;
			header("location:".$_redirect);
		} 

		else if(isset($_result["view"]) && !empty($_result["view"]) && file_exists(VIEWS.$_mvc->getViewFolder().DS.$_result["view"].".php")){
			$_mvc->setViewFile($_result["view"]);
			if(isset($_result["data"]))
				foreach ($_result["data"] as $key => $value)
					${$key} = $value;
			unset($_result);
			require_once VIEWS.$_mvc->getViewFolder().DS.$_mvc->getViewFile().".php";
		} 

		else if(file_exists(VIEWS.$_mvc->getViewFolder().DS.$_mvc->getAction().".php")) {
			if(isset($_result["data"]))
				foreach ($_result["data"] as $key => $value)
					${$key} = $value;
			unset($_result);
			require_once VIEWS.$_mvc->getViewFolder().DS.$_mvc->getAction().".php";
		}
	}
}
