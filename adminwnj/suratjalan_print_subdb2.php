<?php
    include "koneksi.php"; 
    $no_sj      = $_GET['no_sj'];
    $idadmin    = $_GET['idadmin'];
    $jenis      = $_GET['jenis'];
    $datasj     = $koneksi->query("SELECT * FROM surat_jalan_subdb WHERE no_sj = '$no_sj'");
    $tampilsj   = $datasj->fetch_assoc(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cetak invoice</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<style type="text/css">
    table, th, td, tr {
        border: 3px solid;
    }
    body{
        color: black;
    }
</style>
<body>

<center><h1><strong>Surat Jalan WNJ</strong></h1></center>
<center>
    <h2>
        <strong>
            <?php 
                echo substr($tampilsj['waktu'],0,10);
                echo "<br>";
                echo "No. ".$tampilsj['no_sj'];
            ?>
        </strong>
    </h2>
</center>

<?php
    if ($jenis == 'Agen') {
        $datamitra  = $koneksi->query("SELECT admin_mitra.idadmin,mitraagen.namaagen, mitraagen.idmitraagen as idsubmitra, 
                                            admin_mitra.namamitra, admin_mitra_cs.namacs 
                                        FROM mitraagen
                                        INNER JOIN admin_mitra on admin_mitra.idadmin = mitraagen.idadmin
                                        INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                        WHERE mitraagen.idmitraagen = '$idadmin'");
        $tampilnama = $datamitra->fetch_assoc(); 
    }
    if ($jenis == 'Reseller') {
        $datamitra  = $koneksi->query("SELECT admin_mitra.idadmin,mitrareseller.namaagen, mitrareseller.idmitrareseller as idsubmitra, 
                                            admin_mitra.namamitra, admin_mitra_cs.namacs 
                                        FROM mitrareseller
                                        INNER JOIN admin_mitra on admin_mitra.idadmin = mitrareseller.idadmin
                                        INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                        WHERE mitrareseller.idmitrareseller = '$idadmin'");
        $tampilnama = $datamitra->fetch_assoc(); 
    }
    if ($jenis == 'Marketer') {
        $datamitra  = $koneksi->query("SELECT admin_mitra.idadmin,mitramarketer.namaagen, mitramarketer.idmitramarketer as idsubmitra, 
                                            admin_mitra.namamitra, admin_mitra_cs.namacs 
                                        FROM mitramarketer
                                        INNER JOIN admin_mitra on admin_mitra.idadmin = mitramarketer.idadmin
                                        INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                        where mitramarketer.idmitramarketer = '$idadmin'");
        $tampilnama = $datamitra->fetch_assoc(); 
    }
?>

    <div class="row align-items-start">
        <div class="col">
            <!-- <h5><strong style="float:left"> Nama DB : <?= $tampilnama['namamitra']; ?> <br> Nama Sub DB : <?= $tampilnama['namaagen']; ?></strong></h5> -->
            <h5><strong style="float:left"> Kode Mitra : <?= $tampilnama['idadmin']; ?> / <?= $tampilnama['idsubmitra']; ?></strong></h5>
        </div>
        <div class="col"></div>
        <div class="col">
            <h5><strong style="float:rigth"> Nama CS : <?= $tampilnama['namacs']; ?> </strong></h5>
        </div>
    </div>
    <table class="table" id="tb_multiprint" style="font-size: 25px; color: black;">
        <thead>
            <tr>       
                <th >No</th>
                <th >Invoice</th>
                <th>Nama Produk</th>
                <th >QTY</th>
                <th >Checker</th>
                <th >Penerima</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($jenis == 'Agen') {            
                    $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice, products.namaproduk, surat_jalan_subdb.progres,
                                                    surat_jalan_subdb.status, surat_jalan_subdb.waktu, surat_jalan_subdb.id_sj,
                                                    variants.variant, variants.size
                                                FROM surat_jalan_subdb
                                                JOIN variants on variants.id = surat_jalan_subdb.idproduk
                                                JOIN products on products.id = variants.idproducts
                                                JOIN orderagen on (orderagen.idorder = surat_jalan_subdb.idorder and orderagen.invoice = surat_jalan_subdb.invoice)
                                                WHERE surat_jalan_subdb.no_sj = '$no_sj' and surat_jalan_subdb.progres > 0
                                            ");
                }
                if ($jenis == 'Reseller') {
                    $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice, products.namaproduk, surat_jalan_subdb.progres,
                                                    surat_jalan_subdb.status, surat_jalan_subdb.waktu, surat_jalan_subdb.id_sj,
                                                    variants.variant, variants.size
                                                FROM surat_jalan_subdb
                                                JOIN variants on variants.id = surat_jalan_subdb.idproduk
                                                JOIN products on products.id = variants.idproducts
                                                JOIN orderreseller on (orderreseller.idorder = surat_jalan_subdb.idorder and orderreseller.invoice = surat_jalan_subdb.invoice)
                                                WHERE surat_jalan_subdb.no_sj = '$no_sj' and surat_jalan_subdb.progres>0
                                            ");
                }
                if ($jenis == 'Marketer') {
                    $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice, products.namaproduk, surat_jalan_subdb.progres,
                                                    surat_jalan_subdb.status, surat_jalan_subdb.waktu, surat_jalan_subdb.id_sj,
                                                    variants.variant, variants.size
                                                FROM surat_jalan_subdb
                                                JOIN variants on variants.id = surat_jalan_subdb.idproduk
                                                JOIN products on products.id = variants.idproducts
                                                JOIN ordermarketer on (ordermarketer.idorder = surat_jalan_subdb.idorder and ordermarketer.invoice = surat_jalan_subdb.invoice)
                                                WHERE surat_jalan_subdb.no_sj = '$no_sj' and surat_jalan_subdb.progres>0
                                            ");
                }                               
                // $no=1;
                while($tampilkandata=$datapo->fetch_assoc()){             
            ?>
                <tr>
                    <td><?= $no = $no+1; ?></td>  
                    <td><?= $tampilkandata['invoice']; ?></td>
                    <td><?= $tampilkandata['namaproduk']; ?> <?= $tampilkandata['variant']?> <?= $tampilkandata['size']?></td> 
                    <td><?= $tampilkandata['progres']; ?> </td>
                    <td></td>
                    <td></td>
                </tr>
            <?php 
                $total = $total +$tampilkandata['progres']; 
                }
            ?>
        </tbody>
        <tr>
            <td colspan="3"><b>Total</b></td>
            <td><b><?= $total; ?> </b></td> 
            <td></td>
            <td></td>   
        </tr>
    </table>

<br>
<br>

<center>
    <table style="font-size: 25px;">
        <tr>
            <td style="border-color: white;">
            <center>Gudang</center>
            <br>
                <br>
                <br>
                <br>      
            </td>
            <td width="5%" style="border-color: white;">
            
            </td>
            <td style="border-color: white;">
            <center>Checker</center>
            <br>
                <br>
                <br>
                <br>      
            </td>
            <td width="5%" style="border-color: white;">
            
            </td>    
            <td style="border-color: white;">
            <center>Penerima</center>
            <br>
                <br>
                <br>
                <br>
            </td>
            </tr>
            <tr>
            <td style="border-color: white;">
            <center>
            (<span style="color:transparent;">_______________________</span>)
            </center>      
            </td >
            <td style="border-color: white;"></td>
            <td style="border-color: white;">
            <center>
            (<span style="color:transparent;">_______________________</span>)
            </center>      
            </td>   
            <td style="border-color: white;"></td> 
            <td style="border-color: white;">
            <center>
            (<span style="color:transparent;">_______________________</span>)
            </center>
            </td>
        </tr>
    </table>
</center>

<br>
<br>
<br>
    <div>
        <p style="font-size: 22px"> 
            Note : Setelah barang diterima, mohon langsung dicek. Surat jalan yang sudah diverifikasi dan sudah ditandatangani mohon untuk difoto dan dikirim melalui No HP CS Pusat maksimal 3 X 24 Jam
        </p>
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

</body>
<script>
window.print();
</script>

</html>
