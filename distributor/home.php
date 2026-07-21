
<hr>
<center>
    <img src="Logo3g.png" style="height:150px;width:200px;margin-bottom:-70px;">
<?php
	include "phpqrcode/qrlib.php"; 

	$tempdir = "temp/"; //Nama folder tempat menyimpan file qrcode
	if (!file_exists($tempdir)) //Buat folder bername temp
    mkdir($tempdir);

    //isi qrcode jika di scan
    $codeContents = 'http://webmitra.wanoja.com/admin/'; 
	 
	//simpan file kedalam temp 
	//Kode QR mendukung empat tingkat koreksi kesalahan untuk memungkinkan pemulihan data yang hilang, salah dibaca, atau dikaburkan
	
	
    QRcode::png($codeContents, $tempdir.'006_H.png', QR_ECLEVEL_H);

    echo '<h2></h2>';
	//menampilkan file qrcode 

    echo '<img src="'.$tempdir.'006_H.png" />'; 
?>

<hr>
<table>
    <tr>
        <td><img src="fb.png" style="height:40px;"></td>
        <td><img src="ig.png" style="height:40px;"></td>
        <td><img src="yt1.png" style="height:40px;"></td>
    </tr>
</table>
</center>