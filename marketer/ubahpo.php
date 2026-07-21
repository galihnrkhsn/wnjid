<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
    include "settingdatatables.php";
    $idmitramarketer    = $_SESSION["idmitramarketer"];
    $findUser           = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer='$idmitramarketer'");
    $queryUser          = $findUser->fetch_assoc();

    $idpoproduk         = $_GET['id'];
    $invoice            = $_GET['invoice']; 
    $query              = "SELECT COUNT(*) as jumlah,
                                poproduk.idpoproduk,
                                poproduk.namapo,
                                poproduk.status 
                                FROM poproduk 
                            inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                            WHERE poproduk.idpoproduk = '$idpoproduk' 
                            AND pomitra.idmitramarketer = '$idmitramarketer'
                            AND pomitra.invoice = '$invoice'
                            ";
    $sql                = mysqli_query($koneksi, $query);  
    $data               = mysqli_fetch_array($sql);

    date_default_timezone_set('Asia/Jakarta');
    $sql_tgl_ubah       = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'");
    $query_tgl_ubah     = $sql_tgl_ubah->fetch_assoc();
    $today              = date("d M Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer | Wanoja</title>
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
                $query = $koneksi->query("SELECT 
                                                podetail.idpodetail,
                                                podetail.variant,
                                                podetail.idpo,
                                                podetail.harga,
                                                pomitra.jumlah,
                                                pomitra.idpomitra
                                            FROM
                                                podetail
                                                INNER JOIN pokategori ON pokategori.idpo = podetail.idpo
                                                LEFT JOIN pomitra ON pomitra.idpodetail = podetail.idpodetail
                                                    AND pomitra.idpoproduk = '$idpoproduk'
                                                    AND pomitra.invoice = '$invoice'
                                            WHERE
                                                pokategori.idpoproduk = '$idpoproduk'
                                        ");

                    while ($dataproduk = $query->fetch_assoc()) {
                        $idpodetail     = $dataproduk['idpodetail'];
                        $idpo           = $dataproduk['idpo'];
                        $total          = $dataproduk['harga'] * $dataproduk['jumlah'];
                ?>
                    <tr>
                    <td><?= $no++ ?></td>
                            <td>
                                <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" class="form-control form-control-sm" value="<?= $dataproduk['idpomitra'] ?>" name="idpomitra" readonly>
                                    <input type="hidden" class="form-control form-control-sm" value="<?= $dataproduk['harga'] ?>" name="harga" readonly>
                                    <input type="hidden" class="form-control form-control-sm" value="<?= $dataproduk['idpo'] ?>" name="idpo" readonly>
                                    <div class="d-sm-flex align-items-center">
                                        <input type="number" class="form-control form-control-sm" name="qty" placeholder="Jumlah QTY" value="0" min="0">
                                        <button type="submit" name="update" class="mx-sm-0 mx-lg-2 btn btn-success btn-sm">Ubah</button>
                                    </div>
                                </form>
                            </td>
                            <td><?= $dataproduk['jumlah'] ?></td>
                            <td><?= $dataproduk['variant'] ?></td>
                            <td>Rp. <?= number_format($dataproduk['harga']) ?></td>
                            <td>Rp. <?= number_format($total) ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>

    <div class="container mt-5 pb-5 d-flex justify-content-center">
        <?php if ($idpoproduk == '282') : ?>
            <a class="btn btn-primary" href="datapom3.php?id=<?php echo $idpoproduk; ?>&invoice=<?php echo $invoice; ?>">Simpan</a>
        <?php elseif ($idpoproduk == '331') : ?>
            <a class="btn btn-primary" href="datapocustom2.php?id=<?php echo $idpoproduk; ?>&invoice=<?php echo $invoice; ?>">Kembali Ke Invoice</a>
        <?php else : ?>
            <a class="btn btn-primary" href="datapo.php?id=<?php echo $idpoproduk; ?>&invoice=<?php echo $invoice; ?>">Simpan</a>
        <?php endif; ?>
    </div>

    <?php
        if (isset($_POST['update'])) {
            try {
                $idpomitra  = $_POST['idpomitra'];
                $qty        = $_POST['qty'];
                $idpo       = $_POST['idpo'];
                $harga      = $_POST['harga'];
                $total      = $qty * $harga;

                $jenisPO    = $koneksi->query("SELECT jenis_po FROM bukapo WHERE idpoproduk = '$idpoproduk'");
                $rowPO      = $jenisPO->fetch_assoc();
                $jenis_po   = $rowPO['jenis_po'];

                // Ambil data jumlah lama dari pomitra
                $queryOld       = $koneksi->query("SELECT jumlah FROM pomitra WHERE idpomitra = '$idpomitra'");
                $dataOld        = $queryOld->fetch_assoc();
                $jumlah_lama    = $dataOld['jumlah'];

                // Ambil stok dari tabel podetail
                $queryStok      = $koneksi->query("SELECT stok FROM pokategori WHERE idpo = '$idpo'");
                $dataStok       = $queryStok->fetch_assoc();
                $stok           = $dataStok['stok'];

                // Hitung selisih jumlah baru dan lama
                $difference     = $qty - $jumlah_lama;

                // Perbarui stok berdasarkan selisih
                $stok_baru      = $stok - $difference;

                if ($jenis_po == 'PO dengan Stok') {
                    if ($stok_baru < 0) {
                        echo "
                            <script>
                                alert('Stok tidak mencukupi! Perubahan dibatalkan.')
                                location='ubahpo.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    } else {
                        // Update stok di podetail
                        $updateStok = $koneksi->query("UPDATE pokategori SET stok = '$stok_baru' WHERE idpo = '$idpo'");
    
                        if ($updateStok) {
                            // Update jumlah dan total di pomitra
                            $updatePomitra = $koneksi->query("UPDATE pomitra SET jumlah = '$qty', total = '$total' WHERE idpomitra = '$idpomitra'");
                            if ($updatePomitra) {
                                echo "
                                    <script>
                                        alert('Data berhasil diubah dan stok diperbarui!')
                                        location='ubahpo.php?id=$idpoproduk&invoice=$invoice'
                                    </script>
                                ";
                            } else {
                                echo "
                                    <script>
                                        alert('Gagal memperbarui data di pomitra.')
                                        location='ubahpo.php?id=$idpoproduk&invoice=$invoice'
                                    </script>
                                ";
                            }
                        } else {
                            echo "
                                <script>
                                    alert('Stok tidak mencukupi.')
                                    location='ubahpo.php?id=$idpoproduk&invoice=$invoice'
                                </script>
                            ";
                        }
                    }
                } else {
                    $updatePomitra = $koneksi->query("UPDATE pomitra SET jumlah = '$qty', total = '$total' WHERE idpomitra = '$idpomitra'");
                    if ($updatePomitra) {
                        echo "
                            <script>
                                alert('Data berhasil diubah dan stok diperbarui!')
                                location='ubahpo.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Gagal memperbarui data di pomitra.')
                                location='ubahpo.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    }
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>