<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice    = $_GET['invoice']; 
    $idadmin    = $_SESSION["idadmin"];
    $query      = "SELECT COUNT(*) as jumlah,
                        poproduk.idpoproduk,
                        poproduk.namapo,
                        poproduk.status 
                    FROM poproduk 
                    inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                    WHERE poproduk.idpoproduk='$idpoproduk' 
                    AND pomitra.idmitra='$idadmin'
                    AND pomitra.invoice = '$invoice'
                ";
    $sql    = mysqli_query($koneksi, $query);  
    $data   = mysqli_fetch_array($sql);

    $findUser   = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin='$idadmin'");
    $queryUser  = $findUser->fetch_assoc(); 

    $resultProduk           = $koneksi->query("SELECT * FROM poproduk INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk WHERE poproduk.idpoproduk = '$idpoproduk'");
    $excluded_sarung    = [6356, 6357, 6358, 6359, 6360, 6366, 6367, 6368, 6369, 6370, 6371, 6372, 6373, 6540, 6541, 6542, 6543, 6544, 6550, 6551, 6552, 6553, 6554, 6555, 6556, 6557,];
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
    <div class="container mt-5 justify-content-center">
        <p align="center"><strong>SALES INVOICE</strong></p>
        <p align="center"><strong><?php echo $data['namapo']; ?></strong></p><br>
        <p align="left">Nama Mitra  : <?php echo $queryUser["namamitra"]; ?></p>
        <p align="left">Alamat  : <?php echo $queryUser["alamat"]; ?> </p>
        <p align="left">No Invoice  : <?php echo $invoice; ?></p>
        
        <?php if ($idpoproduk != 181 && $idpoproduk != 182 && $idpoproduk != 183): ?>
            <div class="border border-dark">
                <b>Sisa Stock:</b>
                <div class="row col-12">
                    <?php
                    $sql = "SELECT * FROM pokategori WHERE idpoproduk='$idpoproduk' ORDER BY namakategori";
                    $query = $koneksi->query($sql);
                    while ($stok = $query->fetch_assoc()) {
                    ?>
                        <?php if (!in_array($stok['idpo'], $excluded_sarung)) :?>
                            <div class="col-2">
                                <?php echo $stok['namakategori']; ?>
                            </div>
                            <div class="col-2">
                                (<?php echo $stok['stok']; ?>)
                            </div>
                            <br>
                        <?php endif; ?>
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>         

        <div class="table-responsive mt-3">
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
                                                pomitra.idpo,
                                                pomitra.jumlah,
                                                pomitra.invoice,
                                                pomitra.total,
                                                podetail.harga 
                                                FROM pomitra 
                                                INNER JOIN pokategori ON pokategori.idpo=pomitra.idpo 
                                                INNER JOIN podetail ON podetail.idpodetail=pomitra.idpodetail 
                                                WHERE pomitra.idmitra='$idadmin' 
                                                AND pomitra.idpoproduk='$idpoproduk'
                                                AND pomitra.invoice = '$invoice'
                                                ORDER BY podetail.idpodetail");

                while ($data = mysqli_fetch_array($sql)) { 
                ?>
                    <tr>
                        <td class="align-middle"><?php echo $no++; ?></td>
                        <form method="POST">
                            <input type="hidden" name="harga" value="<?php echo $data['harga']; ?>">
                            <input type="hidden" name="idpo" value="<?php echo $data['idpo']; ?>">
                            <input type="hidden" name="idpomitra" value="<?php echo $data['idpomitra']; ?>">
                            <input type="hidden" name="jumlahsebelum" value="<?php echo $data['jumlah']; ?>">
                            <td class="align-middle">
                                <input type="number" min="0" name="jmlh" style="width: auto;">
                                <br>
                                <button type="submit" class="btn btn-primary" name="tambah">+</button>
                                <button type="submit" class="btn btn-danger" name="kurang">-</button>
                            </td>
                        </form>
                        <td class="align-middle"><?php echo $data['jumlah']; ?></td>
                        <td class="align-middle"><?php echo $data['variant']; ?></td>
                        <td class="align-middle">
                            <?php if ($data['jumlah'] == 0): ?>
                                0
                            <?php else: ?>
                                <?php echo $data['harga']; ?>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle"><?php echo $data['total']; ?></td>
                        <?php
                        $jumlah = $jumlah + $data['total'];
                    }
                ?>
            </table><br>
            <a class="btn btn-primary" href="datapo.php?id=<?php echo $idpoproduk ?>&invoice=<?= $invoice; ?>">Simpan</a>
            <br><br>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP SYNTAK -->
    <?php
        include "koneksi.php";
        
        if (isset($_POST["tambah"])) {
            $idpomitra  = $_POST['idpomitra'];
            $idpo       = $_POST["idpo"];
            $jmlh       = $_POST["jmlh"];
            $harga      = $_POST["harga"];
            $total      = $jmlh * $harga;
            
            $sql        = "SELECT stok FROM pokategori WHERE idpo = '$idpo'";
            $query      = $koneksi->query($sql);
            $sisa       = $query->fetch_assoc();
            
            $sql_pomitra_qty    = "SELECT jumlah FROM pomitra WHERE invoice = '$invoice'";
            $query_qty          = $koneksi->query($sql_pomitra_qty);
            $current_qty_row    = $query_qty->fetch_assoc();
            $current_qty        = $current_qty_row['jumlah'];

            $totalQty_setelah_update = $current_qty + $jmlh;
            if ($idpoproduk == '486' && $totalQty_setelah_update > 2) {
                echo "<script>alert('Total Kuantitas untuk produk ini tidak boleh lebih dari 2 setelah penambahan!');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=$invoice';</script>"; 
                exit();
            }

            if ($sisa['stok'] >= $jmlh) {
                $koneksi->query("UPDATE pomitra SET jumlah=jumlah+'$jmlh', total=jumlah*'$harga' WHERE idpomitra='$idpomitra'");
                $koneksi->query("UPDATE pokategori SET stok=stok-'$jmlh' WHERE idpo='$idpo'");
                echo "<script>alert('data berhasil diubah');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=$invoice';</script>";
            } else {
                echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=$invoice';</script>";
            }
        } else if (isset($_POST["kurang"])) {
            $idpomitra = $_POST['idpomitra'];
            $idpo = $_POST["idpo"];
            $jmlh = $_POST["jmlh"];
            $harga = $_POST["harga"];
            $total = $jmlh * $harga;
            $jumlahsebelum = $_POST["jumlahsebelum"];
            $subjumlah = $jumlahsebelum - $jmlh;
            
            if ($subjumlah < 0) {
                echo "<script>alert('Stok kami tidak mencukupi, silahkan revisi pesanan anda sesuaikan dengan stok');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=$invoice';</script>";
            } else {
                $koneksi->query("UPDATE pomitra SET jumlah=jumlah-'$jmlh' WHERE idpomitra='$idpomitra'");
                $koneksi->query("UPDATE pomitra SET total=jumlah*'$harga' WHERE idpomitra='$idpomitra'");
                $koneksi->query("UPDATE pokategori SET stok=stok+'$jmlh' WHERE idpo='$idpo'");
                echo "<script>alert('data berhasil diubah');</script>";
                echo "<script>location='ubahpostok?id=$idpoproduk&invoice=$invoice';</script>";
            }
        }
    ?>
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

                        $kategori = "SELECT * FROM pokategori WHERE idpoproduk = '$idpoproduk'";
                        $queryKategori = $koneksi->query($kategori);
                        $pokategori = $queryKategori->fetch_assoc();
                        
                        if ($pokategori['stok'] >= $jumlah) {
                            // Kurangi stok di tabel kategori
                            $updateStok = $koneksi->query("UPDATE pokategori SET stok = stok - '$jumlah' WHERE idpo = '{$podetail['idpo']}'");
                            if (!$updateStok) {
                                throw new Exception("Error updating stock in pokategori: " . $koneksi->error);
                            }
        
                            // Hitung total harga berdasarkan jumlah baru
                            $harga = $podetail['harga'];
                            $total = $jumlah * $harga;
        
                            // Insert data ke tabel pomitra
                            $insertPomitra = $koneksi->query("INSERT INTO pomitra (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, total, invoice, status, tgl, waktu) VALUES
                            (null, '$idadmin', '$idpoproduk', '{$podetail['idpo']}', '$variant', '$jumlah', '$total', '$invoice', 'Belum DP', NOW(), '$waktu')");
                            if (!$insertPomitra) {
                                throw new Exception("Error executing insert into Pomitra: " . $koneksi->error);
                            }
                        } else {
                            throw new Exception("Stok tidak mencukupi.");
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
    <!-- PHP SYNTAK END -->

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
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
    <!-- END SCRIPT -->
</body>
</html>