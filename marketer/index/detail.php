<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitra_agen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

?>
<?php $idproduk = $_GET['id']; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Mitra | WNJ.ID Hijab</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">


    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">
    
  </head>
  <body>
  
  <div class="site-wrap">
    <header class="site-navbar" role="banner">
      <div class="site-navbar-top">
        <div class="container">
          <div class="row align-items-center">

            <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-left">
              <form action="" class="site-block-top-search">
                <span class="icon icon-search2"></span>
                <input type="text" class="form-control border-0" placeholder="Search">
              </form>
            </div>

            <div class="col-12 mb-3 mb-md-0 col-md-4 order-1 order-md-2 text-center">
              <div class="site-logo">
                <a href="index.php" class="js-logo-clone">wanoja hijab</a>
              </div>
            </div>

            <div class="col-6 col-md-4 order-3 order-md-3 text-right">
              <div class="site-top-icons">
                <ul>
                  <li><a href="#"><span class="icon icon-person"></span></a></li>
                  <li><a href="logout.php"><span class="icon icon-sign-out"></span></a></li>
                   
                  <li class="d-inline-block d-md-none ml-md-0"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a></li>
                </ul>
              </div> 
            </div>

          </div>
        </div>
      </div> 
      <nav class="site-navigation text-right text-md-center" role="navigation">
        <div class="container">
          <ul class="site-menu js-clone-nav d-none d-md-block">
            <li class="has-children active">
              <a href="index.php">Home</a>
              
            </li>
            <li class="has-children">
              <a href="">katalog</a>
              <ul class="dropdown">
                <li><a href="#">Menu One</a></li>
                <li><a href="#">Menu Two</a></li>
                <li><a href="#">Menu Three</a></li>
              </ul>
            </li>
           
            
            <li><a href="">Contact</a></li>
          </ul>
        </div>
      </nav>
    </header>
 <?php $ambil=$koneksi->query("SELECT * FROM produk WHERE idproduk='$_GET[id]'"); ?>
                    <?php $produk=$ambil->fetch_assoc();?>
    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="index.php">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black"><?php echo $produk['namaproduk']; ?></strong></div>
        </div>
      </div>
    </div>  
 <?php $ambil=$koneksi->query("SELECT * FROM produk WHERE idproduk='$_GET[id]'"); ?>
                    <?php $produk=$ambil->fetch_assoc();?>
    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <img src="../produk/<?php echo $produk["fotoproduk"]; ?>" alt="Image" class="img-fluid">
          </div>
          <div class="col-md-6">
            <h2 class="text-black"><?php echo $produk['namaproduk']; ?></h2>
            <p><?php echo $produk['deskripsiproduk']; ?></p>
            <p class="mb-4"><?php echo $produk['deskripsiproduk']; ?></p>
            
            
            
            <p><a href="form-pesanan.php?id=<?php echo $produk['idproduk']; ?>&idd=<?php echo $produk['namaproduk']; ?>" class="buy-now btn btn-sm btn-primary">Kirim Pesanan</a></p>

          </div>
        </div>
      </div>
    </div>

   
   <footer class="site-footer border-top">
      <div class="container">
        <div class="row">
          
          
         
          
          <div class="col-md-6 col-lg-3">
            <div class="block-5 mb-5">
              <h3 class="footer-heading mb-4">Contact Info</h3>
              <ul class="list-unstyled">
                <li class="address">lorem ipsum</li>
                <li class="phone"><a href="">022xxxx</a></li>
                <li class="email">lorem ipsum</li>
              </ul>
            </div>

           
            
          </div>
        </div>
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <p>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" class="text-primary">Colorlib</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
          </div>
          
        </div>
      </div>
    </footer>
  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>

  <script src="js/main.js"></script>
    
  </body>
</html>