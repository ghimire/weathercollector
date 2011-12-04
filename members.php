<?php
session_start();
require_once('includes/functions.php');
protected_page();

if(isset($_SESSION['is_admin']) && ($_SESSION['is_admin'] == 1)){
} else{
    header("Location: index.php");
    die();
}

process_post_variables();
if(isset($_POST['submit'])){
    if( ($_POST['username'] != "") && ($_POST['email'] != "")) {
        $action = $_POST['action'];
        if($action == "add") {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $is_admin = intval($_POST['admin']);
            
            if(($_POST['password'] != "")) {            
                $user_password = $_POST['password'];
                // Avoid duplicates
                $result0 = mysql_query("select * from members where username = '$username' or email = '$email'");
                if(!mysql_num_rows($result0)) {
                        mysql_query("INSERT INTO members (username, user_password, email, is_admin) VALUES('".$username."', '".encryptPassword($user_password)."', '".$email."',$is_admin)");
                }
            }
        } elseif ($action == "edit") {
            $id = intval($_POST['id']);
            $username = $_POST['username'];
            $email = $_POST['email'];
            $is_admin = intval($_POST['admin']);
                    
            if($_POST['password'] != "") {
                $user_password = $_POST['password'];
                mysql_query("UPDATE members set username = '".$username."', user_password = '".encryptPassword($user_password)."', email = '".$email."', is_admin = $is_admin where id = $id");
            } else {
               mysql_query("UPDATE members set username = '".$username."', email = '".$email."', is_admin = $is_admin where id = $id");
            }
        }
    }
}

if( isset($_GET['action']) && ($_GET['action'] == 'delete')){
    $id = intval($_GET['id']);
    mysql_query("DELETE FROM members where id = ".$id);
}

if( isset($_GET['action']) && ($_GET['action'] == 'adduser')){
?>
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
    <form id="reg-form" method="post" action="members.php">
            <div class="left"><input class="input_controls" id="username" name="username" type="text" placeholder="Username" required aria-required="true"></div><div class="right"><span class="info" id="username_info"></span></div>
            <div style="clear:both"></div>
            <div class="left"><input class="input_controls" id="password" name="password" type="password" placeholder="Password" pattern="\S{<?php echo PASSWORD_LENGTH;?>,}" required aria-required="true"></div><div class="right"><span class="info" id="password_info"></span></div>
            <div style="clear:both"></div>
            <div class="left"><input id="email" class="input_controls" name="email" type="text" placeholder="email@address.org" required aria-required="true"></div><div class="right"><span class="info" id="email_info"></span></div>
            <div style="clear:both"></div>
            <div class="left">Is Admin &nbsp;
                <select id="admin" name="admin">
                    <option value="1">YES</option>
                    <option value="0" selected>NO</option>
                </select>
            </div>
            <div style="clear:both"></div>
            <input type="hidden" name="action" value="add">
            <br>

    <div class="left"><input type="submit" name="submit" value="Submit" class="button">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit2" value="Reset" class="button"></div>
    <div style="clear:both"></div>
    </form>
</body></html>    
<?php    
exit;
}

if( isset($_GET['action']) && ($_GET['action'] == 'edit')){
    $id = intval($_GET['id']);
    $query1 = mysql_query("select id, username, user_password, email, is_admin from members where id = $id LIMIT 1");
    $row = mysql_fetch_assoc($query1);
    $username = $row['username'];
    $email = $row['email'];
    $admin = intval($row['is_admin']);
?>
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
    <form id="reg-form" method="post" action="members.php">
            <div class="left"><input class="input_controls" id="username" name="username" type="text" value ="<?php echo $username; ?>" placeholder="Username" required aria-required="true"></div><div class="right"><span class="info" id="username_info"></span></div>
            <div style="clear:both"></div>
            <div class="left"><input class="input_controls" id="password" name="password" type="password" placeholder="Password" pattern="\S{<?php echo PASSWORD_LENGTH;?>,}"></div><div class="right"><span class="info" id="password_info"></span></div>
            <div style="clear:both"></div>
            <div class="left"><input id="email" class="input_controls" name="email" type="text" value="<?php echo $email; ?>" placeholder="email@address.org" required aria-required="true"></div><div class="right"><span class="info" id="email_info"></span></div>
            <div style="clear:both"></div>
            <div class="left">Is Admin &nbsp;
                <select id="admin" name="admin">
                    <option value="1" <?php if($admin == 1) echo "selected"; ?> >YES</option>
                    <option value="0" <?php if(!$admin) echo "selected"; ?>>NO</option>
                </select>
            </div>
            <div style="clear:both"></div>
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="action" value="edit">
            <br>

    <div class="left"><input type="submit" name="submit" value="Submit" class="button">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit2" value="Reset" class="button"></div>
    <div style="clear:both"></div>
    </form>
</body></html>    
<?php    
exit;
}

if(isset($_GET['list'])){
    get_members_table();
    exit;
}

?>

<?php require_once 'header.php';?>
<div id="members"></div>

<script type="text/javascript">
    $(document).ready(function(){
        $.get('members.php?list', function(data){
            $('#members').html(data);
        });
    });
</script>
<?php require_once 'footer.php';?>
