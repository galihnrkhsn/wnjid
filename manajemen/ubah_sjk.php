<?php
    session_start();

    include 'koneksi.php';

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }

    $id         = $_SESSION['user_id'];
    $role       = $_SESSION['user_level'];
    $sjk        = $_GET['id'];

    $query      = $koneksi->query("SELECT * FROM user_manajemen INNER JOIN role WHERE user_manajemen.id = '$id'");
    $user       = $query->fetch_assoc();
    $username   = $user['username'];

    $sqlpo          = "SELECT poproduk.namapo, vendor.vendor AS nama_vendor, sjk.*
                        FROM poproduk
                        JOIN sjk on sjk.idpoproduk = poproduk.idpoproduk
                        JOIN vendor on vendor.id = sjk.vendor
                        WHERE sjk.sjk = '$sjk'";
    $querypo        = $koneksi->query($sqlpo);
    $datapo         = $querypo->fetch_assoc();  
    $created_at     = $datapo['waktu'];
    $namapo         = $datapo['namapo'];
    $vendor         = $datapo['nama_vendor'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WNJ.ID | <?= $username ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7ie2TVuGdxH5tk36y3PBo/zKMpQ2dtnb9Xg" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-RdQdUI6YgeTYOPzN/v53qu3Zk5S8dHf73GAdEP+EeFwkw4FEcGITeJEmG70T1NrM" crossorigin="anonymous"></script>
</head>
<body>
<?php include "assets/components/Navbar/navbar.php"; ?>
<div class="container mt-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-secondary">Ubah SJK</h2>
        <a href="detail_sjk?id=<?= $sjk; ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Details Section -->
    <div class="mb-3">
        <table class="table table-borderless">
            <tr>
                <td><strong>SJK</strong></td>
                <td>:</td>
                <td><?= $sjk; ?></td>
            </tr>
            <tr>
                <td><strong>Vendor</strong></td>
                <td>:</td>
                <td><?= $vendor; ?></td>
            </tr>
            <tr>
                <td><strong>PO</strong></td>
                <td>:</td>
                <td><?= $namapo; ?></td>
            </tr>
        </table>
    </div>

    <!-- Form Section -->
    <form method="post">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>Nama Produk</th>
                        <th>QTY</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                    $no = 1;
                    $ambil = $koneksi->query("SELECT sjk.sjk, sjk.idsjk, sjk.jumlah, podetail.variant
                        FROM sjk
                        JOIN podetail ON podetail.idpodetail = sjk.idpodetail
                        WHERE sjk.sjk = '$sjk'"); 
                    while ($tampil = $ambil->fetch_assoc()) {
                        $id = $tampil['idsjk'];                  
                    ?>
                    <tr>
                        <td><input type="checkbox" name="update[]" value="<?= $id ?>"></td>
                        <td><?= $tampil['variant']; ?></td>
                        <td><?= $tampil['jumlah']; ?></td>
                        <td>
                            <input type="number" min="0" name="jumlah<?= $id ?>" class="form-control form-control-sm">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-success btn-sm" name="but_ubah">Ubah</button>
        </div>
    </form>
</div>

<?php 
    if(isset($_POST['but_ubah'])){
        $waktu = date("Y-m-d H:i:s");
        if(isset($_POST['update'])){
            foreach($_POST['update'] as $updateid){   

            $jumlah = $_POST['jumlah'.$updateid];      
            if ($jumlah<>'') {
                    $sqlnya = $koneksi->query("UPDATE sjk set jumlah='$jumlah', waktu_update = '$waktu'
                                                WHERE idsjk='$updateid'");                    
                        }         
                                                        
            }
            if ($sqlnya) {
                echo "<script>alert('data berhasil diubah');</script>";
                echo "<script>location='ubah_sjk?id=$sjk';</script>";  
                }else{
                echo "<script>alert('data gagal diubah');</script>";
                echo "<script>location='ubah_sjk?id=$sjk';</script>";
            }               
        }            
    }

?>

<script>
    // JavaScript for checkbox functionality
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('input[name="update[]"]');

        checkAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = checkAll.checked);
        });

        checkboxes.forEach(cb => cb.addEventListener('change', function () {
            checkAll.checked = Array.from(checkboxes).every(cb => cb.checked);
        }));
    });
</script>

</body>
</html>