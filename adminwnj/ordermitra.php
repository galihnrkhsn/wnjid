<?php 
    session_start();
    include 'koneksi.php'; 
    if(!isset($_SESSION["administrator"])){
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

    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style type="text/css">
        body{
            padding-right: 0px !important;
        }
    </style>  
</head>

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include "sidebar.php"; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"></h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
            </div>
            <h3><strong>Order Mitra</strong></h3><br>

            <!-- Content Row -->
            
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Distributor</a></li>
                <li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Agen</a></li>
                <li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Reseller</a></li>
                <li><a data-toggle="tab" href="#menu3" class="nav-item nav-link"> Marketer</a></li>
            </ul>
            <div class="tab-content">
                <div id="home" class="tab-pane fade show active" id="home"  role="tabpanel">    
                    <div class="table-responsive">
                        <?php 
                            date_default_timezone_set('Asia/Jakarta');
                            // ==============Set Tanggal +1 hari==============
                            $jumlahhari='-60 days'; 
                            $tgl1 = date('Y-m-d');
                            $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); 
                        ?>
                        <form method="post" action="ordermitra_proses.php" target="_blank">
                            <br>  
                            <button type="submit" class="btn btn-primary" name="done5"><span class="fas fa-truck"></span></button> 
                            <button type="submit" class="btn btn-success" name="selesai"><span class="fas fa-check"></span></button>  
                            <button type="submit" class="btn btn-warning" name="bayar_db"><span class="fas fa-file-invoice-dollar"></span></button>  
                            <button type="submit" class="btn btn-info" name="print_db"><span class="fas fa-print"></span></button>
                            <button type="submit" class="btn btn-danger" name="perpanjang_db"><span class="fas fa-clock"></span></button>
                            <br><br>    
                            <table class="table table-bordered" id="tborderdb">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th><input type="checkbox" id="pilihsemua" onchange="checkAllDB(this)"/></th>
                                        <th>Tanggal</th>
                                        <th>Nama Mitra</th>
                                        <th>CS</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Invoice</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $datapo = $koneksi->query("SELECT admin_mitra.namamitra, ordermitra.tgl, ordermitra.invoice, 
                                                                        ordermitra.payment, ordermitra.status, SUM(ordermitra.jumlah) as qty, 
                                                                        SUM(ordermitra.subtotal) as total, ordermitra.idproduk,
                                                                        admin_mitra_cs.namacs, ordermitra.waktu
                                                                    FROM `ordermitra` 
                                                                    INNER JOIN admin_mitra on ordermitra.idmitra = admin_mitra.idadmin 
                                                                    LEFT JOIN admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin
                                                                    WHERE ordermitra.jumlah > 0 
                                                                    AND ordermitra.tgl > '$tgl2'
                                                                    GROUP BY ordermitra.invoice ORDER BY ordermitra.idorder DESC limit 2000; ");
                                        $no=1;
                                    
                                        while($tampilkan = $datapo->fetch_assoc()){
                                        $invoice    = $tampilkan['invoice'];
                                        $qty        = $tampilkan['qty'];
                                        $total      = $tampilkan['total'];
                                        $idProduk   = $tampilkan['idproduk'];

                                        $diskon = 0; // Inisialisasi diskon

                                        // Periksa apakah idproduk adalah 13813
                                        if ($idProduk == 13850 || $idProduk == 13813) {
                                            $kelipatan = floor($qty / 3); // Hitung kelipatan 3
                                            $diskon = $kelipatan * 35000; // Diskon total
                                            $total -= $diskon; // Kurangi total dengan diskon
                                        }
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $no++; ?></strong></td>
                                        <td>
                                            <input type="checkbox" class="check-item" name="idpomitra_dbcheck[]" value="<?php echo $tampilkan['invoice']; ?>" class="form-control">
                                        </td>
                                        <td><?php echo $tampilkan['tgl'];?> <?= $tampilkan['waktu'] ?></td>
                                        <td><?php echo $tampilkan['namamitra']; ?></td>
                                        <td><?php echo $tampilkan['namacs']; ?></td>
                                        <td>
                                            <?php if ($tampilkan['payment']=='Belum Bayar'): ?>
                                                <div class="badge bg-danger text-white rounded-pill"><?php echo $tampilkan['payment']; ?></div>
                                            <?php endif ?>
                                            <?php if ($tampilkan['payment']=='Lunas' or $tampilkan['payment']=='LUNAS'): ?>
                                                <div class="badge bg-success text-white rounded-pill"><?php echo $tampilkan['payment']; ?></div>
                                            <?php endif ?>  
                                            <?php if ($tampilkan['payment']=='Sudah Konfirmasi'): ?>
                                                <div class="badge bg-info text-white rounded-pill"><?php echo $tampilkan['payment']; ?></div>
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <?php
                                                $status       = $tampilkan['status'];
                                                $badge_class  = '';
                                                $tgl          = $tampilkan['tgl'];
                                                $countdown    = 0;

                                                if ($status === 'Pending') {
                                                    $timestampTr    = strtotime($tgl);
                                                    $timestamExp    = strtotime('+2 days', $timestampTr);

                                                    $countdown      = $timestamExp;

                                                    $today          = time();

                                                    if ($today >= $timestamExp) {
                                                        $status = 'Expired';
                                                    }
                                                }

                                              switch ($status) {
                                                  case 'Expired':
                                                      $badge_class = 'bg-dark';
                                                      break;
                                                  case 'Pending':
                                                      $badge_class = 'bg-danger';
                                                      break;
                                                  case 'Tunggu Confrim Admin':
                                                      $badge_class = 'bg-warning';
                                                      break;
                                                  case 'Proses':
                                                      $badge_class = 'bg-info';
                                                      break;
                                                  case 'Sedang DiKirim':
                                                  case 'Selesai':
                                                      $badge_class = 'bg-success';
                                                      break;
                                                  default:
                                                      // Anda bisa menentukan kelas default jika status tidak cocok
                                                      $badge_class = 'bg-secondary';
                                                      break;
                                              }
                                            if (!empty($badge_class)) {
                                            ?>
                                                <div class="badge <?php echo $badge_class; ?> text-white rounded-pill">
                                                    <?php 
                                                        echo $status;
                                                    ?>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <script>
                                                function startCountdown(elementId, expireTime) {
                                                    const countdownElement = document.getElementById(elementId);
                                                    if (!countdownElement) return;

                                                    // Ubah waktu kedaluwarsa dari detik (PHP) menjadi milidetik (JS)
                                                    const expirationDate = new Date(expireTime * 1000).getTime();

                                                    const x = setInterval(function() {
                                                        const now = new Date().getTime();
                                                        const distance = expirationDate - now;

                                                        if (distance < 0) {
                                                            // Waktu habis
                                                            clearInterval(x);
                                                            countdownElement.closest('.badge').classList.remove('bg-danger');
                                                            countdownElement.closest('.badge').classList.add('bg-dark');
                                                            countdownElement.closest('.badge').innerHTML = 'Expired';
                                                            // Opsional: Muat ulang atau perbarui baris tabel jika expired
                                                            // console.log("Expired ID: " + elementId);
                                                            return;
                                                        }

                                                        // Perhitungan waktu
                                                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                                        // Tampilkan hasilnya
                                                        countdownElement.innerHTML = 
                                                            (hours < 10 ? "0" + hours : hours) + ":" + 
                                                            (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                                                            (seconds < 10 ? "0" + seconds : seconds);
                                                    }, 1000);
                                                }

                                                // Cari semua elemen badge yang perlu hitungan mundur
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    document.querySelectorAll('.badge[data-status="Pending"]').forEach(badge => {
                                                        const status = badge.getAttribute('data-status');
                                                        const expireTime = parseInt(badge.getAttribute('data-expire-time'));
                                                        
                                                        if (status === 'Pending' && expireTime > 0) {
                                                            // Asumsikan ID unik Anda adalah 'id' dari $tampilkan
                                                            const elementId = 'countdown-' + badge.querySelector('span[id^="countdown-"]').id.split('-')[1];
                                                            
                                                            // Panggil fungsi hitungan mundur untuk setiap elemen Pending
                                                            startCountdown(elementId, expireTime);
                                                        }
                                                    });
                                                });
                                                </script>
                                        </td>
                                        <td>
                                            <? if (strtotime($tampilkan['tgl']) > strtotime('2024-11-02')): ?>
                                                <a href="detailorder2.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice'];?></a>
                                            <? else :?>
                                                <a href="detailorder.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
                                            <? endif ?>
                                            <?php if (substr($invoice,0,1)=="V"): ?>
                                                <br>
                                                <!-- Button trigger modal -->
                                                <a href="" data-toggle="modal" data-target="#exampleModal<?= $invoice; ?>">Kartu Ucapan</a>
                                            <?php endif; ?>                
                                        </td>
                                        <td style="text-align:center"><?php echo $tampilkan['qty']; ?></td>
                                        <td style="text-align:center">Rp. <?php echo number_format($total); ?></td>         
                                    </tr>

<?php if (substr($invoice,0,1)=="V"): ?>
<!-- Modal -->
<div class="modal fade" id="exampleModal<?= $invoice; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Inv. <?= $invoice; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<strong>Keterangan Ucapan Box</strong>
<br>
<?php 

      $ambil_ucapan=$koneksi->query("SELECT *
                                FROM hampers
                                WHERE hampers.no_ds= '$invoice'
                                ORDER BY nobox ASC"); 
      while($data_ucapan=$ambil_ucapan->fetch_assoc()){
    $result_explode = explode('|', $data_ucapan['ucapan']);
    $dari=$result_explode[0];    
    $kepada=$result_explode[1];    
    $ucapan=$result_explode[2];        
 ?>
<strong>Ucapan Box <?= $data_ucapan['nobox'] ?></strong>
<br>
Dari : <?= $dari; ?>
<br>
Kepada : <?= $kepada; ?> 
<br>
Ucapan : <?= $ucapan; ?> 
<hr>
<?php } ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
                        <?php } ?>
                      </tbody>
                    </table>
</form>                    
                   

    <script type="text/javascript">
     function checkAllDB(box) 
  {

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    var idpomitra_dbcheck = document.getElementsByName("idpomitra_dbcheck[]");
    var jml=idpomitra_dbcheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_dbcheck[b].checked=true;
        
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    var idpomitra_dbcheck = document.getElementsByName("idpomitra_dbcheck[]");
    var jml=idpomitra_dbcheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_dbcheck[b].checked=false;
        
    }
   }
  }
  
 </script>                              
                    </div>
                    </div>
                    
      <div id="menu1" class="tab-pane fade">
        <div class="table-responsive">
 <form method="post" action="ordermitra_prosesagen.php" target="_blank">
 <br>  
  <button type="submit" class="btn btn-primary" name="doneagen"><span class="fas fa-truck"></span></button> 
  <button type="submit" class="btn btn-success" name="selesaiagen"><span class="fas fa-check"></span></button>  
  <button type="submit" class="btn btn-warning" name="bayar_agen"><span class="fas fa-file-invoice-dollar"></span></button>  
  <button type="submit" class="btn btn-info" name="print_agen"><span class="fas fa-print"></span></button>        
    <table class="table table-bordered" id="tborderagen">
        <thead>
      <tr>
            <th>No</th>
            <th><input type="checkbox" id="pilihsemua" onchange="checkAllAgen(this)"/></th>
            <th>Tanggal</th>
            <th>Nama Agen</th>
            <th>Nama DB</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Invoice</th>
            <th>Qty</th>
            <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
              <?php 
      
                $datapo=$koneksi->query("SELECT admin_mitra.namamitra,
                                                mitraagen.namaagen as agen, 
                                                orderagen.tgl,
                                                orderagen.invoice,
                                                orderagen.payment,
                                                orderagen.status, 
                                                SUM(orderagen.jumlah) as qty, 
                                                SUM(orderagen.subtotal) as total 
                                        FROM `orderagen` 
                                        inner join mitraagen on orderagen.idmitraagen=mitraagen.idmitraagen 
                                        INNER JOIN admin_mitra on admin_mitra.idadmin=mitraagen.idadmin 
                                        WHERE orderagen.jumlah>0  
                                        and orderagen.tgl > '$tgl2'
                  GROUP BY orderagen.invoice ORDER BY orderagen.idorder DESC ");
                $no=1;
              
                while($tampilkan=$datapo->fetch_assoc()){
                ?>
          <tr>    
           <td>
               <?php echo $no++; ?>
          </td> 
            <td>
              <input type="checkbox" class="check-item" name="idpomitra_agen[]" value="<?php echo $tampilkan['invoice']; ?>" class="form-control">
            </td>    
            <td><?php echo $tampilkan['tgl']; ?></td>
             <td><?php echo $tampilkan['agen']; ?></td>
            <td><?php echo $tampilkan['namamitra']; ?></td>
            <td>
             <?php if ($tampilkan['payment']=='Belum Bayar'): ?>
                 <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>

                <?php if ($tampilkan['payment']=='Lunas' or $tampilkan['payment']=='LUNAS'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>  

                <?php if ($tampilkan['payment']=='Sudah Konfirmasi'): ?>
                 <div class="badge bg-info text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>
            </td>
            <td>
             <?php if ($tampilkan['status']=='Pending'): ?>
                 <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>
                
                <?php if ($tampilkan['status']=='Tunggu Confrim Admin'): ?>
                 <div class="badge bg-warning text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>

                <?php if ($tampilkan['status']=='Proses'): ?>
                 <div class="badge bg-info text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>

                <?php if ($tampilkan['status']=='Sedang DiKirim'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?> 

                <?php if ($tampilkan['status']=='Selesai'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>
            </td>
            <td>
            <? if (strtotime($tampilkan['tgl']) > strtotime('2024-11-02')): ?>
                <a href="detailorderagen2.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
            <? else :?>
                <a href="detailorderagen.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
            <? endif ?>
<?php if (substr($tampilkan['invoice'],0,2)=="AV"): ?>
  <br>
<!-- Button trigger modal -->
<a href="" data-toggle="modal" data-target="#exampleModal<?= $tampilkan['invoice']; ?>">
  Kartu Ucapan
</a>   
<?php endif; ?>  
<?php if (substr($tampilkan['invoice'],0,2)=="AV"): ?>
<!-- Modal -->
<div class="modal fade" id="exampleModal<?= $tampilkan['invoice']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Inv. <?= $invoice; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<strong>Keterangan Ucapan Box</strong>
<br>
<?php 

      $ambil_ucapan=$koneksi->query("SELECT *
                                FROM hampers
                                WHERE hampers.no_ds= '$tampilkan[invoice]'
                                ORDER BY nobox ASC"); 
      while($data_ucapan=$ambil_ucapan->fetch_assoc()){
    $result_explode = explode('|', $data_ucapan['ucapan']);
    $dari=$result_explode[0];    
    $kepada=$result_explode[1];    
    $ucapan=$result_explode[2];        
 ?>
<strong>Ucapan Box <?= $data_ucapan['nobox'] ?></strong>
<br>
Dari : <?= $dari; ?>
<br>
Kepada : <?= $kepada; ?> 
<br>
Ucapan : <?= $ucapan; ?> 
<hr>
<?php } ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>               
              </td>
              <td style="text-align:center"><?php echo $tampilkan['qty']; ?></td>
              <td style="text-align:center">Rp. <?php echo number_format($tampilkan['total']); ?></td>
            </tr>
              <?php } ?>   
          </tbody>
        </table>
</form>
    <script type="text/javascript">
     function checkAllAgen(box) 
  {

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    var idpomitra_agen = document.getElementsByName("idpomitra_agen[]");
    var jml=idpomitra_agen.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_agen[b].checked=true;
        
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    var idpomitra_agen = document.getElementsByName("idpomitra_agen[]");
    var jml=idpomitra_agen.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_agen[b].checked=false;
        
    }
   }
  }
  
 </script>         
                         
                     </div>
                      </div>
                        
                <div id="menu2" class="tab-pane fade">
        <div class="table-responsive">
 <form method="post" action="ordermitra_prosesreseller.php" target="_blank">
 <br>  
  <button type="submit" class="btn btn-primary" name="donereseller"><span class="fas fa-truck"></span></button> 
  <button type="submit" class="btn btn-success" name="selesaireseller"><span class="fas fa-check"></span></button>  
  <button type="submit" class="btn btn-warning" name="bayar_reseller"><span class="fas fa-file-invoice-dollar"></span></button>  
  <button type="submit" class="btn btn-info" name="print_reseller"><span class="fas fa-print"></span></button>             
        <table class="table table-bordered" id="tborderreseller">
          <thead>
            <tr>
              <th>No</th>
                <th><input type="checkbox" id="pilihsemua" onchange="checkAllReseller(this)"/></th>            
                <th>Tanggal</th>
                <th>Nama Reseller</th>
                <th>Nama DB</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Invoice</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
              <?php 
      
                $datapo=$koneksi->query("SELECT admin_mitra.namamitra,
                                                mitrareseller.namaagen as reseller, 
                                                orderreseller.tgl,
                                                orderreseller.invoice,
                                                orderreseller.payment,
                                                orderreseller.status, 
                                                SUM(orderreseller.jumlah) as qty, 
                                                SUM(orderreseller.subtotal) as total 
                                                FROM `orderreseller` 
                                                inner join mitrareseller on orderreseller.idmitrareseller=mitrareseller.idmitrareseller 
                                                INNER JOIN admin_mitra on admin_mitra.idadmin=mitrareseller.idadmin 
                                                WHERE orderreseller.jumlah>0 
                                                and orderreseller.tgl > '$tgl2' 
                                                GROUP BY orderreseller.invoice ORDER BY orderreseller.idorder DESC ");
                $no=1;
              
                while($tampilkan=$datapo->fetch_assoc()){
                ?>
            <tr>    
              <td><?php echo $no++; ?></td>     
              <td><input type="checkbox" class="check-item" name="idpomitra_reseller[]" value="<?php echo $tampilkan['invoice']; ?>" class="form-control"></td>                 
              <td><?php echo $tampilkan['tgl']; ?></td>
              <td><?php echo $tampilkan['reseller']; ?></td>
              <td><?php echo $tampilkan['namamitra']; ?></td>
              <td>
                <?php if ($tampilkan['payment']=='Belum Bayar'): ?>
                 <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>

                <?php if ($tampilkan['payment']=='Lunas' or $tampilkan['payment']=='LUNAS'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>  

                <?php if ($tampilkan['payment']=='Sudah Konfirmasi'): ?>
                 <div class="badge bg-info text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>
              </td>
              <td>
              <?php if ($tampilkan['status']=='Pending'): ?>
                 <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>
                
                <?php if ($tampilkan['status']=='Tunggu Confrim Admin'): ?>
                 <div class="badge bg-warning text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>

                <?php if ($tampilkan['status']=='Proses'): ?>
                 <div class="badge bg-info text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>

                <?php if ($tampilkan['status']=='Sedang DiKirim'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?> 

                <?php if ($tampilkan['status']=='Selesai'): ?>
                 <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>
              </td>
              <td>
                <? if (strtotime($tampilkan['tgl']) > strtotime('2024-11-02')): ?>
                    <a href="detailorderreseller2.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
                <? else :?>
                    <a href="detailorderreseller.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a>
                <? endif ?>
              </td>
              <td style="text-align:center"><?php echo $tampilkan['qty']; ?></td>
              <td style="text-align:center">Rp. <?php echo number_format($tampilkan['total']); ?></td>

            </tr>
              <?php } ?>   
          </tbody>
        </table>
</form>        
    <script type="text/javascript">
     function checkAllReseller(box) 
  {

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    var idpomitra_reseller = document.getElementsByName("idpomitra_reseller[]");
    var jml=idpomitra_reseller.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_reseller[b].checked=true;
        
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    var idpomitra_reseller = document.getElementsByName("idpomitra_reseller[]");
    var jml=idpomitra_reseller.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_reseller[b].checked=false;
        
    }
   }
  }
  
 </script>           
                           
                    </div>
                    </div>
                        
<div id="menu3" class="tab-pane fade">
<div class="table-responsive">
 <form method="post" action="ordermitra_prosesmarketer.php" target="_blank">
 <br>  
  <button type="submit" class="btn btn-primary" name="donemarketer"><span class="fas fa-truck"></span></button> 
  <button type="submit" class="btn btn-success" name="selesaimarketer"><span class="fas fa-check"></span></button>  
  <button type="submit" class="btn btn-warning" name="bayar_marketer"><span class="fas fa-file-invoice-dollar"></span></button>  
  <button type="submit" class="btn btn-info" name="print_marketer"><span class="fas fa-print"></span></button>            
    <table class="table table-bordered" id="tbordermarketer">
      <thead>
        <tr>
          <th>No</th>
          <th><input type="checkbox" id="pilihsemua" onchange="checkAllMarketer(this)"/></th>    
          <th>Tanggal</th>
          <th>Nama Marketer</th>
          <th>Nama DB</th>
          <th>Payment</th>
          <th>Status</th>
          <th>Invoice</th>
          <th>Qty</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
                          <?php 
                            $datapo=$koneksi->query("SELECT admin_mitra.namamitra,
                                                            mitramarketer.namaagen as marketer, 
                                                            ordermarketer.tgl,
                                                            ordermarketer.invoice,
                                                            ordermarketer.payment,
                                                            ordermarketer.status, 
                                                            SUM(ordermarketer.jumlah) as qty, 
                                                            SUM(ordermarketer.subtotal) as total 
                                                            FROM `ordermarketer` 
                                                            inner join mitramarketer on ordermarketer.idmitramarketer=mitramarketer.idmitramarketer 
                                                            INNER JOIN admin_mitra on admin_mitra.idadmin=mitramarketer.idadmin 
                                                            where ordermarketer.jumlah>0 
                                                            and ordermarketer.tgl > '$tgl2' 
                                                            GROUP BY ordermarketer.invoice ORDER BY ordermarketer.idorder DESC");
                            $no=1;
                          
                            while($tampilkan=$datapo->fetch_assoc()){
                            ?>
        <tr>
          <td><?php echo $no++; ?></td>        
          <td><input type="checkbox" class="check-item" name="idpomitra_marketer[]" value="<?php echo $tampilkan['invoice']; ?>" class="form-control"></td> 
          <td><?php echo $tampilkan['tgl']; ?></td>
          <td><?php echo $tampilkan['marketer']; ?></td>
          <td><?php echo $tampilkan['namamitra']; ?></td>
          <td>
                <?php if ($tampilkan['payment']=='Belum Bayar'): ?>
                  <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>

                <?php if ($tampilkan['payment']=='Lunas' or $tampilkan['payment']=='LUNAS'): ?>
                  <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>  

                <?php if ($tampilkan['payment']=='Sudah Konfirmasi'): ?>
                  <div class="badge bg-info text-white rounded-pill">
                  <?php echo $tampilkan['payment']; ?>
                  </div>
                <?php endif ?>
          </td>
          <td>
                <?php if ($tampilkan['status']=='Pending'): ?>
                  <div class="badge bg-danger text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>
                
                <?php if ($tampilkan['status']=='Tunggu Confrim Admin'): ?>
                  <div class="badge bg-warning text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>

                <?php if ($tampilkan['status']=='Proses'): ?>
                  <div class="badge bg-info text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>

                <?php if ($tampilkan['status']=='Sedang DiKirim'): ?>
                  <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?> 

                <?php if ($tampilkan['status']=='Selesai'): ?>
                  <div class="badge bg-success text-white rounded-pill">
                  <?php echo $tampilkan['status']; ?>
                  </div>
                <?php endif ?>
          </td>
            <? if (strtotime($tampilkan['tgl']) > strtotime('2024-11-02')): ?>
                <td><a href="detailordermarketer2.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a></td>
            <? else :?>
                <td><a href="detailordermarketer.php?invoice=<?php echo $tampilkan['invoice']; ?>"><?php echo $tampilkan['invoice']; ?></a></td>
            <? endif ?>
          <td style="text-align:center"><?php echo $tampilkan['qty']; ?></td>
          <td style="text-align:center">Rp. <?php echo number_format($tampilkan['total']); ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
</form>
    <script type="text/javascript">
     function checkAllMarketer(box) 
  {

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    var idpomitra_marketer = document.getElementsByName("idpomitra_marketer[]");
    var jml=idpomitra_marketer.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_marketer[b].checked=true;
        
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    var idpomitra_marketer = document.getElementsByName("idpomitra_marketer[]");
    var jml=idpomitra_marketer.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idpomitra_marketer[b].checked=false;
        
    }
   }
  }
  
 </script>        
                    
                    </div>
         
                        </div>
                        
                    </div>
                        
                            
 <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
  
  <?php include "settingdatatables.php"; ?>


</body>

</html>

                                                    