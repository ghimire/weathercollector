<?php

/*************** DATABASE CONFIGURATION  ***************/
define('SQL_HOST','localhost');           // Host name
define('SQL_USER','sms-user');            // Mysql username
define('SQL_PASS','s3cr37passw0rd');         // Mysql password
define('SQL_DB','weather');                 // Database name
/*************** END DATABASE CONFIG ***************/

define('SALT','weathercontrol');
define('PASSWORD_LENGTH',6);
define('LOGIN_PAGE','login.php');
define('SUCCESS_REDIRECT','portal.php');
define('EMAIL_REGEX','/^([a-zA-Z0-9_\-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([a-zA-Z0-9\-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$/');

define("SMTP_SERVER",'example.org');
define("SMTP_USERNAME",'weather@example.org');
define("SMTP_PASSWORD",'s3cr37');

// Do Not Modify Below This Line
// Create a link to the database server
$link = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS);
if(!$link) :
   die('Could not connect: ' . mysql_error());
endif;
// Select a database where our member tables are stored
$db = mysql_select_db(SQL_DB, $link);
if(!$db) :
   die ('Can\'t connect to database : ' . mysql_error());
endif;

?>
