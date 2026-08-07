<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| WNJ.ID </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  </head>
  <body>
  <!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="katalog.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>KATALOG</p></div>
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

.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  
  margin: auto;
  text-align: center;
  font-family: arial;
}

.card p2 {
    text-align: justify;
    padding: 0 15px;
}

.price {
  color: grey;
  font-size: 22px;
}

.card button {
  border: none;
  outline: 0;
  padding-top: 12px;
  padding-bottom: 12px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 50%;
  font-size: 18px;
}

.card button:hover {
  opacity: 0.7;
}

.hidden2 {
  visibility: hidden;
}

</style>

<!--================ NAVBARU END =================-->
</table>
        <div class="container">
        <?php
          // Include / load file koneksi.php
          include "koneksi.php";
          $idadmin=$_SESSION['mitraagen']['idadmin'];
          $idkatalog=$_GET['idkatalog'];          
          
          // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
          $sql = mysqli_query($koneksi, "SELECT * from katalog WHERE idkatalog='$idkatalog'");
          
          while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
          ?>    
            
       <div class="card">
   <!--        <img src="../foto/$data[foto]" alt="" style="width:100%"> -->
          <h1><?php echo $data['namaproduk']; ?></h1>
          <p class="price">Rp. <?php echo str_replace("-"," - Rp. ",str_replace(".0000","0.000",str_replace("..000",".000",str_replace("000",".000",str_replace("0000","0.000",$data['harga']))))); ?></p>
          
          <div class="container">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
  
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php if($data['linkfoto2']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='0' class='active'></li>";
        } if($data['linkfoto3']<>''){
       echo "<li data-target='#myCarousel' data-slide-to='1'></li>";
        } if($data['linkfoto4']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='2'></li>";
        } if($data['linkfoto5']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='3'></li>";
        } if($data['linkfoto6']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='4'></li>";
        }if($data['linkfoto7']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='5'></li>";
        }if($data['linkfoto8']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='6'></li>";
        }if($data['linkfoto9']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='7'></li>";
        }if($data['linkfoto10']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='8'></li>";
        }if($data['linkfoto11']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='9'></li>";
        }if($data['linkfoto12']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='10'></li>";
        }if($data['linkfoto13']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='11'></li>";
        }if($data['linkfoto14']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='12'></li>";
        }if($data['linkfoto15']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='13'></li>";
        }if($data['linkfoto16']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='14'></li>";
        }if($data['linkfoto17']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='15'></li>";
        }if($data['linkfoto18']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='16'></li>";
        }if($data['linkfoto19']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='17'></li>";
        }if($data['linkfoto20']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='18'></li>";
        }if($data['linkfoto21']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='19'></li>";
        }if($data['linkfoto22']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='20'></li>";
        }if($data['linkfoto23']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='21'></li>";
        }if($data['linkfoto24']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='22'></li>";
        }if($data['linkfoto25']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='23'></li>";
        }if($data['linkfoto26']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='24'></li>";
        }if($data['linkfoto27']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='25'></li>";
        }if($data['linkfoto28']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='26'></li>";
        }if($data['linkfoto29']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='27'></li>";
        }if($data['linkfoto30']<>''){
      echo "<li data-target='#myCarousel' data-slide-to='28'></li>";
      }
     
    ?>
    </ol> 

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <?php if($data['linkfoto2']<>''){
      echo "<div class='item active'>
       <center> <img src='$data[linkfoto2]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
         if($data['linkfoto3']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto3]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto4']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto4]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto5']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto5]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
         if($data['linkfoto6']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto6]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto7']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto7]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto8']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto8]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto9']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto9]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto10']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto10]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto11']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto11]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto12']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto12]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto13']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto13]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto14']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto14]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto15']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto15]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto16']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto16]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto17']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto17]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto18']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto18]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto19']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto19]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto20']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto20]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto21']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto21]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto22']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto22]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto23']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto23]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto24']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto24]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto25']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto25]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto26']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto26]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }  if($data['linkfoto27']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto27]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto28']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto28]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto29']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto29]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
          if($data['linkfoto30']<>''){
        echo "<div class='item'>
       <center> <img src='$data[linkfoto30]' width='304' height='236' class='img-thumbnail'></center>
      </div>";
        }
     
      ?>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next" style="margin-right: 2.5%;">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>
        <hr>
        <button  class="btn btn-danger" onclick="copyToClipboard('#p1')" 
        style="  
        /*height:15px;*/
        width:200px;
        border-radius:8px;
        padding:10px;
        /*font-size:15px;*/
        font-family: 'Oswald', sans-serif;
        cursor:pointer;
        background-color:#17a2b8;
        margin-left: 1%;
        margin-bottom: 5%;
        ">
          Copy Deskripsi
        </button>        
          <p2><span onclick="copyTeks()" id="p1"><?php echo  nl2br($data['deskripsi']); ?></span></p2>
        </div>

    <div style="padding: 0 15px;">
      </div>
        
    </div>
    <?php } ?>
    
<script type="text/javascript">
        function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
  Swal.fire({         //displays a pop up with sweetalert
                    icon: 'success',
                    title: 'Text disalin ke clipboard',
                    showConfirmButton: false,
                    timer: 1000
                });  
}
    </script>

  </body>
</html>

