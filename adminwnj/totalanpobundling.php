<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Data Total PO Bundling.xls");
	include "koneksi.php";
	$idpoproduk = $_GET["id"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Laporan PO</title>
	<!--   <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<link href="css/sb-admin-2.min.css" rel="stylesheet">
	-->
</head>
	<!-- Content Row -->
	<p><strong>Totalan PO</strong></p>
        <table border="1">
			<thead>
				<tr>
					<th style="width:1px">No</th>
					<th>Nama PO</th>
					<th>Nama CS</th>
					<th>Distributor</th>
					<th>Kode DB</th>
					<th>Agen</th>
					<th>Reseller</th>
					<th>Marketer</th>
					<?php if ($idpoproduk == 328) : ?>
						<th>Variant</th>
						<th>Custom</th>
					<?php endif; ?>
					<th>Inv</th>
					<th>Qty</th>
					<!-- <th>Jumlah</th> -->
					<th>Total</th>
					<th>diskon</th>
					<th>jumlah</th>
					<th>Ongkir</th>
					<th>Dropship</th>
					<th>Status</th>
				</tr>
			</thead> 
         	<tbody>
				<?php
					$datapo2 = $koneksi->query("SELECT poproduk.namapo, admin_mitra.idadmin, admin_mitra.namamitra as db,
													mitraagen.namaagen as agen, mitrareseller.namaagen as reseller, mitramarketer.namaagen as marketer,
													pomitra.invoice,sum(pomitra.jumlah) as qty, pomitra.status,sum(pomitra.total) as total,
													admin_mitra_cs.namacs, pomitra.custom
												FROM `pomitra` 
												LEFT JOIN mitraagen on mitraagen.idmitraagen = pomitra.idmitraagen 
												LEFT JOIN mitrareseller on mitrareseller.idmitrareseller = pomitra.idmitrareseller 
												LEFT JOIN mitramarketer on mitramarketer.idmitramarketer = pomitra.idmitramarketer 
												LEFT JOIN admin_mitra 
													on (mitraagen.idadmin = admin_mitra.idadmin 
														or mitrareseller.idadmin = admin_mitra.idadmin 
														or mitramarketer.idadmin = admin_mitra.idadmin 
														or pomitra.idmitra = admin_mitra.idadmin) 
												LEFT JOIN admin_mitra_cs on admin_mitra_cs.idadmin = admin_mitra.idadmin
												INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
												WHERE pomitra.idpoproduk = '$idpoproduk' and pomitra.jumlah > 0 and 
												pomitra.status <> 'Belum Acc DB' GROUP BY pomitra.invoice ORDER BY pomitra.tgl ASC");
					$no = 1;
					while($tampilkan2 = $datapo2->fetch_assoc()){
				?>
				<?php 
					$invoice 		= $tampilkan2['invoice'];
					$sqlharga2 		= "SELECT MAX(total) as totalnya,invoice, idpoproduk FROM `pomitra` WHERE invoice = '$invoice'";
					$queryharga2 	= $koneksi->query($sqlharga2);
					$sisaharga2 	= $queryharga2->fetch_assoc(); 
					$stokharga2 	= $sisaharga2['totalnya']; 
					$idpoproduk 	= $sisaharga2['idpoproduk']; 

					$sum_jumlah		= 0;
					$sum_total		= 0;
					$sql 			= mysqli_query($koneksi, "SELECT podetail.variant,
																	podetail.harga,
																	pomitra.jumlah
																FROM pomitra
																JOIN podetail on podetail.idpodetail=pomitra.idpodetail 
																WHERE pomitra.invoice = '$invoice' 
																AND pomitra.jumlah > 0");
										
					while($data = mysqli_fetch_array($sql)){
						$sum_jumlah+= $data['jumlah'];
						$sum_total += $data['jumlah']*$data['harga'];
						$variant = $data['variant'];
					}

					$sql_kirim 		= "SELECT ongkir,dropship FROM podropship WHERE invoice = '$invoice'";
					$query_kirim 	= $koneksi->query($sql_kirim);
					$biaya_kirim 	= $query_kirim->fetch_assoc();
					$jumlah 		= $sum_jumlah/2;
					$bundling   	= 10000;
					$diskonBundle 	= $bundling*$jumlah;
					$total      	= $sum_total-$diskonBundle;
					$ongkir 		= $biaya_kirim['ongkir'];
					$dropship 		= $biaya_kirim['dropship'];
				?>                             
					<tr>
						<td><?php echo $no++; ?></td>     
                        <td><?php echo $tampilkan2['namapo']; ?></td>
                        <td><?php echo $tampilkan2['namacs']; ?></td>
						<td><?php echo $tampilkan2['db']; ?></td>
                        <td><?php echo $tampilkan2['idadmin']; ?></td>
                        <td><?php echo $tampilkan2['agen']; ?></td>
                        <td><?php echo $tampilkan2['reseller']; ?></td>
                        <td><?php echo $tampilkan2['marketer']; ?></td>
                        <?php if ($idpoproduk == 328) : ?>
                          <td><?php echo $variant; ?></td>
                          <td><?php echo $tampilkan2['custom']; ?></td>
                        <?php endif; ?>
                        <td><?php echo $tampilkan2['invoice']; ?></td>
                       	<td>
							<?php if ($idpoproduk==186): ?>
                          		(<?= $sum_jumlah/12; ?> Seri)
							<? elseif ($idpoproduk == 339) : ?>
								<?= $sum_jumlah / 3; ?>
							<? else : ?>
								<?php echo $sum_jumlah; ?>
							<?php endif ?>
                        </td>
                        <td>
							<?php if ($idpoproduk=="120"): ?>
								<?= $stokharga2;?>
							<?php elseif ($idpoproduk === '339'): ?>
								<?= $sum_total / 3; ?>
							<?php elseif ($idpoproduk === '332') : ?>
								<?php if ($tampilkan2['custom'] === 'Set') : ?>
									<?= ($sum_jumlah / 3) * 100000 ?>
								<?php else : ?>
									<?= $sum_total ?>
								<?php endif; ?>
							<?php else: ?>
								<?php echo $sum_total; ?>
							<?php endif ?>                          
                        </td>
						<td><?= $diskonBundle ?></td>
						<td><?= $total; ?></td>
						<td><?= $ongkir; ?></td>
						<td><?= $dropship; ?></td>
                        <td>
                          	<?php echo $tampilkan2['status']; ?>
                        </td>
					</tr>
				<?php } ?>
        	</tbody>
        </table>
		