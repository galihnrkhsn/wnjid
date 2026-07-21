  <?php
	session_start();


  	include "koneksi.php";
	include 'assets/components/Sessions/sesDistri.php';
    
	$idadmin = $_GET['idadmin'];
	

	$query = "SELECT * FROM admin_mitra WHERE idadmin='".$idadmin."'";
	$sql = mysqli_query($koneksi, $query);  
	$data = mysqli_fetch_array($sql);


	if (isset($_POST['ubah'])) {
		$passwordlama = $_POST["passwordlama"];
		$passwordbaru = $_POST["passwordbaru"];


		$sql = "SELECT COUNT(*) as jumlah FROM admin_mitra WHERE idadmin='$idadmin' AND password = '$passwordlama' ";
		$query = $koneksi->query($sql);
		$hasil = $query->fetch_assoc();

		if ($hasil['jumlah'] == 1) {
		$query = "UPDATE admin_mitra SET password='".$passwordbaru."' WHERE idadmin='".$idadmin."'  ";
		$sql = mysqli_query($koneksi, $query);
		echo "<script>
				alert('Password Berhasil Diubah!');
				document.location.href = 'setting.php';
			</script>";
		}else if($hasil['jumlah']==0) {
		echo "<script>
				alert('Password Lama tidak sama!');
				document.location.href = 'setting.php';
			</script>";
		}
	}



  ?>
  
<html lang="en">
	<head>
		<title>Mitra <?= $data['namamitra']; ?>| Wanoja </title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
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
		<div class="col-2"><a href="setting.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
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


 
    <div class="container">
		<div class="col-md-6">
			<form method="post" action="">
				<div class="form-group">
					<label>Password Lama</label>
					<input class="form-control" type="password" name="passwordlama" id="passwordlama" value="">
				</div>
				<div class="form-group">
					<input type="checkbox" class="form-checkbox" name="checkpasslama" id="checkpasslama"> Show password
				</div>
				<div class="form-group">
					<label>Password Baru</label>
					<input class="form-control" type="password" name="passwordbaru" id="passwordbaru" value="">
				</div>
				<div class="form-group">
					<input type="checkbox" class="form-checkbox" name="checkpassbaru" id="checkpassbaru"> Show password
				</div>
				<hr>  
				<button type="submit" name="ubah" class="btn btn-primary">Ubah</button>
				<a class="btn btn-default" href='profile.php'>Batal</a>
			</form>
		</div>
    </div>
 


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

<script type="text/javascript">
	$(document).ready(function(){		
		$('#checkpasslama').click(function(){
			if($(this).is(':checked')){
				$('#passwordlama').attr('type','text');
			}else{
				$('#passwordlama').attr('type','password');
			}
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function(){		
		$('#checkpassbaru').click(function(){
			if($(this).is(':checked')){
				$('#passwordbaru').attr('type','text');
			}else{
				$('#passwordbaru').attr('type','password');
			}
		});
	});
</script>


</body>
</html>