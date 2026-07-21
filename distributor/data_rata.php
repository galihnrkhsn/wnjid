<?php 
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php';
if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}
                    $idpoproduk=$_GET['id'];
                      $idadmin=$_SESSION["admin_mitra"]["idadmin"];
?>
<title>Mitra <?php echo $_SESSION['admin_mitra']['namamitra']; ?>| Wanoja </title>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

		
<style>
.navbaru {
   
    background: #eee  center center;
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
  
  padding: 15px 0;
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
	
	</head>
	<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="detailpo.php?id=<?= $idpoproduk ?>"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<!--================ NAVBARU END =================-->


<div class="container" align="center">
<br><br>

			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>Nama PO</th>											
						<th>Nama Mitra</th>
						<th>Variant</th>
					</tr>
					<?php


					$sql = mysqli_query($koneksi, "SELECT admin_mitra.namamitra,
                                      poproduk.namapo,
                                      podetail.variant

                                      FROM `pomitra` 
                                      inner join poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
                                      join podetail on pomitra.idpodetail = podetail.idpodetail
						                          left join admin_mitra on admin_mitra.idadmin=pomitra.idmitra
						                          where poproduk.idpoproduk='$idpoproduk' 
						                          and pomitra.idmitra = '$idadmin'
                                      GROUP BY pomitra.invoice
                                      ORDER BY admin_mitra.namamitra
                                      ");
					while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
					?>
						<tr>
							
              <td class="align-middle"><?php echo $data['namapo']; ?></td>
              <td class="align-middle"><?php echo $data['namamitra']; ?></td>
              <td class="align-middle"><?php echo $data['variant']; ?></td>
							

						</tr>
					<?php }	?>
				</table>
			</div>

			

	</body>
</html>


