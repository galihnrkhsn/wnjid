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
	$sql = "SELECT * FROM orderpengiriman WHERE invoice = '$invoice'";
	$query = $koneksi->query($sql);
	$pengiriman = $query->fetch_assoc();

    if (isset($_GET['idadmin'])) {
        $selectedIdAdmin = $_GET['idadmin'];
    
        // Ambil data terkait `idadmin` dari database
        $query = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = '$selectedIdAdmin'");
        $data = $query->fetch_assoc();
        
        // Kembalikan data sebagai JSON untuk digunakan di frontend
        // echo json_encode($data);
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
    <title>WNJ.ID</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
</head>
<body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="col-xl-12 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold">Bayar PO Manual</h6>
                                <a href="listpopembayaran.php" class="mb-0 text-primary">Kembali</a>
                            </div>

                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data" class="row">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="namapo" class="form-label mb-0">Nama PO</label>
                                            <select class="form-control form-control-sm" name="namapo" id="namapo">
                                                <option disabled="disabled" selected>~ Default Selected ~</option>
                                                <?php
                                                    $sqlpo = $koneksi->query("SELECT * FROM poproduk ORDER BY idpoproduk DESC");
                                                    while ($datapo = $sqlpo->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $datapo['idpoproduk'] ?>"><?= $datapo['namapo'] ?> (<?= $datapo['idpoproduk'] ?>)</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="namadb" class="form-label mb-0">Nama Distributor</label>
                                            <select class="form-control form-control-sm" name="namadb" id="namadb" onchange="updateIdAdmin()">
                                                <option disabled="disabled" selected>~ Default Selected ~</option>
                                                <?php
                                                    $sqlmitra = $koneksi->query("SELECT * FROM admin_mitra ORDER BY namamitra ASC");
                                                    while ($datamitra = $sqlmitra->fetch_assoc()) {
                                                ?>
                                                    <option value="<?= $datamitra['idadmin'] ?>"><?= $datamitra['namamitra'] ?> (<?= $datamitra['idadmin'] ?>)</option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        Nama Mitra: <?= $data['namamitra'] ?>
                                        <p id="idadmin"></p>

                                        <div class="form-group" id="invoice"></div>

                                        
                                        <div class="form-group">
                                            <label for="metode-bayar" class="form-label mb-0">Metode Bayar</label>
                                            <select class="form-control form-control-sm" name="metode_bayar" id="metode-bayar">
                                                <option disabled="disabled" selected>~ Default Selected ~</option>
                                                <option value="Transfer Bank">Transfer Bank</option>
                                                <option value="Deposit">Deposit</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="bank-pengirim" class="form-label mb-0">Nama Bank Pengirim</label>
                                            <input type="text" class="form-control form-control-sm" name="bank_pengirim" id="bank-pengirim" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="norek" class="form-label mb-0">No Rekening</label>
                                            <input type="text" class="form-control form-control-sm" name="norekening" id="norek" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jumlah" class="form-label mb-0">Jumlah Bayar</label>
                                            <input type="text" class="form-control form-control-sm" name="jumlah_bayar" id="jumlah" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="rekening-pembayaran" class="form-label mb-0">Rekening Pembayaran</label>
                                            <select class="form-control form-control-sm" name="bank_pengirim" id="rekening-pembayaran" required>
                                                <option disabled="disabled" selected>~ Default Selected ~</option>
                                                <option>Mandiri 1300017715213</option>
                                                <option>Muamalat 1100003930</option>
                                                <option>Bank Syariah Indonesia (BSI) 7105696706</option>
                                                <option>BRI 076201007469504</option>
                                                <option>BCA 7751043434</option>    
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal" class="form-label mb-0">Tanggal Pembayaran</label>
                                            <input type="date" class="form-control form-control-sm" name="tanggal_pembayaran" id="tanggal" required>
                                        </div>
                                    </div>
                                </form>
                            </div>
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

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/dist/js/jquery.min.js"></script>
    <script src="assets/dist/js/bootstrap.min.js"></script>
    <script src="assets/dist/DataTables/datatables.min.js"></script>
    <script type="text/javascript">
        function updateIdAdmin() {
            var selectedIdAdmin = document.getElementById('namadb').value;

            $.ajax({
                type: 'GET',
                url: '', // URL mengarah ke file PHP yang sama
                data: { idadmin: selectedIdAdmin },
                dataType: 'json',
                success: function (response) {
                    // Tampilkan atau gunakan respons yang diterima
                    document.getElementById('idadmin').textContent = response.idadmin;
                }
            });
        }

        $(document).ready( function () {
            $('#tb_surat_manual_po').DataTable({
                "lengthMenu": [[25, 50, 100, 200, 300, 400], [25, 50, 100, 200 , 300, 400]]
            });
        });

        $(document).ready(function() {
            $('#namadb').change(function() {
                var jenisPo = $('#namapo').val();
                var jenisDb = $('#namadb').val();

                $.ajax({
                    type: 'GET',
                    url: 'get_invoice.php',
                    data: {
                        jenisPo: jenisPo,
                        jenisDb: jenisDb
                    },
                    success: function(data) {
                        $('#invoice').html(data);
                    }
                })
            })
        });
    </script>
</body>
</html>