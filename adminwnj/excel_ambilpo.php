<?php 

include 'koneksi.php'; 

$idpoproduk = $_GET['id'];

$data=$koneksi->query("SELECT namapo FROM poproduk
                            where idpoproduk='$idpoproduk'");
$tampil=$data->fetch_assoc();
$namapo=$tampil['namapo']; 
?>
<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Ambil Barang PO.xls");
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
<h4><?= $namapo; ?>(<?= $idpoproduk; ?>)</h4>
<h5>Distributor</h5>
 <table border="1px solid">
        <thead>
        <tr>

          <th>Invoice</th>
          <th>Nama Mitra</th>
            
                <th>Krg</th>
                <th>Nama CS</th>
                    
                </tr>
        </thead>
        <tbody>
              <?php 
             
                $datapo=$koneksi->query("SELECT 
                  admin_mitra.namamitra,
                  admin_mitra.idadmin,
                  poproduk.namapo,
                  pomitra.tgl,
                  pomitra.invoice,
                  admin_mitra_cs.namacs,
                  SUM(pomitra.jumlah) as qty
                  FROM `pomitra` 
                  JOIN poproduk on poproduk.idpoproduk = pomitra.idpoproduk
                  inner join admin_mitra on pomitra.idmitra=admin_mitra.idadmin
                  LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin 
                  WHERE pomitra.idpoproduk = '$idpoproduk' and pomitra.jumlah>0
                  GROUP BY pomitra.invoice");
                $no=1;
              
                while($tampilkan=$datapo->fetch_assoc()){
                  $invoice1 = $tampilkan['invoice'];
$datamitra=$koneksi->query("SELECT SUM(surat_jalan_po.progres) as progresnya, surat_jalan_po.invoice FROM surat_jalan_po
                            where surat_jalan_po.invoice='$invoice1'");
                            $tampilprogres=$datamitra->fetch_assoc();                                   
                                $kurang = $tampilkan['qty']-$tampilprogres['progresnya'];                  
                ?>
                <tr>
 
                  <td>
                       <?php echo $tampilkan['invoice']; ?>
                       
                      </td>
                   <td>
                  <?php echo $tampilkan['namamitra']; ?> (<?php echo $tampilkan['idadmin']; ?>) 

                  </td>
                  
                  <td style="text-align:center"><?php echo $kurang; ?></td>
                  <td>
                     <strong><?php echo $tampilkan['namacs']; ?></strong>
                </td>

              
                        </tr>
<?php 
$total_kurang += $kurang;
 ?>                        
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2">Total</td>
                          <td class="text-center"><?= $total_kurang ?></td>
                          <td ></td>
                        </tr>
                      </tfoot>
                    </table>

<br>
<h5>Sub-Distributor</h5>
<h5>Agen</h5>
 <table border="1px solid">
        <thead>
        <tr>
          <th>Invoice</th>
          <th>Nama Mitra</th>
          <th>Nama DB</th>
          <th>Krg</th>
          <th>Nama CS</th>
        </tr>
        </thead>
        <tbody>
              <?php 
                            $kurang= 0 ;
                            $total_kurang = 0;              
                $datapo=$koneksi->query("SELECT 
                  admin_mitra.namamitra,
                  admin_mitra.idadmin,
                  mitraagen.namaagen,
                  mitraagen.idmitraagen,
                  pomitra.tgl,
                  pomitra.invoice,
                  admin_mitra_cs.namacs,
                  SUM(pomitra.jumlah) as qty
                  FROM `pomitra` 
                  inner join mitraagen on pomitra.idmitraagen=mitraagen.idmitraagen
                  JOIN admin_mitra on mitraagen.idadmin = admin_mitra.idadmin
                  JOIN admin_mitra_cs on mitraagen.idadmin = admin_mitra_cs.idadmin
                  WHERE pomitra.idpoproduk = '$idpoproduk' and pomitra.jumlah>0
                  GROUP BY pomitra.invoice");
                $no=1;
              
                while($tampilkanagen=$datapo->fetch_assoc()){
                  $invoice2 = $tampilkanagen['invoice'];
$datamitra=$koneksi->query("SELECT SUM(surat_jalan_po.progres) as progresnya, surat_jalan_po.invoice FROM surat_jalan_po
                            where surat_jalan_po.invoice='$invoice2'");
                            $tampilprogres=$datamitra->fetch_assoc();       

                                $kurang = $tampilkanagen['qty']-$tampilprogres['progresnya'];                      
                ?>
                <tr>

                  <td>
                      <?php echo $tampilkanagen['invoice']; ?>
                       
                      </td>
                   <td>
                   <?php echo $tampilkanagen['namaagen']; ?> (<?php echo $tampilkanagen['idmitraagen']; ?>) 

                  </td>
                  <td>
                   <?php echo $tampilkanagen['namamitra']; ?> (<?php echo $tampilkanagen['idadmin']; ?>) 

                  </td>
                  <td style="text-align:center"><?php echo $kurang; ?></td>
                  <td>
                     <strong><?php echo $tampilkanagen['namacs']; ?></strong>
                </td> 
              
                        </tr>
<?php 
$total_kurang += $kurang;
 ?>                        
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3">Total</td>
                          <td class="text-center"><?= $total_kurang ?></td>
                          <td></td>
                        </tr>
                      </tfoot>
                    </table>      
<h5>Reseller</h5>
 <table border="1px solid">
        <thead>
        <tr>
          <th>Invoice</th>
          <th>Nama Mitra</th>
          <th>Nama DB</th>
          <th>Krg</th>
          <th>Nama CS</th>
        </tr>
        </thead>
        <tbody>
              <?php 
                            $kurang= 0 ;
                            $total_kurang = 0;              
                $datapo=$koneksi->query("SELECT 
                  admin_mitra.namamitra,
                  admin_mitra.idadmin,
                  mitrareseller.namaagen,
                  mitrareseller.idmitrareseller,
                  pomitra.tgl,
                  pomitra.invoice,
                  admin_mitra_cs.namacs,
                  SUM(pomitra.jumlah) as qty
                  FROM `pomitra` 
                  inner join mitrareseller on pomitra.idmitrareseller=mitrareseller.idmitrareseller
                  JOIN admin_mitra on mitrareseller.idadmin = admin_mitra.idadmin
                  JOIN admin_mitra_cs on mitrareseller.idadmin = admin_mitra_cs.idadmin  
                  WHERE pomitra.idpoproduk = '$idpoproduk' and pomitra.jumlah>0
                  GROUP BY pomitra.invoice");
                $no=1;
              
                while($tampilkanreseller=$datapo->fetch_assoc()){
                  $invoice3 = $tampilkanreseller['invoice'];
$datamitra=$koneksi->query("SELECT SUM(surat_jalan_po.progres) as progresnya, surat_jalan_po.invoice FROM surat_jalan_po
                            where surat_jalan_po.invoice='$invoice3'");
                            $tampilprogres=$datamitra->fetch_assoc();     

                                $kurang = $tampilkanreseller['qty']-$tampilprogres['progresnya'];                   
                ?>
                <tr> 
                  <td>
                    <?php echo $tampilkanreseller['invoice']; ?>
                       
                      </td>
                   <td>
                   <?php echo $tampilkanreseller['namaagen']; ?> (<?php echo $tampilkanreseller['idmitrareseller']; ?>) 
                  </td>
                  
                  <td>
                   <?php echo $tampilkanreseller['namamitra']; ?> (<?php echo $tampilkanreseller['idadmin']; ?>) 

                  </td>
                  <td style="text-align:center"><?php echo $kurang; ?></td>
                  <td>
                     <strong><?php echo $tampilkanreseller['namacs']; ?></strong>
                </td> 
              
                        </tr>
<?php 
$total_kurang += $kurang;
 ?>                        
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3">Total</td>
                          <td class="text-center"><?= $total_kurang ?></td>
                          <td></td>
                        </tr>
                      </tfoot>
                    </table>  

<h5>Marketer</h5>
 <table border="1px solid">
        <thead>
        <tr>
          <th>Invoice</th>
          <th>Nama Mitra</th>
          <th>Nama DB</th>
          <th>Krg</th>
          <th>Nama CS</th>
        </tr>
        </thead>
        <tbody>
              <?php 
                            $kurang= 0 ;
                            $total_kurang = 0;               
                $datapo=$koneksi->query("SELECT 
                  admin_mitra.namamitra,
                  admin_mitra.idadmin,
                  mitramarketer.namaagen,
                  mitramarketer.idmitramarketer,
                  pomitra.tgl,
                  pomitra.invoice,
                  admin_mitra_cs.namacs,
                  SUM(pomitra.jumlah) as qty
                  FROM `pomitra` 
                  inner join mitramarketer on pomitra.idmitramarketer=mitramarketer.idmitramarketer
                  JOIN admin_mitra on mitramarketer.idadmin = admin_mitra.idadmin 
                  JOIN admin_mitra_cs on mitramarketer.idadmin = admin_mitra_cs.idadmin 
                  WHERE pomitra.idpoproduk = '$idpoproduk' and pomitra.jumlah>0
                  GROUP BY pomitra.invoice");
                $no=1;
              
                while($tampilkanmarketer=$datapo->fetch_assoc()){
                  $invoice4 = $tampilkanmarketer['invoice'];
$datamitra=$koneksi->query("SELECT SUM(surat_jalan_po.progres) as progresnya, surat_jalan_po.invoice FROM surat_jalan_po
                            where surat_jalan_po.invoice='$invoice4'");
                            $tampilprogres=$datamitra->fetch_assoc();    

                                $kurang = $tampilkanmarketer['qty']-$tampilprogres['progresnya'];                    
                ?>
                <tr>
                  <td>
                    <?php echo $tampilkanmarketer['invoice']; ?>
                       
                      </td>
                   <td>
                   <?php echo $tampilkanmarketer['namaagen']; ?> (<?php echo $tampilkanmarketer['idmitramarketer']; ?>) 
                  </td>
                  <td>
                   <?php echo $tampilkanmarketer['namamitra']; ?> (<?php echo $tampilkanmarketer['idadmin']; ?>) 

                  </td>
                  
                  <td style="text-align:center"><?php echo $kurang; ?></td>
                  <td>
                     <strong><?php echo $tampilkanmarketer['namacs']; ?></strong>
                </td>
              
                        </tr>
<?php 
$total_kurang += $kurang;
 ?>                        
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3">Total</td>
                          <td class="text-center"><?= $total_kurang ?></td>
                          <td></td>
                        </tr>
                      </tfoot>
                    </table>                                                    
</body>
</html>
