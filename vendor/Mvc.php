<?php 

class Mvc
{
	private $_view_folder;
	private $_view_file;
	private $_model;
	private $_controller;
	private $_action;
	private $_parameters;
	static private $_instance;

	/* Construtor do tipo protegido previne que uma nova instância da Classe seja criada através do operador `new` de fora dessa classe. */
	protected function __construct(){}

	/* Método clone do tipo privado previne a clonagem dessa instância da classe */
	private function __clone(){}

	/* Método unserialize do tipo privado para prevenir a desserialização */
	private function __wakeup(){}

	public static function getInstance()
	{
		if (!isset(self::$_instance)){
			self::$_instance = new Mvc();
		}
		return self::$_instance;
	}

	public function getViewFolder()
	{
		return $this->_view_folder;
	}

	public function setViewFolder($value)
	{
		$this->_view_folder = $value;
	}

	public function getViewFile()
	{
		return $this->_view_file;
	}

	public function setViewFile($value)
	{
		$this->_view_file = $value;
	}

	public function getModel()
	{
		return $this->_model;
	}

	public function setModel($value)
	{
		$this->_model = $value;
	}

	public function getController()
	{
		return $this->_controller;
	}

	public function setController($value)
	{
		$this->_controller = $value;
	}

	public function getAction()
	{
		return $this->_action;
	}

	public function setAction($value)
	{
		$this->_action = $value;
	}

	public function getParameters()
	{
		return $this->_parameters;
	}

	public function setParameters($value)
	{
		if(strstr($value, '&')) {
			$_array_parameter = explode("&", $value);
			if(strstr($value, '=')) {
				foreach ($_array_parameter as $parameter) {
					$values = explode("=", $parameter);
					$_parameters[reset($values)] = end($values);
				}
			} else {
				$_parameters = $_array_parameter;
			}
		} else {
			$_parameters = $value;
		}

		$this->_parameters = $_parameters;
	}
}