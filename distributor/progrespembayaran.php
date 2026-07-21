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
$idmitra = $_SESSION['admin_mitra']['idadmin'];
$datapo=$koneksi->query("SELECT poproduk.namapo, popembayaran.jenis 
                            FROM poproduk
                            JOIN popembayaran on poproduk.idpoproduk = popembayaran.idpoproduk
                            where poproduk.idpoproduk='$idpoproduk'
                            LIMIT 1
                            ");
$tampilpo=$datapo->fetch_assoc(); 
$nama = $tampilpo['namapo'];
$jenis = $tampilpo['jenis'];
?>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
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
  <div class="col-8" ><p>Progres Pembayaran</p></div>
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
              <th>Total Tagihan</th>
              <th>Transfer</th>
              <th>Sisa</th>
            </tr>
          </thead>
          <tbody>
                          <?php 
                            $no=1;
                            $datapo=$koneksi->query("SELECT 
                              admin_mitra.namamitra,
                              pomitra.invoice,
                              pomitra.status,
                              MAX(popembayaran.idpembayaran) as idpembayaran,
                              podropship.ongkir,
                              podropship.dropship
                              FROM  popembayaran
                              LEFT JOIN pomitra on popembayaran.invoice=pomitra.invoice 
                              
                              LEFT JOIN admin_mitra on pomitra.idmitra=admin_mitra.idadmin
                              LEFT JOIN podropship on pomitra.invoice = podropship.invoice                               
                              WHERE pomitra.idpoproduk = '$idpoproduk'
                              AND pomitra.idmitra ='$idmitra'
                              GROUP BY pomitra.invoice 
                              ORDER BY popembayaran.idpembayaran desc");
                           
                            while($tampilkan=$datapo->fetch_assoc()){
                              $id = $tampilkan['idpembayaran'];
                              $invoice = $tampilkan['invoice'];
                              $ongkir = $tampilkan['ongkir'];
                              $dropship = $tampilkan['dropship'];

                                $query = "SELECT SUM(jmlhtransfer) as jmlhtf, SUM(jmlh_lunas) as jmlhlunas
                                      FROM popembayaran 
                                      where invoice='$invoice'";
                                $sqlpo = mysqli_query($koneksi, $query);  
                                $tampilpo = mysqli_fetch_array($sqlpo);   

                                $query2 = "SELECT SUM(pomitra.jumlah*podetail.harga) as total
                                      FROM pomitra
                                      JOIN podetail on podetail.idpodetail = pomitra.idpodetail
                                      where pomitra.invoice='$invoice'";
                                $sqlpo2 = mysqli_query($koneksi, $query2);  
                                $tampilpo2 = mysqli_fetch_array($sqlpo2);   

                                $totaltagihan = $tampilpo2['total']-($tampilpo2['total']*35/100)+$ongkir+$dropship;  
                                $totaltf = $tampilpo['jmlhtf']+$tampilpo['jmlhlunas']    
                            ?>
                        <tr>
                          <td><?= $no++; ?></td>                         
                          <td><?= $tampilkan['invoice']; ?></td>
                          <td>Rp. <?= number_format($totaltagihan); ?></td>
                          <td>
                            <a href="detailpembayaran?id=<?= $idpoproduk; ?>&invoice=<?=$invoice ?>">Rp. <?= number_format($totaltf); ?> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-circle-fill" viewBox="0 0 16 16">
                              <path d="M0 8a8 8 0 1 0 16 0A8 8 0 0 0 0 8zm5.904 2.803a.5.5 0 1 1-.707-.707L9.293 6H6.525a.5.5 0 1 1 0-1H10.5a.5.5 0 0 1 .5.5v3.975a.5.5 0 0 1-1 0V6.707l-4.096 4.096z"/>
                            </svg>
                            </a>
                          </td>
                          <td>Rp. <?= number_format($totaltagihan - $totaltf); ?></td>
                        </tr>
                        <?php } ?>
          </tbody>
        </table>
    </div> 

</div>
	</body>
</html>


