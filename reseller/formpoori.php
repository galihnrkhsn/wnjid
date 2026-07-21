<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login2.php';</script>";
   header('location:login2.php');
   exit();
}

  $idpoproduk = $_GET['id'];
  $invoice = $_GET['invoice'];
  $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"];
  $query = "SELECT COUNT(*) as jumlah,poproduk.idpoproduk,poproduk.namapo,poproduk.status FROM poproduk inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk WHERE poproduk.idpoproduk='$idpoproduk' AND pomitra.idmitrareseller='$idmitrareseller'";
  $sql = mysqli_query($koneksi, $query);  
  $data = mysqli_fetch_array($sql);
  ?> 
  
<html lang="en">
<head>
<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
        	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
	<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">  

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.js"></script>
	<script type="text/javascript" src="admin/assets/DataTables/media/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="admin/assets/DataTables/media/css/dataTables.bootstrap.css">
		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><!-- <a href="listpreorder.php"><i class="glyphicon glyphicon-chevron-left"></i></a> --></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
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

<!--================ NAVBARU END =================-->
   <div class="container"> 
   <table id="myJudul" class="w3-table-all w3-centered">
<?php        
$namapo=$data['namapo'];
 $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"]; 
 $id=$data['idpoproduk'];

echo "<center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";

?>
  </table>
  </div><br><br>
  
    <div class="container panel panel-default">
     
 
            <div class="panel-body">
            <div class="row">
            <div class="col-md-12">
                                
		<form method="POST">
		    
		<div style="padding: 0 15px;">
		    
            <ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#ga">Gamis Anak</a></li>
			<!-- <li><a data-toggle="tab" href="#ba">Bergo Anak</a></li>
			<li><a data-toggle="tab" href="#sa">Set Anak</a></li> -->
			<li><a data-toggle="tab" href="#gd">Gamis Dewasa</a></li>
			<!-- <li><a data-toggle="tab" href="#bd">Bergo Dewasa</a></li> -->
			<!-- <li><a data-toggle="tab" href="#sds">Set Dewasa Bergo S</a></li>
			<li><a data-toggle="tab" href="#sdm">Set Dewasa Bergo M</a></li>
			<li><a data-toggle="tab" href="#sdl">Set Dewasa Bergo L</a></li>
			<li><a data-toggle="tab" href="#sdx">Set Dewasa Bergo XL</a></li>
			<li><a data-toggle="tab" href="#sdj">Set Dewasa Bergo JMB</a></li> -->
			<li><a data-toggle="tab" href="#khi">Khimar Anak & Dewasa</a></li>
			<li><a data-toggle="tab" href="#ka1">Koko Pendek Anak</a></li>
			<li><a data-toggle="tab" href="#kd1">Koko Pendek Dewasa</a></li>
			<li><a data-toggle="tab" href="#ka2">Koko Panjang Anak</a></li>
			<li><a data-toggle="tab" href="#kd2">Koko Panjang Dewasa</a></li>
			</ul>
 
  <div class="tab-content">
			<div id="ga" class="tab-pane fade in active">
				<?php
						//initialize total
						  $idpoproduk = $_GET['id'];
						$total = 0;
						$index = 0;
						$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail desc limit 270,54";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
				
				<p>	<form method="POST">	
					<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="text" name="jmlh[]" class="form-control" style="width:300px;" value=0>
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
								</div>	
								
				                <?php $namapo2=$row['namapo'];
				                $id=$row['idpoproduk'];
				                      $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"]; 
				                      } ?>
				        
                    	</p>
			</div>
			
	<div id="gd" class="tab-pane fade">
				<?php
						//initialize total
						  $idpoproduk = $_GET['id'];
						$total = 0;
						$index = 0;
						$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail desc limit 324,54";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
				
					
								
					<p>			<form method="POST">
								<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="text" name="jmlh[]" class="form-control" style="width:300px;" value=0>
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
								</div>	
								
				                <?php $namapo2=$row['namapo'];
				                $id=$row['idpoproduk'];
				                      $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"]; 
				                      } ?>
				                      		        
                    </p>
			</div>
<!----------------------------------------------------------------------------------------------------------------------------->
<div id="khi" class="tab-pane fade">
				<?php
						//initialize total
						  $idpoproduk = $_GET['id'];
						$total = 0;
						$index = 0;
						$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail desc limit 54";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
				
					
								
					<p>			<form method="POST">
								<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="text" name="jmlh[]" class="form-control" style="width:300px;" value=0>
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
								</div>	
								
				                <?php $namapo2=$row['namapo'];
				                $id=$row['idpoproduk'];
				                      $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"]; 
				                      } ?>
				                      		        
                    </p>
			</div>

			<!----------------------------------------------------------------------------------------------------------------------------->
