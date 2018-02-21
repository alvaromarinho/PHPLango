<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title><?= PROJECT ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= 'img/icon.png' ?>" />
	<!--[if IE]><link rel="shortcut icon" href="<?= 'img/icon.ico' ?>"><![endif]-->
	<link rel="stylesheet" href="<?= CSS.'bootstrap.min.css' ?>">
	<link rel="stylesheet" href="<?= CSS.'PHPLango.css' ?>">
</head>
<body>
	<?php require_once ELEMENTS."navbar.php"; ?>
	
	<!-- Modal -->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalTitle">...</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<nav class="col-sm-3 col-md-2 d-none d-sm-block p-0 sidebar bg-light">
				<ul class="nav nav-pills flex-column">
					<?php require_once ELEMENTS."sidebar.php"; ?>
				</ul>
			</nav>
			
			<main role="main" class="col-sm-9 ml-sm-auto col-md-10 mt-3">

