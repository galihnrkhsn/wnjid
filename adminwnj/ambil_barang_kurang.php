<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Pusat | Wanoja</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  
  

</head>

<body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">
      <?php include "sidebar.php"; ?>    
      <!-- Begin Page Content -->
      <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
        </div>
        <h3><strong>Kekurangan Pre Order</strong></h3><br><br>
        <!-- Content Row -->
        <div class="table-responsive">
          <table class="table table-striped" id="tb_listpo_artikel">
              <thead>
                  <tr>
                      <th style="width:1%">No</th>
                      <th>Nama PO</th>
                      <th>Per Nama DB</th>
                      <th>Per Variant</th>
                      <th>Custom</th>
                  </tr>
              </thead>
              <tbody>
                  <?php 
                      $datapo = $koneksi->query("SELECT poproduk.idpoproduk, poproduk.jenis, poproduk.namapo, sum(pomitra.jumlah) as jumlahnya
                      FROM poproduk
                      INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                      WHERE pomitra.jumlah > 0 AND poproduk.idpoproduk > 160
                      GROUP BY pomitra.idpoproduk
                      ORDER BY MAX(pomitra.idpomitra) DESC ");
                      $no = 1;
                      while($tampilkan = $datapo->fetch_assoc()) {
                  ?>
                  <tr>
                      <td><?php echo $no++; ?></td>
                      <td>
                          <a href="kurang_po.php?id=<?= $tampilkan['idpoproduk'] ?>">
                              <?php echo $tampilkan['namapo']; ?> (<?= $tampilkan['idpoproduk'] ?>)
                          </a>
                      </td>
                      <td>
                          <a href="excel.php?id=<?= $tampilkan['idpoproduk']; ?>&jenis=DB" target="_blank" style="color: green">
                              <i class="fas fa-file-excel"></i> Cetak
                          </a>
                      </td>
                      <td>
                          <a href="excel_variant.php?id=<?= $tampilkan['idpoproduk']; ?>&jenis=Variant" target="_blank" style="color: green">
                              <i class="fas fa-file-excel"></i> Cetak
                          </a>
                      </td>
                      <td>
                          <?php if ($tampilkan['jenis'] == 'Custom'): ?>
                              <form action="excel_ambil_barang.php" method="get">
                                  <input type="hidden" name="id" value="<?= $tampilkan['idpoproduk']; ?>" />
                                  <input type="hidden" name="jenis" value="Custom" />
                                  <select name="cs" class="form-control">
                                      <option value="All">All</option>
                                      <option value="Daniar">Daniar</option>
                                      <option value="Delita">Delita</option>
                                      <option value="Intan">Intan</option>
                                      <option value="Novi">Novi</option>
                                      <option value="Rika">Rika</option>
                                      <option value="Silmi">Silmi</option>
                                      <option value="Yara">Yara</option>
                                      <option value="Zahra">Zahra</option>
                                  </select>
                                  <br/>
                                  <button type="submit" class="btn btn-primary form-control">Download Excel</button>
                              </form>
                          <?php endif ?>
                      </td>
                  </tr>
                  <?php } ?>
              </tbody>
          </table>
      </div>                   
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
      <div class="container my-auto">
        <div class="copyright text-center my-auto">
          <span>Copyright &copy; Your Website 2020</span>
        </div>
      </div>
    </footer>
    <!-- End of Footer -->
    </div>
  <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
  
  <?php include "settingdatatables.php"; ?>
  <script>
    $(document).ready(function () {
      $('#tb_ambil_barang').DataTable({
          "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
          "pageLength": 25,
          order: [[2, 'desc']]
      });
  });
  </script>
  <script>
    $(document).ready(function () {
        $('#tb_ambil_barang_agen').DataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": 25,
            order: [[2, 'desc']]
        });
    });
  </script>
  <script>
    $(document).ready(function () {
        $('#tb_ambil_barang_reseller').DataTable({
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": 25,
            order: [[2, 'desc']]
        });
    });
  </script>
  <script>
  $(document).ready(function () {
      $('#tb_ambil_barang_marketer').DataTable({
          "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
          "pageLength": 25,
          order: [[2, 'desc']]
      });
  });
  </script>


</body>

</html>

                                                    