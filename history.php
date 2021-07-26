<!DOCTYPE html>
<html>
<head>
  <?php include 'header.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
  <?php include 'navbar.php'; ?>
  <?php include 'sidebar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <br></br>
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data History</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Mac Perangkat</th>
                    <th>Temperatue</th>
                    <th>Humidity</th>
                    <th>Moisture</th>
                    <th>Pompa Hidup</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      include 'koneksi.php';

                      $query = mysqli_query($conn, "SELECT * FROM history");
                      
                      while($data=mysqli_fetch_array($query)){?>
                      <tr>
                        <td><?=$data['mac_perangkat'];?></td>
                        <td><?=$data['temperature'];?></td>
                        <td><?=$data['humidity'];?></td>
                        <td><?=$data['moisture'];?></td>
                        <td><?=$data['hasil'];?></td>
                      </tr>
                      <?php } ?> 
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Mac Perangkat</th>
                    <th>Temperatue</th>
                    <th>Humidity</th>
                    <th>Moisture</th>
                    <th>Pompa Hidup</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
<?php include 'footer.php'; ?>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- page script -->
<script>
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
</script>
</body>
</html>
