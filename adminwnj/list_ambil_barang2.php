<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	include 'koneksi.php'; 
	if(!isset($_SESSION["administrator"])){
		echo "<script>alert('anda harus login terlebih dahulu');</script>";
		echo "<script>location='login.php';</script>";
		header('location:login.php');
		exit();
	}


	function getData($koneksi, $tipe = 'home') {
		switch ($tipe) {
			case 'agen':
				$tableOrder = 'orderagen';
				$tableMitra = 'mitraagen';
				$joinField = 'idmitraagen';
				$suratTable = 'surat_jalan_subdb';
				break;
			case 'reseller':
				$tableOrder = 'orderreseller';
				$tableMitra = 'mitrareseller';
				$joinField = 'idmitrareseller';
				$suratTable = 'surat_jalan_subdb';
				break;
			case 'marketer':
				$tableOrder = 'ordermarketer';
				$tableMitra = 'mitramarketer';
				$joinField = 'idmitramarketer';
				$suratTable = 'surat_jalan_subdb';
				break;
			default:
				$tableOrder = 'ordermitra';
				$tableMitra = 'admin_mitra';
				$joinField = 'idmitra'; // dari ordermitra
				$adminJoinField = 'idadmin'; // dari admin_mitra
				$suratTable = 'surat_jalan';
		}

		 // subquery qty: hitung per invoice
    $subQty = "
        SELECT invoice, MAX(tgl) AS tgl, SUM(jumlah) AS qty, MAX(idorder) AS idorder
        FROM $tableOrder
        WHERE jumlah > 0 
          AND payment = 'Lunas'
          AND tgl >= DATE_SUB(NOW(), INTERVAL 1 MONTH)"
        . ($tipe != 'home' ? " AND status_progres = 0" : "") . "
        GROUP BY invoice
    ";

    // subquery progres: hitung per invoice
    $subProgres = "
        SELECT invoice, SUM(progres) AS progres
        FROM $suratTable
        GROUP BY invoice
    ";

    $query = "
        SELECT 
            q.invoice,
            am.namamitra,
            am.idadmin,
            acs.namacs,
            q.tgl,
            q.qty,
            COALESCE(p.progres, 0) AS progres"
        . ($tipe != 'home' ? ", m.namaagen" : "") . "
        FROM ($subQty) q
        INNER JOIN $tableOrder o ON o.invoice = q.invoice
        " . ($tipe != 'home' ? "INNER JOIN $tableMitra m ON o.$joinField = m.$joinField" : "") . "
        INNER JOIN admin_mitra am ON " . ($tipe === 'home' ? "o.$joinField = am.$adminJoinField" : "m.idadmin = am.idadmin") . "
        LEFT JOIN admin_mitra_cs acs ON am.idadmin = acs.idadmin
        LEFT JOIN ($subProgres) p ON q.invoice = p.invoice
        WHERE q.qty - COALESCE(p.progres, 0) > 0
		GROUP BY q.invoice
        ORDER BY q.idorder DESC
        LIMIT 2000
    ";

    // debug
    // var_dump($query);

    return $koneksi->query($query)->fetch_all(MYSQLI_ASSOC);
	}


	function tampilkanTabel($data, $type = 'home') {
		foreach ($data as $row) {
			$invoice 	= $row['invoice'];
			$tgl 		= $row['tgl'];
			$kurang 	= $row['qty'] - $row['progres'];

			switch ($type) {
				case 'agen':
					$baseUrl = "ambilbarang_agen2.php";
					break;
				case 'reseller':
					$baseUrl = "ambilbarang_reseller2.php";
					break;
				case 'marketer':
					$baseUrl = "ambilbarang_marketer2.php";
					break;
				default: // home
					$baseUrl = strtotime($tgl) > strtotime('2024-11-02') 
						? "ambilbarang2.php" 
						: "ambilbarang.php";
					break;
			}

			$url = "$baseUrl?invoice=$invoice";

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
	<title>Admin Pusat | Wanoja</title>
	<!-- Custom fonts for this template-->
	<link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<!-- Custom styles for this template-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
	<!-- DataTables JS -->
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

	<link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">
	<!-- Page Wrapper -->
	<div id="wrapper">
		<?php include "sidebar.php"; ?>
		<!-- Begin Page Content -->
		<div class="container-fluid">
			<h1>Ambil Barang</h1>
			<!-- Page Heading -->
			<ul class="nav nav-tabs" id="mainTab" role="tablist">
				<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home" role="tab">Mitra</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#agen" role="tab">Agen</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reseller" role="tab">Reseller</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#marketer" role="tab">Marketer</a></li>
			</ul>

			<div class="tab-content mt-3">
				<?php 
					$homeData 		= getData($koneksi, 'home');
					$agenData 		= getData($koneksi, 'agen');
					$resellerData 	= getData($koneksi, 'reseller');
					$marketerData 	= getData($koneksi, 'marketer');
				?>

				<div class="tab-pane fade show active" id="home" role="tabpanel">
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="tb_ambilbarang">
							<thead class="thead-light">
								<tr>
									<th>Invoice</th>
									<th>Nama Mitra</th>
									<th>Krg</th>
									<th>Nama CS</th>
									<th>Tanggal</th>
								</tr>
							</thead>
							<tbody>
								<?php tampilkanTabel($homeData, 'home'); ?>
							</tbody>
						</table>
					</div>
				</div>

				<?php foreach (['agen' => $agenData, 'reseller' => $resellerData, 'marketer' => $marketerData] as $tab => $data): ?>
				<div class="tab-pane fade" id="<?= $tab ?>" role="tabpanel">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead class="thead-light">
								<tr>
									<th>Invoice</th>
									<th>Nama Mitra (DB)</th>
									<th>Krg</th>
									<th>Nama CS</th>
									<th>Tanggal</th>
								</tr>
							</thead>
							<tbody>
								<?php tampilkanTabel($data, $tab); ?>
							</tbody>
						</table>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<!-- Footer -->
			<footer class="sticky-footer bg-white">
				<div class="container my-auto">
				<div class="copyright text-center my-auto">
					<span>Copyright &copy; WNJ 2020</span>
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

	<script>
		$(document).ready(function () {
			$('#tb_ambilbarang').DataTable(); // Default setup
		});
	</script>
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
	<!-- JS Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
	
	<?php include "settingdatatables.php"; ?>
</body>

</html>

													