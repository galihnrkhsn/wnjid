<?php
include "koneksi.php";

$no_sj = $_GET['no_sj'];      

  $sql = "SELECT admin_mitra.namamitra, surat_jalan_manual.status, surat_jalan_manual.invoice FROM surat_jalan_manual JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin WHERE surat_jalan_manual.no_sj='$no_sj' ";
  $query = $koneksi->query($sql);
  $datadb = $query->fetch_assoc();
$invoice = $datadb['invoice'];
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
<center><h2><strong><?php 
date_default_timezone_set('Asia/Jakarta');
$tgl = date("Y-m-d");

echo $tgl; 
echo "<br>";
$statusnya = $datadb['status'];
if ($statusnya=='INV' or $statusnya=='IPO') {
echo "Inv. ".$invoice;
}else{

echo "No. ".$no_sj;
}
?></strong></h2></center>

  <?php 

            $datamitra=$koneksi->query("SELECT admin_mitra.namamitra,admin_mitra.idadmin, admin_mitra_cs.namacs FROM surat_jalan_manual
                            INNER JOIN admin_mitra on admin_mitra.idadmin = surat_jalan_manual.idadmin 
                            INNER JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                            where surat_jalan_manual.no_sj='$no_sj'");
                            $tampilnama=$datamitra->fetch_assoc();   
  ?>

  <div class="row align-items-start">
    <div class="col">
       <h5><strong style="float:left"> Kode Mitra : <?php echo $tampilnama['idadmin']; ?> </strong></h5>
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
            <th>Nama Produk</th>
            <th >QTY</th>
            <th >Checker</th>
            <th >Penerima</th>
            </tr>
</thead>

            <tbody>


            <?php 

if ($statusnya=='RS' or $statusnya=='INV') {               
            $datapo=$koneksi->query("SELECT 
                                            produk.namaproduk,
                                            surat_jalan_manual.progres,
                                            surat_jalan_manual.waktu
                                            FROM surat_jalan_manual
                                            JOIN produk on produk.idproduk = surat_jalan_manual.idproduk
                                            WHERE surat_jalan_manual.no_sj = '$no_sj' and surat_jalan_manual.progres>0
                                    ");
                            
}
if ($statusnya=='PO' or $statusnya=='IPO') {     
                        $datapo=$koneksi->query("SELECT 
                          podetail.variant as namaproduk,
                          podetail.harga,
                          surat_jalan_manual.progres
                          FROM surat_jalan_manual
                          JOIN podetail on podetail.idpodetail = surat_jalan_manual.idproduk
                          WHERE surat_jalan_manual.no_sj = '$no_sj'
                          ORDER BY podetail.variant asc
                          ");
}      
                            $no=1;
                            while($tampilkandata=$datapo->fetch_assoc()){
                             
            ?>
             <tr>
                         <td >
                             <?php echo $no++; ?>
                        </td>  
                          <td >
                            <?php echo $tampilkandata['namaproduk']; ?>
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
                            <td colspan="2">
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

                                                          