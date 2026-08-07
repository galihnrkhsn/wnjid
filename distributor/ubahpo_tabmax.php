<?php
session_start();

include 'koneksi.php';
include 'floatingbutton.php';

if (!isset($_SESSION["admin_mitra"])) {
    header('Location: login.php');
    exit;
}

$idpoproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$idadmin    = $_SESSION["admin_mitra"]["idadmin"];

if ($idpoproduk <= 0) {
    header('Location: listnewpo.php');
    exit;
}

$stmtOwn = $koneksi->prepare("SELECT COUNT(*) as jumlah, poproduk.idpoproduk, poproduk.namapo, poproduk.status
                               FROM poproduk
                               inner join pomitra on poproduk.idpoproduk = pomitra.idpoproduk
                               WHERE poproduk.idpoproduk = ? AND pomitra.idmitra = ?");
$stmtOwn->bind_param('is', $idpoproduk, $idadmin);
$stmtOwn->execute();
$data = $stmtOwn->get_result()->fetch_assoc();

if (!$data || $data['jumlah'] == 0) {
    header('Location: listnewpo.php');
    exit;
}
?>

<title>Mitra <?= htmlspecialchars($_SESSION['admin_mitra']['namamitra']) ?>| WNJ.ID </title>
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



	</head>
	<body>
<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listnewpo.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

.navbaru {

    background: #eee center center;
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

		<div class="container" align="center">


			<p align="center"><strong>SALES INVOICE</strong></p>
			<p align="center"><strong><?= htmlspecialchars($data['namapo'] ?? '') ?></strong></p><br>
			<p align="left">Nama Mitra  : <?= htmlspecialchars($_SESSION["admin_mitra"]["namamitra"]) ?> </p>
			<p align="left">Alamat  : <?= htmlspecialchars($_SESSION["admin_mitra"]["alamat"]) ?> </p>

			<?php
					$stmtInvoice = $koneksi->prepare("SELECT pomitra.invoice FROM pomitra WHERE pomitra.idmitra = ? and pomitra.idpoproduk = ?");
					$stmtInvoice->bind_param('si', $idadmin, $idpoproduk);
					$stmtInvoice->execute();
					$data2   = $stmtInvoice->get_result()->fetch_assoc();
					$invoice = $data2['invoice'] ?? '';
					?>
			<p align="left">No Invoice  : <?= htmlspecialchars($invoice) ?> </p>

			 <b>	Sisa Stock Kain: </b>
 <div class="row">
              <?php
			$stmtStok = $koneksi->prepare("SELECT * from pokategori where idpoproduk = ? ORDER BY namakategori");
			$stmtStok->bind_param('i', $idpoproduk);
			$stmtStok->execute();
			$query = $stmtStok->get_result();
              while ($stok = $query->fetch_assoc()) {
                ?>

<div class="col-2">
   <?= htmlspecialchars($stok['namakategori']) ?>
</div>
<div class="col-2">
    (<?= (int) $stok['stok'] ?>)
</div>
<br>
<?php } ?>
</div>

			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Ubah Qty</th>
						<th>Qty</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>
					    <th>Jumlah</th>
					</tr>
					<?php
					$no = 1;

					$stmtRows = $koneksi->prepare("SELECT poproduk.namapo,
													pokategori.namakategori,
													podetail.variant,
													pomitra.idpomitra,
													pomitra.idpo,
													pomitra.jumlah,
													pomitra.invoice,
													pomitra.total,
													podetail.harga
					FROM poproduk
					inner JOIN pokategori
					inner join podetail
					inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk
					and pokategori.idpo=pomitra.idpo
					and podetail.idpodetail=pomitra.idpodetail
					WHERE pomitra.idmitra = ?
					and pomitra.idpoproduk = ?");
					$stmtRows->bind_param('si', $idadmin, $idpoproduk);
					$stmtRows->execute();
					$rowsResult = $stmtRows->get_result();

					while ($row = $rowsResult->fetch_assoc()) {
					?>
						<tr>
							<td class="align-middle"><?= $no++; ?></td>
							<form method="POST">
								<input type="hidden" name="idpo" value="<?= (int) $row['idpo'] ?>">
								<input type="hidden" name="idpomitra" value="<?= (int) $row['idpomitra'] ?>">
								<input type="hidden" name="jumlahsebelum" value="<?= (int) $row['jumlah'] ?>">
							<td class="align-middle">
								<input type="number" min="0" name="jmlh" style="width: 80px;" class="form-control">
								<button type="submit" class="btn btn-primary" name="tambah">+</button>
								<button type="submit" class="btn btn-danger" name="kurang">-</button>
							</td>
		                    </form>
							<td class="align-middle"><?= (int) $row['jumlah'] ?></td>
							<td class="align-middle"><?= htmlspecialchars($row['variant']) ?></td>
							<td class="align-middle"><?= htmlspecialchars($row['harga']) ?></td>
							<td class="align-middle"><?= htmlspecialchars($row['total']) ?></td>
											<?php
					}

					?>
		                     <?php
                        if (isset($_POST["tambah"]) || isset($_POST["kurang"])) {
                            $idpomitra = isset($_POST['idpomitra']) ? (int) $_POST['idpomitra'] : 0;
                            $jmlh      = isset($_POST['jmlh']) ? max(0, (int) $_POST['jmlh']) : 0;

                            // Ambil harga, idpo & kepemilikan langsung dari database, JANGAN percaya nilai dari form
                            $stmtRow = $koneksi->prepare("SELECT pomitra.jumlah, pomitra.invoice, pomitra.idmitra, pomitra.idpoproduk,
                                                                  podetail.harga, podetail.idpo
                                                           FROM pomitra
                                                           INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                           WHERE pomitra.idpomitra = ?");
                            $stmtRow->bind_param('i', $idpomitra);
                            $stmtRow->execute();
                            $rowData = $stmtRow->get_result()->fetch_assoc();

                            if (!$rowData || $rowData['idmitra'] !== $idadmin || (int) $rowData['idpoproduk'] !== $idpoproduk) {
                                echo "<script>alert('Data tidak valid atau bukan milik anda.');</script>";
                                echo "<script>location='ubahpo_tabmax?id=$idpoproduk';</script>";
                                exit;
                            }

                            $harga = $rowData['harga'];
                            $idpo  = $rowData['idpo'];
                        }

                        if (isset($_POST["tambah"])) {
                            $stmtJumlah = $koneksi->prepare("SELECT SUM(jumlah) as sjmlh FROM pomitra WHERE invoice = ? and idpo = ?");
                            $stmtJumlah->bind_param('si', $invoice, $idpo);
                            $stmtJumlah->execute();
                            $sjumlah    = $stmtJumlah->get_result()->fetch_assoc();
                            $sum_jumlah = $sjumlah['sjmlh'] ?? 0;

                            if ($sum_jumlah + $jmlh > 3) {
                                echo "<script>alert('Jumlah Dress atau Khimar melebihi 3 Pcs');</script>";
                                echo "<script>location='ubahpo_tabmax?id=$idpoproduk';</script>";
                            } else {
                                $stmtStok2 = $koneksi->prepare("SELECT stok from pokategori where idpo = ?");
                                $stmtStok2->bind_param('i', $idpo);
                                $stmtStok2->execute();
                                $sisa = $stmtStok2->get_result()->fetch_assoc();

                                if (($sisa['stok'] ?? 0) >= $jmlh) {
                                    $stmtUpd1 = $koneksi->prepare("UPDATE pomitra set jumlah = jumlah + ? where idpomitra = ? and idmitra = ?");
                                    $stmtUpd1->bind_param('iis', $jmlh, $idpomitra, $idadmin);
                                    $stmtUpd1->execute();

                                    $stmtUpd2 = $koneksi->prepare("UPDATE pomitra set total = jumlah * ? where idpomitra = ? and idmitra = ?");
                                    $stmtUpd2->bind_param('dis', $harga, $idpomitra, $idadmin);
                                    $stmtUpd2->execute();

                                    $stmtUpd3 = $koneksi->prepare("UPDATE pokategori set stok = stok - ? where idpo = ?");
                                    $stmtUpd3->bind_param('ii', $jmlh, $idpo);
                                    $stmtUpd3->execute();

                                    echo "<script>alert('data berhasil diubah');</script>";
                                    echo "<script>location='ubahpo_tabmax?id=$idpoproduk';</script>";
                                } else {
                                    echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
                                    echo "<script>location='ubahpo_tabmax?id=$idpoproduk';</script>";
                                }
                            }
                        } elseif (isset($_POST["kurang"])) {
                            $jumlahsebelum = isset($_POST["jumlahsebelum"]) ? (int) $_POST["jumlahsebelum"] : 0;
                            $subjumlah     = $jumlahsebelum - $jmlh;
                            if ($subjumlah < 0) {
                                echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
                                echo "<script>location='ubahpo_tabmax.php?id=$idpoproduk';</script>";
                            } else {
                                $stmtUpd1 = $koneksi->prepare("UPDATE pomitra set jumlah = jumlah - ? where idpomitra = ? and idmitra = ?");
                                $stmtUpd1->bind_param('iis', $jmlh, $idpomitra, $idadmin);
                                $stmtUpd1->execute();

                                $stmtUpd2 = $koneksi->prepare("UPDATE pomitra set total = jumlah * ? where idpomitra = ? and idmitra = ?");
                                $stmtUpd2->bind_param('dis', $harga, $idpomitra, $idadmin);
                                $stmtUpd2->execute();

                                $stmtUpd3 = $koneksi->prepare("UPDATE pokategori set stok = stok + ? where idpo = ?");
                                $stmtUpd3->bind_param('ii', $jmlh, $idpo);
                                $stmtUpd3->execute();

                                echo "<script>alert('data berhasil diubah');</script>";
                                echo "<script>location='ubahpo_tabmax?id=$idpoproduk';</script>";
                            }
                        }
                        ?>


						</tr>

				</table><br>
<a class="btn btn-primary" href="datapo.php?id=<?= $idpoproduk ?>">Simpan</a>
		</div>
		</div>
	</body>
</html>
