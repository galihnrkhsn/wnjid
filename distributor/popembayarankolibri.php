<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $grandtotal     = $_GET['total']; 
    $idpoproduk     = $_GET['id'];
    $invoice        = $_GET["invoice"];
    $idadmin        = $_SESSION["idadmin"];

    $ambil          = $koneksi->query("SELECT count(*) AS bank FROM rekeningku where idadmin = '$idadmin'");
    $bank           = $ambil->fetch_assoc();

    $findUser       = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = '$idadmin'");
    $queryUser      = $findUser->fetch_assoc();

    $sqlpo          = $koneksi->query("SELECT namapo FROM poproduk 
                                        INNER JOIN bukapo ON bukapo.idpoproduk = poproduk.idpoproduk 
                                        WHERE poproduk.idpoproduk = '$idpoproduk'
                                    ");
    $po             = $sqlpo->fetch_assoc();
    $namapo         = $po['namapo'];

    $findOrder      = $koneksi->query("SELECT * FROM pomitra WHERE invoice = '$invoice'");
    $queryOrder     = $findOrder->fetch_assoc();
    $status         = $queryOrder['status'];
    var_dump($status);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Wanoja | <?= $queryUser['namamitra'] ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head> 
<body>
    <nav class="navbar bg-body-tertiary py-3">
        <div class="container-fluid row">
            <div class="col-2 text-center">
                <a class="navbar-brand" href="datapokolibri3.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>"><i class="bi bi-chevron-left"></i></a>
            </div>

            <div class="col-8 text-center">
                <h4 class="p-0 m-0 fw-semibold text-uppercase">Pembayaran <?= $namapo ?></h4>
            </div>

            <div class="col-2"></div>
        </div>
    </nav>

    <div class="container mt-3">
        <h5 class="fw-normal text-center">Nama Mitra: <?= $queryUser['namamitra'] ?> (<?= $idadmin ?>)</h5>
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <div class="col-md-5">
                        <div class="form-group mb-2">
                            <label for="invoice" class="form-label mb-1">Invoice</label>
                            <input type="text" name="invoice" class="form-control form-control-sm bg-secondary bg-opacity-10 pe-none" id="invoice" value="<?= $invoice ?> | Rp. <?= number_format($grandtotal) ?>" readonly>
                        </div>
                        <div class="form-group mb-2">
                            <label for="nama-bank" class="form-label mb-1">Nama Bank <span class="text-danger">*</span></label>
                            <?php if ($bank['bank'] == 0 ): ?>
                                <input type="text" name="bank" class="form-control form-control-sm" id="nama-bank" required>
                            <?php else : ?>
                                <select name="bank" class="form-control form-control-sm" id="nama-bank">
                                    <?php
                                        $sqlbank = $koneksi->query("SELECT * FROM rekeningku WHERE idadmin = '$idadmin'");
                                        while ($distributor = $sqlbank->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $distributor['bank'] ?>"><?= $distributor['bank'] ?></option>
                                    <?php } ?>
                                </select>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-2">
                            <label for="nomor-rek" class="form-label mb-1">Nama / Nomor Rekening Pengirim <span class="text-danger">*</span></label>
                            <?php if ($bank['bank'] == 0 ): ?>
                                <input type="text" name="rekening" class="form-control form-control-sm" id="nomor-rek" required>
                            <?php else : ?>
                                <select name="rekening" class="form-control form-control-sm" id="nomor-rek">
                                    <?php
                                        $sqlbank = $koneksi->query("SELECT * FROM rekeningku WHERE idadmin = '$idadmin'");
                                        while ($distributor = $sqlbank->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $distributor['namapemilik'] . " " . $distributor['rekening'] ?>"><?= $distributor['namapemilik'] ?> <?= $distributor['rekening'] ?></option>
                                    <?php } ?>
                                </select>
                            <?php endif; ?>
                        </div>
                        <hr />
                        <div class="form-group mb-2">
                            <label for="jumlah-transfer" class="form-label mb-1">Jumlah Transfer</label>
                            <input type="text" name="jumlah_transfer" class="form-control form-control-sm bg-secondary bg-opacity-10 pe-none" id="jumlah-transfer" value="Rp. <?= number_format(num: $grandtotal) ?>" readonly>
                        </div>
                        <div class="form-group mb-2">
                            <label for="jenis-pembayaran" class="form-label mb-1">Jenis Pembayaran</label>
                            <?php if ($status == "Belum DP") : ?>
                                <input type="text" name="jenis_pembayaran" class="form-control form-control-sm bg-secondary bg-opacity-10 pe-none" id="jenis-pembayaran" value="Payment DP 1 (40%)" readonly>
                            <?php elseif ($status == "Confirm Payment DP 1") : ?>
                                <input type="text" name="jenis_pembayaran" class="form-control form-control-sm bg-secondary bg-opacity-10 pe-none" id="jenis-pembayaran" value="Payment DP 2 (40%)" readonly>
                            <?php elseif ($status == "Confirm Payment DP 2") : ?>
                                <input type="text" name="jenis_pembayaran" class="form-control form-control-sm bg-secondary bg-opacity-10 pe-none" id="jenis-pembayaran" value="Pelunasan (20%)" readonly>
                            <?php endif; ?>
                        </div>
                        <div class="form-group mb-2">
                            <label for="payment" class="form-label mb-1">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="payment" class="form-control form-control-sm" id="payment" required>
                                <option selected disabled>~ Pilih Rekening Pembayaran ~</option>
                                <?php
                                    $queryrek = $koneksi->query("SELECT * FROM rekeningwnj WHERE status = 'A'");
                                    while($rekening = $queryrek->fetch_assoc()) {
                                ?>
                                    <option><?= $rekening['namabank']; ?> <?= $rekening['norekening']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="buktitf" class="form-label mb-1">Bukti Transfer <span class="text-danger">*</span></label>
                            <input type="file" name="buktitf" class="form-control form-control-sm" id="buktitf" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" name="kirim">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PHP SYNTAK -->
    <?php
        if (isset($_POST['kirim'])) {
            try {
                // Set timezone PHP ke Indonesia (WIB)
                date_default_timezone_set('Asia/Jakarta'); // Untuk WIB

                // Dapatkan waktu sekarang
                $current_time       = date('Y-m-d H:i:s');
                $tanggal            = date('Y-m-d');
                $waktu              = date('H-i-s');
                $t                  = date('His');
                $d                  = date('Ymd');
                
                $bankpengirim       = htmlspecialchars($_POST['bank']);
                $rekeningpengirim   = htmlspecialchars($_POST['rekening']);
                $jmlh_transfer      = htmlspecialchars($_POST['jumlah_transfer']);
                $jmlh_transfer      = str_replace(['Rp. ', '.', ','], '', $jmlh_transfer);
                $jmlh_transfer      = (int)$jmlh_transfer;
                $metodebayar        = htmlspecialchars($_POST['payment']);
                $ket                = $bankpengirim . ' - ' . $rekeningpengirim . ' - ' . $metodebayar . ' - ' . $jmlh_transfer;
                $foto               = $_FILES['buktitf']['name'];
                $tmp                = $_FILES['buktitf']['tmp_name'];
                $size               = $_FILES['buktitf']['size'];

                if ($foto <> '') {
                    $ekstensiGambarValid    = ['jpg','jpeg','png','svg'];
                    $ekstensiGambar         = explode('.', $foto);
                    $ekstensiGambar         = strtolower(end($ekstensiGambar));
                    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                        echo "<script>alert('Yang anda upload bukan gambar');</script>";
                        echo "<script>location='popembayarankolibri.php?id=$idpoproduk&invoice=$invoice';</script>";
                        return false;
                    }

                    $namaFileBaru = 'D' . $idadmin . $idpoproduk . $d . $t;
                    $namaFileBaru .= '.';
                    $namaFileBaru .= $ekstensiGambar;  
                }
                if (move_uploaded_file($tmp, 'bukti/' . $namaFileBaru)) {
                    try {                            
                        if ($status == "Belum DP") {
                            $jenis_payment          = "Payment 1";
                            $status_pembayaran      = "Sudah Confirm Payment 1";
                        } elseif ($status == "Sudah Confirm Payment 1") {
                            $jenis_payment          = "Payment 2";
                            $status_pembayaran      = "Sudah Confirm Payment 2";
                        } elseif ($status == "Sudah Confirm Payment 2") {
                            $jenis_payment          = "Lunas";
                            $status_pembayaran      = "Pelunasan";
                        }

                        $koneksi->query("INSERT INTO buktitf VALUES
                                            (
                                                NULL, '$invoice', '$namaFileBaru',
                                                '$jenis_payment', '$ket', '$current_time'
                                            )
                                        ");

                        $sqlins = $koneksi->query("INSERT INTO popembayaran VALUES
                                                    (
                                                        NULL, '$idpoproduk', '$invoice',
                                                        '$bankpengirim', '$rekeningpengirim', '$jmlh_transfer',
                                                        NULL, NULL, NULL,
                                                        '$metodebayar', '$status_pembayaran', 'PO Kolibri Idul Adha',
                                                        '$tanggal', '$waktu', '$current_time'
                                                    )
                                                ");
                        if ($sqlins) {
                            $updstatus = $koneksi->query("UPDATE pomitra SET status = '$status_pembayaran' WHERE invoice = '$invoice'");
                            echo "
                                <script>
                                    alert('Pembayaran sudah berhasil!')
                                    location='datapokolibri3.php?id=$idpoproduk&invoice=$invoice'
                                </script>
                            ";
                        } else {
                            echo "
                                <script>
                                    alert('Pembayaran gagal!')
                                    location='popembayarankolibri.php?id=$idpoproduk&invoice=$invoice'
                                </script>
                            ";
                        }
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "
                        <script>
                            alert('Gagal konfirmasi pembayaran! Terjadi kesalahan')
                            location='popembayarankolibri.php?id=$idpoproduk&invoice=$invoice'
                        </script>
                    ";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>
    <!-- PHP SYNTAK END -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>