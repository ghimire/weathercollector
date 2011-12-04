<?php
session_start();
require_once 'includes/functions.php';

if(is_signed_in()){
	header("Location: ".SUCCESS_REDIRECT);
}

?> 

<?php require_once 'header.php';?>

    <div id="container">
		<div id="content" style="padding-top:250px;">
			<h1 style="margin-left: 300px">Welcome to Weather Collection Centre in Banjul.</h1>
			<p style="margin-left: 500px">
				<a href="login.php">Click Here to Login</a> <br>
			</p>
		</div><!-- / content -->
	</div><!-- / container -->

<?php require_once 'footer.php';?>