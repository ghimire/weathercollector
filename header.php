<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Data Collection</title>
	<link rel='stylesheet' href='css/default.css'>
	
	<script src="js/jquery-1.6.2.min.js"></script>
	<script src="js/modernizr.js"></script>
	<script src="js/default.js"></script>
	
</head>
<body>
		<?php 
		if(is_signed_in()) {
			echo '
			<nav id="header">
				<div class="nav-item-left">
					Logged In As: <span id="loggedin">'.user_info("username").'</span> 
				</div>	
				<div class="nav-item-right">
					<a href="login.php?logout">Sign Out</a>		
				</div>
			</nav>
			';
		}
		?>