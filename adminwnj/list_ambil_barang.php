<?php 
	session_start();
	include 'koneksi.php'; 
  	if(!isset($_SESSION["administrator"])){
		echo "<script>alert('anda harus login terlebih dahulu');</script>";
		echo "<script>location='login.php';</script>";
		header('location:login.php');
		exit();
  	}

	function getData($koneksi, $tipe= 'home') {
		switch ($$tipe) {
			case 'agen':
				$tableOrder 	= 'orderagen';
				$tableMitra 	= 'mitraagen';
				$joinField 		= 'idmitraagen';
				$suratTable		= 'surat_jalan_subdb';
				break;
			case 'reseller':
				$tableOrder 	= 'orderreseller';
				$tableMitra 	= 'mitrareseller';
				$joinField 		= 'idmitrareseller';
				$suratTable		= 'surat_jalan_subdb';
				break;
			case 'marketer':
				$tableOrder 	= 'ordermarketer';
				$tableMitra 	= 'mitramarketer';
				$joinField 		= 'idmitramarketer';
				$suratTable		= 'surat_jalan_subdb';
				break;
			
			default:
				$tableOrder 	= 'ordermitra';
				$tableMitra		= 'admin_mitra';
				$joinField		= 'idadmin';
				$suratTable		= 'surat_jalan';
				break;
		}

		$query = "
			SELECT 
				o.invoice,
				am.namamitra,
				am.idadmin,
				acs.namacs,
				MAX(o.tgl) AS tgl,
				SUM(o.jumlah) AS qty,
				COALESCE(SUM(sj.progres), 0) AS progres";

		if ($tipe != 'home') {
			$query .= ",
				m.namaagen";
		}

		$query .= "
			FROM $tableOrder o
			INNER JOIN $tableMitra m ON o.$joinField = m.$joinField
			INNER JOIN admin_mitra am ON m.idadmin = am.idadmin
			JOIN admin_mitra_cs acs ON am.idadmin = acs.idadmin
			LEFT JOIN $suratTable sj ON o.invoice = sj.invoice
			WHERE o.jumlah > 0 
			AND o.payment = 'Lunas'
			AND o.tgl >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";

		if ($tipe != 'home') {
			$query .= " AND o.status_progres = 0";
		}

		$query .= "
			GROUP BY o.invoice
			HAVING qty - progres > 0
			ORDER BY MAX(o.idorder) DESC
			LIMIT 2000
		";

		echo $query;
		return $koneksi->query($query)->fetch_all(MYSQLI_ASSOC);
	}

	function tampilkanTabel($data, $type = 'home') {
		foreach ($data as $row) {
			$invoice = $row['invoice'];
			$tgl = $row['tgl'];
			$kurang = $row['qty'] - $row['progres'];

			$url = strtotime($tgl) > strtotime('2024-11-02') 
				? "ambilbarang_{$type}2.php?invoice=$invoice" 
				: "ambilbarang_{$type}.php?invoice=$invoice";

			$mitraInfo = ($type == 'home')
				? "{$row['namamitra']} ({$row['idadmin']})"
				: "<i class='fas fa-user'></i> {$row['namaagen']} ({$row['namamitra']})";

			echo "<tr>
				<td><a href='$url' target='_blank'>$invoice</a></td>
				<td>$mitraInfo</td>
				<td style='text-align:center'>$kurang</td>
				<td><strong>{$row['namacs']}</strong></td>
				<td><i class='fas fa-calendar' style='color:red'></i> $tgl</td>
			</tr>";
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

  <title>WNJ.ID</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  
  

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">
	<?php include "sidebar.php"; ?>
	  <!-- Begin Page Content -->
	  <div class="container-fluid">
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
		  <h1 class="h3 mb-0 text-gray-800"></h1>
		  <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
		</div>
		<h3><strong>Ambil Barang</strong></h3><br>
		<!-- <a href="list_ambilbarang_print.php" class="btn btn-primary">Print Ambil Barang</a> -->
		<br>
		<br>
		  <!-- Content Row -->
		  <ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Distributor</a></li>
			<li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Sub-DB</a></li>
			<!-- <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Reseller</a></li>
			<li><a data-toggle="tab" href="#menu3" class="nav-item nav-link"> Marketer</a></li> -->
		  </ul>
	
		  <div class="tab-content">
			<div id="home" class="tab-pane fade show active" id="home"  role="tabpanel">    
			  <div class="table-responsive">
				<table class="table table-bordered" id="tb_ambil_barang">
				  <thead>
					<tr>
					  <th>Invoice</th>
					  <th>Nama Mitra</th>            
					  <th>Krg</th>
					  <th>Nama CS</th>
					  <th>Tanggal</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					  $datapo=$koneksi->query(" SELECT 
													ordermitra.invoice,
													admin_mitra.namamitra,
													admin_mitra.idadmin,
													admin_mitra_cs.namacs,
													SUM(ordermitra.jumlah) as qty,
													MAX(ordermitra.tgl) as tgl,
													MAX(ordermitra.payment) as payment,
													MAX(ordermitra.status_progres) as status_progres
												FROM 
													`ordermitra` 
												INNER JOIN 
													admin_mitra ON ordermitra.idmitra = admin_mitra.idadmin 
												JOIN 
													admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin
												WHERE 
													ordermitra.jumlah > 0 
													AND ordermitra.payment = 'Lunas' 
													AND ordermitra.tgl >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
												GROUP BY 
													ordermitra.invoice,
													admin_mitra.namamitra,
													admin_mitra.idadmin,
													admin_mitra_cs.namacs
												ORDER BY 
													MAX(ordermitra.idorder) DESC
												LIMIT 2000      
											  ");
					  $no=1;
					  while($tampilkan = $datapo->fetch_assoc()){
						$invoice1 		= $tampilkan['invoice'];
						$datamitra		= $koneksi->query("SELECT SUM(surat_jalan.progres) as progresnya, surat_jalan.invoice FROM surat_jalan WHERE surat_jalan.invoice='$invoice1'");
						$tampilprogres	= $datamitra->fetch_assoc();                                   
						$kurang 		= $tampilkan['qty']-$tampilprogres['progresnya'];                  
					  ?>
					  <?php if ($kurang<>0): ?>
						<tr>
							<td>
								<? if (strtotime($tampilkan['tgl']) > strtotime('2024-11-02')): ?>
									<a href="ambilbarang2.php?invoice=<?= $tampilkan['invoice']; ?>" target="blank()"><?= $tampilkan['invoice']; ?></a>
								<? else :?>
									<a href="ambilbarang.php?invoice=<?= $tampilkan['invoice']; ?>" target="blank()"><?= $tampilkan['invoice']; ?></a>
								<? endif ?>
							</td>
							<td>
								<?= $tampilkan['namamitra']; ?> (<?= $tampilkan['idadmin']; ?>) 
							</td>
							<td style="text-align:center"><?= $kurang; ?></td>
							<td>
								<strong><?= $tampilkan['namacs']; ?></strong>
							</td> 
							<td>
								<i class="fas fa-calendar" style="color: red"></i> <?= $tampilkan['tgl']; ?>
							</td>
						</tr>
					  <?php endif ?>
					  <?php } ?>
				  </tbody>
				</table>
			  </div>
			</div>   
			<div id="menu1" class="tab-pane"  role="tabpanel"> 
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#agen" class="nav-item nav-link active">Agen</a></li>
					<li><a data-toggle="tab" href="#reseller" class="nav-item nav-link">Reseller</a></li>
					<li><a data-toggle="tab" href="#marketer" class="nav-item nav-link">Marketer</a></li>
				</ul>
				<div class="tab-content">
					<div id="agen" class="tab-pane fade show active" role="tabpanel"> 
						<p>Agen</p>
					<div class="table-responsive">
					<table class="table table-bordered" id="tb_ambil_barang_agen">
						<thead>
							<tr>
							<!-- <th>No</th> -->
							<th>Invoice</th>
							<th>Nama Mitra (DB)</th>
							<th>Krg</th>
							<th>Nama CS</th>
							<th>Tanggal</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$datapo = $koneksi->query("SELECT mitraagen.namaagen, admin_mitra.namamitra, orderagen.tgl, orderagen.invoice,
																orderagen.payment, orderagen.status, admin_mitra_cs.namacs, SUM(orderagen.jumlah) as qty 
															FROM `orderagen` 
															INNER JOIN mitraagen on orderagen.idmitraagen = mitraagen.idmitraagen 
															INNER JOIN admin_mitra on mitraagen.idadmin = admin_mitra.idadmin 
															JOIN admin_mitra_cs on mitraagen.idadmin = admin_mitra_cs.idadmin
															WHERE orderagen.jumlah > 0 
															And orderagen.payment = 'Lunas' 
															AND orderagen.tgl >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
															and orderagen.status_progres = 0
															GROUP BY orderagen.invoice ORDER BY orderagen.idorder DESC limit 2000 
														");
							$no=1;
				
							while($tampilkanagen = $datapo->fetch_assoc()){
								$invoiceagen 	= $tampilkanagen['invoice'];
								$datamitra		= $koneksi->query("SELECT SUM(surat_jalan_subdb.progres) as progresnya, surat_jalan_subdb.invoice 
																	FROM surat_jalan_subdb
																	WHERE surat_jalan_subdb.invoice = '$invoiceagen'");
								$tampilprogres	= $datamitra->fetch_assoc();                                   
								$kurangagen 	= $tampilkanagen['qty']-$tampilprogres['progresnya'];                  
							?>
								<?php if ($kurangagen <> 0): ?>
							<tr>
								<!-- <td>
								<strong><?= $no++; ?></strong>
								</td>  -->  
								<td>
									<? if (strtotime($tampilkanagen['tgl']) > strtotime('2024-11-02')): ?>
										<a href="ambilbarang_agen2.php?invoice=<?= $tampilkanagen['invoice']; ?>" target="blank()"><?= $tampilkanagen['invoice']; ?></a>
									<? else :?>
										<a href="ambilbarang_agen.php?invoice=<?= $tampilkanagen['invoice']; ?>" target="blank()"><?= $tampilkanagen['invoice']; ?></a>
									<? endif ?>
								</td>
								<td><i class="fas fa-user"></i> <?= $tampilkanagen['namaagen']; ?> (<?= $tampilkanagen['namamitra']; ?>)</td>
								<td style="text-align:center"><?= $kurangagen; ?></td>
								<td><strong><?= $tampilkanagen['namacs']; ?></strong></td> 
								<td><i class="fas fa-calendar" style="color: red"></i> <?= $tampilkanagen['tgl']; ?></td>
							</tr>
							<?php endif ?>                          
							<?php } ?>
						</tbody>
						</table>
					</div>    
				</div>
				<!-- RESELLER -->
				<div id="reseller" class="tab-pane" role="tabpanel">
				  <p>Reseller</p>
				  <div class="table-responsive">
					<table class="table table-bordered" id="tb_ambil_barang_reseller">
					  <thead>
						<tr>
						  <!-- <th>No</th> -->
						  <th>Invoice</th>
						  <th>Nama Mitra (DB)</th>
						  <th>Krg</th>
						  <th>Nama CS</th>
						  <th>Tanggal</th>
						</tr>
					  </thead>
					  <tbody>
						<?php 
					  
						  $datapo=$koneksi->query("SELECT 
													mitrareseller.namaagen,
													admin_mitra.namamitra,
													orderreseller.tgl,
													orderreseller.invoice,
													orderreseller.payment,
													orderreseller.status,
													admin_mitra_cs.namacs,
													SUM(orderreseller.jumlah) as qty 
													FROM `orderreseller` 
													inner join mitrareseller on orderreseller.idmitrareseller=mitrareseller.idmitrareseller 
													inner join admin_mitra on mitrareseller.idadmin=admin_mitra.idadmin 
													JOIN admin_mitra_cs on mitrareseller.idadmin = admin_mitra_cs.idadmin 
													  WHERE orderreseller.jumlah>0 
													  And orderreseller.payment = 'Lunas' 
													  AND orderreseller.tgl >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
													  and orderreseller.status_progres = 0
													  GROUP BY orderreseller.invoice ORDER BY orderreseller.idorder DESC limit 2000 
												");
						  $no=1;
			  
						  while($tampilkanreseller=$datapo->fetch_assoc()){
							$invoicereseller = $tampilkanreseller['invoice'];
							$datamitra=$koneksi->query("SELECT 
							  SUM(surat_jalan_subdb.progres) as progresnya, 
							  surat_jalan_subdb.invoice 
							  FROM surat_jalan_subdb
							  WHERE surat_jalan_subdb.invoice='$invoicereseller'");
							  $tampilprogres=$datamitra->fetch_assoc();                                   
							  $kurangreseller = $tampilkanreseller['qty']-$tampilprogres['progresnya'];                  
						?>
						<?php if ($kurangreseller): ?>
						  <tr>
							<!-- <td>
								<strong><?= $no++; ?></strong>
							</td>  -->  
							<td>
								<? if (strtotime($tampilkanreseller['tgl']) > strtotime('2024-11-02')): ?>
									<a href="ambilbarang_reseller2.php?invoice=<?= $tampilkanreseller['invoice']; ?>" target="blank()"><?= $tampilkanreseller['invoice']; ?></a>
								<? else :?>
									<a href="ambilbarang_reseller.php?invoice=<?= $tampilkanreseller['invoice']; ?>" target="blank()"><?= $tampilkanreseller['invoice']; ?></a>
								<? endif ?>
							</td>
							<td>
							  <i class="fas fa-user"></i> <?= $tampilkanreseller['namaagen']; ?> (<?= $tampilkanreseller['namamitra']; ?>)
							</td>
							<td style="text-align:center"><?= $kurangreseller; ?></td>
							<td>
							  <strong><?= $tampilkanreseller['namacs']; ?></strong>
							</td> 
							<td>
							  <i class="fas fa-calendar" style="color: red"></i> <?= $tampilkanreseller['tgl']; ?>
							</td>
						  </tr>
						<?php endif ?>                           
						<?php } ?>
					  </tbody>
					</table>
				  </div>
				</div>
				<!-- RESELLER END -->
				<!-- MARKETER -->
				<div id="marketer" class="tab-pane" role="tabpanel">
				  <p>Marketer</p>
					<div class="table-responsive">
					  <table class="table table-bordered" id="tb_ambil_barang_marketer">
						<thead>
						  <tr>
							<!--  <th>No</th> -->
							<th>Invoice</th>
							<th>Nama Mitra (DB)</th>
							<th>Krg</th>
							<th>Nama CS</th>
							<th>Tanggal</th>          
						  </tr>
						</thead>
					  <tbody>
						<?php 
					  
						  $datapo=$koneksi->query("SELECT 
													mitramarketer.namaagen,
													admin_mitra.namamitra,
													ordermarketer.tgl,
													ordermarketer.invoice,
													ordermarketer.payment,
													ordermarketer.status,
													admin_mitra_cs.namacs,
													SUM(ordermarketer.jumlah) as qty 
													FROM `ordermarketer` 
													inner join mitramarketer on ordermarketer.idmitramarketer=mitramarketer.idmitramarketer 
													inner join admin_mitra on mitramarketer.idadmin=admin_mitra.idadmin 
													JOIN admin_mitra_cs on mitramarketer.idadmin = admin_mitra_cs.idadmin 
													  WHERE ordermarketer.jumlah>0 And ordermarketer.payment = 'Lunas' 
													  AND ordermarketer.tgl >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
													  and ordermarketer.status_progres = 0
													  GROUP BY ordermarketer.invoice ORDER BY ordermarketer.idorder DESC
												");
						  $no=1;
						  while($tampilkanmarketer=$datapo->fetch_assoc()){
							$invoicemarketer = $tampilkanmarketer['invoice'];
							$datamitra=$koneksi->query("SELECT 
							  SUM(surat_jalan_subdb.progres) as progresnya, 
							  surat_jalan_subdb.invoice 
							  FROM surat_jalan_subdb
							  WHERE surat_jalan_subdb.invoice='$invoicemarketer'");
							  $tampilprogres=$datamitra->fetch_assoc();                                   
							  $kurangmarketer = $tampilkanmarketer['qty']-$tampilprogres['progresnya'];                  
						?>
						<?php if ($kurangmarketer): ?>
							<tr>
							  <!--  <td>
								  <strong><?= $no++; ?></strong>
							  </td>   --> 
							  <td>
								<? if (strtotime($tampilkanmarketer['tgl']) > strtotime('2024-11-02')): ?>
									<a href="ambilbarang_marketer2.php?invoice=<?= $tampilkanmarketer['invoice']; ?>" target="blank()"><?= $tampilkanmarketer['invoice']; ?></a>
								<? else :?>
									<a href="ambilbarang_marketer.php?invoice=<?= $tampilkanmarketer['invoice']; ?>" target="blank()"><?= $tampilkanmarketer['invoice']; ?></a>
								<? endif ?>
							  </td>
							  <td>
								<i class="fas fa-user"></i> <?= $tampilkanmarketer['namaagen']; ?> (<?= $tampilkanmarketer['namamitra']; ?>)
							  </td>
							  <td style="text-align:center"><?= $kurangmarketer; ?></td>
							  <td>
								<strong><?= $tampilkanmarketer['namacs']; ?></strong>
							  </td>
							  <td>
								<i class="fas fa-calendar" style="color: red"></i> <?= $tampilkanmarketer['tgl']; ?>
							  </td> 
							</tr>
						<?php endif ?>                           
						<?php } ?>
					  </tbody>
					</table>
				  </div>
				</div>
			  </div>                
			</div>
						
							
 <!-- Footer -->
	  <footer class="sticky-footer bg-white">
		<div class="container my-auto">
		  <div class="copyright text-center my-auto">
			<span>Copyright &copy; Your Website 2020</span>
		  </div>
		</div>
	  </footer>
	  <!-- End of Footer -->

	</div>
	<!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
	<i class="fas fa-angle-up"></i>
  </a>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
  
  <?php include "settingdatatables.php"; ?>
	<script type="text/javascript">
		$(document).ready( function () {
		$('#tb_ambil_barang').DataTable({
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"pageLength": 25,
			order: [[2, 'desc']]
	});
	} );
	</script>

	<script type="text/javascript">
		$(document).ready( function () {
		$('#tb_ambil_barang_agen').DataTable({
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"pageLength": 25,
			order: [[2, 'desc']]
	});
	} );
	</script>

	<script type="text/javascript">
		$(document).ready( function () {
		$('#tb_ambil_barang_reseller').DataTable({
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"pageLength": 25,
			order: [[2, 'desc']]
	});
	} );
	</script>

	<script type="text/javascript">
		$(document).ready( function () {
		$('#tb_ambil_barang_marketer').DataTable({
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"pageLength": 25,
			order: [[2, 'desc']]
	});
	} );
	</script>

</body>

</html>

													