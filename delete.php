<?php 
    include 'koneksi.php';

    $mac = $_GET['mac_perangkat'];

    $query = mysqli_query($conn, "DELETE FROM terkoneksi WHERE mac_perangkat = '$mac'");

    if($query){
        header("location:device.php");
    }
?>