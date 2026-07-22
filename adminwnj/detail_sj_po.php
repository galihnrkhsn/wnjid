<?php
    session_start();
    include 'koneksi.php'; 
    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $sum = 0;
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
            <!-- Content Row -->
            <?php
                $no_sj      = $_GET["no_sj"];
                $datapo     = $koneksi->query("SELECT invoice FROM surat_jalan_po WHERE no_sj = '$no_sj'");
                $tampilpo   = $datapo->fetch_assoc(); 
                $invoice    = $tampilpo['invoice'];

                $sql        = mysqli_query($koneksi, "SELECT idpoproduk, custom FROM pomitra WHERE invoice = '$invoice' ");              
                $data       = mysqli_fetch_array($sql);

                $idpoproduk = $data['idpoproduk'];
                $custom     = $data['custom'];
            ?>
            <h5><strong>No. Surat Jalan : <?= $no_sj; ?> </strong></h5>
            <h5><strong>Invoice : <?= $invoice; ?> </strong></h5>

            <div class="row">
                <div class="table-responsive">
				<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Progres</th>
                            <?php if ($idpoproduk == 331 && $custom === "Set"): ?>
                            <?php else : ?>      
                                <th>Satuan</th>
                                <th>Total</th>
                            <?php endif ?>              
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $jumlah     = 0;
                            $subtotal   = 0;
                            if ($idpoproduk == "335" || $idpoproduk == "339" || $idpoproduk == "377") {
                                $datapo     = $koneksi->query("SELECT surat_jalan_po.invoice,
                                                                    surat_jalan_po.progres,
                                                                    surat_jalan_po.status,
                                                                    surat_jalan_po.waktu,
                                                                    surat_jalan_po.id_sj,
                                                                    pomitra.idmitra,
                                                                    pomitra.idpomitra,
                                                                    pomitra.custom,
                                                                    podetail.variant,
                                                                    podetail.harga
                                                                    FROM surat_jalan_po
                                                                    INNER JOIN pomitra on pomitra.idpomitra=surat_jalan_po.idpomitra
                                                                    INNER JOIN podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                                                                WHERE surat_jalan_po.no_sj = '$no_sj' 
                                                                and surat_jalan_po.status = 'Checker'
                                                                GROUP BY pomitra.custom
                                        ");
                            } else {
                                $datapo     = $koneksi->query("SELECT surat_jalan_po.invoice,
                                                                    SUM(surat_jalan_po.progres) as progres,
                                                                    surat_jalan_po.status,
                                                                    surat_jalan_po.waktu,
                                                                    surat_jalan_po.id_sj,
                                                                    pomitra.idmitra,
                                                                    pomitra.idpomitra,
                                                                    pomitra.custom,
                                                                    podetail.variant,
                                                                    podetail.harga
                                                                    FROM surat_jalan_po
                                                                    INNER JOIN pomitra on pomitra.idpomitra=surat_jalan_po.idpomitra
                                                                    INNER JOIN podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                                                                WHERE surat_jalan_po.no_sj='$no_sj' 
                                                                and surat_jalan_po.status='Checker'
                                                                GROUP BY pomitra.idpomitra
                                                                ORDER BY pomitra.idpomitra ASC
                                        ");
                            }
                            $no         = 1;
                            while($data = $datapo->fetch_assoc()){
                            $totalnya = $data['progres']*$data['harga'];

                            $custom = $data['custom'];
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
                            <td class="align-middle"><?= $no++; ?></td>
                            <?php if ($idpoproduk == "335" || $idpoproduk == "339" || $idpoproduk == "377") : ?>
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
                                            $pack       = $data_produk['jumlah'];
                                            $harga      = $data_produk['harga'];
                                            $idpomitra  = $data_produk['idpomitra'];
                                        }
                                    ?>
                                </td>
                                <td class="align-middle"><?= $pack; ?></td>
                                <td class="align-middle">Rp. <?= number_format($data['harga']); ?></td>
                                
                                <td class="align-middle">
                                    Rp. <?= number_format($totalnya); ?>
                                </td>
                            <?php else : ?>
                                <?php if($idpoproduk == '245' or $idpoproduk == '250' or $idpoproduk == '251') : ?>
                                    <td class="align-middle"><?= $data['custom']; ?></td>
                                <?php elseif ($idpoproduk == 355 || $idpoproduk == 361 || $idpoproduk == 366 || $idpoproduk == 371 || $idpoproduk == 374 || $idpoproduk == 377) : ?>
                                    <td class="align-middle"><?= $data['variant']; ?> <?= $data['custom']; ?></td>
                                <?php else : ?>
                                    <td class="align-middle"><?= $data['variant']; ?></td>
                                <?php endif; ?>
                                <td class="align-middle"><?= $data['progres']; ?></td>
                                <?php if ($idpoproduk == 331 && $custom === "Set"): ?>
                                <?php else : ?>
                                    <td class="align-middle">Rp. <?= number_format($data['harga']); ?></td>
                                    <td class="align-middle">Rp. <?= number_format($totalnya); ?></td>  
                                <?php endif ?>
                            <?php endif; ?>
                        </tr>
                        <?php
                            $sum_progres = $sum_progres + $data['progres'];
                            if ($idpoproduk == 331 && $data['custom'] === "Set") {
                                $jumlah_progres = ($sum_progres / 3) * 100000;
                            } elseif ($idpoproduk == 355 || $idpoproduk == 361 || $idpoproduk == 366 || $idpoproduk == 371 || $idpoproduk == 374 ) {
                                $jumlah_progres += $totalnya;
                            } else {
                                $jumlah_progres = $jumlah_progres+$totalnya;
                            }
                        ?>                                    
                        <?php } ?>
                    </tbody>
                </table>
                <table style="float: right;width: 100%">
                    <tbody  style="float: right;">
                        <tr>
                            <th style="padding-bottom: 5%;">Total Qty</th>
                            <td style="padding-bottom: 5%;">:</td>
                            <td style="padding-bottom: 5%;"><?= $sum_progres; ?></td>
                        </tr>
                        <tr>
                            <th>JUMLAH</th>
                            <td>:</td>
                            <td>
                                <?php 
                                    $sqlharga2          = "SELECT MAX(pomitra.total) as totalnya,pomitra.invoice, pomitra.idpoproduk
                                                            FROM pomitra WHERE invoice = '$invoice'";
                                    $queryharga2        = $koneksi->query($sqlharga2);
                                    $sisaharga2         = $queryharga2->fetch_assoc(); 
                                    $stokharga2         = $sisaharga2['totalnya']; 
                                    $idpoproduk         = $sisaharga2['idpoproduk']; 
                                    if ($idpoproduk == "120") {
                                        $jumlah_progres = $stokharga2;
                                    }

                                    $sql_diskon         = "SELECT diskon 
                                                            FROM poproduk WHERE idpoproduk = '$idpoproduk'";
                                    $query_diskon       = $koneksi->query($sql_diskon);
                                    $data_diskon        = $query_diskon->fetch_assoc(); 

                                    $persen_tambahan    = $data_diskon['diskon'];
                                    $diskon_tambahan    = $persen_tambahan/100*$jumlah_progres;
                                ?>           
                                Rp. <?= number_format($jumlah_progres); ?>
                            </td>
                        </tr>
                        <?php if ($idpoproduk == 355 || $idpoproduk == 361 || $idpoproduk == 366 || $idpoproduk == 371 || $idpoproduk == 374) :?>
                            <?php
                                $bundling       = 5000;
                                $diskonBundle   = $bundling*$sum_progres;
                                $totalBundle    = $jumlah_progres-$diskonBundle;
                                $jumlah_progres = $totalBundle;
                            ?>
                            <tr>
                                <th>Tambahan Diskon Bundling</th>
                                <td>:</td>
                                <td>- Rp. <?= number_format($diskonBundle) ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($diskon_tambahan>0): ?>
                            <tr>
                                <th>Diskon Tambahan <?= $persen_tambahan; ?>%</th>
                                <td>:</td>
                                <td>Rp. <?= number_format($diskon_tambahan); ?></td>
                            </tr>                           
                        <?php endif ?>
                        <?php 
                        
                        if ($idpoproduk == '96') {
                            $persen_progres = 50;
                            $diskon_progres = 50/100*$jumlah_progres;
                        }else {
                            $persen_progres = 35;
                            $diskon_progres = 35/100*$jumlah_progres;
                        }   
                            $subtotal_progres = $jumlah_progres-$diskon_progres-$diskon_tambahan; 
                        ?>
                        <tr>
                            <th>Diskon DB <?= $persen_progres; ?>%</th>
                            <td>:</td>
                            <td>
                            Rp. <?= number_format($diskon_progres); ?>               
                            </td>
                        </tr>
                        <tr>
                            <th style="padding-bottom: 5%;">Total Bayar</th>
                            <td style="padding-bottom: 5%;">:</td>
                            <td style="padding-bottom: 5%;">Rp. <?= number_format($subtotal_progres); ?></td>
                        </tr>
                        <tr>
                            <th style="padding-bottom: 5%;">Jumlah DP</th>
                            <td style="padding-bottom: 5%;">:</td>
                            <td style="padding-bottom: 5%;">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp. </span>
                                    <input type="number" name="angkadp" id="angkadp" min="0" value="0" class="form-control" onchange="totalnya()">
                                    <input type="hidden" name="angkatotal" id="angkatotal" min="0" value="<?= $subtotal_progres; ?>" class="form-control" onchange="totalnya()">
                                </div> 
                            </td>
                        </tr>
                        <script type="text/javascript">
                            function totalnya() {
                                var vangkadp        = parseInt(document.getElementById('angkadp').value);
                                var vangkatotal     = parseInt(document.getElementById('angkatotal').value);
                                var jumlah_harga    = vangkadp - vangkatotal;
                                document.getElementById('total_jumlah').value = jumlah_harga;
                            }
                        </script>
                        <tr>
                            <th style="padding-bottom: 5%;">Sisa Tagihan</th>
                            <td style="padding-bottom: 5%;">:</td>
                            <td style="padding-bottom: 5%;">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp. </span>          
                                <input type="number"  name="total_jumlah" id="total_jumlah" class="form-control" value="" readonly>
                            </div>
                            </td>
                        </tr>    
                    </tbody>
                </table> 	
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

</body>
</html>