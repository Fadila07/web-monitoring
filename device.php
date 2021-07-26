<?php
// Start the session
session_start();

error_reporting(0);
?>
<?php include "header.php";?>
<?php include "navbar.php";?>
<?php include "sidebar.php";?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Device</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
              <form action="input.php" method="post">
                <div class="card-body">

                <?php
                  if($_SESSION["error"]==1)
                  {
                ?>
                <div class="alert alert-danger">
                  <strong>DATA GAGAL DI INPUT!</strong> Nama Perangkat Sudah Digunakan/Peragkat Sudah Terkoneksi
                </div> 
                <?php
                 session_unset();
                  }
                
                ?> 
                  
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="nama_perangkat" id="nama_perangkat" required>
                  </div>
                  <div class="form-group">
                    <label for="">Device</label>
                    <select class="form-control" name="mac_perangkat" id="mac_perangkat" required>
                        <option disabled selected value="">Select Device...</option>
                        <?php 
                        include 'koneksi.php';

                        $query = mysqli_query($conn, "SELECT * FROM perangkat");
                        while($data=mysqli_fetch_array($query)){?>
                            <option value="<?=$data['mac'];?>"><?= $data['mac'];?></option>
                        <?php } ?>
                    </select>
                  </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-info" name="submit" value="1" onclick="publishMQTT_Connect(1); publishMQTT_Mac()">Connect!</button>
                </div>
              </form>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Perangkat Terkoneksi</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>MAC Address</th>
                    <th>Nama Perangkat</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      include 'koneksi.php';

                      $query = mysqli_query($conn, "SELECT t.id, p.mac, t.nama_perangkat FROM terkoneksi AS t
                      INNER JOIN perangkat AS p ON t.mac_perangkat = p.mac");
                      
                      while($data=mysqli_fetch_array($query)){?>
                      <tr>
                        <td id="macadd"><?=$data['mac'];?></td>
                        <td><?=$data['nama_perangkat'];?></td>
                        <td><a href="delete.php?mac_perangkat=<?php echo $data['mac'];?>">
                        <button type="button" class="btn bg-gradient-danger btn-sm" onclick="publishMQTT_Disconnect(0); publishMQTT_Dis('<?=$data['mac'];?>');">Disconnect!</button></td>
                      </tr>
                      <?php } ?> 
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        <!-- /.card -->
        </div>
    </section>

</div>

<?php include "footer.php";?>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.2/mqttws31.min.js" type="text/javascript"></script>
<script type="text/javascript">

  $(function () {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
  const MQTTbroker = '54.226.6.226';
  var client = new Paho.MQTT.Client(MQTTbroker, 9095, "myclientid_");

  //mqtt connecton options including the mqtt broker subscriptions
  client.connect({
    onSuccess: function () {
      console.log("mqtt connected");
      client.subscribe("esp/connect");
      client.subscribe("esp/disconnect");
      client.subscribe("esp/dis");
      client.subscribe("esp/mac");
      client.onMessageArrived = onMessageArrived;
      client.onConnectionLost = onConnectionLost;
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
  }

  // function submitted(){
  //   if()
  // }
  function publishMQTT_Connect(message){
    // alert(message);
    var cond = message.toString();
    var nm = document.getElementById('nama_perangkat').value;
    var macN = document.getElementById('mac_perangkat').selectedOptions[0].value;
    if(nm !== "" && macN !== ""){
      message = new Paho.MQTT.Message(cond);
      message.destinationName = "esp/connect";
      client.send(message);
    }
    // alert(cond);
    
  }
  
  function publishMQTT_Disconnect(message){
    // alert(message);
    var cond = message.toString();
    // alert(cond);
    message = new Paho.MQTT.Message(cond);
    message.destinationName = "esp/disconnect";
    client.send(message);
    
  }

  function publishMQTT_Mac(){
    var nama = document.getElementById('nama_perangkat').value;
    var macNumber = document.getElementById('mac_perangkat').selectedOptions[0].value;
    
    // var macDis = document.getElementById('macadd').textContent;
    if(nama !== "" && macNumber !== ""){
      message = new Paho.MQTT.Message(macNumber);
      message.destinationName = "esp/mac";
      client.send(message);
      // alert(message);
    // } if(!empty(macDis)){
    //   message = new Paho.MQTT.Message(macDis);
    }
    
  }

  function publishMQTT_Dis(message){
    // var macDis = document.getElementById('macadd').textContent;
    var macDis = message.toString();
    message = new Paho.MQTT.Message(macDis);
    // alert(macDis);
    message.destinationName = "esp/dis";
    client.send(message);
  }

</script>
