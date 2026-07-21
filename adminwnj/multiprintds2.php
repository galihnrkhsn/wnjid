<?php
include "koneksi.php";
        

?>

<style type="text/css">
  @media print {
  footer {page-break-after: always;}
}
</style>

<body>

  <?php   
if(isset($_POST['but_hapus'])){
          if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){
$tampil =$koneksi->query("SELECT invoice FROM podropship where iddropship='$updateid' ");
                 $tampilMar=$tampil->fetch_assoc();
                 $id= $tampilMar['invoice'];
$delete = "DELETE FROM podropship where iddropship='$updateid'";
    $sql = mysqli_query( $koneksi, $delete);

                }
                if ($sql) {
                  echo "<script>alert('data berhasil dihapus');</script>";
                          echo "<script>location='detaildropship.php?id=$id';</script>";
                }
          }
}

   ?>  <?php   
if(isset($_POST['but_proses'])){
          if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

$invoice = $_POST['invoice'.$updateid];
$sql = $koneksi->query("UPDATE podropship SET proses='Proses' WHERE iddropship='$updateid'" );
$tampil =$koneksi->query("SELECT invoice FROM podropship where iddropship='$updateid' ");
                 $tampilMar=$tampil->fetch_assoc();
                 $id= $tampilMar['invoice'];
                }
                if ($sql) {
                  echo "<script>alert('data berhasil diproses');</script>";
                          echo "<script>location='detaildropship.php?id=$id';</script>";
                }
          }
}

   ?>
   
   
