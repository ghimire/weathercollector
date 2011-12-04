<?php
require_once('freeconfig.php');

function filter($data){
	$data = trim(htmlentities(strip_tags($data)));
	if(get_magic_quotes_gpc())
		$data = stripslashes($data);
		
	$data = mysql_real_escape_string($data);
	return $data;
}

function process_post_variables(){
	foreach($_POST as $key => $value){
		$_POST[$key] = filter($value);
	}
    
    foreach($_GET as $key => $value){
        $_GET[$key] = filter($value);
    }    
}

function get_members_table(){
    $query = mysql_query("select id, username, user_password, email, is_admin from members");
    echo "<table id='gridtable' class='gridtable'><tr><th colspan=2><a style='color: red' href='members.php?action=adduser'>Add Member</a></th><th>Username</th><th>Password</th><th>Email</th><th>Admin</th></tr>";
    while($row = mysql_fetch_assoc($query)) {
        $is_admin = "NO";
        if(intval($row['is_admin']) == 1) { $is_admin = "YES"; }
        echo "<tr><td><a href='members.php?action=delete&id=".$row['id']."'>Delete</a></td><td><a href='members.php?action=edit&id=".$row['id']."'>Edit</a>&nbsp;</td><td>".$row['username']."</td>"."</td><td>...</td>"."</td><td>".$row['email']."</td><td>".$is_admin."</td></tr>";
    }
    echo "</table>";
}

function get_stations_table(){
    $query = mysql_query("select id, name, number from station");
    echo "<table id='gridtable' class='gridtable'><tr><th colspan=2><a style='color: red' href='stations.php?action=add'>Add Station</a></th><th>Station Name</th><th>Phone Number</th></tr>";
    while($row = mysql_fetch_assoc($query)) {
        echo "<tr><td><a href='stations.php?action=delete&id=".$row['id']."'>Delete</a></td><td><a href='stations.php?action=edit&id=".$row['id']."'>Edit</a>&nbsp;</td><td>".$row['name']."</td><td>".$row['number']."</td></tr>";
    }
    echo "</table>";
}


function get_user_id(){
	return $_SESSION['member_ID'];	
}

function is_signed_in(){
	if(isset($_SESSION['member_ID']))
		return true;
	else
		return false;
}

function get_ip(){
	if(isset($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
	else return '0.0.0.0';
}

function protected_page(){
	if(!is_signed_in()){
		header('Location: '.LOGIN_PAGE);
		die();
    }
}

function return_json($success,$reg_error){
	$json = array(
		'success' => $success,
		'message' => $reg_error
	);
	echo json_encode($json);
}

function user_info($field='') {
	// If $field is empty
	if(empty($field))
		return false;
	// Check to see if we're allowed to query the requested field.
	// If we add other fields, such as name, e-mail etc, this array
	// will have to be extended to include those fields.
	$accepted = array('id', 'username', 'user_password', 'email');
	if(!in_array($field, $accepted))
		return false;
	// Poll the database
	$result = mysql_query("SELECT ". $field ." FROM members WHERE id = ". $_SESSION['member_ID'] ." LIMIT 1;");
	// If we don't find any rows
	if(1 != mysql_num_rows($result)) :
		mysql_free_result($result);
		return 0;
	else :
		// We found the row that we were looking for
		$row = mysql_fetch_assoc($result);
		// Return the field
		mysql_free_result($result);
		return $row[$field];
	endif;
} // end user_info

// Ban Related Functions
function banlist_info($field='', $comparefield='IP', $condition='127.0.0.1') {
	// If $field is empty
	if(empty($field) || empty($comparefield))
		return false;
	$accepted = array('id', 'IP', 'attempts', 'maxattempts', 'created', 'expires');
	if(!in_array($field, $accepted))
		return false;
	if(!in_array($comparefield, $accepted))
		return false;

	$condition=filter($condition);
	 
	// Poll the database
	$result = mysql_query("SELECT ". $field ." FROM banlist WHERE ".$comparefield." = '". $condition ."' LIMIT 1;");
	// If we don't find any rows
	if(1 != mysql_num_rows($result)) :
		return 0;
	else :
		// We found the row that we were looking for
		$row = mysql_fetch_assoc($result);
		// Return the field
		return $row[$field];
	endif;
} // end user_info

function audit_add($uid, $ip,$message){
	$uid = intval($uid);
	$ip = filter($ip);
	$message = filter($message);
	mysql_query("INSERT INTO audit (user_id, IP, message) VALUES($uid,'$ip','$message')");	
}

function is_banned($ip){
	$expires = banlist_info("expires","IP",$ip); // Y-m-d H:i:s Format
	$timenow = strtotime("now");
	
	if ( $expires && ($expires != '0000-00-00 00:00:00') ) {
		if (strtotime($expires) > $timenow) {
			return true;
		} else {
			ban_clear($ip);
			return false;
		}
	} else {
		return false;
	}
}

function ban_add($ip){
		$ip = filter($ip);
		$previous_attempts=intval(banlist_info('attempts','IP',$ip));
		$maxattempts=intval(banlist_info('maxattempts','IP',$ip));
		if(!$previous_attempts) {
			mysql_query("INSERT INTO banlist (IP) VALUES('".$ip."');");
		} else {
			$result1 = mysql_query("UPDATE banlist SET attempts = attempts + 1 WHERE IP = '".$ip."';");
			$previous_attempts += 1;
			if($previous_attempts >= $maxattempts){
				mysql_query("UPDATE banlist SET expires = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE IP = '".$ip."' ");
			}
		}
		
		return true;
}

function ban_clear($ip){
	$ip = filter($ip);
	mysql_query("DELETE FROM banlist WHERE IP = '".$ip."';");
	return true;
}

// End of Ban Related Functions

function createRandomPassword() {
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	srand((double)microtime()*1000000);
	$i = 0;
	$ranpass = '' ;
	while ($i <= PASSWORD_LENGTH) {
		$num = rand() % 33;
		$tmp = substr($chars, $num, 1);
		$ranpass = $ranpass . $tmp;
		$i++;
	}
	return $ranpass;
}

function encryptPassword($input=''){
	if(!empty($input)){
		return hash("sha512",$input.SALT);
	}
}

function alertbox($message){
	echo '
		<!DOCTYPE html >
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>MySite.tld | rocks the house...</title>
		<link rel="stylesheet" href="css/default.css">
		</head>
		<body>
		<div id="alertbox" class="alertbox">
		'.$message.'
		</div>
		</body>
		</html>
	';
	die();
}

function send_mail($fromname,$fromemail,$toname,$toemail,$subject,$bodyplain,$bodyhtml) {
	require_once("phpmailer/class.phpmailer.php");
	
	unset($mail);
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = SMTP_SERVER;
	$mail->SMTPAuth = true;
	$mail->Username = SMTP_USERNAME;
	$mail->Password = SMTP_PASSWORD;
	$mail->WordWrap = 70;
	$mail->IsHTML(true);
	$mail->Subject = "$subject";
	$mail->From = "$fromemail";
	$mail->FromName = "$fromname";
	$mail->AddAddress("$toemail","$toname");
	$mail->Body    = "$bodyhtml";
	$mail->AltBody = "$bodyplain";

	if (!$mail->Send()) {
		return false;
	} else {
		return true;
	}

	$mail->ClearAddresses();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();

	unset($mail);

}

?>

