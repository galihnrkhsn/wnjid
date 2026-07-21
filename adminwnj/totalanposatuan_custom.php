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
</head>
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
            <th>Variant</th>
            <th>Size</th>
            <th>Custom</th>
            <th>Template</th>
            <th>Font</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Tanggal</th>
         </tr>
         </thead> 
         <tbody>            
            <?php 
          
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
          pomitra.template,
          pomitra.font,
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
          ORDER BY admin_mitra.namamitra,pomitra.invoice, podetail.variant ");
            $no=1;
            while($tampilkan2=$datapo2->fetch_assoc()){

$result_explode = explode('Sz', $tampilkan2['variant']);
$khimar=$result_explode[0];
$ukuran = $result_explode[1];     
$sum +=$tampilkan2['jumlah'];        
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
                        <td>
                          <?php echo $tampilkan2['variant']; ?>
                        </td>
                        <td><?php echo $ukuran; ?></td>
                        <td>
                          <?php echo $tampilkan2['custom']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['template']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['font']; ?>
                        </td>
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
        <tfoot>
          <tr>
            <td colspan="10">Total</td>
            <td><?= $sum; ?></td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
        </table>
    