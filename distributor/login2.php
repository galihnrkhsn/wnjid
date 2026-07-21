<?php 
session_start();

include 'koneksi.php'; 
include 'cek_keterangan.php';
include 'assets/components/Sessions/sesDistri.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<meta property="og:image:alt" content="A shiny red apple with a bite taken out" />

	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="style/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="style/fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="style/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="style/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="style/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="style/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="style/vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="style/css/util.css">
	<link rel="stylesheet" type="text/css" href="style/css/main.css">
	<script type="text/javascript">
        // Function to show an alert and redirect to login page
        function showAlertWithRedirect() {
            if (confirm("Silahkan login terlebih dahulu")) {
                // Redirect to login page
                window.location.href = "https://wnj.id/";
            }
        }

        // Call the function immediately after the page loads
        window.onload = function() {
            showAlertWithRedirect();
        }
    </script>
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
				session_start();
					if (isset($_POST["login"])) {
						$email = $_POST["email"];
						$pass = $_POST["pass"];
						$ambilUser = $koneksi->query("SELECT * FROM users WHERE email='$email'");
						$userData = $ambilUser->fetch_assoc();
						if (password_verify($pass, $userData['password'])) {
							$userLevel = $userData['role'];
							switch ($userLevel) {
								case 'marketer':
									$dashboardFile = "../dashboard/marketer/dashboard_marketer.php";
									break;
								case 'distributor':
									$dashboardFile = "index2";
									break;
								case 'reseller':
									$dashboardFile = "../reseller/listpreorder.php";
									break;
								default:
									echo "<div class='alert alert-danger'>Email atau Password salah</div>";
									exit();
							}
							$_SESSION["user_id"] = $userData["id"];
							$_SESSION["user_level"] = $userLevel;
							
							if ($userLevel == 'distributor') {
								$queryDistributor = $koneksi->query("SELECT idadmin FROM admin_mitra WHERE iduser=" . $userData["id"]);
								$distributorData = $queryDistributor->fetch_assoc();
								$_SESSION["idadmin"] = $distributorData["idadmin"];
							}
							echo "<div class='alert alert-info'>Login sukses</div>";
							echo "<script>location='$dashboardFile';</script>"; // Arahkan ke file dashboard yang sesuai
						} else {
							echo "<script>alert('Login gagal');</script>";
							echo "<script>location='login2.php';</script>";
						}
					}
					?>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="style/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="style/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="style/vendor/bootstrap/js/popper.js"></script>
	<script src="style/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="style/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="style/vendor/daterangepicker/moment.min.js"></script>
	<script src="style/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="style/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="style/js/main.js"></script>

</body>
</html>