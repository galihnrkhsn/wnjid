<?php
    // session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $total=0; 
    $grandtotal=$_GET["total"]; 
    $invoice=$_GET["invoice"];
    $idadmin=$_SESSION["idadmin"];

    $ambil=$koneksi->query("SELECT count(*) as bank FROM rekeningku where idadmin='$idadmin'"); 
    $bank=$ambil->fetch_assoc();

    $findUser = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin='$idadmin'");
    $queryUser = $findUser->fetch_assoc();
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
    
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">   
        <h4 align="center">
        Nama Mitra  : <?php echo $queryUser["namamitra"]; ?> (Cust ID : <?php echo $_SESSION["idadmin"]; ?> )
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
                                            $ambil=$koneksi->query("SELECT * FROM rekeningku where idadmin='$idadmin'"); 
                                            while($distributor=$ambil->fetch_assoc()):
                                        ?>
                                        <option value="<?php echo $distributor['bank']?>"><?php echo $distributor['bank'] ?></option>
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
                                            $ambil=$koneksi->query("SELECT * FROM rekeningku where idadmin='$idadmin'"); 
                                            while($distributor=$ambil->fetch_assoc()): 
                                        ?>
                                        <option value="<?php echo $distributor['namapemilik']." ".$distributor['rekening']?>"><?php echo $distributor['namapemilik']." ".$distributor['rekening']?></option>
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
                                        $ambil_rek=$koneksi->query("SELECT * FROM rekeningwnj WHERE status IS NULL"); 
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
                            <center><button type="submit" class="btn btn-primary" name="kirim">Kirim</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    
    <br><br><br><br>
    
    <!-- FOOTER -->
    <?php // include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->
    <?php   
        if(isset($_POST['kirim'])){
            date_default_timezone_set('Asia/Jakarta');
            $tgl                = date('Y-m-d');
            $sekarang           = date('Y-m-d H:i:s');
            $waktu              = date('H:i:s');

            $invoice            = $_GET["invoice"];
            $bankpengirim       = $_POST["bankpengirim"];
            $rekeningpengirim   = $_POST["rekeningpengirim"];
            $jmlhtransfer       = $_POST["jmlhtransfer"];
            $metodebayar        = $_POST["metodebayar"];
            $grandtotal         = $_GET["total"];
            $bayar              = $_GET["bayar"];
            $idpoproduk         = $_GET["idpo"];
            $jenis              = $_GET["jenis"];
            $termin             = $_GET['termin'];

            $ket                = $bankpengirim.'-'.$rekeningpengirim.'-'.$metodebayar.'-'.$jmlhtransfer;

            $foto               = $_FILES['foto']['name'];
            $tmp                = $_FILES['foto']['tmp_name'];
            $size               = $_FILES['foto']['size'];

            if ($foto <> '') {
                $ekstensiGambarValid    = ['jpg','jpeg','png','svg'];
                $ekstensiGambar         = explode('.', $foto);
                $ekstensiGambar         = strtolower(end($ekstensiGambar));
                if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                    echo "<script>alert('Yang anda upload bukan gambar');</script>";
                    echo "<script>location='popembayaran.php?invoice=$invoice&total=$grandtotal&bayar=$bayar&idpo=$idpoproduk&jenis=$jenis';</script>";
                    return false;
                }
                $namaFileBaru = 'D'.$idadmin.$idpoproduk.uniqid();
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

            if ($jenis == "dp") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                (
                                                    idpembayaran, idpoproduk, invoice,
                                                    bankpengirim, rekeningpengirim, jmlhtransfer,
                                                    metodebayar, jenis, ket,
                                                    tgl, waktu, termin_seq
                                                )
                                                VALUES 
                                                (
                                                    NULL, '$idpoproduk', '$invoice',
                                                    '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                    '$ket', '$tgl', '$waktu', '$termin'
                                                )
                                        ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm DP' WHERE invoice = '$invoice'");
                } catch(Exception $error) {
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Lunas") {
                try {
                    $datatf = $koneksi->query("SELECT jmlh_tambah, jmlh_lunas FROM popembayaran WHERE invoice = '$invoice'");
                    $tampiltf = $datatf->fetch_assoc();

                    if ($tampiltf['jmlh_lunas'] == "") {
                        $sql = $koneksi->query("UPDATE popembayaran SET
                                                    jmlh_lunas = '$jmlhtransfer',
                                                    bankpengirim = '$bankpengirim',
                                                    rekeningpengirim = '$rekeningpengirim',
                                                    metodebayar = '$metodebayar',
                                                    jenis = 'DP'
                                                    WHERE invoice = '$invoice'
                                            ");
                        $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm Pelunasan' WHERE invoice = '$invoice'");
                    } else {
                        $sql = $koneksi->query("UPDATE popembayaran SET
                                                        jmlh_lunas = jmlh_lunas + '$jmlhtransfer'
                                                        jmlh_tambah = '$jmlhtransfer', 
                                                        bankpengirim = '$bankpengirim', 
                                                        rekeningpengirim = '$rekeningpengirim', 
                                                        metodebayar = '$metodebayar'
                                                    WHERE invoice='$invoice'
                                                ");
                        $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm Pelunasan' WHERE invoice = '$invoice'");
                    }
                } catch(Exception $error) {
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Pelunasan") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                (
                                                    idpembayaran, idpoproduk, invoice,
                                                    bankpengirim, rekeningpengirim, jmlhtransfer,
                                                    metodebayar, jenis, ket,
                                                    tgl, waktu, termin_seq
                                                )
                                                VALUES 
                                                (
                                                    NULL, '$idpoproduk', '$invoice',
                                                    '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                    '$ket', '$tgl', '$waktu', '$termin'
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
                                                    tgl, waktu, termin_seq
                                                )
                                                VALUES 
                                                (
                                                    NULL, '$idpoproduk', '$invoice',
                                                    '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                    '$ket', '$tgl', '$waktu', '$termin'
                                                )
                                        ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                } catch(Exception $error) {
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Payment2") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                    (
                                                        idpembayaran, idpoproduk, invoice,
                                                        bankpengirim, rekeningpengirim, jmlhtransfer,
                                                        metodebayar, jenis, ket,
                                                        tgl, waktu, termin_seq
                                                    )
                                                    VALUES 
                                                    (
                                                        NULL, '$idpoproduk', '$invoice',
                                                        '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                        '$ket', '$tgl', '$waktu', '$termin'
                                                    )
                                            ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                } catch(Exception $error) {
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Payment3") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                    (
                                                        idpembayaran, idpoproduk, invoice,
                                                        bankpengirim, rekeningpengirim, jmlhtransfer,
                                                        metodebayar, jenis, ket,
                                                        tgl, waktu, termin_seq
                                                    )
                                                    VALUES 
                                                    (
                                                        NULL, '$idpoproduk', '$invoice',
                                                        '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                        '$ket', '$tgl', '$waktu', '$termin'
                                                    )
                                            ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                } catch(Exception $error) {
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Payment4") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                    (
                                                        idpembayaran, idpoproduk, invoice,
                                                        bankpengirim, rekeningpengirim, jmlhtransfer,
                                                        metodebayar, jenis, ket,
                                                        tgl, waktu, termin_seq
                                                    )
                                                    VALUES 
                                                    (
                                                        NULL, '$idpoproduk', '$invoice',
                                                        '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                        '$ket', '$tgl', '$waktu', '$termin'
                                                    )
                                            ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                } catch(Exception $error) {
                    echo $error->getMessage();
                }
            } elseif ($jenis == "Payment5") {
                try {
                    $sql = $koneksi->query("INSERT INTO popembayaran 
                                                    (
                                                        idpembayaran, idpoproduk, invoice,
                                                        bankpengirim, rekeningpengirim, jmlhtransfer,
                                                        metodebayar, jenis, ket,
                                                        tgl, waktu, termin_seq
                                                    )
                                                    VALUES 
                                                    (
                                                        NULL, '$idpoproduk', '$invoice',
                                                        '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', '$jenis',
                                                        '$ket', '$tgl', '$waktu', '$termin'
                                                    )
                                            ");
                    $koneksi->query("UPDATE pomitra SET status = 'Sudah Confirm $jenis' WHERE invoice = '$invoice'");
                } catch(Exception $error) {
                    echo $error->getMessage();
                }
            } else {
                var_dump(error_get_last());
                die();
            }
            
            if ($sql) {
                try {
                    echo "
                        <script>alert('Terima kasih... Konfirmasi pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami!');</script>
                        <script>location='listnewpo';</script>
                    ";
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
            } else {
                echo "
                    <script>alert('Data gagal ditambahkan!');</script>
                    <script>location='listnewpo';</script>
                ";
            }
        }
    ?>  
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>