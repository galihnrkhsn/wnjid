<?php 
session_start();

include 'koneksi.php';
// include '../distributor/cek_keterangan.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login Mitra Reseller</title>
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
						<img src="style/loginagen.png" alt="wanoja" width="90%">
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
					session_start();
					if (isset($_POST['login'])) {
						$email = $_POST['email'];
						$password = $_POST['pass'];
						$ambil_user = $koneksi->query("SELECT * FROM users WHERE email = '$email'");
						$user_data = $ambil_user->fetch_assoc();

						if (password_verify($password, $user_data['password'])) {
							$user_level = $user_data['role'];
							// if ($user_level == "distributor") {
								
							// } elseif ($user_level == "agen") {
									
							// } elseif ($user_level == "marketer") {
								
							// } elseif ($user_level == "reseller") {
							if ($user_level == "reseller") {
								echo "<script>alert('Login Berhasil!')</script>";
								echo "<script>location='index3.php'</script>";

								$_SESSION['user_id'] = $user_data['id'];
								$_SESSION['user_level'] = $user_level;
								$idmitrareseller = $user_data['id'];

								$query_reseller = $koneksi->query("SELECT idmitrareseller FROM mitrareseller WHERE iduser = '$idmitrareseller'");
								$reseller_data = $query_reseller->fetch_assoc();
								$_SESSION['idmitrareseller'] = $reseller_data['idmitrareseller'];
							} else {
								echo "<script>alert('You don't have permission!')</script>";
							}
						} else {
							echo "<script>alert('Login gagal!')</script>";
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