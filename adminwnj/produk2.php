<?php 
session_start();

include 'koneksi.php'; 

if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$publish = $_GET['id'];
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="container-fluid">
            <!-- CONTENT -->
            <div class="row">
                <h3><strong>Stock Produk</strong></h3>
            </div>
            <a class="btn btn-primary" href="tambah_produk"><span class="fas fa-plus"></span> Tambah Produk</a>
            <br><br>
            <form action="excelproduk3.php" method="GET">
                <input type="hidden" name="status" value="<?= $publish ?>">
                <button type="submit" class="btn btn-success btn-sm">Export Produk Publish</button>
            </form>
            <br>
            <!-- <a class="btn btn-success" href="produk2.php"><span class="fas fa-print"></span> Export Excel</a><br><br> -->
            <p align="left"><input type="radio" name="radio" onclick="javascript:window.location.href='produk2.php?id=0'; "> Publish  &nbsp&nbsp<input type="radio" name="radio" onclick="javascript:window.location.href='produk2.php?id=1'; "> Unpublish</p>
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
                                <th>Variant</th>
                                <th>Size</th>
                                <th>Harga</th>
                                <th>Berat</th>
                                <th>Grade</th>
                                <th>Stock</th>
                                <th>Opsi Stock</th>
                                <th>Diskon</th>
                                <th>Opsi Diskon Kategori</th>
                                <th><i class="fa fa-cog" aria-hidden="true"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $publish = $_GET["id"];

                            $no = 1;
                            $dataproduk = $koneksi->query("SELECT v.id, v.size, v.variant, v.size, v.berat, v.harga, v.hargacoret, v.stock, v.foto, v.status,
                                                                p.namaproduk, v.disc,
                                                                k.namakategori AS kategori, pk.namakategori AS pkategori
                                                            FROM variants v
                                                            INNER JOIN products p ON v.idproducts = p.id
                                                            INNER JOIN kategori k ON p.idkategori = k.idkategori
                                                            INNER JOIN pkategori pk ON p.idpkategori = pk.idpkategori
                                                            WHERE v.status = '$publish'
                                                            ORDER BY p.id DESC");
                            while ($tampilkan = $dataproduk->fetch_assoc()) {
                                $id = $tampilkan['id'];
                        ?>
                            <tr> 
                                <td><?= $no++; ?></td>  
                                <td><input type="checkbox" class="check-item" name="id[]" value="<?= $tampilkan['id']; ?>" class="form-control"></td>
                                <td>
                                    <?php 
                                        if ($tampilkan['status'] == 0) {
                                            echo "<span class='badge bg-success text-white'>Publish</span>";
                                        } else {
                                            echo "<span class='badge bg-danger text-white'>Unpublish</span>";
                                        }
                                    ?>
                                </td> 
                                <td><?= $tampilkan['pkategori']; ?></td>
                                <td><?= $tampilkan['namaproduk']; ?></td>
                                <td><?= $tampilkan['variant']; ?></td>
                                <td><?= $tampilkan['size']; ?></td>
                                <td><?= $tampilkan['harga']; ?></td>
                                <td><?= $tampilkan['berat']; ?></td>
                                <td><?= $tampilkan['kategori']; ?></td>
                                <td><?= $tampilkan['stock']; ?></td>
                                <td class="align-middle"><input type="number" class="form-control" name="stock[<?= $id; ?>]" size="1"></td>
                                <td class="align-middle"><input type="number" class="form-control" value="<?= $tampilkan['disc'] ?>" name="diskon[<?= $id; ?>]" size="1"></td>
                                <td class="align-middle">
                                    <select class="form-control" name="idkategori[<?= $id; ?>]">
                                        <?php
                                        $ambil = $koneksi->query("SELECT * FROM kategori ORDER BY idkategori ASC");
                                        while ($row = $ambil->fetch_assoc()) {
                                        ?>
                                        <option value="<?= $row['idkategori']; ?>">
                                            <?= $row['namakategori']; ?>
                                        </option>
                                        <?php } ?>                            
                                    </select>
                                </td>
                                <td><a href="produk2.php?idproducts=<?= $tampilkan['idproducts']; ?>">Edit</a></td>
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
                    </select>
                    <br>
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button> 
                </div>
            </form>
            <?php
            // Cek apakah tombol simpan ditekan
            if (isset($_POST["simpan"])) {
                $id         = $_POST["id"];
                $opsi       = $_POST["opsi"];
                $stock      = $_POST["stock"]; // Pastikan nama input sesuai dengan yang digunakan
                $idkategori = $_POST["idkategori"];
                $jumlah_dipilih = count($id);

                // Cek opsi yang dipilih
                if ($opsi == "Publish") {
                    for ($x = 0; $x < $jumlah_dipilih; $x++) {
                        $stmt = $koneksi->prepare("UPDATE variants SET status = 0, updated_at = NOW() WHERE id=?");
                        $stmt->bind_param("i", $id[$x]);
                        if ($stmt->execute()) {
                            echo "<script>alert('Produk berhasil dipublish');</script>";
                        } else {
                            echo "<script>alert('Produk gagal dipublish');</script>";
                        }
                        echo "<script>location='produk2.php?id=$publish';</script>";
                    }
                } elseif ($opsi == "Unpublish") {
                    for ($x = 0; $x < $jumlah_dipilih; $x++) {
                        $stmt = $koneksi->prepare("UPDATE variants SET status = 1, updated_at = NOW() WHERE id=?");
                        $stmt->bind_param("i", $id[$x]);
                        if ($stmt->execute()) {
                            echo "<script>alert('Produk berhasil diunpublish');</script>";
                        } else {
                            echo "<script>alert('Produk gagal diunpublish');</script>";
                        }
                        echo "<script>location='produk2.php?id=$publish';</script>";
                    }
                } elseif ($opsi == "Update Stock") {
                    foreach ($id as $updateid) {
                        $stock_value = $stock[$updateid];
                        $stmt = $koneksi->prepare("UPDATE variants SET stock = ?, updated_at = NOW() WHERE id=?");
                        $stmt->bind_param("ii", $stock_value, $updateid);
                        if ($stmt->execute()) {
                            echo "<script>alert('Produk berhasil diupdate stock');</script>";
                        } else {
                            echo "<script>alert('Produk gagal diupdate stock');</script>";
                        }
                        echo "<script>location='produk2.php?id=$publish';</script>";
                    }
                } elseif ($opsi == "Update Kategori Diskon") {
                    foreach ($id as $updateid) {
                        $kategori_value = $idkategori[$updateid];
                        $stmt = $koneksi->prepare("UPDATE products p INNER JOIN variants v ON p.id = v.id SET p.idkategori = ?, updated_at = NOW() WHERE v.id=?");
                        $stmt->bind_param("ii", $kategori_value, $updateid);
                        if ($stmt->execute()) {
                            echo "<script>alert('Produk berhasil diupdate kategori diskon');</script>";
                        } else {
                            echo "<script>alert('Produk gagal diupdate kategori diskon');</script>";
                        }
                        echo "<script>location='produk2.php?id=$publish';</script>";
                    }
                }
            }
            ?>
            </div>
            <!-- CONTENT END -->
        </div>
    </div>
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
                        $('input[name="id[]"]').prop('checked',true);
                    }else{
                        $('input[name="id[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="id[]"]').click(function(){
                    var total_checkboxes = $('input[name="id[]"]').length;
                    var total_checkboxes_checked = $('input[name="id[]"]:checked').length;

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