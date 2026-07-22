<?php
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=Data Total PO.xls");
  include "koneksi.php";
  $idpoproduk=$_GET["id"];
  $query = "SELECT poproduk.idpoproduk,
                  poproduk.namapo
              FROM poproduk 
              WHERE poproduk.idpoproduk='$idpoproduk'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);
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


<!--   <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link href="css/sb-admin-2.min.css" rel="stylesheet">
 -->
</head>

          <!-- Content Row -->

                    <p><strong>Totalan <?php echo $datapo['namapo']; ?></strong></p>
        

        <table border="1">
          <thead>
          <tr>
              <th>No</th>
            <th>Nama CS</th>
            <th>Distributor</th>
            <th>Agen</th>
            <th>Reseller</th>
            <th>Marketer</th>
            <th>Inv</th>
            <?php if($idpoproduk === '234' or $idpoproduk === '245' or $idpoproduk === '250' or $idpoproduk === '251' || $idpoproduk === '325') : ?>
                <th>Variant 1</th>
                <th>Variant 2</th>
                <th>Variant 3</th>
            <?php elseif ($idpoproduk === '237' or $idpoproduk === '240') : ?>
                <th>Custom</th>
            <?php elseif ($idpoproduk === '235' or $idpoproduk === '236' or $idpoproduk == '261' || $idpoproduk == '315' || $idpoproduk == '326') : ?>
              <th>Variant</th>
              <th>Nama</th>
              <th>Font</th>
            <?php elseif ($idpoproduk === '328') : ?>
              <th>Variant</th>
              <th>Custom</th>
            <?php else : ?>
                <th>Variant</th>
            <?php endif; ?>
            <th>Size</th>
            <th>Jumlah</th>
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
                admin_mitra.idadmin,
          mitraagen.namaagen as agen,
          mitrareseller.namaagen as reseller,
          mitramarketer.namaagen as marketer,
          mitraagen.idmitraagen as idagen,
          mitrareseller.idmitrareseller as idreseller,
          mitramarketer.idmitramarketer as idmarketer,
          pomitra.invoice,
          podetail.variant,
          pomitra.custom,
          pomitra.font,
          pokategori.namakategori,
          pomitra.jumlah,
          pomitra.total,
          pomitra.status,
          pomitra.tgl,
           admin_mitra_cs.namacs 
          FROM `poproduk` 
          INNER JOIN pomitra on pomitra.idpoproduk=poproduk.idpoproduk 
          inner JOIN pokategori on pokategori.idpo=pomitra.idpo
          right JOIN podetail on pomitra.idpodetail = podetail.idpodetail
          LEFT JOIN mitraagen on mitraagen.idmitraagen=pomitra.idmitraagen 
          LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
          LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pomitra.idmitramarketer 
          LEFT JOIN admin_mitra on mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin or pomitra.idmitra=admin_mitra.idadmin

              LEFT JOIN admin_mitra_cs on admin_mitra_cs.idadmin = admin_mitra.idadmin
          WHERE pomitra.idpoproduk='$idpoproduk' 
          AND pomitra.jumlah>0
          ORDER BY admin_mitra_cs.namacs asc, admin_mitra.namamitra asc, pomitra.invoice asc");
            $no=1;
            while($tampilkan2=$datapo2->fetch_assoc()){

$result_explode = explode('Sz', $tampilkan2['variant']);
$khimar=$result_explode[0];
$ukuran = $result_explode[1];              
            ?>
                      <tr>
                       <td>
                           <?php echo $no++; ?>
                      </td>     
                        <td>
                          <?php echo $tampilkan2['namacs']; ?>
                        </td>
                         <td>
                          <?php echo $tampilkan2['db']; ?> (<?php echo $tampilkan2['idadmin']; ?>)
                        </td>
                       <td>
                          <?php echo $tampilkan2['agen']; ?> 
                          <?php if ($tampilkan2['idagen']): ?>
                            (<?php echo $tampilkan2['idagen']; ?>)                            
                          <?php endif ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['reseller']; ?>
                          <?php if ($tampilkan2['idreseller']): ?>
                            (<?php echo $tampilkan2['idreseller']; ?>)                            
                          <?php endif ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['marketer']; ?>
                          <?php if ($tampilkan2['idmarketer']): ?>
                            (<?php echo $tampilkan2['idmarketer']; ?>)                            
                          <?php endif ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['invoice']; ?>
                        </td>
                        <?php
                            if($idpoproduk === '234' or $idpoproduk === '245' or $idpoproduk === '250' or $idpoproduk === '251' || $idpoproduk === '325') : 
                                $dataExplode = explode(" | ", $tampilkan2['custom']);
                        ?>
                            <td>
                              <?php echo $dataExplode[0]; ?>
                            </td>
                            <td>
                              <?php echo $dataExplode[1]; ?>
                            </td>
                            <td>
                              <?php echo $dataExplode[2]; ?>
                            </td>
                        <?php elseif($idpoproduk === '237' or $idpoproduk === '240') : ?>
                            <td>
                              <?php echo $tampilkan2['custom']; ?>
                            </td>
                        <?php elseif ($idpoproduk === '235' or $idpoproduk === '236' or $idpoproduk == '261' || $idpoproduk == '315' || $idpoproduk == '326') : ?>
                            <td>
                              <?php echo $tampilkan2['variant']; ?>
                            </td>
                            <td>
                              <?php echo $tampilkan2['custom']; ?>
                            </td>
                            <td>
                              <?php echo $tampilkan2['font']; ?>
                            </td>
                        <?php elseif ($idpoproduk === '328') : ?>
                            <td>
                              <?php echo $tampilkan2['variant']; ?>
                            </td>
                            <td>
                              <?php echo $tampilkan2['custom']; ?>
                            </td>
                        <?php else : ?>
                            <td>
                              <?php echo $tampilkan2['variant']; ?>
                            </td>
                        <?php endif; ?>
                        <td><?php echo $ukuran; ?></td>
                       <td>
                          <?php echo $tampilkan2['jumlah']; ?>
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
		