<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';

    $idpoproduk         = $_GET['id'];
    $invoice            = $_GET['invoice']; 
    $idmitramarketer    = $_SESSION["idmitramarketer"];
    $query              = "SELECT COUNT(*) as jumlah,
                                poproduk.idpoproduk,
                                poproduk.namapo,
                                poproduk.status 
                            FROM poproduk 
                            inner join pomitra on poproduk.idpoproduk = pomitra.idpoproduk 
                            WHERE poproduk.idpoproduk = '$idpoproduk' 
                            AND pomitra.idmitramarketer = '$idmitramarketer'
                            AND pomitra.invoice = '$invoice'
                        ";
    date_default_timezone_set('Asia/Jakarta');
    $sql            = mysqli_query($koneksi, $query);  
    $data           = mysqli_fetch_array($sql);

    $sql_tgl_ubah   = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'");
    $query_tgl_ubah = $sql_tgl_ubah->fetch_assoc();
    $today          = date("d M Y");

    $findUser       = $koneksi->query("SELECT namaagen FROM mitramarketer WHERE idmitramarketer = '$idmitramarketer'");
    $queryUser      = $findUser->fetch_assoc(); 
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
      <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <title>Agen | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-3" align="center">
        <h4 class="text-uppercase fw-semibold">Invoice</h4>
        <h5><?= $data['namapo'] ?></h5>
    </div>

    <div class="container" style="font-size: .875rem">
        <p class="mb-1">Nama Mitra: <?= $queryUser['namamitra'] ?></p>
        <p class="mb-3">No Invoice: <?= $invoice ?></p>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Ubah Qty</th>
                        <th>QTY</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
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
                        $no = 1;
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
                                        <input type="number" class="form-control form-control-sm" name="qty" placeholder="Jumlah QTY" value="<?= $idpoproduk == 489 ? 10 : 0 ?>" min="<?= $idpoproduk == 489 ? 10 : 0 ?>">
                                        <button type="submit" name="update" class="mx-sm-0 mx-lg-2 btn btn-success btn-sm">Ubah</button>
                                    </div>
                                </form>
                            </td>
                            <td><?= $dataproduk['jumlah'] ?></td>
                            <td><?= $dataproduk['variant'] ?></td>
                            <td>Rp. <?= number_format($dataproduk['harga']) ?></td>
                            <td>Rp. <?= number_format($total) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-1">
            <?php if ($idpoproduk == '405' || $idpoproduk == '406' || $idpoproduk == '407') : ?>
                <a href="datapokolibri3.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>" class="btn btn-primary btn-sm">Simpan</a>
            <?php else : ?>
                <a href="datapo.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>" class="btn btn-primary btn-sm">Simpan</a>
            <?php endif; ?>
            <br><br>
        </div>
    </div>

    <?php
        if (isset($_POST['update'])) {
            try {
                $idpomitra  = $_POST['idpomitra'];
                $qty        = $_POST['qty'];

                if ($qty < 10) {
                    echo "<script>alert('QTY Minimal 10!')</script>";
                    echo "<script>location='ubahpo_mandiri.php?id=$idpoproduk&invoice=$invoice';</script>";
                }
                
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
                                location='ubahpo_mandiri.php?id=$idpoproduk&invoice=$invoice'
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
                                        location='ubahpo_mandiri.php?id=$idpoproduk&invoice=$invoice'
                                    </script>
                                ";
                            } else {
                                echo "
                                    <script>
                                        alert('Gagal memperbarui data di pomitra.')
                                        location='ubahpo_mandiri.php?id=$idpoproduk&invoice=$invoice'
                                    </script>
                                ";
                            }
                        } else {
                            echo "
                                <script>
                                    alert('Stok tidak mencukupi.')
                                    location='ubahpo_mandiri.php?id=$idpoproduk&invoice=$invoice'
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
                                location='ubahpo_mandiri.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Gagal memperbarui data di pomitra.')
                                location='ubahpo_mandiri.php?id=$idpoproduk&invoice=$invoice'
                            </script>
                        ";
                    }
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html> 