<?php 

require_once "vendor/path.php";
require_once "vendor/Maker.php";
require_once "vendor/Template.class.php";
require_once "vendor/Auth.class.php";
require_once 'vendor/php-activerecord/ActiveRecord.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);

$user       	= 'root';
$password   	= '';
$database   	= 'phplango';
$server     	= 'localhost';

$maker 		= new Maker();

$mod        	= 0777;
$project    	= strtolower(PROJECT);
$sidebar    	= '';

$connection = new mysqli($server, $user, $password, $database);
if (mysqli_connect_errno()) {
	echo "Connect failed: ".mysqli_connect_error();
	exit();
}
$query  = "SELECT table_name AS 'name', table_comment AS 'relationship' FROM information_schema.tables WHERE table_schema = SCHEMA()";
$query  = $connection->query($query);
while ($result = $query->fetch_array(MYSQLI_ASSOC))
	$tables[] = $result;
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<title>Maker - <?= PROJECT ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="<?= 'img/icon.png' ?>" />
		<!--[if IE]><link rel="shortcut icon" href="<?= 'img/icon.ico' ?>"><![endif]-->
		<link rel="stylesheet" href="<?= CSS.'PHPLango.css' ?>">
		<link rel="stylesheet" href="<?= CSS.'bootstrap.min.css' ?>">

		<style>
			.btn input[type=checkbox] { position: absolute; clip: rect(0,0,0,0); pointer-events: none; }
			.card .bg-dark { color: #fff; }
			.btn-checkbox label { margin-bottom: 0px; }
		</style>
	</head>
	<body>
		<nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
			<a class="navbar-brand" href="<?= ROOT ?>">PHPLango</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="collapsibleNavbar">
				<span class="navbar-text">...a fento-framework PHP</span>
			</div> 
		</nav>
		<div class="container">

			<div class="row pt-3 mb-3">
				<div class="col-12">
					<h1 class="display-4">Maker script</h1>
				</div>
			</div>

			<form method="post" action="">

				<div class="form-group row">
					<div class="col-12">
						<div class="card border-dark">
							<div class="card-header bg-dark">Tables</div>
							<div class="card-body">
								<div class="btn-checkbox" data-toggle="buttons">
									<div class="row">
										<?php foreach ($tables as $table) { 
											echo "<div class='col-3'><label class='btn btn-outline-secondary btn-block'><input type='checkbox' name='tables[]' id='".$table['name']."' value='".$table['name']."'>".ucwords($table['name'])."</label></div>"; 
										} ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-6">
						<div class="card border-dark">
							<div class="card-header bg-dark">Create/Overwrite</div>
							<div class="card-body">
								<div class="btn-checkbox" data-toggle="buttons">
									<div class="row">
										<div class="col-4">
											<label class='btn btn-outline-secondary btn-block' for="overwriteControllers">
												<input type='checkbox' name="overwriteControllers" id="overwriteControllers">Controllers
											</label>
										</div>
										<div class="col-4">
											<label class='btn btn-outline-secondary btn-block' for="overwriteModels">
												<input type='checkbox' name="overwriteModels" id="overwriteModels">Models
											</label>
										</div>
										<div class="col-4">
											<label class='btn btn-outline-secondary btn-block' for="overwriteViews">
												<input type='checkbox' name="overwriteViews" id="overwriteViews">Views
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="card border-dark">
							<div class="card-header bg-dark">Change ADMIN's password</div>
							<div class="card-body">
								<input type="password" name="adminPass" class="form-control" placeholder="Password">
							</div>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-12 mb-3">
						<div class="col-2 offset-5 text-center">
							<button class="btn btn-lg btn-primary btn-block">MAKE</button>
						</div>
					</div>
				</div>

			</form>

		</div>
		<footer class="footer bg-dark">
			<div class="container text-center">
				<span class="text-light">Copyright &copy; Alvin Kalango - 2017.</span>
			</div>
		</footer>

		<script src="<?= JS.'jquery-3.3.1.min.js' ?>"></script>
		<script src="<?= JS.'bootstrap.min.js' ?>"></script>
	</body>
</html>

<?php 

if(!empty($_POST)){
	foreach ($tables as $table) {

		/* VARIABLES */
		$describe      = array();
		$table_columns = array();
		$null          = array();

		$query    = "SELECT 
					column_name     AS 'field', 
					column_default  AS 'default', 
					is_nullable     AS 'null',  
					column_type     AS 'type', 
					column_key      AS 'key', 
					column_comment  AS 'comment'
				FROM information_schema.columns 
				WHERE table_name = '".$table['name']."' AND table_schema = SCHEMA()";
		$query  = $connection->query($query);
		while ($result = $query->fetch_array(MYSQLI_ASSOC))
			$describe[] = (object) $result;
		
		$controller_name  = ActiveRecord\classify($table['name']).'Controller';
		$model_name       = ActiveRecord\classify($table['name'], true);
		$view_folder_name = ActiveRecord\classify($table['name']);

		foreach ($describe as $key => $field){
			if(empty($field->default) && $field->null == "NO" && $field->field != "id")
				$null[] = $field->field;
			if($field->key == "MUL"){
				$array_table_name = explode("_", $field->field);
				unset($array_table_name[count($array_table_name)-1]);               
				$table_name = ActiveRecord\Utils::pluralize(implode("_", $array_table_name));
				$table_columns[$table_name] = $field->comment;
				$describe[$key]->key   = "";
				$describe[$key]->type  = "enum('\$".$table_name."')";
			}
		}

		if(in_array($table['name'], $_POST['tables'])) {

			/* CONTROLLER */
			if(isset($_POST['overwriteControllers'])) {
				$controller_config = array(
					'name'         => $controller_name,
					'model'        => $model_name,
					'table'        => $table['name'],
					'relationship' => $table_columns
				);
				
				$maker->setHtmlController($controller_config);
				file_put_contents(CONTROLLERS.$controller_name.'.php', $maker->getHtmlController());
				chmod(CONTROLLERS.$controller_name.'.php', $mod);
			}

			/* MODEL */
			if(isset($_POST['overwriteModels'])) {
				$relationship = json_decode($table['relationship'], true) ?: array();
				$model_config = array(
					'name'         => $model_name,
					'relationship' => $relationship,
					'null'         => $null
				);
				
				$maker->setHtmlModel($model_config);
				file_put_contents(MODELS.$model_name.'.php', $maker->getHtmlModel());
				chmod(MODELS.$model_name.'.php', $mod);	
			}

			/* VIEW */
			if(isset($_POST['overwriteViews'])) {
				$view_config = array(
					'columns' => $describe,
					'model'   => $model_name,
					'table'   => $table['name'],
				);
				if(!is_dir(VIEWS.$view_folder_name))
					mkdir(VIEWS.$view_folder_name, $mod);
				chmod(VIEWS.$view_folder_name, $mod);

				/* index */
				$maker->setHtmlIndex($view_config);
				file_put_contents(VIEWS.$view_folder_name.DS.'index.php', $maker->getHtmlIndex());
				chmod(VIEWS.$view_folder_name.DS.'index.php', $mod);

				/* create */
				$maker->setHtmlCreate($view_config);
				file_put_contents(VIEWS.$view_folder_name.DS.'create.php', $maker->getHtmlCreate());
				chmod(VIEWS.$view_folder_name.DS.'create.php', $mod);
			}

			/* SIDEBAR */
			$maker->setHtmlSidebar($table['name']);
			$sidebar .= $maker->getHtmlSidebar();
		}
		
		/* DELETING EMPTY FILES */
		if(file_exists(CONTROLLERS.'empty'))
			unlink(CONTROLLERS.'empty');
		if(file_exists(MODELS.'empty'))
			unlink(MODELS.'empty');
	}

	/* SIDEBAR */
	if(file_exists(VIEWS.'Elements'.DS.'sidebar.php'))
		file_put_contents(VIEWS.'Elements'.DS.'sidebar.php', $sidebar);

	$query  =  "SELECT id FROM users WHERE username = 'admin'";
	$query  = $connection->query($query);
	$result = $query->fetch_array(MYSQLI_ASSOC);

	if(!empty($_POST['adminPass'])) {
		$query  =  "UPDATE users SET password = '".Auth::cryptPass($_POST['adminPass'])."' WHERE username = 'admin'";
		$query  = $connection->query($query);
	}

	$connection->close();
	echo "<script>alert('Maker executed successfully!')</script>";
} 
