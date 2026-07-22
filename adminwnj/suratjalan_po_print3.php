<?php
    include "koneksi.php";
    $idadmin    = 717; 
    $mitra      = 'D';      

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
    <center><h1><strong>Surat Jalan PO WNJ</strong></h1></center>
    <center>
        <h2>
            <strong>
                <?php 
                    date_default_timezone_set('Asia/Jakarta');
                    $tgl        = date("Y-m-d");

                    $tglnya     = date("dm");
                    $waktunya   = date("His");
                    $no_sj      = 'SJPO-D717-0502-091140';
                    echo $tgl; 
                    echo "<br>";
                    echo "No. ".$no_sj;
                ?>
            </strong>
        </h2>
    </center>
    <?php 
                $datamitra=$koneksi->query("SELECT  admin_mitra.idadmin,
                                                    admin_mitra.namamitra, 
                                                    admin_mitra_cs.namacs 
                                                    FROM admin_mitra
                                                    INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                    where admin_mitra.idadmin = '717'");
                $tampilnama=$datamitra->fetch_assoc();   
    ?>

    <div class="row align-items-start">
        <div class="col">
            <h5>
                <strong style="float:left"> Kode Mitra : <?= $tampilnama['idadmin']; ?> 
                    <?php 
                        if ($mitra=='A') { 
                            echo "/ ";
                            echo $mitra;
                            echo $tampilnama['idmitraagen'];
                        }
                        if ($mitra=='R') { 
                            echo "/ ";
                            echo $mitra;
                            echo $tampilnama['idmitrareseller'];
                        }
                        if ($mitra=='M') { 
                            echo "/ ";
                            echo $mitra;
                            echo $tampilnama['idmitramarketer'];
                        }
                    ?>
                </strong>
            </h5>
        </div>
        <div class="col">
        </div>
        <div class="col">
            <h5><strong style="float:rigth"> Nama CS : <?= $tampilnama['namacs']; ?> </strong></h5>
        </div>
    </div>
    <table class="table" id="tb_multiprint" style="font-size: 25px; color: black;">
        <thead>
            <tr>       
                <th>No</th>
                <th>Invoice</th>
                <th>Nama Produk</th>
                <th>QTY</th>
                <th>Checker</th>
                <th>Penerima</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            
                $query335 = "SELECT 
                            surat_jalan_po.invoice,
                            surat_jalan_po.progres,
                            surat_jalan_po.status,
                            surat_jalan_po.waktu,
                            surat_jalan_po.id_sj,
                            pomitra.custom,
                            pomitra.idmitra,
                            podetail.variant
                            FROM surat_jalan_po
                            inner join pomitra on pomitra.idpomitra = surat_jalan_po.idpomitra
                            inner join podetail on podetail.idpodetail = surat_jalan_po.idpodetail
                            WHERE surat_jalan_po.invoice = 'D377-7171131003' and surat_jalan_po.progres>0
                        ";
                $datapo335 = $koneksi->query($query335);
                while($tampilkandata = $datapo335->fetch_assoc()){
                    $invoice = $tampilkandata['invoice'];
                    $custom = $tampilkandata['custom'];
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
                <td><?= $no = $no+1; ?></td>
                <td><?= $invoice; ?></td>
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
                                                            limit 100
                                                ");
                    // var_dump($custom, $invoice);
                    // die();
                        $first = true;
                        while ($data_produk = $query->fetch_assoc()) {
                            if (!$first) {
                                echo " - ";
                            }
                            $first = false;
                    ?>
                        <?= $data_produk['variant'] ?>
                    <?php 
                        }
                    ?>
                </td>                           
                <td><?= $tampilkandata['progres']; ?> </td>
                <td></td>
                <td></td>
            </tr>
            <?php 
                $total = $total +$tampilkandata['progres']; 
                }
            ?>
            <tr>
                <td colspan="3"><b>Total</b></td>
                <td><b><?= $total; ?></b></td> 
                <td></td>
                <td></td>   
            </tr>
        </tbody>
    </table>
    <?php
    ?>
    <br><br>
    <center>
        <table style="font-size: 25px;">
            <tr>
                <td style="border-color: white;">
                    <center>Gudang</center>
                    <br><br><br><br>      
                </td>
                <td width="5%" style="border-color: white;"></td>
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

                                                          