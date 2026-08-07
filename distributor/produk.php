<?php
	session_start();

	include 'koneksi.php';
	include 'assets/components/Sessions/sesDistri.php';

	$idmitra = $_SESSION["idadmin"];
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Store | WNJ.ID</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	</head>
	<body>
		<!-- NAVBAR -->
		<?php include "assets/components/Navbar/navbar2.php"; ?>
    	<!-- NAVBAR END -->
		
		<div class="container my-3">
			<div class="row">				
				<?php
					$query = $koneksi->query("SELECT products.*, variants.*, folder.id AS idfolder, folder.name AS nama_folder
													FROM products
													INNER JOIN variants ON variants.idproducts = products.id
													INNER JOIN folder ON variants.folder = folder.id
											");
					while ($data = $query->fetch_assoc()) {
				?>
					<div class="col-6 col-sm-2">
						<div class="card">
							<img src="../image/produk/<?= $data['nama_folder'] ?>/<?= $data['foto'] ?>" loading="lazy">
							<div class="card-body">
								<div class="card-title">
									<a href="" class="text-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">
										<?= $data['namaproduk'] ?> <?= $data['variant'] ?> Sz <?= $data['size'] ?>
									</a>
									<br>
									<a href="add_chart2.php?namaproduk=<?= urlencode($data['namaproduk']) ?>&id=<?= $data['idproducts'] ?>&harga=<?= $data['harga'] ?>&variant=<?= $data['id'] ?>" class="btn btn-primary">Beli</a>
								</div>
								<div><p>(<?= $data["stock"] ?>)</p></div>
								<div class="card-text">Rp. <?= number_format($data['harga']) ?></div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>

		<!-- FOOTER -->
		<?php include 'menubawah.php';?>
		<!-- FOOTER END -->

		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
	</body>
</html>