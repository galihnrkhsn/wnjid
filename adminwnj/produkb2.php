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
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>
          <!-- Content Row -->
          <div class="row">
              <h3><strong>Stock Produk</strong></h3>
              </div>
              
            <a class="btn btn-primary" href="inputproduk.php"><span class="fas fa-plus"></span> Tambah Produk</a>
            <br><br>
            <form action="excelproduk2.php" method="GET">
                <input type="hidden" name="idkategori" value="2">
                <button type="submit" class="btn btn-success">
                    <span class="fas fa-print"></span> Export Excel
                </button>
            </form>
            <br>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="tb_produkb">
                      <thead>
                        <tr>
                            <th>No</th>    
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Berat</th>
                            <th>Kategori</th>
                            <th>Stock</th>
                            <th>Tanggal</th>
                            <th>Opsi Stock</th>
                            <th>Opsi Produk</th>
                        </tr>
                      </thead>
                      <tbody>
                            <?php
                            $no = 1;
                              $dataproduk = $koneksi->query("SELECT kategori.namakategori, variants.id, variants.berat, products.namaproduk, variants.harga, 
                                                                    variants.tgl, variants.stock, variants.foto 
                                                                FROM products
                                                                INNER JOIN variants ON variants.idproducts = products.id 
                                                                INNER JOIN kategori ON products.idkategori = kategori.idkategori 
                                                                WHERE products.idkategori = 2 
                                                                ORDER BY products.namaproduk ASC");
                                while($tampilkan=$dataproduk->fetch_assoc()){
                            ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $tampilkan['namaproduk']; ?></td>
                            <td><?= $tampilkan['harga']; ?></td>
                            <td><?= $tampilkan['berat']; ?></td>
                            <td><?= $tampilkan['namakategori']; ?></td>
                            <td><?= $tampilkan['stock']; ?></td>
                            <td><?= $tampilkan['tgl']; ?></td>
                            <td class="align-middle">
                                <form method="post">  
                                    <input type="hidden" name="idproduk" value="<?= $tampilkan['idproduk']; ?>">
                                    <input type="text" class="form-control" name="stok" size="1">
                                    <button type="submit" class="btn btn-success btn-xs" name="ubah">Ubah</button>
                                </form>
                            </td>
                            <td>
                                <a href="editproduk.php?idproduk=<?= $tampilkan['idproduk']; ?>">Edit</a><hr>
                                <?php 
                                    if($tampilkan['foto']=='') {  
                                        echo "<a href='editfoto.php?idproduk=$tampilkan[idproduk]'>Tambah Foto</a>";
                                    } else { 
                                        echo "<a href='editfoto.php?idproduk=$tampilkan[idproduk]'>Update Foto</a>";
                                    } 
                                ?>
                                <hr>
                                <form method="post"> 
                                    <input type="hidden" name="id" value=<?= $tampilkan['idproduk']; ?>>
                                    <button class="btn btn-danger" name="hapus"><span class="fas fa-trash"></span></button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
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

  <?php
                            if(isset($_POST["tambah"])){
  
                                $idproduk = $_POST["idproduk"];
                                $stok = $_POST["stok"];
                            
                                $query = "UPDATE variants SET stock = stock+".$stok." WHERE id = '$idproduk'";
                                $sql = mysqli_query( $koneksi, $query);
                            
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: produk.php"); // Redirect ke halaman index.php
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                    echo "<br><a href='index.php'>Kembali Ke Form</a>";
                                    }
                            }else if(isset($_POST["ubah"])){
                
                                $idproduk = $_POST['idproduk'];
                                $stok = $_POST["stok"];
                                
                                $query = "UPDATE variants SET stock= '".$stok."' WHERE id = '$idproduk'";
                                $sql = mysqli_query( $koneksi, $query);
                            
                                if($sql){ // Cek jika proses simpan ke database sukses atau tidak
                                    // Jika Sukses, Lakukan :
                                        header("location: produkb.php"); // Redirect ke halaman index.php
                                }else{
                                    // Jika Gagal, Lakukan :
                                    echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
                                    echo "<br><a href='form_ubah.php'>Kembali Ke Form</a>";
                                    }     
                            }
                            if(isset($_POST["hapus"])){
         
                                $id = $_POST['id'];
                                $koneksi->query("DELETE FROM variants WHERE id = '$id'" );
                                echo "<script>alert('data berhasil dihapus');</script>";
                                echo "<script>location='produk.php';</script>";
                            }
                            ?>
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
  <?php include "settingdatatables.php" ?>

</body>

</html>

                                        