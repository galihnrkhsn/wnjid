     <?php
      $idmitraagen=$_SESSION["idmitraagen"];
       $ambil=$koneksi->query("SELECT mode FROM mitraagen where idmitraagen='$idmitraagen' "); 
      $mode=$ambil->fetch_assoc();
          ?>
 <!-- FOOTER 2 -->         
 <script src="https://kit.fontawesome.com/b812ba9ae3.js" crossorigin="anonymous"></script> 
<div class="container row fixed-bottom jumbotron3" >
  <div class="col-20 "><a href="index3"><i class="fa fa-home fa-lg "></i><p class="text-center">Home</p></a></div>
  <div class="col-20"><a href="wanoja-link.php"><i class="fa fa-info-circle fa-lg "></i><p class="text-center">W-Info</p></a></div>
  <div class="col-20"><a href="elearning.php"><i class="fa fa-leanpub fa-lg"></i><p class="text-center">Tutorial</p></a></div>
  <div class="col-20"><a href="setting.php"><i class="fa fa-cog fa-lg"></i> <p class="text-center">Setting</p></a></div>
  <div class="col-20"><a href="profile.php"><i class="fa fa-user-circle fa-lg"></i><p class="text-center">Profil</p></a></div>
</div>	
<style>
.jumbotron3 {
    background: #b8c6db;
   center center;
    margin: auto;
    text-align: center;
    overflow: hidden;
    padding: 10px 0px 0px 0px;
}

.jumbotron3 p {
  text-decoration: none;
  padding: 0px 0px 0px 0px;
  text-align: center;  
}

.jumbotron3 a{
  color: black;
}

.jumbotron3 a:hover{
  color: #7e7fe5;
}


[class*="col-"] {
  
}


.col-20 {width: 20%;color: #337ab7;}
</style>