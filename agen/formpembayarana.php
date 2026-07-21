<div class="form-group">
    <label>Invoice</label>
    <select name="invoice" class="form-control" required>
        <?php 
            $total      = 0; 
            $grandtotal = $_GET["total"]; 
            $invoice    = $_GET["id"];
        ?>
        <option value="<?php echo $invoice; ?>"><?php echo $invoice; ?> | Rp. <?php echo number_format($grandtotal); ?></option>
    </select>
</div>
<!-- PEMBAYARAN BANK -->
<form method="post" enctype="multipart/form-data">
    <div id="form-bank">  
        <?php  
            $idmitraagen    = $_SESSION["idmitraagen"];       
            $ambil          = $koneksi->query("SELECT count(*) as bank FROM rekeningku where idmitraagen='$idmitraagen'"); 
            $bank           = $ambil->fetch_assoc();
                            
            if ($bank['bank']==0){
                echo "<label>Nama Bank Pengirim</label><div class='form-group'><input type='text' class='form-control' name='bankpengirim' required></div>";
            } else{ 
        ?>
        <div class='form-group'>
            <label>Nama Bank Pengirim</label>
            <select name='bankpengirim' class='form-control'>
                <?php 
                    $ambil=$koneksi->query("SELECT * FROM rekeningku where idmitraagen = '$idmitraagen'"); 
                    while($mitraagen=$ambil->fetch_assoc()){
                ?>      
                    <option value="<?php echo $mitraagen['bank']?>"><?php echo $mitraagen['bank'] ?></option>
                <?php }?>
            </select>
            <br>
        </div>
        <?php } ?>
        <?php  
            $idmitraagen    = $_SESSION["idmitraagen"];        
            $ambil          = $koneksi->query("SELECT count(*) as bank FROM rekeningku where idmitraagen='$idmitraagen'"); 
            $bank           = $ambil->fetch_assoc();
            if ( $bank['bank']==0 ){
        ?>
            <div class='form-group'>
                <label>Nama / Nomor Rekening Pengirim</label>
                <input type='text' class='form-control' name='rekeningpengirim' required>
            </div>
        <? } else{ ?>
        <div class='form-group'>
            <label>Nama / Nomor Rekening Pengirim</label>
            <select name='rekeningpengirim' class='form-control' required>
                <?php
                    $ambil=$koneksi->query("SELECT * FROM rekeningku where idmitraagen='$idmitraagen'"); 
                    while($mitraagen=$ambil->fetch_assoc()){
                ?>
                    <option value="<?php echo $mitraagen['namapemilik']." ".$mitraagen['rekening']?>"><?php echo $mitraagen['namapemilik']." ".$mitraagen['rekening']?></option>
                <? } ?>
            </select><br>
        </div>
        <? } ?>
        <div class="form-group">
            <label>Jumlah Transfer</label>
            <input type="number" class="form-control" name="jmlhtransfer" min="<?= $grandtotal; ?>" value='<?= $grandtotal; ?>' required>
        </div>
    
        <div class="form-group">
            <label>Metode Pembayaran</label>
            <select class="form-control" name="metodebayar" required>
                <option value="">~Pilih Rekening Pembayaran~</option>
                <?php 
                    $ambilrekening      = $koneksi->query("SELECT * FROM rekeningwnj WHERE status IS NULL");
                    while($datarekening = $ambilrekening->fetch_assoc()){ ?>
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
<!-- PEMBAYARAN BANK -->
<?php
    include "koneksi.php";
    if(isset($_POST['kirimBank'])){
        date_default_timezone_set('Asia/Jakarta');
        $tgl                    = date('H:i:s');
        $invoiceNilai           = $_GET["id"];
        $grandtotalNilai        = $_GET["total"];
        $bankpengirim           = addslashes(htmlspecialchars($_POST["bankpengirim"]));
        $rekeningpengirim       = addslashes(htmlspecialchars($_POST["rekeningpengirim"]));
        $jmlhtransfer           = $_POST["jmlhtransfer"];
        $metodebayar            = $_POST["metodebayar"];
        $foto                   = $_FILES['foto']['name'];
        $tmp                    = $_FILES['foto']['tmp_name'];
        $ukuranFile             = $_FILES['foto']['size'];
        $ekstensiGambarValid    = ['jpg','jpeg','png','svg'];
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
            $namaFileBaru = 'AGN_' . uniqid();
            $namaFileBaru .= '.';
            $namaFileBaru .= $ekstensiGambar;
            if (move_uploaded_file($tmp, '../adminwnj/bukti/' . $namaFileBaru)) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $koneksi->begin_transaction();
                try {
                    $sql = "INSERT INTO orderpembayaran (idpembayaran, invoice, bankpengirim, rekeningpengirim, jmlhtransfer, metodebayar, tgl, waktu, foto) 
                                    VALUES (null, '$invoiceNilai', '$bankpengirim', '$rekeningpengirim', '$jmlhtransfer', '$metodebayar', NOW(), '$tgl', '$namaFileBaru')";
                    $koneksi->query("UPDATE orderagen SET status='Tunggu Confrim Admin', payment='Sudah Konfirmasi' where invoice='$invoiceNilai' ");  

                    $koneksi->query($sql);
                    $koneksi->commit();
                    echo "<script>alert('Terimakasih .. Konfirmasi Pembayaran sudah kami terima, selanjutnya setiap invoice bisa dilihat pada menu transaksi');</script>";
                    echo "<script>location='detailorder2.php?id=$invoiceNilai&jenis=Flash'</script>";
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