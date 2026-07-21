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

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

<?php include "sidebar.php"; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

       

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
           
          </div>

          <!-- Content Row -->
          <div class="row">

<h3><strong>Daftar Keranjang Mitra</strong></h3>
</div>
<div class="table-responsive">
  <form method="post">
<table class="table table-striped" id="tbkeranjang">
<thead>
                        <tr>
                            <th>
                            No    
                            </th>
                            <th>Check All<input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                            <th>
                            Nama Produk
                          </th>
                          <th>Variant</th>
                          <th>Size</th>
                           <th>
                            Nama DB
                          </th>
                          <th>
                            Nama Sub DB
                          </th>
                           <th>
                            Jumlah
                          </th>
                          <th>
                           Tanggal / Waktu
                          </th>
                          <th>
                           Status
                          </th>
                        </tr>
                      </thead>
                      
                      <tbody>
                            <?php
                                $no = 1;  
                                $dataproduk = $koneksi->query("SELECT variants.id, keranjang.idkeranjang, keranjang.status,
                                                                keranjang.waktu, products.namaproduk, admin_mitra.namamitra, 
                                                                mitraagen.namaagen as agen, 
                                                                mitrareseller.namaagen as reseller, 
                                                                mitramarketer.namaagen as marketer,
                                                                keranjang.jmlh, keranjang.tgl,
                                                                variants.variant,
                                                                variants.size
                                                                FROM keranjang 
                                                                LEFT JOIN mitraagen ON keranjang.idagen = mitraagen.idmitraagen 
                                                                LEFT JOIN mitrareseller ON keranjang.idreseller = mitrareseller.idmitrareseller 
                                                                LEFT JOIN mitramarketer ON keranjang.idmarketer = mitramarketer.idmitramarketer 
                                                                LEFT JOIN admin_mitra on (keranjang.idmitra = admin_mitra.idadmin 
                                                                or mitraagen.idadmin = admin_mitra.idadmin 
                                                                or mitrareseller.idadmin = admin_mitra.idadmin 
                                                                or mitramarketer.idadmin = admin_mitra.idadmin) 
                                                                INNER JOIN variants ON keranjang.idproduk = variants.id
                                                                INNER JOIN products ON variants.idproducts = products.id
                                                                order by keranjang.idkeranjang desc");
                              
                            while($tampilkan=$dataproduk->fetch_assoc()){
                            ?>
                        <tr>
                            <td>
                                <?php echo $no++; ?>
                            </td>
                                  <td>
                              <input type="checkbox" class="check-item" name="idkeranjang[]" value="<?php echo $tampilkan['idkeranjang']; ?>" class="form-control">
                              <input type="hidden" class="check-item" name="idproduk[]" value="<?php echo $tampilkan['idproduk']; ?>" class="form-control">
                              <input type="hidden" class="check-item" name="jmlh[]" value="<?php echo $tampilkan['jmlh']; ?>" class="form-control">
                            </td>  
                          <td>
                            <?php echo $tampilkan['namaproduk']; ?>
                          </td>
                          <td><?= $tampilkan['variant'] ?></td>
                          <td><?= $tampilkan['size'] ?></td>
                          <td>
                            <?php echo $tampilkan['namamitra']; ?>
                          </td>
                          <td>
                            <?php if($tampilkan['agen']<>'')
                            { echo "Agen $tampilkan[agen]"; }
                            if($tampilkan['reseller']<>'')
                            { echo "Reseller $tampilkan[reseller]"; }
                            if($tampilkan['marketer']<>'')
                            { echo "Marketer $tampilkan[marketer]"; } ?>
                          </td>
                          <td>
                            <?php echo $tampilkan['jmlh']; ?>
                          </td>
                          <td>
                           <?php echo $tampilkan['tgl']; ?> / <?php echo $tampilkan['waktu']; ?>
                          </td>
                          <td>
                          <?php if ($tampilkan['status']=='Active'): ?>
                            <div class="badge bg-success text-white rounded-pill">
                                <?php echo $tampilkan['status']; ?>
                            </div>
                          <?php endif ?>
                          <?php if ($tampilkan['status']=='Expired'): ?>
                            <div class="badge bg-danger text-white rounded-pill">
                                <?php echo $tampilkan['status']; ?>
                            </div>
                          <?php endif ?>
                          </td>
                    
              
                        </tr>
                        <?php } ?>
                      </tbody>

                    </table>
                    <button type="submit" class="btn btn-warning" name="hapuscheck_stok" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang? Barang AKAN KEMBALI Ke Stok Produk');"><span class="fas fa-times" ></span> Check</button>
                    &nbsp;
                    <button type="submit" class="btn btn-danger" name="hapuscheck" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang? Barang TIDAK AKAN KEMBALI Ke Stok Produk');"><span class="fas fa-trash" ></span> Check</button>
                    </form>
                  </div>

<?php
    if(isset($_POST["hapuscheck"])){
        include "koneksi.php";
        $idkeranjang        = $_POST['idkeranjang'];
        $idproduk           = $_POST['idproduk'];
        $jmlh               = $_POST['jmlh'];
        $jumlah_dipilih     = count($idkeranjang);
        for($x = 0; $x < $jumlah_dipilih; $x++){
            $tampil         = $koneksi->query("SELECT * FROM keranjang WHERE idkeranjang='$idkeranjang[$x]' ");
            $tampilMas      = $tampil->fetch_assoc();
            $idproduk       = $tampilMas['idproduk'];
            $jmlh           = $tampilMas['jmlh'];

            $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'" );
        }
        echo "<script>alert('data berhasil dihapus');</script>";
        echo "<script>location='keranjang.php';</script>";
    } 

    if(isset($_POST["hapuscheck_stok"])){
        include "koneksi.php";
        $idkeranjang        = $_POST['idkeranjang'];
        $idproduk           = $_POST['idproduk'];
        $jmlh               = $_POST['jmlh'];
        $jumlah_dipilih     = count($idkeranjang);
        for($x = 0; $x < $jumlah_dipilih; $x++){
            $tampil         = $koneksi->query("SELECT * FROM keranjang WHERE idkeranjang='$idkeranjang[$x]' ");
            $tampilMas      = $tampil->fetch_assoc();
            $idproduk       = $tampilMas['idproduk'];
            $jmlh           = $tampilMas['jmlh'];

            $koneksi->query("UPDATE variants SET stock = stock+'$jmlh' WHERE id = '$idproduk'");
            $koneksi->query("DELETE FROM keranjang WHERE idkeranjang = '$idkeranjang[$x]'" );
        }
        echo "<script>alert('data berhasil dihapus');</script>";
        echo "<script>location='keranjang.php';</script>";
    }                  
?>
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
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

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

  <script type="text/javascript">
  function checkAll(box) 
  {
   let checkboxes = document.getElementsByTagName('input');

   if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
    for (let i = 0; i < checkboxes.length; i++) {
     if (checkboxes[i].type == 'checkbox') {
      checkboxes[i].checked = true;
     }
    }
   } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
    for (let i = 0; i < checkboxes.length; i++) {
     if (checkboxes[i].type == 'checkbox') {
      checkboxes[i].checked = false;
     }
    }
   }
  }
 </script>

<?php include "settingdatatables.php"; ?>

</body>

</html>

                        