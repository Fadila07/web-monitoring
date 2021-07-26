<?php
    $servername = "localhost";
    $database   = "iot";
    $username   = "root";
    $password   = "";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn){
        die("Connection failed: " . $conn->connect_error);
    }

?>