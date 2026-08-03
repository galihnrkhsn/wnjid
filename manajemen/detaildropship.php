<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    $idmanage    = $_SESSION["idmanage"];
    $tipe        = $_SESSION["user_tipe"];

    $queryManage = $koneksi->query("SELECT * FROM management WHERE id='$idmanage'");
    $data = $queryManage->fetch_assoc();

    $invoice=$_GET["id"];
    $idpoproduk=$_GET["idpoproduk"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
     <div class="container mt-5">
        <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <a href="listpoartikel.php" style="float: right;">
                  <i class="fas fa-arrow-left fa-m"> Kembali</i>
              </a>
              <h2 class="m-0 font-weight-bold text-secondary">List Po Artikel</h2>
          </div>
          <br>
          <br>

          <!-- Content Row -->
          <div class="row" style="margin: auto;">
              <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Nama PO</th>
                              <th>Invoice</th>
                              <th>Nama Pengirim</th>
                              <th>Telepon Pengirim</th>
                              <th>Nama Penerima</th>
                              <th>Telepon Penerima</th>
                              <th width="20">Alamat Penerima</th>
                              <th width="20">Keterangan</th>
                              <th>Ekspedisi</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php 
                          $datapodropship = $koneksi->query("
                              SELECT * 
                              FROM podropship 
                              INNER JOIN poproduk ON poproduk.idpoproduk = podropship.idpoproduk 
                              WHERE podropship.invoice = '$invoice'
                          ");
                          $no = 1;

                          while ($tampilkan = $datapodropship->fetch_assoc()) {
                          ?>
                              <tr>
                                  <td><?php echo $no++; ?></td>
                                  <td><?php echo $tampilkan['namapo']; ?></td>
                                  <td><?php echo $tampilkan['invoice']; ?></td>
                                  <td><?php echo $tampilkan['namapengirim']; ?></td>
                                  <td><?php echo $tampilkan['tlppengirim']; ?></td>
                                  <td><?php echo $tampilkan['namapenerima']; ?></td>
                                  <td><?php echo $tampilkan['tlppenerima']; ?></td>
                                  <td><?php echo $tampilkan['alamatpenerima']; ?></td>
                                  <td><?php echo $tampilkan['keterangan']; ?></td>
                                  <td><?php echo $tampilkan['ekspedisi']; ?></td>
                              </tr>
                          <?php } ?>
                      </tbody>
                  </table>
              </div>
          </div><!-- Content Row -->

     </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include "assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <!-- END SCRIPT -->
</body>
</html>