<?php
    session_start();
    include "koneksi.php";

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('Anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php'</script>";
        header('location:login.php');
        exit();
    }

    $idpoproduk = $_GET['id'];
    $query = "SELECT poproduk.idpoproduk, poproduk.namapo FROM poproduk
                WHERE poproduk.idpoproduk = '$idpoproduk'
            ";
    $sql = mysqli_query($koneksi, $query);
    $datapo = mysqli_fetch_array($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Pre Order | WNJ.ID</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top" class="sidebar-toggled">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>

        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0 fw-semibold"><?= $datapo['namapo'] ?></h4>
            </div>

            <?php if ($idpoproduk == 186 or $idpoproduk == 187) : ?>
                <div class="my-3">
                    <a href="hampers.php?id=<?= $idpoproduk ?>" class="btn btn-primary btn-sm">Tambah Voal Hamper</a>
                </div>
            <?php else : ?>
                <div class="my-3">
                    <a href="formpo.php?id=<?= $idpoproduk ?>" class="btn btn-primary btn-sm">Tambah Invoice</a>
                </div>
            <?php endif; ?>

            <div>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#pre-order" class="nav-item nav-link active">Semua PO</a></li>
                    <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Belum Bayar</a></li>
                </ul>

                <div class="tab-content">
                    <div id="pre-order" class="tab-pane fade show active" role="tabpanel1">
                        <div class="card shadow-sm my-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form method="post">
                                        <table class="table table-striped" id="tbmaximus">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>#</th>
                                                    <th>Invoice</th>
                                                    <th>Nama DB</th>
                                                    <th>Sub DB</th>
                                                    <th>Kemitraaan</th>
                                                    <th>Status PO</th>
                                                    <th>Proses PO</th>
                                                    <th>Waktu</th>
                                                </tr>
                                            </thead>
        
                                            <tbody>
                                                <?php
                                                    $datapo = $koneksi->query("SELECT
                                                                admin_mitra.idadmin,
                                                                admin_mitra.namamitra,
                                                                poproduk.namapo,
                                                                mitraagen.namaagen AS agen,
                                                                mitrareseller.namaagen AS reseller,
                                                                mitramarketer.namaagen AS marketer,
                                                                pomitra.idpomitra,
                                                                pomitra.invoice,
                                                                pomitra.status,
                                                                pomitra.tgl,
                                                                pomitra.waktu,
                                                                pomitra.proses,
                                                                poproduk.namapo,
                                                                poproduk.idpoproduk
                                                                FROM pomitra
                                                                LEFT JOIN mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen
                                                                LEFT JOIN mitrareseller ON pomitra.idmitrareseller = mitrareseller.idmitrareseller
                                                                LEFT JOIN mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer
                                                                LEFT JOIN admin_mitra ON pomitra.idmitra = admin_mitra.idadmin
                                                                    OR mitraagen.idadmin = admin_mitra.idadmin
                                                                    OR mitrareseller.idadmin = admin_mitra.idadmin
                                                                    OR mitramarketer.idadmin = admin_mitra.idadmin
                                                                INNER JOIN poproduk ON pomitra.idpoproduk = poproduk.idpoproduk
                                                                WHERE pomitra.idpoproduk = '$idpoproduk'
                                                                GROUP BY pomitra.invoice
                                                            ");
                                                    $no = 1;
                                                    while ($tampilkan = $datapo->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><input class="" type="checkbox" name="update[]" value="<?= $tampilkan['invoice']; ?>"></td>
                                                        <td><?= $tampilkan['invoice'] ?></td>
                                                        <td><?= $tampilkan['namamitra'] ?></td>
                                                        <td>
                                                            <?php if ($tampilkan['agen'] <> '') : ?>
                                                                <div class="badge bg-info text-white rounded-pill">Agen</div>
                                                            <?php elseif ($tampilkan['reseller'] <> '') : ?>
                                                                <div class="badge bg-warning text-white rounded-pill">Reseller</div>
                                                            <?php elseif ($tampilkan['marketer'] <> '') : ?>
                                                                <div class="badge bg-danger text-white rounded-pill">Marketer</div>
                                                            <?php else : ?>
                                                                <div class="badge bg-success text-white rounded-pill">Distributor</div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td></td>
                                                        <td><?= $tampilkan['status'] ?></td>
                                                        <td></td>
                                                        <td><?= $tampilkan['tgl'] ?> / <?= $tampilkan['waktu'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>

                                            </tfoot>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <?php include "settingdatatables.php"; ?>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script type="text/javascript">
        function checkAllDB(box) {
            if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
                var idpomitra_dbcheck = document.getElementsByName("idpomitra_dbcheck[]");
                var jml=idpomitra_dbcheck.length;
                var b=0;
                for (b=0;b<jml;b++) {
                    idpomitra_dbcheck[b].checked=true;
                }
            } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
                var idpomitra_dbcheck = document.getElementsByName("idpomitra_dbcheck[]");
                var jml=idpomitra_dbcheck.length;
                var b=0;
                for (b=0;b<jml;b++) {
                    idpomitra_dbcheck[b].checked=false;
                }
            }
        }
    </script>
</body>
</html>