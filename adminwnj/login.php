<?php 
session_start();

include 'koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login Admin Pusat</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block"><img src="img/logownj.jpg" width="500px"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Hello Selamat Bekerja!</h1>
                  </div>
                  <form class="user" method="post">
                    <div class="form-group">
                      <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                    </div>
                    <div class="form-group">
                      <input type="password" name="pass" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>
                    <div class="form-group">
                    </div>
                    	<button class="btn btn-primary" name="login">
							Login
						</button>
                    <hr>
                  </form>
                  
                  	<?php 
					// jika ada tombol login di eksekusi
					if (isset($_POST["login"])) {
						
						$email = $_POST["email"];
						$pass = $_POST["pass"];

            // echo "<script>alert('$pass ');</script>";
            $ambilUser = $koneksi->query("SELECT * FROM users WHERE email='$email'");
						$userData = $ambilUser->fetch_assoc();

						$verify = password_verify($pass, $userData['password']);

						// $ambil=$koneksi->query("SELECT * FROM administrator WHERE email='$email' ");
            
						// $akunyangcocok=$ambil->num_rows;

						if($verify==1){
							// anda sudah login
							$akun = $userData;
							// role dari tabel users dibawa ke session administrator - dipakai
							// access_guard.php utk membatasi akses role 'creative' cuma ke Foto Produk.
							//setelah di arraykan maka disimpan di sesson
							$_SESSION["administrator"] = $akun;

							echo "<div class='alert alert-info'>login sukses</div>";
							//jika sudah ada sesion keranjang maka db akan dilarikan ke riwayat
							if(isset($_SESSION["keranjang"]) OR !empty($_SESSION["keranjang"])){
							echo "<script>location='../store/riwayat.php';</script>";

							}else if($akun['role'] === 'creative'){
								echo "<script>location='foto_produk.php';</script>";

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
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