<!-- Tambah Ke Portal -->
<?php
    if (isset($_POST['portal'])) {
        if (isset($_POST['update'])) {
            $idds = $_POST['parse'];
            $koneksi2 = mysqli_connect("localhost","n1609756_db_portalwnj","Padasuk@218","n1609756_db_portalwnj");
            $koneksi = mysqli_connect("localhost","n1609756_db_wnjweb","Padasuk@218","n1609756_db_wnjweb");
            $updateid = $_POST['update'];
            
            $countUpdatedId = count($updateid);
            
            // foreach($_POST['update'] as $updateid) {
            //     $namacs = $_POST['namapenerima'];
            //     var_dump($dataCount);
            //     $sql = $koneksi->query("SELECT * FROM podropship WHERE iddropship = '$updateid'");
            //     $query = $sql->fetch_assoc();
            //     $invoice = $query['invoice'];
            //     $sql_portal = $koneksi2->query("SELECT * FROM logistik WHERE invoice = '$invoice'");
            //     $query_portal = $sql_portal->fetch_assoc();
            //     // echo "<pre>";
            //     // var_dump($query);
            //     // echo "</pre>";
            //     // die();
            //     $namapenerima = $query['namapenerima'];
            //     $ekspedisi = $query['ekspedisi'];
            //     $idadmin = $query['idadmin'];
            //     // $namacs_value = $namacs[0];
            //     // var_dump($namacs_value);
            //     // echo "Nama CS: " . $query_portal['namacs'] . "<br />";
            //     // $sql = $koneksi2->query("INSERT INTO logistik
            //     //                             (idlogistik, tgl, penerima,
            //     //                             ekspedisi, noresi, biayakirim,
            //     //                             keterangan, status, idadmin,
            //     //                             namacs, no_sj, jenis_mitra) VALUES
            //     //                             (NULL, NOW(), '$namapenerima',
            //     //                             '$ekspedisi', '', '0',
            //     //                             'Dropship', '', '$idadmin',
            //     //                             '$namacs', '', 'WNJ')
            //     //                         ");
            // }
            
            for($x = 0; $x < $countUpdatedId; $x++) {
                $idx = $updateid[$x];
                $iddropship = $_POST['iddropship'][$x];
                $no_dropship = $_POST['no_ds'][$x];
                $namacs = $_POST['namacs'][$x];
                $namamitra = $_POST['namamitra'][$x];
                $idadmin = $_POST['idadmin'][$x];
                $namapengirim = $_POST['namapengirim'][$x];
                $tlppengirim = $_POST['tlppengirim'][$x];
                $namapenerima = $_POST['namapenerima'][$x];
                $tlppenerima = $_POST['tlppenerima'][$x];
                $ekspedisi = $_POST['ekspedisi'][$x];
                $alamatpenerima = $_POST['alamatpenerima'][$x];
                $invoice = $_POST['invoice'][$x];
                $id = $_GET["id"];

        //         // Menampilkan informasi yang dipilih
        //         echo "ID: " . $updateid[$x] . "<br />";
        //         echo "Nama CS: " . $x . " " . $namacs . "<br />";
        //         echo "Nama Mitra: " . $x . " " . $namamitra . "<br />";
        //         echo "ID Admin: " . $x . " " . $idadmin . "<br />";
        //         echo "Nama Pengirim: " . $x . " " . $namapengirim . "<br />";
        //         echo "Telp Pengirim: " . $x . " " . $tlppengirim . "<br />";
        //         echo "Nama Penerima: " . $x . " " . $namapenerima . "<br />";
        //         echo "Telp Penerima: " . $x . " " . $tlppenerima . "<br />";
        //         echo "Ekspedisi: " . $x . " " . $ekspedisi . "<br />";
        //         echo "Alamat Penerima: " . $x . " " . $alamatpenerima . "<br />";
        //         echo "Invoice: " . $x . " " . $invoice . "<br />";
        //         echo "Jenis Mitra: WNJ <br />";

                $sql = $koneksi2->query("INSERT INTO logistik
                                        (idlogistik, tgl, penerima,
                                        ekspedisi, noresi, biayakirim,
                                        keterangan, status, idadmin,
                                        namacs, no_sj, jenis_mitra) VALUES
                                        (NULL, NOW(), '$namapenerima',
                                        '$ekspedisi', '', '0',
                                        'Dropship', '', '$idadmin',
                                        '$namacs', '', 'WNJ')
                                      ");
                $sql2 = $koneksi2->query("INSERT INTO t_user
                                          (id_user, namacs, nama,
                                          teleponpengirim, nama_penerima, teleponpenerima,
                                          alamat, keterangan, ekspedisi,
                                          invoice, status, created_date,
                                          pcs, namamitra, idadmin,
                                          ongkir, marketplace, modified_date,
                                          resi_pengiriman, no_sj, jenis_mitra) VALUES
                                          (NULL, '$namacs', '$namapengirim',
                                          '$tlppengirim', '$namapenerima', '$tlppenerima',
                                          '$alamatpenerima', 'Dropship', '$ekspedisi',
                                          '$invoice', NULL, NOW(),
                                          NULL, '$namamitra', '$idadmin',
                                          '0', NULL, NOW(),
                                          NULL, NULL, 'WNJ')
                                        ");
                $sql3 = $koneksi->query("UPDATE `podropship` SET `proses` = 'Proses' WHERE `podropship`.`no_ds` = '$no_dropship'");

                if ($sql && $sql2 && $sql3) {
                  echo "<script>alert('Data berhasil di tambahkan ke portal!')</script>";
                  echo "<script>location='detaildropship.php?id=$idds'</script>";
                } else {
                  echo " " . $koneksi2->error;
                }
            }
        }
    }
?>
<!-- End Tambah Ke Portal -->
   
<?php 
if(isset($_POST['but_export'])){
          if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

$invoice = $_POST['invoice'.$updateid];
$sql = $koneksi->query("SELECT podropship.namapengirim, 
                      podropship.tlppengirim, 
                      podropship.namapenerima, 
                      podropship.tlppenerima, 
                      podropship.alamatpenerima,
                      podropship.invoice,
                      podropship.idpoproduk,
                      podropship.keterangan,
                      podropship.no_ds,
                      podropship.ekspedisi,
                      podropship.layanan,
                      poproduk.namapo,
                      poproduk.idpoproduk,
                      tb_ro_provinces.province_name as provinsi,
                      tb_ro_cities.city_name as kota,
                      tb_ro_subdistricts.subdistrict_name as kecamatan 

                      FROM podropship 
                      LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
                      LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
                      LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id 
                        JOIN poproduk on podropship.idpoproduk=poproduk.idpoproduk 
                        WHERE podropship.iddropship = '$updateid' ");
$data = $sql->fetch_array() ;   
$idpoproduk = $data['idpoproduk'];  


  $datamitra=$koneksi->query("SELECT admin_mitra.namamitra,admin_mitra_cs.namacs,admin_mitra.idadmin,
                              mitraagen.namaagen as agen, 
                              mitrareseller.namaagen as reseller, 
                              mitramarketer.namaagen as marketer,
                              podropship.invoice 
                              FROM podropship 
                              LEFT JOIN mitraagen on podropship.idmitraagen=mitraagen.idmitraagen 
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=podropship.idmitrareseller 
                              LEFT JOIN mitramarketer on podropship.idmitramarketer=mitramarketer.idmitramarketer 
                              LEFT JOIN admin_mitra on admin_mitra.idadmin=podropship.idadmin or mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin 
                              LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin
                              WHERE podropship.iddropship='$updateid'");
                            $tampilnama=$datamitra->fetch_assoc();                        
 ?>
<style type="text/css">
        td{
            border: 1px solid black;
        }
        th{
            border: 1px solid red;
        }
</style>
    
    <div align="center" style="margin-bottom:10px;" >
        <table style="width:100%" >
            <tr align="center" >
                <th>
                    <p style="color:red; margin-bottom:-1.7px;" >
                    Jika Pesanan Sudah Sampai Segera Cek Barang Sesuai Dengan Struk
                        <table style="width:70%" >
                            <tr align="center" >
                                <th>
                                    <p style="color:red; font-size:30;" >Kami Tidak Menerima Komplain Tanpa Foto/Video Lebih dari 1x24 Jam</p>
                                </th>
                            </tr>
                        </table>
                </th>
            </tr>
        </table>
    </div>


    <div style="border-bottom:1px dashed #000;;color:black;margin-bottom:10px;"></div>


        <table style="width:100%" align="center">
            <tr align="center" colspan="3" height="50">
                <td colspan="3">
                    <?php
    	                echo '<center><img src="logowanoja.png" style="width:100px;" /></center>';
    	            ?>
	            </td> 
            </tr>
  
            <tr align="center" height="50" >
              <td>Mitra : <br>
            <?php echo $tampilnama['idadmin']; ?></td>
            <td>CS : <br><?php echo $tampilnama['namacs']; ?>

            </td>
                <td>
                    <?php if ($data['ekspedisi']=='tiki'): ?>
  <img src="img/TIKIREG.jpg" alt="" width="100" height="30" style="margin-top:2px;">
  <?php elseif ($data['ekspedisi']=='Ambil ke Pusat'): ?>
    <img src="img/Ambil.jpg" alt="" width="100" height="30" style="margin-top:2px;">
  <?php elseif ($data['ekspedisi']=='Disatukan'): ?>
    <img src="img/Disatukan.jpg" alt="" width="100" height="30" style="margin-top:2px;">
  <?php elseif ($data['ekspedisi']=='Gosend'): ?>
    <img src="img/Gosend.jpg" alt="" width="100" height="30" style="margin-top:2px;">   
  <?php elseif ($data['ekspedisi']=='anteraja'): ?>
    <img src="img/anteraja.jpg" alt="" width="100" height="30" style="margin-top:2px;">
  <?php elseif ($data['ekspedisi']=='sicepat'): ?>
    <img src="img/Sicepat REG.jpg" alt="" width="100" height="30" style="margin-top:2px;">   
  <?php elseif ($data['ekspedisi']=='wahana'): ?>
    <img src="img/Wahana Ekspres.jpg" alt="" width="100" height="30" style="margin-top:2px;">  
  <?php elseif ($data['ekspedisi']=='jne'): ?>
    <img src="img/JNE <?php echo strtoupper($data['layanan']); ?>.jpg" alt="" width="100" height="30" style="margin-top:2px;">          
  <?php elseif ($data['ekspedisi']=='ide'): ?>
    <img src="img/ide.jpg" alt="" width="100" height="30" style="margin-top:2px;">   
    <?php elseif ($data['ekspedisi']=='idetruck'): ?>
    <img src="img/ide.jpg" alt="" width="100" height="30" style="margin-top:2px;">
    <br> <?php echo strtoupper('ID Express Truck'); ?>
    <?php elseif ($data['ekspedisi']=='Ahsan'): ?>
    <img src="img/ahsan.jpg" alt="" width="100" height="30" style="margin-top:2px;">
    <br> <?php echo strtoupper('Ahsan'); ?>
    <?php elseif ($data['ekspedisi']=='KALOG'): ?>
    <img src="img/KALOG.jpg" alt="" width="100" height="30" style="margin-top:2px;">
    <br> <?php echo strtoupper('KALOG'); ?> 
    <?php elseif ($data['ekspedisi']=='IndahCargo'): ?>
    <img src="img/IndahCargo.jpg" alt="" width="100" height="30" style="margin-top:2px;">
    <br> <?php echo strtoupper('IndahCargo'); ?>  
    <?php elseif ($data['ekspedisi']=='jtr'): ?>
    <img src="img/jtr.png" alt="" width="100" height="30" style="margin-top:2px;object-fit: cover;">
  

  <?php else: ?>
    <img src="img/<?php echo $data['ekspedisi']; ?>.jpg" alt="" width="100" height="30" style="margin-top:2px;">
<?php endif ?><br><?php echo strtoupper($data['ekspedisi']); ?> / <?php echo strtoupper($data['layanan']); ?>
                </td>
            </tr> 
        </table>


        <table style="width:100%">
            <tr align="center">
                <td scope="col" >
                    <font face="Palatino Linotype" > 
                        <h4 style="color:black; margin-bottom:10px;">
                            Pengirim :
                            <br>
                            <?php echo $data['namapengirim']; ?>
                            <br>
                            Telp : <?php echo $data['tlppengirim']; ?>
                        </h4>
                </td>
            </tr>
        </table>

        <table style="width:100%">
            <tr align="center">
                <td scope="col" >
                    <font face="Palatino Linotype" >
                        <h4 style="color:black; margin-bottom:10px;">
                            Penerima :
                            <br> 
                            <?php echo $data['namapenerima']; ?> 
                            <br>
                            Telp : <?php echo $data['tlppenerima']; ?>
                            <br>
                            <?php echo $data['alamatpenerima']; ?>
                            <br>
                            <?php echo $data['kecamatan']; ?>, <?php echo $data['kota']; ?>, <?php echo $data['provinsi']; ?>
                            
                        </h4>
                </td>
            </tr>
        </table>
        <table style="width:100%">    
            <tr align="center">
                <td>
                    Mohon Halalkan Segala Kekurangan dan Ketidaknyamanan dalam Bermuamalah Bersama Kami
                </td>
            </tr>
        </table>
        
        <br>

    <div style="border-bottom:1px dashed #000;;color:black;"></div>
 
    <p>note :
        <br>
        <b><?php echo $data['namapo']; ?> : <?php echo $data['invoice']; ?> / <?php echo $data['no_ds']; ?><b>
        <br>
<?php if ($idpoproduk<>186): ?>
          
            <?php echo $data['keterangan']; ?>
        <?php endif ?>        
    </p>

    <div class="footer"></div>
    <footer></footer>
    <!-- End of Content Wrapper -->

<?php                
 }
              }
        } 
        ?>
        
<?php
    if(isset($_POST['but_inv'])){
        if(isset($_POST['update'])){
            $countUpdatedId = count($_POST['update']);
            for ($x = 0; $x < $countUpdatedId; $x++) {
                $inv = $_POST['parse'];
                $no_ds = $_POST['update'][$x];
                if(substr($inv, offset: 0, 1) == "D") {
                    $datamitra = $koneksi->query("SELECT admin_mitra.*,
                                                        admin_mitra.*, 
                                                        admin_mitra_cs.namacs,
                                                        podropship.*
                                                        FROM podropship
                                                        JOIN admin_mitra ON podropship.idadmin = admin_mitra.idadmin
                                                        INNER JOIN admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                        WHERE podropship.iddropship = '$no_ds'
                                                ");
                    $tampilnama = $datamitra->fetch_assoc();
                    $idpoproduk = $tampilnama['idpoproduk'];
                    $invoice = $tampilnama['invoice'];
                    $iddropship = $tampilnama['no_ds'];
                } elseif(substr($inv, 0, 1) == "A") {
                    $datamitra = $koneksi->query("SELECT mitraagen.*,
                                                		podropship.*,
                                                        pomitra.*
                                                        FROM `pomitra`
                                                        LEFT JOIN podropship ON pomitra.invoice = podropship.invoice 
                                                        LEFT JOIN mitraagen ON mitraagen.idmitraagen = pomitra.idmitraagen
                                                        LEFT JOIN admin_mitra ON mitraagen.idadmin = admin_mitra.idadmin
                                                        WHERE podropship.iddropship = '$no_ds';
                                                ");
                    $tampilnama = $datamitra->fetch_assoc();
                    $idpoproduk = $tampilnama['idpoproduk'];
                    $invoice = $tampilnama['invoice'];
                    $iddropship = $tampilnama['no_ds'];
                } elseif (substr($inv, 0, 1) == "R") {
                    $datamitra = $koneksi->query("SELECT mitrareseller.*,
                                                		podropship.*,
                                                        pomitra.*
                                                        FROM `pomitra`
                                                        LEFT JOIN podropship ON pomitra.invoice = podropship.invoice 
                                                        LEFT JOIN mitrareseller ON mitrareseller.idmitrareseller = pomitra.idmitrareseller
                                                        LEFT JOIN admin_mitra ON mitrareseller.idadmin = admin_mitra.idadmin
                                                        WHERE podropship.iddropship = '$no_ds';
                                                ");
                    $tampilnama = $datamitra->fetch_assoc();
                    $idpoproduk = $tampilnama['idpoproduk'];
                    $invoice = $tampilnama['invoice'];
                    $iddropship = $tampilnama['no_ds'];
                } elseif (substr($inv, 0, 1) == "M") {
                    $datamitra = $koneksi->query("SELECT mitramarketer.*,
                                                		podropship.*,
                                                        pomitra.*
                                                        FROM `pomitra`
                                                        LEFT JOIN podropship ON pomitra.invoice = podropship.invoice 
                                                        LEFT JOIN mitramarketer ON mitramarketer.idmitramarketer = pomitra.idmitramarketer
                                                        LEFT JOIN admin_mitra ON mitramarketer.idadmin = admin_mitra.idadmin
                                                        WHERE podropship.iddropship = '$no_ds';
                                                ");
                    $tampilnama = $datamitra->fetch_assoc();
                    $idpoproduk = $tampilnama['idpoproduk'];
                    $invoice = $tampilnama['invoice'];
                    $iddropship = $tampilnama['no_ds'];
                } else {
                    echo "Not Found";
                }
?>     
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
<style type="text/css">
  table, th, td, tr {
  border: 2px solid;
   border-collapse: collapse;
}
body{
  color: black;
}
</style>


<center><p style="font-size:60;margin-bottom:0;"><strong>WNJ.ID</strong></p></center>
    <?php
        if(substr($inv, 0, 1) == "D") : 
    ?>
<center><p style="font-size:40;margin-top:0;"><strong>Inv. <?= $invoice ?></strong></p></center>
    <?php elseif (substr($inv, 0, 1 == "A")) : ?>
        <center><p style="font-size:40;margin-top:0;"><strong>Inv. <?= $iddropship ?></strong></p></center>
    <?php endif; ?>
  <div class="row align-items-start">
    <div class="col">
       <h5><strong style="float:left">Penerima : <?php echo $tampilnama['namapenerima']; ?> </strong></h5>
    </div>
    <div class="col">
    </div>
    <div class="col">
      <h5><strong style="float:rigth">Nama CS : <?php echo $tampilnama['namacs']; ?> </strong></h5>
    </div>
  </div>
    <table style="width:100%;font-size: 28px">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Checker</th>
                <th>Penerima</th>
            </tr>
        </thead>
        <tbody>
<?php 
    $datapodropship=$koneksi->query("SELECT podetail.harga,
                                            podetail.variant, 
                                            pods.jumlah, 
                                            pods.invoice, 
                                            pods.idpodetail, 
                                            pods.id
                                    FROM pods
                                    JOIN podetail ON podetail.idpodetail = pods.idpodetail
                                    WHERE pods.no_ds= '$iddropship'
                                    AND pods.jumlah>0
                                    ");
$no=1;
        while($tampilkan=$datapodropship->fetch_assoc()){
?>
            <tr>
                <td><?php echo $no++; ?></td>    
                <td><?= $tampilkan['variant'];?></td>
                <td><center><?= $tampilkan['jumlah'];?></center></td>
                <td></td>
                <td></td>
            </tr>
<?php 
$qty += $tampilkan['jumlah'];
$totalbayar +=$total;
?>                       
<?php } ?>
        </tbody>
    </table>
<br>
<br>
<center>
<table style="font-size: 25px;border-color: white;">
  <tr>
    <td style="border-color: white;">
      <center>Gudang</center>
      <br>
        <br>
        <br>
        <br>      
    </td>
    <td width="5%" style="border-color: white;">
      
    </td>
    <td style="border-color: white;">
      <center>Checker</center>
      <br>
        <br>
        <br>
        <br>      
    </td>
    <td width="5%" style="border-color: white;">
      
    </td>    
    <td style="border-color: white;">
      <center>Penerima</center>
      <br>
        <br>
        <br>
        <br>
    </td>
    </tr>
    <tr>
      <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_____________________</span>)
      </center>      
    </td >
    <td style="border-color: white;"></td>
      <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_____________________</span>)
      </center>     
    </td>   
    <td style="border-color: white;"></td> 
    <td style="border-color: white;">
      <center>
      (<span style="color:transparent;">_____________________</span>)
      </center>
    </td>
  </tr>
</table>
    </center>

            <div>
                <p style="font-size: 22px"> 
                  Note : Setelah barang diterima, mohon langsung dicek. Surat jalan yang sudah diverifikasi dan sudah ditandatangani mohon untuk difoto dan dikirim melalui No HP CS Pusat maksimal 3 X 24 Jam
<br>
<br>
<?php if ($idpoproduk==186 or $idpoproduk==187): ?>
<strong>Keterangan Isi Box</strong>
<?php endif; ?>
<br>
<?php 
      $ambil_box=$koneksi->query("SELECT *
                                FROM hampers
                                WHERE hampers.no_ds= '$invoice'
                                ORDER BY nobox ASC"); 
      while($data_box=$ambil_box->fetch_assoc()){
    $result_explode = explode('|', $data_box['ucapan']);
    $dari=$result_explode[0];    
    $kepada=$result_explode[1];    
    $ucapan=$result_explode[2];        
 ?>
<label>Box <?= $data_box['nobox'] ?> : <?php echo str_replace("|",", ",$data_box['idpodetail']); ?> <?php if ($dari or $kepada or $ucapan): ?>
	<span style="color: red">(Req. Kartu Ucapan)</span>
<?php endif ?></label>
<br>

<?php } ?>                
            </div>                  
                </p>
                <br>


    
    <div class="footer"></div>
    <footer></footer>
    <?php                
                }
            }
        } 
    ?>
</body>
<script>
 window.print();
</script>

</html>

                                                          