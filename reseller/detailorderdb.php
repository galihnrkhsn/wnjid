<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$invoice=$_GET["id"];


	$sql = "SELECT * FROM orderpengirimandb WHERE invoice='$invoice' ";
	$query = $koneksi->query($sql);
	$pengiriman = $query->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
	<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

		
		<style>
		.align-middle{
			vertical-align: middle !important;
		}
		
		#myJudul {
    
  text-align: center;
  border-collapse: collapse;
  width: 100%;
  font-size: 18px;
  
  
}
#myJudul th  {
  text-align: center;
  padding: 12px;
  background-color: #f1f1f1;
  font-size: 18px;
}
#myJudul td {
  text-align: center;
  padding: 12px;
 
  
}
		
		</style>
<style type="text/css">
		p.dotted {
			border-style: dotted;
		}
		p.dashed {
			border-style: dashed;
		}
		p.solid {
			border-style: solid;
		}
		p.double {
			border-style: double;
		}
		p.groove {
			border-style: groove;
		}
		p.ridge {
			border-style: ridge;
		}
		p.inset {
			border-style: inset;
		}
		p.outset {
			border-style: outset;
		}
		p.none {
			border-style: none;
		}
		p.hidden {
			border-style: hidden;
		}
		p.mix {
			border-style: dotted dashed solid double;
		}
	</style>
<style type="text/css">
   .left    { text-align: left;}
   .right   { text-align: right;}
   .center  { text-align: center;}
   .justify { text-align: justify;}
</style>	
	
		
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="transaksidb.php"><span class="glyphicon glyphicon-chevron-left"</span></a></div>
  <div class="col-8" ><p>DETAIL ORDER DB</p></div>
  <div class="col-2"></div>
