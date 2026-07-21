     <?php
      $idadmin=$_SESSION["admin_mitra"]["idadmin"];
       $ambil=$koneksi->query("SELECT mode FROM admin_mitra where idadmin='$idadmin' "); 
      $mode=$ambil->fetch_assoc();
          ?>
 <!-- FOOTER 2 -->         
<div class="container row fixed-bottom jumbotron3" >
  <div class="col-20 "><a href="index.php"><i class="fa fa-home fa-lg "></i><p class="text-center">Home</p></a></div>
  <div class="col-20"><a href="wanoja-link.php"><i class="fa fa-info-circle fa-lg "></i><p class="text-center">W-Info</p></a></div>
  <div class="col-20"><a href="elearning.php"><i class="fab fa-leanpub fa-lg"></i><p class="text-center">Tutorial</p></a></div>
  <div class="col-20"><a href="setting.php"><i class="fa fa-cog fa-lg"></i> <p class="text-center">Setting</p></a></div>
  <div class="col-20"><a href="profile.php"><i class="fa fa-user-circle fa-lg"></i><p class="text-center">Profil</p></a></div>
</div>		
 <!-- FOOTER 2 END --> 
 


<style>
/* Place the navbar at the bottom of the page, and make it stick */

.jumbotron3 {
    background: #f2f2f2;
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


[class*="col-"] {
  
}


.col-20 {width: 20%;}






</style>