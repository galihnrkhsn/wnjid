<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["mitraagen"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

						//initialize total
						$idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"];
					
						$keranjangdb=0;
			
						$sql2 = "SELECT * FROM keranjangdb  WHERE idmarketer='$idmitramarketer' ";
						$query2 = $koneksi->query($sql2);
						while($apaya = $query2->fetch_assoc()){
						    $keranjangdb+=$apaya['jmlh'];
						}
	?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
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

		
		<style>
		.align-middle{
			vertical-align: middle !important;
		}
		
		#myJudul {
    
  text-align: center;
  border-collapse: collapse;
  width: 100%;
  font-size: 18px;
  
  
}
#myJudul th  {
  text-align: center;
  padding: 12px;
  background-color: #f1f1f1;
  font-size: 18px;
}
#myJudul td {
  text-align: center;
  padding: 12px;
 
  
}
		
		</style>
<style type="text/css">
		p.dotted {
			border-style: dotted;
		}
		p.dashed {
			border-style: dashed;
		}
		p.solid {
			border-style: solid;
		}
		p.double {
			border-style: double;
		}
		p.groove {
			border-style: groove;
		}
		p.ridge {
			border-style: ridge;
		}
		p.inset {
			border-style: inset;
		}
		p.outset {
			border-style: outset;
		}
		p.none {
			border-style: none;
		}
		p.hidden {
			border-style: hidden;
		}
		p.mix {
			border-style: dotted dashed solid double;
		}
	</style>
<style type="text/css">
   .left    { text-align: left;}
   .right   { text-align: right;}
   .center  { text-align: center;}
   .justify { text-align: justify;}
</style>	
	
		
	</head>
	<body>
		<!-- Membuat Menu Header / Navbar -->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><p class="glyphicon glyphicon-chevron-left" value="<" onclick="history.back(-1)"/></p></div>
  <div class="col-8" ><p>ORDER DB</p></div>
  <div class="col-2"><a href="view_cart.php"><span class="glyphicon glyphicon-shopping-cart"></span></a></div>
</div>
<div class="container row fixed-top navbaru2" >

  <div class="col-2"></div>
  <div class="col-8" ></div>
  <div class="col-2"><a href="view_cart.php"><span class="badge"><?php echo $keranjangdb; ?></span></a></div>
</div><br><br><br><br>





<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {
   
    background: #fefbd8  url("jumbotron-bg.png") center center;
    margin: auto;
   text-align: center;
    overflow: hidden;
    
}

.navbaru p {
  
  padding: 10px 0;
  font-size: 20px;
   color: #0f0f0a;
   text-align: center;
   
}

.navbaru span {
  
  padding: 5px 0;
  font-size: 30px;
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
		
		<div style="padding: 0 15px;">
		      <?php
		//info message
		if(isset($_SESSION['message'])){
			?>
			<div class="row">
				<div class="col-sm-6 col-sm-offset-6">
					<div class="alert alert-info text-center">
						<?php echo $_SESSION['message']; ?>
					</div>
				</div>
			</div>
			<?php
			unset($_SESSION['message']);
		}
		?>	   
	
			<form method="POST" action="save_cartdb.php">
			<table class="table table-bordered table-striped">
				<thead>
					<th>Nama</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Subtotal</th>
				</thead>
				<tbody>
					<?php
					    include "koneksi.php";
						//initialize total
						$idmitramarketer=$_SESSION["mitraagen"]["idmitramarketer"];
						$idadmin=$_SESSION["mitraagen"]["idadmin"];
						$total = 0;
						$berat=0;
						$qty=0;
			
						$sql = "SELECT * FROM keranjangdb inner join produkdb on keranjangdb.idprodukdb=produkdb.idprodukdb WHERE idmarketer='$idmitramarketer' and jmlh>0 ";
						$query = $koneksi->query($sql);
						while($row = $query->fetch_assoc()){
					?>
								<tr>
									<input type="hidden" name="idprodukdb[]" value="<?php echo $row['idprodukdb']; ?>">
									<input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
									<input type="hidden" name="idmitra" value="<?php echo $idmitramarketer; ?>">
									<input type="hidden" name="idadmin" value="<?php echo $idadmin; ?>">
									<input type="hidden" name="stock[]" value="<?php echo $row['stock']; ?>">
									<input type="hidden" name="jmlh[]" value="<?php echo $row['jmlh']; ?>">
									<input type="hidden" name="subtotal[]" value="<?php echo $row['subtotal']; ?>">
									<td><?php echo $row['namaproduk']; ?></td>
									<td>Rp. <?php echo number_format($row['harga'], 2); ?></td>
									<?php
									$max=$row['stock'];
									$max1=$max+1;
									?>
									<td><input type="number" min="0" class="form-control" value="<?php echo $row['jmlh']; ?>" name="jmlhbaru[]" max="<?php echo $max1; ?>">Ready Stock : <?php echo $row['stock']; ?></td>
									<?php $subtotal=number_format($row['subtotal'], 2); ?>
									<td><?php echo $subtotal  ?></td>
									<?php $total +=$row['subtotal']; 
									       $berat += $row['berat'] ?>
								</tr>
					
							<?php
							$qty+=$row['jmlh'];
						}
                      
					?>
										<tr>
						<td colspan="3" align="right"><b>Jumlah Qty</b></td>
						<td><b><?php echo $qty; ?></b></td>
					</tr>
					<tr>
						<td colspan="3" align="right"><b>Total</b></td>
						<td><b><?php echo number_format($total,2); ?></b></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="berat" value="<?php echo $berat; ?>">
			<div class="container">
			<div class="d-flex justify-content-between  mb-3">
            <div class="p-2 "><a href="storedb.php" class="btn btn-warning btn-s"><span class="glyphicon glyphicon-chevron-left"></span>  </a></div>
            <div class="p-2 "></div>
            <div class="p-2 "><button type="submit" class="btn btn-success btn-s" name="save">Ubah Stock</button></div>
          </div>
          </div>
			
			<br>
			<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Klik Ubah Stock sebelum checkout
            </div>
			<br>
			
                <div class="d-flex justify-content-center">
                 
                
                    <?php if($total==0){
                      echo "<a href='storedb.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                    }else{
                    echo"<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <span class='glyphicon glyphicon-chevron-right'> </button>";
                    }
                    ?>       
                </div>
			
			</form>
		</div>
	</div>
</div>
</body>
</html>