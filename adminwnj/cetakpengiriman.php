<body onload="window.print()">
<?php
include "koneksi.php";
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
$sqldb = $koneksi->query("SELECT *,COUNT(*) as jumlah FROM orderpengiriman inner join ordermitra 
	on orderpengiriman.invoice=ordermitra.invoice WHERE idorderp = '$_GET[id]' ");
$datadb = $sqldb->fetch_array();

$sqlagen = $koneksi->query("SELECT *,COUNT(*) as jumlah FROM orderpengiriman inner join orderagen 
	on orderpengiriman.invoice=orderagen.invoice WHERE idorderp = '$_GET[id]' ");
$dataagen = $sqlagen->fetch_array();

$sqlreseller = $koneksi->query("SELECT *,COUNT(*) as jumlah FROM orderpengiriman inner join orderreseller 
	on orderpengiriman.invoice=orderreseller.invoice WHERE idorderp = '$_GET[id]' ");
$datareseller = $sqlreseller->fetch_array();

$sqlmarketer = $koneksi->query("SELECT *,COUNT(*) as jumlah FROM orderpengiriman inner join ordermarketer 
	on orderpengiriman.invoice=ordermarketer.invoice WHERE idorderp = '$_GET[id]' ");
$datamarketer = $sqlmarketer->fetch_array();

if ($dataagen['jumlah']>0){
    $sql = $koneksi->query("SELECT *, orderpengiriman.alamat as alamatnya, orderpengiriman.provinsi as provinsinya, orderpengiriman.kota as kotanya, orderpengiriman.kecamatan as kecamatannya,  orderpengiriman.kodepos as kodeposnya, mitraagen.idmitraagen as idsubmitra FROM orderpengiriman inner join orderagen on orderpengiriman.invoice=orderagen.invoice 
                            inner JOIN mitraagen on mitraagen.idmitraagen=orderagen.idmitraagen 
                            WHERE idorderp = '$_GET[id]' ");
}

if ($datareseller['jumlah']>0){
    $sql = $koneksi->query("SELECT *, orderpengiriman.alamat as alamatnya, orderpengiriman.provinsi as provinsinya, orderpengiriman.kota as kotanya, orderpengiriman.kecamatan as kecamatannya,  orderpengiriman.kodepos as kodeposnya, mitrareseller.idmitrareseller as idsubmitra FROM orderpengiriman inner join orderreseller on orderpengiriman.invoice=orderreseller.invoice 
                            inner JOIN mitrareseller on mitrareseller.idmitrareseller=orderreseller.idmitrareseller 
                            WHERE idorderp = '$_GET[id]' ");
}

if ($datamarketer['jumlah']>0){
    $sql = $koneksi->query("SELECT *, orderpengiriman.alamat as alamatnya, orderpengiriman.provinsi as provinsinya, orderpengiriman.kota as kotanya, orderpengiriman.kecamatan as kecamatannya,  orderpengiriman.kodepos as kodeposnya,mitramarketer.idmitramarketer as idsubmitra FROM orderpengiriman inner join ordermarketer on orderpengiriman.invoice=ordermarketer.invoice 
                            inner JOIN mitramarketer on mitramarketer.idmitramarketer=ordermarketer.idmitramarketer 
                            WHERE idorderp = '$_GET[id]' ");
}

if ($datadb['jumlah']>0){
    $sql = $koneksi->query("SELECT *,COUNT(*) as jumlah, orderpengiriman.alamat as alamatnya, orderpengiriman.provinsi as provinsinya, orderpengiriman.kota as kotanya, orderpengiriman.kecamatan as kecamatannya,  orderpengiriman.kodepos as kodeposnya FROM orderpengiriman inner join ordermitra on orderpengiriman.invoice=ordermitra.invoice 
                            inner JOIN admin_mitra on admin_mitra.idadmin=ordermitra.idmitra 
                            WHERE idorderp = '$_GET[id]' ");
}
$data = $sql->fetch_array() ;
$idadminya = $data['idadmin'];
  $datamitra=$koneksi->query("SELECT admin_mitra_cs.namacs, admin_mitra.namamitra, admin_mitra.idadmin
                                FROM admin_mitra_cs join admin_mitra on admin_mitra.idadmin = admin_mitra_cs.idadmin WHERE admin_mitra_cs.idadmin='$idadminya'");
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
    $codeContents = 'http://wnj.web.id/'; 
	 
	//simpan file kedalam folder temp dengan nama 001.png
	QRcode::png($codeContents,$tempdir."001.png"); 


	echo '<center><img src="logowanoja.png" style="width:100px;" /></center>';
	//menampilkan file qrcode 
	//echo '<img src="'.$tempdir.'001.png" />';
 ?></td> 
  </tr>
  

 <tr align="center" height="50" >
<td>CS : <?php echo $tampilnama['namacs']; ?><br>
    Kode Mitra : <?php echo $tampilnama['idadmin']; ?> 
    <?php if ($data['idsubmitra']<>""): ?>
      / 
      <?php if ($dataagen['jumlah']>0): ?>
        A<?= $data['idsubmitra'] ?>
      <?php endif ?>
      <?php if ($datareseller['jumlah']>0): ?>
        R<?= $data['idsubmitra'] ?>
      <?php endif ?>
      <?php if ($datamarketer['jumlah']>0): ?>
        M<?= $data['idsubmitra'] ?>
      <?php endif ?>
      
    <?php endif ?>
    
</td>


<td><?php if ($data['ekspedisi']=='tiki' and $data['layanan']=='REG'): ?>
  <img src="img/TIKIREG.jpg" alt="" width="100" height="30" style="margin-top:2px;">
  <?php elseif ($data['ekspedisi']=='Ambil ke Pusat'): ?>
    <img src="img/Ambil.jpg" alt="" width="100" height="30" style="margin-top:2px;">
  <?php elseif ($data['ekspedisi']=='Disatukan'): ?>
    <img src="img/Disatukan.jpg" alt="" width="100" height="30" style="margin-top:2px;">
  <?php elseif ($data['ekspedisi']=='Gosend'): ?>
    <img src="img/Gosend.jpg" alt="" width="100" height="30" style="margin-top:2px;">   
  <?php elseif ($data['ekspedisi']=='anteraja'): ?>
    <img src="img/anteraja.jpg" alt="" width="100" height="30" style="margin-top:2px;">
  <?php elseif ($data['ekspedisi']=='sicepat' and $data['layanan']=='REG'): ?>
    <img src="img/Sicepat REG.jpg" alt="" width="100" height="30" style="margin-top:2px;">   
  <?php elseif ($data['ekspedisi']=='wahana' and $data['layanan']=='REGPACK'): ?>
    <img src="img/Wahana Ekspres.jpg" alt="" width="100" height="30" style="margin-top:2px;">  
  <?php elseif ($data['ekspedisi']=='jne' and $data['layanan']=='REGPACK'): ?>
    <img src="img/JNE REG.jpg" alt="" width="100" height="30" style="margin-top:2px;">            
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
    <img src="img/<?php echo $data['layanan']; ?>.jpg" alt="" width="100" height="30" style="margin-top:2px;">
<?php endif ?><br><?php echo strtoupper($data['ekspedisi']); ?> / <?php echo strtoupper($data['layanan']); ?></td>
<!--<td><?php echo $data['status']; ?><br><?php echo $data['pcs']; ?></td>-->

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
<br><?php echo $data['alamatnya']; ?><br>  Kota/Kab <?php echo $data['kotanya']; ?> <br>  Provinsi <?php echo $data['provinsinya']; ?> <br> Kode POS <?php echo $data['kodeposnya']; ?><br>Telp : <?php echo $data['tlppenerima']; ?>
         </h4></tr></td>
       
    </table>
    
    <tr align="center"><td>
    Mohon Halalkan Segala Kekurangan dan Ketidaknyamanan dalam Bermuamalah Bersama Kami
    </td></tr>
</table><br>

 <div style="border-bottom:1px dashed #000;;color:black;">
</div><br>
 Tanggal : <?php echo date('d-m-Y'); ?><br>
 note : #<?php echo $data['invoice']; ?>
<?php
$invoice=$data['invoice'];
if ($dataagen['jumlah']>0){
    $datao=$koneksi->query("SELECT produk.namaproduk,orderagen.jumlah FROM orderagen inner join produk on orderagen.idproduk=produk.idproduk where orderagen.invoice='$invoice' ");
}

if ($datareseller['jumlah']>0){
    $datao=$koneksi->query("SELECT produk.namaproduk,orderreseller.jumlah FROM orderreseller 
    	inner join produk on orderreseller.idproduk=produk.idproduk 
    	where orderreseller.invoice='$invoice' ");
}

if ($datamarketer['jumlah']>0){
    $datao=$koneksi->query("SELECT produk.namaproduk,ordermarketer.jumlah FROM ordermarketer 
    	inner join produk on ordermarketer.idproduk=produk.idproduk 
    	where ordermarketer.invoice='$invoice' ");
}

if ($datadb['jumlah']>0){

     if (substr($invoice,0,1)=="F") {
 
    $datao=$koneksi->query("SELECT produk.namaproduk,SUM(ordermitra.jumlah) as jumlah FROM ordermitra 
      inner join produk on ordermitra.idproduk=produk.idproduk 
      where ordermitra.invoice='$invoice'
      GROUP BY produk.idproduk");
     }
     else{
      
    $datao=$koneksi->query("SELECT produk.namaproduk,ordermitra.jumlah FROM ordermitra 
      inner join produk on ordermitra.idproduk=produk.idproduk 
      where ordermitra.invoice='$invoice' ");
     }  
}
while($tampilkano=$datao->fetch_assoc()){
?>
<br><?php echo $tampilkano['namaproduk']; ?> <?php echo $tampilkano['jumlah']; ?>pcs, <?php }?>
 

</body>