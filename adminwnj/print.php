<?php
    session_start();
    include 'koneksi.php';
    $iddropship = $_GET["id"];
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
                        WHERE podropship.iddropship = '$iddropship' ");
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
                              WHERE podropship.iddropship='$iddropship'");
                            $tampilnama=$datamitra->fetch_assoc();   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <style type="text/css">
        td{
            border: 1px solid black;
        }
        th{
            border: 1px solid red;
        }
    </style>
</head>
<body>
    
    
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

    <script type="text/javascript">
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
