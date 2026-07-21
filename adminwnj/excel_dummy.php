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
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Kekurangan Ambil Barang PO Per $jenis.xls");
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
                        $apaya = $query2->fetch_assoc();
                        $namapo = $apaya['namapo'];
                        ?>
                              <h1><?= $namapo ?></h1>


<table class="table table-bordered" style="  font-size: 12px;">
            <thead>
                    <tr>

                        <th>No</th>
                        <th>Invoice</th>
                        <th>Nama DB</th>
                        <th>Nama Sub-DB</th>
                        <th>Nama CS</th>
                        <th>Nama Produk</th>
                        <th>QTY</th>
                        <th>Ambil</th>
                        <th>Krg</th>
            </thead>
    <tbody>
<?php
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
                                   WHERE pomitra.idpoproduk='263'
                                   AND pomitra.idpomitra > '3071285'
                                   AND pomitra.jumlah > 0
                                   ORDER BY pomitra.idpomitra ASC
                                   LIMIT 3000
");

    while($tampilkan=$datapo->fetch_assoc()) {

$id = $tampilkan['idpomitra'];
            $datamitra=$koneksi->query("
              SELECT SUM(surat_jalan_po.progres) as progresnya FROM surat_jalan_po
                            where surat_jalan_po.idpomitra='$id'
                            GROUP BY surat_jalan_po.idpomitra
                            ");
                            $tampilprogres=$datamitra->fetch_assoc();                                   
                                $kurang = $tampilkan['jumlah']-$tampilprogres['progresnya'];
 ?>
                        <tr> 
                          <td><?= $tampilkan['idpomitra'] ?></td>                    
                          <td><?php echo $tampilkan['invoice']; ?></td>
                          <td><?php echo $tampilkan['namamitra']; ?></td>
                          <td><?php echo $tampilkan['agen']; ?><?php echo $tampilkan['reseller']; ?><?php echo $tampilkan['marketer']; ?></td>
                          <td><?php echo $tampilkan['namacs']; ?></td>  
                          <td><?php echo $tampilkan['custom']; ?></td>
                          <td><?php echo $tampilkan['variant']; ?></td>
                          <td><?php echo $tampilkan['jumlah']; ?></td>
                          <td><?php echo $tampilprogres['progresnya']; ?></td>
                          <td><?php echo $kurang ?></td>
                        </tr>
<?php 
  $total_jumlah += $tampilkan['jumlah'];
  $total_ambil += $tampilprogres['progresnya'];
  $total_kurang += $kurang;
    }
 ?>
    
    </tbody>
                      <tfoot>
                        <tr>   
                          <td colspan="7">Total</td>                  
                          <td><?= $total_jumlah; ?></td>
                          <td><?= $total_ambil; ?></td>
                          <td><?= $total_kurang; ?></td>
                        </tr>
                      </tfoot>
                    </table>
</body>
</html>