<?php 
session_start();

include 'koneksi.php'; 
?>
<!-- Header -->
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Sistem Informasi</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
<nav class="navbar navbar-inverse navbar-fixed-top">
  
   <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      
      
    </div>

   
  </div><!-- /.container-fluid -->
   
</nav>

<!-- akhir Header -->




		    <div class="col-sm-4 col-xs-1"></div>
			<div class="col-sm-4 col-xs-10">
				<div>
					
					<p>masukan nama mitra distributor anda</p>
				</div>
				 <div>
					<form method="post">
						<div >
							<select name="kodemitra">
							    <option></option>
							    <?php $data=$koneksi->query("SELECT * FROM admin_mitra"); 
							    while($tampilkan=$data->fetch_assoc()){
							    ?>
							    <option value="<?php echo $tampilkan['kodemitra']; ?>"><?php echo $tampilkan['namamitra']; ?></option>
							    <?php } ?>
							</select>
						</div>
						<div >
							<label>username</label><br>
							<input type="text" name="username">
						</div>
						<br>
						
						<div >
							<label>Password</label><br>
							<input type="text" name="password">
						</div>
						<br>
						<button class="btn btn-info" name="login">login</button>
					</form>


					<?php 
					// jika ada tombol login di eksekusi
					if (isset($_POST["login"])) {
						
						$kodemitra=$_POST["kodemitra"];
						$username=$_POST["username"];
                        $password=$_POST["password"];
						$ambil=$koneksi->query("SELECT * FROM mitra_agen WHERE kodemitra='$kodemitra'");

						$akunyangcocok=$ambil->num_rows;

						if($akunyangcocok==1){
							// anda sudah login
							$akun=$ambil->fetch_assoc();
							//setelah di arraykan maka disimpan di sesson
							$_SESSION["mitra_agen"] = $akun;

							echo "<div class='alert alert-info'>login sukses</div>";
							//jika sudah ada sesion keranjang maka db akan dilarikan ke riwayat 
							if(isset($_SESSION["keranjang"]) OR !empty($_SESSION["keranjang"])){
							echo "<script>location='../store/riwayat.php';</script>";

							}else{
							    
								echo "<script>location='index.php';</script>";

							}
						}else{
							//anda gagal login
							echo "<script>alert('anda gagal login ');</script>";
							echo "<script>location='login.php';</script>";
						}
					}
					?>

				  </div>
			</div>
		
	