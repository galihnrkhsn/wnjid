<?php 
session_start();

include 'koneksi.php'; 
include 'assets/components/Sessions/sesMarketer.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mitra | WNJ </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
      <!-- Include Navbar -->
    <?php include "assets/components/Navbar/navbar2.php"; ?>
    <!-- Navbar End -->

    <!-- Main Content -->
    <div class="container mt-3">
        <h2 class="text-center mb-4"><i class="fas fa-bag-shopping"></i> Transaksi</h2>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#agen">Belum Bayar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reseller">Lunas</a>
            </li>
        </ul>

		<div class="container">
			<div class="tab-content mt-3">
				<!-- Tab Content for Agen -->
				<div id="agen" class="container tab-pane active">
					<h3 class="mb-3">Order Mitra</h3>
					<div class="table-responsive">
						<table class="table table-striped table-bordered" id="tb_dataagen">
							<thead>
								<tr>
									<th class="text-center" style="width: 60px;">No</th>
									<th>Data</th>
									<th>Invoice</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$no			= 1;
									$idmitra 	= $_SESSION["idmitramarketer"];
									$sql 		= mysqli_query($koneksi, "SELECT 
																			mitramarketer.namaagen,
																			ordermarketer.status as statusnya,
																			ordermarketer.payment,
																			ordermarketer.tgl,
																			ordermarketer.invoice,
																			ordermarketer.idproduk,
																			variants.jenis
																			FROM `ordermarketer` 
																			INNER JOIN mitramarketer on mitramarketer.idmitramarketer = ordermarketer.idmitramarketer
																			INNER JOIN variants ON variants.id = ordermarketer.idproduk
																			where ordermarketer.idmitramarketer = '$idmitra' 
																			and (ordermarketer.payment<>'Lunas' or ordermarketer.payment<>'LUNAS') 
																			GROUP BY invoice 
																			ORDER BY  ordermarketer.idorder desc");
									while($data = mysqli_fetch_array($sql)){
								?>
								<tr>
									<td style="width: 5%"><?= $no++; ?></td>
									<td>
										<b><?= $data['tgl']; ?></b>
										<br>
										Mitra: <?= $data['namaagen'];?>
										<br> 
										<b>Payment : <?= $data['payment']; ?></b>
									</td>
									<td>
										Inv :
											<?php if (substr($data['invoice'],0,1)=="F"): ?>
												<a class="text-primary" href="detailorder_get.php?id=<?= $data['invoice']; ?>">
											<?php elseif ($data['jenis'] == 'Flash'): ?>
												<a class="text-primary" href="dataorder2.php?id=<?= $data['invoice']; ?>$jenis=<?= $data['jenis'] ?>">#<?= $data['invoice'] ?></a>
											<?php elseif ($data['idproduk'] >= 14039 && $data['idproduk'] <= 14053 ): ?>
												<a class="text-primary" href="detailordershort.php?id=<?= $data['invoice'] ?>">#<?= $data['invoice'] ?></a>
											<?php elseif (strtotime($data['tgl']) > strtotime('2024-10-02 23:59:59') && substr($data['invoice'], 0, 2) == "MP"): ?>
												<a class="text-primary" href="dataorder2.php?id=<?= $data['invoice']; ?>">#<?= $data['invoice'] ?></a>
											<?php elseif (strtotime($data['tgl']) > strtotime('2024-10-02 23:59:59')): ?>
												<a class="text-primary" href="detailorder2.php?id=<?= $data['invoice']; ?>">#<?= $data['invoice'] ?></a>
											<?php elseif (substr($data['invoice'], 0, 2) == "MP") : ?>
												<a class="text-primary" href="dataorder.php?id=<?= $data['invoice'] ?>">#<?= $data['invoice'] ?></a>
											<?php else: ?>
												<a class="text-primary" href="detailorder.php?id=<?= $data['invoice']; ?>">#<?= $data['invoice'];?></a>
											<?php endif ?>
											<br>
											Status : <?= $data['statusnya']; ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- Tab Content for Agen END -->
				<!-- Tab Content for Reseller -->
				<div id="reseller" class="container tab-pane fade">
					<h3 class="mb-3">Order Mitra</h3>
					<div class="table-responsive">
						<table class="table table-striped table-bordered" id="tb_datareseller">
							<thead>
								<tr>
									<th class="text-center" style="width: 60px;">No</th>
									<th>Data</th>
									<th>Invoice</th>
								</tr>
							</thead>
							<tbody>
							<?php
								// Include / load file koneksi.php
								include "koneksi.php";
								
								$idmitra = $_SESSION['idmitramarketer'];
								// Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
								$sql = mysqli_query($koneksi, "SELECT DISTINCT mitramarketer.namaagen, ordermarketer.invoice, ordermarketer.payment, 
																ordermarketer.status,ordermarketer.tgl 
														FROM `ordermarketer` 
														INNER JOIN mitramarketer 
														ON mitramarketer.idmitramarketer = ordermarketer.idmitramarketer 
														WHERE ordermarketer.idmitramarketer='$idmitra' 
														AND (ordermarketer.payment = 'Lunas' OR ordermarketer.payment='LUNAS') 
														GROUP BY invoice ORDER BY idorder DESC");
								
								$no = 1;
								while($data2 = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
							?>
								<tr>
									<td style="width: 5%"><?= $no++; ?></td>											
									<td>
										<b><?= $data2['tgl']; ?></b>
										<br>
										DB: <?= $data2['namamitra'];?>
										<br> 
										<b>Payment : <?= $data2['payment']; ?></b>
									</td>
									<td>Inv: 
										<?php if (substr($data2['invoice'],0,1)=="F"): ?>
											<a class="text-primary" href="detailorder_get.php?id=<?= $data2['invoice']; ?>">
										<!-- <?php elseif (strtotime($data2['tgl']) > strtotime('2024-08-26 23:59:59')): ?>
											<a class="text-primary" href="detailorder2.php?id=<?= $data2['invoice']; ?>">	 -->
										<?php else: ?>
											<a class="text-primary" href="detailorder.php?id=<?= $data2['invoice']; ?>">	
										<?php endif ?> 						  		
										#<?= $data2['invoice'];?></a>
										<br>Status : <?= $data2['status']; ?><br>
										<? if ($data2['status'] == 'Sudah Diterima!') : ?>
											<!-- <p class="text-success">Barang Sudah diterima!</p> -->
										<? else : ?>
											<form method="post">
												<input type="hidden" name="id" value="<?= $data2['invoice']; ?>">
												<button class="btn btn-primary btn-xs" name="done">Sudah Terima Barang</button>
											</form>
										<? endif ?>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- Tab Content for Reseller END -->
			</div>
		</div>
    </div>
    <br><br><br><br>
    <!-- Main Content End -->

    <!-- FOOTER -->
    <?
        include 'menubawahstore.php';
    ?>
    <!-- FOOTER END -->
	<?php
		if(isset($_POST["done"])){
			try {
				// Disable safe update mode
				$koneksi->query("SET SQL_SAFE_UPDATES = 0");
		
				$invoiceNilai = $_POST['id'];
				$updateQuery = "UPDATE ordermarketer SET status = 'Sudah Diterima!', payment = 'Lunas' WHERE invoice = '$invoiceNilai'";
				$koneksi->query($updateQuery);
			
				// Optionally, re-enable safe update mode
				$koneksi->query("SET SQL_SAFE_UPDATES = 1");
			
				echo "<script>alert('Berhasil Update barang')</script>";
				echo "<script>Location : transaksi.php</script>";
			} catch (Exception $e) {
				echo "Error: " . $e->getMessage();
			}
		}
	?>
    <!-- PHP SYNTAK -->
    <?
    include 'koneksi.php';
    ?>
    <!-- PHP SYNTAK END -->

    <!-- Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script src="../adminwnj/assets/dist/js/jquery.min.js"></script>
    <script src="../adminwnj/assets/dist/js/bootstrap.min.js"></script>
    <script src="../adminwnj/assets/dist/DataTables/datatables.min.js"></script>


    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_dataagen').DataTable({
                "pageLength": 10,
                "language": {
                "decimal":        "",
                "emptyTable":     "Tidak ada data rekening",
                "info":           "Ditampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty":      "Ditampilkan 0 sampai 0 dari 0 data",
                "infoFiltered":   "(Disaring dari _MAX_ total data)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Tampilkan _MENU_ Data",
                "loadingRecords": "Memuat...",
                "processing":     "Pemrosesan...",
                "search":         "Cari Data:",
                "zeroRecords":    "Data yang dicari tidak ditemukan",
                "paginate": {
                    "first":      "Awal",
                    "last":       "Akhir",
                    "next":       "&#10095;",
                    "previous":   "&#10094;"
                    }
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#tb_datareseller').DataTable({
                "pageLength": 10,
                "language": {
                "decimal":        "",
                "emptyTable":     "Tidak ada data rekening",
                "info":           "Ditampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty":      "Ditampilkan 0 sampai 0 dari 0 data",
                "infoFiltered":   "(Disaring dari _MAX_ total data)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Tampilkan _MENU_ Data",
                "loadingRecords": "Memuat...",
                "processing":     "Pemrosesan...",
                "search":         "Cari Data:",
                "zeroRecords":    "Data yang dicari tidak ditemukan",
                "paginate": {
                    "first":      "Awal",
                    "last":       "Akhir",
                    "next":       "&#10095;",
                    "previous":   "&#10094;"
                    }
                }
            });
        });
    </script>
    <!-- SCRIPT -->

</div>
  
</body>
</html>