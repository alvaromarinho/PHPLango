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
<body class="main-signin">
	<?php require_once ELEMENTS."navbar.php"; ?>
	<div class="container">
		<?php require_once ELEMENTS.'message.php'; ?>
		<form class="form-signin" method="post" action="<?= ROOT ?>">
			<h2 class="form-signin-heading">Please sign in</h2>
			<input type="hidden" name="_auth">
			<label for="username" class="sr-only">Email address</label>
			<input type="text" id="username" name="username" class="form-control" placeholder="Email address" required autofocus>
			<label for="password" class="sr-only">Password</label>
			<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
			<!-- <div class="checkbox">
				<label>
					<input type="checkbox" value="remember-me"> Remember me
				</label>
			</div> -->
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</form>
	</div>
	<script src="<?= JS.'jquery-3.2.1.slim.min.js' ?>"></script>
    <script src="<?= JS.'bootstrap.min.js' ?>"></script>
</body>
</html>