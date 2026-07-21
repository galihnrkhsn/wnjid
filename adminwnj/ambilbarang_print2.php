<?php
var_dump('print baru');
die();
    include "koneksi.php";    
    if(isset($_POST['but_selesai'])){
        if(isset($_POST['update'])){
            foreach($_POST['update'] as $updateid){
                $invoice = $_POST['invoice'.$updateid];      
                $sqlnya = $koneksi->query("UPDATE ordermitra set Status_progres=1 where invoice='$invoice'");
            }
            if ($sqlnya) {
                echo "<script>alert('status berhasil diubah');</script>";
                echo "<script>location='list_ambilbarang_print.php';</script>";  
            } else {
                echo "<script>alert('status gagal diubah');</script>";
                echo "<script>location='list_ambilbarang_print.php';</script>";
            }
        }
    }
    if(isset($_POST['but_minus'])){
        if(isset($_POST['update'])){
            foreach($_POST['update'] as $updateid){
                $invoice = $_POST['invoice'.$updateid];
                // echo "<script>alert('$updateid');</script>";
                $sqlnya = $koneksi->query("UPDATE ordermitra set Status_progres=2 where invoice='$invoice'");
            }
            if ($sqlnya) {
                echo "<script>alert('status berhasil diubah');</script>";
                echo "<script>location='list_ambilbarang_print.php';</script>";  
            } else {
                echo "<script>alert('status gagal diubah');</script>";
                echo "<script>location='list_ambilbarang_print.php';</script>";
            }
        }
    }
    if(isset($_POST['but_selesai_agen'])){
        if(isset($_POST['update_agen'])){
            foreach($_POST['update_agen'] as $updateid){
                $invoice = $_POST['invoice'.$updateid];      
                // echo "<script>alert('$updateid');</script>";
                $sqlnya = $koneksi->query("UPDATE orderagen set Status_progres=1 where invoice='$invoice'");
            }
            if ($sqlnya) {
                echo "<script>alert('status berhasil diubah');</script>";
                echo "<script>location='list_ambilbarang_print.php';</script>";  
            } else {
                echo "<script>alert('status gagal diubah');</script>";
                echo "<script>location='list_ambilbarang_print.php';</script>";
            }
        }
    }   
    if(isset($_POST['but_minus_agen'])){
		if(isset($_POST['update_agen'])){
			foreach($_POST['update_agen'] as $updateid){
				$invoice = $_POST['invoice'.$updateid];
				// echo "<script>alert('$updateid');</script>";
				$sqlnya = $koneksi->query("UPDATE orderagen set Status_progres=2 where invoice='$invoice'");
			}
			if ($sqlnya) {
				echo "<script>alert('status berhasil diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";  
			}else{
				echo "<script>alert('status gagal diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";
			}       
		}
    }        
	if(isset($_POST['but_selesai_reseller'])){
		if(isset($_POST['update_reseller'])){
			foreach($_POST['update_reseller'] as $updateid){
				$invoice = $_POST['invoice'.$updateid];
				// echo "<script>alert('$updateid');</script>";
					$sqlnya = $koneksi->query("UPDATE orderreseller set Status_progres=1 where invoice='$invoice'");
			}
			if ($sqlnya) {
				echo "<script>alert('status berhasil diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";  
			}else{
				echo "<script>alert('status gagal diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";
			}       
		}
    }    
	if(isset($_POST['but_minus_reseller'])){
		if(isset($_POST['update_reseller'])){
			foreach($_POST['update_reseller'] as $updateid){
				$invoice = $_POST['invoice'.$updateid];
				// echo "<script>alert('$updateid');</script>";
				$sqlnya = $koneksi->query("UPDATE orderreseller set Status_progres=2 where invoice='$invoice'");
			}
			if ($sqlnya) {
				echo "<script>alert('status berhasil diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";  
			}else{
				echo "<script>alert('status gagal diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";
			} 
		}
    }        
	if(isset($_POST['but_selesai_marketer'])){
		if(isset($_POST['update_marketer'])){
			foreach($_POST['update_marketer'] as $updateid){
				$invoice = $_POST['invoice'.$updateid];
				// echo "<script>alert('$updateid');</script>";
				$sqlnya = $koneksi->query("UPDATE ordermarketer set Status_progres=1 where invoice='$invoice'");
			}
			if ($sqlnya) {
				echo "<script>alert('status berhasil diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";  
			}else{
				echo "<script>alert('status gagal diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";
			}
		}
    }   
	if(isset($_POST['but_minus_marketer'])){
		if(isset($_POST['update_marketer'])){
			foreach($_POST['update_marketer'] as $updateid){
				$invoice = $_POST['invoice'.$updateid];
				// echo "<script>alert('$updateid');</script>";
				$sqlnya = $koneksi->query("UPDATE ordermarketer set Status_progres=2 where invoice='$invoice'");
			}
			if ($sqlnya) {
				echo "<script>alert('status berhasil diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";  
			}else{
				echo "<script>alert('status gagal diubah');</script>";
				echo "<script>location='list_ambilbarang_print.php';</script>";
			}   
		}
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Cetak invoice</title>

	<!-- Custom fonts for this template-->
	<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<style type="text/css">
	table, th, td {
		border: 3px solid;
	}
	body{
		color: black;
	}
</style>
<body>
	<center><h1><strong>Ambil Barang</strong></h1></center>
	<?php 
		if(isset($_POST['but_export'])){ 
	?>
	<table class="table" id="tb_multiprint" style="font-size: 25px; color: black;">
		<thead>
			<tr>       
				<th >Nama CS</th>
				<th >Nama DB</th>
				<th >Invoice</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if(isset($_POST['update'])){
					foreach($_POST['update'] as $updateid){
						$invoice = $_POST['invoice'.$updateid];                
						$datapo = $koneksi->query("SELECT admin_mitra.namamitra, ordermitra.tgl, ordermitra.invoice,
														ordermitra.payment, ordermitra.status, admin_mitra_cs.namacs, SUM(ordermitra.jumlah) as qty
													FROM `ordermitra` 
													inner join admin_mitra on ordermitra.idmitra = admin_mitra.idadmin 
													JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin
													WHERE ordermitra.jumlah > 0 
													AND ordermitra.payment = 'Lunas' 
													AND ordermitra.tgl > '2022-05-01' 
													AND ordermitra.invoice = '$invoice'
													GROUP BY ordermitra.invoice ORDER BY ordermitra.idorder DESC
												");
						while($tampilkandata = $datapo->fetch_assoc()){
            ?>
             	<tr>	
					<td><?= $tampilkandata['namacs']; ?></td>
					<td><?= $tampilkandata['namamitra']; ?></td>
					<td><?= $tampilkandata['invoice']; ?></td> 
				</tr>
            <?php 
						}
					}
				}
			?>
		</tbody>
	</table>
	<?php } ?>
	<?php 
		if(isset($_POST['but_export_agen'])){ 
  	?>
	<table class="table" id="tb_multiprint" style="font-size: 25px; color: black;">
		<thead>
			<tr>       
				<th >Nama CS</th>
				<th >Nama Agen</th>
				<th >Invoice</th>
            </tr>
		</thead>
		<tbody>
			<?php 
				if(isset($_POST['update_agen'])){
              		foreach($_POST['update_agen'] as $updateid){
						$invoice = $_POST['invoice'.$updateid];
 			?>
            <?php 
            	$datapo = $koneksi->query("SELECT mitraagen.namaagen, orderagen.tgl, orderagen.invoice, orderagen.payment,
												orderagen.status, mitraagen.idadmin, admin_mitra_cs.namacs, SUM(orderagen.jumlah) as qty 
											FROM `orderagen` 
											INNER JOIN mitraagen on orderagen.idmitraagen = mitraagen.idmitraagen
											JOIN admin_mitra_cs on mitraagen.idadmin = admin_mitra_cs.idadmin
											WHERE orderagen.jumlah > 0 And orderagen.payment = 'Lunas' and orderagen.tgl > '2022-05-01'
											and orderagen.invoice = '$invoice' 
											GROUP BY orderagen.invoice ORDER BY orderagen.idorder DESC limit 2000
                                    	");
				while($tampilkandata = $datapo->fetch_assoc()){
            ?>
			<tr>	
				<td><?= $tampilkandata['namacs']; ?></td>
				<td><?= $tampilkandata['namaagen']; ?></td>
				<td><?= $tampilkandata['invoice']; ?></td> 
			</tr>
            <?php 
					} 
				}
			}
			?>
          </tbody>
        </table>
		<?php }?>
		<?php 
			if(isset($_POST['but_export_reseller'])){
		?>
		<table class="table" id="tb_multiprint" style="font-size: 25px; color: black;">
			<thead>
				<tr>       
					<th>Nama CS</th>
					<th>Nama Reseller</th>
					<th>Invoice</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if(isset($_POST['update_reseller'])){
						foreach($_POST['update_reseller'] as $updateid){
							$invoice = $_POST['invoice'.$updateid];
              
				?>
				<?php 
					$datapo = $koneksi->query("SELECT mitrareseller.namaagen, orderreseller.tgl, orderreseller.invoice,
													orderreseller.payment, orderreseller.status, admin_mitra_cs.namacs, SUM(orderreseller.jumlah) as qty 
												FROM `orderreseller` 
												INNER JOIN mitrareseller on orderreseller.idmitrareseller = mitrareseller.idmitrareseller
												JOIN admin_mitra_cs on mitrareseller.idadmin = admin_mitra_cs.idadmin 
												WHERE orderreseller.jumlah > 0 And orderreseller.payment = 'Lunas' and orderreseller.tgl > '2022-05-01'
												AND orderreseller.invoice = '$invoice'
												GROUP BY orderreseller.invoice ORDER BY orderreseller.idorder DESC limit 2000 
											");
					while($tampilkandata=$datapo->fetch_assoc()){
				?>
					<tr>	
						<td><?= $tampilkandata['namacs']; ?></td>
						<td><?= $tampilkandata['namaagen']; ?></td>
						<td><?= $tampilkandata['invoice']; ?></td> 
					</tr>
				<?php 		
							} 
						}
					}
				?>
          	</tbody>
        </table>
		<?php
			}
		?>
		<?php
			if(isset($_POST['but_export_marketer'])){
		?>
		<table class="table" id="tb_multiprint" style="font-size: 25px; color: black;">
			<thead>
				<tr>       
					<th >Nama CS</th>
					<th >Nama Marketer</th>
					<th >Invoice</th>
				</tr>
			</thead>
            <tbody>
				<?php
					if(isset($_POST['update_marketer'])){
						foreach($_POST['update_marketer'] as $updateid){
							$invoice = $_POST['invoice'.$updateid];
				?>
				<?php 
					$datapo = $koneksi->query("SELECT mitramarketer.namaagen, ordermarketer.tgl, ordermarketer.invoice, ordermarketer.payment,
													ordermarketer.status, admin_mitra_cs.namacs, SUM(ordermarketer.jumlah) as qty 
												FROM `ordermarketer` 
												INNER JOIN mitramarketer on ordermarketer.idmitramarketer = mitramarketer.idmitramarketer 
												JOIN admin_mitra_cs on mitramarketer.idadmin = admin_mitra_cs.idadmin 
												WHERE ordermarketer.jumlah>0 And ordermarketer.payment = 'Lunas' and ordermarketer.tgl > '2022-05-01'
												and ordermarketer.invoice = '$invoice'
												GROUP BY ordermarketer.invoice ORDER BY ordermarketer.idorder DESC
											");
					while($tampilkandata = $datapo->fetch_assoc()){
				?>
				<tr>	
					<td><?= $tampilkandata['namacs']; ?></td>
					<td><?= $tampilkandata['namaagen']; ?></td>
					<td><?= $tampilkandata['invoice']; ?></td> 
				</tr>
				<?php 
							}
						}
					}
				?>

          	</tbody>
        </table>
		<?php } ?>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>                    

</body>
<script>
window.print();
</script>

</html>

