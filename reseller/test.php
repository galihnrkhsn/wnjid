<?php
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';
    include "settingdatatables.php";

    $findUser = $koneksi->query("SELECT * FROM mitrareseller WHERE idmitrareseller='$idmitrareseller'");
    $queryUser = $findUser->fetch_assoc();
    $idmitrareseller=$_SESSION["idmitrareseller"];

    $idpoproduk = $_GET['id'];
    $invoice=$_GET['invoice']; 
    $query = "SELECT COUNT(*) as jumlah,
                        poproduk.idpoproduk,
                        poproduk.namapo,
                        poproduk.status 
                        FROM poproduk 
                        inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                        WHERE poproduk.idpoproduk='$idpoproduk' 
                        AND pomitra.idmitrareseller='$idmitrareseller'
                        AND pomitra.invoice = '$invoice'
                        ";
    $sql = mysqli_query($koneksi, $query);  
    $data = mysqli_fetch_array($sql);
        $sql_tgl_ubah = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'");
        $query_tgl_ubah = $sql_tgl_ubah->fetch_assoc();
        date_default_timezone_set('Asia/Jakarta');
        $today = date("d M Y");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content=""><title>Reseller | Wanoja</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5" align="center">
        <p align="center"><strong>SALES INVOICE</strong></p>
        <p align="center"><strong><?php echo $data['namapo']; ?></strong></p>
        <br>
        <p align="left">Nama Mitra: <?php echo $queryUser["namaagen"]; ?></p>
        <p align="left">Alamat: <?php echo $queryUser["alamat"]; ?></p>
        <p align="left">No Invoice: <?php echo $invoice; ?></p>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Ubah Qty</th>
                    <th>Qty</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                </tr>
                <?php
                $no = 1;
                $sql = mysqli_query($koneksi, "SELECT 
                                                pokategori.namakategori,
                                                podetail.variant,
                                                pomitra.idpomitra,
                                                pomitra.jumlah,
                                                pomitra.invoice,
                                                pomitra.total,
                                                podetail.harga 
                    FROM pomitra 
                    INNER JOIN pokategori ON pokategori.idpo = pomitra.idpo 
                    INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail                                        
                    WHERE pomitra.idmitrareseller = '$idmitrareseller' 
                    AND pomitra.idpoproduk = '$idpoproduk' 
                    AND pomitra.invoice = '$invoice'
                    ORDER BY podetail.idpodetail ASC");

                while ($data = mysqli_fetch_array($sql)) {
                    // Ambil semua data dari hasil eksekusi $sql
                ?>
                    <tr>
                        <td class="align-middle"><?php echo $no++; ?></td>
                        <form method="POST">
                            <input type="hidden" name="harga" value="<?php echo $data['harga']; ?>">
                            <input type="hidden" name="idpomitra" value="<?php echo $data['idpomitra']; ?>">
                            <input type="hidden" name="invoice" value="<?php echo $data['invoice']; ?>">
                            <td class="align-middle">
                                <input type="number" min="<?= $data['jumlah'] ?>" name="jmlh" style="width:100px;">
                                <button type="submit" class="btn btn-success" name="edit">Ubah</button>
                            </td>
                        </form>
                        <td class="align-middle"><?php echo $data['jumlah']; ?></td>
                        <td class="align-middle"><?php echo $data['variant']; ?></td>
                        <td class="align-middle"><?php echo $data['harga']; ?></td>
                        <td class="align-middle"><?php echo $data['total']; ?></td>
                    </tr>
                    <?php
                    if (isset($_POST["edit"])) {
                        include "koneksi.php";
                        $idpomitra = $_POST['idpomitra'];
                        $invoice = $_POST['invoice'];
                        $jmlh = $_POST["jmlh"];
                        $harga = $_POST["harga"];
                        $total = $jmlh * $harga;

                        $koneksi->query("UPDATE pomitra SET jumlah='$jmlh', total='$total' WHERE idpomitra='$idpomitra';");
                        // $koneksi->query("UPDATE pomitra SET tgl=NOW() WHERE invoice='$invoice';");
                        echo "<script>alert('Data berhasil diubah');</script>";
                        echo "<script>location='ubahpo.php?id=$idpoproduk&invoice=$invoice';</script>";
                    } elseif (isset($_POST["hapus"])) {
                        include "koneksi.php";
                        $idpomitra = $_POST['idpomitra'];
                        $koneksi->query("DELETE FROM pomitra WHERE idpomitra='$idpomitra';");
                        echo "<script>alert('Data berhasil dihapus');</script>";
                        echo "<script>location='ubahpo.php?id=$idpo&invoice=$invoice';</script>";
                    }
                    $jumlah += $data['total'];
                    ?>
                <?php
                }
                ?>
            </table>
            <br>
            <!-- <p align="left">Qty: <?php // echo $sum; ?> </p> -->
            <!-- <p align="right">JUMLAH: Rp. <?php echo number_format($jumlah); ?> </p>
            <?php $diskon = 35 / 100 * $jumlah;
            $subtotal = $jumlah - $diskon; ?>
            <p align="right">Diskon: Rp. <?php echo number_format($diskon); ?> </p><br>
            <p align="right">TOTAL: Rp. <?php echo number_format($subtotal); ?> </p> -->
            <?php if ($idpoproduk == '282') : ?>
                <a class="btn btn-primary" href="datapom3.php?id=<?php echo $idpoproduk; ?>&invoice=<?php echo $invoice; ?>">Simpan</a>
            <?php else : ?>
                <a class="btn btn-primary" href="datapo.php?id=<?php echo $idpoproduk; ?>&invoice=<?php echo $invoice; ?>">Simpan</a>
            <?php endif; ?>
            <br><br>
        </div>
    </div>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-0 mt-5">
            <h4 style="color: #153448;">Input Variant</h4>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div id="variantContainer">
                <div class="row mx-0" id="variantForm_0">
                    <div class="col-12">
                        <h6 class="badge text-bg-info text-uppercase">Variant 1</h6>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="variant" class="d-flex align-items-center">Produk<p class="text-danger p-0 m-0 ml-1">*</p></label>
                            <select name="variant[]" class="form-control form-control-sm" id="variantSelect_0" onchange="updateHiddenProductInput(this, 0)">
                                <option value="" disabled selected>Silahkan pilih variant</option>
                                <?php
                                    $ambil = $koneksi->query(" SELECT DISTINCT podetail.variant, podetail.idpodetail, pomitra.custom
                                                                FROM poproduk  
                                                                INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                                INNER JOIN podetail ON pokategori.idpo = podetail.idpo 
                                                                LEFT JOIN pomitra ON pomitra.idpodetail = podetail.idpodetail AND pomitra.invoice = '$invoice'
                                                                WHERE poproduk.idpoproduk = '$idpoproduk'
                                                                AND podetail.variant NOT LIKE '%Custom%'
                                                                AND pomitra.idpodetail IS NULL
                                                                ORDER BY podetail.idpodetail ASC;
                                                                ");
                                    if ($ambil) {
                                        while ($data = $ambil->fetch_assoc()) {
                                            $custom = $data['custom'];
                                            ?>
                                            <option value="<?= $data['idpodetail']; ?>">
                                                <?= $data['variant']; ?><?= !empty($custom) ? " | $custom" : ""; ?> | <!-- Menampilkan stok -->
                                            </option>
                                            <?php
                                        }
                                    } else {
                                        echo "Error executing query: " . $koneksi->error;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="hiddenProductInput_0" name="hiddenProductInput[]" value="">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="jumlah" class="d-flex align-items-center">Jumlah<p class="text-danger p-0 m-0 ml-1">*</p></label>
                            <input type="number" class="form-control form-control-sm" placeholder="Masukan Jumlah" name="jumlah[]" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <button type="button" class="btn btn-secondary btn-sm mb-3" id="buttonAddVariant">Tambah Variant</button>
            </div>
            <div class="col-sm-12">
                <button class="btn btn-primary btn-sm" type="submit" name="insert-variant">Tambah Produk Variant</button>
            </div>
            <div class="col-sm-12">
                <hr />
            </div>
        </form>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <?php
        if (isset($_POST["insert-variant"])) {
            $processed = false;
            $koneksi->begin_transaction(); // Mulai transaksi
            try {
                foreach ($_POST['hiddenProductInput'] as $key => $index) {
                    $variant = mysqli_real_escape_string($koneksi, $_POST['variant'][$key]);
                    $jumlah = mysqli_real_escape_string($koneksi, $_POST['jumlah'][$key]);
                    date_default_timezone_set('Asia/Jakarta');
                    $waktu = date("H:i:s");

                    if ($jumlah > 0) {
                        $sql = "SELECT * FROM podetail WHERE idpodetail = '$variant'";
                        $query = $koneksi->query($sql);
                        $podetail = $query->fetch_assoc();
                        // Jika data belum ada, lakukan insert
                        $harga = $podetail['harga'];
                        $idpo = $podetail['idpo'];
                        $total = $jumlah * $harga;
                        $insertPomitra = $koneksi->query("INSERT INTO pomitra (idpomitra, idmitrareseller, idpoproduk, idpo, idpodetail, jumlah, total, invoice, status, tgl, waktu) VALUES
                        (null, '$idmitrareseller', '$idpoproduk', '$idpo', '$variant', '$jumlah', '$total', '$invoice', 'Belum DP', NOW(), '$waktu')");
                        if (!$insertPomitra) {
                            throw new Exception("Error executing insert into Pomitra: " . $koneksi->error);
                        }
                    } else {
                        throw new Exception("Jumlah harus lebih dari 0.");
                    }
                }

                $koneksi->commit(); // Commit transaksi
                $processed = true;
            } catch (Exception $e) {
                $koneksi->rollback(); // Rollback transaksi jika ada error
                echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
            }

            if ($processed) {
                echo "<script>alert('Data berhasil dikirim');</script>";
                echo "<script>location='datapo.php?id=$idpoproduk&invoice=$invoice';</script>";
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
    <script type="text/javascript">
        const buttonAddVariant = document.getElementById("buttonAddVariant");
        const variantContainer = document.getElementById("variantContainer");
        let variantFormCount = 0;

        buttonAddVariant.addEventListener('click', function() {
            variantFormCount++;

            const variantForm = document.createElement('div');
            variantForm.className = "row mx-0";
            variantForm.id = `variantForm_${variantFormCount}`;
            variantForm.innerHTML = `
            <div class="col-12">
                <h6 class="badge text-bg-info text-uppercase">Variant ${variantFormCount + 1}</h6>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="variant" class="d-flex align-items-center">Produk<p class="text-danger p-0 m-0 ml-1">*</p></label>
                    <select name="variant[]" class="form-control form-control-sm" id="variantSelect_${variantFormCount}" onchange="updateHiddenProductInput(this, ${variantFormCount})">
                        <option value="" disabled selected>Silahkan pilih variant</option>
                        <?php
                            $ambil = $koneksi->query(" SELECT DISTINCT podetail.variant, podetail.idpodetail, pomitra.custom
                                                        FROM poproduk  
                                                        INNER JOIN pokategori ON poproduk.idpoproduk = pokategori.idpoproduk
                                                        INNER JOIN podetail ON pokategori.idpo = podetail.idpo 
                                                        LEFT JOIN pomitra ON pomitra.idpodetail = podetail.idpodetail AND pomitra.invoice = '$invoice'
                                                        WHERE poproduk.idpoproduk = '$idpoproduk'
                                                        AND podetail.variant NOT LIKE '%Custom%'
                                                        AND pomitra.idpodetail IS NULL
                                                        ORDER BY podetail.idpodetail ASC;
                                                        ");
                            if ($ambil) {
                                while ($data = $ambil->fetch_assoc()) {
                                    $custom = $data['custom'];
                                    ?>
                                    <option value="<?= $data['idpodetail']; ?>">
                                        <?= $data['variant']; ?><?= !empty($custom) ? " | $custom" : ""; ?>
                                    </option>
                                    <?php
                                }
                            } else {
                                echo "Error executing query: " . $koneksi->error;
                            }
                        ?>
                    </select>
                </div>
            </div>
            <input type="hidden" id="hiddenProductInput_${variantFormCount}" name="hiddenProductInput[]" value="">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="jumlah" class="d-flex align-items-center">Jumlah<p class="text-danger p-0 m-0 ml-1">*</p></label>
                    <input type="number" class="form-control form-control-sm" placeholder="Masukan Jumlah" name="jumlah[]" required>
                </div>
            </div>
            `;
            variantContainer.appendChild(variantForm);
        });

        function updateHiddenProductInput(selectElement, index) {
            const hiddenProductInput = document.getElementById(`hiddenProductInput_${index}`);
            hiddenProductInput.value = selectElement.value;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>