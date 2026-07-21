<?php
    session_start();

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idmitraagen = $_SESSION["idmitraagen"];
    $jenis_po = $_GET['jenis'];

    $query_po = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'");
    $sql = $query_po->fetch_assoc();
    $namapo = $sql['namapo'];
	$query = $koneksi->query("SELECT COUNT(*) as jumlah,
                    poproduk.idpoproduk,
                    poproduk.namapo,
                    poproduk.status, 
                    pomitra.invoice
                    FROM poproduk 
                    inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                    WHERE poproduk.idpoproduk='$idpoproduk' 
                    AND pomitra.idmitraagen='$idmitraagen'");
    $data = $query->fetch_assoc();
    $inv = $data['invoice'];
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
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
	<title>Mitra <?php echo $_SESSION['mitraagen']['namaagen']; ?>| Wanoja </title>
</head>
<body>
	<!--================ NAVBARU  =================-->
	<div class="container row fixed-top navbaru" >
		<div class="col-2"><a href="listpreorder.php"><i class="glyphicon glyphicon-chevron-left"></i></a></div>
		<div class="col-8" ><p>PRE ORDER</p></div>
		<div class="col-2"></div>
	</div>
	<br><br><br><br>
	<style>
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
				$namapo			= $data['namapo'];
				$id				= $data['idpoproduk'];
				if($data['jumlah']<1){
					echo "
						<center> <h4> <b>Formulir Pemesanan $namapo </b> </h4> </center>";
				} else {
					echo "
						<center><b>Anda sudah mengisi Formulir $namapo , Klik Tombol Dibawah ini Jika ingin melihat atau merevisi Invoice, 
													</b><br><br>
						<a class='btn btn-info' href='datapo.php?id=$id&invoice=$inv'> INVOICE</a> </center>";
				}
			?>
		</table>
	</div>
	<br><br>
	<div class="container panel panel-default">
		<div class="panel-body">
            <div class="row">
				<div class="col-md-6">
					<form method="POST">		
						<div style="padding: 0 15px;">
							<ul class="nav nav-tabs">
								<?php
									$noo	= 1;
									$sql 	= "SELECT * FROM bukapo_tab 
												WHERE idpoproduk = '$idpoproduk' order by id asc";
									$query 	= $koneksi->query($sql);
									while($row = $query->fetch_assoc()){
								?>
									<?php if ($noo==1): ?>
										<li class="active"><a data-toggle="tab" href="#home<?= $row['id']; ?>"  class="nav-item nav-link active"><?= $row['nama_tab']; ?></a></li>
									<?php else: ?>				
										<li class=""><a data-toggle="tab" href="#home<?= $row['id']; ?>" class="nav-item nav-link"><?= $row['nama_tab']; ?></a></li>
									<?php endif ?>
									<?php $noo++; ?>		
								<?php } ?>
							</ul>
							<br>
							<div class="tab-content">
								<?php
									$no			= 1;
									$sql_isi 	= "SELECT * FROM bukapo_tab 
													WHERE idpoproduk = '$idpoproduk' order by id asc";
									$query_isi 	= $koneksi->query($sql_isi);
									while($row_isi = $query_isi->fetch_assoc()){
										$id_awal 	= $row_isi['id_awal'];
										$id_akhir 	= $row_isi['id_akhir'];		
								?>	
									<?php if ($no==1): ?>
										<div id="home<?= $row_isi['id']; ?>" class="tab-pane fade active show in">
									<?php else: ?>
										<div id="home<?= $row_isi['id']; ?>" class="tab-pane fade ">					
									<?php endif ?>		
										<?php
											$no++;
											$idpoproduk 	= $_GET['id'];
										
											$sql_variant 	= "SELECT * FROM poproduk 
																inner join pokategori on poproduk.idpoproduk=pokategori.idpoproduk
																inner join podetail on pokategori.idpo=podetail.idpo
																where poproduk.idpoproduk='$idpoproduk' and (podetail.idpodetail BETWEEN '$id_awal' AND '$id_akhir') order by pokategori.idpo asc";
											$query_variant 	= $koneksi->query($sql_variant);
											while($row_variant = $query_variant->fetch_assoc()){
										?>
											<div class="form-group">
												<label><?php echo $row_variant['variant']; ?></label>
												<input type="hidden" name="idpo[]" value="<?php echo $row_variant['idpo']; ?>">
												<input type="hidden" name="idpodetail[]" value="<?php echo $row_variant['idpodetail']; ?>">
												<input type="number" min="0" required name="jmlh[]" class="form-control" style="width:300px;" value=0>
												<input type="hidden" name="harga[]" value="<?php echo $row_variant['harga']; ?>">
											</div>	
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
						<?php 
							if($data['jumlah']<1){
								echo "<button type='submit' class='btn btn-primary' name='save'>Kirim</button>";
							}else{
								echo "# ";
							}
						?>
					</form>
					<?php
						if(isset($_POST["save"])){
							include "koneksi.php";
							date_default_timezone_set('Asia/Jakarta');
							$today 			= date("s");
							$waktu 			= date("H:i:s");
							$idmitraagen	= $_SESSION["idmitraagen"];
							$idpo			= $_POST["idpo"];
							$idpodetail		= $_POST["idpodetail"];
							$jmlh			= $_POST["jmlh"];
							$harga			= $_POST["harga"];
								
							$jumlah_dipilih = count($jmlh);
							$subtotal		= 0;  
							$total			= 0;
							$jmlhakhir		= 0;

							if (isset($_GET['invoice'])) {
                                $invoice = $_GET['invoice'];
                            } else {  
                                // Fungsi untuk menghasilkan angka acak dengan panjang tertentu
                                function generateAngkaAcak($length) {
                                    $angka_acak = '';
                                    for ($i = 0; $i < $length; $i++) {
                                    // Menggunakan mt_rand untuk angka acak dari 0 hingga 9
                                    $angka_acak .= mt_rand(0, 9);
                                    }
                                    return $angka_acak;
                                }
                                // Menghasilkan angka acak dengan panjang minimal 7 dan maksimal 7 angka
                                $angka_acak = generateAngkaAcak(5);
                                $invoice = 'A' . $idpoproduk . '-' . $idmitraagen . $angka_acak;
                            }
                                    
							for($x=0;$x<$jumlah_dipilih;$x++){
								$total		= $jmlh[$x]*$harga[$x];
								$tot		= $total;
								$jmlhakhir	+= $jmlhakhir+$jmlh[$x];
								$tot		= 0;

								$koneksi->query("insert into pomitra (idpomitra,idmitraagen,idpoproduk,idpo,idpodetail,jumlah,total,invoice,status,tgl,waktu) values
								(null,'$idmitraagen','$idpoproduk','$idpo[$x]','$idpodetail[$x]','$jmlh[$x]','$total','$invoice','Belum Acc DB',NOW(),'$waktu')");

							}
							echo "<script>alert('data berhasil dikirim');</script>";
							echo "<script>location='datapo.php?id=$idpoproduk&invoice=$invoice';</script>";
						}
         
					?>		                    
				</div>
			</div>
		</div>	
	</div>
</div>
