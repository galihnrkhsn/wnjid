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
      <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <a href="javascript:void(0);" onclick="history.back();" style="float: right;">
                <i class="fas fa-arrow-left fa-m"> Kembali</i>
            </a>
            <h2 class="m-0 font-weight-bold text-secondary">List Po Dropship</h2>
        </div>

        <!-- Content Row -->
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tb_dropship">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama PO</th>
                            <th>Nama DB</th>
                            <th>Nama Sub DB</th>
                            <th>Invoice</th>
                            <th>List Dropship</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $datapo = $koneksi->query("SELECT 
                                                      subquery.invoice,
                                                      subquery.namapo,
                                                      subquery.namamitra,
                                                      subquery.agen,
                                                      subquery.reseller,
                                                      subquery.marketer
                                                  FROM (
                                                      SELECT 
                                                          podropship.invoice,
                                                          poproduk.namapo,
                                                          admin_mitra.namamitra,
                                                          GROUP_CONCAT(DISTINCT mitraagen.namaagen SEPARATOR ', ') AS agen,
                                                          GROUP_CONCAT(DISTINCT mitrareseller.namaagen SEPARATOR ', ') AS reseller,
                                                          GROUP_CONCAT(DISTINCT mitramarketer.namaagen SEPARATOR ', ') AS marketer,
                                                          MAX(podropship.iddropship) AS max_iddropship
                                                      FROM 
                                                          podropship 
                                                      LEFT JOIN 
                                                          mitraagen ON podropship.idmitraagen = mitraagen.idmitraagen 
                                                      LEFT JOIN 
                                                          mitrareseller ON podropship.idmitrareseller = mitrareseller.idmitrareseller 
                                                      LEFT JOIN 
                                                          mitramarketer ON podropship.idmitramarketer = mitramarketer.idmitramarketer 
                                                      LEFT JOIN 
                                                          admin_mitra ON admin_mitra.idadmin = podropship.idadmin 
                                                          OR mitraagen.idadmin = admin_mitra.idadmin 
                                                          OR mitrareseller.idadmin = admin_mitra.idadmin 
                                                          OR mitramarketer.idadmin = admin_mitra.idadmin 
                                                      INNER JOIN 
                                                          poproduk ON podropship.idpoproduk = poproduk.idpoproduk 
                                                      GROUP BY 
                                                          podropship.invoice,
                                                          poproduk.namapo,
                                                          admin_mitra.namamitra
                                                  ) AS subquery
                                                  ORDER BY 
                                                      subquery.max_iddropship DESC
                                                  LIMIT 0, 50000;

                                                ");
                        $no = $mulai + 1;

                        while ($tampilkan = $datapo->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $tampilkan['namapo']; ?></td>
                                <td><?= $tampilkan['namamitra']; ?></td>
                                <td>
                                    <?= $tampilkan['agen']; ?>
                                    <?= $tampilkan['reseller']; ?>
                                    <?= $tampilkan['marketer']; ?>
                                </td>
                                <td><?= $tampilkan['invoice']; ?></td>
                                <td><a href="detaildropship.php?id=<?= $tampilkan['invoice']; ?>" target="_blank">Klik Disini</a></td>
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