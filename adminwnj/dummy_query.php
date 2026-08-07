<?php
  session_start();

  include 'koneksi.php';

  if(!isset($_SESSION['administrator'])) {
    echo "<srcipt>alert('Anda harus login terlebih dahulu');</srcipt>";
    echo "<script>location='login.php';</script>";
    header('location:login.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>WNJ.ID</title>

  <!-- Custom Fonts For This Template -->
  <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Bootstrap 5.3 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="sidebar-toggled" id="page-top">

  <div id="wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="container-fluid">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"></h1>
      </div>

      <h3 class="fw-bold text-uppercase">Order Mitra Keep</h3>

      <ul class="nav nav-tabs my-2">
        <li class="active"><a href="#home" data-toggle="tab" class="nav-item nav-link active">Distributor</a></li>
        <li><a href="#menu1" class="nav-item nav-link" data-toggle="tab">Agen</a></li>
        <li><a href="#menu2" class="nav-item nav-link" data-toggle="tab">Reseller</a></li>
        <li><a href="#menu3" class="nav-item nav-link" data-toggle="tab">Marketer</a></li>
      </ul>

      <div class="tab-content">
        <!-- Distributor Tab Start -->
        <div id="home" class="tab-pane fade show active" role="tabpanel">
          <h6>Order Mitra Keep Distributor</h6>
          
          <div class="table-responsive">
            <form method="post">
              <table class="table table-bordered" id="tborderdb">
                <thead>
                  <tr>
                    <th>
                      <small>
                        Check All
                      </small> <br>
                      <input type="checkbox" id="pilihsemua" onchange="checkAllDB(this)">
                    </th>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>QTY</th>
                    <th>Nama Mitra</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Waktu Pembayaran</th>
                    <th>Invoice</th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                    $datapo=$koneksi->query("SELECT admin_mitra.namamitra,
                    ordermitra.idorder,
                    ordermitra.tgl,
                    ordermitra.invoice,
                    ordermitra.payment,
                    ordermitra.status, 
                    ordermitra.jumlah as qty, 
                    produk.namaproduk 
                    FROM `ordermitra` 
                    inner join admin_mitra on ordermitra.idmitra=admin_mitra.idadmin 
                    inner join produk on produk.idproduk = ordermitra.idproduk
                    WHERE ordermitra.jumlah>0  and ordermitra.status = 'Pending'
                    ORDER BY ordermitra.idorder DESC ");
                  $no=1;
                
                  while($tampilkan=$datapo->fetch_assoc()){
                  ?>
                  <tr>
                    <td>
                      <input type="checkbox" class="check-item" name="idorder_dbcheck[]" id="iddb" value="<?= $tampilkan['idorder']; ?>">
                    </td>
                    <td><?= $no++; ?></td>
                    <td><?= $tampilkan['tgl']; ?></td>
                    <td><?= $tampilkan['namaproduk']; ?></td>
                    <td><?= $tampilkan['qty']; ?></td>
                    <td><?= $tampilkan['namamitra']; ?></td>
                    <td><?= $tampilkan['payment']; ?></td>
                    <td><?= $tampilkan['status']; ?></td>
                    <td>
                      <?php
                        $jumlahhari = '1+ Days';
                      ?>

                      <span class="badge bg-success rounded-pill" id="link2<?= $tampilkan['idorder']; ?>">Active</span>
                      <p id="demomiki<?= $tampilkanp['idorder']; ?>" class="text-danger"></p>
                      
                      <?php
                        date_default_timezone_set('Asia/Jakarta');
                        $tgl1 = $tampilkan['tgl'];
                        $tgl2 = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1)));
                      ?>

                      <script>
                        // Mengatur waktu akhir perhitungan mundur
                        var countDownDatemiki<?= $tampilkan['idorder']; ?>= new Date("<?php echo $tgl2; ?> 23:59:00").getTime();

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
                    <td><a href="detailorder.php?invoice=<?= $tampilkan['invoice']; ?>"><?= $tampilkan['invoice']; ?></a></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              <button type="submit" class="btn btn-warning" name="hapusdbcheck_stok" onclick="return confirm('Yakin akan menghapus pesanan? Barang akan kembali lagi ke stok produk.');">
                <span class="fas fa-times"></span> Check
              </button>
              <button type="submit" class="btn btn-danger" name="hapusdbcheck" onclick="return confirm('Yakin akan menghapus pesanan? Barang tidak akan kembali ke stok produk.')"><span class="fas fa-trash"></span> Check</button>
            </form>
          </div>

          <!-- SQL Query -->
          <?php
            if(isset($_POST['hapusdbcheck'])) {
              include 'koneksi.php';
              $idordercheck = $_POST['idorder_dbcheck'];
              $jumlah_dipilih = count($idordercheck);
              for ($x=0; $x < $jumlah_dipilih; $x++) { 
                $tampil = $koneksi->query("SELECT * FROM ordermitra WHERE idorder='$idordercheck[$x]' ");
                $tampilMar = $tampil->fetch_assoc();
                $invoicenya = $tampilMar['invoice'];

                $koneksi->query("DELETE FROM ordermitra WHERE idorder='$idordercheck[$x]' ");
                $cariinvoice = $koneksi->query("SELECT COUNT(*) AS jumlahinv FROM ordermitra WHERE invoice='$invoicenya' ");
                $tampilkaninv = $cariinvoice->fetch_assoc();
                $jumlahinv = $tampilkaninv['jumlahinv'];
                
                if ($jumlahinv==0) {
                  $delete = "DELETE FROM orderpengiriman WHERE invoice='$invoicenya' ";
                  $sql = mysqli_query($koneksi, $delete);
                }
              }

              echo "<script>alert('Data berhasil dihapus');</script>";
              echo "<script>location='ordermitra_keep.php'</script>";
            }

            if(isset($_POST['hapusdbcheck_stok'])) {
              include 'koneksi.php';
              $idordercheck = $_POST['idorder_check'];
              $jumlah_dipilih = count($idordercheck);

              for ($x=0; $x < $jumlah_dipilih; $x++) { 
                $tampil = $koneksi->query("SELECT * FROM ordermitra WHERE idorder='$idordercheck[$x]' ");
                $tampilMar = $tampil->fetch_assoc();

                $invoicenya = $tampilMar['invoice'];
                $jumlahnya = $tampilMar['jumlah'];
                $produknya = $tampilMar['idproduk'];

                $koneksi->query("UPDATE produk SET stock=stock+'$jumlahnya' WHERE idproduk='$produknya' ");
                $koneksi->query("DELETE FROM ordermitra WHERE idorder='$idordercheck[$x]'");

                if ($jumlahinv==0) {
                  $delete = "DELETE FROM orderpengiriman WHERE invoice='$invoicenya'";
                  $sql = mysqli_query($koneksi, $delete);
                }
              }

              echo "<script>alert('Data berhasl dihapus');</script>";
              echo "<script>location='ordermitra_keep.php'</script>";
            }
          ?>
        </div>
        <!-- Distributor Tab End -->

        <!-- Agen Tab Start -->
        <div id="menu1" class="tab-pane fade">
            <h6>Order Mitra Keep Agen</h6>

            <div class="table-responsive">
              <form method="post">
                <table class="table table-bordered" id="tborderagen">
                  <thead>
                    <tr>
                      <th>
                        <span class="fs-6 d-block">Check All <input type="checkbox" id="pilihsemua" onchange="checkAll(this)"></span>
                      </th>
                      <th>No</th>
                      <th>Tanggal</th>
                      <th>Nama Barang</th>
                      <th>QTY</th>
                      <th>Nama Mitra</th>
                      <th>Payment</th>
                      <th>Status</th>
                      <th>Waktu Pembayaran</th>
                      <th>Invoice</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                      $datapo = $koneksi->query("SELECT mitraagen.namaagen,
                        orderagen.idorder,
                        orderagen.tgl,
                        orderagen.invoice,
                        orderagen.payment,
                        orderagen.status,
                        orderagen.jumlah AS qty,
                        produk.namaproduk
                        FROM `orderagen`INNER JOIN mitraagen ON
                        orderagen.idmitraagen = mitraagen.idmitraagen INNER JOIN produk ON
                        produk.idproduk = orderagen.idproduk WHERE orderagen.jumlah > 0 AND
                        orderagen.status = 'Pending' ORDER BY orderagen.idorder DESC
                      ");

                      while ($tampilkan = $datapo->fetch_assoc()) {
                    ?>
                      <tr>
                        <td></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </form>
            </div>
        </div>
        <!-- Agen Tab End -->
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