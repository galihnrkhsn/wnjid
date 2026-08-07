<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

  $idpo = $_GET['idpo'];

  $query = "SELECT * FROM pokategori WHERE idpo='$idpo'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
  ?>
  
<html lang="en">
<head>
	<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| WNJ.ID </title>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
	<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="index.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>MY STOCK</p></div>
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

</style>

<!--================ NAVBARU END =================--><br><br>

  <div class="panel panel-default">
   
            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
                                
		
		<div style="padding: 0 15px;">
            	<?php echo $data['namapo']; ?> <br><br>
            	
					<?php
						//initialize total
						  $idpo = $_GET['idpo'];
					//	$total = 0;
					//	$index = 0;
								?>
								
						<form method="POST">
						        <?php			
						        $idpo=$_GET["idpo"];
						        $idpomitra=$_GET["idpomitra"];
						        $jmlhid=count($idpomitra);
				                for($x=0;$x<$jmlhid;$x++){
								$sql2 = "SELECT podetail.variant,podetail.harga,pokategori.idpo,pomitra.jumlah,pomitra.idpomitra FROM pomitra inner join 
								podetail inner join pokategori on pomitra.idpodetail=podetail.idpodetail and pomitra.idpo=pokategori.idpo WHERE pomitra.idpomitra='$idpomitra'";
						        $query2 = $koneksi->query($sql2);
							    while($po = $query2->fetch_assoc()){
								?>
								<div class="form-group">
								    <label><?php echo $po['variant']; ?></label>
								    <input type="hidden" name="idpomitra" value="<?php echo $po['idpomitra']; ?>">
								    <input type="text" name="jmlh" class="form-control" style="width:80px;" value="<?php echo $po['jumlah'];?>">
								    <input type="hidden" name="harga" value="<?php echo $po['harga']; ?>">
								</div>	
				                <?php }
				                        ?>
                    			<button type="submit" class="btn btn-primary" name="save">Kirim</button>
                    	</form>
                    	<?php } ?>
			                       <?php
			                        if(isset($_POST["save"])){
	                                 include "koneksi.php";
					               $idadmin=$_SESSION["admin_mitra"]["idadmin"];
					               $idpomitra= $_POST['idpomitra'];
					               $jmlh=$_POST["jmlh"];
			                       $harga=$_POST["harga"];    
			                       $total=$jmlh*$harga;
                                    	 //$jmlhtotal=count($total);
                                           // for($x=0;$x<$jmlhtotal;$x++){
                                            //echo number_format($total, 2);
                                              //  $subtotal=$subtotal+$total[$x];
                                              //$tot=$total;
                                              //$tot=0;
        					                   $koneksi->query("UPDATE pomitra set jumlah='$jmlh', total='$total' where idpomitra='$idpomitra'");
        					                   	echo "<script>alert('data berhasil dikirim');</script>";
		                                        echo "<script>location='datapo.php?id=$idpo';</script>";
        					                     }
			                        
					                    ?>
					                    
		</div>
	</div>
</div>
<script src="src/bootstrap-input-spinner.js"></script>
<script>
    $("input[type='number']").inputSpinner()
</script>
