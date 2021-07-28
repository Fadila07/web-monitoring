<?php
// Start the session
session_start();

error_reporting(0);
?>
 <?php
 session_unset();

//  include 'koneksi.php';
            
    if (isset($_POST["device"])) {
        $device = $_POST['device'];
        $_SESSION["device"] = (string)$device;

        // $sql = mysqli_query($conn,"SELECT hasil FROM history WHERE mac_perangkat = '$device' ORDER BY id DESC LIMIT 1") or die (mysqli_error());
        // $row = mysqli_fetch_array($sql);
        // $_SESSION["hasil"] = $row['hasil'];
        // echo "<meta http-equiv='refresh' content='5'";
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <?php include "header.php";?>
        
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
    
        <?php include "navbar.php";?>
        <?php include "sidebar.php";?>
        <?php include "dashboard.php";?>
        <?php include "footer.php";?>
        <?php include "metode.php";?>
        <?php include "js.php";?>
        
        
    </body>
</html>