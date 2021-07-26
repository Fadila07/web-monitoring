<?php

    include ('koneksi.php');

    $temperature = $_POST["sawi/temperature"];
    $humidity = $_POST["sawi/humidity"];
    $moisture = $_POST["sawi/moisture"];
    $macaddress = $_POST["sawi/mac"];
    
    
    // if($temperature != 0 && $humidity !=0 && $moisture !=0 &&){
        mysqli_query($conn, "INSERT INTO monitoring (mac_perangkat, temperature, humidity, moisture) VALUES ('$macaddress','$temperature', '$humidity', '$moisture')");
    // }
    


?>