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
    if( ($_POST['name'] != "") && ($_POST['number'] != "")) {    
        $action = $_POST['action'];
        if($action == "add") {
            $name = $_POST['name'];
            $number = $_POST['number'];
            $result0 = mysql_query("select * from station where name = '$name' or number = $number");
            // Avoid duplicates
            if(!mysql_num_rows($result0)) {
                mysql_query("INSERT INTO station (name, number) VALUES('".$name."', $number)");
            }
        } elseif ($action == "edit") {
            $id = intval($_POST['id']);
            $name = $_POST['name'];
            $number = $_POST['number'];
            mysql_query("UPDATE station set name = '".$name."', number = $number where id = $id");
        }
    }
    unset($_POST['submit']);
}

if( isset($_GET['action']) && ($_GET['action'] == 'delete')){
    $id = intval($_GET['id']);
    mysql_query("DELETE FROM station where id = ".$id);
}

if( isset($_GET['action']) && ($_GET['action'] == 'add')){
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
    <form id="reg-form" method="post" action="stations.php">
            <div class="left"><input class="input_controls" id="name" name="name" type="text" placeholder="name" required aria-required="true"></div><div class="right"><span class="info" id="name_info"></span></div>
            <div style="clear:both"></div>
            <div class="left"><input class="input_controls" id="number" name="number" type="number" placeholder="number" pattern="\d{7}"></div><div class="right"><span class="info" id="number_info"></span></div>
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
    $query1 = mysql_query("select id, name, number from station where id = $id LIMIT 1");
    $row = mysql_fetch_assoc($query1);
    $name = $row['name'];
    $number = $row['number'];
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
    <form id="reg-form" method="post" action="stations.php">
            <div class="left"><input class="input_controls" id="name" name="name" type="text" value ="<?php echo $name; ?>" placeholder="name" required aria-required="true"></div><div class="right"><span class="info" id="name_info"></span></div>
            <div style="clear:both"></div>
            <div class="left"><input class="input_controls" id="number" name="number" type="number" value ="<?php echo $number; ?>" placeholder="number" pattern="\d{7}"></div><div class="right"><span class="info" id="number_info"></span></div>
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
    get_stations_table();
    exit;
}

?>

<?php require_once 'header.php';?>
<div id="stations"></div>

<script type="text/javascript">
    $(document).ready(function(){
        $.get('stations.php?list', function(data){
            $('#stations').html(data);
        });
    });
</script>
<?php require_once 'footer.php';?>
