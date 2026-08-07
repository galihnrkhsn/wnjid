<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesReseller.php';
    include 'settingdatatables.php';

    $idpoproduk         = $_GET['id'];
    $idmitrareseller    = $_SESSION["idmitrareseller"];
    $poproduk           = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();
    $bukapo             = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();
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
    
    <title>Reseller | WNJ.ID</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5"> 
        <table id="myJudul" class="w3-table-all w3-centered">
            <tr>
                <td colspan="2">
                    <div class="justify-content-center">
                        <h4><b>Formulir Pemesanan </b></h4>
                        <p class="text-danger">*<?= $poproduk['namapo']; ?> MINIMAL ORDER 10 PCS/VARIANT</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="container panel panel-default mt-4">
        <?php $query = "SELECT * FROM poproduk WHERE poproduk.idpoproduk = '228'"; ?>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST">
                        <div style="padding: 0 15px;">
                            <?php
                                $sql = "SELECT * FROM poproduk 
                                        INNER JOIN pokategori 
                                        INNER JOIN podetail 
                                        ON poproduk.idpoproduk = pokategori.idpoproduk 
                                        AND pokategori.idpo = podetail.idpo 
                                        WHERE poproduk.idpoproduk = '$idpoproduk' 
                                        ORDER BY podetail.idpodetail ASC";
                                $query = $koneksi->query($sql);

                                while($row = $query->fetch_assoc()){
                            ?>
                                <div class="form-group">
                                    <label><?php echo $row['variant']; ?></label>
                                    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                                    <input type="number" name="jmlh[]" class="form-control" style="width:300px;" value="<?= $idpoproduk == 489 ? 10 : 0 ?>" min="<?= $idpoproduk == 489 ? 10 : 0 ?>" required>
                                </div>

                                <?php
                                }
                            ?>
                            <?php
                                $sql = $koneksi->query("SELECT COUNT(*) AS total_inv, pomitra.* FROM pomitra WHERE idpoproduk = $idpoproduk AND idmitrareseller = $idmitrareseller");
                                $row = $sql->fetch_assoc();
                                if ($row['total_inv'] > 0) : 
                            ?>
                                <a href="datapo.php?id=<?= $row['idpoproduk'] ?>&invoice=<?= $row['invoice'] ?>" class="btn btn-success btn-sm">Invoice</a>
                            <?php else : ?>
                                <p><strong><font color="red" size="5px">*</font></strong>Jangan Kosongkan Label, Cukup isi dengan Angka 0 jika tidak memesan</p>
                                <button type='submit' class='btn btn-primary' name='save'>Kirim</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br><br><br>
    <?php
        if(isset($_POST["save"])){
            $checkQuery     = $koneksi->query("SELECT COUNT(*) as count 
                                                FROM pomitra 
                                                WHERE idmitrareseller = '$idmitrareseller' 
                                                AND idpoproduk = '$idpoproduk' 
                                                AND idmitraagen IS NULL 
                                                AND idmitra IS NULL 
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
                $invoice = 'R' . $idpoproduk . '-' . $idmitrareseller. $angka_acak;
            }

            for($x = 0; $x < $jumlah_dipilih; $x++){
                $processed = true;
                $sql        = "SELECT * FROM podetail WHERE idpodetail = '$idpodetail[$x]'";
                $query      = $koneksi->query($sql);
                $detail     = $query->fetch_assoc();
                $harga      = $detail['harga'];
                $idpo       = $detail['idpo'];
                $total      = $jmlh[$x] * $harga;

                $koneksi->query("INSERT INTO pomitra (idpomitra, idmitrareseller, idpoproduk, idpo, idpodetail, jumlah, total, invoice, status, tgl, waktu) VALUES
                (null, '$idmitrareseller', '$idpoproduk', '$idpo', '$idpodetail[$x]', '$jmlh[$x]', '$total', '$invoice', 'Belum DP', NOW(), '$waktu')");
            }

            if ($processed) {
                echo "<script>alert('data berhasil dikirim');</script>";
                echo "<script>location='datapo.php?id=$idpoproduk&invoice=$invoice';</script>";
                exit();
            } else {
                echo "<script>alert('Tidak ada item yang diproses.')</script>";
                echo "<script>location='formpo_mandiri.php?id=$idpoproduk';</script>";
            }
        }
    ?>
    <?php include 'menubawah.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>