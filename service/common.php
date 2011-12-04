<?php
//// Common Functions - INCLUDE ////

//******* You may modify these values ********//

// DATABASE DETAILS //
define("HOST","localhost");
define("DB_NAME","weather");
define("DB_USER","sms-user");
define("DB_PW","s3cr37passw0rd");

// INCOMING EMAIL/SMTP AUTHENTICATION DETAILS //
define("SMTP_SERVER",'example.org');
define("EMAIL_USERNAME",'weather@example.org');
define("EMAIL_PASSWORD",'s3cr37');

// REGULAR EXPRESSION TO MATCH PHONE NUMBERS //
define("PHONE_REGEX",'/^[0-9]{7}/');

// INTERNATIONAL COUNTRY CALLING CODE
define("COUNTRYCODE","379");
define("CONFIRMATION_MESSAGE","Your data has been received.");

// ***************************************//
// WARNING: Do not modify below this line //
// ***************************************//

define("SMSLIMIT",160);
define("EMAILPROCESS_LIMIT", 5);
define("SMSPROCESS_LIMIT", 10);
define("SMSPREFIX","From: ");
define("EMAIL_REGEX",'/^([a-zA-Z0-9_\-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([a-zA-Z0-9\-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$/');
define("EMAILSTRING_REGEX",'/^(.*)[\[\<](([a-zA-Z0-9_\-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([a-zA-Z0-9\-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?))[\]\>]$/');
define("DATA_REGEX",'/^[0-9][0-9\ ]+=$/');

$db = mysql_connect(HOST,DB_USER,DB_PW) or die("Connect Error");
mysql_select_db(DB_NAME);

// Log Messages
function logmsg($msg) {
	$message = mysql_real_escape_string($msg);
	mysql_query('insert into log (message) values("'.$message.'")') or die(mysql_error());
}

// Get User ID from Number
function get_id_from_number($number) {
	if (preg_match(PHONE_REGEX,$number)) {
		$result = mysql_query("select id from station where number = ".$number." limit 1");
		if(mysql_num_rows($result)) {
			$row = mysql_fetch_assoc($result);
			mysql_free_result($result);
			return intval($row['id']);
		} else {
			mysql_free_result($result);
			return -1;
		}
	} else { 
		return -1;
	}
}

// Validate Phone Number
function is_authorized_number($number){
	if (preg_match(PHONE_REGEX,$number)) {
		$result = mysql_query("select id from station where number = ".$number);
		if(1==mysql_num_rows($result)){
			$row = mysql_fetch_assoc($result);
			mysql_free_result($result);
			return intval($row['id']);
		} else return 0;
	} else {
		return -1;
	}
}

// Validate Email Address
function is_authorized_email($email){
    if (preg_match(EMAIL_REGEX,$email)) {
        $result = mysql_query('select id from members where email = "'.$email.'"');
        if(1==mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result);
            mysql_free_result($result);
            return intval($row['id']);
        } else return 0;
    } else {
        return -1;
    }
}

// Get Number from Email Address
function get_number($email){
	$keywords = preg_split("/[@]/", trim($email));
	return intval($keywords[0]);
}

// Get Name and Email from Email String
function split_email_string($emailstring){
	$emailstring = trim($emailstring);
	if (preg_match(EMAILSTRING_REGEX, $emailstring, $keywords)) {
		if (preg_match(EMAIL_REGEX,$keywords[2]))
			return array($keywords[1],$keywords[2]); 
		else
			return array('',$emailstring);
	} else
		return array('',$emailstring);
}

// Split text to 160 chunks
function split_chunk($text,&$chunk){
        if(strlen($text) <= SMSLIMIT) {
                array_push($chunk,$text);
                return $chunk;
        } else {
                array_push($chunk,substr($text,0,160));
                $remaining_text = substr($text,160,strlen($text));
                split_chunk($remaining_text,$chunk);
        }
}

// Insert Email for Processing
function insert_to_process_email($from_email,$from_name,$subject,$to_email,$to_name,$body) {
	$from_email = mysql_real_escape_string($from_email);
	$from_name = mysql_real_escape_string($from_name);
	$subject = mysql_real_escape_string($subject);
	$to_email = mysql_real_escape_string($to_email);
	$to_name = mysql_real_escape_string($to_name);
	$body = mysql_real_escape_string($body);
	
	mysql_query('INSERT INTO process_email (from_email,from_name,subject,to_email,to_name,body) VALUES ( "'.$from_email.'", "'.$from_name.'", "'.$subject.'", "'.$to_email.'", "'.$to_name.'", "'.$body.'" )');
	
}

// Weather Log Insertion
function insert_to_weatherlog($number,$text){
    $number = mysql_real_escape_string(intval($number));
    $text = mysql_real_escape_string($text);
    $station = get_id_from_number($number);
    $text=preg_replace("/(\t|\r|\n)/","",$text);  // remove new lines \n, tabs and \r
    
    if (preg_match(DATA_REGEX, $text)) {
        mysql_query('INSERT INTO weatherlog (station_id, data) VALUES( '.$station.', "'.$text.'" )');
        // Send confirmation with partial data
        insert_to_smsqueue($number,substr($text,0,10));
    }
}

// SMS Queue
function insert_to_smsqueue($number,$text){
	$number = mysql_real_escape_string(intval($number));
	$text = CONFIRMATION_MESSAGE."\nData: ".$text."...";
	mysql_query('INSERT INTO outbox (number,text) VALUES( '.$number.', "'.$text.'" )');
}

//// End of Common Functions ////
?>
