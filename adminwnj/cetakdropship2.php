<body onload="window.print()">
<?php
include "koneksi.php";
$sql = $koneksi->query("SELECT * FROM podropship_konin inner join poproduk on podropship_konin.idpoproduk=poproduk.idpoproduk WHERE podropship_konin.invoice = '$_GET[id]' ");
//$sql = $koneksi->query("SELECT * FROM ekspedisi WHERE idekspedisi = '$_GET[id]' ");
$data = $sql->fetch_array() ;



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
<!--<td>cso : <br><?php echo $data['namacs']; ?></td>-->
<td><img src="ekpedisi/ekpedisi/<?php echo $data['ekspedisi']; ?>.jpg" alt="<?php echo $data['ekspedisi']; ?>" width="100" height="30" style="margin-top:2px;"></td>
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
<br><?php echo $data['alamatpenerima']; ?><br>Telp : <?php echo $data['tlppenerima']; ?>
         </h4></tr></td>
       
    </table>
    
    <tr align="center"><td>
    Mohon Halalkan Segala Kekurangan dan Ketidaknyamanan dalam Bermuamalah Bersama Kami
    </td></tr>
</table><br>

 <div style="border-bottom:1px dashed #000;;color:black;">
</div>
 note :<br><b><?php echo $data['namapo']; ?><b> invoice: <?php echo $data['invoice']; ?><br>
 <?php echo $data['keterangan']; ?>



<br>
 

<br>


</body>