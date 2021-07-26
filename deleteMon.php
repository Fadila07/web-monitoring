<?php 
    include 'koneksi.php';

    $query = mysqli_query($conn, "DELETE FROM monitoring");
?>