<?php
/* // SSL enforce
 if( $_SERVER['SERVER_PORT'] == 80) {
 header('Location:https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).''.basename($_SERVER['PHP_SELF']));
 die();
 }
 // End of SSL enforce */
session_start();
require_once 'includes/functions.php';

if(isset($_GET['logout'])) {
	//audit_add(get_user_id(),get_ip(), "Logged Out.");
	unset($_SESSION['member_ID']);
    unset($_SESSION['is_admin']);
	unset($_SESSION);
	//sleep(3);
	$log_error="You have successfully logged out";
} else {
    if (is_signed_in()) {
        header('Location: '.SUCCESS_REDIRECT);
        die();
    }
}

process_post_variables();
if(isset($_POST['Submit'])) {
	// Username and password sent from signup form
	// First we remove all HTML-tags and PHP-tags, then we create a sha1-hash
	$username = $_POST['username'];
	$password = encryptPassword($_POST['password']);

	if (!is_banned(get_ip()) ) {
		// Make the query a wee-bit safer
		$query = sprintf("SELECT * FROM members WHERE username = '%s' AND user_password = '%s' LIMIT 1;", $username, $password);
		$result = mysql_query($query);
		if(1 != mysql_num_rows($result)) {
			// MySQL returned zero rows (or there's something wrong with the query)
			$log_error = 'Incorrect Login Credentials.';
			audit_add(0,get_ip(), "Login Failed.");
			ban_add(get_ip());
			mysql_free_result($result);
		} else {
			// We found the row that we were looking for
			$row = mysql_fetch_assoc($result);
			// Register the user ID for further use
			$_SESSION['member_ID'] = intval($row['id']);
            if (intval($row['is_admin'])) { $_SESSION['is_admin'] = 1; }
			//audit_add(get_user_id(),get_ip(), "Login Successful.");
			ban_clear(get_ip());
			mysql_free_result($result);
            
            header('Location: '.SUCCESS_REDIRECT);
		}
	} else {
		$log_error = 'You are currently banned. Retry later.';
	}
}

?> 

<?php require_once 'header.php';?>

<section id="login">
	<?php if(isset($log_error) && !empty($log_error)) { echo "<span class='errmsg'>$log_error</span>"; unset($log_error); } ?>
		<div id="reg-form">
		<form name="login-form" id="login-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<div class="left"><input class="input_controls" title="Username" name="username" id="username" type="text" placeholder="Username" pattern="\S{2,}" required aria-required="true"></div>
				<div style="clear:both"></div>
				<div class="left"><input class="input_controls" title="Password" name="password" id="password" type="password" placeholder="Password" pattern="\S{<?php echo PASSWORD_LENGTH;?>,}" required aria-required="true"></div>
				<div style="clear:both"></div>
							
				<div class="left"><input type="submit" name="Submit" value="Sign In" class="button"></div>
				<div style="clear:both"></div>
		</form>
		</div>
</section>

<?php require_once 'footer.php';?>