</div><br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #fefbd8  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navhitam {
   
    background: black  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 10px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru span {
  
  padding: 5px 0;
  font-size: 30px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru2 {
   
    
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

</style>

<div class="container">
    
    
    
    <h3><center>Invoice #<?php echo $invoice; ?></center></h3>
    
    <hr>
    
    <h4><center><?php echo $_SESSION["mitraagen"]["namaagen"]; ?> (Cust ID : <?php echo $_SESSION["mitraagen"]["idmitrareseller"]; ?> )</center></h4><br>
    
    <b>Status Pesanan</b><br>
    <?php echo $pengiriman['tgl']; ?><br>
    
    <?php
    $sql = "SELECT status,payment FROM orderagendb WHERE invoice='$invoice' ";
	$query = $koneksi->query($sql);
	$status = $query->fetch_assoc();
    ?>
    
    Payment : <?php echo $status['payment'] ?><br>
    Status  : <?php echo $status['status'] ?>
    <hr>
    <b>Data Pengiriman</b><br>
    Dari        : <?php echo $pengiriman['namapengirim']; ?><br>
    Dikirim Ke  : <?php echo $pengiriman['namapenerima']; ?><br>
    Alamat      : <?php echo $pengiriman['alamat']; ?><br>
    Provinsi    : <?php echo $pengiriman['provinsi']; ?><br>
    Kota/Kab    : <?php echo $pengiriman['kota']; ?><br>
    Kecamatan   : <?php echo $pengiriman['kecamatan']; ?><br>
    Ekspedisi   : <?php echo $pengiriman['ekspedisi']; ?><br>
    <hr>
    
    <b>Item Pesanan</b><br>
    
    <?php
   // $total=0;
    $sql = "SELECT * FROM orderagendb inner join produkdb on produkdb.idprodukdb=orderagendb.idprodukdb WHERE orderagendb.invoice='$invoice' and orderagendb.jumlah>0 ";
	$query = $koneksi->query($sql);
	while ($order = $query->fetch_assoc()){
    ?>
    
    <?php echo $order['namaproduk']; ?> <?php echo $order['jumlah'];?>Pcs : Rp. <?php echo number_format($order['subtotal']); ?><br>
    
    <?php } ?>
    
  <?php
    $totala=0;
    $sql = "SELECT * FROM orderagendb inner join produkdb on produkdb.idprodukdb=orderagendb.idprodukdb WHERE orderagendb.invoice='$invoice' and orderagendb.jumlah>0 ";
	$query = $koneksi->query($sql);
	while ($ga = $query->fetch_assoc()){
    ?>
    
    <?php  $totala +=  $ga['subtotal']; } ?>
    
    
    <hr>
     <?php
   //  $biayad=0;
     $apaja=$pengiriman['dropship'];
    $dropship=$pengiriman['berat'];
    
        if($dropship<=5000 and $dropship>=0 and $apaja=='ya') {
            $biayad=3000;
        }
        else if($dropship<=10000 and $dropship>=6000 and $apaja=='ya') {
            $biayad=5000;
        }
        else if($dropship<=20000 and $dropship>=11000 and $apaja=='ya') {
            $biayad=10000;
        }
         else if($dropship<=30000 and $dropship>=21000 and $apaja=='ya') {
            $biayad=15000;
        }
        else if($dropship<=40000 and $dropship>=31000 and $apaja=='ya') {
            $biayad=20000;
        }
         else if($dropship<=50000 and $dropship>=41000 and $apaja=='ya') {
            $biayad=25000;
        }
        else if($dropship<=60000 and $dropship>=51000 and $apaja=='ya') {
            $biayad=30000;
        }
         else if($dropship<=70000 and $dropship>=61000 and $apaja=='ya') {
            $biayad=35000;
        }
        else if($dropship<=80000 and $dropship>=71000 and $apaja=='ya') {
            $biayad=40000;
        }
         else if($dropship<=90000 and $dropship>=81000 and $apaja=='ya') {
            $biayad=45000;
         }
        else if($dropship<=100000 and $dropship>=91000 and $apaja=='ya') {
            $biayad=50000;
         }
        else if($dropship<=110000 and $dropship>=101000 and $apaja=='ya') {
            $biayad=55000;
        }
         else if($dropship<=120000 and $dropship>=111000 and $apaja=='ya') {
            $biayad=60000;
        }
        else if($dropship<=130000 and $dropship>=121000 and $apaja=='ya') {
            $biayad=65000;
        }
         else if($dropship<=140000 and $dropship>=131000 and $apaja=='ya') {
            $biayad=70000;
        }
        else if($dropship<=150000 and $dropship>=141000 and $apaja=='ya') {
            $biayad=75000;
        }
         else if($dropship<=160000 and $dropship>=151000 and $apaja=='ya') {
            $biayad=80000;
        }
        else if($dropship<=170000 and $dropship>=161000 and $apaja=='ya') {
            $biayad=85000;
        }
         else if($dropship<=180000 and $dropship>=171000 and $apaja=='ya') {
            $biayad=90000;
         }
        else if($dropship<=190000 and $dropship>=181000 and $apaja=='ya') {
            $biayad=95000;
        }
         else if($dropship<=200000 and $dropship>=191000 and $apaja=='ya') {
            $biayad=100000;
         }
        else if($apaja=='tidak'){
            $biayad=0;
        }        
        
    $tbiayad=number_format($biayad); 
    $ongkir=$pengiriman['ongkir'];
    $kurir=$pengiriman['ekspedisi'];
    $diskona=$totala*15/100;
   // $diskonb=$totalb*55/100;
    $tdiskona=number_format($diskona);
  //  $tdiskonb=number_format($diskonb);
    $grandtotal=($totala+$ongkir+$biayad)-($diskona);
    $tongkir=number_format($ongkir);
    $tgrandtotal=number_format($grandtotal);
    $total=$totala;
    $ttotal=number_format($total);
    if($ongkir==0){
      if($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat') {
           echo "<p align='right'><b>Total : Rp. $ttotal</b>";
         echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
        echo "Ongkir : Rp. $tongkir<br>";
        echo "Diskon Reseller 15% : Rp. -$tdiskona<br>";
       //  echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
       echo "<hr>";
       echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
        }else{
              echo "<p align='right'><b>Total : $ttotal</b>";
            echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
            echo "Ongkir : Menunggu di Isi Admin<br>";
            echo "Diskon Reseller 15% : Rp. -$tdiskona<br>";
         //   echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
            echo "<hr>";
            echo "<p align='right'><b>GrandTotal : Pending</b><br></br></p>";
       }
    }else{
    echo "<p align='right'><b>Total : Rp. $ttotal</b>";
    echo "<p align='right'>Biaya Dropship : Rp. $tbiayad<br>";
    echo "Ongkir : Rp. $tongkir<br>";
    echo "Diskon Reseller 15% : Rp. -$tdiskona<br>";
 //   echo "Diskon Grade B 55% : Rp. -$tdiskonb<br></p>";
    echo "<hr>";
    echo "<p align='right'><b>GrandTotal : Rp. $tgrandtotal</b><br></br></p>";
    }
        ?>
    
    
    <?php
        if($ongkir==0){
            if($kurir=='Ahsan' or $kurir=='Gosend' or $kurir=='Ambil ke Pusat') {
                echo "<center><a class='btn btn-info btn-lg' href='formpembayarandb.php?id=$invoice&total=$grandtotal'>Konfimasi Pembayaran</a></center>";
            }else{
            echo "<center><a class='btn btn-info btn-lg' href='#'>Menunggu Total Pembayaran</a></center>";
            }
    }
    else if($status['status']=='Pending'){
    echo "<center><a class='btn btn-info btn-lg' href='formpembayarandb.php?id=$invoice&total=$grandtotal'>Konfimasi Pembayaran</a></center>";
    } 
    else{
         echo "<center><a class='btn btn-info btn-lg' href='#'>Menunggu Pengiriman</a></center>";
    } 
    ?>
</div>


	</div>
	</div>
</div>
</body>
</html>