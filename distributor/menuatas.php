    <style>
/* Place the navbar at the bottom of the page, and make it stick */

.jumbotron3 {
    background: #f2f2f2;
   center center;
    margin: auto;
  text-align: center;
    overflow: hidden;
    padding: 10px 10px 10px 10px;
}

.jumbotron3 p {
  text-decoration: none;
  padding: 10px 10px 10px 10px;
  
   
   text-align: center;
   
}



[class*="col-"] {
  
}


.col-20 {width: 20%;}



</style> 
 <?php 
     $mitra= $_SESSION['admin_mitra']['idadmin'];

     $ambil=$koneksi->query("SELECT admin_mitra.namamitra,admin_mitra.foto,(sum(saldo.debit) - sum(saldo.credit)) AS selisih FROM admin_mitra inner join saldo on admin_mitra.idadmin=saldo.idadmin where saldo.idadmin= '$mitra'"); 
     while($data=$ambil->fetch_assoc()){
      ?>
      
 <!-- MENU ATAS 2 -->         
<div class="container row fixed-top jumbotron3" >
    <div class="col-2 "><a href="logout.php"><i class="fa fa-power-off fa-lg "></i></a></div>
  <div class="col-8 ">Assalamualaikum, <br><strong><?php echo $data['namamitra'] ?></strong></div>
  <div class="col-2"><a href="logout.php"><i class="fa fa-shopping-cart fa-lg"></i></a></div>
 
  
</div>

 <!-- MENU ATAS 2 END --> 
 <?php } ?>



