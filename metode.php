<?php
    echo '<span hidden id="delete"></span>';
    echo '<span hidden id="refresh"></span>';
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.2/mqttws31.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?php
    // date_default_timezone_set('Asia/Jakarta');
    // session_start();
    // include 'js.php';
    // include 'sendData.php';
    // $query = mysqli_query($conn,"SELECT * FROM sensornew order by id desc") or die (mysqli_error());
    // $row = mysqli_fetch_array($query);
    // $temperature = $_COOKIE["gfg"];
    //     // var_dump($temperature);
    //     $humidity = $_COOKIE["hmn"];
    //     // var_dump($humidity);
    //     $moisture = $_COOKIE["mois"];
    //     // var_dump($moisture);

    $alfa = array();
    $zn = 0;
    $zt = 0;
    $total = 0;
    $z = array();
    $hasil = 0;
    $mac = array();
    $moisture=array();
    $temperature=array();
    $humidity=array();
    $waktu=array();
    $hitung = 0;

    include 'koneksi.php';

    echo $hasil;

    $query = mysqli_query($conn, "SELECT AVG(temperature) AS temperature, AVG(humidity) AS humidity, AVG(moisture) AS moisture, 
    mac_perangkat FROM monitoring GROUP BY mac_perangkat") or die(mysqli_error());
    $query1 = mysqli_query($conn, "SELECT  *  FROM monitoring GROUP BY mac_perangkat");
    while($data=mysqli_fetch_array($query1)){
      

        $mac[$hitung] = $data['mac_perangkat'];

        $hitung++;

    }
    $count_mois = 0;
    for($i = 0; $i<$hitung; $i++){
        $query1 = mysqli_query($conn, "SELECT SUM(moisture)/COUNT(moisture) AS mois, mac_perangkat FROM monitoring WHERE moisture != 0 GROUP BY mac_perangkat");
        while($data=mysqli_fetch_array($query1)){
            $moisture[$count_mois] = $data['mois'];
            $id = $data['mac_perangkat'];
            $count_mois++;
        }  
    }
    

    $count_temp = 0;
    for($i = 0; $i<$hitung; $i++){
        $query1 = mysqli_query($conn, "SELECT SUM(temperature)/COUNT(temperature) AS temp, mac_perangkat FROM monitoring WHERE temperature != 0 GROUP BY mac_perangkat");
        while($data=mysqli_fetch_array($query1)){
            $temperature[$count_temp] = $data['temp'];
            $id = $data['mac_perangkat'];
            $count_temp++;
        }  
    }

    $count_hum = 0;
    for($i = 0; $i<$hitung; $i++){
        $query1 = mysqli_query($conn, "SELECT SUM(humidity)/COUNT(humidity) AS hum, mac_perangkat FROM monitoring WHERE humidity != 0 GROUP BY mac_perangkat");
        while($data=mysqli_fetch_array($query1)){
            $humidity[$count_hum] = $data['hum'];
            $id = $data['mac_perangkat'];
            $count_hum++;
        }  
    }
    $hasil=array();
    
    for($i = 0; $i<$count_temp; $i++){
        
        // if($temperature[$i] != 0 && $humidity[$i] !=0 && $moisture[$i] !=0){
                $hasil[$i] = round(perhitungan($temperature[$i], $humidity[$i], $moisture[$i]));
        // }
    }

    $cekhasil = array();
    $cekmac = array();

    for($i = 0; $i<$hitung; $i++){

        // session_unset();
            
            $_SESSION["hasil".$i] = (string)$hasil[$i];
            array_push($cekhasil, $_SESSION["hasil".$i]);
            
            $_SESSION["mac".$i] = (string)$mac[$i];
            array_push($cekmac, $_SESSION["mac".$i]);
            $_SESSION["hitung"] = $hitung;

            // $waktu[$i] = date("Y-m-d H:i:s");

            // echo $i;
            // echo "<br>";
            // echo $_SESSION["hasil".$i];
            // echo "<br>";
            // echo $_SESSION["mac".$i];
            // echo "<br>";
        
            
       mysqli_query($conn, "INSERT INTO history VALUES( '','$mac[$i]','$temperature[$i]', '$humidity[$i]', '$moisture[$i]', '$hasil[$i]')") or die(mysqli_error());
    }

    
    // echo "Hasil = " .$hasil . "<br>";
    // echo $hasil;
    // $ins = mysqli_query($conn, "INSERT temperature, humidity , moisture, hasil,
    // id FROM monitor WHERE temp != 0 and hm != 0 and mois != 0 GROUP BY id") or die(mysqli_error());
    // echo $temperature ."<br>";
    // echo $humidity ."<br>";
    // echo $moisture ."<br>";

    function findMin($x, $y, $z){
        if($x <= $y && $x <= $z){
            return $x;
        } elseif($y <= $x && $y <= $z){
            return $y;
        } else{
            return $z;
        }
    }

    function tempDingin($temperature){
        if($temperature <= 5){
            return 1;
        } elseif ($temperature > 5 && $temperature < 10){
            return (10 - $temperature) / (10-5);
        } else{
            return 0;
        }
    }

    function tempSejuk($temperature){
        if($temperature > 5 && $temperature <= 10){
            return ($temperature - 5) / (10-5);
        } elseif($temperature > 10 && $temperature < 15){
            return (15 - $temperature) / (15-10);
        } else {
            return 0;
        }
    }

    function tempNormal($temperature){
        if($temperature > 15 && $temperature <= 25){
            return ($temperature - 15) / (25-15);
        } elseif($temperature > 25 && $temperature < 30){
            return (30 - $temperature) / (30-25);
        } else{
            return 0;
        }
    }

    function tempPanas($temperature){
        if($temperature > 25 && $temperature <= 30){
            return ($temperature - 25) / (30-25);
        } elseif($temperature > 30 && $temperature < 35){
            return (35 - $temperature) / (35-30);
        } else{
            return 0;
        }
    }

    function tempSPanas($temperature){
        if($temperature <= 35){
            return 0;
        } elseif ($temperature > 35 && $temperature < 45){
            return ($temperature - 35) / (45-35);
        } else{
            return 1;
        }
    }

    function humKering($humidity){
        if($humidity <= 15){
            return 1;
        } elseif ($humidity > 15 && $humidity < 25){
            return (25 - $humidity) / (25-15);
        } else{
            return 0;
        }
    }

    function humAgKering($humidity){
        if($humidity > 20 && $humidity <= 30){
            return ($humidity - 20) / (30-20);
        } elseif($humidity > 30 && $humidity < 45){
            return (45 - $humidity) / (45-30);
        } else{
            return 0;
        }
    }

    function humSedang($humidity){
        if($humidity > 35 && $humidity <= 50){
            return ($humidity - 35) / (50-35);
        } elseif($humidity > 50 && $humidity < 65){
            return (65 - $humidity) / (65-50);
        } else{
            return 0;
        }
    }

    function humAgBasah($humidity){
        if($humidity > 60 && $humidity <= 70){
            return ($humidity - 60) / (70-60);
        } elseif($humidity > 70 && $humidity < 80){
            return (80 - $humidity) / (80-70);
        } else{
            return 0;
        }
    }

    function humBasah($humidity){
        if($humidity <= 75){
            return 0;
        } elseif ($humidity > 75 && $humidity < 85){
            return ($humidity - 75) / (85-75);
        } else{
            return 1;
        }
    }

    function moisKering($moisture){
        if($moisture <= 0){
            return 1;
        } elseif ($moisture > 0 && $moisture <= 35){
            return (35 - $moisture) / (35-0);
        } else{
            return 0;
        }
    }

    function moisLembab($moisture){
        if($moisture > 25 && $moisture <= 50){
            return ($moisture - 25) / (50-25);
        } elseif($moisture > 50 && $moisture < 80){
            return (80 - $moisture) / (80-50);
        } else{
            return 0;
        }
    }

    function moisBasah($moisture){
        if($moisture <= 70){
            return 0;
        } elseif ($moisture > 70 && $moisture < 100){
            return ($moisture - 70) / (100-70);
        } else{
            return 1;
        }
    }

    function Mati($alfa){
        if($alfa <= 0){
            return 1;
        } else if($alfa > 0 && $alfa < 1){
            return (7.5 - ($alfa * (7.5 - 0)));
        } else{
            return 0;
        }
    }

    function Cepat($alfa){
        if($alfa > 0 && $alfa < 1){
            $zn = (($alfa * (7.5 - 0)) + 0);
            $zt = (15 - ($alfa * (15 - 7.5)));
            return $total = ($zn + $zt) / 2;
        } else{
            return 0;
        }
    }

    function Sebentar($alfa){
        if($alfa > 0 && $alfa < 1){
            $zn = (($alfa * (15 - 7.5)) + 7.5);
            $zt = (22.5 - ($alfa * (22.5 - 15)));
            return $total = ($zn + $zt) / 2;
        } else{
            return 0;
        }
    }

    function AgakSebentar($alfa){
        if($alfa > 0 && $alfa < 1){
            $zn = (($alfa * (22.5 - 15)) + 15);
            $zt = (30 - ($alfa * (30 - 22.5)));
            return $total = ($zn + $zt) / 2;
        } else{
            return 0;
        }
    }

    function Sedang($alfa){
        if($alfa > 0 && $alfa < 1){
            $zn = (($alfa * (30 - 22.5)) + 22.5);
            $zt = (37.5 - ($alfa * (37.5 - 30)));
            return $total = ($zn + $zt) / 2;
        } else{
            return 0;
        }
    }

    function AgakLumayan($alfa){
        if($alfa > 0 && $alfa < 1){
            $zn = (($alfa * (37.5 - 30)) + 30);
            $zt = (45 - ($alfa * (45 - 37.5)));
            return $total = ($zn + $zt) / 2;
        } else{
            return 0;
        }
    }

    function Lumayan($alfa){
        if($alfa > 0 && $alfa < 1){
            $zn = (($alfa * (45 - 37.5)) + 37.5);
            $zt = (52.5 - ($alfa * (52.5 - 45)));
            return $total = ($zn + $zt) / 2;
        } else{
            return 0;
        }
    }

    function Lama($alfa){
        if($alfa > 0 && $alfa < 1){
            $zn = (($alfa * (52.5 - 45)) + 45);
            $zt = (60 - ($alfa * (60 - 52.5)));
            return $total = ($zn + $zt) / 2;
        } else{
            return 0;
        }
    }

    function SangatLama($alfa){
        if($alfa <= 0){
            return 0;
        } elseif($alfa > 0 && $alfa < 1){
            return (($alfa * (60 - 52.5)) + 52.5);
        } else{
            return 1;
        }
    }

    function perhitungan($temperature, $humidity, $moisture){
        $alfa[0] = findMin(tempDingin($temperature), humKering($humidity), moisKering($moisture));
        $z[0] = AgakLumayan($alfa[0]);

        $alfa[1] = findMin(tempDingin($temperature), humKering($humidity), moisLembab($moisture));
        $z[1] = Cepat($alfa[1]);

        $alfa[2] = findMin(tempDingin($temperature), humKering($humidity), moisBasah($moisture));
        $z[2] = Mati($alfa[2]);

        $alfa[3] = findMin(tempDingin($temperature), humAgKering($humidity), moisKering($moisture));
        $z[3] = Sedang($alfa[3]);

        $alfa[4] = findMin(tempDingin($temperature), humAgKering($humidity), moisLembab($moisture));
        $z[4] = Cepat($alfa[4]);

        $alfa[5] = findMin(tempDingin($temperature), humAgKering($humidity), moisBasah($moisture));
        $z[5] = Mati($alfa[5]);

        $alfa[6] = findMin(tempDingin($temperature), humSedang($humidity), moisKering($moisture));
        $z[6] = AgakSebentar($alfa[6]);

        $alfa[7] = findMin(tempDingin($temperature), humSedang($humidity), moisLembab($moisture));
        $z[7] = Cepat($alfa[7]);

        $alfa[8] = findMin(tempDingin($temperature), humSedang($humidity), moisBasah($moisture));
        $z[8] = Mati($alfa[8]);

        $alfa[9] = findMin(tempDingin($temperature), humAgBasah($humidity), moisKering($moisture));
        $z[9] = Sebentar($alfa[9]);

        $alfa[10] = findMin(tempDingin($temperature), humAgBasah($humidity), moisLembab($moisture));
        $z[10] = Cepat($alfa[10]);

        $alfa[11] = findMin(tempDingin($temperature), humAgBasah($humidity), moisBasah($moisture));
        $z[11] = Mati($alfa[11]);

        $alfa[12] = findMin(tempDingin($temperature), humBasah($humidity), moisKering($moisture));
        $z[12] = Sebentar($alfa[12]);

        $alfa[13] = findMin(tempDingin($temperature), humBasah($humidity), moisLembab($moisture));
        $z[13] = Cepat($alfa[13]);

        $alfa[14] = findMin(tempDingin($temperature), humBasah($humidity), moisBasah($moisture));
        $z[14] = Mati($alfa[14]);

        $alfa[15] = findMin(tempSejuk($temperature), humKering($humidity), moisKering($moisture));
        $z[15] = AgakLumayan($alfa[15]);

        $alfa[16] = findMin(tempSejuk($temperature), humKering($humidity), moisLembab($moisture));
        $z[16] = AgakSebentar($alfa[16]);

        $alfa[17] = findMin(tempSejuk($temperature), humKering($humidity), moisBasah($moisture));
        $z[17] = Cepat($alfa[17]);

        $alfa[18] = findMin(tempSejuk($temperature), humAgKering($humidity), moisKering($moisture));
        $z[18] = Sedang($alfa[18]);

        $alfa[19] = findMin(tempSejuk($temperature), humAgKering($humidity), moisLembab($moisture));
        $z[19] = Sebentar($alfa[19]);

        $alfa[20] = findMin(tempSejuk($temperature), humAgKering($humidity), moisBasah($moisture));
        $z[20] = Cepat($alfa[20]);

        $alfa[21] = findMin(tempSejuk($temperature), humSedang($humidity), moisKering($moisture));
        $z[21] = AgakSebentar($alfa[21]);

        $alfa[22] = findMin(tempSejuk($temperature), humSedang($humidity), moisLembab($moisture));
        $z[22] = Cepat($alfa[22]);

        $alfa[23] = findMin(tempSejuk($temperature), humSedang($humidity), moisBasah($moisture));
        $z[23] = Mati($alfa[23]);

        $alfa[24] = findMin(tempSejuk($temperature), humAgBasah($humidity), moisKering($moisture));
        $z[24] = Sebentar($alfa[24]);

        $alfa[25] = findMin(tempSejuk($temperature), humAgBasah($humidity), moisLembab($moisture));
        $z[25] = Cepat($alfa[25]);

        $alfa[26] = findMin(tempSejuk($temperature), humAgBasah($humidity), moisBasah($moisture));
        $z[26] = Mati($alfa[26]);

        $alfa[27] = findMin(tempSejuk($temperature), humBasah($humidity), moisKering($moisture));
        $z[27] = Sebentar($alfa[27]);

        $alfa[28] = findMin(tempSejuk($temperature), humBasah($humidity), moisLembab($moisture));
        $z[28] = Cepat($alfa[28]);

        $alfa[29] = findMin(tempSejuk($temperature), humBasah($humidity), moisBasah($moisture));
        $z[29] = Mati($alfa[29]);

        $alfa[30] = findMin(tempNormal($temperature), humKering($humidity), moisKering($moisture));
        $z[30] = Lumayan($alfa[30]);

        $alfa[31] = findMin(tempNormal($temperature), humKering($humidity), moisLembab($moisture));
        $z[31] = Sebentar($alfa[31]);

        $alfa[32] = findMin(tempNormal($temperature), humKering($humidity), moisBasah($moisture));
        $z[32] = Cepat($alfa[32]);

        $alfa[33] = findMin(tempNormal($temperature), humAgKering($humidity), moisKering($moisture));
        $z[33] = AgakLumayan($alfa[33]);

        $alfa[34] = findMin(tempNormal($temperature), humAgKering($humidity), moisLembab($moisture));
        $z[34] = Sebentar($alfa[34]);

        $alfa[35] = findMin(tempNormal($temperature), humAgKering($humidity), moisBasah($moisture));
        $z[35] = Cepat($alfa[35]);

        $alfa[36] = findMin(tempNormal($temperature), humSedang($humidity), moisKering($moisture));
        $z[36] = Sedang($alfa[36]);

        $alfa[37] = findMin(tempNormal($temperature), humSedang($humidity), moisLembab($moisture));
        $z[37] = Sebentar($alfa[37]);

        $alfa[38] = findMin(tempNormal($temperature), humSedang($humidity), moisBasah($moisture));
        $z[38] = Mati($alfa[38]);

        $alfa[39] = findMin(tempNormal($temperature), humAgBasah($humidity), moisKering($moisture));
        $z[39] = Sedang($alfa[39]);

        $alfa[40] = findMin(tempNormal($temperature), humAgBasah($humidity), moisLembab($moisture));
        $z[40] = Cepat($alfa[40]);

        $alfa[41] = findMin(tempNormal($temperature), humAgBasah($humidity), moisBasah($moisture));
        $z[41] = Mati($alfa[41]);

        $alfa[42] = findMin(tempNormal($temperature), humBasah($humidity), moisKering($moisture));
        $z[42] = AgakSebentar($alfa[42]);

        $alfa[43] = findMin(tempNormal($temperature), humBasah($humidity), moisLembab($moisture));
        $z[43] = Cepat($alfa[43]);

        $alfa[44] = findMin(tempNormal($temperature), humBasah($humidity), moisBasah($moisture));
        $z[44] = Mati($alfa[44]);

        $alfa[45] = findMin(tempPanas($temperature), humKering($humidity), moisKering($moisture));
        $z[45] = Lama($alfa[45]);

        $alfa[46] = findMin(tempPanas($temperature), humKering($humidity), moisLembab($moisture));
        $z[46] = AgakLumayan($alfa[46]);

        $alfa[47] = findMin(tempPanas($temperature), humKering($humidity), moisBasah($moisture));
        $z[47] = Sebentar($alfa[47]);

        $alfa[48] = findMin(tempPanas($temperature), humAgKering($humidity), moisKering($moisture));
        $z[48] = Lama($alfa[48]);

        $alfa[49] = findMin(tempPanas($temperature), humAgKering($humidity), moisLembab($moisture));
        $z[49] = Sedang($alfa[49]);

        $alfa[50] = findMin(tempPanas($temperature), humAgKering($humidity), moisBasah($moisture));
        $z[50] = Sebentar($alfa[50]);

        $alfa[51] = findMin(tempPanas($temperature), humSedang($humidity), moisKering($moisture));
        $z[51] = Lumayan($alfa[51]);

        $alfa[52] = findMin(tempPanas($temperature), humSedang($humidity), moisLembab($moisture));
        $z[52] = Sedang($alfa[52]);

        $alfa[53] = findMin(tempPanas($temperature), humSedang($humidity), moisBasah($moisture));
        $z[53] = Cepat($alfa[53]);

        $alfa[54] = findMin(tempPanas($temperature), humAgBasah($humidity), moisKering($moisture));
        $z[54] = AgakLumayan($alfa[54]);

        $alfa[55] = findMin(tempPanas($temperature), humAgBasah($humidity), moisLembab($moisture));
        $z[55] = AgakSebentar($alfa[55]);

        $alfa[56] = findMin(tempPanas($temperature), humAgBasah($humidity), moisBasah($moisture));
        $z[56] = Cepat($alfa[56]);

        $alfa[57] = findMin(tempPanas($temperature), humBasah($humidity), moisKering($moisture));
        $z[57] = AgakLumayan($alfa[57]);

        $alfa[58] = findMin(tempPanas($temperature), humBasah($humidity), moisLembab($moisture));
        $z[58] = Sebentar($alfa[58]);

        $alfa[59] = findMin(tempPanas($temperature), humBasah($humidity), moisBasah($moisture));
        $z[59] = Mati($alfa[59]);

        $alfa[60] = findMin(tempSPanas($temperature), humKering($humidity), moisKering($moisture));
        $z[60] = SangatLama($alfa[60]);

        $alfa[61] = findMin(tempSPanas($temperature), humKering($humidity), moisLembab($moisture));
        $z[61] = Sedang($alfa[61]);

        $alfa[62] = findMin(tempSPanas($temperature), humKering($humidity), moisBasah($moisture));
        $z[62] = Sebentar($alfa[62]);

        $alfa[63] = findMin(tempSPanas($temperature), humAgKering($humidity), moisKering($moisture));
        $z[63] = Lama($alfa[63]);

        $alfa[64] = findMin(tempSPanas($temperature), humAgKering($humidity), moisLembab($moisture));
        $z[64] = Sedang($alfa[64]);

        $alfa[65] = findMin(tempSPanas($temperature), humAgKering($humidity), moisBasah($moisture));
        $z[65] = Sebentar($alfa[65]);

        $alfa[66] = findMin(tempSPanas($temperature), humSedang($humidity), moisKering($moisture));
        $z[66] = Lumayan($alfa[66]);

        $alfa[67] = findMin(tempSPanas($temperature), humSedang($humidity), moisLembab($moisture));
        $z[67] = AgakSebentar($alfa[67]);

        $alfa[68] = findMin(tempSPanas($temperature), humSedang($humidity), moisBasah($moisture));
        $z[68] = Cepat($alfa[68]);

        $alfa[69] = findMin(tempSPanas($temperature), humAgBasah($humidity), moisKering($moisture));
        $z[69] = AgakLumayan($alfa[69]);

        $alfa[70] = findMin(tempSPanas($temperature), humAgBasah($humidity), moisLembab($moisture));
        $z[70] = Sedang($alfa[70]);

        $alfa[71] = findMin(tempSPanas($temperature), humAgBasah($humidity), moisBasah($moisture));
        $z[71] = Cepat($alfa[71]);

        $alfa[72] = findMin(tempSPanas($temperature), humBasah($humidity), moisKering($moisture));
        $z[72] = Sedang($alfa[72]);

        $alfa[73] = findMin(tempSPanas($temperature), humBasah($humidity), moisLembab($moisture));
        $z[73] = AgakSebentar($alfa[73]);

        $alfa[74] = findMin(tempSPanas($temperature), humBasah($humidity), moisBasah($moisture));
        $z[74] = Cepat($alfa[74]);

        $temp_1 = 0;
        $temp_2 = 0;
        
        for($i = 0; $i < 75; $i++){
            $temp_1 = $temp_1 + $alfa[$i] * $z[$i];
            $temp_2 = $temp_2 + $alfa[$i];
        }
        $hasil = $temp_1 / $temp_2;
        echo "cek";
        return $hasil;
    }

?>


<script type="text/javascript">
console.log("bb");
   const MQTTbroker = 'localhost';
   client = new Paho.MQTT.Client(MQTTbroker, 9095, "/ws", "clientsawi" + parseInt(Math.random() * 100, 10));

  //mqtt connecton options including the mqtt broker subscriptions
  client.connect({
    onSuccess: function () {
      console.log("mqtt connected");
      client.subscribe("esp/hasil");
      client.subscribe("esp/hasilmac");
      client.onMessageArrived = onMessageArrived;
      client.onConnectionLost = onConnectionLost;
    },
    onFailure: function (message) {
      console.log("Connection failed, ERROR: " + message.errorMessage);
    //   window.setTimeout(location.reload(),20000); //wait 20seconds before trying to connect again.
    }
  });
  
  function onConnectionLost(responseObject) {
    console.log("connection lost: " + responseObject.errorMessage);
    window.setTimeout(location.reload(),20000); //wait 20seconds before trying to connect again.
  };

  function onMessageArrived(message) {
    console.log(message.destinationName, '',message.payloadString);
  }

  function publishToMQTT_Fuzzy(message, message1) {
            message = new Paho.MQTT.Message(message);
            message1 = new Paho.MQTT.Message(message1);
            message.destinationName = "esp/hasil";
            message1.destinationName="esp/hasilmac";
            client.send(message);
            client.send(message1);
        }

    $(document).ready(function(){
        setInterval(function() {
            //     publishToMQTT_Fuzzy(document.getElementById("h").innerHTML);
            // console.log("sess"+publishToMQTT_Fuzzy(sessionStorage.getItem('hasil'[0]));

            var data = <?php echo json_encode($_SESSION["hitung"]);?>;
            cekhasil = <?php echo json_encode($cekhasil);?>;
            cekmac = <?php echo json_encode($cekmac);?>;


            var i = 0;
            publishToMQTT_Fuzzy(cekhasil[i], cekmac[i]);
            // setTimeout(function(){
            //     $('#delete').load("deleteMon.php");
            //         console.log("data");
            //     },5000);
            // i++;
            // setTimeout(function() {
            // if(i<data){
                
            //     publishToMQTT_Fuzzy(cekhasil[i], cekmac[i]);
                

            // }
            // }, 3000);
            // if(i== data){
                setTimeout(function(){
                $('#delete').load("deleteMon.php");
                    console.log("data");
                },5000);
                // setTimeout(function(){
                // $('#refresh').load("metode.php");
                //     console.log("data");
                // },20000);
            // }
                
                // publishToMQTT_Mac(cekmac[i]);

                    // console.log("ss");
                
                    
                    
                // document.getElementById("h").innerHTML  = '<span>' +hasil +' </span>';
                    
                
            
            }, 1000*60);
    })

</script>