<?php 

if(isset($_SESSION['message'])) { 
	echo 
		"<div class='alert alert-".$_SESSION['class']." alert-dismissable'>".
			"<button type='button' class='close' data-dismiss='alert'>&times;</button>".
			$_SESSION['message'].
		"</div>";
} 
unset($_SESSION['message']);
unset($_SESSION['class']);