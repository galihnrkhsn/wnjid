
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


<!--   <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
            <th>Inv</th>
            <th>Distributor</th>
            <th>Agen</th>
            <th>Reseller</th>
            <th>Marketer</th>
            <th>Variant</th>   
            <th>Ukuran</th>            
            <th>Jumlah</th>          
            <th>Provinsi</th>
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
          pomitra.tgl,
          tb_ro_provinces.province_name
          FROM `poproduk` 
          INNER JOIN pomitra on pomitra.idpoproduk=poproduk.idpoproduk 
          inner JOIN pokategori on pokategori.idpo=pomitra.idpo
          right JOIN podetail on pomitra.idpodetail = podetail.idpodetail
          LEFT JOIN mitraagen on mitraagen.idmitraagen=pomitra.idmitraagen 
          LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
          LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pomitra.idmitramarketer 
          LEFT JOIN admin_mitra on (mitraagen.idadmin=admin_mitra.idadmin 
                                or mitrareseller.idadmin=admin_mitra.idadmin 
                                or mitramarketer.idadmin=admin_mitra.idadmin 
                                or pomitra.idmitra=admin_mitra.idadmin)
          LEFT JOIN tb_ro_provinces ON (admin_mitra.provinsi = tb_ro_provinces.province_id
                                    or mitraagen.provinsi = tb_ro_provinces.province_id
                                    or mitrareseller.provinsi = tb_ro_provinces.province_id
                                    or mitramarketer.provinsi = tb_ro_provinces.province_id)                            

          WHERE pomitra.idpoproduk='$idpoproduk' 
          and pomitra.jumlah > 0
          ORDER BY pomitra.tgl asc, pomitra.invoice asc");
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
                          <?php echo $tampilkan2['namapo']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['invoice']; ?>
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
                        <td>
                          <?php echo $khimar; ?>
                        </td>
                        <td>
                          <?php echo $ukuran; ?>
                        </td>
                       <td>
                          <?php echo $tampilkan2['jumlah']; ?>
                        </td>
                        <td>
                          <?php echo $tampilkan2['province_name']; ?>
                        </td>
                      </tr>
                    <?php } ?>
        </tbody>
        </table>
    