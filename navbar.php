<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
        <!-- select -->
        <form action="overview.php" method="post">
        <div class="form-group" id="form-device">
            <select class="form-control" id="device" name="device">
 
                <?php 
                  include 'koneksi.php';

                  $query = mysqli_query($conn, "SELECT p.mac, t.nama_perangkat FROM terkoneksi AS t
                  INNER JOIN perangkat AS p ON t.mac_perangkat = p.mac");
                  while($data=mysqli_fetch_array($query)){?>
                    <?php
                        if($data['mac'] == $_SESSION["device"])
                        {
                            ?>
                            <option <?php echo 'selected="selected"'?> value="<?=$_SESSION["device"];?>"><?php echo $data['nama_perangkat'];?></option>
                            <?php
                        }
                        else
                        {
                          ?>
                          <option value="<?=$data['mac'];?>"><?php echo $data['nama_perangkat'];?></option>
                          <?php
                        }
                    ?>
                    
                  <?php } ?>
            </select>
        </div>
        
        <div class="nav-item">
          <button type="submit" class="btn btn-primary">Select</button>
        </div>
        </form>
        
    </ul>
  </nav>

  <!-- <script>
    function myFunction(){
      document.getElementById("device").selectedIndex;
    }
  </script> -->
  <!-- /.navbar -->