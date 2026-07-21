<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
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
		
		
	</head>
	<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>KONFIRMASI</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #eee  url("jumbotron-bg.png") center center;
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

<!--================ NAVBARU END =================-->

 <!--================ATAS END  =================-->  

<div class="container">
    
    <center>
       
        </center><br>
      <!--   <pre>
<form method="get">
<label>Cari Nama Produk</label>

<input type="text" name="namaproduk">  <button type="submit" class="btn btn-primary" name="cari">Cari</button>  <button type="submit" class="btn btn-primary" name="tampil">Tampil Semua</button></form></pre>-->
         <form method="post">
              <input type="date" name="tanggal">
    <!--          <input type="text" placeholder="Cari No Order..."  name="noorder"> -->
                <button class="btn btn-primary" type="submit" name="cari">
                  <i class="fas fa-search fa-sm"></i>
                </button>
                   <button class="btn btn-warning" type="submit" name="tampil">
                       Tampil Semua
                </button>
             </form><br>
     
       <?php
         if(isset($_POST["cari"])){
      $no=1;
      $tgl=$_POST["tanggal"];
      //$noorder=$_POST["noorder"];
      $page = (isset($_GET['page']))? $_GET['page'] : 1;
	  $limit = 20; 
	  $limit_start = ($page - 1) * $limit;
      $ambil=$koneksi->query("SELECT tgl, COUNT(noorder) as invoice, SUM(dropship) as ekspedisi , SUM(subtotal) as total, status FROM `tagihan`where tgl='$tgl' GROUP BY tgl order by tgl desc  ");       
      }elseif(isset($_GET["tampil"])){
      $no=1;
      $page = (isset($_GET['page']))? $_GET['page'] : 1;
	  $limit = 20; 
	  $limit_start = ($page - 1) * $limit;
      $ambil=$koneksi->query("SELECT DISTINCT ordermitra.idmitra, orderpembayaran.tgl,orderpembayaran.rekeningpengirim,admin_mitra.namamitra,orderpengiriman.invoice,orderpengiriman.total,orderpembayaran.bankpengirim,orderpembayaran.metodebayar,orderpembayaran.jmlhtransfer FROM orderpengiriman inner join admin_mitra inner join orderpembayaran inner join ordermitra on admin_mitra.idadmin=ordermitra.idmitra and orderpembayaran.invoice=orderpengiriman.invoice GROUP by orderpembayaran.invoice ORDER BY orderpembayaran.idpembayaran DESC LIMIT ".$limit_start.",".$limit." "); 
      }else{
      $no=1;
      $page = (isset($_GET['page']))? $_GET['page'] : 1;
	  $limit = 20; 
	  $limit_start = ($page - 1) * $limit;
      $ambil=$koneksi->query("SELECT DISTINCT ordermitra.idmitra, orderpembayaran.tgl,orderpembayaran.rekeningpengirim,admin_mitra.namamitra,orderpengiriman.invoice,orderpengiriman.total,orderpembayaran.bankpengirim,orderpembayaran.metodebayar,orderpembayaran.jmlhtransfer FROM orderpengiriman inner join admin_mitra inner join orderpembayaran inner join ordermitra on admin_mitra.idadmin=ordermitra.idmitra and orderpembayaran.invoice=orderpengiriman.invoice GROUP by orderpembayaran.invoice ORDER BY orderpembayaran.idpembayaran DESC LIMIT ".$limit_start.",".$limit.""); 
      }
      while($data=$ambil->fetch_assoc()){
      ?>
      
        <div class="table-responsive">
    <table class="table" id="dataTables-example">
    <thead>
        
     <tr>
          <td><b><?php echo $data['tgl'];?></b><br>DB: <?php echo $data['namamitra'];?> #<?php echo $data['invoice'];?><br>Tagihan <?php echo number_format($data['total']);?></td>
          <td><?php echo $data['bankpengirim'];?> <?php echo $data['rekeningpengirim'];?> -> <?php echo $data['metodebayar'];?><br>Jumlah Transfer : <br>Rp. <?php echo number_format($data['jmlhtransfer']);?><br><form method="post"><input type="hidden" name="invoice" value=<?php echo $data['invoice']; ?>><button type="submit" class="btn btn-primary btn-xs" name="approve">Approve</button> <a class="btn btn-success btn-xs" href="#">confrim WA</a></form></td>
          
    </tr>
    
</table><br>
     </div>
  <?php } ?>
<ul class="pagination">
				<!-- LINK FIRST AND PREV -->
				<?php
				if($page == 1){ // Jika page adalah page ke 1, maka disable link PREV
				?>
					<li class="disabled"><a href="#">First</a></li>
					<li class="disabled"><a href="#">&laquo;</a></li>
				<?php
				}else{ // Jika page bukan page ke 1
					$link_prev = ($page > 1)? $page - 1 : 1;
				?>
					<li><a href="?page=1">First</a></li>
					<li><a href="?page=<?php echo $link_prev; ?>">&laquo;</a></li>
				<?php
				}
				?>
				
				<!-- LINK NUMBER -->
				<?php
				// Buat query untuk menghitung semua jumlah data
				$sql2 = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM orderpembayaran");
				$get_jumlah = mysqli_fetch_array($sql2);
				
				$jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah halamannya
				$jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
				$start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
				$end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number
				
				for($i = $start_number; $i <= $end_number; $i++){
					$link_active = ($page == $i)? ' class="active"' : '';
				?>
					<li<?php echo $link_active; ?>><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
				<?php
				}
				?>
				
				<!-- LINK NEXT AND LAST -->
				<?php
				// Jika page sama dengan jumlah page, maka disable link NEXT nya
				// Artinya page tersebut adalah page terakhir 
				if($page == $jumlah_page){ // Jika page terakhir
				?>
					<li class="disabled"><a href="#">&raquo;</a></li>
					<li class="disabled"><a href="#">Last</a></li>
				<?php
				}else{ // Jika Bukan page terakhir
					$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
				?>
					<li><a href="?page=<?php echo $link_next; ?>">&raquo;</a></li>
					<li><a href="?page=<?php echo $jumlah_page; ?>">Last</a></li>
				<?php
				}
				?>
			</ul>
  
  
</div>

	<?php
	if(isset($_POST["approve"])){
	$invoice = $_POST['invoice'];
	
	$koneksi->query("UPDATE ordermitra SET status='Diproses',payment='LUNAS' where invoice='$invoice' "); 
	echo "<script>location='konfirmasi.php'</script>";
	}
	?>

</body>
</html>

