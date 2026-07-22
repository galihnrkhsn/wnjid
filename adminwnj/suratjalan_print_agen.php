<?php
    include "koneksi.php";
    $idmitraagen = $_POST['idmitraagen'];
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
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
                date_default_timezone_set('Asia/Jakarta');
                $tgl        = date("Y-m-d");
                $tglnya     = date("dm");
                $waktunya   = date("His");
                $no_sj      = 'SJ-A'.$idmitraagen.'-'.$tglnya.'-'.$waktunya;
                echo $tgl; 
                echo "<br>";
                echo "No. ".$no_sj;
            ?>
        </strong>
    </h2>
</center>

<?php 
    if(isset($_POST['but_export'])){ 
        $datamitra  = $koneksi->query("SELECT admin_mitra.idadmin, mitraagen.idmitraagen as idsubmitra, admin_mitra.namamitra, 
                                            admin_mitra_cs.namacs, mitraagen.namaagen 
                                        FROM mitraagen
                                        INNER JOIN admin_mitra on admin_mitra.idadmin = mitraagen.idadmin 
                                        INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                        WHERE mitraagen.idmitraagen = '$idmitraagen'");
        $tampilnama = $datamitra->fetch_assoc();   
  ?>

    <div class="row align-items-start">
        <div class="col">
            <!-- <h5><strong style="float:left"> Nama DB : <?= $tampilnama['namamitra']; ?> </strong></h5> -->
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
            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){
                    $tglprint   = date("Y-m-d");
                    $waktuprint = date("H:i:s");                
                    $invoice    = $_POST['invoice'.$updateid];
                    $id_sj      = $_POST['id_sj'.$updateid];
                    $sqlnya     = $koneksi->query("UPDATE surat_jalan_subdb set Status = 'Checker', no_sj = '$no_sj', waktu = '$tglprint $waktuprint' WHERE id_sj = '$id_sj'");
        ?>
        <?php 
            $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice, produk.namaproduk, orderagen.jumlah, surat_jalan_subdb.progres,
                                            surat_jalan_subdb.status, surat_jalan_subdb.waktu, surat_jalan_subdb.id_sj, orderagen.idmitraagen
                                        FROM surat_jalan_subdb
                                        JOIN produk on produk.idproduk = surat_jalan_subdb.idproduk
                                        JOIN orderagen on (orderagen.idorder = surat_jalan_subdb.idorder and orderagen.invoice = surat_jalan_subdb.invoice)
                                        WHERE surat_jalan_subdb.id_sj = '$id_sj' and surat_jalan_subdb.progres > 0
                                        AND orderagen.tgl < '2024-11-02'
                                    ");
            while($tampilkandata=$datapo->fetch_assoc()){
        ?>
            <tr>
                <td><?= $no = $no+1; ?></td>  
                <td><?= $tampilkandata['invoice']; ?></td>
                <td><?= $tampilkandata['namaproduk']; ?></td> 
                <td><?= $tampilkandata['progres']; ?> </td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
        <?php 
            $datapo = $koneksi->query("SELECT surat_jalan_subdb.invoice, products.namaproduk, orderagen.jumlah, surat_jalan_subdb.progres,
                                            surat_jalan_subdb.status, surat_jalan_subdb.waktu, surat_jalan_subdb.id_sj, orderagen.idmitraagen,
                                            variants.variant, variants.size, orderagen.tgl
                                        FROM surat_jalan_subdb
                                        JOIN variants on variants.id = surat_jalan_subdb.idproduk
                                        JOIN products ON products.id = variants.idproducts
                                        JOIN orderagen on (orderagen.idorder = surat_jalan_subdb.idorder and orderagen.invoice = surat_jalan_subdb.invoice)
                                        WHERE surat_jalan_subdb.id_sj = '$id_sj' and surat_jalan_subdb.progres > 0
                                        AND orderagen.tgl > '2024-11-02'
                                    ");
            while($tampilkandata = $datapo->fetch_assoc()){
        ?>
            <tr>
                <td><?= $no = $no+1; ?></td>  
                <td><?= $tampilkandata['invoice']; ?></td>
                <td><?= $tampilkandata['namaproduk']; ?> <?= $tampilkandata['variant']; ?> <?= $tampilkandata['size']; ?></td> 
                <td><?= $tampilkandata['progres']; ?> </td>
                <td></td>
                <td></td>
            </tr>
        <?php 
                    $total = $total +$tampilkandata['progres']; 
                }
            }
        ?>
    </tbody>
    <tr>
        <td colspan="3"><b>Total</b></td>
        <td><b><?= $total; ?></b></td> 
        <td></td>
        <td></td>   
    </tr>
</table>
<?php
    }
}
?>

<br>
<br>

<center>
    <table style="font-size: 25px;">
        <tr>
            <td style="border-color: white;">
                <center>Gudang</center>
                <br><br><br><br>      
            </td>
            <td width="5%" style="border-color: white;"></td>Gudang
            <td style="border-color: white;">
                <center>Checker</center>
                <br><br><br><br>      
            </td>
            <td width="5%" style="border-color: white;"></td>    
            <td style="border-color: white;">
                <center>Penerima</center>
                <br><br><br><br>
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
<br><br><br>
<div>
    <p style="font-size: 22px"> 
        Note : Setelah barang diterima, mohon langsung dicek. Surat jalan yang sudah diverifikasi dan sudah ditandatangani mohon untuk difoto dan dikirim melalui No HP CS Pusat maksimal 3 X 24 Jam
    </p>
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
<script>
window.print();
</script>

</html>

                                                          