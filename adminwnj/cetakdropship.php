<body onload="window.print()">

<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include "koneksi.php";
$sql = $koneksi->query("SELECT * 
                        FROM podropship 
                        inner join poproduk on podropship.idpoproduk=poproduk.idpoproduk 
                        LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
                        LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
                        LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                        WHERE podropship.iddropship = '$_GET[id]' ");
//$sql = $koneksi->query("SELECT * FROM ekspedisi WHERE idekspedisi = '$_GET[id]' ");
$data = $sql->fetch_array();


  $datamitra=$koneksi->query("SELECT admin_mitra.namamitra,
                              admin_mitra.idadmin,
                              admin_mitra_cs.namacs,
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
                              WHERE podropship.invoice='$data[invoice]'");
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
   <th><p style="color:red; margin-bottom:-1.7px;" >
   Jika Pesanan Sudah Sampai Segera Cek Barang Sesuai Dengan Struk
    <table style="width:70%" >
   <tr align="center" >
   <th><p style="color:red; font-size:30;" >Kami Tidak Menerima Komplain Tanpa Foto/Video Lebih dari 1x24 Jam</p></th>
   </tr></table>
   
   </th>
   </tr></table>
</div>
<div style="border-bottom:1px dashed #000;;color:black;margin-bottom:10px;">
</div>
<table style="width:100%" align="center">
  
  <tr align="center" colspan="3" height="50">
   <td colspan="3"><?php

	include "../portalwnj/phpqr/phpqrcode/qrlib.php"; 

	$tempdir = "../portalwnj/phpqr/temp/"; //Nama folder tempat menyimpan file qrcode
	if (!file_exists($tempdir)) //Buat folder bername temp
    mkdir($tempdir);

    //isi qrcode jika di scan
    $codeContents = 'http://wanoja.com/'; 
	 
	//simpan file kedalam folder temp dengan nama 001.png
	QRcode::png($codeContents,$tempdir."001.png"); 


	echo '<center><img src="logowanoja.png" style="width:100px;" /></center>';
	//menampilkan file qrcode 
	//echo '<img src="'.$tempdir.'001.png" />';
 ?></td> 
  </tr>
  

 <tr align="center" height="50" >
<td>CS : <br><?php echo $tampilnama['namacs']; ?></td>
<td><?php if ($data['ekspedisi']=='tiki'): ?>
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
<?php endif ?><br><?php echo strtoupper($data['ekspedisi']); ?> / <?php echo strtoupper($data['layanan']); ?></td>


</tr> 
 
  
</table>


<table style="width:100%">
    
    
    <tr align="center">
        <td scope="col" >
       <font face="Palatino Linotype" > <h4 style="color:black; margin-bottom:10px;">
    Pengirim :<br><?php echo $data['namapengirim']; ?><br>Telp : <?php echo $data['tlppengirim']; ?></h4>
    </tr></td>
     </table>
<table style="width:100%">
    <tr align="center">
        <td scope="col" >
    <font face="Palatino Linotype" ><h4 style="color:black; margin-bottom:10px;">
        
    Penerima :<br> <?php echo $data['namapenerima']; ?> 
<br><?php echo $data['alamatpenerima']; ?>
<?php if ($data['province_name']<>""): ?>
<br><?php echo $data['province_name']; ?>,<?= $data['city_name']; ?>,<?= $data['subdistrict_name']; ?>  
<?php endif ?>

<br>Telp : <?php echo $data['tlppenerima']; ?>
         </h4></tr></td>
       
    </table>
    
    <tr align="center"><td>
    Mohon Halalkan Segala Kekurangan dan Ketidaknyamanan dalam Bermuamalah Bersama Kami
    </td></tr>
</table><br>

 <div style="border-bottom:1px dashed #000;;color:black;">
</div>
Mitra : <?php echo $tampilnama['idadmin']; ?>
<br>
 note :<br><b><?php echo $data['namapo']; ?> : <?php echo $data['invoice']; ?><b><br>
 <?php echo $data['keterangan']; ?>



<br>
 

<br>


</body>