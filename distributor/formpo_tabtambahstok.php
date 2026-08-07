<?php
session_start();

include 'koneksi.php';

if (!isset($_SESSION["admin_mitra"])) {
    header('Location: login2.php');
    exit;
}

$idpoproduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$invoice    = $_GET['invoice'] ?? '';
$idadmin    = $_SESSION["admin_mitra"]["idadmin"];

if ($idpoproduk <= 0 || $invoice === '' || !preg_match('/^[A-Za-z0-9\-]+$/', $invoice)) {
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

		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<title>Mitra <?= htmlspecialchars($_SESSION['admin_mitra']['namamitra']) ?>| WNJ.ID </title>

</head>
<body>

<!--================ NAVBARU  =================-->
<div class="container row fixed-top navbaru" >

  <div class="col-2"><a href="listnewpo"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
  <div class="col-8" ><p>PRE ORDER</p></div>
  <div class="col-2"></div>
</div>

<br><br><br><br>

<style>
/* Place the navbar at the bottom of the page, and make it stick */

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

<!--================ NAVBARU END =================-->
   <div class="container">
   <table id="myJudul" class="w3-table-all w3-centered">
<?php
$namapo = $data['namapo'] ?? '';
echo "
    <center> <h4> <b>Formulir Pemesanan " . htmlspecialchars($namapo) . " </b> </h4> </center>";
?>
  </table>
  </div><br><br>

  <div class="container panel panel-default">


            <div class="panel-body">
            <div class="row">
            <div class="col-md-6">

			<form method="POST">

			<div style="padding: 0 15px;">


	<ul class="nav nav-tabs">

<?php
$noo = 1;
	$stmtTabs = $koneksi->prepare("SELECT * FROM bukapo_tab WHERE idpoproduk = ? order by id asc");
	$stmtTabs->bind_param('i', $idpoproduk);
	$stmtTabs->execute();
	$query = $stmtTabs->get_result();
	while ($row = $query->fetch_assoc()) {
?>
<?php if ($noo == 1): ?>
		<li class="active"><a data-toggle="tab" href="#home<?= (int) $row['id']; ?>"  class="nav-item nav-link active"><?= htmlspecialchars($row['nama_tab']); ?></a></li>
		<?php else: ?>
		<li class=""><a data-toggle="tab" href="#home<?= (int) $row['id']; ?>" class="nav-item nav-link"><?= htmlspecialchars($row['nama_tab']); ?></a></li>
		<?php endif ?>
<?php $noo++; ?>
<?php } ?>
	</ul>
		<br>
		<div class="tab-content">
<?php
$no = 1;
	$stmtTabsIsi = $koneksi->prepare("SELECT * FROM bukapo_tab WHERE idpoproduk = ? order by id asc");
	$stmtTabsIsi->bind_param('i', $idpoproduk);
	$stmtTabsIsi->execute();
	$query_isi = $stmtTabsIsi->get_result();
	while ($row_isi = $query_isi->fetch_assoc()) {
		$id_awal  = (int) $row_isi['id_awal'];
		$id_akhir = (int) $row_isi['id_akhir'];
?>
<?php if ($no == 1): ?>
			<div id="home<?= (int) $row_isi['id']; ?>" class="tab-pane fade active show in">
		<?php else: ?>
			<div id="home<?= (int) $row_isi['id']; ?>" class="tab-pane fade ">
		<?php endif ?>

<?php
						$no++;

						$stmtVariant = $koneksi->prepare("SELECT * FROM poproduk
						inner join pokategori on poproduk.idpoproduk=pokategori.idpoproduk
						inner join podetail on pokategori.idpo=podetail.idpo
						where poproduk.idpoproduk = ? and (podetail.idpodetail BETWEEN ? AND ?) order by pokategori.idpo asc");
						$stmtVariant->bind_param('iii', $idpoproduk, $id_awal, $id_akhir);
						$stmtVariant->execute();
						$query_variant = $stmtVariant->get_result();
							while ($row_variant = $query_variant->fetch_assoc()) {
								?>


								<div class="form-group">
								    <label><?= htmlspecialchars($row_variant['variant']); ?></label>
								    <input type="hidden" name="idpodetail[]" value="<?= (int) $row_variant['idpodetail']; ?>">
								    <input type="number" min="0" required name="jmlh[]" class="form-control" style="width:300px;" value=0>
								</div>

				                <?php
				                      } ?>

			</div>
<?php } ?>
		</div>



	        <?php
                			echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";

	                		?>
                    	</form>


			                       <?php
			                        if (isset($_POST["save"])) {
	                                 date_default_timezone_set('Asia/Jakarta');
                                    $waktu = date("H:i:s");

					               $idpodetail = $_POST["idpodetail"] ?? [];
			                       $jmlh       = $_POST["jmlh"]       ?? [];

			                       $jumlah_dipilih = count($idpodetail);
                                    $sql = false;

                                    for ($x = 0; $x < $jumlah_dipilih; $x++) {
                                        $idpodetailX = (int) $idpodetail[$x];
                                        $jmlhX       = isset($jmlh[$x]) ? max(0, (int) $jmlh[$x]) : 0;

                                        $stmtVar2 = $koneksi->prepare("SELECT podetail.harga, podetail.idpo FROM podetail WHERE podetail.idpodetail = ?");
                                        $stmtVar2->bind_param('i', $idpodetailX);
                                        $stmtVar2->execute();
                                        $data_variant = $stmtVar2->get_result()->fetch_assoc();
                                        if (!$data_variant) {
                                            continue;
                                        }
                                        $harga = $data_variant['harga'];
                                        $idpo  = $data_variant['idpo'];
                                        $total = $jmlhX * $harga;

						$stmtSisa = $koneksi->prepare("SELECT stok from pokategori where idpo = ?");
						$stmtSisa->bind_param('i', $idpo);
						$stmtSisa->execute();
						$sisa = $stmtSisa->get_result()->fetch_assoc();

                                             if ($jmlhX > 0 && ($sisa['stok'] ?? 0) > $jmlhX) {
			$stmtCek = $koneksi->prepare("SELECT idpodetail, invoice FROM pomitra WHERE idpodetail = ? and invoice = ? and idmitra = ?");
			$stmtCek->bind_param('iss', $idpodetailX, $invoice, $idadmin);
			$stmtCek->execute();
			$datacocok = $stmtCek->get_result()->num_rows;

			if ($datacocok >= 1) {
			      $stmtUpd = $koneksi->prepare("UPDATE pomitra set jumlah = jumlah + ? WHERE idpodetail = ? and invoice = ? and idmitra = ?");
			      $stmtUpd->bind_param('iiss', $jmlhX, $idpodetailX, $invoice, $idadmin);
			      $sql = $stmtUpd->execute();

			      $stmtUpdStok = $koneksi->prepare("UPDATE pokategori set stok = stok - ? where idpo = ?");
			      $stmtUpdStok->bind_param('ii', $jmlhX, $idpo);
			      $stmtUpdStok->execute();
			} else {
				$invoiceBaru = 'D' . $idpoproduk . '-' . $idadmin;
				$stmtIns = $koneksi->prepare("INSERT into pomitra (idpomitra,idmitra,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
				(null,?,?,?,?,?,?,?,'Belum DP',NOW(),?)");
				$stmtIns->bind_param('siiiidss', $idadmin, $idpoproduk, $idpo, $idpodetailX, $jmlhX, $total, $invoiceBaru, $waktu);
				$sql = $stmtIns->execute();

				$stmtUpdStok = $koneksi->prepare("UPDATE pokategori set stok = stok - ? where idpo = ?");
				$stmtUpdStok->bind_param('ii', $jmlhX, $idpo);
				$stmtUpdStok->execute();
			}
}
        					               }
if ($sql) {
echo "<script>alert('data berhasil dikirim');</script>";
echo "<script>location='datapo?id=$idpoproduk';</script>";
} else {
echo "<script>alert('data gagal dikirim');</script>";
echo "<script>location='datapo?id=$idpoproduk';</script>";
}

			                                    }

					                    ?>

		</div>
	</div>
</div>	</div>
</div>
