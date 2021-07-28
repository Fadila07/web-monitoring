<?php
// Start the session
session_start();
?>
<?php 
    include 'koneksi.php';

    $nama_perangkat = $_POST['nama_perangkat'];
    $mac = $_POST['mac_perangkat'];
    $query = "INSERT INTO terkoneksi(nama_perangkat, mac_perangkat) VALUES ('$nama_perangkat', '$mac')";
    $cek = mysqli_num_rows(mysqli_query($conn,"SELECT mac_perangkat FROM terkoneksi WHERE mac_perangkat = '$mac' OR nama_perangkat = '$nama_perangkat'"));

    // if(isset($_POST['submit'])){
        session_unset();
        if(!empty($nama_perangkat && !empty($mac))){
            if($cek>0){
                $_SESSION["error"] = 1;
               
            } else{
                mysqli_query($conn, $query);
                $_SESSION["error"] = 0;
            }
        }
    // }

    header("location:device.php");
?>