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

    $queryManage    = $koneksi->query("SELECT *, role.id as id_role FROM user_manajemen INNER JOIN role ON user_manajemen.id_role = role.id WHERE user_manajemen.id = '$id'");
    $data           = $queryManage->fetch_assoc();
    $iduser         = $data['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Distributor | Wanoja</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>

</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <!-- TABLE -->
        <div class="col mt-2">
            
        </div>
        <!-- TABLE END -->
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
        <!-- UPDATE -->
        <?php 
            if (isset($_POST["kirimubah"])) {
                date_default_timezone_set('Asia/Jakarta');
                $waktu      = date('H:i:s');
                $idrk       = $_POST["idrk"];
                $tanggal    = $_POST["tanggal"];
                $keterangan = addslashes(htmlspecialchars($_POST["keterangan"]));
                $kategori   = $_POST['kategori'];
                $kredit     = $_POST["kredit"];
                $debit      = $_POST["debit"];
                $sisa       = $distributor2['sisa'] + $kredit - $debit;
                
                $foto       = $_FILES['foto']['name'];
                $tmp        = $_FILES['foto']['tmp_name'];
                $ukuranFile = $_FILES['foto']['size'];
                
                if ($foto == "") {
                    $sql = $koneksi->query("UPDATE rekeningkoran SET tanggal = '$tanggal', waktu = '$waktu', keterangan = '$keterangan', kredit = '$kredit', debit = '$debit', sisasaldo = '$sisa', kategori_id = '$kategori' WHERE idrk = '$idrk'");
                    if ($sql) {
                        echo "<script>alert('Transaksi ($keterangan) berhasil diubah');</script>";
                        echo "<script>location='finance.php?tipe=$tipe'</script>";
                    } else {
                        echo "<script>alert('Transaksi ($keterangan) gagal diubah');</script>";
                        echo "<script>location='finance.php?tipe=$tipe'</script>";
                    }
                    return false;
                }
                
                $ekstensiGambarValid    = ['jpg', 'jpeg', 'png', 'svg'];
                $ekstensiGambar         = explode('.', $foto);
                $ekstensiGambar         = strtolower(end($ekstensiGambar));
                if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                    echo "<script>alert('Yang anda upload bukan gambar');</script>";
                    echo "<script>location='finance.php?tipe=$tipe'</script>";
                    return false;
                }            
                
                $today          = date("His"); 
                $tglsekarang    = date("ymd");
                $namadepan      = $kredit == 0 ? 'D' . $tipe : 'K' . $tipe;
                $namaFileBaru   = $namadepan . $tglsekarang . $today . '.' . $ekstensiGambar;

                if (move_uploaded_file($tmp, 'buktitransfer/' . $namaFileBaru)) {
                    $sql = $koneksi->query("UPDATE rekeningkoran SET tanggal='$tanggal', waktu='$waktu', keterangan='$keterangan', kredit='$kredit', debit='$debit', sisasaldo='$sisa', buktitf='$namaFileBaru' WHERE idrk='$idrk'");
                }
                if ($sql) {
                    echo "<script>alert('Transaksi ($keterangan) berhasil diubah');</script>";
                    echo "<script>location='finance.php?tipe=$tipe'</script>";
                } else {
                    echo "<script>alert('Transaksi ($keterangan) gagal diubah');</script>";
                    echo "<script>location='finance.php?tipe=$tipe'</script>";
                }
            }
        ?>
        <!-- STORE DEBIT -->
        <?php 
            if (isset($_POST["kirimdebit"])) {
                date_default_timezone_set('Asia/Jakarta');
                $waktu          = date('H:i:s');
                $tanggal        = $_POST["tanggal"];
                $keterangan     = addslashes(htmlspecialchars($_POST["keterangan"]));
                $kategori       = $_POST['kategori'];
                $debit          = $_POST["debit"];
                $sisa           = $distributor2['sisa'] - $debit;

                $foto           = $_FILES['foto']['name'];
                $tmp            = $_FILES['foto']['tmp_name'];
                $ukuranFile     = $_FILES['foto']['size'];

                // Cek apakah yang diupload adalah gambar
                $ekstensiGambarValid    = ['jpg', 'jpeg', 'png', 'svg'];
                $ekstensiGambar         = explode('.', $foto);
                $ekstensiGambar         = strtolower(end($ekstensiGambar));
                if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                    echo "<script>alert('Yang anda upload bukan gambar');</script>";
                    echo "<script>location='finance.php?tipe=$tipe'</script>";
                    return false;
                }

                $today          = date("His"); 
                $tglsekarang    = date("ymd");
                $namaFileBaru   = 'D' . $tipe . $tglsekarang . $today . '.' . $ekstensiGambar;

                if (move_uploaded_file($tmp, 'buktitransfer/' . $namaFileBaru)) {
                    $koneksi->query("INSERT INTO rekeningkoran (idrk, iduser, tanggal, waktu, keterangan, kredit, debit, sisasaldo, buktitf, tipe, kategori_id) VALUES (null, '$iduser', '$tanggal', '$waktu', '$keterangan', '0', '$debit', '$sisa', '$namaFileBaru', '$tipe', '$kategori')");
                    echo "<script>alert('Debit ($keterangan) berhasil ditambahkan');</script>";
                    echo "<script>location='finance.php?tipe=$tipe'</script>";
                }
            }
        ?>         
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include "assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#tb_finance').DataTable({
                "pageLength": 5,
                "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                order: [[0, 'desc']],
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#skill_dropdown").change(function () {
                var inputVal = $(this).val();
                var eleBox = $("." + inputVal);
                $(".skill").hide();
                $(eleBox).show();
            });
        });
    </script>
    <!-- END SCRIPT -->
</body>
</html>