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
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
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
            <!-- Content Row -->          
            <h3><strong>Input Data Buka PO</strong></h3>
            <nav style="width: 100%;">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-db-tab" data-toggle="tab" href="#nav-db" role="tab" aria-controls="nav-home" aria-selected="true">PO Standar</a>
                    <a class="nav-item nav-link" id="nav-agen-tab" data-toggle="tab" href="#nav-agen" role="tab" aria-controls="nav-profile" aria-selected="false">PO Custom Tab</a>
                </div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-db" role="tabpanel" aria-labelledby="nav-db-tab">
                        <form method="post" enctype="multipart/form-data" class="form-group">
                            <div class="form-group">
                                <label for="idpoproduk">PO Produk</label><br>
                                <select class="form-control" id="idpoproduk" name="idpoproduk" required>
                                    <option disabled='disabled' value="" selected>~Pilih PO Produk~</option>
                                    <?php
                                        $ambil=$koneksi->query("SELECT * FROM poproduk ORDER BY idpoproduk DESC");
                                        while($row=$ambil->fetch_assoc()){
                                    ?>
                                        <option value="<?php echo $row['idpoproduk']; ?>"><?php echo $row['idpoproduk']; ?> | <?php echo $row['namapo']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <?php $today = date('Y-m-d'); ?>

                            <label>Tanggal Tutup PO</label><br>
                            <input type="date" name="tgl" class="form-control" value="<?= $today ?>" required><br>

                            <label>Tanggal Acc DB</label><br>
                            <input type="date" name="tgl_acc_db" class="form-control" value="<?= $today ?>" required><br>

                            <label>Tanggal Ubah PO</label><br>
                            <input type="date" name="tgl_ubah" class="form-control" value="<?= $today ?>" required><br>

                            <label>Tanggal Bayar PO</label><br>
                            <input type="date" name="tgl_bayar" class="form-control" value="<?= $today ?>" required><br>    

                            <label>Tanggal Dropship</label><br>
                            <input type="date" name="tgl_dropship" class="form-control" value="<?= $today ?>" required><br>
                            
                            <div class="form-group">
                                <label for="jenis_mitra">Jenis Mitra</label><br>
                                <select class="form-control" id="jenis_mitra" name="jenis_mitra" required>
                                    <option disabled='disabled' value="" selected>~Pilih Jenis Mitra~</option>
                                    <option value="Semua Mitra">Semua Mitra</option>
                                    <option value="Distributor">Distributor</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="jenis_po">Jenis PO</label><br>
                                <select class="form-control" id="jenis_po" name="jenis_po" required>
                                    <option disabled='disabled' value="" selected>~Pilih Jenis PO~</option>
                                    <option value="PO Mandiri">PO Mandiri</option>
                                    <option value="PO Bundling 2">PO Bundling 2</option>
                                    <option value="PO Bundling 5">PO Bundling 5</option>
                                    <option value="PO Tab tanpa stok">PO Tab tanpa Stok</option>
                                    <option value="PO dengan Stok">PO dengan Stok</option>
                                    <option value="PO tanpa Stok">PO tanpa Stok</option>
                                    <option value="PO Miki Custom">PO Miki Custom</option>
                                    <option value="PO Miki Polos">PO Miki Polos</option>
                                    <option value="PO Brooch Custom">PO Brooch Custom</option>
                                    <option value="PO Bagi Rata">PO Bagi Rata</option>
                                    <option value="PO Kolibri">PO Kolibri</option>
                                    <option value="PO Hampers">PO Hampers</option>
                                    <option value="PO Karakter Stok">PO Karakter Stok</option>
                                </select>
                            </div>
                            
                            <label>Jumlah Karakter</label><br>
                            <input type="number" name="karakter" class="form-control" value="0" required><br> 

                            <div class="form-group">
                                <label for="huruf">Huruf Kapital</label><br>
                                <select class="form-control" id="huruf" name="huruf" required>
                                    <option>Tidak</option>
                                    <option>Ya</option>
                                </select>
                            </div>
                            <div id="terminContainer">
                                <div class="termin-item border p-3 mb-3">
                                    <label>DP (%)</label>
                                    <input type="number" class="form-control" name="dp[]" required>

                                    <label>Urutan DP (seq)</label>
                                    <input type="number" class="form-control" name="seq[]" required>

                                    <label>Pelunasan?</label>
                                    <select class="form-control" name="is_pelunasan[]">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya (Pelunasan)</option>
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success" onclick="addTermin()">+ Tambah DP</button>
                            <br><br>
                            <button class="btn btn-primary" name="save">Tambah Data</button>
                        </form>
                        <?php
                            include "koneksi.php";
                            if(isset($_POST["save"])){
                                try {
                                    $koneksi->begin_transaction();
                                    $idpoproduk     = $_POST["idpoproduk"];
                                    $tgl            = $_POST["tgl"];
                                    $tgl_acc_db     = $_POST["tgl_acc_db"];
                                    $tgl_ubah       = $_POST["tgl_ubah"];
                                    $tgl_bayar      = $_POST["tgl_bayar"];
                                    $tgl_dropship   = $_POST["tgl_dropship"];
                                    $jenis_mitra    = $_POST["jenis_mitra"];
                                    $jenis_po       = $_POST["jenis_po"];
                                    $status         = $_POST["status"];
                                    $karakter       = $_POST["karakter"];
                                    $huruf          = $_POST["huruf"];

                                    $sql = $koneksi->query("INSERT INTO bukapo 
                                                                (
                                                                    idbpo,idpoproduk,tgl,tgl_acc_db,tgl_ubah,tgl_bayar,
                                                                    tgl_dropship,jenis_mitra,jenis_po,status,custom
                                                                )
                                                            VALUES 
                                                                (
                                                                    null,'$idpoproduk','$tgl','$tgl_acc_db','$tgl_ubah','$tgl_bayar',
                                                                    '$tgl_dropship','$jenis_mitra','$jenis_po','UNPUBLISH','$karakter|$huruf'
                                                                )
                                                        ");

                                    $dp             = $_POST['dp'];
                                    $seq            = $_POST['seq'];
                                    $is_pelunasan   = $_POST['is_pelunasan'];

                                    for ($i = 0; $i < count($dp); $i++) {
                                        $dpp        = $dp[$i];
                                        $seqq       = $seq[$i];
                                        $pelunasan  = $is_pelunasan[$i];

                                        $koneksi->query("INSERT INTO termin_po 
                                                                (dp, idpoproduk, is_pelunasan, seq)
                                                            VALUES
                                                                ('$dpp', '$idpoproduk', '$pelunasan', '$seqq')
                                                        ");
                                    }
                                    $koneksi->commit();
                                    echo "<script>alert('data berhasil ditambah');</script>";
                                    echo "<script>location='data_bukapo.php';</script>";
                                } catch (Exception $e) {
                                    $koneksi->rollback();
                                    echo "<script>alert('Terjadi kesalahan: ".$e->getMessage()."');</script>";
                                    echo "<script>location='data_bukapo.php';</script>";
                                }
                            }
                        ?>
                    </div>
                    <div class="tab-pane fade" id="nav-agen" role="tabpanel" aria-labelledby="nav-agen-tab">
                        <form method="post" enctype="multipart/form-data" class="form-group">
                            <div class="form-group">
                                <label for="idpoproduk">PO Produk</label><br>
                                <select class="form-control" id="idpoproduk_custom" name="idpoproduk_custom" required>
                                    <option disabled='disabled' value="" selected>~Pilih PO Produk~</option>
                                    <?php
                                        $ambil = $koneksi->query("SELECT * FROM poproduk ORDER BY idpoproduk DESC");
                                        while($row = $ambil->fetch_assoc()){
                                    ?>
                                        <option value="<?php echo $row['idpoproduk']; ?>"><?php echo $row['idpoproduk']; ?> | <?php echo $row['namapo']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <label>Tanggal Tutup PO</label><br>
                            <input type="date" name="tgl" class="form-control" required><br>

                            <label>Tanggal Acc DB</label><br>
                            <input type="date" name="tgl_acc_db" class="form-control" required><br>

                            <label>Tanggal Ubah PO</label><br>
                            <input type="date" name="tgl_ubah" class="form-control" required><br>    

                            <label>Tanggal Bayar PO</label><br>
                            <input type="date" name="tgl_bayar" class="form-control" required><br>    
                                
                            <label>Tanggal Dropship</label><br>
                            <input type="date" name="tgl_dropship" class="form-control" required><br>
                            
                            <div class="form-group">
                                <label for="jenis_mitra">Jenis Mitra</label><br>
                                <select class="form-control" id="jenis_mitra" name="jenis_mitra" required>
                                    <option disabled='disabled' value="" selected>~Pilih Jenis Mitra~</option>
                                    <option value="Semua Mitra">Semua Mitra</option>
                                    <option value="Distributor">Distributor</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jenis_po">Jenis PO</label><br>
                                <select class="form-control" id="jenis_po" name="jenis_po" required>
                                    <option disabled='disabled' value="" selected>~Pilih Jenis PO~</option>
                                    <option value="PO Custom Tab">PO Custom Tab</option>
                                    <option value="PO Custom Tab Stok">PO Custom Tab Stok</option>
                                    <option value="PO Konin">PO Konin</option>
                                </select>
                            </div>
                            <div class="form-group"  id="tabel_idpoproduk" name="tabel_idpoproduk"></div>
                            <div id="terminContainerCustom">
                                <div class="termin-item border p-3 mb-3">
                                    <label>DP (%)</label>
                                    <input type="number" class="form-control" name="dp[]" required>

                                    <label>Urutan DP (seq)</label>
                                    <input type="number" class="form-control" name="seq[]" required>

                                    <label>Pelunasan?</label>
                                    <select class="form-control" name="is_pelunasan[]">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya (Pelunasan)</option>
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success" onclick="addTerminCustom()">+ Tambah DP</button>
                            <br><br>  
                            <button class="btn btn-primary" name="save_custom">Tambah Data</button>
                        </form>
                        <?php
                            if(isset($_POST["save_custom"])){
                                try {
                                    $koneksi->begin_transaction();
                                    $idpoproduk     = $_POST["idpoproduk_custom"];
                                    $tgl            = $_POST["tgl"];
                                    $tgl_acc_db     = $_POST["tgl_acc_db"];
                                    $tgl_ubah       = $_POST["tgl_ubah"];    
                                    $tgl_bayar      = $_POST["tgl_bayar"];
                                    $tgl_dropship   = $_POST["tgl_dropship"];
                                    $jenis_mitra    = $_POST["jenis_mitra"];
                                    $jenis_po       = $_POST["jenis_po"];
                                    $status         = $_POST["status"];
                                    $jmlh_tab       = $_POST["jmlh_tab"];
                                    $nama_tab       = $_POST["nama_tab"];
                                    $id_awal        = $_POST["id_awal"];
                                    $id_akhir       = $_POST["id_akhir"];
                                    $result_explode = explode('|', $jmlh_tab);
                                    $angka          = $result_explode[1];

                                    $sql    = $koneksi->query("INSERT INTO bukapo 
                                                                    (
                                                                        idbpo,idpoproduk,tgl,tgl_acc_db,tgl_ubah,tgl_bayar,
                                                                        tgl_dropship,jenis_mitra,jenis_po,status
                                                                    )
                                                                VALUES 
                                                                    (
                                                                        null,'$idpoproduk','$tgl','$tgl_acc_db','$tgl_ubah','$tgl_bayar',
                                                                        '$tgl_dropship','$jenis_mitra','$jenis_po','UNPUBLISH'
                                                                    )
                                                            ");
                                    for($x = 0; $x < $angka; $x++){
                                        $sql_tab    = $koneksi->query("INSERT INTO bukapo_tab 
                                                                            (id,idpoproduk,nama_tab,id_awal,id_akhir,jmlh_tab) 
                                                                        VALUES 
                                                                            (null,'$idpoproduk','$nama_tab[$x]','$id_awal[$x]','$id_akhir[$x]','$angka') 
                                                                    ");
                                    }

                                    $dp             = $_POST['dp'];
                                    $seq            = $_POST['seq'];
                                    $is_pelunasan   = $_POST['is_pelunasan'];

                                    for ($i = 0; $i < count($dp); $i++) {
                                        $dpp        = $dp[$i];
                                        $seqq       = $seq[$i];
                                        $pelunasan  = $is_pelunasan[$i];

                                        $koneksi->query("INSERT INTO termin_po 
                                                                (dp, idpoproduk, is_pelunasan, seq)
                                                            VALUES
                                                                ('$dpp', '$idpoproduk', '$pelunasan', '$seqq')
                                                        ");
                                    }
                                    $koneksi->commit();
                                    echo "<script>alert('data berhasil ditambah');</script>";
                                    echo "<script>location='data_bukapo.php';</script>";
                                } catch (Exception $e) {
                                    $koneksi->rollback();
                                    echo "<script>alert('Terjadi kesalahan: ".$e->getMessage()."');</script>";
                                    echo "<script>location='data_bukapo.php';</script>";
                                }
                            }
                        ?>
                    </div>
                </div>
            </nav>
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
    <script>
        function addTermin() {
            let html = `
                <div class="termin-item border p-3 mb-3">
                    <label>DP (%)</label>
                    <input type="number" class="form-control" name="dp[]" required>

                    <label>Urutan DP (seq)</label>
                    <input type="number" class="form-control" name="seq[]" required>

                    <label>Pelunasan?</label>
                    <select class="form-control" name="is_pelunasan[]">
                        <option value="0">Tidak</option>
                        <option value="1">Ya (Pelunasan)</option>
                    </select>

                    <button type="button" class="btn btn-danger mt-2" onclick="this.parentElement.remove()">Hapus</button>
                </div>
            `;
            document.getElementById("terminContainer").insertAdjacentHTML('beforeend', html);
        }
        function addTerminCustom() {
            let html = `
                <div class="termin-item border p-3 mb-3">
                    <label>DP (%)</label>
                    <input type="number" class="form-control" name="dp[]" required>

                    <label>Urutan DP (seq)</label>
                    <input type="number" class="form-control" name="seq[]" required>

                    <label>Pelunasan?</label>
                    <select class="form-control" name="is_pelunasan[]">
                        <option value="0">Tidak</option>
                        <option value="1">Ya (Pelunasan)</option>
                    </select>

                    <button type="button" class="btn btn-danger mt-2" onclick="this.parentElement.remove()">Hapus</button>
                </div>
            `;
            document.getElementById("terminContainerCustom").insertAdjacentHTML('beforeend', html);
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#idpoproduk_custom').change(function(){
                var idpoproduk_custom = $('#idpoproduk_custom').val();
                $.ajax({
                    type: 'GET',
                    url: 'cek_id_po.php',
                    data: 'idpoproduk_custom=' + idpoproduk_custom,
                    success: function (data) {
                        $("#tabel_idpoproduk").html(data);
                    }
                });
                });
        });
    </script>
</body>
</html>

		                    