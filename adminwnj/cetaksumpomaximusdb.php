<?php 
session_start();

include 'koneksi.php'; 
?>

<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data PO Maximus PerDB.xls");
?>

 <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

          <!-- Content Row -->
         

<h3><strong>Totalan PO DB</strong></h3>

<table class="table table-striped" id="tbslider">
                      <thead>
                        <tr>
                            <th>
                                No
                            </th>    
                          <th>
                            Nama PO
                          </th>          
                          <th>
                           Nama Mitra
                          </th>
                          <!-- <th>
                           Invoice
                          </th> -->
                              <th>
                           Jumlah Qty 
                          </th>
                              <th>
                           Total Harga
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
                             $datapo=$koneksi->query("SELECT poproduk.namapo,admin_mitra.namamitra,sum(pomaximus.jumlah) as jmlh,sum(pomaximus.total) as total FROM pomaximus INNER JOIN poproduk on pomaximus.idpoproduk=poproduk.idpoproduk 
                LEFT JOIN mitraagen on pomaximus.idmitraagen=mitraagen.idmitraagen
                LEFT JOIN mitrareseller ON pomaximus.idmitrareseller=mitrareseller.idmitrareseller
                LEFT JOIN mitramarketer ON pomaximus.idmitramarketer=mitramarketer.idmitramarketer
                LEFT JOIN admin_mitra on pomaximus.idadmin=admin_mitra.idadmin OR
                mitraagen.idadmin=admin_mitra.idadmin OR
                mitrareseller.idadmin=admin_mitra.idadmin OR
                mitramarketer.idadmin=admin_mitra.idadmin

                WHERE pomaximus.jumlah>0 GROUP BY admin_mitra.idadmin 
                ORDER BY `admin_mitra`.`namamitra`  ASC ");
                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>     
                         
                           <td>
                            <?php echo $tampilkan['namapo']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['namamitra']; ?>
                          </td>
                          <!-- <td>
                            <a href="detailinvoice_maximus.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
                          </td> -->
                            <td>
                            <!-- <a href="detailinvoice_maximus.php?invoice=<?php echo $tampilkan['invoice']; ?>"> --><?php echo $tampilkan['jmlh']; ?><!-- </a> -->
                          </td> <td>
                            <?php echo number_format($tampilkan['total']); ?>
                          </td>
              
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    
                