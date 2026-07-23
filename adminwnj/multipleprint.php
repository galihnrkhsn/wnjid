<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["administrator"])) {
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
 <title>Cetak invoice</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
<style type="text/css">
  @media print {
  footer {page-break-after: always;}
}
</style>
<style type="text/css">
  table, th, td, tr {
  border: 2px solid black;
   border-collapse: collapse;
}
body{
  color: black;
}
</style>
<body>

          <!-- Content Row -->
<br>
<br>
<br>

<?php 
if(isset($_POST['but_export'])){
          if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

$invoice = $_POST['invoice'.$updateid] ?? '';

  $stmtPo = $koneksi->prepare("SELECT
            poproduk.namapo
        FROM poproduk
        inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk
        WHERE pomitra.invoice=?");
  $stmtPo->bind_param('s', $invoice);
  $stmtPo->execute();
  $poInfo = $stmtPo->get_result()->fetch_assoc();

  $stmtMitra = $koneksi->prepare("SELECT admin_mitra.namamitra as db,
                                    admin_mitra.idadmin,
                                    admin_mitra_cs.namacs,
                              mitraagen.namaagen as agen,
                              mitraagen.idmitraagen as idagen,
                              mitrareseller.namaagen as reseller,
                              mitrareseller.idmitrareseller as idreseller,
                              mitramarketer.namaagen as marketer,
                              mitramarketer.idmitramarketer as idmarketer,
                              podropship.namapenerima,
                              poproduk.idpoproduk
                              FROM `pomitra`
                              LEFT JOIN podropship on pomitra.invoice=podropship.invoice
                              LEFT JOIN mitraagen on mitraagen.idmitraagen=pomitra.idmitraagen
                              LEFT JOIN mitrareseller on mitrareseller.idmitrareseller=pomitra.idmitrareseller
                              LEFT JOIN mitramarketer on mitramarketer.idmitramarketer=pomitra.idmitramarketer
                              LEFT JOIN admin_mitra on admin_mitra.idadmin = COALESCE(pomitra.idmitra, mitraagen.idadmin, mitrareseller.idadmin, mitramarketer.idadmin)
                              LEFT JOIN admin_mitra_cs on admin_mitra.idadmin = admin_mitra_cs.idadmin
                              INNER JOIN poproduk on pomitra.idpoproduk=poproduk.idpoproduk
                              WHERE pomitra.invoice=?");
  $stmtMitra->bind_param('s', $invoice);
  $stmtMitra->execute();
  $tampilnama = $stmtMitra->get_result()->fetch_assoc();
 ?>

<center><p style="font-size:60;margin-bottom:0;"><strong>WNJ.ID</strong></p></center>
<center><p style="font-size:60;margin-bottom:0;"><strong><?= htmlspecialchars($poInfo['namapo'] ?? '') ?></strong></p></center>
<center><p style="font-size:50;margin-top:0;"><strong>Inv. <?= htmlspecialchars($invoice) ?></strong></p></center>

  <div class="row align-items-start">
    <div class="col">
       <h5><strong style="float:left">Mitra : <?php echo htmlspecialchars($tampilnama['db'] ?? '') ?>(<?php echo htmlspecialchars($tampilnama['idadmin'] ?? '') ?>)
       <?php if ($tampilnama['idagen'] or $tampilnama['idreseller'] or $tampilnama['idmarketer']): ?>
       / <?= htmlspecialchars($tampilnama['idagen'] ?? '') ?><?= htmlspecialchars($tampilnama['idreseller'] ?? '') ?><?= htmlspecialchars($tampilnama['idmarketer'] ?? '') ?>
       <?php endif ?>
       </strong></h5>
    </div>
    <div class="col">
    </div>
    <div class="col">
      <h5><strong style="float:rigth">Nama CS : <?php echo htmlspecialchars($tampilnama['namacs'] ?? '') ?> </strong></h5>
    </div>
  </div>

    <?php if ($tampilnama['idpoproduk']=='97' || $tampilnama['idpoproduk'] == '259' || $tampilnama['idpoproduk'] == '267' || $tampilnama['idpoproduk'] == '277' || $tampilnama['idpoproduk'] == '239') { ?>
      <strong> Nama Keluarga : <?php echo htmlspecialchars($tampilnama['namapenerima'] ?? '') ?> </strong><br>
    <?php } ?>

     <?php if ($tampilnama['agen']<>'' OR $tampilnama['reseller']<>'' OR $tampilnama['marketer']<>'') { ?>
    <strong> Nama Sub DB : <?php echo htmlspecialchars($tampilnama['agen'] ?? '') ?> <?php echo htmlspecialchars($tampilnama['reseller'] ?? '') ?>  <?php echo htmlspecialchars($tampilnama['marketer'] ?? '') ?></strong>
  <?php } ?>
  <br>
    <?php if($tampilnama['idpoproduk'] == '276' || $tampilnama['idpoproduk'] == '278' || $tampilnama['idpoproduk'] == '281' || $tampilnama['idpoproduk'] == '283') : ?>
            
    <?php else : ?>
        <h5>Tanggal : <?php echo date('d-m-Y'); ?></h5><br>
    <?php endif; ?>
  <div class="table-responsive mt-3">
        <table class="" style="font-size: 19px;width: 100%">
            <tr>
              <th>No</th>
              <th>Nama Produk</th>
              <th>Qty</th>
              <th>Custom</th>
              <th>Font</th>
              <th>Template</th>
            <?php if($tampilnama['idpoproduk'] == '276' || $tampilnama['idpoproduk'] == '278' || $tampilnama['idpoproduk'] == '281' || $tampilnama['idpoproduk'] == '283') : ?>
                <th>Produksi</th>
                <th>Distribusi</th>
            <?php elseif($tampilnama['idpoproduk'] == '259' || $tampilnama['idpoproduk'] == '267' || $tampilnama['idpoproduk'] == '277') : ?>
              <th>Checker</th>
            <?php elseif($tampilnama['idpoproduk'] == '239') : ?>
              <th>Eksekutor</th>
              <th>Checker</th>
            <?php else : ?>
              <th>Checker</th>
              <th>Penerima</th>
            <?php endif; ?>
            </tr>
                          <?php
$qty=0;
                            $stmtDetail = $koneksi->prepare("SELECT
                             podetail.harga,
                                            podetail.variant,
                                            pomitra.jumlah,
                                            pomitra.invoice,
                                            pomitra.custom,
                                            pomitra.font,
                                            pomitra.template,
                                            pomitra.idpodetail
                                    FROM pomitra
                                    JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                    WHERE pomitra.invoice = ?
                                    AND pomitra.jumlah>0
                                    ");
                            $stmtDetail->bind_param('s', $invoice);
                            $stmtDetail->execute();
                            $detailResult = $stmtDetail->get_result();
                            $no=1;

                            while($tampilkan=$detailResult->fetch_assoc()){
                            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?= htmlspecialchars($tampilkan['variant']) ?> (<?= htmlspecialchars($tampilkan['custom'] ?? '') ?>)</td>
                <td><?= htmlspecialchars($tampilkan['jumlah']) ?></td>
                <td><?= htmlspecialchars($tampilkan['custom'] ?? '') ?></td>
                <td><?= htmlspecialchars($tampilkan['font'] ?? '') ?></td>
                <td><?= htmlspecialchars($tampilkan['template'] ?? '') ?></td>
                <?php if($tampilnama['idpoproduk'] == '276' || $tampilnama['idpoproduk'] == '278' || $tampilnama['idpoproduk'] == '281' || $tampilnama['idpoproduk'] == '283') : ?>
                    <td></td>
                    <td></td>
                <?php elseif($tampilnama['idpoproduk'] == '259' || $tampilnama['idpoproduk'] == '267' || $tampilnama['idpoproduk'] == '277') : ?>
                <?php else : ?>
                    <td></td>
                    <td></td>
                <?php endif; ?>
<?php
$qty += $tampilkan['jumlah'];
?>

                        </tr>
                        <?php } ?>
                        <tr>
                          <?php if(substr($invoice,0,2)=="MH" and substr($invoice,2,1)<>"P" or substr($invoice,0,2)=="BR") : ?>
                            <td colspan="4">Total</td>
                          <?php else : ?>
                            <td colspan="2">Total</td>
                          <?php endif; ?>
                          <td><?= $qty; ?></td>
<?php if(substr($invoice,0,2)=="MH" and substr($invoice,2,1)<>"P") : ?>
              <td></td>
              <td></td>
<?php endif; ?>
                        <?php if($tampilnama['idpoproduk'] == '276' || $tampilnama['idpoproduk'] == '278' || $tampilnama['idpoproduk'] == '281' || $tampilnama['idpoproduk'] == '283') : ?>
                            <td></td>
                            <td></td>
                        <?php elseif($tampilnama['idpoproduk'] == '259' || $tampilnama['idpoproduk'] == '267' || $tampilnama['idpoproduk'] == '277') : ?>
                        
                        <?php else : ?>
                          <td></td>
                          <td></td>
                        <?php endif; ?>
                        </tr>
                    </table>
<br>
<br>
<center>
<table style="font-size: 25px;border-color: white;">
  <tr>
    <?php if($tampilnama['idpoproduk'] == '276' || $tampilnama['idpoproduk'] == '278' || $tampilnama['idpoproduk'] == '281' || $tampilnama['idpoproduk'] == '283') : ?>
        <td style="border-color: white;">
          <center>Produksi</center>
          <br>
            <br>
            <br>
            <br>      
        </td>
        <td width="5%" style="border-color: white;">
          
        </td>    
        <td style="border-color: white;">
          <center>Distribusi</center>
          <br>
            <br>
            <br>
            <br>
        </td>
        </tr>
        <tr>
          <td style="border-color: white;">
          <center>
          (<span style="color:transparent;">_____________________</span>)
          </center>      
        </td >
        <td style="border-color: white;"></td> 
        <td style="border-color: white;">
          <center>
          (<span style="color:transparent;">_____________________</span>)
          </center>
        </td>
    <?php else : ?>
        <?php if($tampilnama['idpoproduk'] == '259' || $tampilnama['idpoproduk'] == '267' || $tampilnama['idpoproduk'] == '277' || $tampilnama['idpoproduk'] == '239') : ?>
        <?php else : ?>
            <td style="border-color: white;">
              <center>Gudang</center>
              <br>
                <br>
                <br>
                <br>      
            </td>
            <td width="5%" style="border-color: white;">
          
        </td>
        <?php endif; ?>
        <td style="border-color: white;">
            <?php if($tampilnama['idpoproduk'] == '239') : ?>
              <center>Eksekutor</center>
            <?php else : ?>
              <center>Checker</center>
            <?php endif; ?>
          <br>
            <br>
            <br>
            <br>      
        </td>
        <td width="5%" style="border-color: white;">
          
        </td>    
        <td style="border-color: white;">
            <?php if($tampilnama['idpoproduk'] == '259' || $tampilnama['idpoproduk'] == '267' || $tampilnama['idpoproduk'] == '277') : ?>
              <center>Assisten</center>
            <?php elseif($tampilnama['idpoproduk'] == '239') : ?>
              <center>Checker</center>
            <?php else : ?>
              <center>Penerima</center>
            <?php endif; ?>
          <br>
            <br>
            <br>
            <br>
        </td>
        </tr>
        <tr>
            <?php if($tampilnama['idpoproduk'] == '259' || $tampilnama['idpoproduk'] == '267' || $tampilnama['idpoproduk'] == '277' || $tampilnama['idpoproduk'] == '239') : ?>
            <?php else : ?>
          <td style="border-color: white;">
          <center>
          (<span style="color:transparent;">_____________________</span>)
          </center>      
        </td >
        <td style="border-color: white;"></td>
            <?php endif; ?>
          <td style="border-color: white;">
          <center>
          (<span style="color:transparent;">_____________________</span>)
          </center>     
        </td>   
        <td style="border-color: white;"></td> 
        <td style="border-color: white;">
          <center>
          (<span style="color:transparent;">_____________________</span>)
          </center>
        </td>
    <?php endif; ?>
  </tr>
</table>
    </center>

            <div>
                <p style="font-size: 22px">
                <?php if($tampilnama['idpoproduk'] == '259' || $tampilnama['idpoproduk'] == '267' || $tampilnama['idpoproduk'] == '277' || $tampilnama['idpoproduk'] == '239') : ?>
                
                <?php else : ?>
                  Note : Setelah barang diterima, mohon langsung dicek<?php if($tampilnama['idpoproduk'] == '276' || $tampilnama['idpoproduk'] == '278' || $tampilnama['idpoproduk'] == '281' || $tampilnama['idpoproduk'] == '283') : ?>, lalu kurangi di web<?php endif; ?>. Surat jalan yang sudah diverifikasi dan sudah ditandatangani mohon untuk difoto dan dikirim melalui No HP CS Pusat maksimal 3 X 24 Jam
                <?php endif; ?>
                </p>
<br>
<br>
            </div>

    <div class="footer"></div>
    <footer></footer>
    <!-- End of Content Wrapper -->

<?php
 }
              }
        }
        ?>

</body>
<script>
window.print();
</script>

</html>

                                                          