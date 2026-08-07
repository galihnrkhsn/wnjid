<?php 
    session_start();
    include 'koneksi.php';
    $idpoproduk = $_GET['id'];
    $datapo = $koneksi->query("SELECT 
                                    poproduk.namapo, popembayaran.jenis, poproduk.idpoproduk
                                FROM
                                    poproduk
                                        JOIN
                                    popembayaran ON poproduk.idpoproduk = popembayaran.idpoproduk
                                WHERE
                                    poproduk.idpoproduk = '$idpoproduk'
                            ");
    $tampilpo = $datapo->fetch_assoc();
    $idpo = $tampilpo['idpoproduk'];
    $nama = $tampilpo['namapo'];
    $jenis = $tampilpo['jenis'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>WNJ.ID</title>
    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
<style type="text/css">
    body {
        padding-right: 0px ! important;
    }
    td, th {
        white-space: nowrap;
        width: auto;
    }
    p {
        margin-bottom: 0;
    }
</style>
<body id="page-top" class="sidebar-toggled">

    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        
        <div class="container-fluid">
            <!-- Content Row -->
            <div class="row">
                <div class="col-xl-12 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Pembayaran DP PO <?= $jenis  ?></h6>
                            <h6>
                                <strong>
                                    <a href="listpopembayaran.php?id=<?= $idpo ?>">
                                        <span class="fa fa-chevron-left"></span> Kembali
                                    </a>
                                </strong>
                            </h6>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <div>
                                <h2><?= $tampilpo['namapo']; ?></h2>
                                <div class="table-responsive py-4">
                                    <form method="post" enctype="multipart/form-data">
                                        <table class="table table-bordered" id="tb_dp">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th><input type="checkbox" id="checkAll"></th>
                                                    <th>Status</th>
                                                    <th>Nama Mitra</th>
                                                    <th>Nama CS</th>
                                                    <th>Invoice</th>
                                                    <th>Payment 1 / Dp</th>
                                                    <th>Payment 2</th>
                                                    <th>Payment 3</th>
                                                    <th>Pelunasan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sqlpo = $koneksi->query("SELECT
                                                                                    pomitra.idpomitra,
                                                                                    pomitra.invoice,
                                                                                    pomitra.status,
                                                                                    admin_mitra.namamitra,
                                                                                    admin_mitra_cs.namacs,
                                                                                    popembayaran.idpembayaran
                                                                                FROM
                                                                                    pomitra
                                                                                        LEFT JOIN
                                                                                    admin_mitra ON admin_mitra.idadmin = pomitra.idmitra
                                                                                        LEFT JOIN
                                                                                    admin_mitra_cs ON admin_mitra_cs.idadmin = pomitra.idmitra
                                                                                        LEFT JOIN
                                                                                    popembayaran ON popembayaran.invoice = pomitra.invoice
                                                                                WHERE
                                                                                    pomitra.idpoproduk = '$idpo'
                                                                                GROUP BY pomitra.invoice
                                                                                ORDER BY popembayaran.idpembayaran ASC
                                                                            ");
                                                    $no = 1;
                                                    while ($datapo = $sqlpo->fetch_assoc()) {
                                                        $idpomitra = $datapo['idpomitra'];
                                                        $invoice = $datapo['invoice'];
                                                        $sqlpay1 = $koneksi->query("SELECT * FROM popembayaran WHERE invoice = '$invoice' AND jenis = 'Payment DP 1'ORDER BY idpembayaran");
                                                        $datapay1 = $sqlpay1->fetch_assoc();
                                                        $sqlpay2 = $koneksi->query("SELECT * FROM popembayaran WHERE invoice = '$invoice' AND jenis = 'Payment DP 2'ORDER BY idpembayaran");
                                                        $datapay2 = $sqlpay2->fetch_assoc();
                                                        $sqlpay3 = $koneksi->query("SELECT * FROM popembayaran WHERE invoice = '$invoice' AND jenis = 'Payment DP 3'ORDER BY idpembayaran");
                                                        $datapay3 = $sqlpay3->fetch_assoc();
                                                        $sqlpelunasan = $koneksi->query("SELECT * FROM popembayaran WHERE invoice = '$invoice' AND jenis = 'Lunas'ORDER BY idpembayaran");
                                                        $datapelunasan = $sqlpelunasan->fetch_assoc();
                                                ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td>
                                                            <input type="checkbox" name="update[]" value="<?= $idpomitra ?>">
                                                            <input type="hidden" name="invoice[]" value="<?= $invoice ?>">
                                                        </td>
                                                        <td><?= $datapo['status'] ?></td>
                                                        <td><?= $datapo['namamitra'] ?></td>
                                                        <td><?= $datapo['namacs'] ?></td>
                                                        <td>
                                                            <?php if ($datapo['status'] == "Belum DP") : ?>
                                                                <p class="mb-0"><?= $datapo['invoice'] ?></p>
                                                            <?php else : ?>
                                                                <a href="detail_popembayaran.php?id=<?= $datapo['invoice'] ?>"><?= $datapo['invoice'] ?></a>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            Rp. <?= number_format($datapay1['jmlhtransfer']) ?>
                                                            <p><?= $datapay1['tgl'] ?></p>
                                                            <p><?= $datapay1['bankpengirim'] ?></p>
                                                        </td>
                                                        <td>
                                                            Rp. <?= number_format($datapay2['jmlhtransfer']) ?>
                                                            <p><?= $datapay2['tgl'] ?></p>
                                                            <p><?= $datapay2['bankpengirim'] ?></p>
                                                        </td>
                                                        <td>
                                                            Rp. <?= number_format($datapay3['jmlhtransfer']) ?>
                                                            <p><?= $datapay3['tgl'] ?></p>
                                                            <p><?= $datapay3['bankpengirim'] ?></p>
                                                        </td>
                                                        <td>
                                                            Rp. <?= number_format($datapelunasan['jmlhtransfer']) ?>
                                                            <p><?= $datapelunasan['tgl'] ?></p>
                                                            <p><?= $datapelunasan['bankpengirim'] ?></p>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-sm btn-primary" name="confirm">Confirm</button>
                                            <button type="submit" class="btn btn-sm btn-danger" name="batal">Batal</button>
                                        </div>
                                    </form>
                                    <?php
                                        if (isset($_POST['confirm'])) {
                                            if (isset($_POST['update'])) {
                                                foreach ($_POST['update'] AS $updateid) {
                                                    $idpomitra = $updateid;

                                                    $sqlpo = $koneksi->query("SELECT * FROM pomitra WHERE idpomitra = '$idpomitra'");
                                                    $datapo = $sqlpo->fetch_assoc();
                                                    
                                                    if ($datapo['status'] == "Belum DP") {
                                                        $status = "Confirm Payment DP 1";
                                                    } elseif ($datapo['status'] == "Payment DP 1") {
                                                        $status = "Confirm Payment DP 1";
                                                    } elseif ($datapo['status'] == "Payment DP 2") {
                                                        $status = "Confirm Payment DP 2";
                                                    } elseif ($datapo['status'] == "Payment DP 3") {
                                                        $status = "Confirm Payment DP 3";
                                                    } elseif ($datapo['status'] == "Pelunasan") {
                                                        $status = "Lunas";
                                                    } else {
                                                        $status = "Kondisi salah!";
                                                    }

                                                    $sqlupd = $koneksi->query("UPDATE pomitra SET status = '$status' WHERE idpomitra = '$idpomitra'");
                                                    if ($sqlupd) {
                                                        echo "
                                                            <script>
                                                                alert('Pembayaran berhasil di konfirmasi')
                                                                location='popembayarankonin.php?id=$idpo'
                                                            </script>
                                                        ";
                                                    } else {
                                                        echo "
                                                            <script>
                                                                alert('Gagal mengkonfirmasi pembayaran')
                                                                location='popembayarankonin.php?id=$idpo'
                                                            </script>
                                                        ";
                                                    }
                                                }
                                            } else {
                                                echo "
                                                    <script>
                                                        alert('Tidak ada data yang dirubah')
                                                        location='popembayarankonin.php?id=$idpo'
                                                    </script>
                                                ";
                                            }
                                        } elseif (isset($_POST['batal'])) {
                                            if (isset($_POST['update'])) {
                                                foreach ($_POST['update'] AS $updateid) {
                                                    $idpomitra = $updateid;

                                                    $sqlinv = $koneksi->query("SELECT invoice FROM pomitra WHERE idpomitra = '$idpomitra'");
                                                    $datainv = $sqlinv->fetch_assoc();
                                                    $inv = $datainv['invoice'];

                                                    $sqlpo = $koneksi->query("UPDATE pomitra SET status = 'Belum DP' WHERE invoice = '$inv'");

                                                    $sqlpembayaran = $koneksi->query("DELETE FROM popembayaran WHERE invoice = '$inv'");
                                                    $sqlbuktitf = $koneksi->query("DELETE FROM buktitf WHERE invoice = '$inv'");

                                                    if ($sqlpo && $sqlpembayaran && $sqlbuktitf) {
                                                        echo "
                                                            <script>
                                                                alert('Pembatalan pembayaran berhasil')
                                                                location='popembayarankonin.php?id=$idpo'
                                                            </script>
                                                        ";
                                                    } else {
                                                        echo "
                                                            <script>
                                                                alert('Gagal membatalkan pembayaran')
                                                                location='popembayarankonin.php?id=$idpo'
                                                            </script>
                                                        ";
                                                    }
                                                }
                                            } else {
                                                echo "
                                                    <script>
                                                        alert('Tidak ada data yang dirubah')
                                                        location='popembayarankonin.php?id=$idpo'
                                                    </script>
                                                ";
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2020</span>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <?php include "settingdatatables.php"; ?>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_dp').DataTable({
                "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                columnDefs: [{ orderable: false, targets: 1 }]
            });
        });

        $(document).ready(function(){
            // Check / Uncheck All
            $('#checkAll').change(function(){
                if ($(this).is(':checked')) {
                    $('input[name="update[]"]').prop('checked',true);
                } else {
                    $('input[name="update[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $('input[name="update[]"]').click(function(){
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