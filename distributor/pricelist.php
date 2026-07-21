<?php 
session_start();

include 'koneksi.php'; 
include 'floatingbutton.php';
include 'assets/components/Sessions/sesDistri.php';


// if(!isset($_SESSION["admin_mitra"])){
//   echo "<script>alert('anda harus login terlebih dahulu');</script>";
//    echo "<script>location='login2.php';</script>";
//    header('location:login2.php');
//    exit();
// }

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Mitra <?php echo $_SESSION['namamitra']; ?>| Wanoja </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	</head>
  <body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->
    
    <!-- MAIN CONTENT -->
    <div class="container pt-3">
        <div class="text-center mb-3">
            <a class="btn btn-primary" href="pdfpricelist.php" target="_blank"><i class="fas fa-file-export"></i> Export</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover pt-2" id="tb_store">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width:25%">Nama Artikel</th>
                        <th style="width:15%">Konsumen</th>
                        <th style="width:15%">Distributor</th>
                        <th style="width:15%">Agen</th>
                        <th style="width:15%">Reseller</th>
                        <th style="width:15%">Marketer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ambil=$koneksi->query("SELECT * FROM pricelist order by namaartikel asc "); 
                    $no = 1;
                    while($distributor=$ambil->fetch_assoc()){
                    ?>
                    <tr>
                        <?php $marketer=$distributor['harga_ecer_d']*90/100;
                        ?>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $distributor['namaartikel'];?></td>
                        <td>Rp. <?php echo number_format($distributor['harga_ecer_d']);?></td>
                        <td>Rp. <?php echo number_format($distributor['harga_d']);?></td>
                        <td>Rp. <?php echo number_format($distributor['harga_a']);?></td>
                        <td>Rp. <?php echo number_format($distributor['harga_ecer_a']);?></td>
                        <td>Rp. <?php echo number_format($marketer);?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- MAIN CONTENT END -->

    <br><br><br><br>

    <!-- FOOTER -->
    <?php include "menubawah.php"; ?>		
    <!-- FOOTER END -->

    <!-- PHP -->
    <?php include "settingdatatables.php"; ?>   
    <!-- PHP END -->

    <!-- SCRIPT -->
    <!-- SCRIPT END -->
</body>
</html>

