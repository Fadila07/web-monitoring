<?php
    $servername = "localhost";
    $database   = "iot";
    $username   = "admin";
    $password   = "123";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn){
        die("Connection failed: " . $conn->connect_error);
    }

?>