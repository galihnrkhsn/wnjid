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
$berat=$_GET["berat"];

?>
  
<html lang="en">
<head>
<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">  

<style>

body {
  background: #F1F3FA;
}

/* Profile container */
.profile {
  margin: 20px 0;
}

/* Profile sidebar */
.profile-sidebar {
  padding: 20px 0 10px 0;
  background: #fff;
}

.profile-userpic img {
  float: none;
  margin: 0 auto;
  width: 50%;
  height: 50%;
  -webkit-border-radius: 50% !important;
  -moz-border-radius: 50% !important;
  border-radius: 50% !important;
}

.profile-usertitle {
  text-align: center;
  margin-top: 20px;
}

.profile-usertitle-name {
  color: #5a7391;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 7px;
}

.profile-usertitle-job {
  text-transform: uppercase;
  color: #5b9bd1;
  font-size: 12px;
  font-weight: 600;
  margin-bottom: 15px;
}

.profile-userbuttons {
  text-align: center;
  margin-top: 10px;
}

.profile-userbuttons .btn {
  text-transform: uppercase;
  font-size: 11px;
  font-weight: 600;
  padding: 6px 15px;
  margin-right: 5px;
}

.profile-userbuttons .btn:last-child {
  margin-right: 0px;
}
    
.profile-usermenu {
  margin-top: 30px;
}

.profile-usermenu ul li {
  border-bottom: 1px solid #f0f4f7;
}

.profile-usermenu ul li:last-child {
  border-bottom: none;
}

.profile-usermenu ul li a {
  color: #93a3b5;
  font-size: 14px;
  font-weight: 400;
}

.profile-usermenu ul li a i {
  margin-right: 8px;
  font-size: 14px;
}

.profile-usermenu ul li a:hover {
  background-color: #fafcfd;
  color: #5b9bd1;
}

.profile-usermenu ul li.active {
  border-bottom: none;
}

.profile-usermenu ul li.active a {
  color: #5b9bd1;
  background-color: #f6f9fb;
  border-left: 2px solid #5b9bd1;
  margin-left: -2px;
}

/* Profile Content */
.profile-content {
  padding: 20px;
  background: #fff;
  min-height: 460px;
}

* {
  box-sizing: border-box;
}

body {
  font-family: Arial, Helvetica, sans-serif;
}

