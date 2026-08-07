<?php
   session_start();
   include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>WNJ.id</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" href="home/assets/css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" href="home/assets/css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="home/assets/css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="home/assets/images/fevicon.png" type="image/gif" />
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="home/assets/css/jquery.mCustomScrollbar.min.css">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

      <style>
         .form-control {
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
         }

         .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
         }
      </style>
   </head>
   <!-- body -->
   <body class="main-layout">
      <!-- loader  -->
      <div class="loader_bg">
         <div class="loader"><img src="home/assets/images/loading.gif" alt="#"/></div>
      </div>
      <!-- end loader -->
      <!-- header -->
      <header>
         <!-- header inner -->
         <div class="header">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                     <div class="full">
                        <div class="center-desk">
                           <div class="logo">
                              <a href="/"><img src="home/assets/images/Logo.png" alt="#" /></a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-7 col-lg-7 col-md-9 col-sm-9">
                     <nav class="navigation navbar navbar-expand-md navbar-dark ">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarsExample04">
                           <ul class="navbar-nav mr-auto">
                              <li class="nav-item active">
                                 <a class="nav-link" href="index.html">Home</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link" href="about.html">Kemitraan</a>
                              </li>
                              <li class="nav-item">
                                 <a class="nav-link" href="about.html">Katalog</a>
                              </li>
                              <li class="nav-item">
                                 <a href="#" class="nav-link" data-toggle="modal" data-target="#exampleModal">
                                    Login
                                 </a>
                              </li>
                           </ul>
                        </div>
                     </nav>
                  </div>
                  <div class="col-md-2">
                     <ul class="social_icon">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </header>
      <!-- end header inner -->
      <!-- end header -->
      <!-- banner -->
      <section class="banner_main">
         <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-body">
                     <h2 class="font-weight-bold text-uppercase text-center">Login</h2>
                     <form method="post">
                        <div class="form-group mb-2">
                           <label for="email" class="mb-0">Email</label>
                           <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                        </div>
                        <div class="form-group mb-2">
                           <label for="password" class="mb-0">Password</label>
                           <div class="input-group">
                                 <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                                 <span class="input-group-text toggle-password" onclick="togglePasswordVisibility()">
                                    <i id="eye-icon" class="bi bi-eye-slash"></i>
                                 </span>
                           </div>
                        </div>
                        <div class="text-center mt-3">
                           <button class="btn btn-primary" name="login">Login</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <div id="myCarousel" class="carousel slide banner1" data-ride="carousel">
            <ol class="carousel-indicators">
               <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
               <li data-target="#myCarousel" data-slide-to="1"></li>
            </ol>
            <div class="carousel-inner">
               <div class="carousel-item active">
                  <div class="container-fluid">
                     <div class="carousel-caption relative">
                        <div class="row d_flex">
                           <div class="col-md-12">
                              <img class="bann_img" src="home/assets/images/banner.png" loading="lazy" alt="#"/>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="carousel-item">
                  <div class="container-fluid">
                     <div class="carousel-caption relative">
                        <div class="row d_flex">
                           <div class="col-md-12">
                              <img class="bann_img" src="home/assets/images/banner-2.png" loading="lazy" alt="#"/>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- end banner -->
      <!-- our partnership -->
      <div  class="slin2 my-5 py-5">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>Kemitraan</h2>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-6 col-md-3">
                  <div class="experts_box">
                     <div class="experts_img">
                        <figure><img src="/home/assets/images/distributor.jpg"></figure>
                     </div>
                  </div>
               </div>
               <div class="col-6 col-md-3">
                  <div class="experts_box">
                     <div class="experts_img">
                        <figure><img src="/home/assets/images/agen.jpg"></figure>
                     </div>
                  </div>
               </div>
               <div class="col-6 col-md-3">
                  <div class="experts_box">
                     <div class="experts_img">
                        <figure><img src="/home/assets/images/reseller.jpg"></figure>
                     </div>
                  </div>
               </div>
               <div class="col-6 col-md-3">
                  <div class="experts_box">
                     <div class="experts_img">
                        <figure><img src="/home/assets/images/marketer.jpg"></figure>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end our partnership -->
      <!--  footer -->
      <footer>
         <div class="footer">
            <div class="container pb-5">
               <div class="row">
                  <div class="col-sm-6">
                     <ul class="conta">
                        <!-- <li><i class="fa fa-map-marker" aria-hidden="true"></i> Passages of Lorem Ipsum available</li> -->
                        <!-- <li><i class="fa fa-phone" aria-hidden="true"></i> Call : +</li> -->
                     </ul>
                  </div>
                  <div class="col-sm-6">
                     <ul class="conta">
                        <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="#"> wanojahijab@gmail.com</a></li>
                     </ul>
                  </div>
               </div>
            </div>
            <!-- <div class="copyright">
               <div class="container">
                  <div class="row">
                     <div class="col-md-10 offset-md-1">
                        <p>© 2019 All Rights Reserved. Design by  <a href="https://html.design/"> Free Html Templates</a> Distributed By <a href="https://themewagon.com">ThemeWagon</a></p>
                     </div>
                  </div>
               </div>
            </div> -->
         </div>
      </footer>

      <?php
         if(isset($_POST['login'])) {
            // $koneksi->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
            try {
               $email = $_POST['email'];
               $pass = $_POST['password'];

               $user = $koneksi->query("SELECT * FROM users WHERE email = '$email'");
               $data = $user->fetch_assoc();

               if (password_verify($pass, $data['password'])) {
                  $role = $data['role'];
                  $id = $data['id'];

                  $_SESSION["user_id"] = $id;
                  $_SESSION["user_level"] = $role;

                  if ($role == "distributor") {
                        $queryDistributor = $koneksi->query("SELECT idadmin FROM admin_mitra WHERE iduser = '$id'");
                        $distributorData = $queryDistributor->fetch_assoc();
                        $_SESSION["idadmin"] = $distributorData["idadmin"];
                        echo "<script>alert('Login berhasil!')</script>";
                        echo "<script>location='../distributor/index2.php'</script>";
                  } elseif ($role == "agen") {
                        $query_agen = $koneksi->query("SELECT idmitraagen FROM mitraagen WHERE iduser = '$id'");
                        $agen_data = $query_agen->fetch_assoc();
                        $_SESSION["idmitraagen"] = $agen_data["idmitraagen"];
                        echo "<script>alert('Login berhasil!')</script>";
                        echo "<script>location='../agen/index3.php'</script>";
                  } elseif ($role == "reseller") {
                        $query_agen = $koneksi->query("SELECT idmitrareseller FROM mitrareseller WHERE iduser = '$id'");
                        $agen_data = $query_agen->fetch_assoc();
                        $_SESSION["idmitrareseller"] = $agen_data["idmitrareseller"];
                        echo "<script>alert('Login berhasil!')</script>";
                        echo "<script>location='../reseller/index3.php'</script>";
                        echo "Berhasil! " . $role;
                  } elseif ($role == "marketer") {
                        $query_agen = $koneksi->query("SELECT idmitramarketer FROM mitramarketer WHERE iduser = '$id'");
                        $agen_data = $query_agen->fetch_assoc();
                        $_SESSION["idmitramarketer"] = $agen_data["idmitramarketer"];
                        echo "<script>alert('Login berhasil!')</script>";
                        echo "<script>location='../marketer/index2.php'</script>";
                        echo "Berhasil! " . $role;
                  } else {
                        echo "Login gagal!";
                  }
               } else {
                  $management = $koneksi->query("SELECT * FROM management WHERE email = '$email'");
                  $managementData = $management->fetch_assoc();
   
                  if ($managementData && password_verify($pass, $managementData['password'])) {
                        $tipe = $managementData['tipe'];
                        $id = $managementData['id'];
   
                        $_SESSION["user_id"] = $id;
                        $_SESSION["user_tipe"] = $tipe;
   
                        echo "<script>alert('Login berhasil!')</script>";
                        if ($tipe == "M") {
                           $_SESSION["idmanage"] = $managementData["id"];
                           echo "<script>location='../manajemen/index'</script>";
                        } elseif ($tipe == "P") {
                           $_SESSION["idmanage"] = $managementData["id"];
                           echo "<script>location='../produksi/index'</script>";
                        }
                  } else {
                        echo "<script>alert('Login gagal!')</script>";
                        echo "<script>location='login-multi'</script>";
                  }
               }
            } catch (mysqli_sql_exception $e) {
               echo "Error: " . $e->getMessage();
            }
         }
      ?>

      <script>
         function togglePasswordVisibility() {
               var passwordInput = document.getElementById("password");
               var eyeIcon = document.getElementById("eye-icon");

               if (passwordInput.type === "password") {
                  passwordInput.type = "text";
                  eyeIcon.classList.remove("bi-eye-slash");
                  eyeIcon.classList.add("bi-eye");
               } else {
                  passwordInput.type = "password";
                  eyeIcon.classList.remove("bi-eye");
                  eyeIcon.classList.add("bi-eye-slash");
               }
         }
      </script>
      <!-- end footer -->
      <!-- Javascript files-->
      <script src="home/assets/js/jquery.min.js"></script>
      <script src="home/assets/js/bootstrap.bundle.min.js"></script>
      <script src="home/assets/js/jquery-3.0.0.min.js"></script>
      <!-- sidebar -->
      <script src="home/assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="home/assets/js/custom.js"></script>
   </body>
</html>