<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$id = $_GET['id'];

$data_po=$koneksi->query("SELECT poproduk.namapo 
                                FROM poproduk 
                            where poproduk.idpoproduk='$id'");
$tampilkan_po=$data_po->fetch_assoc();
$namapo = $tampilkan_po['namapo'];
?>
<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$namapo Dropship Pulau Jawa.xls");
?>

              
<h3><?= $namapo; ?></h3>
                <table>
                      <thead>
                        <tr>
                            <th>
                            No    
                            </th>   
                            <th>CS</th> 
                            <th>
                            Invoice
                          </th>
						<th>Nama DB</th>
            <th>Nama Sub DB</th>
                           <th>
                            Provinsi
                          </th>
                           <th>
                            Kota
                          </th>
                           <th>
                            Kecamatan
                          </th>                     
                        </tr>
                      </thead>
                      <tbody>
                            <?php
                            $no = 1;
                            $dataproduk=$koneksi->query("SELECT admin_mitra.namamitra,
									                              mitraagen.namaagen as agen, 
									                              mitrareseller.namaagen as reseller, 
									                              mitramarketer.namaagen as marketer,
									                              podropship.invoice,                                                
                                                admin_mitra_cs.namacs,
                            									podropship.iddropship
                                                        FROM podropship
                                                        JOIN pomitra on podropship.invoice = pomitra.invoice
                                                        LEFT JOIN mitraagen on podropship.idmitraagen=mitraagen.idmitraagen 
							                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=podropship.idmitrareseller 
							                              LEFT JOIN mitramarketer on podropship.idmitramarketer=mitramarketer.idmitramarketer

							                              LEFT JOIN admin_mitra on (admin_mitra.idadmin=podropship.idadmin 
							                              						or mitraagen.idadmin=admin_mitra.idadmin 
							                              						or mitrareseller.idadmin=admin_mitra.idadmin 
							                              						or mitramarketer.idadmin=admin_mitra.idadmin) 
LEFT JOIN admin_mitra_cs ON (admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                                                     or mitraagen.idadmin=admin_mitra_cs.idadmin 
                                                                     or mitrareseller.idadmin=admin_mitra_cs.idadmin
                                                                     or mitramarketer.idadmin=admin_mitra_cs.idadmin)                                                          
                                                        WHERE podropship.idpoproduk = '$id'
                                                        and (podropship.provinsi = 3 
                                                          or podropship.provinsi = 5 
                                                          or podropship.provinsi = 6
                                                          or podropship.provinsi = 9
                                                          or podropship.provinsi = 10
                                                          or podropship.provinsi = 11)
                                                                                                    
                                                          GROUP BY podropship.iddropship
                                                        order by admin_mitra_cs.namacs, podropship.provinsi, podropship.invoice asc
                                                        ");
 
                            while($tampilkan=$dataproduk->fetch_assoc()){
                            	$idds = $tampilkan['iddropship'];

$data_provinsi=$koneksi->query("SELECT tb_ro_provinces.province_name,
										tb_ro_cities.city_name, 
										tb_ro_subdistricts.subdistrict_name 
                                FROM podropship
								LEFT JOIN tb_ro_provinces ON podropship.provinsi = tb_ro_provinces.province_id
								LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
								LEFT JOIN tb_ro_subdistricts ON podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                            where podropship.iddropship='$idds'");
$tampilkan_provinsi=$data_provinsi->fetch_assoc();
$provinsi = $tampilkan_provinsi['province_name']; 
$kota = $tampilkan_provinsi['city_name']; 
$kecamatan = $tampilkan_provinsi['subdistrict_name'];                            	
                            ?>
                        <tr>
                            <td>
                                <?php echo $no++; ?>
                            </td>  
                            <td>
                              <?php echo $tampilkan['namacs']; ?>
                            </td>  
                          <td>
                            <?php echo $tampilkan['invoice']; ?>
                          </td>
                           <td>
                           <?php echo $tampilkan['namamitra']; ?> 
                          </td>
                          <td>
                           <?php echo $tampilkan['agen']; ?> <?php echo $tampilkan['reseller']; ?> <?php echo $tampilkan['marketer']; ?> 
                          </td>
                          <td>
                            <?php echo $provinsi; ?>
                          </td>
                          <td>
                            <?php echo $kota; ?>
                          </td>
                          <td>
                            <?php echo $kecamatan; ?>
                          </td>
							
				
							
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                 

</html>

		                                    