<?php

    include ('koneksi.php');

    $mac = $_POST["iot/mac"];
    $sql = mysqli_query($conn,"SELECT * FROM perangkat") or die(mysqli_error());
    $row = mysqli_fetch_array($sql);

    if($mac != NULL){
        if(mysqli_num_rows($sql) == 0){
            mysqli_query($conn, "INSERT INTO perangkat (mac) VALUES ('$mac')");
        } else{
            if($row['mac'] != $mac){
                mysqli_query($conn, "INSERT INTO perangkat (mac) VALUES ('$mac')");
            }
        }
    }

    // $temperature = $_POST["sawi/temperature"];
    // $humidity = $_POST["sawi/humidity"];
    // $moisture = $_POST["sawi/moisture"];
    // $tem = $_POST["sawi/iot/temperature"];
    // $hu = $_POST["sawi/iot/humidity"];
    // $moi = $_POST["sawi/iot/moisture"];
    // $sql = mysqli_query($conn,"SELECT * FROM realt") or die(mysqli_error());
    

    // if($temperature != 0 && $humidity !=0 && $moisture !=0){
    //     mysqli_query($conn, "INSERT INTO sensornew (temperature, humidity, moisture) VALUES ('$temperature', '$humidity', '$moisture')");
    // }

    // if($tem != 0 && $hu != 0 && $moi != 0){
    //     if(mysqli_num_rows($sql) == 0){
    //         mysqli_query($conn, "INSERT INTO realt (temperature, humidity, moisture) VALUES ('$tem', '$hu', '$moi')");
    //     }
    //     else{
    //         mysqli_query($conn, "UPDATE realt SET temperature = '$tem', humidity = '$hu', moisture = '$moi' WHERE id = 1");
    //     }
    // }
    


?>