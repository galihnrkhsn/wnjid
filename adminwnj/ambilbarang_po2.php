<?php 
    session_start();

    include 'koneksi.php'; 


    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

        $invoice = $_GET["invoice"];
        $mitra   = $_GET["mitra"];

    $sql = $koneksi->query("SELECT 
                                    pomitra.idpoproduk,
                                    bukapo.jenis_po,
                                    CONCAT_WS(', ', admin_mitra.namamitra, mitraagen.namaagen, mitramarketer.namaagen, mitrareseller.namaagen) AS namamitra,
                                    CONCAT_WS(', ', admin_mitra.idadmin, mitraagen.idmitraagen, mitramarketer.idmitramarketer, mitrareseller.idmitrareseller) AS idmitra
                                FROM
                                    pomitra
                                        LEFT JOIN
                                    admin_mitra ON pomitra.idmitra = admin_mitra.idadmin
                                        LEFT JOIN
                                    mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen
                                        LEFT JOIN
                                    mitrareseller ON pomitra.idmitrareseller = mitrareseller.idmitrareseller
                                        LEFT JOIN
                                    mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer
                                        LEFT JOIN
                                    bukapo ON bukapo.idpoproduk = pomitra.idpoproduk
                                WHERE
                                    pomitra.invoice = '$invoice'
                            ");
    $datainv = $sql->fetch_assoc();
    $idpoproduk = $datainv['idpoproduk'];
    $jenis_po = $datainv['jenis_po'];
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
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-flex align-items-center jusitfy-content-between mb-2">
                        <h3 class="mb-0 text-gray-800 font-weight-bold">Ambil Barang PO</h3>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0 font-weight-bold text-primary">Invoice <?= $invoice ?></h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="font-weight-bold">Nama Mitra: <?= $datainv['namamitra'] ?></h4>
                                    <div class="table-responsive">
                                        <form method="post">
                                            <table class="table table-bordered" style="font-size: .875rem">
                                                <thead>
                                                    <tr>
                                                        <th><input type='checkbox' id='checkAll' ></th>
                                                        <th>Nama Produk</th>
                                                        <th>QTY</th>
                                                        <th width="300">Ambil</th>
                                                        <th>Krg</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if ($jenis_po === "PO Inner Custom") {
                                                            $datapo = $koneksi->query("SELECT 
                                                                                            pomitra.custom,
                                                                                            pomitra.jumlah
                                                                                        FROM
                                                                                            pomitra
                                                                                        WHERE
                                                                                            pomitra.invoice = '$invoice'
                                                                                                AND pomitra.jumlah > 0
                                                                                        GROUP BY pomitra.custom
                                                                                    ");
                                                        }
                                                        while ($dataproduk = $datapo->fetch_assoc()) {
                                                            $id = $dataproduk['idpomitra'];
                                                            $datamitra = $koneksi->query("SELECT 
                                                                                                SUM(surat_jalan_po.progres) AS progresnya
                                                                                            FROM
                                                                                                surat_jalan_po
                                                                                            WHERE
                                                                                                surat_jalan_po.idpomitra = '$id'
                                                                                        ");
                                                            $tampilprogres = $datamitra->fetch_assoc();
                                                            $kurang = $dataproduk['jumlah']-$tampilprogres['progresnya'];
                                                            $idpoproduk = $dataproduk['idpoproduk'];

                                                            $custom = $dataproduk['custom'];
                                                            $string = $custom;
                                                            // Mengubah semua huruf menjadi huruf kecil
                                                            $string = strtolower($string);
                                                            // Mengganti spasi dengan tanda hubung
                                                            $string = str_replace(' ', '-', $string);
                                                            // Menghilangkan tanda - di awal string
                                                            $string = ltrim($string, '-');
                                                            // Menghapus karakter yang tidak diperlukan (opsional, jika diperlukan)
                                                            $string = preg_replace('/[^a-z0-9\-]/', '', $string);
                                                    ?>
                                                        <tr>
                                                            <td><input type="checkbox" name="update[]" value="<?= $id ?>"></td>
                                                            <td>
                                                                <?php
                                                                    $query = $koneksi->query("SELECT 
                                                                                                    podetail.*, pomitra.*
                                                                                                FROM
                                                                                                    pomitra
                                                                                                        INNER JOIN
                                                                                                    podetail ON pomitra.idpodetail = podetail.idpodetail
                                                                                                WHERE
                                                                                                    pomitra.invoice = '$invoice'
                                                                                                        AND pomitra.jumlah > 0
                                                                                                        AND pomitra.custom = '$custom'
                                                                                            ");
                                                                    $first = true;
                                                                    while ($data_produk = $query->fetch_assoc()) {
                                                                        if (!$first) {
                                                                            echo " - ";
                                                                        }
                                                                        $first = false;
                                                                ?>
                                                                    <?= $data_produk['variant'] ?>
                                                                <?php 
                                                                        $jumlah = $data_produk['jumlah'];
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td><?= $jumlah; ?></td>
                                                            <td>
                                                                <?php if ($kurang == 0) : ?>
                                                                    <?= $dataproduk['jumlah'] ?>
                                                                <?php else : ?>
                                                                <?php endif; ?>
                                                                <input type="number" name="progres_<?= $id ?>" class="form-control form-control-sm" value="0" max="<?= $dataproduk['jumlah']; ?>" min="0" required>
                                                            </td>
                                                            <td>
                                                                <?= $kurang ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>

                                            <button type="submit" class="btn btn-sm btn-success" name="but_update">Simpan</button>
                                            <button type="submit" class="btn btn-sm btn-warning" name="but_kurang">Kurang</button>
                                            <a href="list_ambilbarang_po.php" class="btn btn-sm btn-danger">Kembali</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script type="text/javascript">
        $(document).ready(function(){
            // Check/Uncheck ALl
            $ ('#checkAll').change(function() {
                if ($(this).is(':checked')) {
                    $('input[name="update[]"]').prop('checked',true);
                } else {
                    $('input[name="update[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $ ('input[name="update[]"]').click(function() {
                var total_checkboxes = $('input[name="update[]"]').length;
                var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                if (total_checkboxes_checked == total_checkboxes) {
                    $('#checkAll').prop('checked',true);
                } else {
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>
</body>
</html>