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
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  
  

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
            </div>

           <h3><strong>Order Mitra Keep</strong></h3><br>

            <!-- Content Row -->
        	<ul class="nav nav-tabs">
    			<li class="active"><a data-toggle="tab" href="#home" class="nav-item nav-link active">Distributor</a></li>
    			<li><a data-toggle="tab" href="#menu1" class="nav-item nav-link">Agen</a></li>
    			<li><a data-toggle="tab" href="#menu2" class="nav-item nav-link">Reseller</a></li>
    			<li><a data-toggle="tab" href="#menu3" class="nav-item nav-link"> Marketer</a></li>
		    </ul>
		
            <div class="tab-content">
                <div id="home" class="tab-pane fade show active" id="home"  role="tabpanel"> 
                    <p>Order Mitra Keep Distributor</p>		   
                    <div class="table-responsive">
                        <form method="post">
                            <table class="table table-bordered" id="tborderdb">
                                <thead>
                                    <tr>
                                        <th>Check All
                                        <br>  
                                        <input type="checkbox" id="pilihsemua" onchange="checkAllDB(this)"/></th>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Variant</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                        <th>Nama Mitra</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Waktu Pembayaran</th>
                                        <th>Invoice</th>
                                        <!-- <th style="width:150px">
                                        <span class="fas fa-cog"></span>
                                        </th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $datapo=$koneksi->query("SELECT admin_mitra.namamitra, ordermitra.idorder, ordermitra.tgl,
                                                                        ordermitra.invoice, ordermitra.payment, ordermitra.status, ordermitra.jumlah as qty,
                                                                        ordermitra.waktu, products.namaproduk, variants.variant, variants.idproducts, variants.size, variants.jenis
                                                                    FROM `ordermitra` 
                                                                    INNER JOIN admin_mitra on ordermitra.idmitra=admin_mitra.idadmin 
                                                                    INNER JOIN variants on variants.id = ordermitra.idproduk
                                                                    INNER JOIN products on variants.idproducts = products.id
                                                                    WHERE ordermitra.jumlah > 0 and ordermitra.status = 'Pending'
                                                                    ORDER BY ordermitra.idorder DESC");
                                        $no = 1;
                                    while($tampilkan = $datapo->fetch_assoc()){
                                        $jenis = $tampilkan['jenis'];
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="check-item" name="idorder_dbcheck[]" id="iddb" value="<?= $tampilkan['idorder']; ?>" class="form-control">
                                        </td> 
                                        <td><?= $no++; ?></td>     
                                        <td><?= $tampilkan['tgl']; ?></td>
                                        <td><?= $tampilkan['namaproduk']; ?></td>
                                        <td><?= $tampilkan['variant']; ?></td>
                                        <td><?= $tampilkan['size']; ?></td>
                                        <td><?= $tampilkan['qty']; ?></td>
                                        <td><?= $tampilkan['namamitra']; ?></td>
                                        <td><?= $tampilkan['payment']; ?></td>
                                        <td><?= $tampilkan['status']; ?></td>
                                        <td>
                                            <?php
                                                // Set timezone ke Asia/Jakarta
                                                date_default_timezone_set('Asia/Jakarta');

                                                // Waktu awal dari database
                                                $waktu_awal = new DateTime($tampilkan['waktu']);

                                                // Waktu sekarang
                                                $waktu_sekarang = new DateTime();

                                                // Hitung waktu akhir (2 jam dari waktu awal)
                                                $waktu_akhir = clone $waktu_awal; // Mengkloning objek DateTime
                                                $waktu_akhir->modify('+24 hours');

                                                // Menghitung selisih waktu
                                                $selisih = $waktu_akhir->getTimestamp() - $waktu_sekarang->getTimestamp();
                                                $countdown_h = floor($selisih / 3600);
                                                $countdown_m = floor(($selisih % 3600) / 60);
                                                $countdown_s = $selisih % 60;

                                                // Menyimpan informasi untuk JavaScript
                                                $isActive = $selisih > 0;
                                            ?>
                                            <center>
                                                <div class="badge <?= $isActive ? 'bg-success' : 'bg-danger' ?> text-white rounded-pill" id="link2<?= $tampilkan['idorder']; ?>">
                                                    <?= $isActive ? 'Active' : 'Expired' ?>
                                                </div>
                                                <p id="countdown"><?= sprintf('%02d : %02d : %02d', $countdown_h, $countdown_m, $countdown_s) ?></p>
                                            </center>

                                            <script>
                                                // Countdown timer
                                                let countdownElement = document.getElementById('countdown');
                                                let countdownTime = <?= $selisih ?>; // waktu dalam detik

                                                // Update countdown setiap detik
                                                let countdownInterval = setInterval(function() {
                                                    if (countdownTime <= 0) {
                                                        clearInterval(countdownInterval);
                                                        countdownElement.innerHTML = "00 : 00 : 00"; // Menampilkan waktu habis
                                                        document.getElementById('link2<?= $tampilkan['idorder']; ?>').classList.remove('bg-success');
                                                        document.getElementById('link2<?= $tampilkan['idorder']; ?>').classList.add('bg-danger');
                                                        countdownElement.innerHTML = "Expired";
                                                    } else {
                                                        // Hitung jam, menit, dan detik
                                                        let hours = Math.floor(countdownTime / 3600);
                                                        let minutes = Math.floor((countdownTime % 3600) / 60);
                                                        let seconds = countdownTime % 60;

                                                        // Tampilkan waktu countdown
                                                        countdownElement.innerHTML = `${String(hours).padStart(2, '0')} : ${String(minutes).padStart(2, '0')} : ${String(seconds).padStart(2, '0')}`;

                                                        // Kurangi satu detik
                                                        countdownTime--;
                                                    }
                                                }, 1000);
                                            </script>
                                        </td>
                                        <td><a href="detailorder2.php?invoice=<?= $tampilkan['invoice']; ?>&jenis=<?= $tampilkan['jenis']; ?>"><?= $tampilkan['invoice']; ?></a></td>        
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-warning" name="hapusdbcheck_stok"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Barang Akan Kembali Ke Stok Produk');"><span class="fas fa-times" ></span> Check</button>                    
                            <button type="submit" class="btn btn-danger" name="hapusdbcheck"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Barang Tidak Akan Kembali Ke Stok');"><span class="fas fa-trash"></span> Check</button>                    
                        </form>
                        <?php
                            if(isset($_POST["hapusdbcheck"])){
                                include "koneksi.php";
                                $idordercheck       = $_POST['idorder_dbcheck'];
                                $jumlah_dipilih     = count($idordercheck);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                    $tampil         = $koneksi->query("SELECT * FROM ordermitra WHERE idorder='$idordercheck[$x]' ");
                                    $tampilMar      = $tampil->fetch_assoc();
                                    $invoicenya     = $tampilMar['invoice'];
                                    
                                    $koneksi->query("DELETE FROM ordermitra WHERE idorder = '$idordercheck[$x]'" );
                                    
                                    $cariinvoice    = $koneksi->query("SELECT count(*) as jumlahinv FROM ordermitra WHERE invoice = '$invoicenya'");
                                    $tampilkaninv   = $cariinvoice->fetch_assoc();
                                    $jumlahinv      = $tampilkaninv['jumlahinv'];

                                    if ($jumlahinv == 0) {
                                        $delete = "DELETE FROM orderpengiriman WHERE invoice = '$invoicenya'";
                                        $sql = mysqli_query($koneksi, $delete);
                                    }
                                }
                                echo "<script>alert('data berhasil dihapus');</script>";
                                echo "<script>location='ordermitra_keep.php';</script>";
                            }
                            if(isset($_POST["hapusdbcheck_stok"])){
                                include "koneksi.php";
                                $idordercheck       = $_POST['idorder_dbcheck'];
                                $jumlah_dipilih     = count($idordercheck);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                    
                                    $tampil         = $koneksi->query("SELECT * FROM ordermitra WHERE idorder = '$idordercheck[$x]' ");
                                    $tampilMar      = $tampil->fetch_assoc();
                                    
                                    $invoicenya     = $tampilMar['invoice'];
                                    $jumlahnya      = $tampilMar['jumlah'];
                                    $produknya      = $tampilMar['idproduk'];
                    
                                    $koneksi->query("UPDATE variants SET stock = stock+'$jumlahnya' WHERE id = '$produknya'" );
                                    $koneksi->query("DELETE FROM ordermitra WHERE idorder = '$idordercheck[$x]'" );
                                
                                    $cariinvoice    = $koneksi->query("SELECT count(*) as jumlahinv FROM ordermitra WHERE invoice = '$invoicenya'");
                                    $tampilkaninv   = $cariinvoice->fetch_assoc();
                                    $jumlahinv      = $tampilkaninv['jumlahinv'];

                                    if ($jumlahinv == 0) {
                                        $delete = "DELETE FROM orderpengiriman WHERE invoice = '$invoicenya'";
                                        $sql = mysqli_query( $koneksi, $delete);
                                    }
                                }
                                echo "<script>alert('data berhasil dihapus');</script>";
                                echo "<script>location='ordermitra_keep.php';</script>";
                            }   
                        ?>
                    </div>
                </div>
                    
                <div id="menu1" class="tab-pane fade">
                    <p>Order Mitra Keep Agen</p>    		
                    <div class="table-responsive">
                        <form method="post">
                            <table class="table table-bordered" id="tborderagen">
                                <thead>
                                    <tr>
                                        <th>Check All
                                        <br>  
                                        <input type="checkbox" id="pilihsemua" onchange="checkAllAgen(this)"/></th>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Variant</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                        <th>Nama Agen</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Waktu Pembayaran</th>
                                        <th>Invoice</th>
                                        <!-- <th style="width:150px">
                                        <span class="fas fa-cog"></span>
                                        </th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $datapo = $koneksi->query("SELECT mitraagen.namaagen, orderagen.idorder, orderagen.tgl, orderagen.invoice,
                                                                        orderagen.payment, orderagen.status,  orderagen.jumlah as qty, 
                                                                        products.namaproduk, variants.variant, variants.size, variants.jenis 
                                                                        FROM `orderagen` 
                                                                    INNER JOIN mitraagen on orderagen.idmitraagen = mitraagen.idmitraagen 
                                                                    INNER JOIN variants on variants.id = orderagen.idproduk
                                                                    INNER JOIN products on variants.idproducts = products.id
                                                                    WHERE orderagen.jumlah > 0 and orderagen.status = 'Pending'
                                                                    ORDER BY orderagen.idorder DESC ");
                                        $no = 1;
                                        while($tampilkan = $datapo->fetch_assoc()){
                                            $jenis = $tampilkan['jenis'];
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="check-item" name="idorder_agencheck[]" id="idagen" value="<?= $tampilkan['idorder']; ?>" class="form-control">
                                        </td> 
                                        <td><?= $no++; ?></td>     
                                        <td><?= $tampilkan['tgl']; ?></td>
                                        <td><?= $tampilkan['namaproduk']; ?></td>
                                        <td><?= $tampilkan['variant']; ?></td>
                                        <td><?= $tampilkan['size']; ?></td>
                                        <td><?= $tampilkan['qty']; ?></td>
                                        <td><?= $tampilkan['namaagen']; ?></td>
                                        <td><?= $tampilkan['payment']; ?></td>
                                        <td><?= $tampilkan['status']; ?></td>
                                        <td>
                                            <?php $jumlahhari='+1 days'; ?>
                                            <center>
                                                <div class="badge bg-success text-white rounded-pill"  id="link2<?= $tampilkan['idorder']; ?>">
                                                    Active
                                                </div>
                                                <p id="demomiki<?= $tampilkan['idorder']; ?>" style="color: red;"></p>
                                            </center>      
                                            <?php 
                                                date_default_timezone_set('Asia/Jakarta');
                                                $tgl1 = $tampilkan['tgl'];// pendefinisian tanggal awal
                                                $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
                                            ?>

                                            <script>

                                                // Mengatur waktu akhir perhitungan mundur
                                                var countDownDatemiki<?= $tampilkan['idorder']; ?>= new Date("<?= $tgl2; ?> 23:59:00").getTime();

                                                // Memperbarui hitungan mundur setiap 1 detik
                                                var x = setInterval(function() {

                                                // Untuk mendapatkan tanggal dan waktu hari ini
                                                var now = new Date().getTime();
                                                    
                                                // Temukan jarak antara sekarang dan tanggal hitung mundur
                                                var distance = countDownDatemiki<?= $tampilkan['idorder']; ?> - now;
                                                    
                                                // Perhitungan waktu untuk hari, jam, menit dan detik
                                                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                    
                                                // Keluarkan hasil dalam elemen dengan id = "demo"
                                                document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = days + "d " + hours + "h "
                                                + minutes + "m " + seconds + "s ";
                                                    
                                                // Jika hitungan mundur selesai, tulis beberapa teks 
                                                if (distance < 0) {
                                                    clearInterval(x);
                                                    document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = "<div class='badge bg-danger text-white rounded-pill'>Expired</div>";
                                                    var x = document.getElementById("linkmiki<?= $tampilkan['idorder']; ?>");
                                                    var y = document.getElementById("link2<?= $tampilkan['idorder']; ?>");
                                                
                                                    y.style.display = "none";
                                                    x.style.display = "none";
                                                    }
                                                }, 1000);
                                            </script>
                                        </td>
                                        <td>
                                        <a href="detailorderagen2.php?invoice=<?= $tampilkan['invoice']; ?>&jenis=<?= $tampilkan['jenis']; ?>"><?= $tampilkan['invoice']; ?></a>
                                        </td>	
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-warning" name="hapusagencheck_stok"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Barang Akan Kembali Ke Stok Produk');"><span class="fas fa-times" ></span> Check</button> 
                            <button type="submit" class="btn btn-danger" name="hapusagencheck"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Barang Tidak Akan Kembali Ke Stok');"><span class="fas fa-trash"></span> Check</button>
                        </form>
                        <?php           
                            if(isset($_POST["hapusagencheck"])){
                                include "koneksi.php";
                                $idordercheck       = $_POST['idorder_agencheck'];
                                $jumlah_dipilih     = count($idordercheck);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                    $tampil         = $koneksi->query("SELECT * FROM orderagen WHERE idorder='$idordercheck[$x]' ");
                                    $tampilMar      = $tampil->fetch_assoc();
                                    $invoicenya     = $tampilMar['invoice'];
                                    
                                    $koneksi->query("DELETE FROM orderagen WHERE idorder = '$idordercheck[$x]'" );
                                    
                                    $cariinvoice    = $koneksi->query("SELECT count(*) as jumlahinv FROM orderagen WHERE invoice ='$invoicenya'");
                                    $tampilkaninv   = $cariinvoice->fetch_assoc();
                                    $jumlahinv      = $tampilkaninv['jumlahinv'];

                                    if ($jumlahinv == 0) {
                                        $delete = "DELETE FROM orderpengiriman WHERE invoice = '$invoicenya'";
                                        $sql = mysqli_query( $koneksi, $delete);
                                    }
                                }
                                echo "<script>alert('data berhasil dihapus');</script>";
                                echo "<script>location='ordermitra_keep.php';</script>";
                            }
                            if(isset($_POST["hapusagencheck_stok"])){
                                include "koneksi.php";
                                $idordercheck       = $_POST['idorder_agencheck'];
                                $jumlah_dipilih     = count($idordercheck);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                    $tampil         = $koneksi->query("SELECT * FROM orderagen WHERE idorder = '$idordercheck[$x]' ");
                                    $tampilMar      = $tampil->fetch_assoc();
                                    $invoicenya     = $tampilMar['invoice'];
                                    $jumlahnya      = $tampilMar['jumlah'];
                                    $produknya      = $tampilMar['idproduk'];

                                    $koneksi->query("UPDATE variants SET stock = stock+'$jumlahnya' WHERE id='$produknya'" );
                                    
                                    $koneksi->query("DELETE FROM orderagen WHERE idorder='$idordercheck[$x]'" );
                                    
                                    $cariinvoice = $koneksi->query("SELECT count(*) as jumlahinv FROM orderagen WHERE invoice='$invoicenya'");
                                    $tampilkaninv=$cariinvoice->fetch_assoc();
                                    $jumlahinv = $tampilkaninv['jumlahinv'];

                                    if ($jumlahinv==0) {
                                        $delete = "DELETE FROM orderpengiriman WHERE invoice='$invoicenya'";
                                        $sql = mysqli_query( $koneksi, $delete);
                                        }
                                }
                                echo "<script>alert('data berhasil dihapus');</script>";
                                echo "<script>location='ordermitra_keep.php';</script>";
                            }   
                        ?>
                    </div>
                </div>
                        
              	<div id="menu2" class="tab-pane fade">
                    <p>Order Mitra Keep Reseller</p>              		
                    <div class="table-responsive">
                        <form method="post">
                            <table class="table table-bordered" id="tborderreseller">
                                <thead>
                                    <tr>
                                        <th>Check All
                                        <br>  
                                        <input type="checkbox" id="pilihsemua" onchange="checkAllReseller(this)"/></th>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Variant</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                        <th>Nama Reseller</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Waktu Pembayaran</th>
                                        <th>Invoice</th>
                                        <!-- <th style="width:150px">
                                    <span class="fas fa-cog"></span>
                                        </th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $datapo = $koneksi->query("SELECT mitrareseller.namaagen, orderreseller.idorder, orderreseller.tgl,
                                                                        orderreseller.invoice, orderreseller.payment, orderreseller.status, orderreseller.jumlah as qty, 
                                                                        products.namaproduk, variants.variant, variants.size, variants.jenis 
                                                                    FROM `orderreseller` 
                                                                    INNER JOIN mitrareseller on orderreseller.idmitrareseller=mitrareseller.idmitrareseller 
                                                                    INNER JOIN variants on variants.id = orderreseller.idproduk
                                                                    INNER JOIN products on variants.idproducts = products.id
                                                                    WHERE orderreseller.jumlah > 0  and orderreseller.status = 'Pending'
                                                                    ORDER BY orderreseller.idorder DESC ");
                                        $no = 1;
                                        while($tampilkan = $datapo->fetch_assoc()){
                                            $jenis = $tampilkan['jenis'];
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="check-item" name="idorder_resellercheck[]" id="idreseller" value="<?= $tampilkan['idorder']; ?>" class="form-control">
                                        </td> 
                                        <td><?= $no++; ?></td>     
                                        <td><?= $tampilkan['tgl']; ?></td>
                                        <td><?= $tampilkan['namaproduk']; ?></td>
                                        <td><?= $tampilkan['variant']; ?></td>
                                        <td><?= $tampilkan['size']; ?></td>
                                        <td><?= $tampilkan['qty']; ?></td>
                                        <td><?= $tampilkan['namaagen']; ?></td>
                                        <td><?= $tampilkan['payment']; ?></td>
                                        <td><?= $tampilkan['status']; ?></td>
                                        <td>
                                            <?php $jumlahhari='+1 days'; ?>
                                            <center>
                                                <div class="badge bg-success text-white rounded-pill"  id="link2<?= $tampilkan['idorder']; ?>">
                                                    Active
                                                </div>
                                                <p id="demomiki<?= $tampilkan['idorder']; ?>" style="color: red;"></p>
                                            </center>      
                                            <?php 
                                                date_default_timezone_set('Asia/Jakarta');
                                                $tgl1 = $tampilkan['tgl'];// pendefinisian tanggal awal
                                                $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
                                            ?>

                                            <script>
                                                // Mengatur waktu akhir perhitungan mundur
                                                var countDownDatemiki<?= $tampilkan['idorder']; ?>= new Date("<?= $tgl2; ?> 23:59:00").getTime();

                                                // Memperbarui hitungan mundur setiap 1 detik
                                                var x = setInterval(function() {

                                                // Untuk mendapatkan tanggal dan waktu hari ini
                                                var now = new Date().getTime();
                                                    
                                                // Temukan jarak antara sekarang dan tanggal hitung mundur
                                                var distance = countDownDatemiki<?= $tampilkan['idorder']; ?> - now;
                                                    
                                                // Perhitungan waktu untuk hari, jam, menit dan detik
                                                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                    
                                                // Keluarkan hasil dalam elemen dengan id = "demo"
                                                document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = days + "d " + hours + "h "
                                                + minutes + "m " + seconds + "s ";
                                                    
                                                // Jika hitungan mundur selesai, tulis beberapa teks 
                                                if (distance < 0) {
                                                    clearInterval(x);
                                                    document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = "<div class='badge bg-danger text-white rounded-pill'>Expired</div>";
                                                    var x = document.getElementById("linkmiki<?= $tampilkan['idorder']; ?>");
                                                    var y = document.getElementById("link2<?= $tampilkan['idorder']; ?>");
                                                
                                                    y.style.display = "none";
                                                    x.style.display = "none";
                                                    }
                                                }, 1000);
                                            </script>
                                        </td>
                                        <td><a href="detailorderreseller2.php?invoice=<?= $tampilkan['invoice']; ?>&jenis=<?= $tampilkan['jenis']; ?>"><?= $tampilkan['invoice']; ?></a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-warning" name="hapusresellercheck_stok"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Barang Akan Kembali Ke Stok Produk');"><span class="fas fa-times" ></span> Check</button> 
                            <button type="submit" class="btn btn-danger" name="hapusresellercheck"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Barang Tidak Akan Kembali Ke Stok');"><span class="fas fa-trash"></span> Check</button>                                
                        </form>

                        <?php                   
                            if(isset($_POST["hapusreseller"])){
                                $idorder            = $_POST['idorder'];
                                $invoice            = $_POST['invoice'];
                            
                                $delete             = "DELETE FROM orderreseller WHERE idorder = '$idorder'";
                                $sql                = mysqli_query( $koneksi, $delete);

                                $cariinvoice        = $koneksi->query("SELECT count(*) as jumlahinv FROM orderreseller WHERE invoice = '$invoice'");
                                $tampilkaninv       = $cariinvoice->fetch_assoc();
                                $jumlahinv          = $tampilkaninv['jumlahinv'];

                                if ($jumlahinv == 0) {
                                    $delete         = "DELETE FROM orderpengiriman WHERE invoice = '$invoice'";
                                    $sql            = mysqli_query( $koneksi, $delete);
                                }
                                                        
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    echo "<script>alert('Produk berhasil dihapus');</script>";
                                    echo "<script>location='ordermitra_keep.php';</script>";
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                }
                            }
                                                
                            if(isset($_POST["hapusresellercheck"])){
                                include "koneksi.php";
                                $idordercheck       = $_POST['idorder_resellercheck'];
                                $jumlah_dipilih     = count($idordercheck);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                    $tampil         = $koneksi->query("SELECT * FROM orderreseller WHERE idorder = '$idordercheck[$x]' ");
                                    $tampilMar      = $tampil->fetch_assoc();
                                    $invoicenya     = $tampilMar['invoice'];
                                    
                                    $koneksi->query("DELETE FROM orderreseller WHERE idorder = '$idordercheck[$x]'" );
                                    
                                    $cariinvoice    = $koneksi->query("SELECT count(*) as jumlahinv FROM orderreseller WHERE invoice = '$invoicenya'");
                                    $tampilkaninv   = $cariinvoice->fetch_assoc();
                                    $jumlahinv      = $tampilkaninv['jumlahinv'];

                                    if ($jumlahinv == 0) {
                                        $delete = "DELETE FROM orderpengiriman WHERE invoice = '$invoicenya'";
                                        $sql = mysqli_query( $koneksi, $delete);
                                    }
                                }
                                echo "<script>alert('data berhasil dihapus');</script>";
                                echo "<script>location='ordermitra_keep.php';</script>";

                            }
                        

                            if(isset($_POST["hapusresellercheck_stok"])){
                                include "koneksi.php";
                                $idordercheck       = $_POST['idorder_resellercheck'];
                                $jumlah_dipilih     = count($idordercheck);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                
                                    $tampil         = $koneksi->query("SELECT * FROM orderreseller WHERE idorder='$idordercheck[$x]' ");
                                    $tampilMar      = $tampil->fetch_assoc();
                                    
                                    $invoicenya     = $tampilMar['invoice'];
                                    $jumlahnya      = $tampilMar['jumlah'];
                                    $produknya      = $tampilMar['idproduk'];

                                    $koneksi->query("UPDATE variants SET stock = stock+'$jumlahnya' WHERE id = '$produknya'" );
                                    $koneksi->query("DELETE FROM orderreseller WHERE idorder='$idordercheck[$x]'" );
                                    
                                    $cariinvoice    = $koneksi->query("SELECT count(*) as jumlahinv FROM orderreseller WHERE invoice = '$invoicenya'");
                                    $tampilkaninv   = $cariinvoice->fetch_assoc();
                                    $jumlahinv      = $tampilkaninv['jumlahinv'];

                                    if ($jumlahinv == 0) {
                                        $delete = "DELETE FROM orderpengiriman WHERE invoice = '$invoicenya'";
                                        $sql = mysqli_query( $koneksi, $delete);
                                    }
                                }
                                echo "<script>alert('data berhasil dihapus');</script>";
                                echo "<script>location='ordermitra_keep.php';</script>";
                            }     
                        ?>
                    </div>
                </div>
                        
                <div id="menu3" class="tab-pane fade">
                    <p>Order Mitra Keep Marketer</p>
                    <div class="table-responsive">
                        <form method="post">    
                            <table class="table table-bordered" id="tbordermarketer">
                                <thead>
                                    <tr>
                                        <th>Check All
                                        <br>  
                                        <input type="checkbox" id="idmarketer" onchange="checkAllMarketer(this)"/></th>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Variant</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                        <th>Nama Marketer</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Waktu Pembayaran</th>
                                        <th>Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php        
                                        $datapo = $koneksi->query("SELECT mitramarketer.namaagen, ordermarketer.idorder, ordermarketer.tgl, ordermarketer.invoice,
                                                                        ordermarketer.payment, ordermarketer.status,  ordermarketer.jumlah as qty, 
                                                                        products.namaproduk, variants.variant, variants.size, variants.jenis 
                                                                    FROM `ordermarketer` 
                                                                    INNER JOIN mitramarketer on ordermarketer.idmitramarketer = mitramarketer.idmitramarketer 
                                                                    INNER JOIN variants on variants.id = ordermarketer.idproduk
                                                                    INNER JOIN products on products.id = variants.idproducts
                                                                    WHERE ordermarketer.jumlah > 0 and ordermarketer.status = 'Pending'
                                                                    ORDER BY ordermarketer.idorder DESC ");
                                        $no     = 1;
                                        while($tampilkan = $datapo->fetch_assoc()){
                                            $jenis = $tampilkan['jenis'];
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="check-item" name="idordercheck[]" value="<?= $tampilkan['idorder']; ?>" class="form-control">
                                        </td>
                                        <td><?= $no++; ?></td>
                                        <td><?= $tampilkan['tgl']; ?></td>
                                        <td><?= $tampilkan['namaproduk']; ?></td>
                                        <td><?= $tampilkan['variant']; ?></td>
                                        <td><?= $tampilkan['size']; ?></td>
                                        <td><?= $tampilkan['qty']; ?></td>
                                        <td><?= $tampilkan['namaagen']; ?></td>
                                        <td><?= $tampilkan['payment']; ?></td>
                                        <td><?= $tampilkan['status']; ?></td>
                                        <td>
                                            <?php $jumlahhari='+1 days'; ?>
                                            <center>
                                                <div class="badge bg-success text-white rounded-pill"  id="link2<?= $tampilkan['idorder']; ?>">
                                                    Active
                                                </div>
                                                <p id="demomiki<?= $tampilkan['idorder']; ?>" style="color: red;"></p>
                                            </center>      
                                            <?php 
                                                date_default_timezone_set('Asia/Jakarta');
                                                $tgl1 = $tampilkan['tgl'];// pendefinisian tanggal awal
                                                $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
                                            ?>

                                            <script>
                                                // Mengatur waktu akhir perhitungan mundur
                                                var countDownDatemiki<?= $tampilkan['idorder']; ?>= new Date("<?= $tgl2; ?> 23:59:00").getTime();

                                                // Memperbarui hitungan mundur setiap 1 detik
                                                var x = setInterval(function() {

                                                // Untuk mendapatkan tanggal dan waktu hari ini
                                                var now = new Date().getTime();
                                                    
                                                // Temukan jarak antara sekarang dan tanggal hitung mundur
                                                var distance = countDownDatemiki<?= $tampilkan['idorder']; ?> - now;
                                                    
                                                // Perhitungan waktu untuk hari, jam, menit dan detik
                                                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                    
                                                // Keluarkan hasil dalam elemen dengan id = "demo"
                                                document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = days + "d " + hours + "h "
                                                + minutes + "m " + seconds + "s ";
                                                    
                                                // Jika hitungan mundur selesai, tulis beberapa teks 
                                                if (distance < 0) {
                                                    clearInterval(x);
                                                    document.getElementById("demomiki<?= $tampilkan['idorder']; ?>").innerHTML = "<div class='badge bg-danger text-white rounded-pill'>Expired</div>";
                                                    var x = document.getElementById("linkmiki<?= $tampilkan['idorder']; ?>");
                                                    var y = document.getElementById("link2<?= $tampilkan['idorder']; ?>");
                                                
                                                    y.style.display = "none";
                                                    x.style.display = "none";
                                                    }
                                                }, 1000);
                                            </script>
                                        </td>
                                        <td><a href="detailordermarketer2.php?invoice=<?= $tampilkan['invoice']; ?>&jenis=<?= $tampilkan['jenis']; ?>"><?= $tampilkan['invoice']; ?></a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-warning" name="hapusmarketercheck_stok"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Barang Akan Kembali Ke Stok Produk');"><span class="fas fa-times" ></span> Check</button>
                            <button type="submit" class="btn btn-danger" name="hapusmarketercheck"  onclick="return confirm('Yakin Akan Menghapus Pesanan? Barang Tidak Akan Kembali Ke Stok');"><span class="fas fa-trash"></span> Check</button>

                        </form>                    
                        <?php
                            if(isset($_POST["hapusmarketer"])){
                                $idorder        = $_POST['idorder'];
                                $invoice        = $_POST['invoice'];
                            
                                $delete         = "DELETE FROM ordermarketer WHERE idorder = '$idorder'";
                                $sql            = mysqli_query( $koneksi, $delete);

                                $cariinvoice    = $koneksi->query("SELECT count(*) as jumlahinv FROM ordermarketer WHERE invoice = '$invoice'");
                                $tampilkaninv   = $cariinvoice->fetch_assoc();
                                $jumlahinv      = $tampilkaninv['jumlahinv'];

                                if ($jumlahinv == 0) {
                                    $delete = "DELETE FROM orderpengiriman WHERE invoice = '$invoice'";
                                    $sql = mysqli_query( $koneksi, $delete);
                                }

                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    echo "<script>alert('Produk berhasil dihapus');</script>";
                                    echo "<script>location='ordermitra_keep.php';</script>";
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                }
                            }
                                
                            if(isset($_POST["hapusmarketercheck"])){
                                include "koneksi.php";
                                $idordercheck       = $_POST['idordercheck'];
                                $jumlah_dipilih     = count($idordercheck);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                    $koneksi->query("DELETE FROM ordermarketer WHERE idorder = '$idordercheck[$x]'" );
                                    
                                    $tampil         = $koneksi->query("SELECT * FROM ordermarketer WHERE idorder = '$idordercheck[$x]' ");
                                    $tampilMar      = $tampil->fetch_assoc(); 
                                    $invoicenya     = $tampilMar['invoice'];
                                    
                                    $cariinvoice    = $koneksi->query("SELECT count(*) as jumlahinv FROM ordermarketer WHERE invoice = '$invoicenya'");
                                    $tampilkaninv   = $cariinvoice->fetch_assoc();
                                    $jumlahinv      = $tampilkaninv['jumlahinv'];
                                    if ($jumlahinv == 0) {
                                        $delete     = "DELETE FROM orderpengiriman WHERE invoice = '$invoicenya'";
                                        $sql        = mysqli_query( $koneksi, $delete);
                                    }
                                }
                                echo "<script>alert('data berhasil dihapus');</script>";
                                echo "<script>location='ordermitra_keep.php';</script>";
                            }
        

                            if(isset($_POST["hapusmarketercheck_stok"])){
                                include "koneksi.php";
                                $idordercheck       = $_POST['idordercheck'];
                                $jumlah_dipilih     = count($idordercheck);
                                for($x = 0; $x < $jumlah_dipilih; $x++){
                                    $tampil         = $koneksi->query("SELECT * FROM ordermarketer WHERE idorder = '$idordercheck[$x]' ");
                                    $tampilMar      = $tampil->fetch_assoc();
                                    $invoicenya     = $tampilMar['invoice'];
                                    $jumlahnya      = $tampilMar['jumlah'];
                                    $produknya      = $tampilMar['idproduk'];
                                    
                                    $koneksi->query("UPDATE variants SET stock = stock+'$jumlahnya' WHERE id = '$produknya'" );
                                    $koneksi->query("DELETE FROM ordermarketer WHERE idorder = '$idordercheck[$x]'" );
                                    
                                    $cariinvoice    = $koneksi->query("SELECT count(*) as jumlahinv FROM ordermarketer WHERE invoice = '$invoicenya'");
                                    $tampilkaninv   = $cariinvoice->fetch_assoc();
                                    $jumlahinv      = $tampilkaninv['jumlahinv'];

                                    if ($jumlahinv == 0) {
                                        $delete     = "DELETE FROM orderpengiriman WHERE invoice = '$invoicenya'";
                                        $sql        = mysqli_query( $koneksi, $delete);
                                    }
                                }
                                echo "<script>alert('data berhasil dihapus');</script>";
                                echo "<script>location='ordermitra_keep.php';</script>";
                            }        
                        ?>
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

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
  
  <?php include "settingdatatables.php"; ?>

    <script type="text/javascript">
  function checkAllMarketer(box) 
  {

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    var idordercheck = document.getElementsByName("idordercheck[]");
    var jml=idordercheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idordercheck[b].checked=true;
        
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    var idordercheck = document.getElementsByName("idordercheck[]");
    var jml=idordercheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idordercheck[b].checked=false;
        
    }
   }
  }
  
 
   function checkAllReseller(box) 
  {

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    var idorder_resellercheck = document.getElementsByName("idorder_resellercheck[]");
    var jml=idorder_resellercheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idorder_resellercheck[b].checked=true;
        
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    var idorder_resellercheck = document.getElementsByName("idorder_resellercheck[]");
    var jml=idorder_resellercheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idorder_resellercheck[b].checked=false;
        
    }
   }
  }
  
   function checkAllAgen(box) 
  {

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    var idorder_agencheck = document.getElementsByName("idorder_agencheck[]");
    var jml=idorder_agencheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idorder_agencheck[b].checked=true;
        
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    var idorder_agencheck = document.getElementsByName("idorder_agencheck[]");
    var jml=idorder_agencheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idorder_agencheck[b].checked=false;
        
    }
   }
  }  

     function checkAllDB(box) 
  {

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    var idorder_dbcheck = document.getElementsByName("idorder_dbcheck[]");
    var jml=idorder_dbcheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idorder_dbcheck[b].checked=true;
        
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    var idorder_dbcheck = document.getElementsByName("idorder_dbcheck[]");
    var jml=idorder_dbcheck.length;
    var b=0;
    for (b=0;b<jml;b++)
    {
        idorder_dbcheck[b].checked=false;
        
    }
   }
  }
  
 </script> 

</body>

</html>

		                                                