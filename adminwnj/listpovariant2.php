<?php
    session_start();
    include 'koneksi.php'; 
    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $id = $_GET["id"];
    $datapo = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$id'");
    $po = $datapo->fetch_assoc();
    $namapo = $po['namapo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top" class="sidebar-toggled">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>

        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h3><?= $namapo; ?></h3>
                <a href="listpoartikel.php">Kembali</a>
            </div>

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="satuan-tab" data-toggle="tab" href="#satuan" role="tab" aria-controls="satuan" aria-selected="true">Satuan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="set-tab" data-toggle="tab" href="#set" role="tab" aria-controls="set" aria-selected="false">Set</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="satuan" role="tabpanel" aria-labelledby="satuan-tab">
                    <div class="table-responsive px-2">
                        <table class="table table-striped table-bordered table-hover" id="tb_variant">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Variant</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $subtotal = 0;
                                    $query = $koneksi->query("SELECT 
                                                                    podetail.*,
                                                                    SUM(pomitra.jumlah) AS jumlah,
                                                                    REPLACE(RIGHT(podetail.variant, 2),
                                                                        ' ',
                                                                        '') AS ukuran,
                                                                    pomitra.idpodetail AS detail,
                                                                    pomitra.custom AS is_set
                                                                FROM
                                                                    poproduk
                                                                        RIGHT JOIN
                                                                    pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                        RIGHT JOIN
                                                                    pokategori ON pokategori.idpo = pomitra.idpo
                                                                        RIGHT JOIN
                                                                    podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                WHERE
                                                                    poproduk.idpoproduk = '$id'
                                                                        AND pomitra.custom = 'Satuan'
                                                                GROUP BY podetail.variant
                                                                ORDER BY podetail.idpodetail
                                                            ");
                                    $no = 1;
                                    while ($data = $query->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data['variant'] ?></td>
                                        <td><?= $data['jumlah'] ?></td>
                                        <td>Rp. <?= number_format($data['harga'] * $data['jumlah']) ?></td>
                                    </tr>
                                <?php 
                                        $subtotal = $subtotal + $data['jumlah'];
                                        $total_seluruh += $data['harga'] * $data['jumlah'];
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th><?= $subtotal ?></th>
                                    <th>Rp. <?= number_format($total_seluruh) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="set" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="table-responsive px-2">
                        <table class="table table-striped table-bordered table-hover" id="tb_variant1">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Variant</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $subtotal = 0;
                                    $query = $koneksi->query("SELECT 
                                                                    podetail.*,
                                                                    SUM(pomitra.jumlah) AS jumlah,
                                                                    pomitra.idpodetail AS detail,
                                                                    pomitra.custom AS is_set
                                                                FROM
                                                                    poproduk
                                                                        RIGHT JOIN
                                                                    pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                        RIGHT JOIN
                                                                    pokategori ON pokategori.idpo = pomitra.idpo
                                                                        RIGHT JOIN
                                                                    podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                WHERE
                                                                    poproduk.idpoproduk = '$id'
                                                                        AND pomitra.custom = 'Set'
                                                                GROUP BY podetail.variant
                                                                ORDER BY podetail.idpodetail
                                                            ");
                                    $no = 1;
                                    while ($data = $query->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data['variant'] ?></td>
                                        <td><?= $data['jumlah'] ?></td>
                                        <td>Harga akan di kalkulasikan menjadi kelipatan 3</td>
                                    </tr>
                                <?php 
                                        $subtotal = $subtotal + $data['jumlah'];
                                    }
                                ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th><?= $subtotal ?></th>
                                    <th>Rp. <?= number_format(($subtotal / 3) * 100000) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <hr />
            
            <h5>Summary</h5>
            <div class="table-responsive">
                <table class="table table-bordered" id="tb_kategori">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Variant</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = $koneksi->query("SELECT 
                                                            pokategori.namakategori,
                                                            SUM(pomitra.jumlah) AS jumlah,
                                                            podetail.*,
                                                            pomitra.idpodetail AS detail
                                                        FROM
                                                            poproduk
                                                                INNER JOIN
                                                            pomitra
                                                                INNER JOIN
                                                            pokategori
                                                                INNER JOIN
                                                            podetail ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                AND pokategori.idpo = pomitra.idpo
                                                                AND podetail.idpodetail = pomitra.idpodetail
                                                        WHERE
                                                            poproduk.idpoproduk = '$id'
                                                        GROUP BY podetail.variant
                                                        ORDER BY podetail.idpodetail
                                                    ");
                            $no = 1;
                            while ($data = $query->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['namakategori'] ?></td>
                                <td><?= $data['jumlah'] ?></td>
                            </tr>
                        <?php 
                                $total = $total + $data['jumlah'];
                            } 
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th><?= $total ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Wanoja 2020</span>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    Select "Logout" below if you are ready to end your current session.
                </div>
                
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_variant').DataTable({
                iDisplayLength: -1,
                "bLengthChange" : false,
                info: false,
                paging: false
            });
        });
    </script>
    
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_variant1').DataTable({
                iDisplayLength: -1,
                "bLengthChange" : false,
                info: false,
                paging: false
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_kategori').DataTable({
                iDisplayLength: -1,
                "bLengthChange" : false,
                info: false,
                paging: false
            });
        });
    </script>
</body>
</html>