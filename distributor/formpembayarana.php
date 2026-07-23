
<div class="form-group">
    <label>Invoice</label>
    <select name="invoice" class="form-control" required>
        <?php 
            $total          = 0; 
            $grandtotal     = $_GET["total"]; 
            $invoice        = $_GET["id"];
            $mitra          = $_SESSION['idadmin'];
            $harga          = $_GET['harga'];
            $ongkir         = $_GET['ongkir'];
            $biayaDS        = $_GET['ds'];
        ?>
        <option value="<?php echo $invoice; ?>"><?php echo $invoice; ?> | Rp. <?php echo number_format($grandtotal); ?></option>
    </select>
</div>
<div class="form-group">
    <label>Jenis Pembayaran</label>
    <br>
    <input type="radio" name="pilihMetode" value="dariSaldo" class="detail">Ambil dari saldo
    <br>
    <input type="radio" name="pilihMetode" value="dariBank" class="detail">Transfer Bank
    <br>
    <input type="radio" name="pilihMetode" value="dariSaldoBank" class="detail">Saldo + Transfer Bank
</div>
<hr class="new5">
<!-- PEMBAYARAN SALDO -->        
<form method="post" enctype="multipart/form-data"> 
    <div class="form-group" id="form-saldo">
        <label>Metode pembayaran : Ambil dari Saldo</label>
        <?php 
            $mitra      = $_SESSION['idadmin'];
            if ($mitra == 233) {
                $ambil2 = $koneksi->query("SELECT admin_mitra.namamitra,
                                                (sum(saldo.debit) - sum(saldo.credit)) AS selisih 
                                            FROM admin_mitra 
                                            inner join saldo on admin_mitra.idadmin=saldo.idadmin 
                                            WHERE saldo.idadmin = 233
                                            AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
                                            AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
                                            AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
                                        "); 
            } else {
                $ambil2 = $koneksi->query("SELECT (sum(debit) - sum(credit)) AS selisih FROM saldo WHERE idadmin = '$mitra' AND deleted_at IS NULL"); 
            }

            $saldo      = $ambil2->fetch_assoc();
            if($saldo['selisih']>=$_GET['total']){
                $saldo2             = number_format($saldo['selisih']);
                $sisa               = number_format($saldo['selisih']-$grandtotal);
                $grandtotal2        = number_format($grandtotal);
        ?>
            <br>Sisa saldo<br>
            <b><i>Rp. <?= $saldo2;?> </i></b><br>
            <hr class='new5'>
            <b>Ringkasan pembayaran</b><br>
            Total Tagihan : <b> Rp <?= $grandtotal2;?> </b><br>
            Saldo Terpakai : <b> -Rp <?= $grandtotal2; ?> </b><br>
            Sisa Saldo : <b> Rp <?= $sisa; ?> </b><br>
            <input type="hidden" name="saldo" id="saldo" value="<?= $grandtotal; ?>">
            <center>
                <button type="submit" class="btn btn-primary" name="simpanSaldo">Kirim</button>
            </center>
        <?php      
            } else {
                $saldo2=number_format($saldo['selisih']);
                echo "<br><i>Sisa saldo : Rp. $saldo2 (tidak cukup untuk melakukan pembayaran menggunakan saldo)</i><br>";
            }
        ?>
    </div>
</form>     
      
<!-- PEMBAYARAN SALDO -->       

<!-- PEMBAYARAN BANK -->
<form method="post" enctype="multipart/form-data">
    <div id="form-bank">
        <?php  
            $idadmin        = $_SESSION["idadmin"];
            $ambil          = $koneksi->query("SELECT count(*) as bank FROM rekeningku WHERE idadmin='$idadmin'"); 
            $bank           = $ambil->fetch_assoc();
                            
            if ($bank['bank']==0){
                echo "<label>Nama Bank Pengirim</label><div class='form-group'><input type='text' class='form-control' name='bankpengirim' required></div>";
            } else{ 
        ?>
            <div class='form-group'>
                <label>Nama Bank Pengirim</label>
                <select name='bankpengirim' class='form-control'>
                    <?php 
                        $ambil = $koneksi->query("SELECT * FROM rekeningku WHERE idadmin = '$idadmin'"); 
                        while($admin_mitra = $ambil->fetch_assoc()){
                    ?>    
                    <option value="<?php echo $admin_mitra['bank']?>"><?php echo $admin_mitra['bank'] ?></option>
                    <?php }?>
                </select>
                <br>
            </div>
        <?php } ?>
        <?php  
            $idadmin    = $_SESSION["idadmin"];        
            $ambil      = $koneksi->query("SELECT count(*) as bank FROM rekeningku WHERE idadmin='$idadmin'"); 
            $bank       = $ambil->fetch_assoc();
            
            if ($bank['bank']==0){ 
        ?>
            <div class='form-group'>
                <label>Nama / Nomor Rekening Pengirim</label>
                <input type='text' class='form-control' name='rekeningpengirim' required>
            </div>
        <?php
            } else { 
        ?>
            <div class='form-group'>
                <label>Nama / Nomor Rekening Pengirim</label>
                <select name='rekeningpengirim' class='form-control' required>
                    <?php          
                        $ambil = $koneksi->query("SELECT * FROM rekeningku WHERE idadmin = '$idadmin'"); 
                        while($admin_mitra = $ambil->fetch_assoc()){
                    ?>
                        <option value="<?php echo $admin_mitra['namapemilik']." ".$admin_mitra['rekening']?>"><?php echo $admin_mitra['namapemilik']." ".$admin_mitra['rekening']?></option>
                    <?php } ?>
                </select>
                <br>
            </div>
        <?php } ?>
        <div class="form-group">
            <label>Jumlah Transfer</label>
            <input type="number" class="form-control" name="jmlhtransfer" min="0" value="<?= $grandtotal; ?>" required>
        </div>
    
        <div class="form-group">
            <label>Metode Pembayaran</label>
            <select class="form-control" name="metodebayar" required>
                <option value="">~Pilih Metode Bayar~</option>
                <?php 
                    $ambilrekening = $koneksi->query("SELECT * FROM rekeningwnj WHERE status IS NULL");
                    while($datarekening = $ambilrekening->fetch_assoc()){ 
                ?>
                    <option value="<?= $datarekening['namabank']; ?> <?= $datarekening['norekening']; ?>"><?= $datarekening['namabank']; ?> <?= $datarekening['norekening']; ?></option>
                <?php } ?>
            </select>    
        </div>
        <div class="form-group">
            <label>Bukti Transfer</label>
            <input type="file" name="foto" class="form-control">
            <span>Silahkan upload bukti transfer/bukti saldo tampilan di web</span>
        </div>
        <center>
            <button type="submit" class="btn btn-primary" name="kirimBank">Kirim</button>
        </center>
    </div>
</form>
<!-- PEMBAYARAN BANK -->


<!-- PEMBAYARAN SALDO && BANK -->
<div id="form-saldo-bank">
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="grandtotal"  id="grandtotal" value="<?= str_replace('.', '', $grandtotal); ?>" readonly>
        <label>Metode pembayaran : Saldo + Transfer Bank</label>
        <div class="form-group">
            <?php 
                $ambil2   = $koneksi->query("SELECT (sum(debit) - sum(credit)) AS selisih FROM saldo WHERE idadmin = '$mitra' AND deleted_at IS NULL"); 
                $saldo    = $ambil2->fetch_assoc();

                $sisa     = $saldo['selisih'];
                $saldo2   = number_format($sisa);
            ?>
            <b class="mt-2">Sisa saldo : </b><i>Rp. <?= $saldo2;?> </i>
            <div class="form-group">
                <label class="mt-2">Saldo</label>
                <input type="hidden" name="sisa" id="sisa" class="form-control" value="<?= $sisa; ?>">
                <input type="number" name="saldo2" id="saldo2" class="form-control" value="0" min="0" max="<?= $sisa; ?>">
            </div>
            <hr class="new5">
        </div>
        <?php  
            $ambil      = $koneksi->query("SELECT count(*) as bank FROM rekeningku WHERE idadmin = '$mitra'"); 
            $bank       = $ambil->fetch_assoc();
                            
            if ($bank['bank'] == 0){ 
        ?>
            <div class='form-group'>
                <label>Nama Bank Pengirim</label>
                <input type='text' class='form-control' name='bankpengirim' required>
            </div>
        <?php   
            } else{ 
        ?>
            <div class='form-group' >
                <label>Nama Bank Pengirim</label>
                <select name='bankpengirim' class='form-control'>
                    <?php 
                        $ambil = $koneksi->query("SELECT * FROM rekeningku WHERE idadmin = '$mitra'"); 
                        while($admin_mitra = $ambil->fetch_assoc()){
                    ?>
                    <option value="<?php echo $admin_mitra['bank']?>"><?php echo $admin_mitra['bank'] ?></option>
                    <?php 
                        }
                    ?>
                </select>
            </div>
        <?php 
            } 
        ?>
        <?php  
            $ambil      = $koneksi->query("SELECT count(*) as bank FROM rekeningku WHERE idadmin = '$mitra'"); 
            $bank       = $ambil->fetch_assoc();
            if ($bank['bank']==0) { 
        ?>
            <div class='form-group'>
                <label>Nama / Nomor Rekening Pengirim</label>
                <input type='text' class='form-control' name='rekeningpengirim' required>
            </div>
        <?php
            } else { 
        ?>
            <div class='form-group'>
                <label>Nama / Nomor Rekening Pengirim</label>
                <select name='rekeningpengirim' class='form-control' required>
                    <?php
                        $ambil = $koneksi->query("SELECT * FROM rekeningku WHERE idadmin = '$mitra'"); 
                        while($admin_mitra = $ambil->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $admin_mitra['namapemilik']." ".$admin_mitra['rekening']?>">
                            <?php echo $admin_mitra['namapemilik']." ".$admin_mitra['rekening']?>
                        </option>
                    <?php   
                        } 
                    ?>
                </select>
                <br>
            </div>
        <?php   
            } 
        ?>
        <div class="form-group">
            <label>Jumlah Transfer</label>
            <input type="number" class="form-control" name="jmlhtransfer" id="jmlhtransfer" required>
        </div>
    
        <div class="form-group">
            <label>Rekening Pembayaran</label>
            <select class="form-control" name="metodebayar" required>
                <option value="">~Pilih Rekening Pembayaran~</option>
                <?php 
                    $ambilrekening = $koneksi->query("SELECT * FROM rekeningwnj WHERE status IS NULL");
                    while($datarekening = $ambilrekening->fetch_assoc()){ ?>
                    <option value="<?= $datarekening['namabank']; ?> <?= $datarekening['norekening']; ?> & Saldo">
                        <?= $datarekening['namabank']; ?> <?= $datarekening['norekening']; ?></option>
                <?php } ?>
            </select>    
        </div>
        <div class="form-group">
            <label>Bukti Transfer</label>
            <input type="file" name="foto" class="form-control">
            <span>Silahkan upload bukti transfer/bukti saldo tampilan di web</span>
        </div>    

        <div class="form-group">
            <label>Ringkasan pembayaran</label>
            <br>
            <label>Total Tagihan : Rp <?= number_format($grandtotal); ?></label>
            <br>
        </div>
        <center><button type="submit" class="btn btn-primary" name="simpanSaldoBank">Kirim</button></center>
    </form>
</div>
<br><br><br>
<!-- PEMBAYARAN SALDO && BANK -->


<!-- PEMBAYARAN BANK -->
<?php
    include "koneksi.php";

    if (!function_exists('convertUploadedImageToWebp')) {
        function convertUploadedImageToWebp(string $tmpPath, string $extension, string $destDir, string $baseName, int $quality = 85) {
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image = @imagecreatefromjpeg($tmpPath);
                    break;
                case 'png':
                    $image = @imagecreatefrompng($tmpPath);
                    break;
                default:
                    $image = false;
            }

            if (!$image) {
                return null;
            }

            // Jaga transparansi kalau sumbernya PNG
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);

            $filename = $baseName . '.webp';
            $saved    = imagewebp($image, $destDir . $filename, $quality);
            imagedestroy($image);

            return $saved ? $filename : null;
        }
    }

    if(isset($_POST['kirimBank'])){

        date_default_timezone_set('Asia/Jakarta');
        $tgl                    = date('H:i:s');
        $invoiceNilai           = $_GET["id"];
        $grandtotalNilai        = $_GET["total"];
        $bankpengirim           = addslashes(htmlspecialchars($_POST["bankpengirim"]));
        $rekeningpengirim       = addslashes(htmlspecialchars($_POST["rekeningpengirim"]));
        $jmlhtransfer           = $_POST["jmlhtransfer"];
        $metodebayar            = $_POST["metodebayar"];
        $hasil                  = $jmlhtransfer - $grandtotalNilai;

        $foto                   = $_FILES['foto']['name'];
        $tmp                    = $_FILES['foto']['tmp_name'];
        $ukuranFile             = $_FILES['foto']['size'];
        $ekstensiGambarValid    = ['jpg','jpeg','png'];
        $ekstensiGambar         = explode('.', $foto);
        $ekstensiGambar         = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        $errors                 = [];

        // Check file extension
        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            $errors[] = "Ekstensi file tidak valid!";
        }

        // Check file size (optional, example: max 2MB)
        if ($ukuranFile > 2000000) {
            $errors[] = "Ukuran file terlalu besar!";
        }

        if (empty($errors)) {
            $namaFileBaru = convertUploadedImageToWebp($tmp, $ekstensiGambar, '../image/bukti_transfer/', 'DB' . uniqid());

            if ($namaFileBaru) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $koneksi->begin_transaction();
                try {
                    $sql            = "INSERT INTO orderpembayaran (idpembayaran, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, tgl, waktu, foto) 
                                        VALUES (null, '$invoiceNilai', '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', NOW(), '$tgl', '$namaFileBaru')";
                    $sqlstatus      = $koneksi->query("UPDATE ordermitra SET status='Tunggu Confrim Admin', payment = 'Sudah Konfirmasi' WHERE invoice='$invoiceNilai' ");
                    $koneksi->query($sql);
                    $koneksi->commit();
                    echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya setiap invoice bisa dilihat pada menu transaksi');</script>";
                    echo "<script>location='detailorderb2.php?id=$invoiceNilai&jenis=$jenis'</script>";
                } catch (Exception $e) {
                    $koneksi->rollback();
                    error_log($e->getMessage());
                    echo "<script>alert('Terjadi kesalahan saat menyimpan data!');</script>";
                }
            } else {
                var_dump(error_get_last());
                echo "<script>alert('Terjadi kesalahan saat mengunggah file!');</script>";
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<script>alert('$error');</script>";
            }
        }    
    }


    // PEMBAYARAN SALDO
    elseif(isset($_POST['simpanSaldo'])){
        $idadmin              = $_SESSION["idadmin"];
        $invoiceNilai         = $_GET["id"];
        $grandtotalNilai      = $_GET["total"];
        $saldo                = $_POST["saldo"];
        date_default_timezone_set('Asia/Jakarta');
        $tgl                  = date('H:i:s');

        $sqlpembayaran = $koneksi->query("INSERT INTO orderpembayaran 
                                                (
                                                    idpembayaran, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, tgl, waktu
                                                ) 
                                            VALUES 
                                                (
                                                    null, '$invoiceNilai', 'Ambil Dari Saldo', 'Ambil Dari Saldo', '$saldo', 'Ambil Dari Saldo', 
                                                    NOW(),'$tgl'
                                                )
                                        ");
        $sqlpengiriman = $koneksi->query("UPDATE orderpengiriman SET total = '$grandtotalNilai' WHERE invoice = '$invoiceNilai'");

        $sqlstatus     = $koneksi->query("UPDATE ordermitra SET status = 'Tunggu Confrim Admin', payment = 'Sudah Konfirmasi' 
                                            WHERE invoice = '$invoiceNilai' 
                                        ");  
        if ($sqlpembayaran && $sqlpengiriman && $sqlstatus) {
            echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya setiap invoice bisa dilihat pada menu transaksi');</script>";
            echo "<script>location='detailorderb2.php?id=$invoiceNilai&jenis=$jenis'</script>";
        }else{
            echo "<script>alert('Konfirmasi Pembayaran gagal');</script>";
            echo "<script>location='detailorderb2.php?id=$invoiceNilai&jenis=$jenis'</script>";
        }
    } 


    // PEMBAYARAN SALDO BANK
    elseif(isset($_POST['simpanSaldoBank'])){
        date_default_timezone_set('Asia/Jakarta');
        $tgl                    = date('H:i:s');
        $invoiceNilai           = $_GET["id"];
        $grandtotalNilai        = $_GET["total"];
        $saldo                  = $_POST["saldo2"];
        $sisa                   = $_POST["sisa"];
        $bankpengirim           = addslashes(htmlspecialchars($_POST["bankpengirim"]));
        $rekeningpengirim       = addslashes(htmlspecialchars($_POST["rekeningpengirim"]));
        $jmlhtransfer           = $_POST["jmlhtransfer"];
        $metodebayar            = $_POST["metodebayar"];
        $hasiljumlah            = $saldo + $jmlhtransfer;

        if ($saldo > $sisa) {
            echo "<script>alert('Saldo melebihi Sisa Saldo');</script>";
            echo "<script>location='detailorderb2.php?id=$invoiceNilai&total=$grandtotalNilai</script>";
            return false;
        }

        $foto       = $_FILES['foto']['name'];
        $tmp        = $_FILES['foto']['tmp_name'];
        $ukuranFile = $_FILES['foto']['size'];
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar      = explode('.', $foto);
        $ekstensiGambar      = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        $errors              = [];

        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            $errors[] = "Ekstensi file tidak valid!";
        }

        // Check file size (optional, example: max 2MB)
        if ($ukuranFile > 2000000) {
            $errors[] = "Ukuran file terlalu besar!";
        }

        if (empty($errors)) {
            $namaFileBaru = convertUploadedImageToWebp($tmp, $ekstensiGambar, '../image/bukti_transfer/', uniqid());

            if ($namaFileBaru) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $koneksi->begin_transaction();
                try {
                    $querySaldoBank = $koneksi->query("INSERT INTO orderpembayaran 
                                                                    (
                                                                        idpembayaran, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, 
                                                                        metodebayar, tgl, waktu, foto
                                                                    ) 
                                                            VALUES (
                                                                        NULL,'$invoiceNilai', '$bankpengirim', '$rekeningpengirim', 
                                                                        '$jmlhtransfer', '$metodebayar', NOW(), '$tgl', '$namaFileBaru'
                                                                    )
                                                    ");

                    $koneksi->query("UPDATE orderpengiriman SET total = '$grandtotalNilai' WHERE invoice = '$invoiceNilai'");

                    $koneksi->query("UPDATE ordermitra SET status = 'Tunggu Confrim Admin', payment = 'Sudah Konfirmasi' 
                                        WHERE invoice = '$invoiceNilai' 
                                    ");
                    $koneksi->commit();
                    
                    echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya setiap invoice bisa dilihat pada menu transaksi');</script>";
                    echo "<script>location='detailorderb2.php?id=$invoiceNilai&jenis=$jenis'</script>";
                } catch (Exception $e) {
                    $koneksi->rollback();
                    error_log($e->getMessage());
                    echo "<script>alert('Terjadi kesalahan saat menyimpan data!');</script>";
                }
            } else {
                var_dump(error_get_last());
                echo "<script>alert('Terjadi kesalahan saat mengunggah file!');</script>";
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<script>alert('$error');</script>";
            }
        }       
    }



?>
<script>
    $(document).ready(function(){
        $("#form-saldo").css("display","none"); //Menghilangkan form-input ketika pertama kali dijalankan
        $(".detail").click(function(){ //Memberikan even ketika class detail di klik (class detail ialah class radio button)
            if ($("input[name='pilihMetode']:checked").val() == "dariSaldo" ) { //Jika radio button "berbeda" dipilih maka tampilkan form-inputan
                $("#form-saldo").slideDown("fast"); //Efek Slide Down (Menampilkan Form Input)
            } else {
                $("#form-saldo").slideUp("fast"); //Efek Slide Up (Menghilangkan Form Input)
            }
        });
    });
</script>

<script>
    $(document).ready(function(){
        $("#form-bank").css("display","none"); //Menghilangkan form-input ketika pertama kali dijalankan
        $(".detail").click(function(){ //Memberikan even ketika class detail di klik (class detail ialah class radio button)
            if ($("input[name='pilihMetode']:checked").val() == "dariBank" ) { //Jika radio button "berbeda" dipilih maka tampilkan form-inputan
                $("#form-bank").slideDown("fast"); //Efek Slide Down (Menampilkan Form Input)
            } else {
                $("#form-bank").slideUp("fast"); //Efek Slide Up (Menghilangkan Form Input)
            }
        });
    });
</script>

<script>
    $(document).ready(function(){
        $("#form-saldo-bank").css("display","none"); //Menghilangkan form-input ketika pertama kali dijalankan
        $(".detail").click(function(){ //Memberikan even ketika class detail di klik (class detail ialah class radio button)
            if ($("input[name='pilihMetode']:checked").val() == "dariSaldoBank" ) { //Jika radio button "berbeda" dipilih maka tampilkan form-inputan
                $("#form-saldo-bank").slideDown("fast"); //Efek Slide Down (Menampilkan Form Input)
            } else {
                $("#form-saldo-bank").slideUp("fast"); //Efek Slide Up (Menghilangkan Form Input)
            }
        });
    });
</script>
<script>
window.addEventListener('DOMContentLoaded', function () {
    const saldoInput = document.getElementById('saldo2');
    const grandtotalInput = document.getElementById('grandtotal');
    const jmlhtransferInput = document.getElementById('jmlhtransfer');

    saldoInput.addEventListener('input', function () {
        const grandtotal = parseFloat(grandtotalInput.value) || 0;
        const saldo = parseFloat(saldoInput.value) || 0;
        const jmlhtransfer = grandtotal - saldo;
        jmlhtransferInput.value = jmlhtransfer;
    });

    jmlhtransferInput.addEventListener('input', function () {
        const grandtotal = parseFloat(grandtotalInput.value) || 0;
        const jmlhtransfer = parseFloat(jmlhtransferInput.value) || 0;
        const saldo = grandtotal - jmlhtransfer;

        saldoInput.value = saldo;
    });
});
</script>

