<?php
include "koneksi.php";
$id=$_GET["id"];
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
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

          <!-- Content Row -->
                        <?php 
                           // $subtotal=0;
                            $datapoku=$koneksi->query("SELECT * from poproduk where idpoproduk='$id'");
                            //$no=1;
                         
                           $tampilkanuy=$datapoku->fetch_assoc();
                            ?>         

<h3><strong><?php echo $tampilkanuy['namapo']; ?></strong></h3>
      
        
	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th style="width:1px">No</th>
						<th>Variant</th>
						<th>Jumlah</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $subtotal=0;
                            $datapo=$koneksi->query("SELECT podetail.variant, sum(pomaximus.jumlah) as jumlah FROM poproduk INNER JOIN pomaximus INNER JOIN pokategori INNER JOIN podetail on poproduk.idpoproduk=pomaximus.idpoproduk and pokategori.idpo=pomaximus.idpo and podetail.idpodetail=pomaximus.idpodetail where poproduk.idpoproduk='$id' GROUP by podetail.variant");
                            $no=1;
                         
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan['variant']; ?>
                          </td>
                           <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>
							
                       	<?php $subtotal=$subtotal+$tampilkan['jumlah'] ?>
                        </tr>
                        <?php } ?>
                                    <tr>
                            <td colspan="2">
                              <b>  Total</b>
                            </td>
                            <td>
                            <b><?php echo $subtotal; ?> </b> 
                            </td>    
                        </tr>
                      </tbody>
                    </table><br><br>
                    
                    <p><strong>Summary</strong></p>
                    <div class="table-responsive">
				<table class="table table-bordered">
					<tr>
					    <th style="width:1px">No</th>
						<th>Variant</th>
						<th>Jumlah</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                            $total=0;
                            // $namapo2=$_GET["namapo"];
        
                            $datapo2=$koneksi->query("SELECT pokategori.namakategori, sum(pomaximus.jumlah) as jumlah FROM poproduk INNER JOIN pomaximus INNER JOIN pokategori INNER JOIN podetail on poproduk.idpoproduk=pomaximus.idpoproduk and pokategori.idpo=pomaximus.idpo and podetail.idpodetail=pomaximus.idpodetail where poproduk.idpoproduk='$id' GROUP by pokategori.namakategori");
                            $no2=1;
                            while($tampilkan2=$datapo2->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no2++; ?>
                        </td>     
                          <td>
                            <?php echo $tampilkan2['namakategori']; ?>
                          </td>
                           <td>
                            <?php echo $tampilkan2['jumlah']; ?>
                          </td>
                          
							
                        <?php $total =$total+$tampilkan2['jumlah']; ?>
							
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2">
                               <b> Total</b>
                            </td>
                            <td>
                            <b><?php echo $total; ?></b>
                            </td>    
                        </tr>
                      </tbody>
                    </table>
                    
                    <script>
                    window.print();
                    </script>