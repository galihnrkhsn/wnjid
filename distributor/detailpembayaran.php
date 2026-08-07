<?php 
session_start();

include 'koneksi.php'; 
// include 'floatingbutton.php';
if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}
$idpoproduk = $_GET['id'];
$invoice = $_GET['invoice'];
$idmitra = $_SESSION['admin_mitra']['idadmin'];
$datapo=$koneksi->query("SELECT poproduk.namapo, popembayaran.jenis 
                            FROM poproduk
                            JOIN popembayaran on poproduk.idpoproduk = popembayaran.idpoproduk
                            where popembayaran.invoice = '$invoice'
                            LIMIT 1
                            ");
$tampilpo=$datapo->fetch_assoc(); 
$nama = $tampilpo['namapo'];
$jenis = $tampilpo['jenis'];
?>
<title>Mitra <?= $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/b812ba9ae3.js" crossorigin="anonymous"></script> 



		
<style>

.navbaru {
   
    background: #eee   center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 12px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru i {
  
  padding: 15px 0;
  font-size: 23px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

</style>
	
	</head>
	<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>Detail Pembayaran</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<!--================ NAVBARU END =================-->


<div class="container" align="center">
<br><center><h3><?= $nama; ?></h3></center><br>  
        <div class="table-responsive">            
        <table class="table table-bordered" id="tb_dp" style="width: 100%">
          <thead>
            <tr>
              <th>No</th>
              <th>Invoice</th>
              <?php if ($jenis=='dp' or $jenis==''): ?>
              <th>Transfer DP</th>              
              <th>Bank</th>
              <th>Tanggal DP</th>
              <?php else: ?>
              <th>Payment 1</th>              
              <th>Bank Payment 1</th>
              <th>Tanggal Payment 1</th>                
              <?php endif ?>
              <?php if ($jenis=='dp' or $jenis==''): ?>
              <th>Transfer Pelunasan</th>              
              <th>Bank Pelunasan</th>
              <th>Tanggal Pelunasan</th>
              <?php else: ?>  
              <th>Payment 2</th>              
              <th>Bank Payment 2</th>
              <th>Tanggal Payment 2</th>
              <th>Transfer Payment 3</th>              
              <th>Bank Payment 3</th>
              <th>Tanggal Payment 3</th>                            
              <?php endif ?>
            </tr>
          </thead>
          <tbody>
                          <?php 
                            $no=1;
                            $datapo=$koneksi->query("SELECT 
                              admin_mitra.namamitra,
                              pomitra.invoice,
                              pomitra.status,
                              MAX(popembayaran.idpembayaran) as idpembayaran
                              FROM  popembayaran
                              LEFT JOIN pomitra on popembayaran.invoice=pomitra.invoice 
                              
                              LEFT JOIN admin_mitra on pomitra.idmitra=admin_mitra.idadmin                               
                              WHERE pomitra.invoice = '$invoice'
                              GROUP BY pomitra.invoice 
                              ORDER BY popembayaran.idpembayaran desc");
                           
                            while($tampilkan=$datapo->fetch_assoc()){
                              $id = $tampilkan['idpembayaran'];
                              $invoice = $tampilkan['invoice'];

                                $query = "SELECT jmlhtransfer, bankpengirim, jenis, tgl
                                      FROM popembayaran 
                                      where invoice='$invoice' and (jenis ='Payment 1' or jenis ='dp' or jenis is null)";
                                $sqlpo = mysqli_query($koneksi, $query);  
                                $tampilpo = mysqli_fetch_array($sqlpo);   

                                $query2 = "SELECT jmlhtransfer, jmlh_lunas, bankpengirim, jenis, tgl
                                      FROM popembayaran 
                                      where invoice='$invoice' and (jenis ='Payment 2' or jenis ='dp' or jenis is null)";
                                $sqlpo2 = mysqli_query($koneksi, $query2);  
                                $tampilpo2 = mysqli_fetch_array($sqlpo2);                       
                                 
                                $query3 = "SELECT jmlhtransfer, bankpengirim, jenis, tgl
                                      FROM popembayaran 
                                      where invoice='$invoice' and jenis ='Payment 3'";
                                $sqlpo3 = mysqli_query($koneksi, $query3);  
                                $tampilpo3 = mysqli_fetch_array($sqlpo3);   

                            ?>
                        <tr>
                          <td><?= $no++; ?></td>                        
                          <td><?= $tampilkan['invoice']; ?></td>
                          <td>Rp. <?= number_format($tampilpo['jmlhtransfer']); ?></td>
                          <td><?= $tampilpo['bankpengirim']; ?></td>                          
                          <td><?= $tampilpo['tgl']; ?></td>

                          
                          <?php if ($jenis=='dp'): ?>
                          <td>Rp. <?= number_format($tampilpo2['jmlh_lunas']); ?></td>  
                            <?php else: ?>                              
                          <td>Rp. <?= number_format($tampilpo2['jmlhtransfer']); ?></td>
                          <?php endif ?>                              
                          <?php if($jenis=='dp'and $tampilpo2['jmlh_lunas']<>""): ?>
                          <td><?= $tampilpo2['bankpengirim']; ?></td>                          
                          <td><?= $tampilpo2['tgl']; ?></td>
                          <?php elseif($jenis=='dp' and $tampilpo2['jmlh_lunas']==""): ?>
                            <td></td>
                            <td></td>
                          <?php endif ?> 
                          <?php if ($jenis<>'dp'): ?>
                          <td><?= $tampilpo2['bankpengirim']; ?></td>                          
                          <td><?= $tampilpo2['tgl']; ?></td>  
                          <td>Rp. <?= number_format($tampilpo3['jmlhtransfer']); ?></td>
                          <td><?= $tampilpo3['bankpengirim']; ?></td>                          
                          <td><?= $tampilpo3['tgl']; ?></td>                                                   
                          <?php endif ?> 
                        </tr>
                        <?php } ?>
          </tbody>
        </table>
    </div> 

</div>
	</body>
</html>


