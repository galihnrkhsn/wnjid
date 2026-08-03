<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $idadmin=$_SESSION["idadmin"];
    $invoice='D'.$idpoproduk.'-'.$idadmin;
    $query = "SELECT COUNT(*) as jumlah,
                poproduk.idpoproduk,
                poproduk.namapo,
                poproduk.status 
                FROM poproduk 
                inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                WHERE poproduk.idpoproduk='$idpoproduk' 
                AND pomitra.idmitra='$idadmin'
                AND pomitra.invoice = '$invoice'
                ";
    $sql = mysqli_query($koneksi, $query);  
    $data = mysqli_fetch_array($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <title>Distributor | Wanoja</title>
    <style>
        body {
            background-color: #f5f7fb;
        }
        .po-header {
            background: #f0f6ff;
            border-radius: 14px;
            padding: 22px 24px;
        }
        .po-header h4 {
            margin: 0;
            color: #2b2f42;
        }
        .po-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            padding: 24px;
        }
        .po-links a {
            border-radius: 8px;
            font-weight: 600;
        }
        .po-note {
            border-radius: 10px;
            font-size: .9rem;
        }
        .po-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 4px;
            border-bottom: 1px solid #eef1f5;
        }
        .po-item:last-of-type {
            border-bottom: none;
        }
        .po-item label {
            margin: 0;
            font-weight: 600;
            color: #2b2f42;
        }
        .qty-control {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .qty-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            background: #fff;
            font-weight: bold;
            line-height: 1;
        }
        .qty-btn:active {
            background: #f0f6ff;
        }
        .qty-input {
            width: 70px;
            text-align: center;
            border-radius: 8px;
        }
        .btn-submit {
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-4">
        <?php
            $namapo = $data['namapo'];
            $id = $data['idpoproduk'];
        ?>
        <div class="po-header text-center mb-4">
            <h4><i class="fa-solid fa-cart-shopping mr-2"></i><b>Formulir Pemesanan <?= $namapo ?></b></h4>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="po-card">
                    <form method="POST">
                        <?php
                            // Initialize total
                            $idpoproduk = $_GET['id'];
                            $total = 0;
                            $index = 0;
                            $sql = "SELECT * FROM poproduk
                                    INNER JOIN pokategori
                                    INNER JOIN podetail
                                    ON poproduk.idpoproduk=pokategori.idpoproduk
                                    AND pokategori.idpo=podetail.idpo
                                    WHERE poproduk.idpoproduk='$idpoproduk'
                                    AND podetail.variant NOT LIKE '%Custom%'
                                    AND podetail.idpo NOT IN (4413, 4382, 4351)
                                    ORDER BY podetail.idpodetail ASC";
                            $query = $koneksi->query($sql);

                            while($row = $query->fetch_assoc()){
                        ?>
                            <div class="po-item">
                                <label><?php echo $row['variant']; ?></label>
                                <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                                <div class="qty-control">
                                    <button type="button" class="qty-btn qty-minus">&minus;</button>
                                    <input type="number" min="0" name="jmlh[]" class="form-control qty-input" value="0" required>
                                    <button type="button" class="qty-btn qty-plus">&plus;</button>
                                </div>
                            </div>

                            <?php
                                $namapo2 = $row['namapo'];
                                $id = $row['idpoproduk'];
                                $idadmin = $_SESSION["idadmin"];
                            }

                            $sql_tgl = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'");
                            $query_tgl = $sql_tgl->fetch_assoc();
                            date_default_timezone_set('Asia/Jakarta');
                            $today = date("d M Y");
                        ?>
                        <?php
                            $sql = $koneksi->query("SELECT COUNT(*) AS total_inv, pomitra.* FROM pomitra WHERE idpoproduk = $idpoproduk AND idmitra = $idadmin");
                            $row = $sql->fetch_assoc();
                            if ($row['total_inv'] > 0) :
                        ?>
                            <div class="alert alert-success po-note mt-3 mb-0 text-center">
                                <i class="fa-solid fa-circle-check mr-1"></i> Pesanan kamu sudah terkirim.
                                <div class="mt-2">
                                    <?php if ($idpoproduk == '405' || $idpoproduk == '406' || $idpoproduk == '407') : ?>
                                        <a href="datapokolibri3.php?id=<?= $row['idpoproduk'] ?>&invoice=<?= $row['invoice'] ?>" class="btn btn-success btn-sm">Lihat Invoice</a>
                                    <?php else : ?>
                                        <a href="datapo.php?id=<?= $row['idpoproduk'] ?>&invoice=<?= $row['invoice'] ?>" class="btn btn-success btn-sm">Lihat Invoice</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-warning po-note mt-3 text-center">
                                <i class="fa-solid fa-circle-info mr-1"></i> Biarkan angka <strong>0</strong> pada varian yang tidak dipesan.
                            </div>
                            <button type='submit' class='btn btn-primary btn-block btn-submit' name='save'><i class="fa-solid fa-paper-plane mr-2"></i>Kirim Pesanan</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT END -->

    <script>
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('qty-plus') || e.target.classList.contains('qty-minus')) {
                var input = e.target.parentElement.querySelector('.qty-input');
                var value = parseInt(input.value, 10) || 0;
                if (e.target.classList.contains('qty-plus')) {
                    value += 1;
                } else if (value > 0) {
                    value -= 1;
                }
                input.value = value;
            }
        });
    </script>

    <br><br><br><br>

    <!-- PHP SYNTAK -->
    <?php
        if(isset($_POST["save"])){
            $checkQuery     = $koneksi->query("SELECT COUNT(*) as count 
                                                FROM pomitra 
                                                WHERE idmitra = '$idadmin' 
                                                AND idpoproduk = '$idpoproduk' 
                                                AND idmitraagen IS NULL 
                                                AND idmitrareseller IS NULL 
                                                AND idmitramarketer IS NULL");
            $exists         = $checkQuery->fetch_assoc()['count'];

            if ($exists > 0) {
                echo "<script>alert('Data sudah ada, tidak boleh mengirim ulang!');</script>";
                echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
                exit();
            }

            date_default_timezone_set('Asia/Jakarta');
            $today          = date("s");
            $waktu          = date("H:i:s");
            $idpodetail     = $_POST["idpodetail"];
            $jmlh           = $_POST["jmlh"];
            $jumlah_dipilih = count($jmlh);
            $totalQty       = array_sum($jmlh);
            if ($idpoproduk == '486' && $totalQty > 2) {
                echo "<script>alert('Total Qty tidak boleh lebih dari 2!');</script>";
                echo "<script>location='formpoku.php?id=$idpoproduk';</script>";
                exit();
            } elseif ($idpoproduk == 545) {
                $total = array_sum($jmlh);
                if ($total % 2 != 0) {
                    echo "<script>alert('Total Qty Harus kelipatan 2!');</script>";
                    echo "<script>location='formpoku.php?id=$idpoproduk';</script>";
                    exit();
                }
            }

            if (isset($_GET['invoice'])) {
                $invoice = $_GET['invoice'];
            } else {  
                // Fungsi untuk menghasilkan angka acak dengan panjang tertentu
                function generateAngkaAcak($length) {
                    $angka_acak = '';
                    for ($i = 0; $i < $length; $i++) {
                    // Menggunakan mt_rand untuk angka acak dari 0 hingga 9
                    $angka_acak .= mt_rand(0, 9);
                    }
                    return $angka_acak;
                }
                // Menghasilkan angka acak dengan panjang minimal 7 dan maksimal 7 angka
                $angka_acak = generateAngkaAcak(5);
                $invoice = 'D' . $idpoproduk . '-' . $idadmin . $angka_acak;
            }

            for($x = 0; $x < $jumlah_dipilih; $x++){
                $processed = true;
                $sql        = "SELECT * FROM podetail WHERE idpodetail = '$idpodetail[$x]'";
                $query      = $koneksi->query($sql);
                $detail     = $query->fetch_assoc();
                $harga      = $detail['harga'];
                $idpo       = $detail['idpo'];
                $total      = $jmlh[$x] * $harga;

                $koneksi->query("INSERT INTO pomitra (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, total, invoice, status, tgl, waktu) VALUES
                (null, '$idadmin', '$idpoproduk', '$idpo', '$idpodetail[$x]', '$jmlh[$x]', '$total', '$invoice', 'Belum DP', NOW(), '$waktu')");
            }
            if ($processed) {
                if($idpoproduk == '271') {
                    echo "<script>alert('data berhasil dikirim');</script>";
                    echo "<script>location='datapom3.php?id=$idpoproduk&invoice=$invoice';</script>";
                } elseif ($idpoproduk == '405' || $idpoproduk == '406' || $idpoproduk == '407') {
                    echo "<script>alert('data berhasil dikirim');</script>";
                    echo "<script>location='datapokolibri3.php?id=$idpoproduk&invoice=$invoice';</script>";
                } else {
                    echo "<script>alert('data berhasil dikirim');</script>";
                    echo "<script>location='datapo.php?id=$idpoproduk&invoice=$invoice';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Tidak ada item yang diproses.')</script>";
                echo "<script>location='formpoku.php?id=$idpoproduk';</script>";

            }
        }
    ?>
    <!-- PHP SYNTAK END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->
    
    <!-- SCRIPT -->
    <!-- <script src="src/bootstrap-input-spinner.js"></script>
    <script>
        $("input[type='number']").inputSpinner()
    </script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>