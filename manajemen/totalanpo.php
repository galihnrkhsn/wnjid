<?php include 'template/header.php';  ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Total PO.xls");
?>

  <title>Laporan PO</title>


<!--   <link href="../vendor/manajemen/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
            <th>Agen</th>
            <th>Reseller</th>
            <th>Marketer</th>
            <!-- <th>Variant</th>
            <th>Custom</th> -->
            <th>Inv</th>
            <th>Qty</th>
            <!-- <th>Jumlah</th> -->
            <th>Total</th>
            <th>Status</th>
         </tr>
         </thead> 
         <tbody>            
                            <?php 
                          include "koneksi.php";
$idpoproduk=$_GET["id"];
$datapo2=$koneksi->query("SELECT poproduk.namapo,
                                admin_mitra.namamitra as db,
                                mitraagen.namaagen as agen,
                                mitrareseller.namaagen as reseller,
                                mitramarketer.namaagen as marketer,
                                pomitra.invoice,sum(pomitra.jumlah) as qty,
                                pomitra.status,sum(pomitra.total) as total,
                                admin_mitra_cs.namacs 
                                FROM `pomitra` 
							LEFT JOIN mitraagen on mitraagen.idmitraagen=pomitra.idmitraagen 
							LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
							LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pomitra.idmitramarketer 
							LEFT JOIN admin_mitra on (mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin or pomitra.idmitra=admin_mitra.idadmin) 
              LEFT JOIN admin_mitra_cs on admin_mitra_cs.idadmin = admin_mitra.idadmin
							INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
							WHERE pomitra.idpoproduk='$idpoproduk' and pomitra.jumlah>0 and 
							pomitra.status<>'Belum Acc DB' GROUP BY pomitra.invoice ORDER BY pomitra.tgl ASC");
                            $no=1;
                            while($tampilkan2=$datapo2->fetch_assoc()){
                            ?>
                      <tr>
                       <td>
                           <?php echo $no++; ?>
                      </td>     
                        <td>
                          <?php echo $tampilkan2['namapo']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['namacs']; ?>
                        </td>
                         <td>
                          <?php echo $tampilkan2['db']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['agen']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['reseller']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['marketer']; ?>
                        </td>
                        <!-- <td>
                          <?php echo $tampilkan2['variant']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['custom']; ?>
                        </td> -->
                        <td>
                          <?php echo $tampilkan2['invoice']; ?>
                        </td>
                       <td>
                          <?php echo $tampilkan2['qty']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['total']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['status']; ?>
                        </td>
                      </tr>
                    <?php } ?>
        </tbody>
        </table>
		