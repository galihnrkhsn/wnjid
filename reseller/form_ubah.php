
  
  <?php
  session_start();
  // Load file koneksi.php
  include "koneksi.php";

if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}     
    
  $idmitrareseller = $_GET['idmitrareseller'];
  

  $query = "SELECT * FROM mitrareseller WHERE idmitrareseller='".$idmitrareseller."'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql); 
  ?>
  
<html lang="en">
<head>
	<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
	<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="profile.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PROFILE</p></div>
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


  
    <div class="container panel-default">
   
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
                                
  <form method="post" action="proses_ubah.php?idmitrareseller=<?php echo $idmitrareseller; ?>" enctype="multipart/form-data">
    
      <div class="form-group">
          <label>Nama Mitra</label>
    <input class="form-control" name="namaagen" value="<?php echo $data['namaagen']; ?>">
    </div>
    
    <div class="form-group">
          <!-- <label>Password</label> -->
    <input class="form-control" type="hidden" name="password" value="<?php echo $data['password']; ?>">
    </div>
    
     <div class="form-group">
          <label>Whatsapp</label>
    <input class="form-control" name="whatsapp" value="<?php echo $data['whatsapp']; ?>">
    <label> <font size="2" color="silver">contoh (628xxxxxxxx)</font></label>
    </div>    
    
      <div class="form-group">
          <label>Telegram</label>
    <input class="form-control" name="telegram" value="<?php echo $data['telegram']; ?>">
    <label> <font size="2" color="silver">isi dengan username telegram tanpa @ <br>(untuk melihat username ada di pengaturan)</font></label>
    </div>
    
    <div class="form-group">
    <label>Facebook</label>
    <input class="form-control" name="facebook" value="<?php echo $data['facebook']; ?>">
    <label> <font size="2" color="silver">isi dengan link facebook pribadi atau bisnis<br>
    misal : www.facebook.com/wanojahijab cukup masukan "wanojahijab"</font></label>
    </div>
    
    <div class="form-group">
    <label>Instagram</label>    
    <input class="form-control" name="instagram" value="<?php echo $data['instagram']; ?>">
    <label> <font size="2" color="silver">isi dengan link IG pribadi atau bisnis<br>
    misal : www.instagram.com/wanojahijab cukup masukan "wanojahijab"</font></label>
    </div>
    
    <div class="form-group">
          <label>Email</label>  
          <input class="form-control" name="email" value="<?php echo $data['email']; ?>" readonly>
    </div>
    
  <div style="display: none;">  
     <div class="form-group">
          <label>Titik Koordinat</label>  
          <input class="form-control" name="titikkordinat" value="<?php echo $data['titikkordinat']; ?>">
          <label> <font size="2" color="silver">1.Buka Aplikasi Google Maps<br>
    2.Cari Lokasi kita, Zoom sampai lokasi terlihat detail<br>
    3.Tekan dan Tahan sampai muncul titik kordinat berada di atas (contoh:-6,121.232.....)<br>
    4.Copy Paste Nomor tersebut, lalu masukan pada form ubah</font></label>
    </div>
    
    <hr>
     <input type="checkbox" name="ubahalamat" value="true"><b> Ceklis jika ingin merubah Alamat</b><br><br>
     
     <label> <font size="2" color="silver">* isi dengan benar semua detail alamat. Alamat ini akan digunakan untuk proses transaksi</font></label><br>
    
    <div class="form-group">
     <label>Alamat</label>      
     <textarea class="form-control" rows="3" name="alamat"><?php echo $data['alamat']; ?></textarea>
     </div>
     
    
     
    	<?php
							
									//Get Data Kabupaten
									//-----------------------------------------------------------------------------

									//Get Data Provinsi
									$curl = curl_init();

									curl_setopt_array($curl, array(
										CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
										CURLOPT_RETURNTRANSFER => true,
										CURLOPT_ENCODING => "",
										CURLOPT_MAXREDIRS => 10,
										CURLOPT_TIMEOUT => 30,
										CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
										CURLOPT_CUSTOMREQUEST => "GET",
										CURLOPT_HTTPHEADER => array(
										"key:d1e0da7453eb42f959f5b3072a94a21c"
										),
										));

										$response = curl_exec($curl);
										$err = curl_error($curl);

										echo "
										<div class= \"form-group\">
											<label for=\"provinsi\">Provinsi Tujuan </label>
											<select class=\"form-control\" name='provinsi' id='provinsi'>";
												echo "<option>Pilih Provinsi Tujuan</option>";
												$data = json_decode($response, true);
												for ($i=0; $i < count($data['rajaongkir']['results']); $i++) {
													echo "<option value='".$data['rajaongkir']['results'][$i]['province_id']."|".$data['rajaongkir']['results'][$i]['province']."'>".$data['rajaongkir']['results'][$i]['province']."</option>";
												}
												echo "</select>
											</div>";
											//Get Data Provinsi
											
										//get data kecamatan
										
											?>

											<div class="form-group">
												<label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
												<select class="form-control" id="kabupaten" name="kabupaten"></select>
											</div>
											
												<div class="form-group">
												<label for="kecamatan">Kecamatan Tujuan</label><br>
												<select class="form-control" id="kecamatan" name="kecamatan"></select>
											</div>
    
     </div>
     
  <hr>  
   <button type="submit" class="btn btn-primary">Ubah</button>
  <a class="btn btn-danger" href='profile.php')>Batal</a>
  </form>
  <script type="text/javascript">

	$(document).ready(function(){
		$('#provinsi').change(function(){

			//Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
			var prov = $('#provinsi').val();

      		$.ajax({
            	type : 'GET',
           		url : 'cek_kabupaten.php',
            	data :  'prov_id=' + prov,
					success: function (data) {

					//jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
					$("#kabupaten").html(data);
				}
          	});
		});
		
	
		$('#kabupaten').change(function(){

			//Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
			var kabupaten = $('#kabupaten').val();

      		$.ajax({
            	type : 'GET',
           		url : 'cek_kecamatan.php',
            	data :  'kabupaten_id=' + kabupaten,
					success: function (data) {

					//jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
					$("#kecamatan").html(data);
				}
          	});
		});


		$("#kurir").change(function(){
			//Mengambil value dari option select provinsi asal, kabupaten, kurir, berat kemudian parameternya dikirim menggunakan ajax
			var asal = $('#asal').val();
			var kab = $('#kabupaten').val();
			var kec = $('#kecamatan').val();
			var kurir = $('#kurir').val();
			var berat = $('#berat').val();

      		$.ajax({
            	type : 'POST',
           		url : 'cek_ongkir.php',
            	data :  {'kab_id' : kab, 'kec_id' : kec, 'kurir' : kurir, 'asal' : asal, 'berat' : berat},
					success: function (data) {

					//jika data berhasil didapatkan, tampilkan ke dalam element div ongkir
					$("#ongkir").html(data);
				}
          	});
		});
	});
</script>
</body>
</html>