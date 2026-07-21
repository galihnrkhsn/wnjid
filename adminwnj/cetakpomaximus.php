<?php
include "koneksi.php";
$invoice=$_GET["invoice"];
$sum=0;

  $datamitra=$koneksi->query("SELECT admin_mitra.namamitra,poproduk.namapo,mitraagen.namaagen as agen,mitrareseller.namaagen as reseller,mitramarketer.namaagen as marketer,pomaximus.invoice FROM pomaximus LEFT JOIN mitraagen on pomaximus.idmitraagen=mitraagen.idmitraagen LEFT JOIN mitrareseller on pomaximus.idmitrareseller=mitrareseller.idmitrareseller LEFT JOIN mitramarketer on pomaximus.idmitramarketer=mitramarketer.idmitramarketer LEFT JOIN admin_mitra on pomaximus.idadmin=admin_mitra.idadmin or mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin INNER JOIN poproduk on pomaximus.idpoproduk=poproduk.idpoproduk WHERE pomaximus.invoice='$invoice' and pomaximus.jumlah>0");
                            $tampilnama=$datamitra->fetch_assoc()
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

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

          <!-- Content Row -->
<br>
<br>
<br>

<center><h3><strong><?php echo $tampilnama['namapo']; ?></strong></h3></center>
<center><h5>Invoice <?php echo $invoice; ?></h5></center>

    <strong> Nama DB : <?php echo $tampilnama['namamitra']; ?> </strong><br>
    <strong> Nama SubDB : <?php echo $tampilnama['agen']; ?> <?php echo $tampilnama['reseller']; ?>  <?php echo $tampilnama['marketer']; ?></strong>

    <div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<th>Qty</th>
						<th>Nama Barang</th>
					    <th>Satuan</th>
					    <th style:"text-align:center">Jumlah</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                         // $invoice=$_GET["invoice"];
                          	$jumlah=0;
					        $subtotal=0;
        
                            $datapo=$koneksi->query("SELECT poproduk.namapo,pokategori.namakategori,podetail.variant,pomaximus.idpomaximus,pomaximus.jumlah,pomaximus.invoice,pomaximus.total,podetail.harga 
					FROM poproduk inner JOIN pokategori inner join podetail inner join pomaximus on poproduk.idpoproduk=pomaximus.idpoproduk and pokategori.idpo=pomaximus.idpo and podetail.idpodetail=pomaximus.idpodetail WHERE pomaximus.invoice='$invoice' and pomaximus.jumlah>0");
                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>
                           <td>
                            <?php echo $tampilkan['variant']; ?>
                          </td>
                           <td>
                           Rp. <?php echo number_format($tampilkan['harga']); ?>
                          </td>
                          <td>
                            Rp. <?php echo number_format($tampilkan['total']); ?>
                          </td>
            
                        <?php
							$sum=$sum+$tampilkan['jumlah'];
                            $idpomaximus=array($tampilkan['idpomaximus']);						
							$jumlah=$jumlah+$tampilkan['total'];
							$invoice=$tampilkan['invoice'];
							//$subtotal=$subtotal+$jumlah;
							?>
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                 
				             <p align="right">Total Qty : <?php echo $sum; ?> </p> 
				            <p align="right">JUMLAH  Rp. <?php echo number_format($jumlah); ?> </p>
				            
				            <?php $inv1=substr($invoice,0,1);
                                 if($inv1=='D'){
                                  $potongan=35;
                                 }
                                 if($inv1=='A'){
                                    $potongan=25;
                                   }
                                   if($inv1=='R'){
                                    $potongan=15;
                                   }
                                   if($inv1=='M'){
                                    $potongan=10;
                                   }
                                  $diskon=$potongan/100*$jumlah;
				                  $subtotal=$jumlah-$diskon;   
				                  $payment1= $subtotal*30/100;
				                  $payment2= $subtotal*30/100;
				                  $payment3= $subtotal*40/100;
                                  $dp= $subtotal*50/100;
				                  
				            ?>
				            <p align="right">Diskon <?php echo $potongan; ?>% Rp. -<?php echo number_format($diskon); ?> </p><br>      
				            <p align="right">TOTAL  Rp. <?php echo number_format($subtotal); ?> </p>
				            <hr>
				            <!--<p align="right">Payment 1 : Rp. <?php echo number_format($payment1); ?> </p>
				            <p align="right">Payment 2 : Rp. <?php echo number_format($payment2); ?> </p>
				            <p align="right">Payment 3 : Rp. <?php echo number_format($payment3); ?> </p>-->
                            <p align="right">DP 50% : Rp. <?php echo number_format($dp); ?> </p>

                            <script>
window.print();
</script>