/* Style the header */
.header {
  background-color: #f1f1f1;
  padding: 30px;
  text-align: center;
  font-size: 35px;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 50px;
  height: 40px; /* Should be removed. Only for demonstration */
  
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Style the footer */
.footer {
  background-color: #f1f1f1;
  padding: 10px;
  text-align: center;
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media (max-width: 600px) {
  .column {
    width: 50%;
  }
}
img {
  border-radius: 50%;
}


.link:hover{
	color: blue;
}
.link:link{
	color: white;
}
.link:active{
	color: white;
}
.link:visited{
    color: white;
	background: white;
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

</head>
 <script type="text/javascript"> 
history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    </script> 
<body>

<table id="myJudul" class="w3-table-all w3-centered">

    <tr>
      <th style="width:5%;"></th>
      <th style="width:90%;">Data Pengiriman</th>
      <th style="width:5%;"></th>
    </tr>
   
  </table>


  
  <div class="panel panel-default">
   
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
    
    	<p align="right"><input type="radio" onclick="javascript:window.location.href='formpengiriman.phpid=<?php echo $invoice; ?>&berat=<?php echo $berat; ?>'; " checked="checked"> Dropship  <input type="radio" onclick="javascript:window.location.href='formpengiriman2.php?id=<?php echo $invoice; ?>&berat=<?php echo $berat; ?>'; "> Kirim Ke Alamat Pribadi</p>
               
<form method="post">
 
      <div class="form-group">
          <label>Nama Pengirim</label>
    <input type="text" class="form-control" name="namapengirim" value="<?php echo $_SESSION['mitraagen']['namaagen']; ?>">
    </div>
    
     <div class="form-group">
          <label>Telepon Pengirim</label>
    <input type="text" class="form-control" name="tlppengirim" value="<?php echo $_SESSION['mitraagen']['whatsapp']; ?>">
    </div>    
    
    <hr>
    
      <div class="form-group">
          <label>Nama Penerima</label>
    <input type="text" class="form-control" name="namapenerima" >
    </div>
    
    <div class="form-group">
    <label>Telepon Penerima</label>
    <input type="text" class="form-control" name="tlppenerima" >
    </div>
    
        <div class="form-group">
    <label>Alamat</label>
    <textarea class="form-control" name="alamat"></textarea>
    </div>
    
     <div class="form-group">
    <label>Kode POS</label>
    <input type="text" class="form-control" name="kodepos" value="<?php echo $_SESSION['mitraagen']['kodepos']; ?>" required>
    </div>

    <div class="form-group">
	<label for="prov">Provinsi Tujuan</label><br>
	<select class="form-control" id="prov" name="prov" required>
	   <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
	 <?php
	 include "koneksi.php";
	 $idprov=$_SESSION["mitraagen"]["provinsi"];
	 
	
	     $ambil=$koneksi->query("SELECT * FROM tb_ro_provinces");
	    
	 while($row=$ambil->fetch_assoc()){
   ?>
   <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
   <?php } ?>
   </select>
   </div>

											<div class="form-group">
												<label for="kabupaten">Kota/Kabupaten Tujuan</label><br>
												<select class="form-control" id="kabupaten" name="kabupaten" required></select>
											</div>
											
												<div class="form-group">
												<label for="kecamatan">Kecamatan Tujuan</label><br>
												<select class="form-control" id="kecamatan" name="kecamatan" required></select>
											</div>
											
												<div class="form-group">
												<label for="berat">Berat (gram)</label><br>
												<input class="form-control" id="berat" type="text" name="berat" value="<?php echo $berat; ?>" readonly />
											</div>
											
											<div class="form-group">
												<label for="kurir">Kurir</label><br>
												<select class="form-control" id="kurir" name="kurir" required>
												     <option disabled='disabled' selected>~Pilih Kurir Pengiriman~</option>
												     <option value="jne">JNE</option>
												     	<option value="tiki">TIKI</option>
												 	<option value="pos">POS INDONESIA</option>
												 	<option value="wahana">WAHANA</option>
												 	<option value="sicepat">SICEPAT</option>
												 	<option value='jnt'>J&T</option>
												 	<option value='lion'>LION</option>
												 	<optgroup label="Lainnya (Ongkir Manual)">
												 	  <option value='Ahsan'>Ahsan</option>
												 	  <option value='Baraka'>Baraka</option>
                            						  <option value='Dakota'>Dakota</option>
                            						  <option value='IndahCargo'>IndahCargo</option>
                            						  <option value='Adam Cargo'>Adam Cargo</option>
                            						  <option value='Pegasus'>Pegasus</option>
                            						  <option value='Gosend'>GoSend</option>
                            						  <option value='KALOG'>KALOG</option>
                            						  <option value='Sentral'>Sentral</option>
                            						  <option value='CMC KARGO'>CMC CARGO</option>
                            						  <option value='Triplogic'>Triplogic</option>
                            						  <option value='Ambil ke Pusat'>Ambil Ke Pusat</option>
												</select>
											</div>
											
										<div class="form-group" id="ongkir">
												<label for="layanan">Layanan</label><br>
												<select class="form-control" name="layanan" id="layanan"></select>
												<label><font color="grey">*Jika Memilih Kurir dengan Kategori "Lainnya (Ongkir Manual)" lanjut pilih "Kirim" jika opsi layanan masih kosong</font></label>
										</div>
										
										
				</div>
								</div>
							</div>
						</div>
			
<script type="text/javascript">

	$(document).ready(function(){
		$('#prov').change(function(){

			//Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
			var provinsi = $('#prov').val();

      		$.ajax({
            	type : 'GET',
           		url : 'cek_kabupaten2.php',
            	data :  'prov_id=' + provinsi,
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
           		url : 'cek_kecamatan2.php',
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
					$("#layanan").html(data);
				}
          	});
		});
	});
</script>
</div>
 

 <center><button type="submit" class="btn btn-primary btn-lg" name="kirim">Kirim</button></center>
 </form>
 
</body>

<?php
include "koneksi.php";

	 
	if(isset($_POST['kirim'])){
	  $namapengirim=$_POST["namapengirim"];
	 $tlppengirim=$_POST["tlppengirim"];
	  $namapenerima=$_POST["namapenerima"];
	 $tlppenerima=$_POST["tlppenerima"];
	 $alamat=$_POST["alamat"];
	
	 $provinsi_id=$_POST["prov"];
	  $result_explode = explode('|', $provinsi_id);
    $provinsi=$result_explode[1];
	 
	 $kabupaten_id=$_POST["kabupaten"];
	 $result_explode = explode('|', $kabupaten_id);
    $kabupaten=$result_explode[1];
	 
	 $kecamatan_id=$_POST["kecamatan"];
	 $result_explode = explode('|', $kecamatan_id);
    $kecamatan=$result_explode[1];
	 
	 $ekspedisi=$_POST["kurir"];
	 
	 $layanan=$_POST["layanan"];
	$result_explode = explode('|', $layanan);
    $layananku=$result_explode[0]; 
	 
	 $berat=$_POST["berat"];
	 
	$result_explode = explode('|', $layanan);
    $ongkir=$result_explode[1];
	 
	 $kodepos=$_POST["kodepos"];
	 $invoice=$_GET["id"];
	 $total=0;
	 
	$sql = "SELECT subtotal FROM orderagendb WHERE invoice='$invoice' ";
	$query = $koneksi->query($sql);
	while($row = $query->fetch_assoc()){
	    $total= $total+$row['subtotal'];
	}
	
	 $total=$total+$ongkir;
	  $query = "insert into orderpengirimandb (idorderpdb,namapengirim,tlppengirim,namapenerima,tlppenerima,alamat,provinsi,kota,kecamatan,ekspedisi,layanan,berat,ongkir,dropship,kodepos,invoice,total,tgl) values
	  (null,'$namapengirim','$tlppengirim','$namapenerima','$tlppenerima','$alamat','$provinsi','$kabupaten','$kecamatan','$ekspedisi','$layananku','$berat','$ongkir','ya','$kodepos','$invoice','$total',NOW())";
            $sql = mysqli_query( $koneksi, $query);  
        echo "<script>alert('data berhasil ditambah');</script>";
		echo "<script>location='detailorderdb.php?id=$invoice'</script>";
	} 
?>	
</html>
