<?php
    session_start();
    error_reporting (0);

    require_once 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';

    $idpoproduk = $_GET['idpo'];
    $invoice    = $_GET['invoice'];
    $idadmin    = $_SESSION["idadmin"];

    $queryDB    = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin = '$idadmin'");
    $dataDB     = $queryDB->fetch_assoc();

    $bukapo     = $koneksi->query("SELECT jenis_po FROM bukapo WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();
    $jenisPO    = $bukapo['jenis_po'];
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-3 mb-5">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <form method="post">
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Nama Pengirim</label>
                                <input type="text" class="form-control" name="namapengirim" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Telepon Pengirim</label>
                                <input type="number" class="form-control" name="tlppengirim" required>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Nama Penerima</label>
                                <input type="text" class="form-control" name="namapenerima" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Telepon Penerima</label>
                                <input type="number" class="form-control" name="tlppenerima" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Alamat Penerima</label>
                                <textarea class="form-control" name="alamatpenerima" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="prov" class="form-label mb-0 text-muted">Provinsi Tujuan</label>
                                <select class="form-control" id="prov" name="prov" required>
                                    <option disabled='disabled' selected>~Pilih Provinsi Tujuan~</option>
                                    <?php
                                        $ambil      = $koneksi->query("SELECT * FROM tb_ro_provinces");
                                        while($row  = $ambil->fetch_assoc()){
                                    ?>
                                        <option value="<?php echo $row['province_id']; ?>|<?php echo $row['province_name']; ?>"><?php echo $row['province_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kabupaten" class="form-label mb-0 text-muted">Kota/Kabupaten Tujuan</label>
                                <select class="form-control" id="kabupaten" name="kabupaten" required></select>
                            </div>
                            <div class="form-group">
                                <label for="kecamatan" class="form-label mb-0 text-muted">Kecamatan Tujuan</label>
                                <select class="form-control" id="kecamatan" name="kecamatan" required></select>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Keterangan</label>
                                <textarea class="form-control" name="keterangan"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label mb-0 text-muted">Ekspedisi</label>
                                <select id="ekspedisi" class="form-control form-control-sm" name="ekspedisi" required>
                                    <?php if (isset($_GET['id'])) : ?>
                                        <option value="<?= $ekspedisi ?>" selected><?= $ekspedisi ?></option>
                                    <?php else : ?>
                                        <option selected>~ Default Selected ~</option>
                                    <?php endif; ?>
                                    <optgroup label="JNE">
                                        <option value="JNE Reg">JNE Reg</option>
                                        <option value="JNE YES">JNE YES</option>
                                        <option value="JNE Oke">JNE Oke</option>
                                        <option value="JNE CTC">JNE CTC</option>
                                        <option value="JTR">JNE Trucking (JTR)</option>
                                    <optgroup label="J&T">
                                        <option value="J&T">J&T</option>
                                        <option value="J&T Cargo">J&T Cargo</option>
                                    <optgroup label="WAHANA">
                                        <option value="Wahana Ekspres">Wahana Ekspres</option>
                                        <option value="Wahana Kargo">Wahana Kargo</option>
                                    <optgroup label="SICEPAT">
                                        <option value="Sicepat BEST">Sicepat BEST</option>
                                        <option value="Sicepat REG">Sicepat Reg</option>
                                        <option value="Sicepat Kargo">Sicepat Cargo</option>
                                    <optgroup label="POS">
                                        <option value="Pos Ekonomi Jumbo">Pos Ekonomi Jumbo</option>
                                        <option value="Pos Kilat">Pos Kilat</option>
                                    <optgroup label="LAINNYA">
                                        <option value="Paxel">Paxel</option>
                                        <option value="SPX Express">SPX Express</option>
                                        <option value="IDE">ID Express</option>
                                        <option value="Ahsan">Ahsan</option>
                                        <option value="Baraka">Baraka</option>
                                        <option value="Pegasus">Pegasus</option>
                                        <option value="Sentral">Sentral</option>
                                        <option value="Lion Parcel">Lion Parcel</option>
                                        <option value="Dakota">Dakota</option>
                                        <option value="Indah Cargo">IndahCargo</option>
                                        <option value="Adam Cargo">Adam Cargo</option>
                                        <option value="Gosend">GoSend</option>
                                        <option value="Kalog">Kalog</option>
                                        <option value="CMC KARGO">CMC CARGO</option>
                                        <option value="Tiki">Tiki</option>
                                        <option value="Triplogic">Triplogic</option>
                                        <option value="Anteraja">Anteraja</option>
                                        <option value="Ambil ke Pusat">Ambil Ke Pusat</option>
                                </select>
                            </div>

                            <div class="table-responsive">
                                <?php if (in_array($idpoproduk, [186, 187])): ?>
                                    <p><strong><font color="red" size="5px">*</font></strong> Pilih Isi Box</p>
                                <?php endif; ?>
                                <p><strong><font color="red" size="5px">*</font></strong> Jangan Kosongkan Label, cukup isi dengan angka 0 jika tidak memilih produk.</p>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><input type='checkbox' id='checkAll'> Check</th>
                                            <th>Nama Produk</th>
                                            <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                                <th>Custom</th>
                                                <th>Font</th>
                                            <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                                <th>Template</th>
                                            <?php endif; ?>
                                            <th>Stok Invoice</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $query = "SELECT 
                                                podetail.variant, pomitra.jumlah, podetail.idpodetail, 
                                                pomitra.custom, pomitra.idpomitra, pomitra.font, pomitra.custom, pomitra.template,
                                                IFNULL((SELECT SUM(pods.jumlah) FROM pods WHERE pods.invoice = '$invoice' AND pods.idpodetail = podetail.idpodetail AND pods.idpomitra = pomitra.idpomitra), 0) AS progres
                                            FROM pomitra
                                            JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                            WHERE pomitra.invoice = '$invoice' AND pomitra.jumlah > 0
                                            ORDER BY pomitra.idpomitra ASC";
                                    $result = $koneksi->query($query);
                                    while ($data = $result->fetch_assoc()):
                                        $id = $data['idpodetail'];
                                        $idpomitra = $data['idpomitra'];
                                        $kurang = $data['jumlah'] - $data['progres'];
                                    ?>
                                    <tr>
                                        <td>
                                            <input type='checkbox' class='check-item' name='update[]' value='<?= $idpomitra ?>' <?= $kurang > 0 ? '' : 'disabled' ?>>
                                            <?php if ($kurang > 0): ?>
                                                <input type="hidden" name='idpodetail<?= $idpomitra ?>' value='<?= $id ?>'>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($data['variant']) . ' ' . htmlspecialchars($data['custom']); ?></td>
                                        <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                            <td><?= $data['custom'] ?></td>
                                            <td><?= $data['font'] ?></td>
                                        <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                            <td><?= $data['template'] ?></td>
                                        <?php endif; ?>
                                        <td><?= $kurang; ?></td>
                                        <td><input type='number' class="form-control" min="0" max="<?= $kurang; ?>" name='jumlah<?= $idpomitra ?>' value='0' required></td>
                                    </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <center><button type="submit" class="btn btn-primary" name="kirim" id="submitBtn" disabled>Kirim</button></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->

    <!-- PHP SYNTAK -->
<?php
    // Pastikan variabel $koneksi adalah objek koneksi MySQLi yang valid

    if (isset($_POST['kirim'])) {
        // 1. AKTIFKAN MODE EXCEPTION PADA MYSQLI UNTUK MEMBUAT TRY...CATCH BEKERJA
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Jika tidak ada item yang dipilih sama sekali, lakukan redirect awal
        if (!isset($_POST['update']) || empty($_POST['update'])) {
            // Logika ini diperbolehkan jika Anda ingin mengizinkan submit tanpa item update
            echo "<script>alert('Data berhasil ditambah');</script>"; // Mungkin maksudnya: Data telah disimpan tanpa item detail
            echo "<script>location='formds?idpo=$idpoproduk&invoice=$invoice'</script>";
            exit; // Hentikan eksekusi di sini
        }
        
        // 2. SANITASI DAN AMANKAN INPUT (TIDAK PERLU addslashes LAGI KARENA PAKAI PREPARED)
        $namapengirim   = htmlspecialchars($_POST["namapengirim"]);
        $tlppengirim    = htmlspecialchars($_POST["tlppengirim"]);
        $namapenerima   = htmlspecialchars($_POST["namapenerima"]);
        $tlppenerima    = htmlspecialchars($_POST["tlppenerima"]);
        $alamatpenerima = htmlspecialchars($_POST["alamatpenerima"]);
        $keterangan     = htmlspecialchars($_POST["keterangan"] ?? '');
        $dari           = htmlspecialchars($_POST["dari"] ?? '');
        $kepada         = htmlspecialchars($_POST["kepada"] ?? '');
        $ekspedisi      = $_POST["ekspedisi"];

        // Proses data provinsi, kabupaten, kecamatan
        $provinsi_id    = $_POST["prov"];
        $provinsi       = explode('|', $provinsi_id)[0];
        
        $kabupaten_id   = $_POST["kabupaten"];
        $kabupaten      = explode('|', $kabupaten_id)[0];
        
        $kecamatan_id   = $_POST["kecamatan"];
        $kecamatan      = explode('|', $kecamatan_id)[0];
        
        $keterangannya  = $keterangan;

        if ($idpoproduk == 186 || $idpoproduk == 187) {
            $keterangannya = $dari . '|' . $kepada . '|' . $keterangan;
        }
        
        // 3. AMBIL DATA ID DROPSHIP TERAKHIR (Perlu diamankan jika pakai input user)
        // Query ini TIDAK berada dalam transaksi karena ini adalah SELECT data ID yang tidak diubah
        $sql_ds = $koneksi->query("SELECT iddropship FROM podropship ORDER BY iddropship DESC LIMIT 1");
        // Karena mysqli_report aktif, error di sini akan melempar Exception secara otomatis

        date_default_timezone_set('Asia/Jakarta');
        $data           = $sql_ds->fetch_assoc();
        $no             = $data ? (int)$data['iddropship'] : 0; // Jika tidak ada data, mulai dari 0
        $ab             = 1;
        $nobaru         = $no + $ab;
        $no_ds          = $invoice . '-' . $nobaru;
        $today          = date("Y-m-d H:i:s");
        
        $totaljum       = 3; // Inisialisasi awal untuk logic tertentu
        $item_inserted  = false; // Flag untuk memastikan ada item detail yang di-insert

        // 4. MULAI TRANSAKSI
        try {
            $koneksi->begin_transaction();

            // --- A. Hitung totaljum (Loop 1) ---
            if ($idpoproduk == 186 || $idpoproduk == 187) {
                foreach($_POST['update'] as $updateid){
                    $jumlah = (int)$_POST['jumlah'.$updateid];
                    $totaljum += $jumlah;
                }
            }

            // --- B. INSERT ke tabel pods (Detail Pesanan) ---
            $stmt_pods = $koneksi->prepare("INSERT INTO pods 
                (no_ds, invoice, idpodetail, idpomitra, jumlah, waktu)
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_pods->bind_param("ssiiis", $no_ds, $invoice, $idpodetail, $updateid, $jumlah, $today);

            foreach($_POST['update'] as $updateid){
                // Konversi ke integer untuk keamanan
                $jumlah     = (int)$_POST['jumlah'.$updateid];
                $idpodetail = (int)$_POST['idpodetail'.$updateid];
                $updateid   = (int)$updateid; // ID item mitra

                if ($jumlah <= 0) { 
                    continue; // Skip jika jumlah 0 atau negatif
                }

                // Eksekusi Prepared Statement pods
                if (!$stmt_pods->execute()) {
                    $stmt_pods->close();
                    throw new Exception('Error insert pods: ' . $koneksi->error);
                }
                $item_inserted = true;
            }
            $stmt_pods->close();

            // --- C. Cek Logika Pembatalan (Jika semua jumlah = 0) ---
            if ($item_inserted === false) {
                throw new Exception("Transaksi dibatalkan: Tidak ada item dengan jumlah lebih dari 0.");
            }

            $stmt_podropship = $koneksi->prepare("INSERT INTO podropship (
                idadmin, idmitraagen, idmitrareseller, idmitramarketer, 
                idpoproduk, invoice, no_ds, namapengirim, tlppengirim, 
                namapenerima, tlppenerima, alamatpenerima, provinsi, kota, 
                kecamatan, keterangan, ekspedisi
            ) VALUES (
                ?, '0', '0', '0', 
                ?, ?, ?, ?, ?, 
                ?, ?, ?, ?, ?, 
                ?, ?, ?
            )");
            
            // Tipe data diasumsikan iissssssssssssss (i=integer, s=string)
            $stmt_podropship->bind_param("iissssssssssss", 
                $idadmin, 
                $idpoproduk, $invoice, $no_ds, $namapengirim, $tlppengirim, 
                $namapenerima, $tlppenerima, $alamatpenerima, $provinsi, $kabupaten, 
                $kecamatan, $keterangannya, $ekspedisi
            );
            
            // Eksekusi Query podropship (WAJIB DITAMBAH)
            if (!$stmt_podropship->execute()) {
                $stmt_podropship->close();
                throw new Exception('Error insert podropship: ' . $koneksi->error);
            }
            $stmt_podropship->close();

            // --- E. COMMIT TRANSAKSI ---
            $koneksi->commit();
            
            echo "<script>alert('Data berhasil ditambah');</script>";
            echo "<script>location='listds?id=$idpoproduk&invoice=$invoice'</script>";

        } catch (Exception $e) {
            // --- F. ROLLBACK JIKA ADA ERROR ---
            $koneksi->rollback();
            error_log("Kesalahan Database: " . $e->getMessage()); 
            
            $error_message = $e->getMessage();
            
            if (strpos($error_message, "Tidak ada item") !== false) {
                echo "<script>alert('Gagal! Semua item yang dipilih memiliki jumlah 0. Transaksi dibatalkan.');</script>";
                echo "<script>location='formds?idpo=$idpoproduk&invoice=$invoice'</script>"; 
            } else {
                // Tampilkan pesan error umum untuk keamanan
                echo "<script>alert('Terjadi kesalahan saat menyimpan data. Transaksi dibatalkan. Silakan coba lagi.');</script>";
            }
        }
    }
    ?>
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script>
        $(document).ready(function () {
            // Ajax untuk daerah
            $('#prov').change(function () {
                const prov = $(this).val();
                $.get('cek_kabupaten_dropship.php', { prov_id: prov }, function (data) {
                    $('#kabupaten').html(data);
                });
            });

            $('#kabupaten').change(function () {
                const kab = $(this).val();
                $.get('cek_kecamatan_dropship.php', { kabupaten_id: kab }, function (data) {
                    $('#kecamatan').html(data);
                });
            });

            // Toggle button
            function toggleSubmitButton() {
                const checked = $('input[name="update[]"]:checked').length > 0;
                $('#submitBtn').prop('disabled', !checked);
            }

            // Checkbox handler
            $(document).on('change', 'input[name="update[]"]', function () {
                const total = $('input[name="update[]"]:not(:disabled)').length;
                const checked = $('input[name="update[]"]:checked').length;
                $('#checkAll').prop('checked', total === checked);
                toggleSubmitButton();
            });

            // Check All
            $('#checkAll').on('change', function () {
                const checked = $(this).is(':checked');
                $('input[name="update[]"]:not(:disabled)').prop('checked', checked);
                toggleSubmitButton();
            });

            toggleSubmitButton();
        });
    </script>
    <!-- END SCRIPT -->
</body>
</html>