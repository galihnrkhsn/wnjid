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

    if ($mitra == 'D') {
        $sql = "SELECT pomitra.invoice, pomitra.idpoproduk, admin_mitra.namamitra as namamitra FROM pomitra join admin_mitra on admin_mitra.idadmin = pomitra.idmitra WHERE invoice='$invoice' ";
        $query = $koneksi->query($sql);
        $pengiriman = $query->fetch_assoc();
    }
    if ($mitra=='A') {
        $sql = "SELECT pomitra.invoice, pomitra.idpoproduk, mitraagen.namaagen as namamitra FROM pomitra join mitraagen on mitraagen.idmitraagen = pomitra.idmitraagen WHERE invoice='$invoice' ";
        $query = $koneksi->query($sql);
        $pengiriman = $query->fetch_assoc();
    }

    if ($mitra=='R') {
        $sql = "SELECT pomitra.invoice, pomitra.idpoproduk, mitrareseller.namaagen as namamitra FROM pomitra join mitrareseller on mitrareseller.idmitrareseller = pomitra.idmitrareseller WHERE invoice='$invoice' ";
        $query = $koneksi->query($sql);
        $pengiriman = $query->fetch_assoc();
    }

    if ($mitra=='M') {
        $sql = "SELECT pomitra.invoice, pomitra.idpoproduk, mitramarketer.namaagen as namamitra FROM pomitra join mitramarketer on mitramarketer.idmitramarketer = pomitra.idmitramarketer WHERE invoice='$invoice' ";
        $query = $koneksi->query($sql);
        $pengiriman = $query->fetch_assoc();
    }

    $idpoproduk = $pengiriman['idpoproduk'];

    $bukapo     = $koneksi->query("SELECT jenis_po FROM bukapo WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();
    $jenisPO    = $bukapo['jenis_po'];
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

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ambil Barang PO</h1>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Invoice <?= $invoice ?></h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div>
   <h4><strong>Nama Mitra : <?= $pengiriman['namamitra']; ?></strong></h4><br>

 <div class="table-responsive">
<form method="post">              
        <table class="table table-bordered" style="  font-size: 12px;">
            <thead>
                    <tr>
                        <th><input type='checkbox' id='checkAll' ></th>
                            <!--<th>Idpoproduk</th>-->
                            <th>Nama Produk</th>
                            <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                <th>Custom</th>
                                <th>Font</th>
                            <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                <th>Template</th>
                            <?php endif; ?>
                            <th>qty</th>
                            <th>Ambil</th>
                            <th>Krg</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $idpoproduk = $pengiriman['idpoproduk'];

                            if ($mitra == 'D') {
                                if ($idpoproduk == '339' || $idpoproduk == '335') {
                                  $group = "GROUP BY pomitra.custom";
                                }
                                $datapo = $koneksi->query("SELECT admin_mitra.namamitra, podetail.variant, pomitra.idpomitra,
                                                                pomitra.idpodetail, pomitra.invoice, pomitra.custom, pomitra.idpoproduk, pomitra.jumlah,
                                                                pomitra.font,
                                                                pomitra.template
                                                            FROM poproduk 
                                                            INNER JOIN pomitra on poproduk.idpoproduk = pomitra.idpoproduk 
                                                            INNER JOIN pokategori on pokategori.idpo = pomitra.idpo
                                                            INNER JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                                            INNER JOIN admin_mitra ON pomitra.idmitra = admin_mitra.idadmin
                                                            WHERE pomitra.invoice = '$invoice' and pomitra.jumlah > 0
                                                            $group
                                                            ORDER BY pomitra.idpomitra ASC
                                ");
                            } if ($mitra=='A') {
                                if ($idpoproduk == '339' || $idpoproduk == '335') {
                                    $group = "GROUP BY pomitra.custom";
                                }
                                $datapo = $koneksi->query("SELECT mitraagen.namaagen,
                                                                podetail.variant,
                                                                pomitra.idpomitra,
                                                                pomitra.idpodetail,
                                                                pomitra.invoice,
                                                                pomitra.custom,
                                                                pomitra.idpoproduk,
                                                                pomitra.jumlah,
                                                                pomitra.font,
                                                                pomitra.template
                                                                FROM poproduk 
                                                                    INNER JOIN pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                                                                    INNER JOIN pokategori on pokategori.idpo=pomitra.idpo
                                                                    INNER JOIN podetail on podetail.idpodetail=pomitra.idpodetail
                                                                    INNER JOIN mitraagen ON pomitra.idmitraagen=mitraagen.idmitraagen
                                                                    WHERE pomitra.invoice='$invoice'  and pomitra.jumlah>0
                                                                    $group
                                                                ");
                            } if ($mitra == 'R') {
                                if ($idpoproduk == '339' || $idpoproduk == '335') {
                                    $group = "GROUP BY pomitra.custom";
                                }
                                $datapo = $koneksi->query("SELECT mitrareseller.namaagen, podetail.variant, pomitra.idpomitra, pomitra.idpodetail, 
                                                                pomitra.invoice, pomitra.custom, pomitra.idpoproduk, pomitra.jumlah,
                                                                pomitra.font,
                                                                pomitra.template
                                                            FROM poproduk 
                                                            INNER JOIN pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                                                            INNER JOIN pokategori on pokategori.idpo=pomitra.idpo
                                                            INNER JOIN podetail on podetail.idpodetail=pomitra.idpodetail
                                                            INNER JOIN mitrareseller ON pomitra.idmitrareseller=mitrareseller.idmitrareseller
                                                            WHERE pomitra.invoice='$invoice'  and pomitra.jumlah>0
                                                            $group
                                                        ");
                            } if ($mitra=='M') {
                                if ($idpoproduk == '339' || $idpoproduk == '335') {
                                    $group = "GROUP BY pomitra.custom";
                                }
                                $datapo=$koneksi->query("SELECT mitramarketer.namaagen,
                                                            podetail.variant,
                                                            pomitra.idpomitra,
                                                            pomitra.idpodetail,
                                                            pomitra.invoice,
                                                            pomitra.custom,
                                                            pomitra.idpoproduk,
                                                            pomitra.jumlah,
                                                            pomitra.font,
                                                            pomitra.template
                                                            
                                                            FROM poproduk 
                                                              INNER JOIN pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                                                              INNER JOIN pokategori on pokategori.idpo=pomitra.idpo
                                                              INNER JOIN podetail on podetail.idpodetail=pomitra.idpodetail
                                                              INNER JOIN mitramarketer ON pomitra.idmitramarketer=mitramarketer.idmitramarketer
                                                              WHERE pomitra.invoice = '$invoice' and pomitra.jumlah>0
                                                              $group
                                                              ORDER BY pomitra.idpomitra ASC
                                                            ");
                            }
                            $no = 1;
                            while($tampilkan = $datapo->fetch_assoc()){
                                $id             = $tampilkan['idpomitra'];
                                $datamitra      = $koneksi->query("SELECT SUM(surat_jalan_po.progres) as progresnya FROM surat_jalan_po
                                                                    WHERE surat_jalan_po.idpomitra='$id'");
                                $tampilprogres  = $datamitra->fetch_assoc();
                                $kurang         = $tampilkan['jumlah']-$tampilprogres['progresnya'];
                                $idpoproduk     = $tampilkan['idpoproduk'];

                                $custom         = $tampilkan['custom'];
                                $string         = $custom;
                                // Mengubah semua huruf menjadi huruf kecil
                                $string         = strtolower($string);
                                // Mengganti spasi dengan tanda hubung
                                $string         = str_replace(' ', '-', $string);
                                // Menghilangkan tanda - di awal string
                                $string         = ltrim($string, '-');
                                // Menghapus karakter yang tidak diperlukan (opsional, jika diperlukan)
                                $string         = preg_replace('/[^a-z0-9\-]/', '', $string);
                             ?>
                        
                        <tr>
                            <td><input type='checkbox' name='update[]' value='<?= $id ?>' ></td>
                            <td>
                                <?php if($tampilkan['idpoproduk'] === '237' or $tampilkan['idpoproduk'] === '234') : ?>
                                    <?= $tampilkan['custom']; ?>
                                <?php elseif ($tampilkan['idpoproduk'] == '339') : ?>
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
                                                echo " | ";
                                            }
                                            $first = false;
                                    ?>
                                    <?= $data_produk['variant']; ?>
                                    <?php 
                                        }
                                    ?>
                                <?php else : ?>
                                    <?= $tampilkan['variant']; ?> <?= $tampilkan['custom']; ?> 
                                <?php endif; ?>
                            </td>        
                            <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                <td><?= $tampilkan['custom'] ?></td>
                                <td><?= $tampilkan['font'] ?></td>
                            <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                <td><?= $tampilkan['template'] ?></td>
                            <?php endif; ?>
                            <td><?= $tampilkan['jumlah']; ?></td>
                            <td style="width:22%">
                                <input type="number" name="progres_<?= $id ?>" class="form-control" value="0" max="<?= $tampilkan['jumlah']; ?>" min="0" required>
                            </td>
                            <td><?= $kurang ?></td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
 <input type='submit' class="btn btn-success" value='Simpan' name='but_update' >
  <input type='submit' class="btn btn-warning" value='Kurang' name='but_kurang' >
 <a href="list_ambilbarang_po.php" class="btn btn-danger" style="float: right;">Kembali</a>        
      </form>                    
</div>  
   
                                    </div>
                                </div>
                            </div>
                        </div> 


  <!-- <a class="btn btn-success" href="suratjalan.php" target="blank">Cetak Surat Jalan</a>     -->          

  <!-- Content Row -->
          <div class="row">
            


                   

  
   
<?php 
        if(isset($_POST['but_update'])){
          date_default_timezone_set('Asia/Jakarta');
          $waktu = date("h:i:s");
          $tanggal = date("Y-m-d");
          try {
            if(isset($_POST['update'])){
              foreach($_POST['update'] as $updateid){
                $progres = $_POST['progres_'.$updateid];
                if ($progres>0) {
                  $ambil=$koneksi->query("SELECT * FROM surat_jalan_po WHERE idpomitra = '$updateid' AND status = 'Ambil Barang'");
                  $datacocok=$ambil->num_rows;

                  if ($datacocok == 1) {
                    $sqlnya = $koneksi->query("UPDATE surat_jalan_po set progres=progres + '$progres' where idpomitra='$updateid'  and status = 'Ambil Barang'");
                  } else {
                    $data = $koneksi->query("SELECT * FROM pomitra WHERE idpomitra = '$updateid'");
                    $tampilkan = $data->fetch_assoc();
                    $idpodetail = $tampilkan["idpodetail"];
                    $invoice = $tampilkan["invoice"];
                    $custom = $tampilkan["custom"];

                    $sqlnya = $koneksi->query("INSERT INTO surat_jalan_po
                                                  (id_sj, idpomitra, idpodetail, custom, invoice, progres, status, waktu) 
                                                VALUES
                                                  (
                                                    NULL, '$updateid', '$idpodetail',
                                                    '$custom', '$invoice', '$progres',
                                                    'Ambil Barang', '$tanggal $waktu'
                                                  )
                                              ");
                  }
                }
              }

              if ($sqlnya) {
                echo "<script>alert('data berhasil disimpan');</script>";
                echo "<script>location='ambilbarang_po.php?invoice=$invoice&mitra=$mitra';</script>";  
              } else{
                echo "<script>alert('data gagal disimpan');</script>";
                echo "<script>location='ambilbarang_po.php?invoice=$invoice&mitra=$mitra';</script>";
              }
            }
          } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
          }
        }

    if(isset($_POST['but_kurang'])){
        date_default_timezone_set('Asia/Jakarta');
        $waktu      = date("h:i:s");
        $tanggal    = date("Y-m-d");

        if(isset($_POST['update'])){
            foreach($_POST['update'] as $updateid){
                $progres = $_POST['progres_'.$updateid];
                if ($progres>0) {
                    $ambil = $koneksi->query("SELECT * FROM surat_jalan_po WHERE idpomitra = '$updateid' and status = 'Ambil Barang'");
                    $datacocok = $ambil->num_rows;
                    if($datacocok == 1){
                        $sqlnya = $koneksi->query("UPDATE surat_jalan_po set progres=progres - '$progres' where idpomitra='$updateid'  and status = 'Ambil Barang'");
                        if ($sqlnya) {
                            echo "<script>alert('data berhasil disimpan');</script>";
                            echo "<script>location='ambilbarang_po.php?invoice=$invoice&mitra=$mitra';</script>";  
                        }else{
                            echo "<script>alert('data gagal disimpan');</script>";
                            echo "<script>location='ambilbarang_po.php?invoice=$invoice&mitra=$mitra';</script>";
                        }
                    }
                }
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

                                                    