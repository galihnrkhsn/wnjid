<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include "settingdatatables.php";

    $total          = 0; 
    $grandtotal     = $_GET["total"]; 
    $invoice        = $_GET["invoice"];

    $idmitraagen    = $_SESSION["idmitraagen"];
    $ambil          = $koneksi->query("SELECT count(*) as bank FROM rekeningku where idmitraagen = '$idmitraagen'"); 
    $bank           = $ambil->fetch_assoc();

    $findUser       = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen='$idmitraagen'");
    $queryUser      = $findUser->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agen | Wanoja</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
    
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">   
        <h4 align="center">
            <?php echo $queryUser["namaagen"]; ?> (Cust ID : <?php echo $_SESSION["idmitraagen"]; ?> )
        </h4>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="container">               
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Invoice</label>
                                <select name="invoice" class="form-control" required>
                                    <option value="<?php echo $invoice; ?>"><?php echo $invoice; ?> | Rp. <?php echo number_format($grandtotal); ?></option>
                                </select>
                            </div>
                            <?php if ($bank['bank']==0): ?>
                                <div class='form-group'>
                                    <label>Nama Bank Pengirim</label>
                                    <input type='text' class='form-control' name='bankpengirim' required>
                                </div>
                            <?php else: ?>
                                <div class='form-group'>
                                    <label>Nama Bank Pengirim</label>
                                    <select name='bankpengirim' class='form-control'>
                                        <?php 
                                            $ambil=$koneksi->query("SELECT * FROM rekeningku where idmitraagen='$idmitraagen'"); 
                                            while($agen=$ambil->fetch_assoc()):
                                        ?>
                                        <option value="<?php echo $agen['bank']?>"><?php echo $agen['bank'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <?php if ($bank['bank']==0): ?>
                                <div class='form-group'>
                                    <label>Nama / Nomor Rekening Pengirim</label>
                                    <input type='text' class='form-control' name='rekeningpengirim' required>
                                </div>
                            <?php else: ?>
                                <div class='form-group'>
                                    <label>Nama / Nomor Rekening Pengirim</label>
                                    <select name='rekeningpengirim' class='form-control' required>
                                        <?php 
                                            $ambil=$koneksi->query("SELECT * FROM rekeningku where idmitraagen='$idmitraagen'"); 
                                            while($agen=$ambil->fetch_assoc()): 
                                        ?>
                                        <option value="<?php echo $agen['namapemilik']." ".$agen['rekening']?>"><?php echo $agen['namapemilik']." ".$agen['rekening']?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <hr>

                            <div class="form-group">
                                <label>Jumlah Transfer</label>
                                <input type="number" min="0" value='<?= $grandtotal;?>' class="form-control" name="jmlhtransfer" required readonly>         
                            </div>

                            <div class="form-group">
                                <label>Metode Pembayaran</label>
                                <select class="form-control" name="metodebayar" required>
                                    <option value="">~Pilih Rekening Pembayaran~</option>
                                    <?php 
                                        $ambil_rek=$koneksi->query("SELECT * FROM rekeningwnj WHERE status = 'A'");
                                        while($rekening=$ambil_rek->fetch_assoc()): 
                                    ?>          
                                        <option><?= $rekening['namabank']; ?> <?= $rekening['norekening']; ?></option>
                                    <?php endwhile; ?>     
                                </select>    
                            </div>

                            <div class="form-group">
                                <label>Bukti Transfer</label>
                                <input type="file" name="foto" class="form-control">
                                <span>Silahkan upload bukti transfer/bukti saldo tampilan di web</span>
                            </div>

                            <?= $_GET["status"]; ?>
                            <center><button type="submit" class="btn btn-primary" name="kirim">Kirim</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>
    
    <!-- PHP -->
    <?php   
        if(isset($_POST['kirim'])){
            date_default_timezone_set('Asia/Jakarta');
            $tgl                = date('Y-m-d');
            $sekarang           = date('Y-m-d H:i:s');
            $waktu              = date('H:i:s');

            $bankpengirim       = $_POST["bankpengirim"];
            $rekeningpengirim   = $_POST["rekeningpengirim"];
            $jmlhtransfer       = $_POST["jmlhtransfer"];
            $metodebayar        = $_POST["metodebayar"];
            $invoice            = $_GET["invoice"];
            $grandtotal         = $_GET["total"];
            $bayar              = $_GET["bayar"];
            $idpoproduk         = $_GET["idpo"];
            $jenis              = $_GET["jenis"];

            $ket                = $bankpengirim.'-'.$rekeningpengirim.'-'.$metodebayar.'-'.$jmlhtransfer;

            $foto               = $_FILES['foto']['name'];
            $tmp                = $_FILES['foto']['tmp_name'];
            $size               = $_FILES['foto']['size'];

            if ( $foto <> '' ) {
                $ekstensiGambarValid = ['jpg','jpeg','png','svg'];
                $ekstensiGambar = explode('.', $foto);
                $ekstensiGambar = strtolower(end($ekstensiGambar));
                if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                    echo "<script>
                            alert('Yang anda upload bukan gambar');
                        </script>";
                    echo "<script>location='popembayaran.php?invoice=$invoice&total=$grandtotal&bayar=$bayar&idpo=$idpoproduk&jenis=$jenis';</script>";
                return false;
                }
                $namaFileBaru = 'A'.$idmitraagen.$idpoproduk.uniqid();
                $namaFileBaru.='.';
                $namaFileBaru.= $ekstensiGambar;
            }

            if (move_uploaded_file($tmp, 'bukti/' . $namaFileBaru)) {
                try {
                    $koneksi->query("INSERT INTO buktitf
                                            (id, invoice, gambar, jenis, ket, waktu)
                                        VALUES
                                            (NULL, '$invoice', '$namaFileBaru', '$jenis', '$ket', '$waktu')
                                    ");
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
            if ( $jenis == 'dp' ) {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                (
                                                    idpembayaran, idpoproduk, invoice, 
                                                    bankpengirim, rekeningpengirim,
                                                    jmlhtransfer, metodebayar, jenis, ket, tgl, waktu
                                                ) 
                                                VALUES 
                                                (
                                                    NULL, '$idpoproduk', '$invoice', '$bankpengirim', 
                                                    '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', 
                                                    '$jenis', '$ket', '$tgl','$waktu'
                                                )
                                        ");

                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm DP' WHERE invoice = '$invoice' ");
                    if ($sql) {
                        echo "<script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>";
                        echo "<script>location='listnewpo';</script>";
                    }
                } catch(Exception $error) {
                    echo "Konfirmasi DP Gagal!";
                    echo $error->getMessage();
                }
            } elseif ( $jenis == "lunas" ) {
                try {
                    $datatf = $koneksi->query("SELECT jmlh_tambah, jmlh_lunas FROM popembayaran WHERE invoice = '$invoice'");
                    $tampiltf = $datatf->fetch_assoc();

                    if ($tampiltf['jmlh_lunas'] == "") {
                        $sql = $koneksi->query("UPDATE popembayaran SET
                                                    jmlh_lunas          = '$jmlhtransfer',
                                                    bankpengirim        = '$bankpengirim',
                                                    rekeningpengirim    = '$rekeningpengirim',
                                                    metodebayar         = '$metodebayar',
                                                    jenis               = 'dp'
                                                    WHERE invoice       = '$invoice'
                                            ");
                        $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm Pelunasan' WHERE invoice = '$invoice'");
                    } else {
                        $sql = $koneksi->query("UPDATE popembayaran SET
                                                        jmlh_lunas          = jmlh_lunas + '$jmlhtransfer'
                                                        jmlh_tambah         = '$jmlhtransfer', 
                                                        bankpengirim        = '$bankpengirim', 
                                                        rekeningpengirim    = '$rekeningpengirim', 
                                                        metodebayar         = '$metodebayar'
                                                    WHERE invoice           = '$invoice'
                                                ");
                        $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm Pelunasan' WHERE invoice = '$invoice'");
                    }

                    if ($sql) {
                        echo "<script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>";
                        echo "<script>location='listnewpo';</script>";
                    }
                } catch(Exception $error) {
                    echo "Konfirmasi Pelunasan Gagal!";
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Pelunasan") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                (
                                                    idpembayaran, idpoproduk, invoice,
                                                    bankpengirim, rekeningpengirim, jmlhtransfer,
                                                    metodebayar, jenis, ket,
                                                    tgl, waktu
                                                )
                                                VALUES 
                                                (
                                                    NULL, '$idpoproduk', '$invoice',
                                                    '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                    '$ket', '$tgl', '$waktu'
                                                )
                                            ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm Pelunasan' WHERE invoice = '$invoice'");
                    if ($sql) {
                        echo "<script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>";
                        echo "<script>location='listnewpo';</script>";
                    }
                } catch(Exception $error) {
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Payment1") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                (
                                                    idpembayaran, idpoproduk, invoice,
                                                    bankpengirim, rekeningpengirim, jmlhtransfer,
                                                    metodebayar, jenis, ket,
                                                    tgl, waktu
                                                )
                                                VALUES 
                                                (
                                                    NULL, '$idpoproduk', '$invoice',
                                                    '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                    '$ket', '$tgl', '$waktu'
                                                )
                                        ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                    if ($sql) {
                        echo "<script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>";
                        echo "<script>location='listnewpo';</script>";
                    }
                } catch(Exception $error) {
                    echo "Konfirmasi Gagal! 1";
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Payment2") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                    (
                                                        idpembayaran, idpoproduk, invoice,
                                                        bankpengirim, rekeningpengirim, jmlhtransfer,
                                                        metodebayar, jenis, ket,
                                                        tgl, waktu
                                                    )
                                                    VALUES 
                                                    (
                                                        NULL, '$idpoproduk', '$invoice',
                                                        '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                        '$ket', '$tgl', '$waktu'
                                                    )
                                            ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                    if ($sql) {
                        echo "<script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>";
                        echo "<script>location='listnewpo';</script>";
                    }
                } catch(Exception $error) {
                    echo "Konfirmasi Gagal! 2";
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Payment3") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                    (
                                                        idpembayaran, idpoproduk, invoice,
                                                        bankpengirim, rekeningpengirim, jmlhtransfer,
                                                        metodebayar, jenis, ket,
                                                        tgl, waktu
                                                    )
                                                    VALUES 
                                                    (
                                                        NULL, '$idpoproduk', '$invoice',
                                                        '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                        '$ket', '$tgl', '$waktu'
                                                    )
                                            ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                    if ($sql) {
                        echo "<script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>";
                        echo "<script>location='listnewpo';</script>";
                    }
                } catch(Exception $error) {
                    echo "Konfirmasi Gagal! 3";
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Payment4") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                    (
                                                        idpembayaran, idpoproduk, invoice,
                                                        bankpengirim, rekeningpengirim, jmlhtransfer,
                                                        metodebayar, jenis, ket,
                                                        tgl, waktu
                                                    )
                                                    VALUES 
                                                    (
                                                        NULL, '$idpoproduk', '$invoice',
                                                        '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                        '$ket', '$tgl', '$waktu'
                                                    )
                                            ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                    if ($sql) {
                        echo "<script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>";
                        echo "<script>location='listnewpo';</script>";
                    }
                } catch(Exception $error) {
                    echo "Konfirmasi Gagal! 4";
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Payment5") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                    (
                                                        idpembayaran, idpoproduk, invoice,
                                                        bankpengirim, rekeningpengirim, jmlhtransfer,
                                                        metodebayar, jenis, ket,
                                                        tgl, waktu
                                                    )
                                                    VALUES 
                                                    (
                                                        NULL, '$idpoproduk', '$invoice',
                                                        '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                        '$ket', '$tgl', '$waktu'
                                                    )
                                            ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                    if ($sql) {
                        echo "<script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>";
                        echo "<script>location='listnewpo';</script>";
                    }
                } catch(Exception $error) {
                    echo "Konfirmasi Gagal! 5";
                    echo $error->getMessage();
                }
            } else {
                var_dump(error_get_last());
            }
        }
    ?>  
    <!-- PHP END -->
    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>