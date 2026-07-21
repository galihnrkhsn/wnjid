<?php 
session_start();
$title = "Login";
include 'template/header.php'; 
?>



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
						
						$email=$_POST["email"];
						$pass = sha1($_POST["pass"]);

            // echo "<script>alert('$pass ');</script>";

						$ambil=$koneksi->query("SELECT * FROM management WHERE email='$email' AND password='$pass' ");

						$akunyangcocok=$ambil->num_rows;

						if($akunyangcocok==1){
							// anda sudah login
							$akun=$ambil->fetch_assoc();
							//setelah di arraykan maka disimpan di sesson
							$_SESSION["management"] = $akun;

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
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>