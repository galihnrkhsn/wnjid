<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
$no=1;
$idpoproduk = $_GET['id'];
$jenis = $_GET['jenis'];
?>
<?php
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=Kekurangan Ambil Barang PO Per $jenis.xls");
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
<?php 
                        $sql2 = "SELECT * FROM poproduk  WHERE idpoproduk='$idpoproduk'";
                        $query2 = $koneksi->query($sql2);
                        while($apaya = $query2->fetch_assoc()){ ?>
                              <h1><?= $apaya['namapo'] ?></h1>
                      <?php  } ?>


<table class="table table-bordered" style="  font-size: 12px;">
            <thead>
                    <tr>

                        <th>No</th>
<?php if ($jenis=="DB"): ?>
                          
                        <th>Invoice</th>
                        <th>Nama DB</th>
                        <th>Nama Sub-DB</th>
                        <th>Nama CS</th>
                        <th>Custom</th>
<?php endif ?>                        
                        <th>Nama Produk</th>
                        <th>QTY</th>
                        <th>Ambil</th>
                        <th>Krg</th>
                         
                        </tr>
                      </thead>
                      <tbody>
                          <?php 
if ($jenis=="Variant") {
  # code...

$datapo=$koneksi->query("

SELECT 
                                podetail.variant,
                                pomitra.idpomitra,
                                pomitra.idpodetail,
                                pomitra.invoice,
                                pomitra.custom,
                                SUM(pomitra.jumlah) as jumlah
                                
                                FROM poproduk 
                                  inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                                  inner JOIN pokategori on pokategori.idpo=pomitra.idpo
                                  inner join podetail on podetail.idpodetail=pomitra.idpodetail
                                  WHERE pomitra.idpoproduk='$idpoproduk'  and pomitra.jumlah>0
                                  GROUP BY pomitra.custom, podetail.idpodetail
                                  ORDER BY podetail.variant                                                                   
                                ");
}
if ($jenis=="DB") {
  # code...

$datapo=$koneksi->query("
SELECT admin_mitra.namamitra,
        admin_mitra.idadmin,
        mitraagen.namaagen as agen,
        mitraagen.idmitraagen,
        mitrareseller.namaagen as reseller,
        mitrareseller.idmitrareseller,
        mitramarketer.namaagen as marketer,
        mitramarketer.idmitramarketer,
        admin_mitra_cs.namacs,
                                 podetail.variant,
                                 pomitra.idpomitra,
                                 pomitra.idpodetail,
                                 pomitra.invoice,
                                 pomitra.custom,
                                pomitra.jumlah
                                
                                 FROM poproduk 
                                   inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                                   inner join podetail on podetail.idpodetail=pomitra.idpodetail
                                   LEFT JOIN mitraagen ON pomitra.idmitraagen=mitraagen.idmitraagen
                                   LEFT JOIN mitrareseller ON pomitra.idmitrareseller=mitrareseller.idmitrareseller
                                   LEFT JOIN mitramarketer ON pomitra.idmitramarketer=mitramarketer.idmitramarketer
                                   LEFT JOIN admin_mitra ON (pomitra.idmitra=admin_mitra.idadmin 
                                   or mitraagen.idadmin=admin_mitra.idadmin 
                                   or mitrareseller.idadmin=admin_mitra.idadmin
                                   or mitramarketer.idadmin=admin_mitra.idadmin)
                                   LEFT JOIN admin_mitra_cs ON (admin_mitra.idadmin = admin_mitra_cs.idadmin 
                                   or mitraagen.idadmin=admin_mitra_cs.idadmin 
                                   or mitrareseller.idadmin=admin_mitra_cs.idadmin
                                   or mitramarketer.idadmin=admin_mitra_cs.idadmin)
                                   WHERE pomitra.idpoproduk='$idpoproduk'  and pomitra.jumlah>0
                                   -- GROUP BY pomitra.idpomitra
                                   ORDER BY admin_mitra.namamitra,pomitra.invoice, podetail.variant 
");
  }
  
                 




                            while($tampilkan=$datapo->fetch_assoc()){
if ($jenis=="Variant") {
                                 # code...
                                                             
                                $id = $tampilkan['idpodetail'];  
                                $custom = $tampilkan['custom'];  
            $datamitra=$koneksi->query("
              SELECT SUM(surat_jalan_po.progres) as progresnya
                                        FROM surat_jalan_po
                                        where surat_jalan_po.idpodetail='$id'
                                        and surat_jalan_po.custom = '$custom'
                            ");
} 
if ($jenis=="DB") {
              # code...
                        
 $id = $tampilkan['idpomitra'];
            $datamitra=$koneksi->query("
              SELECT SUM(surat_jalan_po.progres) as progresnya FROM surat_jalan_po
                            where surat_jalan_po.idpomitra='$id'
                            GROUP BY surat_jalan_po.idpomitra
                            ");    
}         
                            $tampilprogres=$datamitra->fetch_assoc();                                   
                                $kurang = $tampilkan['jumlah']-$tampilprogres['progresnya'];
                             ?>

                     
                        
                         
<?php if ($jenis=="DB") : ?> 
<?php if ($kurang!=0): ?>
                        <tr> 
                          <td><?= $no++; ?></td>                    
                          <td>
                            <?php echo $tampilkan['invoice']; ?>
                          </td>
                          <td><?php echo $tampilkan['namamitra']; ?></td>
                          <td>
                            
                            <?php echo $tampilkan['agen']; ?>
                            <?php echo $tampilkan['reseller']; ?>
                            <?php echo $tampilkan['marketer']; ?>
                          </td>
                          <td>

                            <?php echo $tampilkan['namacs']; ?>
                          </td>  
                          <td>

                            <?php echo $tampilkan['custom']; ?>
                          </td>                          
  
                          <td>
                            <?php echo $tampilkan['variant']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['jumlah']; ?>
                          </td>
                          <td>
                            <?php echo $tampilprogres['progresnya']; ?>
                          </td>
                          <td>
                              <?php echo $kurang ?> 
                          </td>
                        </tr>
<?php 
  $total_jumlah += $tampilkan['jumlah'];
  $total_ambil += $tampilprogres['progresnya'];
  $total_kurang += $kurang;
 ?>                        
<?php endif ?>                             
                        
<?php endif ?> 

<?php if ($jenis=="Variant") : ?>       
                        <tr> 
                          <td><?= $no++; ?></td> 
                          <td><?php echo $tampilkan['variant']; ?> <?php echo $tampilkan['custom']; ?></td>
                          <td>
                           <?php echo $tampilkan['jumlah']; ?>
                          </td>
                          <td>
                            <?php echo $tampilprogres['progresnya']; ?>
                          </td>
                          <td>
                              <?php echo $kurang; ?> 
                          </td>
                        </tr>
<?php 
  $total_jumlah += $tampilkan['jumlah'];
  $total_ambil += $tampilprogres['progresnya'];
  $total_kurang += $kurang;
 ?>                        
<?php endif ?>                          
<?php 


} 

?>
                      </tbody>
                      <tfoot>
                        <tr>
<?php if ($jenis=="DB") : ?>                           
                          <td colspan="7">Total</td>
<?php endif ?>       
<?php if ($jenis=="Variant") : ?>
                          <td colspan="2">Total</td>  
<?php endif ?>                       
                          <td><?= $total_jumlah; ?></td>
                          <td><?= $total_ambil; ?></td>
                          <td><?= $total_kurang; ?></td>
                        </tr>
                      </tfoot>
                    </table>
</body>
</html>
