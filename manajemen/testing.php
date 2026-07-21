<?php
    session_start();
    include "koneksi.php";
    include "assets/components/Sessions/sesManage.php";

    $idmanage = $_SESSION["idmanage"];
    $tipe = $_SESSION["user_tipe"];

    $queryManage = $koneksi->query("SELECT * FROM management WHERE id='$idmanage'");
    $data = $queryManage->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Finance | Wanoja</title>
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
    <form method="POST" enctype="multipart/form-data">
        <div class="modal-body">
            <p class="statusMsg"></p>
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required />
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required />
            </div>
            <div class="form-group">
                <label for="kredit">Kredit</label>
                <input type="number" min="0" class="form-control" id="kredit" name="kredit" placeholder="Masukkan Total Kredit" required />
            </div>
            <div class="form-group">
                <label for="foto">Nota</label>
                <br>
                <input type="file" name="foto" id="foto" required />
            </div>
        </div>
        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
            <button type="submit" class="btn btn-primary" name="kirimkredit">KIRIM</button>
        </div>
    </form>

    <?php 
if (isset($_POST["kirimkredit"])) {
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date('H:i:s');
    $tanggal = $_POST["tanggal"];
    $keterangan = addslashes(htmlspecialchars($_POST["keterangan"]));
    $kredit = $_POST["kredit"];
    $sisa = $distributor2['sisa'] + $kredit;

    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $ukuranFile = $_FILES['foto']['size'];
    $errorFile = $_FILES['foto']['error'];

    // Debugging information
    echo "Error code: " . $errorFile . "<br>";
    echo "File size: " . $ukuranFile . "<br>";
    echo "Temp file location: " . $tmp . "<br>";

    if ($errorFile !== UPLOAD_ERR_OK) {
        echo "<script>alert('Gagal mengupload file dengan error code: $errorFile');</script>";
        exit;
    }

    // Cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'svg'];
    $ekstensiGambar = explode('.', $foto);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('Yang anda upload bukan gambar');</script>";
        echo "<script>location='finance.php?tipe=$tipe'</script>";
        exit;
    }

    // Optional: Cek ukuran file
    $ukuranMaksimal = 2 * 1024 * 1024; // 2MB
    if ($ukuranFile > $ukuranMaksimal) {
        echo "<script>alert('Ukuran file terlalu besar');</script>";
        echo "<script>location='finance.php?tipe=$tipe'</script>";
        exit;
    }

    $today = date("His"); 
    $tglsekarang = date("ymd");
    $namaFileBaru = 'K' . $tipe . $tglsekarang . $today . '.' . $ekstensiGambar;

    // Ensure directory exists and is writable
    $targetDir = __DIR__ . '/buktitransfer/' . $namaFileBaru;

    echo $tmp . " | " . $targetDir . " | <br>" . $namaFileBaru;

    if (move_uploaded_file($tmp, $targetDir)) {
        echo "File succes moved";
    } else {
        echo "File cannot moved";
    }

    // // Move the uploaded file
    // if (move_uploaded_file($tmp, $targetDir . $namaFileBaru)) {
    //     $stmt = $koneksi->prepare("INSERT INTO rekeningkoran (idrk, iduser, tanggal, waktu, keterangan, kredit, debit, sisasaldo, buktitf, tipe) VALUES (null, ?, ?, ?, ?, ?, '0', ?, ?, ?)");
    //     $stmt->bind_param('ssssssss', $idmanage, $tanggal, $waktu, $keterangan, $kredit, $sisa, $namaFileBaru, $tipe);
    //     $stmt->execute();
    //     if ($stmt->affected_rows > 0) {
    //         echo "<script>alert('Kredit ($keterangan) berhasil ditambahkan');</script>";
    //         echo "<script>location='finance.php?tipe=$tipe'</script>";
    //     } else {
    //         echo "<script>alert('Data gagal ditambahkan');</script>";
    //     }
    //     $stmt->close();
    // } else {
    //     // Error logging
    //     error_log('Failed to move uploaded file from ' . $tmp . ' to ' . $targetDir . $namaFileBaru);
    //     echo "<script>alert('Gagal mengupload file');</script>";
    // }
}
?>
</body>
</html>