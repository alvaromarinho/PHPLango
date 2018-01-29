<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title><?= PROJECT ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= IMG.'icon.png' ?>" />
    <!--[if IE]><link rel="shortcut icon" href="<?= IMG.'icon.ico' ?>"><![endif]-->
    <link rel="stylesheet" href="<?= CSS.'PHPLango.css' ?>">
    <link rel="stylesheet" href="<?= CSS.'bootstrap.min.css' ?>">
</head>
<body style="padding-top: 40px;">
	<?php require_once ELEMENTS."navbar.php"; ?>
	<div class="container">
		<div class="jumbotron">
			<p style="font-size: 2rem; font-weight: 300; line-height: 1.2;"><?= $_SESSION['message'] ?></p>
			<?php unset($_SESSION['message']); ?>
		</div>
	</div>
	<script src="<?= JS.'jquery-3.2.1.slim.min.js' ?>"></script>
    <script src="<?= JS.'bootstrap.min.js' ?>"></script>
</body>
</html>
