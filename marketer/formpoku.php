<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
    include "settingdatatables.php";

    $idpoproduk = $_GET['id'];
    $idmitramarketer=$_SESSION["idmitramarketer"];
    $invoice='M'.$idpoproduk.'-'.$idmitramarketer;
    $query = "SELECT COUNT(*) as jumlah,
                poproduk.idpoproduk,
                poproduk.namapo,
                poproduk.status 
                FROM poproduk 
                inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                WHERE poproduk.idpoproduk='$idpoproduk' 
                AND pomitra.idmitramarketer='$idmitramarketer'
                AND pomitra.invoice = '$invoice'
                ";
    $sql = mysqli_query($koneksi, $query);  
    $data = mysqli_fetch_array($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer | WNJ.ID</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5"> 
        <table id="myJudul" class="w3-table-all w3-centered">
            <?php        
                $namapo = $data['namapo'];
                $id = $data['idpoproduk'];
            ?>
            <tr>
                <td colspan="2">
                    <div class="d-flex justify-content-center">
                        <h4><b>Formulir Pemesanan <?= $namapo ?></b></h4>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="container panel panel-default mt-4">
        <?php $query = "SELECT * FROM poproduk WHERE poproduk.idpoproduk = '228'"; ?>
        <div class="panel-body">
            <?php if($idpoproduk == 228) : ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="container mb-4 text-center">
                            <a href="https://drive.google.com/drive/folders/16OoJPwCV0zpKeIA9-YFuehYFmfhGomxo?usp=sharing" class="btn btn-warning btn-sm mt-2" target="_blank">Google Drive Legging</a>
                            <a href="https://ads.wnj.web.id/legging" class="btn btn-success btn-sm mt-2" target="_blank">Landing Page Legging ( Website Legging )</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <form method="POST">
                    <div style="padding: 0 15px;">
                            <?php
                                // Initialize total
                                $idpoproduk = $_GET['id'];
                                $total      = 0;
                                $index      = 0;
                                $sql        = "SELECT * FROM poproduk 
                                                INNER JOIN pokategori 
                                                INNER JOIN podetail 
                                                ON poproduk.idpoproduk = pokategori.idpoproduk 
                                                AND pokategori.idpo = podetail.idpo 
                                                WHERE poproduk.idpoproduk = '$idpoproduk' 
                                                AND podetail.variant NOT LIKE '%Custom%' 
                                                AND podetail.idpo NOT IN (4413, 4382, 4351)
                                                ORDER BY podetail.idpodetail ASC";
                                $query      = $koneksi->query($sql);
                                while($row = $query->fetch_assoc()){
                            ?>
                                <div class="form-group">
                                    <label><?php echo $row['variant']; ?></label>
                                    <input type="hidden" name="idpodetail[]" value="<?php echo $row['idpodetail']; ?>">
                                    <input type="number" min="0" name="jmlh[]" class="form-control" style="width:300px;" value=0 required>
                                </div>

                                <?php
                                    $namapo2    = $row['namapo'];
                                    $id         = $row['idpoproduk'];
                                    $idadmin    = $_SESSION["idadmin"]; 
                                }

                                $sql_tgl = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'");
                                $query_tgl = $sql_tgl->fetch_assoc();
                                date_default_timezone_set('Asia/Jakarta');
                                $today = date("d M Y");
                            ?>
                            <?php
                                $sql = $koneksi->query("SELECT COUNT(*) AS total_inv, pomitra.* FROM pomitra WHERE idpoproduk = $idpoproduk AND idmitramarketer = $idmitramarketer");
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
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <?php
        if(isset($_POST["save"])){
            $checkQuery     = $koneksi->query("SELECT COUNT(*) as count 
                                                FROM pomitra 
                                                WHERE idmitramarketer = '$idmitramarketer' 
                                                AND idpoproduk = '$idpoproduk'");
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
                $invoice = 'M' . $idpoproduk . '-' . $idmitramarketer . $angka_acak;
            }

            for($x = 0; $x < $jumlah_dipilih; $x++){
                $processed  = true;
                $sql        = "SELECT * FROM podetail WHERE idpodetail = '$idpodetail[$x]'";
                $query      = $koneksi->query($sql);
                $detail     = $query->fetch_assoc();
                $harga      = $detail['harga'];
                $idpo       = $detail['idpo'];
                $total      = $jmlh[$x] * $harga;

                $koneksi->query("INSERT INTO pomitra 
                                        (idpomitra, idmitramarketer, idpoproduk, idpo, idpodetail, jumlah, total, invoice, status, tgl, waktu) 
                                    VALUES
                                        (null, '$idmitramarketer', '$idpoproduk', '$idpo', '$idpodetail[$x]', '$jmlh[$x]', '$total', '$invoice', 'Belum DP', NOW(), '$waktu')");
            }
            if ($processed) {
                if($idpoproduk == '271') {
                    echo "<script>alert('data berhasil dikirim');</script>";
                    echo "<script>location='datapom3.php?id=$idpoproduk&invoice=$invoice';</script>";
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
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>