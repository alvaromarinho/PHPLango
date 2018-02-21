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
<body class="main-signin">
	<?php require_once ELEMENTS."navbar.php"; ?>
	<div class="container">
		<?php require_once ELEMENTS.'message.php'; ?>
		<form class="form-signin" method="post" action="<?= ROOT ?>">
			<h2 class="form-signin-heading">Please sign in</h2>
			<input type="hidden" name="auth">
			<input type="text" name="username" class="form-control" placeholder="Email address" required autofocus>
			<input type="password" name="password" class="form-control" placeholder="Password" required>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</form>
	</div>
	<script src="<?= JS.'jquery-3.3.1.min.js' ?>"></script>
    <script src="<?= JS.'bootstrap.min.js' ?>"></script>
</body>
</html>