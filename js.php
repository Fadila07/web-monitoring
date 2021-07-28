<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-streaming@1.8.0"></script>

<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
</script>

<script type="text/javascript">
    function showTime() {
        var a_p = "";
        var today = new Date();
        var curr_hour = today.getHours();
        var curr_minute = today.getMinutes();
        var curr_second = today.getSeconds();
        if (curr_hour < 12) {
            a_p = "AM";
        } else {
            a_p = "PM";
        }
        if (curr_hour == 0) {
            curr_hour = 12;
        }
        if (curr_hour > 12) {
            curr_hour = curr_hour - 12;
        }
        curr_hour = checkTime(curr_hour);
        curr_minute = checkTime(curr_minute);
        curr_second = checkTime(curr_second);
        document.getElementById('time').innerHTML=curr_hour + ":" + curr_minute + ":" + curr_second + " " + a_p;
    }
             
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
    setInterval(showTime, 500);         
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.2/mqttws31.min.js" type="text/javascript"></script>
<script type="text/javascript">
        const Broker = '44.195.141.13';
        var messagePayloadTemperature = 0;
        var messagePayloadHumidity = 0;
        var messagePayloadMoisture = 0;
        var messagePayloadMac = 0;
       
        var client = new Paho.MQTT.Client(Broker, 9095,
                    "myclientid_" + parseInt(Math.random() * 100, 10));
        // var client = new Paho.MQTT.Client(Broker, 9095, "myclientid_");
        // var cl = new Paho.MQTT.Client(MQTTbroker, 1883, "myclientid_");
        // client.onMessageArrived = onMessageArrived;
        // client.onConnectionLost = onConnectionLost;
        //connect to broker is at the bottom of the init() function !!!!
        
        //mqtt connecton options including the mqtt broker subscriptions
        client.connect({
            onSuccess: function () {
                console.log("mqtt connected js.php");
                client.subscribe("sawi/iot/temperature");
                client.subscribe("sawi/iot/humidity");
                client.subscribe("sawi/iot/moisture");
                client.subscribe("sawi/iot/mac");
                client.subscribe("esp/manual");
                client.subscribe("esp/device");
                client.subscribe("esp/hasil");
                client.subscribe("esp/hasilmac");

                client.onMessageArrived = onMessageArrived;
                client.onConnectionLost = onConnectionLost;
                // Connection succeeded; subscribe to our topics
                // client.subscribe(MQTTsubTopic, {qos: 1});
                // starttoConnect();
            },
            onFailure: function (message) {
                console.log("Connection failed, ERROR: " + message.errorMessage);
                // window.setTimeout(location.reload(),20000); //wait 20seconds before trying to connect again.
            }
        });

        function onConnectionLost(responseObject) {
            console.log("connection lost: " + responseObject.errorMessage);
            window.setTimeout(location.reload(),20000); //wait 20seconds before trying to connect again.
        };

        function onMessageArrived(message) {
            console.log(message.destinationName, '',message.payloadString);
           
            if(message.destinationName  == "sawi/iot/temperature"){
                console.log("Message Arrived: " + message.payloadString);
                // document.getElementById("temperature").innerHTML = '<span>' +message.payloadString +' </span>';
                messagePayloadTemperature = parseInt(message.payloadString);
        
                // Creating a cookie after the document is ready
                $(document).ready(function () {
                    createCookie("gfg", messagePayloadTemperature, "10");
                });
                            
                // Function to create the cookie
                function createCookie(name, value, days) {
                    var expires;
                                
                    if (days) {
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        expires = "; expires=" + date.toGMTString();
                    }
                    else {
                        expires = "";
                    }
                                
                    document.cookie = escape(name) + "=" + 
                    escape(value) + expires + "; path=/";
                }
            
        
                console.log("Temperature: " +messagePayloadTemperature);
            }
            if(message.destinationName == "sawi/iot/humidity"){
                console.log("Message Arrived: " + message.payloadString);
                // document.getElementById("humidity").innerHTML = '<span>' +message.payloadString +' </span>';
                messagePayloadHumidity = parseInt(message.payloadString);
 
                // Creating a cookie after the document is ready
                $(document).ready(function () {
                    createCookie("hmn", messagePayloadHumidity, "10");
                });
                            
                // Function to create the cookie
                function createCookie(name, value, days) {
                    var expires;
                                
                    if (days) {
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        expires = "; expires=" + date.toGMTString();
                    }
                    else {
                        expires = "";
                    }
                                
                    document.cookie = escape(name) + "=" + 
                    escape(value) + expires + "; path=/";
                }

                console.log("Humidity: " +messagePayloadHumidity);
            } if(message.destinationName == "sawi/iot/moisture"){
                console.log("Message Arrived: " + message.payloadString);
                // document.getElementById("moisture").innerHTML = '<span>' +message.payloadString +' </span>';
                messagePayloadMoisture = parseFloat(message.payloadString);

                $(document).ready(function () {
                    createCookie("mois", messagePayloadMoisture, "10");
                });
                            
                // Function to create the cookie
                function createCookie(name, value, days) {
                    var expires;
                                
                    if (days) {
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        expires = "; expires=" + date.toGMTString();
                    }
                    else {
                        expires = "";
                    }
                                
                    document.cookie = escape(name) + "=" + 
                    escape(value) + expires + "; path=/";
                }

                console.log("Moisture: " +messagePayloadMoisture);
            } if(message.destinationName=="sawi/iot/mac"){
                console.log("Message Arrived: " + message.payloadString);
                // document.getElementById("coba").innerHTML  = '<span>' +message.payloadString +' </span>';
                messagePayloadMac = message.PayloadString;
                if(message.payloadString == <?php echo json_encode($_SESSION["device"]);?>){
                    // document.getElementById("coba").innerHTML  = '<span>' +message.payloadString +' </span>';
                    document.getElementById("temperature").innerHTML  = '<span>' +messagePayloadTemperature +' </span>';
                    document.getElementById("humidity").innerHTML  = '<span>' +messagePayloadHumidity +' </span>';
                    document.getElementById("moisture").innerHTML  = '<span>' +messagePayloadMoisture +' </span>';
                }
            } 
        };


        function publishToMQTT(message) {
            message = new Paho.MQTT.Message(message ? "1" : "0");
            message.destinationName = "esp/manual";
            client.send(message);
        }



        function publishToMQTT_de() {
            var device = <?php echo json_encode($_SESSION["device"]);?>;
            message = new Paho.MQTT.Message(device);
            message.destinationName = "esp/device";
            client.send(message);
        }

        $(document).ready(function () {
            $("#manualBtn").bootstrapSwitch();

            $('#manualBtn').on('switchChange.bootstrapSwitch', function (event, state) {
                publishToMQTT(state);
                publishToMQTT_de();
            });
        });

        // $(document).ready(function() {
        //     setInterval(function() {
        //         $("#ref").load('dashboard.php');
        //     }, 5000);
        // })

    function refreshTemperature(chart){
        chart.config.data.datasets.forEach(function (dataset){
            dataset.data.push({
                x: Date.now(),
                y: messagePayloadTemperature
            });
        });
    }

        function onrefreshHum(chart){
            chart.config.data.datasets.forEach(function (dataset){
                dataset.data.push({
                    x: Date.now(),
                    y: messagePayloadHumidity
                })
            });
        }

        function onrefreshMois(chart){
            chart.config.data.datasets.forEach(function (dataset){
                dataset.data.push({
                    x: Date.now(),
                    y: messagePayloadMoisture
                })
            });
        }

        // var dataTemp = {
        //     data: messagePayloadTemperature,
        //     lineTension: 0,
        //     fill: false,
        //     borderColor: 'blue'
        // };
        
        var chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };
        
        var color = Chart.helpers.color;
        var configTemperature = {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Temperature',
			        backgroundColor: color(chartColors.red).alpha(0.5).rgbString(),
                    borderColor: chartColors.yellow,
                    fill: false,
                    // lineTension: 0,
                    // borderDash: [8, 4],
                    data: []
                }]
            },
            options: {
                title: {
                    display: true,
                    // text: "Temperature"
                },
                scales: {
                    xAxes: [{
                        type: 'realtime',
                        realtime: {
                            duration: 20000,
                            refresh: 2000,
                            delay: 3000,
                            onRefresh: refreshTemperature
                        }
                    }],
                    yAxis: [{
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }]
                },
                tooltips: {
                    mode: 'nearest',
                    intersect: false
                },
                hover: {
                    mode: 'nearest',
                    intersect: false
                }
            }
        };

        var configHumidity = {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Humidity',
			        backgroundColor: color(chartColors.grey).alpha(0.5).rgbString(),
                    borderColor: chartColors.blue,
                    fill: false,
                    // lineTension: 0,
                    // borderDash: [8, 4],
                    data: []
                }]
            },
            options: {
                title: {
                    display: true,
                    // text: "Temperature"
                },
                scales: {
                    xAxes: [{
                        type: 'realtime',
                        realtime: {
                            duration: 20000,
                            refresh: 2000,
                            delay: 3000,
                            onRefresh: onrefreshHum
                        }
                    }],
                    yAxis: [{
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }]
                },
                tooltips: {
                    mode: 'nearest',
                    intersect: false
                },
                hover: {
                    mode: 'nearest',
                    intersect: false
                }
            }
        };

        var configMoisture = {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Moisture',
			        backgroundColor: color(chartColors.yellow).alpha(0.5).rgbString(),
                    borderColor: chartColors.orange,
                    fill: false,
                    // lineTension: 0,
                    // borderDash: [8, 4],
                    data: []
                }]
            },
            options: {
                title: {
                    display: true,
                    // text: "Temperature"
                },
                scales: {
                    xAxes: [{
                        type: 'realtime',
                        realtime: {
                            duration: 20000,
                            refresh: 2000,
                            delay: 3000,
                            onRefresh: onrefreshMois
                        }
                    }],
                    yAxis: [{
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }]
                },
                tooltips: {
                    mode: 'nearest',
                    intersect: false
                },
                hover: {
                    mode: 'nearest',
                    intersect: false
                }
            }
        };

        window.onload = function() {
            var ctx = document.getElementById("ChartTemperature").getContext("2d");
            window.ChartTemperature = new Chart(ctx, configTemperature);
            var ctx1 = document.getElementById("ChartHumidity").getContext("2d");
            window.ChartHumidity = new Chart(ctx1, configHumidity);
            var ctx2 = document.getElementById("ChartMoisture").getContext("2d");
            window.ChartMoisture = new Chart(ctx2, configMoisture);
        };
        
</script>