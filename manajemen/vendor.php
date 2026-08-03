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
    $idpoproduk = $_GET['id'];
    $vendor     = $_GET['vendor'];

    $query      = $koneksi->query("SELECT * FROM user_manajemen INNER JOIN role WHERE user_manajemen.id = '$id'");
    $user       = $query->fetch_assoc();
    $username   = $user['username'];

    $sql_vendor     = "SELECT * FROM vendor WHERE id = '$vendor' ";
    $query_vendor   = $koneksi->query($sql_vendor);
    $data_vendor    = $query_vendor->fetch_assoc();  
    $namavendor     = $data_vendor['vendor'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wanoja | <?= $username ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7ie2TVuGdxH5tk36y3PBo/zKMpQ2dtnb9Xg" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-RdQdUI6YgeTYOPzN/v53qu3Zk5S8dHf73GAdEP+EeFwkw4FEcGITeJEmG70T1NrM" crossorigin="anonymous"></script>
</head>
<body>
        <!--**********************************
            Nav header start
        ***********************************-->
        <?php include "assets/components/Navbar/navbar.php"; ?>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <?php if (isset($vendor)) : ?>
            <div class="container my-4">
                <!-- CONTENT HEAD -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="fw-bold text-secondary">Vendor <?= $namavendor; ?></h2>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <!-- CONTENT ROW -->
                <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <form method="post" action="print_sjk.php" target="_blank()">
                        <table class="table table-bordered table-striped" id="tb_vendor">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th><input type='checkbox' id='checkAll'></th>
                                    <th>No SJK</th>
                                    <th>Nama PO</th>
                                    <th>Jumlah</th>
                                    <th>Bukti SJ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  
                                $no = 1;
                                $ambil = $koneksi->query("SELECT sjk.sjk,
                                                                    SUM(sjk.jumlah) AS jumlah,
                                                                    poproduk.namapo,
                                                                    MAX(sjk.image) AS image
                                                            FROM sjk
                                                            INNER JOIN poproduk ON poproduk.idpoproduk=sjk.idpoproduk
                                                            WHERE sjk.idpoproduk = '$idpoproduk' AND sjk.vendor ='$vendor'
                                                            GROUP BY sjk.sjk, poproduk.namapo
                                                        "); 
                                while($tampil = $ambil->fetch_assoc()){
                                    $id = $tampil['sjk'];                  
                                ?>      
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><input type='checkbox' name='update[]' value='<?= $id ?>' ></td>
                                        <td><a href="detail_sjk?id=<?= $tampil['sjk']; ?>" class="custom-text-color"><?= $tampil['sjk']; ?></a></td>
                                        <td><?= $tampil['namapo']; ?></td>
                                        <td><?= $tampil['jumlah']; ?></td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal<?= $no; ?>">
                                                <img src="buktivendor/<?= $tampil['image'] ?>" class="img-thumbnail" style="max-width: 50px;">
                                            </a>

                                            <!-- Modal -->
                                            <div class="modal fade" id="imageModal<?= $no; ?>" tabindex="-1" aria-labelledby="imageModalLabel<?= $no; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="imageModalLabel<?= $no; ?>">Bukti SJ</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="buktivendor/<?= $tampil['image'] ?>" class="img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>          
                            </tbody>
                        </table>
                        <input type='submit' class="btn btn-primary" value='Print' name='but_print'>
                        <button type="submit" class="btn btn-danger" name="but_hapus" onclick="return confirm('Yakin Akan Menghapus SJK?');">Hapus</button>
                    </form>
                </div>  
            </div>
                <!-- CONTENT ROW END -->
            </div>
        <?php else : ?>
        <div class="container my-4">
            <a href="index.php" class="btn btn-outline-secondary mb-5">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <!-- Tambah Vendor -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Tambah Vendor</h6>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Vendor</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama vendor" required>
                        </div>
                        <button class="btn btn-primary w-100" name="kirim">Kirim</button>
                    </form>
                </div>
            </div>
            <!-- Data Vendor -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">Data Vendor</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Vendor</th>
                                    <th class="text-center" width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = $koneksi->query("SELECT * FROM vendor");
                                    $no = 1;
                                    while ($data = $sql->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data['vendor'] ?></td>
                                        <td class="text-center">
                                            <form method="post" enctype="multipart/form-data" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                <button class="btn btn-danger btn-sm" name="delete" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Bootstrap JS CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#">WNJ.ID</a> 2024</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

    <?php
        if (isset($_POST['kirim'])) {
            try {
                $name = $_POST['name'];
                
                $sql = $koneksi->query("INSERT INTO vendor VALUES (NULL, '$name')");
                if ($sql) {
                    echo "
                        <script>
                            alert('Data vendor berhasil ditambahkan!')
                            location='vendor.php'
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            alert('Data vendor gagal ditambahkan!')
                            location='vendor.php'
                        </script>
                    ";
                }
            } catch(Exception $e) {
                echo "
                    <script>
                        alert('Data vendor gagal ditambahkan!')
                        location='vendor.php'
                    </script>
                ";
            }
        } elseif (isset($_POST['delete'])) {
            try {
                $id = $_POST['id'];
                $sql = $koneksi->query("DELETE FROM vendor WHERE id = '$id'");
                if ($sql) {
                    echo "
                        <script>
                            alert('Data vendor berhasil dihapus!')
                            location='vendor.php'
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            alert('Data vendor gagal dihapus!')
                            location='vendor.php'
                        </script>
                    ";
                }
            } catch(Exception $e) {
                echo "
                    <script>
                        alert('Data vendor gagal dihapus!')
                        location='vendor.php'
                    </script>
                ";
            }
        }
    ?>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
</body>
</html>