<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}
$publish    = $_GET["id"];
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>WNJ.ID</title>

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
           <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>

          <!-- Content Row -->
          <div class="row">
              
              <h3><strong>Stock Produk</strong></h3>
              </div>
              
                <div class="d-flex align-items-center justify-content-between">
                  <a href="inputproduk.php" class="btn btn-primary btn-sm">Tambah Produk</a>
                  <div class="d-flex align-items-center">
                    <form action="excelproduk2.php" method="GET">
                      <input type="hidden" name="status" value="<?= $publish ?>">
                      <button type="submit" class="btn btn-success btn-sm">Export Produk Publish</button>
                    </form>
                  </div>
                </div>
                <p class="my-1" align="left">
                  <input type="radio" name="radio" onclick="javascript:window.location.href='produk.php?id=0'; "> Publish
                  <input type="radio" name="radio" onclick="javascript:window.location.href='produk.php?id=1'; "> Unpublish
                </p>
                <form method="post">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="tb_produk">
                        <thead>
                            <tr>
                            <th>No</th>  
                            <th><input type='checkbox' id='checkAll' > Check</th>
                            <th>Status</th>
                            <th>Produk Kategori</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Berat</th>
                            <th>Grade</th>
                            <th>Stock</th>
                            <th>Opsi Stock</th>
                            <th>Opsi Diskon Kategori</th>
                            <th><i class="fa fa-cog" aria-hidden="true"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $publish    = $_GET["id"];
                            $no         = 1;  
                            $dataproduk = $koneksi->query("SELECT pkategori.namakategori,
                                                                kategori.namakategori as grrade,
                                                                produk.idproduk,
                                                                produk.berat,
                                                                produk.status,
                                                                produk.namaproduk,
                                                                produk.harga,
                                                                produk.tgl,
                                                                produk.stock,
                                                                produk.foto 
                                                        FROM produk 
                                                        LEFT join kategori ON produk.idkategori=kategori.idkategori 
                                                        LEFT JOIN pkategori on pkategori.idpkategori=produk.idpkategori 
                                                        where produk.idkategori>0 
                                                        and produk.status='$publish' 
                                                        order by produk.idproduk desc ");
                            
                        while($tampilkan=$dataproduk->fetch_assoc()){
                            $id = $tampilkan['idproduk'];
                        ?>
                            <tr> 
                            <td><?php echo $no++; ?></td>  
                            <td><input type="checkbox" class="check-item" name="idproduk[]" value="<?php echo $tampilkan['idproduk']; ?>" class="form-control"></td>
                            <td><?php if($tampilkan['status']==0){
                                    echo "<span class='badge bg-success text-white'>Publish</span>"; }
                                    else { echo "<span class='badge bg-danger text-white'>Unpublish</span>"; }
                                ?></td> 
                            <td><?php echo $tampilkan['namakategori']; ?></td>
                            <td><?php echo $tampilkan['namaproduk']; ?></td>
                            <td><?php echo $tampilkan['harga']; ?></td>
                            <td><?php echo $tampilkan['berat']; ?></td>
                            <td><?php echo $tampilkan['grrade']; ?></td>
                            <td><?php echo $tampilkan['stock']; ?></td>
                            <td class="align-middle"><input type="number" class="form-control" name="stock<?= $id ?>" size="1"></td>
                            <td class="align-middle">
                            <select class="form-control" name="idkategori[]">
                            <?php
                            $ambil=$koneksi->query("SELECT * FROM kategori ORDER BY idkategori asc");
                            while($row=$ambil->fetch_assoc()){
                            ?>
                            <option value="<?php echo $row['idkategori']; ?>">
                            <?php echo $row['namakategori']; ?>
                            </option>
                            <?php } ?>                            
                                            </select>
                                        </td>
                                        <td><a href="editproduk.php?idproduk=<?php echo $tampilkan['idproduk']; ?>">Edit</a>

                                        </td>
                                        </tr>
                            <?php } ?>
                            
                        </tbody>
                    </table>
                    <select name="opsi" class="form-control">
                                         <option>Publish</option>
                                         <option>Unpublish</option>
                                         <option>Update Stock</option>
                                         <option>Update Kategori Diskon</option>
                                         <!-- <option>Hapus</option> -->
                                         </select><br>
                                         <button type="submit" class="btn btn-primary" name="simpan">Simpan</button> 
                    </div>
                    </form>

                    <?php
                    if(isset($_POST["simpan"])){
	                    $idproduk = $_POST["idproduk"];
	                    $opsi = $_POST["opsi"];
                      $stock = $_POST["stock"];
                      $idkategori = $_POST["idkategori"];
                      $jumlah_dipilih=count($idproduk);
                      if($opsi=="Publish"){
                          for($x=0;$x<$jumlah_dipilih;$x++){
  	                      $sql = $koneksi->query("UPDATE produk set status=0 where idproduk='$idproduk[$x]'");
                          if ($sql) {
                          echo "<script>alert('Produk berhasil dipublish');</script>";
                          echo "<script>location='produk.php?id=$publish';</script>";
                          }else{
                          echo "<script>alert('Produk gagal dipublish');</script>";
                          echo "<script>location='produk.php?id=$publish';</script>";                          
                          }
                        }
                      }  
                      if($opsi=="Unpublish"){
                        for($x=0;$x<$jumlah_dipilih;$x++){
                          $sql = $koneksi->query("UPDATE produk set status=1 where idproduk='$idproduk[$x]'");
                          if ($sql) {
                          echo "<script>alert('Produk berhasil diunpublish');</script>";
                          echo "<script>location='produk.php?id=$publish';</script>";
                          }else{
                          echo "<script>alert('Produk gagal diunpublish');</script>";
                          echo "<script>location='produk.php?id=$publish';</script>";                          
                          }
                        }   
                      }
                      if($opsi=="Update Stock"){

                        if(isset($_POST['idproduk'])){
                          foreach($_POST['idproduk'] as $updateid){

                            $stock = $_POST['stock'.$updateid];
                            // echo "<script>alert('$updateid $stock');</script>";
                          $sql = $koneksi->query("UPDATE produk set stock=$stock where idproduk='$updateid'");
                          if ($sql) {
                          echo "<script>alert('Produk berhasil diupdate stock');</script>";
                          echo "<script>location='produk.php?id=$publish';</script>";
                          }else{
                          echo "<script>alert('Produk gagal diupdate stock');</script>";
                          echo "<script>location='produk.php?id=$publish';</script>";                          
                          }                            
                    
                           }                        


                        }   
                      }
                      if($opsi=="Update Kategori Diskon"){
                        for($x=0;$x<$jumlah_dipilih;$x++){
                          $sql = $koneksi->query("UPDATE produk set idkategori=$idkategori[$x] where idproduk='$idproduk[$x]'");
                          if ($sql) {
                          echo "<script>alert('Produk berhasil diupdate kategori diskon');</script>";
                          echo "<script>location='produk.php?id=$publish';</script>";
                          }else{
                          echo "<script>alert('Produk gagal diupdate kategori diskon');</script>";
                          echo "<script>location='produk.php?id=$publish';</script>";                          
                          }
                        }   
                      }
                      // if($opsi=="Hapus"){
                      //   for($x=0;$x<$jumlah_dipilih;$x++){
                      //   // $koneksi->query("delete from produk where idproduk='$idproduk[$x]'");
                      //   echo "<script>alert('produk Hapus');</script>";
                      //   echo "<script>location='produk.php';</script>";
                      //   }   
                      // }
                    }  
                    ?>    

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
  <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
  <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>



<script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="idproduk[]"]').prop('checked',true);
                    }else{
                        $('input[name="idproduk[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="idproduk[]"]').click(function(){
                    var total_checkboxes = $('input[name="idproduk[]"]').length;
                    var total_checkboxes_checked = $('input[name="idproduk[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll').prop('checked',true);
                    }else{
                        $('#checkAll').prop('checked',false);
                    }
                });
            });
        </script>
<?php include "settingdatatables.php" ?>
</body>

</html>

		                                    