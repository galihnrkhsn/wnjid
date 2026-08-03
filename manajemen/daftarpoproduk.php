<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    $idmanage    = $_SESSION["idmanage"];
    $tipe        = $_SESSION["user_tipe"];

    $queryManage = $koneksi->query("SELECT * FROM management WHERE id='$idmanage'");
    $data = $queryManage->fetch_assoc();

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
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <a href="listpoartikel.php" style="float: right;">
              <i class="fas fa-arrow-left fa-m"> Kembali</i>
          </a>
          <h2 class="m-0 font-weight-bold text-secondary">List Po Per Invoice</h2>
      </div>

        <!-- Content Row -->
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tb_listpo_artikel">
                    <thead>
                        <tr>
                            <th style="width: 1%;">No</th>
                            <th>ID PO</th>
                            <th>Nama PO</th>
                            <th>Jumlah PO (Pcs/Pack)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $datapo = $koneksi->query("SELECT DISTINCT 
                                                      poproduk.idpoproduk, 
                                                      poproduk.namapo, 
                                                      SUM(pomitra.jumlah) AS jumlahnya
                                                  FROM 
                                                      poproduk 
                                                  INNER JOIN 
                                                      pomitra 
                                                  ON 
                                                      poproduk.idpoproduk = pomitra.idpoproduk 
                                                  WHERE 
                                                      pomitra.jumlah > 0 
                                                      AND poproduk.idpoproduk > 100
                                                  GROUP BY 
                                                      poproduk.idpoproduk
                                                  ");
                        $no = 1;

                        while ($tampilkan = $datapo->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $tampilkan['idpoproduk']; ?></td>
                            <td>
                                <a href="listpoinvoice.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></a>
                            </td>
                            <td>
                                <a href="listpoinvoice.php?id=<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['jumlahnya']; ?></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

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