<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

        if(isset($_POST['excel'])){
  $idpoproduk = $_POST['namapo1'];


$datapo=$koneksi->query("SELECT poproduk.namapo, popembayaran.jenis 
                            FROM poproduk
                            JOIN popembayaran on poproduk.idpoproduk = popembayaran.idpoproduk
                            where poproduk.idpoproduk='$idpoproduk'
                            LIMIT 1
                            ");
$tampilpo=$datapo->fetch_assoc(); 
$nama = $tampilpo['namapo'];
$jenis = $tampilpo['jenis'];
?>
<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Pembayaran PO $nama.xls");
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>

<h2><?php   echo $tampilpo['namapo']; ?></h2>
<table class="table table-bordered" id="tb_dp" style="width: 100%">
          <thead>
            <tr>
              <th>No</th>
              <th><input type='checkbox' id='checkAll'></th>
              <th>Status</th>
              <th>Nama mitra</th>
              <th>Invoice</th>
              <?php if ($jenis=='dp'): ?>
              <th>Transfer DP</th>              
              <th>Bank</th>
              <th>Tanggal DP</th>
              <?php else: ?>
              <th>Payment 1</th>              
              <th>Bank Payment 1</th>
              <th>Tanggal Payment 1</th>                
              <?php endif ?>
              <?php if ($jenis=='dp'): ?>
              <th>Transfer Pelunasan</th>              
              <th>Bank Pelunasan</th>
              <th>Tanggal Pelunasan</th>
              <?php else: ?>  
              <th>Payment 2</th>              
              <th>Bank Payment 2</th>
              <th>Tanggal Payment 2</th>
              <th>Transfer Payment 3</th>              
              <th>Bank Payment 3</th>
              <th>Tanggal Payment 3</th>                            
              <?php endif ?>
            </tr>
          </thead>
          <tbody>
                          <?php 
                            $no=1;
                            $datapo=$koneksi->query("SELECT 
                              admin_mitra.namamitra,
                              pomitra.invoice,
                              pomitra.status,
                              MAX(popembayaran.idpembayaran) as idpembayaran
                              FROM  popembayaran
                              LEFT JOIN pomitra on popembayaran.invoice=pomitra.invoice 
                              
                              LEFT JOIN admin_mitra on pomitra.idmitra=admin_mitra.idadmin                               
                              WHERE pomitra.idpoproduk = '$idpoproduk'
                              AND pomitra.idmitra <>''
                              GROUP BY pomitra.invoice 
                              ORDER BY popembayaran.idpembayaran desc");
                           
                            while($tampilkan=$datapo->fetch_assoc()){
                              $id = $tampilkan['idpembayaran'];
                              $invoice = $tampilkan['invoice'];

                                $query = "SELECT jmlhtransfer, bankpengirim, jenis, tgl
                                      FROM popembayaran 
                                      where invoice='$invoice' and (jenis ='Payment 1' or jenis ='dp' or jenis is null)";
                                $sqlpo = mysqli_query($koneksi, $query);  
                                $tampilpo = mysqli_fetch_array($sqlpo);   

                                $query2 = "SELECT jmlhtransfer, jmlh_lunas, bankpengirim, jenis, tgl
                                      FROM popembayaran 
                                      where invoice='$invoice' and (jenis ='Payment 2' or jenis ='dp')";
                                $sqlpo2 = mysqli_query($koneksi, $query2);  
                                $tampilpo2 = mysqli_fetch_array($sqlpo2);                       
                                 
                                $query3 = "SELECT jmlhtransfer, bankpengirim, jenis, tgl
                                      FROM popembayaran 
                                      where invoice='$invoice' and jenis ='Payment 3'";
                                $sqlpo3 = mysqli_query($koneksi, $query3);  
                                $tampilpo3 = mysqli_fetch_array($sqlpo3);   

                            ?>
                        <tr>
                          <td><?php echo $no++; ?></td>
                          <td><input type='checkbox' name='update[]' value='<?= $id ?>'></td>
                          <td><?php echo $tampilkan['status']; ?></td>
                          <td><?php echo $tampilkan['namamitra']; ?></td>                          
                          <td><?php echo $tampilkan['invoice']; ?></td>
                          <td><?php echo $tampilpo['jmlhtransfer']; ?></td>
                          <td><?php echo $tampilpo['bankpengirim']; ?></td>                          
                          <td><?php echo $tampilpo['tgl']; ?></td>

                          
                          <?php if ($jenis=='dp'): ?>
                          <td><?php echo $tampilpo2['jmlh_lunas']; ?></td>  
                            <?php else: ?>                              
                          <td><?php echo $tampilpo2['jmlhtransfer']; ?></td>
                          <?php endif ?>                              
                          <?php if($jenis=='dp' and $tampilpo2['jmlh_lunas']<>""): ?>
                          <td><?php echo $tampilpo2['bankpengirim']; ?></td>                          
                          <td><?php echo $tampilpo2['tgl']; ?></td>
                          <?php else: ?>
                            <td></td>
                            <td></td>
                          <?php endif ?> 
                          <?php if ($jenis<>'dp'): ?>
                          <td><?php echo $tampilpo2['bankpengirim']; ?></td>                          
                          <td><?php echo $tampilpo2['tgl']; ?></td>  
                          <td><?php echo $tampilpo3['jmlhtransfer']; ?></td>
                          <td><?php echo $tampilpo3['bankpengirim']; ?></td>                          
                          <td><?php echo $tampilpo3['tgl']; ?></td>                                                   
                          <?php endif ?> 
                        </tr>
                        <?php } ?>
          </tbody>
        </table>
</body>
</html>

<?php } ?>