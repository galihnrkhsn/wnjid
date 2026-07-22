<?php
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$invoice=$_GET["invoice"];
$idpoproduk=$_GET["idpoproduk"];

   $querypengiriman = "SELECT podropship.namapengirim,
            podropship.tlppengirim,
            podropship.namapenerima,
            podropship.tlppenerima,
            podropship.alamatpenerima,
            podropship.ekspedisi,
            podropship.layanan,
            podropship.ongkir,
            podropship.dropship,
            tb_ro_provinces.province_name,
            tb_ro_cities.city_name,
            tb_ro_subdistricts.subdistrict_name
        FROM podropship 
        LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
      LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
      LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
        WHERE podropship.invoice='$invoice'";
  $sqlpengiriman = mysqli_query($koneksi, $querypengiriman);  
  $datapengiriman = mysqli_fetch_array($sqlpengiriman);


  $query = "SELECT poproduk.idpoproduk,
            poproduk.namapo,
            pomitra.tgl,
            pomitra.waktu,
            pomitra.status
        FROM poproduk 
        inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
        WHERE pomitra.invoice='$invoice'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);


  $datamitra=$koneksi->query("SELECT poproduk.namapo,
                              admin_mitra.namamitra as db,
                              admin_mitra.idadmin as iddb,
                              mitraagen.namaagen as agen,
                              mitraagen.idmitraagen as idagen,                            
                              mitrareseller.namaagen as reseller,
                              mitrareseller.idmitrareseller as idreseller,
                              mitramarketer.namaagen as marketer,
                              mitramarketer.idmitramarketer as idmarketer,
                              podropship.namapenerima 

                              FROM `pomitra`
                              LEFT JOIN podropship on pomitra.invoice=podropship.invoice 
                              LEFT JOIN mitraagen on mitraagen.idmitraagen=pomitra.idmitraagen 
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller 
                              LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pomitra.idmitramarketer 
                              LEFT JOIN admin_mitra on (mitraagen.idadmin=admin_mitra.idadmin or mitrareseller.idadmin=admin_mitra.idadmin or mitramarketer.idadmin=admin_mitra.idadmin or pomitra.idmitra=admin_mitra.idadmin) 
                              INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk 
                              WHERE pomitra.invoice='$invoice'");
                            $tampilnama=$datamitra->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Pusat | Wanoja</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php include "sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h3 class="mb-4 text-gray-800" style="width: 100%">
    <a class="btn btn-info" href="cetakpomitra.php?invoice=<?php echo $invoice; ?>&idpoproduk=<?php echo $idpoproduk ?>" target="blank"><i class="fa fa-print"></i> Print Inv</a>
    <a class="btn btn-info" href="cetakpomitrasj.php?invoice=<?php echo $invoice; ?>&idpoproduk=<?php echo $idpoproduk ?>" target="blank"><i class="fa fa-print"></i> Print Sj.</a>
    <a class="btn btn-success" href="cetakpomitra_excel.php?invoice=<?php echo $invoice; ?>" target="blank"><i class="fa fa-file-excel"></i> Excel</a>
    <a class="btn btn-primary" href="formpo_tambah.php?invoice=<?php echo $invoice; ?>"><i class="fa fa-plus"></i> Tambah Kekurangan</a> 
    <a class="btn btn-warning" href="ubahpo.php?invoice=<?php echo $invoice; ?>"><i class="fa fa-edit"></i> Ubah PO</a>                   
<a class="link" href="listpokolibri.php?id=<?= $idpoproduk; ?>" style="float: right;"><i class="fa fa-arrow-left"></i> Kembali</a>
            </h3>
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          

<center><h3><strong><?php echo $datapo['namapo']; ?><br>Invoice <?php echo $invoice; ?></strong></h3></center>

    <strong> Nama Distributor : <?php echo $tampilnama['db']; ?>(<?php echo $tampilnama['iddb']; ?>) </strong>
    <?php if ($idpoproduk=='97') { ?>
      <br>
      <strong> Nama Keluarga : <?php echo $tampilnama['namapenerima']; ?> </strong><br>
    <?php } ?>
     <?php if ($tampilnama['agen']<>'') { ?>
    <strong> 
      <br>
      Nama Sub DB : <?php echo $tampilnama['agen']; ?>(<?php echo $tampilnama['idagen']; ?>)
    </strong>
  <?php } ?>
     <?php if ($tampilnama['reseller']<>'') { ?>
      <br>
    <strong> 
      Nama Sub DB : <?php echo $tampilnama['reseller']; ?>(<?php echo $tampilnama['idreseller']; ?>)
    </strong>
  <?php } ?>
     <?php if ($tampilnama['marketer']<>'') { ?>
      <br>
    <strong> 
      Nama Sub DB : <?php echo $tampilnama['marketer']; ?>(<?php echo $tampilnama['idmarketer']; ?>)
    </strong>
  <?php } ?>
  <?php if($idpoproduk == '237') : ?>
    <p><span class="text-danger">*</span> Untuk nama custom berada di dalam tanda ()</p>
  <?php endif; ?>
<div class="row">
  <br>
	<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>No</th>
						<?php if (substr($invoice,0,2)=="DI") : ?>
						    <th>Mix</th>
						<?php else : ?>
						    <th>Nama Barang</th>
						<?php endif; ?>
						<?php
						if (substr($invoice,0,2)=="MH") { ?>
						<th>Custom Nama</th>
						<th>Font Teks</th>
						<!-- <th>Warna Teks</th> -->
						<?php } ?>
						<?php
						if (substr($invoice,0,2)=="RM") { ?>
						<th>Custom Nama</th>
						<th>Font Teks</th>
						<?php } ?>
						<?php
						if (substr($invoice,0,1)=="B") { ?>
						<th>Custom Nama</th>
						<th>Jenis Font</th>
						<?php } ?>
						<?php
						if ($idpoproduk == '261') { ?>
						<th>Custom Nama</th>
						<th>Jenis Font</th>
						<?php } ?>
             <?php
            if (substr($invoice,0,1)=="M") { ?>
            <th>Custom Nama</th>
            <?php } ?>
            <?php
            if (substr($invoice,0,3)=="LUX") { ?>
            <th>Ukuran Custom Panjang Dress</th>
            <th>Ukuran Khimar</th>
            <?php } ?>
            <?php if ($idpoproduk == 237 or $idpoproduk == 240) : ?>
				<th>Jenis PO</th>
				<th>Font Teks</th>
            <?php endif; ?>
            <?php
            if (substr($invoice,0,1)=="K") { ?>
            <!--<th>Template</th>-->
            <th>Custom</th>
            <!--<th>Font</th>-->
            <?php } ?> 
<?php if ($idpoproduk==186 or $idpoproduk==187): ?>
            <th>Qty</th>      
  <?php else: ?>
              <th>Satuan</th>
              <th>Qty</th>
              <th style="text-align:center">Jumlah</th>
<?php endif ?>
                        </tr>
                      </thead>
                      <tbody>
                        <? if ($idpoproduk === '335' || $idpoproduk === '339') : ?>
                          <?php
                                    $no = 1;
                                    $packs = [];
                                    $sql = $koneksi->query("SELECT 
                                                                    pomitra.custom
                                                                FROM
                                                                    pomitra
                                                                WHERE
                                                                    pomitra.invoice = '$invoice'
                                                                        AND pomitra.jumlah > 0
                                                                GROUP BY pomitra.custom
                                                            ");
                                    while($data = $sql->fetch_assoc()) {
                                        $custom = $data['custom'];
                                        $string = $custom;
                                        // Mengubah semua huruf menjadi huruf kecil
                                        $string = strtolower($string);
                                        // Mengganti spasi dengan tanda hubung
                                        $string = str_replace(' ', '-', $string);
                                        // Menghilangkan tanda - di awal string
                                        $string = ltrim($string, '-');
                                        // Menghapus karakter yang tidak diperlukan (opsional, jika diperlukan)
                                        $string = preg_replace('/[^a-z0-9\-]/', '', $string);
                                ?>
                                <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <?php
                                                    $query = $koneksi->query("SELECT 
                                                                                    podetail.*, pomitra.*
                                                                                FROM
                                                                                    pomitra
                                                                                        INNER JOIN
                                                                                    podetail ON pomitra.idpodetail = podetail.idpodetail
                                                                                WHERE
                                                                                    pomitra.invoice = '$invoice'
                                                                                        AND pomitra.jumlah > 0
                                                                                        AND pomitra.custom = '$custom'
                                                                            ");
                                                    $first = true;
                                                    while ($data_produk = $query->fetch_assoc()) {
                                                        if (!$first) {
                                                            echo " - ";
                                                        }
                                                        $first = false;
                                                ?>
                                                    <?= $data_produk['variant'] ?>
                                                <?php 
                                                        $pack = $data_produk['jumlah'];
                                                        $harga = $data_produk['harga'];
                                                        $idpomitra = $data_produk['idpomitra'];
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $harga ?></td>
                                            <td><?= $pack ?></td>
                                            <td>Rp. <?= number_format($harga) ?></td>
                                        </tr>
                                        <?
                                        $sum += $pack;
                                        $jumlah += $pack * $harga;
                                        ?>
                                  <?}?>
                        <? else : ?>
                          <?php 
                          	$jumlah=0;
					                  $subtotal=0;
                            $datapo=$koneksi->query("
                              SELECT 
                              poproduk.namapo,
                              poproduk.idpoproduk,
                              poproduk.jenis,
                              poproduk.diskon,
                              pokategori.namakategori,
                              podetail.variant,
                              pomitra.idpomitra,
                              pomitra.jumlah,
                              pomitra.invoice,
                              pomitra.total,
                              pomitra.custom,
                              pomitra.template,
                              pomitra.font,
                              pomitra.is_custom,
                              podetail.harga 
					                    FROM poproduk 
                               inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                               inner JOIN pokategori on pokategori.idpo=pomitra.idpo
                               inner join podetail on podetail.idpodetail=pomitra.idpodetail
                               WHERE pomitra.invoice='$invoice' and pomitra.jumlah>0
                               ORDER BY pomitra.idpodetail
                               ");
                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
                        <tr>
                         
                         <td>
                             <?php echo $no++; ?>
                        </td>
                           <td>
                          <?php if ($tampilkan['idpoproduk']==186 or $tampilkan['idpoproduk']==187 or $tampilkan['idpoproduk']==234 or $idpoproduk === '245' or $idpoproduk === '250' or $idpoproduk === '251' || $idpoproduk === '325'): ?>
                            <?= $tampilkan['custom']; ?>
                          <?php elseif ($idpoproduk == 237 or $idpoproduk == 240) : ?>
                            <?= $tampilkan['custom']; ?>
                          <?php else : ?>
                            <?php echo $tampilkan['variant']; ?>
                          <?php endif ?>
                          </td>
                          <?php if($idpoproduk == 237 or $idpoproduk == 240 ) : ?>
                            <?php if ($tampilkan['is_custom'] == 0) : ?>
                                <td>Rocela Polos | Goura Polos</td>
                            <?php elseif ($tampilkan['is_custom'] == 1) : ?>
                                <td>Rocela Polos | Goura Custom</td>
                            <?php elseif ($tampilkan['is_custom'] == 2) : ?>
                                <td>Rocela Custom | Goura Polos</td>
                            <?php elseif ($tampilkan['is_custom'] == 3) : ?>
                                <td>Rocela Custom | Goura Custom</td>
                            <?php endif; ?>
                          <?php endif; ?>
                           <?php if (substr($invoice,0,2)=="MH") { ?>
                          <td>
                            <?php echo $tampilkan['custom']; ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['font']; ?>
                          </td> 
                          <!-- <td>
                            <?php if ($tampilkan['namakategori']=='Cream' or $tampilkan['namakategori']=='Silver' 
                            	or $tampilkan['namakategori']=='White' or $tampilkan['namakategori']=='Grey') {
                            	echo "Black"; 
                            	} else {
                            	echo "Gold";	
                            	} 
                            ?>
                          </td>  -->
                          <?php } ?>
                          <?php if (substr($invoice,0,2)=="RM") { ?>
                          <td>
                            <?php 
                              $datacustom=$tampilkan['custom'];
                                $result_explode = explode('|', $datacustom);
                                $baris1=$result_explode[0];
                                $baris2=$result_explode[1];
                              echo  nl2br($baris1);
                              echo "<br>";
                              echo  nl2br($baris2);	
                            ?>                          	
                          </td>
                          <td>
                            <?php echo $tampilkan['font']; ?>
                          </td> 
                          <?php } ?>
                          <?php if (substr($invoice,0,1)=="B") { ?>
                          <td>
                            <?php echo $tampilkan['custom']; ?>
                          </td>
                          <td>
                              <?= $tampilkan['font'] ?>
                          </td>
                             <?php } ?>
                          <?php if ($idpoproduk == '261') { ?>
                          <td>
                            <?php echo $tampilkan['custom']; ?>
                          </td>
                          <td>
                              <?= $tampilkan['font'] ?>
                          </td>
                             <?php } ?>
                           <?php if (substr($invoice,0,1)=="M") { ?>
                          <td>
                            <?php echo $tampilkan['custom']; ?>
                          </td>
                          <?php } ?> 
                          <?php if (substr($invoice,0,3)=="LUX") { ?>
                          <td>
                            <?php echo $tampilkan['custom']; ?> cm
                          </td>
                          <td>
                            <?php echo $tampilkan['font']; ?>
                          </td>
                          <?php } ?> 
                          <?php if (substr($invoice,0,1)=="K") { ?>
                          <!--<td>-->
                            <?php // echo $tampilkan['template']; ?>
                          <!--</td>-->
                          <td>
                            <?php echo $tampilkan['custom']; ?>
                          </td>
                          <!--<td>-->
                            <?php // echo $tampilkan['font']; ?>
                          <!--</td>-->
                          <?php } ?>
                          
                          <?php if($tampilkan['idpoproduk'] == 237 or $tampilkan['idpoproduk'] == 240) : ?>
                            <td>
                                <?= $tampilkan['font']; ?>
                            </td>
                          <?php endif; ?>
                          
<?php if ($idpoproduk==186 or $idpoproduk==187) : ?>
                          <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>  
<?php elseif($idpoproduk == '261') : ?>
                           <td>
                           Rp. <?php echo number_format($tampilkan['total']); ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>
                          <td>
                            Rp. <?php echo number_format($tampilkan['jumlah']*$tampilkan['total']); ?>
                          </td>
<?php else: ?>
                           <td>
                           Rp. <?php echo number_format($tampilkan['harga']); ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['jumlah']; ?>
                          </td>
                          <td>
                            Rp. <?php echo number_format($tampilkan['jumlah']*$tampilkan['harga']); ?>
                          </td>
<?php endif ?>                                              

            
                          <?php
                            $sum+=$tampilkan['jumlah'];
                            if ($idpoproduk == '261') {
                                $jumlah+=$tampilkan['jumlah']*$tampilkan['total'];
                            } else {
                                $jumlah+=$tampilkan['jumlah']*$tampilkan['harga'];
                            }
                            $invoice=$tampilkan['invoice'];
                            $persen_tambahan = $tampilkan['diskon'];
                            $jenis_po = $tampilkan['jenis'];
                          ?>
                        </tr>
                        <?php } ?>
                        <? endif ?>
                      </tbody>
                    </table>
                    
<?php 
  $ongkir = $datapengiriman['ongkir'];
  $dropship = $datapengiriman['dropship'];

  $diskon_tambahan = $persen_tambahan/100*$jumlah;
  $persen=35;
  $diskon=35/100*$jumlah;

  // $subtotal=$jumlah+$dropship+$ongkir-$diskon-$diskon_tambahan; 
    $subtotal=$jumlah-$diskon-$diskon_tambahan; 


  $dp1 = $subtotal * 30/100;
  $dp2 = $subtotal * 40/100;
  $dp3 = $subtotal * 30/100;   

?>                    
<table style="float: right;width: 100%">
 <tbody  style="float: right;">
    <tr>
        <th style="padding-bottom: 5%;">Total Qty</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">
        <?= $sum; ?>
        </td>
    </tr>
    <tr>
        <th>Jumlah</th>
        <td>:</td>
        <td>
        Rp. <?= number_format($jumlah); ?>                     
        </td>
    </tr>   
    <tr>
        <th>Ongkir</th>
        <td>:</td>
        <td>      
        Rp. <?= number_format($ongkir); ?>                     
        </td>
    </tr>   

    <tr>
        <th style="padding-bottom: 5%;">Dropship</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">      
        Rp. <?= number_format($dropship); ?>                     
        </td>
    </tr>   

<?php if ($diskon_tambahan>0): ?>
      
    <tr>
        <th>Diskon Tambahan <?= $persen_tambahan; ?>%</th>
        <td>:</td>
        <td>      
        Rp. <?= number_format($diskon_tambahan); ?>                     
        </td>
    </tr>                           
    <?php endif ?>  
    <tr>
        <th style="padding-bottom: 5%;">Diskon DB <?= $persen; ?>%</th>
        <td style="padding-bottom: 5%;">:</td>
        <td style="padding-bottom: 5%;">      
        Rp. <?= number_format($diskon); ?>                     
        </td>
    </tr>  

    <tr>
        <th>Total Bayar</th>
        <td>:</td>
        <td>        
Rp. <?php echo number_format($subtotal); ?> 
        </td>
    </tr>            
</tbody>
</table>  

</div>				            
              <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>

		                                                      

                    