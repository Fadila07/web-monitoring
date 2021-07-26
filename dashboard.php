<?php
error_reporting(0);
?>
<span id="ref" hidden></span>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <span id="coba"></span> -->
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <!-- <?php echo $_SESSION["hitung"] ?> -->
              <span id="time"></span>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-transparent">
              <div class="inner">
                <h3><span id="temperature"></span>&deg;C</h3>
                <p style="font-size: 20px"> Temperature</p>
              </div>
              <div class="icon">
                <i class="fas fa-thermometer-half"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-transparent">
              <div class="inner">
                <h3><span id="humidity"></span>%</h3>
                <p style="font-size: 20px"> Humidity</p>
              </div>
              <div class="icon">
                <i class="fas fa-tint"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-transparent">
              <div class="inner">
                <h3><span id="moisture"></span>%</h3>
                <p style="font-size: 20px"> Moisture</p>
              </div>
              <div class="icon">
                <i class="fas fa-angle-double-down"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-transparent">
              <div class="inner">
                <!-- <h3><span><?php echo $_SESSION["hasil"] ?></span >ms</h3> -->
                <p style="font-size: 20px"> Water Pump</p>
              </div>
              <div class="icon">
                <i class="fas fa-pump-soap"></i>
                <input type="checkbox" id = "manualBtn" name="my-checkbox" data-bootstrap-switch data-off-color="danger" data-on-color="success">
              </div>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Chart Temperature</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="ChartTemperature" height="200" min-width="200"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Chart Humidity</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="ChartHumidity" height="200" min-width="200"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3"></div>
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Chart Moisture</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="ChartMoisture" height="200" min-width="200"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3"></div>
        </div>
    </div><!-- /.container-fluid -->
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->