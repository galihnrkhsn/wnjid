<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["admin_mitra"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

	//initialize cart if not set or is unset
if(!isset($_SESSION['po'])){
	$_SESSION['po'] = array();
}

	//unset qunatity
unset($_SESSION['po_array']);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		
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
	<?php include 'headstore.php'; ?>
	
	 <pre>
<a class="glyphicon glyphicon-chevron-left" value="<" href="store.php"/></a>                                        <a href="view_cart.php"><span class="badge"><?php echo count($_SESSION['cart']); ?></span> Keranjang <span class="glyphicon glyphicon-shopping-cart"></span></a></pre>
<div class="row">


<table id="myJudul">
  <tr>
      <table id="myJudul">
      <tr class="header">
    <th style="width:100%;">WANOJA STORE</th>
  </tr>
    <td></td>
  </tr>
</table>
        <div class="container">
	    
		
			<form method="POST" action="save_cart.php">
			<table class="table table-bordered table-striped">
				<thead>
					<th></th>
					<th>Nama</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Subtotal</th>
				</thead>
				<tbody>
					<?php
						//initialize total
						$total = 0;
						if(!empty($_SESSION['po'])){
						//connection
						include "koneksi.php";
						//create array of initail qty which is 1
 						$index = 0;
 						if(!isset($_SESSION['po_array'])){
 							$_SESSION['po_array'] = array_fill(0, count($_SESSION['po']), 1);
 						}
						$sql = "SELECT * FROM podetail WHERE idpodetail IN (".implode(',',$_SESSION['po']).")";
						$query = $koneksi->query($sql);
							while($row = $query->fetch_assoc()){
								?>
								<tr>
									<td>
										<a href="delete_item.php?id=<?php //echo $row['idproduk']; ?>&index=<?php //echo $index; ?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></a>
									</td>
									<td><?php echo $row['variant']; ?></td>
									<td>Rp. <?php echo number_format($row['harga'], 2); ?></td>
									<input type="hidden" name="indexes[]" value="<?php echo $index; ?>">
									<td><input type="text" class="form-control" value="<?php echo $_SESSION['po_array'][$index]; ?>" name="jumlah_<?php echo $index; ?>"></td>
									<td><?php echo number_format($_SESSION['po_array'][$index]*$row['harga'], 2); ?></td>
									<?php $total += $_SESSION['po_array'][$index]*$row['harga']; ?>
								</tr>
								<?php
								$index ++;
							}
						}
						else{
							?>
							<tr>
								<td colspan="4" class="text-center">Tidak ada Produk dalam keranjang</td>
							</tr>
							<?php
						}

					?>
					<tr>
						<td colspan="4" align="right"><b>Total</b></td>
						<td><b><?php echo number_format($total, 2); ?></b></td>
					</tr>
				</tbody>
			</table>
			<a href="store.php" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
			<button type="submit" class="btn btn-success btn-xs" name="save">Save Changes</button>
			<a href="clear_cart.php" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> Clear Cart</a>
			<a href="checkout.php" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-check"></span> Checkout</a>
			</form>
		</div>
	</div>
</div>
</body>
</html>