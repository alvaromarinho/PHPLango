<?php 

require_once "vendor/path.php";
require_once "vendor/Maker.php";
require_once "vendor/Template.class.php";
require_once 'vendor/php-activerecord/ActiveRecord.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);

$user       = 'root';
$password   = '';
$database   = 'phplango';
$server     = 'localhost';

$maker 		= new Maker();

$mod        = 0777;
$project    = strtolower(PROJECT);
$sidebar    = '';

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
		<link rel="icon" href="<?= IMG.'icon.png' ?>" />
		<!--[if IE]><link rel="shortcut icon" href="<?= IMG.'icon.ico' ?>"><![endif]-->
		<link rel="stylesheet" href="<?= CSS.'PHPLango.css' ?>">
		<link rel="stylesheet" href="<?= CSS.'bootstrap.min.css' ?>">
		<style>
			.font {font-size: 2rem; font-weight: 300; line-height: 1.2; margin: 0;}
		</style>
	</head>
	<body>
		<nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
			<a class="navbar-brand" href="<?= ROOT ?>"><?= PROJECT ?></a>
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

			<div class="row my-3 mx-1">
				<div class="col-12">
					<p class="font">Tables:</p>
				</div>
			</div>

			<div class="row m-3">
				<?php foreach ($tables as $table) { echo "<div class='col-2 border px-3 py-2'>".ucwords($table['name'])."</div>"; } ?>
			</div>
					
			<form method="post" action="">
				<div class="row my-3 mx-1">
					<div class="col-12">
						<div class="form-group row">
							<label for="overwrite" class="col-3 font">Overwrite files:</label>
							<div class="col-2">
								<select class="form-control" name="overwrite" id="overwrite">
									<option value="N">NO</option>
									<option value="Y">YES</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="row my-3 mx-1">
					<div class="col-12">
						<div class="form-group row">
							<div class="col-1 text-center">
								<button class="btn btn-primary btn-block">Make</button>
							</div>
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

		<script src="<?= JS.'jquery-3.2.1.slim.min.js' ?>"></script>
		<script src="<?= JS.'bootstrap.min.js' ?>"></script>
	</body>
</html>
<?php 
if(!empty($_POST)){
	foreach ($tables as $table) {
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
		$controller_config = array(
			'name'         => $controller_name,
			'model'        => $model_name,
			'table'        => $table['name'],
			'relationship' => $table_columns
		);
		/* CONTROLLER */
		if(!file_exists(CONTROLLERS.$controller_name.'.php') || $_POST['overwrite'] == 'Y') {
			$maker->setHtmlController($controller_config);
			file_put_contents(CONTROLLERS.$controller_name.'.php', $maker->getHtmlController());
			chmod(CONTROLLERS.$controller_name.'.php', $mod);
		}
		$relationship = json_decode($table['relationship'], true);
		$model_config = array(
			'name'         => $model_name,
			'relationship' => $relationship,
			'null'         => $null
		);
		/* MODEL */
		if(!file_exists(MODELS.$model_name.'.php') || $_POST['overwrite'] == 'Y') {
			$maker->setHtmlModel($model_config);
			file_put_contents(MODELS.$model_name.'.php', $maker->getHtmlModel());
		}
		/* VIEW */
		if(!is_dir(VIEWS.$view_folder_name))
			mkdir(VIEWS.$view_folder_name, $mod);
		chmod(VIEWS.$view_folder_name, $mod);
		/* index */
		if(!file_exists(VIEWS.$view_folder_name.DS.'index.php') || $_POST['overwrite'] == 'Y') {
			$maker->setHtmlIndex($table['name'], $describe);
			file_put_contents(VIEWS.$view_folder_name.DS.'index.php', $maker->getHtmlIndex());
			chmod(VIEWS.$view_folder_name.DS.'index.php', $mod);
		}
		/* create */
		if(!file_exists(VIEWS.$view_folder_name.DS.'create.php') || $_POST['overwrite'] == 'Y') {
			$maker->setHtmlCreate($model_name, $table['name'], $describe);
			file_put_contents(VIEWS.$view_folder_name.DS.'create.php', $maker->getHtmlCreate());
			chmod(VIEWS.$view_folder_name.DS.'create.php', $mod);
		}
		$maker->setHtmlSidebar($table['name']);
		$sidebar .= $maker->getHtmlSidebar();

		/* CLEAN ARRAYS */
		$describe           = array();
		$table_columns      = array();
		$null               = array();
		
		/* DELETING EMPTY FILES */
		if(file_exists(CONTROLLERS.'empty'))
			unlink(CONTROLLERS.'empty');
		if(file_exists(MODELS.'empty'))
			unlink(MODELS.'empty');
	}
	/* SIDEBAR */
	if(file_exists(VIEWS.'Elements'.DS.'sidebar.php'))
		file_put_contents(VIEWS.'Elements'.DS.'sidebar.php', $sidebar);
	$connection->close();
	header("location:".ROOT);
} 
