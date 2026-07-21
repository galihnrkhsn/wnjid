<?php
include "koneksi.php"; 
  $no_sj = $_GET['no_sj'];
  $idadmin = $_GET['idadmin'];
  $mitra = $_GET['mitra']; 

            $datasj=$koneksi->query("SELECT * FROM surat_jalan_po 
                            where no_sj='$no_sj'");
                            $tampilsj=$datasj->fetch_assoc(); 

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

<center><h1><strong>Surat Jalan PO WNJ</strong></h1></center>
<center><h2><strong><?php 
echo substr($tampilsj['waktu'],0,10);
echo "<br>";
echo "No. ".$tampilsj['no_sj'];
?></strong></h2></center>

<?php

if ($mitra=='D') {
  $datamitra=$koneksi->query("SELECT  admin_mitra.idadmin,
                                    admin_mitra.namamitra, 
                                    admin_mitra_cs.namacs 
                                    FROM admin_mitra
                                    INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                    where admin_mitra.idadmin='$idadmin'");
                            $tampilnama=$datamitra->fetch_assoc();   
}

if ($mitra=='A') {
  $datamitra=$koneksi->query("SELECT  admin_mitra.idadmin,
                                    mitraagen.idmitraagen, 
                                    admin_mitra_cs.namacs 
                                    FROM mitraagen
                                    INNER JOIN admin_mitra  on admin_mitra.idadmin = mitraagen.idadmin
                                    INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                    where mitraagen.idmitraagen='$idadmin'");
                            $tampilnama=$datamitra->fetch_assoc();   
}
if ($mitra=='R') {
  $datamitra=$koneksi->query("SELECT  admin_mitra.idadmin,
                                    mitrareseller.idmitrareseller, 
                                    admin_mitra_cs.namacs 

                                    FROM mitrareseller
                                    INNER JOIN admin_mitra on admin_mitra.idadmin = mitrareseller.idadmin
                                    INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                    where mitrareseller.idmitrareseller='$idadmin'");
                            $tampilnama=$datamitra->fetch_assoc();   
}
if ($mitra=='M') {
  $datamitra=$koneksi->query("SELECT  admin_mitra.idadmin,
                                    mitramarketer.idmitramarketer, 
                                    admin_mitra_cs.namacs 

                                    FROM mitramarketer
                                    INNER JOIN admin_mitra on admin_mitra.idadmin = mitramarketer.idadmin
                                    INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                    where mitramarketer.idmitramarketer='$idadmin'");
                            $tampilnama=$datamitra->fetch_assoc();   
}
  ?>

  <div class="row align-items-start">
    <div class="col">
       <h5>
        <strong style="float:left"> Kode Mitra : <?php echo $tampilnama['idadmin']; ?> 
<?php 
if ($mitra=='A') { 
echo "/ ";
echo $mitra;
echo $tampilnama['idmitraagen'];

}
if ($mitra=='R') { 
echo "/ ";
echo $mitra;
echo $tampilnama['idmitrareseller'];}
if ($mitra=='M') { 
echo "/ ";
echo $mitra;
echo $tampilnama['idmitramarketer'];}

 ?>
        </strong>
      </h5>
    </div>
    <div class="col">
    </div>
    <div class="col">
      <h5><strong style="float:rigth"> Nama CS : <?php echo $tampilnama['namacs']; ?> </strong></h5>
    </div>
  </div>
<table class="table" id="tb_multiprint" style="font-size: 25px; color: black;">
<thead>
          <tr>       
            <th >No</th>
            <th >Invoice</th>
            <th>Nama Produk</th>
            <th >Qty PO</th>
            <th >SJ <?= date_format($tampilsj['waktu'],0,10,"d/m"); ?></th>
            <th >Checker</th>
            <th >Penerima</th>
            </tr>
</thead>

            <tbody>

            <?php 
            $datapo=$koneksi->query("SELECT 
                                            surat_jalan_po.invoice,
                                            surat_jalan_po.progres,
                                            surat_jalan_po.status,
                                            surat_jalan_po.waktu,
                                            surat_jalan_po.id_sj,
                                            pomitra.idmitra,
                                            pomitra.custom,
                                            pomitra.jumlah,
                                            podetail.variant
                                            FROM surat_jalan_po
                                            inner join pomitra on pomitra.idpomitra=surat_jalan_po.idpomitra
                                            inner join podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                                            WHERE surat_jalan_po.no_sj = '$no_sj' and surat_jalan_po.progres>0
                                    ");
                            // $no=1;
                            while($tampilkandata=$datapo->fetch_assoc()){
                             
            ?>
             <tr>
                         <td >
                             <?php echo $no = $no+1; ?>
                        </td>  
                          
                          <td >
                            <?php echo $tampilkandata['invoice']; ?>
                          </td>
                          <td >
                            <?php echo $tampilkandata['variant']; ?>
<?php if ($tampilkandata['custom']): ?>
<br>
<?php echo $tampilkandata['custom']; ?>                       
                            

                          <?php endif ?>
                          </td> 
                          <td >
                           <?php echo $tampilkandata['jumlah']; ?> 
                          </td>
                          <td >
                           <?php echo $tampilkandata['progres']; ?> 
                          </td>
                          <td >
                            
                          </td>
                          <td >
                            
                          </td>
                        </tr>
            <?php 
$total = $total +$tampilkandata['progres']; 
          }
          ?>
          </tbody>

                                    <tr>
                            <td colspan="3">
                              <b>Total</b>
                            </td>
                            <td>
                            <b><?php echo $total; ?> </b> 
                            </td> 
                            <td>
                            </td>
                            <td>
                            </td>   
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
