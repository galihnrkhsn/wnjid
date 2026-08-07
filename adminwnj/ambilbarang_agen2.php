<?php 
    session_start();

    include 'koneksi.php'; 


    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
    $invoice    = $_GET["invoice"];
    $sql        = "SELECT orderagen.invoice, mitraagen.namaagen 
                    FROM orderagen 
                    JOIN mitraagen on mitraagen.idmitraagen = orderagen.idmitraagen 
                    WHERE invoice = '$invoice' ";
    $query      = $koneksi->query($sql);
    $pengiriman = $query->fetch_assoc();
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
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"></h1>
                    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                </div>
                <h3><strong>Invoice <?= $invoice ?></strong></h3><br>
                <h4><strong>Nama Mitra : <?= $pengiriman['namaagen']; ?></strong></h4><br>
                <!-- <a class="btn btn-success" href="suratjalan.php" target="blank">Cetak Surat Jalan</a>     -->          

                <!-- Content Row -->
                <div class="row">
                    <div class="table-responsive">
                        <form method="post">              
                            <table class="table table-bordered" style="  font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th><input type='checkbox' id='checkAll' ></th>
                                        <!-- <th>No</th> -->
                                        <th>Nama Produk</th>
                                        <th>qty</th>
                                        <th>Ambil</th>
                                        <th>Krg</th>
                                        <!-- <th>Ready</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $datapo = $koneksi->query("SELECT mitraagen.namaagen, products.namaproduk, orderagen.idorder, 
                                                                            orderagen.idproduk, orderagen.invoice, orderagen.jumlah, 
                                                                            variants.variant, variants.size
                                                                    FROM orderagen 
                                                                    INNER JOIN mitraagen ON mitraagen.idmitraagen = orderagen.idmitraagen
                                                                    INNER JOIN variants ON variants.id = orderagen.idproduk
                                                                    INNER JOIN products ON products.id = variants.idproducts
                                                                    WHERE orderagen.invoice = '$invoice' 
                                                                    AND orderagen.jumlah > 0");
                                        $no     = 1;
                                        while($tampilkan = $datapo->fetch_assoc()){
                                            $id             = $tampilkan['idorder'];
                                            $datamitra      = $koneksi->query("SELECT SUM(surat_jalan_subdb.progres) AS progresnya 
                                                                                FROM surat_jalan_subdb
                                                                                WHERE surat_jalan_subdb.idorder = '$id'");
                                            $tampilprogres  = $datamitra->fetch_assoc();                                   
                                            $kurang         = $tampilkan['jumlah']-$tampilprogres['progresnya'];
                                    ?>
                                    <tr>
                                        <td><input type='checkbox' name='update[]' value='<?= $id ?>'></td>
                                        <td><?= $tampilkan['namaproduk']; ?> <?= $tampilkan['variant']; ?> <?= $tampilkan['size']; ?></td>
                                        <td><?= $tampilkan['jumlah']; ?></td>
                                        <td style="width:22%">
                                            <input type="number" name="progres_<?= $id ?>" class="form-control" min="0" value="0" max="<?= $kurang; ?>" required>
                                        </td>
                                        <td><?= $kurang ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <input type='submit' class="btn btn-success" value='Simpan' name='but_update' >
                            <a href="list_ambil_barang.php" class="btn btn-danger" style="float: right;">Kembali</a>
                        </form>
                    </div>
                    <?php 
                        if(isset($_POST['but_update'])){
                            date_default_timezone_set('Asia/Jakarta');
                            $waktu      = date("h:i:s");
                            $tanggal    = date("Y-m-d");
                            if(isset($_POST['update'])){
                                foreach($_POST['update'] as $updateid){
                                    $progres    = $_POST['progres_'.$updateid];
                                    $ambil      = $koneksi->query("SELECT * FROM surat_jalan_subdb WHERE idorder = '$updateid'");
                                    $datacocok  = $ambil->num_rows;
                                    if($datacocok == 1){
                                        $sqlnya = $koneksi->query("UPDATE surat_jalan_subdb set progres=progres + '$progres' where idorder='$updateid'");
                                    } else {
                                        $data       = $koneksi->query("SELECT * FROM orderagen WHERE idorder = '$updateid'");
                                        $tampilkan  = $data->fetch_assoc();
                                        $idproduk   = $tampilkan["idproduk"];
                                        $invoice    = $tampilkan["invoice"];
                                        $sqlnya     = $koneksi->query("INSERT INTO surat_jalan_subdb 
                                                                            (id_sj, idorder, idproduk, invoice, progres, status, waktu) 
                                                                        VALUES (null, '$updateid', '$idproduk', '$invoice', '$progres',
                                                                                'Ambil Barang', '$tanggal $waktu')
                                                                    ");
                                    }
                                }
                                if ($sqlnya) {
                                    echo "<script>alert('data berhasil disimpan');</script>";
                                    echo "<script>location='ambilbarang_agen2.php?invoice=$invoice';</script>";  
                                } else {
                                    echo "<script>alert('data gagal disimpan');</script>";
                                    echo "<script>location='ambilbarang_agen2.php?invoice=$invoice';</script>";
                                }
                            }   
                        }
                    ?>                            
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

<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update[]"]').prop('checked',true);
                    }else{
                        $('input[name="update[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update[]"]').click(function(){
                    var total_checkboxes = $('input[name="update[]"]').length;
                    var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll').prop('checked',true);
                    }else{
                        $('#checkAll').prop('checked',false);
                    }
                });
            });
        </script>
</body>
</html>