<div id="ka2" class="tab-pane fade">
				<?php
						//initialize total
						  $idpoproduk = $_GET['id'];
						$total = 0;
						$index = 0;
						$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail desc limit 54,54";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
				
					
								
					<p>			<form method="POST">
								<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="text" name="jmlh[]" class="form-control" style="width:300px;" value=0>
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
								</div>	
								
				                <?php $namapo2=$row['namapo'];
				                $id=$row['idpoproduk'];
				                      $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"]; 
				                      } ?>
				                      		        
                    </p>
			</div>

			<!----------------------------------------------------------------------------------------------------------------------------->
<div id="ka1" class="tab-pane fade">
				<?php
						//initialize total
						  $idpoproduk = $_GET['id'];
						$total = 0;
						$index = 0;
						$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail desc limit 108,54";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
				
					
								
					<p>			<form method="POST">
								<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="text" name="jmlh[]" class="form-control" style="width:300px;" value=0>
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
								</div>	
								
				                <?php $namapo2=$row['namapo'];
				                $id=$row['idpoproduk'];
				                      $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"]; 
				                      } ?>
				                      		        
                    </p>
			</div>

				<!----------------------------------------------------------------------------------------------------------------------------->
<div id="kd1" class="tab-pane fade">
				<?php
						//initialize total
						  $idpoproduk = $_GET['id'];
						$total = 0;
						$index = 0;
						$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail desc limit 162,54";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
				
					
								
					<p>			<form method="POST">
								<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="text" name="jmlh[]" class="form-control" style="width:300px;" value=0>
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
								</div>	
								
				                <?php $namapo2=$row['namapo'];
				                $id=$row['idpoproduk'];
				                      $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"]; 
				                      } ?>
				                      		        
                    </p>
			</div>

			<!----------------------------------------------------------------------------------------------------------------------------->

			<div id="kd2" class="tab-pane fade">
				<?php
						//initialize total
						  $idpoproduk = $_GET['id'];
						$total = 0;
						$index = 0;
						$sql = "SELECT * FROM poproduk inner join pokategori inner join podetail on poproduk.idpoproduk=pokategori.idpoproduk and pokategori.idpo=podetail.idpo where poproduk.idpoproduk='$idpoproduk' order by podetail.idpodetail desc limit 216,54";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
				
					
								
					<p>			<form method="POST">
								<div class="form-group">
								    <label><?php echo $row['variant']; ?></label>
								    <input type="hidden" name="idpo[]" value="<?php echo $row['idpo']; ?>">
								    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
								    <input type="text" name="jmlh[]" class="form-control" style="width:300px;" value=0>
								    <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
								</div>	
								
				                <?php $namapo2=$row['namapo'];
				                $id=$row['idpoproduk'];
				                      $idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"]; 
				                      } ?>
				                      		        
                    </p>
			</div>

			<!----------------------------------------------------------------------------------------------------------------------------->


			</div>
				  
			        <?php echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>"; ?>
                    	</form>
			
                    	
						<?php
						if(isset($_POST["save"])){
						include "koneksi.php";
						date_default_timezone_set('Asia/Jakarta');
						$today = date("s");
						$idmitrareseller=$_SESSION["mitraagen"]["idmitrareseller"];
						$idpo= $_POST["idpo"];
						$idpodetail=$_POST["idpodetail"];
						$jmlh=$_POST["jmlh"];
						$harga=$_POST["harga"];
			                         
						$jumlah_dipilih = count($jmlh);
						$subtotal=0;  
						$total=0;
						$jmlhakhir=0;
                                    
							for($x=0;$x<$jumlah_dipilih;$x++){
							$total=$jmlh[$x]*$harga[$x];
							
							$tot=$total;
							$jmlhakhir+=$jmlhakhir+$jmlh[$x];
							$tot=0;
							

							$koneksi->query("INSERT INTO pomitra (idpomitra,idmitrareseller,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl) VALUES
							(null,'$idmitrareseller','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total',
							'$invoice','Belum Acc DB',NOW())");
							
							echo "<script>alert('data berhasil dikirim');</script>";
							echo "<script>location='datapokonin.php?invoice=$invoice&id=$idpoproduk';</script>";
							

							}
												}        
					                    
					                    ?>
					                    

		</div>
	</div>
</div>	</div>
</div>