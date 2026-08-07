<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
    include "settingdatatables.php";
    $idmitramarketer=$_SESSION["idmitramarketer"];
    $findUser = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer='$idmitramarketer'");
    $queryUser = $findUser->fetch_assoc();

    $total=0; 
    $grandtotal=$_GET["total"];
    $invoice=$_GET["invoice"];
    $ambil=$koneksi->query("SELECT count(*) as bank FROM rekeningku where idmitramarketer='$idmitramarketer'"); 
    $bank=$ambil->fetch_assoc();
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
        <h4 align="center">
            <?php echo $queryUser["namaagen"]; ?> (Cust ID : <?php echo $_SESSION["idmitramarketer"]; ?> )
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
                                            $ambil=$koneksi->query("SELECT * FROM rekeningku where idmitramarketer='$idmitramarketer'"); 
                                            while($marketer=$ambil->fetch_assoc()):
                                        ?>
                                        <option value="<?php echo $marketer['bank']?>"><?php echo $marketer['bank'] ?></option>
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
                                            $ambil=$koneksi->query("SELECT * FROM rekeningku where idmitramarketer='$idmitramarketer'"); 
                                            while($marketer=$ambil->fetch_assoc()): 
                                        ?>
                                        <option value="<?php echo $marketer['namapemilik']." ".$marketer['rekening']?>"><?php echo $marketer['namapemilik']." ".$marketer['rekening']?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <hr>

                            <div class="form-group">
                                <label>Jumlah Transfer</label>
                                <input type="number" min="0" value='<?= $grandtotal;?>' class="form-control" name="jmlhtransfer" required>         
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
            $tgl=date('H:i:s');
            $sekarang=date('Y-m-d H:i:s');
            $invoice=$_POST["invoice"];
            $bankpengirim=addslashes(htmlspecialchars($_POST["bankpengirim"]));
            $rekeningpengirim=addslashes(htmlspecialchars($_POST["rekeningpengirim"]));
            $jmlhtransfer=$_POST["jmlhtransfer"];
            $metodebayar=$_POST["metodebayar"];
            
            $grandtotal=$_GET["total"] ;
            $bayar=$_GET["bayar"] ;
            $idpoproduk=$_GET["idpo"];
            $jenis=$_GET["jenis"];

            $foto = $_FILES['foto']['name'];
            $tmp = $_FILES['foto']['tmp_name'];
            $ukuranFile = $_FILES['foto']['size'];

            if ($foto<>'') {
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
                $namaFileBaru = uniqid();
                $namaFileBaru.='.';
                $namaFileBaru.= $ekstensiGambar;  
            }

            if ($jenis=='dp') {
                $sql = $koneksi->query("INSERT INTO popembayaran (idpembayaran, idpoproduk, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, jenis, tgl, waktu) 
                                        VALUES (null,'$idpoproduk','$invoice','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar','$jenis',NOW(),'$tgl')");
                $koneksi->query("UPDATE pomitra SET status='Sudah Confirm DP' where invoice='$invoice' "); 
            }
            if ($jenis=="lunas") {
                $datatf=$koneksi->query("SELECT jmlh_tambah, jmlh_lunas FROM popembayaran
                                    where invoice='$invoice'");
                $tampiltf=$datatf->fetch_assoc();
                if ($tampiltf['jmlh_lunas']=="") {
                    $sql = $koneksi->query("UPDATE popembayaran set jmlh_lunas ='$jmlhtransfer', 
                                                                    bankpengirim = '$bankpengirim', 
                                                                    rekeningpengirim = '$rekeningpengirim',
                                                                    metodebayar='$metodebayar',
                                                                    jenis='dp'
                                                                    WHERE invoice='$invoice'");
                    $koneksi->query("UPDATE pomitra SET status='Sudah Confirm Pelunasan' where invoice='$invoice' "); 
                }else{
                    $sql = $koneksi->query("UPDATE popembayaran set jmlh_lunas =jmlh_lunas + '$jmlhtransfer',
                                                                    jmlh_tambah = '$jmlhtransfer', 
                                                                    bankpengirim = '$bankpengirim', 
                                                                    rekeningpengirim = '$rekeningpengirim', 
                                                                    metodebayar='$metodebayar'
                                                                    WHERE invoice='$invoice'");
                    $koneksi->query("UPDATE pomitra SET status='Sudah Confirm Pelunasan' where invoice='$invoice' "); 
                }      
            }
            if ($jenis=='Payment 1') {
                $sql = $koneksi->query("INSERT INTO popembayaran (idpembayaran, idpoproduk, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, jenis, tgl, waktu) VALUES
                (null,'$idpoproduk','$invoice','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar','$jenis',NOW(),'$tgl')");
                $koneksi->query("UPDATE pomitra SET status='Sudah Confirm $jenis' where invoice='$invoice' "); 
            }   

            if ($jenis=='Payment 2') {
                $sql = $koneksi->query("INSERT INTO popembayaran (idpembayaran, idpoproduk, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, jenis, tgl, waktu) VALUES
                (null,'$idpoproduk','$invoice','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar','$jenis',NOW(),'$tgl')");
                $koneksi->query("UPDATE pomitra SET status='Sudah Confirm $jenis' where invoice='$invoice' "); 
            }   

            if ($jenis=='Payment 3') {
                $sql = $koneksi->query("INSERT INTO popembayaran (idpembayaran, idpoproduk, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, jenis, tgl, waktu) VALUES
                (null,'$idpoproduk','$invoice','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar','$jenis',NOW(),'$tgl')");
                $koneksi->query("UPDATE pomitra SET status='Sudah Confirm $jenis' where invoice='$invoice' "); 
            }            
            if ($jenis=='Payment 4') {
                $sql = $koneksi->query("INSERT INTO popembayaran (idpembayaran, idpoproduk, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, jenis, tgl, waktu) VALUES
                (null,'$idpoproduk','$invoice','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar','$jenis',NOW(),'$tgl')");
                $koneksi->query("UPDATE pomitra SET status='Sudah Confirm $jenis' where invoice='$invoice' "); 
            }            
            if ($jenis=='Payment 5') {
                $sql = $koneksi->query("INSERT INTO popembayaran (idpembayaran, idpoproduk, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, jenis, tgl, waktu) VALUES
                (null,'$idpoproduk','$invoice','$bankpengirim','$rekeningpengirim','$jmlhtransfer','$metodebayar','$jenis',NOW(),'$tgl')");
                $koneksi->query("UPDATE pomitra SET status='Sudah Confirm $jenis' where invoice='$invoice' "); 
            }            

        if ($sql) {
            if ($foto<>'') {
                if(move_uploaded_file($tmp, 'bukti/'. $namaFileBaru)){
                    $ket = $bankpengirim.'-'.$rekeningpengirim.'-'.$metodebayar.'-'.$jmlhtransfer;
                    $koneksi->query("INSERT INTO buktitf (id, invoice, gambar, jenis, ket, waktu) 
                                        VALUES (null, '$invoice','$namaFileBaru','$jenis','$ket','$sekarang')");
                }
            }  
            echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya tunggu konfirmasi dari kami');</script>";
            echo "<script>location='listnewpo'</script>";
        }else{
            echo "<script>alert('Konfirmasi Pembayaran DP Gagal');</script>";
            echo "<script>location='listnewpo'</script>";
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