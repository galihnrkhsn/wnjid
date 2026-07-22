
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

<?php
$idpoproduk=$_GET["id"];
// include "koneksi";
// $namapo=$koneksi->query("SELECT * FROM poproduk WHERE idpoproduk='$idpoproduk'");
// $tampil=$namapo->fetch_assoc();

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Total PO.xls");
?>

  <title>Laporan PO</title>

<style type="text/css">
  table, th, td, tr {
  border: 3px solid;
}
</style>
<!--   <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link href="css/sb-admin-2.min.css" rel="stylesheet">
 -->
</head>

          <!-- Content Row -->

                    <p><strong>Totalan PO</strong></p>
        

  <table>
  <tr>
    <th>no</th>
    <th>
      Nama DB
    </th>
                            <?php 
                          include "koneksi.php";
                            $idpoproduk=$_GET["id"];
                            $idadmin=$_GET["idadmin"];
                            $datapo2=$koneksi->query("SELECT 
podetail.variant,
pomitra.jumlah
FROM `pomitra` 
INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
INNER JOIN podetail on pomitra.idpodetail = podetail.idpodetail
inner JOIN pokategori on pokategori.idpo=pomitra.idpo

WHERE pomitra.idpoproduk='109'

GROUP BY podetail.variant
ORDER BY podetail.variant asc
"

);
                            while($tampilkan2=$datapo2->fetch_assoc()){
                            ?>    
          <th>
           <?php echo $tampilkan2['variant']; ?>
          </th>  
  
                                  
 <?php } ?> 
  </tr>
    <tr>
 <?php 
                            $datapo3=$koneksi->query("SELECT 
admin_mitra.namamitra as db,
admin_mitra.idadmin
FROM `pomitra` 
INNER JOIN admin_mitra on pomitra.idmitra=admin_mitra.idadmin

INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
INNER JOIN podetail on pomitra.idpodetail = podetail.idpodetail
inner JOIN pokategori on pokategori.idpo=pomitra.idpo

WHERE pomitra.idpoproduk='$idpoproduk'
GROUP BY admin_mitra.idadmin
ORDER BY admin_mitra.namamitra asc
");
                            $no=1;
                            while($tampilkan3=$datapo3->fetch_assoc()){
                              $idadminya = $tampilkan3['idadmin'];
                            ?>
                            <th><?= $no++; ?></th>
      <th>
          <?php echo $tampilkan3['db']; ?>
      </th>
                            <?php 
                          include "koneksi.php";
                            $datapo2=$koneksi->query("SELECT 
podetail.variant,
pomitra.jumlah
FROM `pomitra` 
INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
INNER JOIN podetail on pomitra.idpodetail = podetail.idpodetail
inner JOIN pokategori on pokategori.idpo=pomitra.idpo

WHERE pomitra.idpoproduk='109'
and pomitra.idmitra = '$idadminya' 
GROUP BY podetail.variant
ORDER BY podetail.variant asc
");
                            while($tampilkan2=$datapo2->fetch_assoc()){
                            ?>    
          <th>
           <?php echo $tampilkan2['jumlah']; ?>
          </th>  
  
                                  
 <?php } ?> 
  </tr>
  <?php } ?>

</table>
		