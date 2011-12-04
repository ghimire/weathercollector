<?php
session_start();
require_once('includes/functions.php');
protected_page();

if(isset($_GET['export'])){
    header('Content-Type: text/plain');
    $query0 = mysql_query("select name, data, received from weatherlog, station where weatherlog.station_id = station.id order by received desc");
    while($row0 = mysql_fetch_assoc($query0)) {
        echo $row0['received'].": ".$row0['data']."\r\n";
    }
    die();
}

?>

<?php require_once 'header.php';?>
<div>
    <h1>Weather Log - <?php echo date('Y-m-d'); ?> <a target="_blank" href="<?php echo $_SERVER['PHP_SELF']; ?>?export">Export</a>
        <?php if(isset($_SESSION['is_admin']) && ($_SESSION['is_admin'] == 1)){
            echo "&nbsp;|&nbsp;<a href='members.php'>Members</a>&nbsp;|&nbsp;<a href='stations.php'>Stations</a>";
        } ?>
    </h1>
    <br/>
    <?php
        // Get Log
        $query = mysql_query("select name, data, received from weatherlog, station where weatherlog.station_id = station.id order by received desc");
        echo "<table id='gridtable' class='gridtable'><tr><th>Station Name</th><th>Data</th><th>Received</th></tr>";
        while($row = mysql_fetch_assoc($query)) {
            echo "<tr><td>".$row['name']."</td>"."</td><td>".$row['data']."</td>"."</td><td>".$row['received']."</td></tr>";
        }
        echo "</table>";
    ?>
    
</div>	

<script type="text/javascript">

</script> 
	
<?php require_once 'footer.php';?>