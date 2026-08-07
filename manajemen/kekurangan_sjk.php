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

    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];
    $sjk            = $_GET['id'];

    $query          = $koneksi->query("SELECT * FROM user_manajemen INNER JOIN role WHERE user_manajemen.id     = '$id'");
    $user           = $query->fetch_assoc();
    $username       = $user['username'];

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
    $idpoproduk     = $datapo['idpoproduk'];
    $idvendor       = $datapo['vendor'];
    $no_sjk         = $datapo['no_sjk'];
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-secondary">Tambah Kekurangan SJK</h2>
        <a href="detail_sjk?id=<?= $sjk; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form method="POST" class="p-3 border rounded bg-light">
        <?php
            $sql    = "SELECT podetail.idpo, podetail.idpodetail, podetail.variant, podetail.harga 
                        FROM podetail
                        JOIN pokategori ON pokategori.idpo = podetail.idpo
                        WHERE pokategori.idpoproduk = '$idpoproduk'
                        ORDER BY podetail.variant ASC";
            $query  = $koneksi->query($sql);

            while ($row = $query->fetch_assoc()) {
                $query_detail   = "SELECT sjk.idpodetail FROM sjk WHERE sjk.sjk='$sjk' AND sjk.idpodetail='$row[idpodetail]'";
                $sqlpo_detail   = mysqli_query($koneksi, $query_detail);
                $datapo_detail  = mysqli_fetch_array($sqlpo_detail);
                $idpodetail     = $datapo_detail['idpodetail'];
        ?>
            <?php if ($idpodetail == "") : ?>
                <div class="mb-3">
                    <label class="form-label"><?= $row['variant']; ?></label>
                    <input type="hidden" name="idpodetail[]" value="<?= $row['idpodetail']; ?>">
                    <input type="number" name="jmlh[]" min="0" class="form-control" placeholder="Masukkan jumlah">
                </div>
            <?php endif; ?>
        <?php } ?>
        <button type="submit" class="btn btn-primary w-100" name="save">Kirim</button>
    </form>
</div>
<?php
    if(isset($_POST["save"])){
        error_reporting(E_ALL);
        ini_set('error_reporting', 1);
        date_default_timezone_set('Asia/Jakarta');

        $waktu          = date("Y-m-d H:i:s");
        $idpodetail     = $_POST["idpodetail"];
        $jmlh           = $_POST["jmlh"];                           
        $jumlah_dipilih = count($idpodetail);
        
        for($x = 0; $x < $jumlah_dipilih; $x++){

            if (empty($jmlh[$x]) || $jmlh[$x] <= 0) {
                continue;
            }

            $query_variant  = "SELECT podetail.harga, podetail.idpo
                                FROM podetail
                                WHERE podetail.idpodetail = '{$idpodetail[$x]}'";
            $sql_variant    = mysqli_query($koneksi, $query_variant);  
            $data_variant   = mysqli_fetch_array($sql_variant);

            $harga          = $data_variant['harga'];
            $idpo           = $data_variant['idpo'];  
            $total          = $jmlh[$x]*$harga;
            if ($jmlh[$x] > 0) {
                $sql        = $koneksi->query("INSERT INTO sjk 
                                                    (
                                                        idsjk, no_sjk, sjk, tanggal, vendor, idpoproduk, 
                                                        idpodetail, jumlah, waktu, waktu_update
                                                    ) 
                                                VALUES 
                                                    (
                                                        null, '$no_sjk', '$sjk', '$tanggal', '$idvendor', 
                                                        '$idpoproduk', '$idpodetail[$x]', '$jmlh[$x]', '$waktu', NOW()
                                                    )"
                                            );
                if ($sql) {
                    echo "<script>alert('data berhasil dikirim');</script>";
                    echo "<script>location='detail_sjk?id=$sjk';</script>";
        
                }
            }  
        }                                    
    }
?>
</body>
</html>