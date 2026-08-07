<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
	<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="dataagen.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>SUB MITRA</p></div>
  <div class="col-2"><a href="whatsapp://send?text=http://mitra.wanoja.com/profil.php?user=<?php echo $tampilkan['instagram'] ?>"><i class="fa fa-share-alt" aria-hidden="true"></i></a></div>
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
  margin-top: 15px;
  padding: 2px 0;
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
    <!--<center><p>Tombol export berfungsi untuk mendownload semua data, dengan cara klik tombol <b>'export'</b> lalu ubah file name <b>(.php)</b> menjadi <b>(.pdf)</b></p></center>
-->   
<div class="container">
    <a class="btn btn-success" href="inputagen.php">Tambah Mitra</a> <a class="btn btn-primary" href="dataagen_ubah.php">Ubah Status</a><br><br> 
    
   <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
      <tr>
          <th style="width:15%">Opsi</th>
          <th style="width:15%">Status</th>
          <th style="width:35%">Nama Mitra</th>
          <th style="width:15%">Email</th>
          <th style="width:15%">Whatsapp</th>
          <th style="width:15%">Telegram</th>
          <th style="width:15%">Facebook</th>
          <th style="width:15%">Instagram</th>
          <th style="width:15%">Alamat</th>
      </tr>
      </thead>
      <tbody>
      <?php 
      $page = (isset($_GET['page']))? $_GET['page'] : 1;
	  $limit = 15; // Jumlah data per halamannya
					// Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
	 $limit_start = ($page - 1) * $limit;
	    $idadmin=$_SESSION["admin_mitra"]["idadmin"];				
      $no=1;
      $ambil=$koneksi->query("SELECT * FROM mitra_agen where idadmin='$idadmin' LIMIT ".$limit_start.",".$limit.""); 
      while($distributor=$ambil->fetch_assoc()){
      ?>
      <tr>
          <td> <a class="btn btn-danger btn-xs" href="hapusagen.php?idmitraagen=<?php echo $distributor['idmitraagen'];?>">Hapus</a></td>
          <td><?php echo $distributor['status'];?></td>
          <td><?php echo $distributor['namaagen'];?></td>
          <td><?php echo $distributor['email'];?></td>
          <td><?php echo $distributor['whatsapp'];?></td>
          <td><?php echo $distributor['telegram'];?></td>
          <td><?php echo $distributor['facebook'];?></td>
          <td><?php echo $distributor['instagram'];?></td>
          <td><?php echo $distributor['alamat'];?></td>
      </tr>
      <?php } ?>
      </tbody>
  </table>
  
        	                <?php
                            if(isset($_POST["ubah"])){
	
                        	$idmitraagen = $_POST['idmitraagen'];
	                        $status=$_POST['status'];
	                        
		                    $query = "UPDATE mitra_agen SET status= '".$status."' where idmitraagen='$idmitraagen'";    
                            $sql = mysqli_query( $koneksi, $query);
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: dataagen.php"); // Redirect ke halaman index.php
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
                                    }
                            }    
                             ?>
  
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
				$sql2 = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM mitra_agen");
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
  
<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>


  
</body>
</html>

