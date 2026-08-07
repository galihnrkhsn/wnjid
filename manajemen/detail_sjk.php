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
    $idvendor       = $datapo['vendor'];

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
<div class="container my-4">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="text-secondary fw-bold">Detail SJK</h2>
        <a href="vendor.php?id=<?= $datapo['idpoproduk'] ?>&vendor=<?= $idvendor; ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Detail Info -->
    <div class="card mb-4">
        <div class="card-body">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="fw-bold">SJK</td>
                        <td>:</td>
                        <td><?= $sjk; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Vendor</td>
                        <td>:</td>
                        <td><?= $vendor; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">PO</td>
                        <td>:</td>
                        <td><?= $namapo; ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Image -->
            <div class="text-center my-3">
                <img src="buktivendor/<?= $datapo['image'] ?>" alt="Bukti Vendor" class="img-fluid rounded" style="max-width: 500px;">
            </div>

            <!-- Date Info -->
            <p class="text-muted text-center" style="font-size: 0.85rem;">
                Dibuat pada: 
                <?php
                    $datetime = new DateTime($created_at, new DateTimeZone('UTC'));
                    $datetime->setTimezone(new DateTimeZone('Asia/Jakarta'));
                    $formatted_date = $datetime->format('l, d F Y');
                    echo $formatted_date;
                ?>
            </p>
        </div>
    </div>

    <!-- Detail Table -->
    <div>
        <a href="ubah_sjk.php?id=<?= $sjk ?>" class="btn btn-primary mb-3">
            Ubah SJK
        </a>
        <a href="kekurangan_sjk.php?id=<?= $sjk ?>" class="btn btn-success mb-3">
            Tambah Variant SJK
        </a>
    </div>
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Daftar Produk</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-sm">
                <thead class="table-secondary text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>QTY</th>
                        <th>Diupdate Pada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                        $no = 1;
                        $ambil = $koneksi->query("SELECT sjk.*, sjk.jumlah, podetail.variant
                                                  FROM sjk
                                                  JOIN podetail on podetail.idpodetail = sjk.idpodetail
                                                  WHERE sjk.sjk = '$sjk'");
                        while ($tampil = $ambil->fetch_assoc()) {
                            $id = $tampil['idsjk'];
                    ?>
                        <tr class="text-center">
                            <td><?= $no++; ?></td>
                            <td><?= $tampil['variant']; ?></td>
                            <td><?= $tampil['jumlah']; ?></td>
                            <td>
                                <?= $tampil['waktu_update'] == NULL ? $tampil['tanggal'] : $tampil['waktu_update']; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>