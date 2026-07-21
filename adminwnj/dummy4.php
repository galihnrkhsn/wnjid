<?php
include "koneksi.php";
        

?>
<style type="text/css">
  @media print {
  footer {page-break-after: always;}
}
</style>
<?php 
if(isset($_POST['but_export'])){
          if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

  $query = "SELECT 
  			admin_mitra.namamitra,admin_mitra_cs.namacs,admin_mitra.idadmin,
					  admin_mitra.alamat,admin_mitra.whatsapp,
                      poproduk.namapo,
                      poproduk.idpoproduk,
                      pomitra.invoice,
                      tb_ro_provinces.province_name as provinsi,
                      tb_ro_cities.city_name as kota,
                      tb_ro_subdistricts.subdistrict_name as kecamatan 

                      FROM pomitra 
                      LEFT JOIN admin_mitra ON admin_mitra.idadmin = pomitra.idmitra
                      LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin
                      LEFT JOIN tb_ro_provinces on admin_mitra.provinsi = tb_ro_provinces.province_id
                      LEFT JOIN tb_ro_cities on admin_mitra.kota = tb_ro_cities.city_id
                      LEFT JOIN tb_ro_subdistricts on admin_mitra.kecamatan = tb_ro_subdistricts.subdistrict_id 
                        JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk
        WHERE pomitra.invoice='$updateid'";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);                   
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
            <?php echo $datapo['idadmin']; ?></td>
            <td>CS : <br><?php echo $datapo['namacs']; ?>

            </td>
                <td>
                    Ekspedisi : 
                    <br>
                    .
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
                            WNJ
                            <br>
                            Telp : 089655775486
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
                            <?php echo $datapo['namamitra']; ?> 
                            <br>
                            Telp : <?php echo $datapo['whatsapp']; ?>
                            <br>
                            <?php echo $datapo['alamat']; ?>
                            <br>
                            <?php echo $datapo['kecamatan']; ?>, <?php echo $datapo['kota']; ?>, <?php echo $datapo['provinsi']; ?>
                            
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
        <b><?php echo $datapo['namapo']; ?> : <?php echo $datapo['invoice']; ?><b>
            <br>
<?php 
                            $data_detail=$koneksi->query("SELECT 
                              podetail.variant, pomitra.jumlah
                              FROM pomitra
                              JOIN podetail on podetail.idpodetail = pomitra.idpodetail    
                              WHERE pomitra.invoice = '$updateid'
                              and pomitra.jumlah>0 ");
                          
                            while($tampilkan_detail=$data_detail->fetch_assoc()){
                                echo $tampilkan_detail['variant'];
                                echo "(";
                                echo $tampilkan_detail['jumlah'];
                                echo ")";
                                echo "<br>";
                            }
 ?>      
    </p>

    <div class="footer"></div>
    <footer></footer>
    <!-- End of Content Wrapper -->
<?php                
 }
              }         
        } 
        ?>	
<script>
 window.print();
</script>        