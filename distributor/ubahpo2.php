<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice=$_GET['invoice']; 
    $idadmin=$_SESSION["idadmin"];
    $query = "SELECT COUNT(*) as jumlah,
                        poproduk.idpoproduk,
                        poproduk.namapo,
                        poproduk.status 
                        FROM poproduk 
                        inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                        WHERE poproduk.idpoproduk='$idpoproduk' 
                        AND pomitra.idmitra='$idadmin'
                        AND pomitra.invoice = '$invoice'
                        ";
    $sql = mysqli_query($koneksi, $query);  
    $data = mysqli_fetch_array($sql);
    $sql_tgl_ubah = $koneksi->query("SELECT * FROM bukapo WHERE idpoproduk = '$idpoproduk'");
    $query_tgl_ubah = $sql_tgl_ubah->fetch_assoc();
    date_default_timezone_set('Asia/Jakarta');
    $today = date("d M Y");

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
      <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
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
                                                        pomitra.jumlah
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
                            $idpodetail = $dataproduk['idpodetail'];
                            $total = $dataproduk['harga'] * $dataproduk['jumlah'];
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" class="form-control form-control-sm" value="<?= $dataproduk['idpomitra'] ?>" name="idpomitra" readonly>
                                    <input type="hidden" class="form-control form-control-sm" value="<?= $dataproduk['harga'] ?>" name="harga" readonly>
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
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-1">
            <a href="datapo.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>" class="btn btn-primary btn-sm">Simpan</a>
        </div>
    </div>

    <?php
        if (isset($_POST['update'])) {
            try {
                $idpomitra = $_POST['idpomitra'];
                $qty = $_POST['qty'];
                $harga = $_POST['harga'];
                $total = $qty * $harga;

                $sql = $koneksi->query("UPDATE pomitra SET jumlah = '$qty', total = '$total' WHERE idpomitra = '$idpomitra'");
                if ($sql) {
                    echo "
                        <script>
                            alert('Data berhasil diubah!')
                            location='ubahpo.php?id=$idpoproduk&invoice=$invoice'
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            alert('Data gagal diubah!')
                            location='ubahpo.php?id=$idpoproduk&invoice=$invoice'
                        </script>
                    ";
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