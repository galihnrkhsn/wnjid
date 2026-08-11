<?php
    session_start();

    include 'koneksi.php';

    if (!isset($_SESSION["administrator"])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // $pesan: satu notifikasi ringkas per submit, menggantikan alert()+redirect
    // yang dulu di-echo berkali-kali (satu per baris) di dalam loop.
    $pesan = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['opsi'])) {
        $idProduk = array_map('intval', $_POST['id'] ?? []);
        $opsi     = $_POST['opsi'];

        if (empty($idProduk)) {
            $pesan = ['type' => 'danger', 'text' => 'Pilih minimal 1 produk terlebih dahulu.'];
        } elseif ($opsi === 'Publish' || $opsi === 'Unpublish') {
            // Aksi berlaku produk secara keseluruhan: semua varian di bawah produk terpilih
            // ikut di-publish/unpublish bareng. Kontrol per-varian ada di maintenance_produk.php.
            $statusValue = $opsi === 'Publish' ? 0 : 1;
            $stmt        = $koneksi->prepare("UPDATE variants SET status = ?, updated_at = NOW() WHERE idproducts = ?");
            $sukses      = 0;
            foreach ($idProduk as $pid) {
                $stmt->bind_param('ii', $statusValue, $pid);
                $stmt->execute();
                $sukses += $stmt->affected_rows > 0 ? 1 : 0;
            }
            $label = $opsi === 'Publish' ? 'dipublish' : 'di-unpublish';
            $pesan = ['type' => 'success', 'text' => "$sukses produk berhasil $label."];
        }
    }

    $tab = ($_GET['tab'] ?? 'publish') === 'unpublish' ? 'unpublish' : 'publish';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- jQuery (hanya sekali, jangan dobel) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .action-toolbar {
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: .35rem;
            padding: .9rem 1rem;
            margin-bottom: 1rem;
        }
        .action-toolbar .toolbar-hint {
            font-size: .8rem;
            color: #858796;
            margin-bottom: .6rem;
        }
        .action-toolbar .btn-group-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            align-items: center;
        }
        .nav-buttons a {
            margin-right: .4rem;
            margin-bottom: .5rem;
        }
        #tb_produk td {
            vertical-align: middle;
        }
        .status-tabs a {
            display: inline-block;
            padding: .5rem 1.25rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: .85rem;
            text-decoration: none;
            border: 1px solid #d1d3e2;
            color: #5a5c69;
            margin-right: .5rem;
        }
        .status-tabs a.active {
            background: #4e73df;
            border-color: #4e73df;
            color: #fff;
        }
        .status-tabs a.active.tab-unpublish {
            background: #858796;
            border-color: #858796;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <!-- CONTENT -->
            <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-2">
                <div>
                    <h3 class="mb-1"><strong>Daftar Produk</strong></h3>
                    <p class="text-muted mb-0">
                        Kelola status publish per produk di sini. Untuk detail varian, stock, harga, diskon, atau Grade,
                        klik tombol <i class="fas fa-pen"></i> Edit pada baris produknya.
                    </p>
                </div>
            </div>

            <div class="nav-buttons mb-3">
                <a class="btn btn-success" href="tambah_produk"><i class="fas fa-plus"></i> Tambah Produk Baru</a>
                <a class="btn btn-outline-primary" href="maintenance_produk.php"><i class="fas fa-list"></i> Kelola Produk (Detail)</a>
                <a class="btn btn-outline-primary" href="update_produk.php"><i class="fas fa-file-excel"></i> Update via Excel</a>
                <a class="btn btn-outline-secondary" href="foto_produk"><i class="fas fa-image"></i> Kelola Foto</a>
            </div>

            <form action="excelproduk3.php" method="GET" class="mb-3">
                <input type="hidden" name="status" value="0">
                <button type="submit" class="btn btn-outline-success btn-sm"><i class="fas fa-download"></i> Export Produk yang Sudah Publish (Excel)</button>
            </form>

            <?php if ($pesan !== null): ?>
                <div class="alert alert-<?= htmlspecialchars($pesan['type']) ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($pesan['text']) ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>

            <div class="status-tabs mb-3">
                <a href="?tab=publish" class="<?= $tab === 'publish' ? 'active' : '' ?>"><i class="fas fa-eye"></i> Publish</a>
                <a href="?tab=unpublish" class="<?= $tab === 'unpublish' ? 'active tab-unpublish' : '' ?>"><i class="fas fa-eye-slash"></i> Unpublish</a>
            </div>

            <form method="post" id="formProduk">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="tb_produk">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th><input type='checkbox' id='checkAll'> Pilih</th>
                                <th>Nama Produk</th>
                                <th>Kategori Produk</th>
                                <th>Grade</th>
                                <th>Jumlah Varian</th>
                                <th>Total Stock</th>
                                <th>Status</th>
                                <th><i class="fa fa-cog"></i> Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="action-toolbar">
                    <p class="toolbar-hint mb-2">
                        <i class="fas fa-info-circle"></i>
                        Centang produk pada tabel di atas, lalu pilih aksi. Aksi berlaku untuk SEMUA varian dari produk yang dicentang.
                    </p>
                    <div class="btn-group-actions">
                        <button type="submit" name="opsi" value="Publish" class="btn btn-success btn-sm"
                                onclick="return konfirmasiAksi('mempublish');">
                            <i class="fas fa-eye"></i> Publish Terpilih
                        </button>
                        <button type="submit" name="opsi" value="Unpublish" class="btn btn-secondary btn-sm"
                                onclick="return konfirmasiAksi('meng-unpublish');">
                            <i class="fas fa-eye-slash"></i> Unpublish Terpilih
                        </button>
                    </div>
                </div>
            </form>
            <!-- CONTENT END -->
        </div>
    </div>
        <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script type="text/javascript">
        function konfirmasiAksi(labelAksi) {
            var jumlah = $('input[name="id[]"]:checked').length;
            if (jumlah === 0) {
                alert('Centang minimal 1 produk terlebih dahulu.');
                return false;
            }
            return confirm('Yakin ' + labelAksi + ' untuk ' + jumlah + ' produk terpilih (berlaku ke semua variannya)?');
        }

        $(document).ready(function(){

            // Check/Uncheck All
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="id[]"]').prop('checked',true);
                }else{
                    $('input[name="id[]"]').each(function(){
                        $(this).prop('checked',false);
                    });
                }
            });

            // Checkbox click
            $(document).on('click', 'input[name="id[]"]', function(){
                var total_checkboxes = $('input[name="id[]"]').length;
                var total_checkboxes_checked = $('input[name="id[]"]:checked').length;

                if(total_checkboxes_checked == total_checkboxes){
                    $('#checkAll').prop('checked',true);
                }else{
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('#tb_produk').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "api/data_produk.php",
                    data: function (d) {
                        d.tab = <?= json_encode($tab) ?>;
                    }
                },
                order: [[ 0, "desc" ]],
                columnDefs: [
                    { orderable: false, targets: [1, 8] } // kolom yang tidak bisa sort
                ],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                pageLength: 10
            });
        });
    </script>
</body>

</html>
