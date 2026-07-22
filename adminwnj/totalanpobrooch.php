<?php 



 ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

<?php
include "koneksi.php";
                            $idpoproduk=$_GET["id"];
                            $datapo2=$koneksi->query("SELECT poproduk.namapo,
admin_mitra.namamitra as db,
mitraagen.namaagen as agen,
mitrareseller.namaagen as reseller,
mitramarketer.namaagen as marketer,
pomitra.invoice,
podetail.variant,
pomitra.custom,
pomitra.font,
pokategori.namakategori,
pomitra.jumlah,
pomitra.total,
pomitra.status,
pomitra.tgl
FROM `pomitra` 

LEFT JOIN mitraagen on mitraagen.idmitraagen=pomitra.idmitraagen 
LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pomitra.idmitramarketer 
LEFT JOIN admin_mitra on mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin or pomitra.idmitra=admin_mitra.idadmin
INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
INNER JOIN podetail on pomitra.idpodetail = podetail.idpodetail
inner JOIN pokategori on pokategori.idpo=pomitra.idpo

WHERE pomitra.idpoproduk='$idpoproduk' and pomitra.jumlah>0 ORDER BY pomitra.tgl DESC");
while($tampilkan2=$datapo2->fetch_assoc()){
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Total $tampilkan2[namapo].xls");
}
?>

  <title>Laporan PO</title>


<!--   <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
            <th>Distributor</th>
            <!-- <th>Agen</th>
            <th>Reseller</th>
            <th>Marketer</th> -->
            <th>Variant</th>
            <th>Nama Custom</th>
            <th>Jumlah Karakter</th>
            <th>Size Brooch</th>
            <th>Inv</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tanggal</th>
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
pomitra.invoice,
podetail.variant,
pomitra.custom,
pomitra.font,
pokategori.namakategori,
pomitra.jumlah,
pomitra.total,
pomitra.status,
pomitra.tgl
FROM `pomitra` 

LEFT JOIN mitraagen on mitraagen.idmitraagen=pomitra.idmitraagen 
LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pomitra.idmitramarketer 
LEFT JOIN admin_mitra on mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin or pomitra.idmitra=admin_mitra.idadmin
INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
INNER JOIN podetail on pomitra.idpodetail = podetail.idpodetail
inner JOIN pokategori on pokategori.idpo=pomitra.idpo

WHERE pomitra.idpoproduk='$idpoproduk' and pomitra.jumlah>0 ORDER BY pomitra.tgl DESC");
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
                          <?php echo $tampilkan2['db']; ?>
                        </td>
                       <!--  <td>
                          <?php echo $tampilkan2['agen']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['reseller']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['marketer']; ?>
                        </td> -->
                        <td>
                          <?php echo $tampilkan2['variant']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['custom']; ?>
                        </td>
                        <td>
                          <?php echo strlen($tampilkan2['custom']); ?>
                        </td>
                        <td>
                            <?php 
                            $jmlh=strlen($tampilkan2['custom']);
                            if ($jmlh>=1 and $jmlh<=4){
                            echo "2*4 cm";
                              } if($jmlh>=5 and $jmlh<=7) {
                              echo "3*5 cm"; 
                              } if($jmlh>=8 and $jmlh<=10) {
                              echo "3*8 cm"; 
                              } 
                            ?>
                          </td> 
                        <td>
                          <?php echo $tampilkan2['invoice']; ?>
                        </td>
                       <td>
                          <?php echo $tampilkan2['jumlah']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['total']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['status']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['tgl']; ?>
                        </td>
                      </tr>
                    <?php } ?>
        </tbody>
        </table>
		