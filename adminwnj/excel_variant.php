<?php 
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Kekurangan Ambil Barang PO Per $jenis.xls");
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


<table class="table" border="1" style="  font-size: 12px;">
            <thead>
                    <tr>

                        <th>No</th>
                        <th>Idpodetail</th>
                        <!--<th>Idpoproduk</th>-->
                        <!--<th>Invoice</th>-->
                        <th>Nama Produk</th>
                        <th>QTY</th>
                        <th>Ambil</th>
                        <th>Krg</th>
            </thead>
    <tbody>
<?php
$datapo=$koneksi->query("
            SELECT 
            podetail.variant,
            pomitra.idpomitra,
            pomitra.idpodetail,
            pomitra.invoice,
            SUM(pomitra.jumlah) as jumlah
            
            FROM poproduk 
              inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
              inner JOIN pokategori on pokategori.idpo=pomitra.idpo
              inner join podetail on podetail.idpodetail=pomitra.idpodetail
              WHERE pomitra.idpoproduk='$idpoproduk' and pomitra.jumlah>0
              GROUP BY podetail.idpodetail
              ORDER BY podetail.idpodetail                                                                   
        ");
        $no = 1;

    while($tampilkan=$datapo->fetch_assoc()) {

          $id = $tampilkan['idpodetail'];
            $datamitra=$koneksi->query("
              SELECT SUM(surat_jalan_po.progres) as progresnya, pomitra.invoice, pomitra.idpoproduk
                            FROM surat_jalan_po
                            INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                            where surat_jalan_po.idpodetail='$id'
                            AND pomitra.idpoproduk = '$idpoproduk'
                            ORDER BY surat_jalan_po.idpodetail
                            ");
                            $tampilprogres=$datamitra->fetch_assoc();                                   
                                $kurang = $tampilkan['jumlah']-$tampilprogres['progresnya'];
 ?>
                        <tr> 
                          <td><?= $no++ ?></td>
                          <td><?= $tampilkan['idpodetail'] ?></td>
                          <!--<td><?= $tampilprogres['idpoproduk'] ?></td>-->
                          <!--<td><?= $tampilprogres['invoice'] ?></td>-->
                          <td><?= $tampilkan['variant']; ?></td>
                          <td><?= $tampilkan['jumlah']; ?></td>
                          <td><?= $tampilprogres['progresnya']; ?></td>
                          <td><?= $kurang ?></td>
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
                          <td colspan="2">Total</td>    
                          <td><?= $tampilkan['idpo']; ?></td>              
                          <td><?= $total_jumlah; ?></td>
                          <td><?= $total_ambil; ?></td>
                          <td><?= $total_kurang; ?></td>
                        </tr>
                      </tfoot>
                    </table>
</body>
</html>