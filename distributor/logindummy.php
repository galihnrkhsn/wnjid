<?php 
session_start();

include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V6</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/mitra-theme/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="style/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="style/fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/mitra-theme/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../vendor/mitra-theme/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/mitra-theme/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/mitra-theme/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../vendor/mitra-theme/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="style/css/util.css">
	<link rel="stylesheet" type="text/css" href="style/css/main.css">
<!--===============================================================================================-->
<style>
div {
  background-image: url('img/login.png');
}
.login100-form-btn {
	box-shadow: none ;
	}

	.login100-form-btn:hover  {
  background-color: #333333;
  box-shadow: none;
}
</style>
</head>
<body>

<!-- <center>
	<h1>Web Maintenance</h1>
</center> -->

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-t-1 p-b-5">
				<form method="post" class="login100-form validate-form" >
					<span class="login100-form-title ">
						<img src="img/logindb.png" alt="wanoja" width="90%">
					</span><br>

					<div class="wrap-input100 validate-input m-t-5 m-b-5" data-validate = "masukan email">
						<input class="input100" type="text" name="email">
						<span class="focus-input100" data-placeholder="E-mail"></span>
					</div>
<br>
					<div class="wrap-input100 validate-input m-b-15" data-validate="masukan password">
						<input class="input100" type="password" name="pass">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" name="login">
							Login
						</button>
					</div>
                    <span class="login100-form-title ">
						
					</span>
					
				</form>
				<?php 
					// jika ada tombol login di eksekusi
					if (isset($_POST["login"])) {
						
						$email=$_POST["email"];
						$pass = $_POST["pass"];

						$ambil=$koneksi->query("SELECT * FROM admin_mitra WHERE email='$email' AND password='$pass' ");

						$akunyangcocok=$ambil->num_rows;

						if($akunyangcocok==1){
							// anda sudah login
							$akun=$ambil->fetch_assoc();
							//setelah di arraykan maka disimpan di sesson
							$_SESSION["admin_mitra"] = $akun;

							$idadmin=$_SESSION["admin_mitra"]["idadmin"];

							$koneksi->query("INSERT INTO statuslogin (idsession,idadmin) VALUES (null,'$idadmin')");

							$ambilakhir=$koneksi->query("SELECT count(idadmin) as jumlahlogin from statuslogin WHERE idadmin='$idadmin'"); 
     						while($data=$ambilakhir->fetch_assoc()){
	     						if ($data['jumlahlogin']>=3) {
									$koneksi->query("DELETE from statuslogin where idadmin='$idadmin'  ORDER BY idadmin DESC LIMIT 1");
								}
     					}

     					$ambilid=$koneksi->query("SELECT * FROM statuslogin WHERE idadmin='$idadmin' ORDER BY `statuslogin`.`idsession` DESC LIMIT 1");

							$idyangcocok=$ambilid->num_rows;
							$idnya=$ambilid->fetch_assoc();

							$_SESSION["statuslogin"] = $idnya;

							echo "<div class='alert alert-info'>login sukses</div>";
							echo "<script>location='index.php';</script>";
						}else{
							//anda gagal login
							echo "<script>alert('anda gagal login ');</script>";
							echo "<script>location='login2.php';</script>";
						}
					}
					?>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="../vendor/mitra-theme/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/mitra-theme/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/mitra-theme/bootstrap/js/popper.js"></script>
	<script src="../vendor/mitra-theme/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/mitra-theme/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/mitra-theme/daterangepicker/moment.min.js"></script>
	<script src="../vendor/mitra-theme/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/mitra-theme/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="style/js/main.js"></script>

</body>
</html>