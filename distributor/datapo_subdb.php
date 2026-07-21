<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idadmin = $_SESSION["idadmin"];
    $data_user = $_GET["subdb"];
    $query = $koneksi->query("SELECT * FROM pomitra WHERE invoice LIKE '$invoice'");
    $data = $query->fetch_assoc();
    $status = $data['status'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distributor | Wanoja</title>
</head>
<body>
    <?php include "assets/components/Navbar/navbar.php"; ?>

    <div class="container mt-3">
        <h2 class="text-center mb-4">SALES INVOICE</h2>
        <p class="text-center"><strong><?= $data['namapo']; ?></strong></p><br>
        <p class="text-left">Nama Mitra  : <?= $data_user; ?> </p>
        <p class="text-left">No Invoice  : <?= $invoice; ?> </p>
        <p class="text-left">Status      : <?= $data['status']; ?></p>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = $koneksi->query("SELECT 
                                                        poproduk.namapo,
                                                        pokategori.namakategori,
                                                        podetail.variant,
                                                        pomitra.idpomitra,
                                                        pomitra.jumlah,
                                                        pomitra.invoice,
                                                        pomitra.total,
                                                        podetail.harga
                                                    FROM
                                                        poproduk
                                                            INNER JOIN
                                                        pokategori
                                                            INNER JOIN
                                                        podetail
                                                            INNER JOIN
                                                        pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                                            AND pokategori.idpo = pomitra.idpo
                                                            AND podetail.idpodetail = pomitra.idpodetail
                                                    WHERE
                                                        pomitra.invoice = '$invoice'
                                                            AND pomitra.jumlah > 0
                                                ");
                        $no = 1;
                        while ($data = $query->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['variant'] ?></td>
                            <td>Rp. <?= number_format($data['harga']); ?></td>
                            <td><?= $data['jumlah'] ?></td>
                            <td>Rp. <?= number_format($data['harga'] * $data['jumlah']); ?></td>
                        </tr>
                    <?php
                            $jumlah += $data['jumlah'] * $data['harga'];
                            $sum += $data['jumlah'];
                        }
                    ?>
                </tbody>
            </table>

            <table border="0" style="float: right;">
                <tbody style="float: right;">
                    <tr>
                        <th width="200">Total Qty</th>
                        <td width="50">:</td>
                        <td style="text-align: right"><?= $sum ?></td>
                    </tr>
                    <tr>
                        <th width="200">Total Bayar</th>
                        <td width="50">:</td>
                        <td style="text-align: right">Rp. <?= number_format($jumlah) ?></td>
                    </tr>
                    <tr>
                        <?php
                            $parts = explode('-', $invoice);
                            $id = $parts[1];
                            $query = $koneksi->query("SELECT 
                                                            mitraagen.idmitraagen,
                                                            mitramarketer.idmitramarketer,
                                                            mitrareseller.idmitrareseller
                                                        FROM
                                                            pomitra
                                                                LEFT JOIN
                                                            mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen
                                                                LEFT JOIN
                                                            mitrareseller ON pomitra.idmitrareseller = mitrareseller.idmitrareseller
                                                                LEFT JOIN
                                                            mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer
                                                                LEFT JOIN
                                                            admin_mitra ON mitraagen.idadmin = admin_mitra.idadmin
                                                                OR mitrareseller.idadmin = admin_mitra.idadmin
                                                                OR mitramarketer.idadmin = admin_mitra.idadmin
                                                        WHERE
															(mitraagen.idmitraagen = '$id'
                                                            OR mitrareseller.idmitrareseller = '$id'
                                                            OR mitramarketer.idmitramarketer = '$id')
                                                    ");
                            $data = $query->fetch_assoc();
                            if (!empty($data['idmitraagen'])) {
                                $diskon = "25%";
                                $total_diskon = $jumlah * 25/100;
                            } elseif (!empty($data['idmitrareseller']))  {
                                $diskon = "15%";
                                $total_diskon = $jumlah * 15/100;
                            } elseif (!empty($data['idmitramarketer'])) {
                                $diskon = "10%";
                                $total_diskon = $jumlah * 10/100;
                            } else {
                                $diskon = "Tidak ada diskon!";
                            }
                        ?>
                        <th width="200">Diskon Sub DB <?= $diskon; ?></th>
                        <td width="50">:</td>
                        <td style="text-align: right">Rp. <?= number_format($total_diskon) ?></td>
                    </tr>

                    <tr>
                        <th width="200">Total Bayar</th>
                        <td width="50">:</td>
                        <td style="text-align: right">Rp. <?= number_format($jumlah - $total_diskon) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-center my-3">
            <?php if ($status === "Belum Acc DB") : ?>
                <form method="post">
                    <button type="submit" class="btn btn-primary btn-sm" name="approve">Approve DB</button>
                    <button type="submit" class="btn btn-danger btn-sm" name="cancel">Cancel Order</button>
                </form>
            <?php else : ?>
                <p class="px-2 py-1 bg-success text-white rounded">Data sudah di Approve DB</p>
            <?php endif; ?>
        </div>
    </div>

    <?php
        if(isset($_POST["approve"])){
            $invoice = $_GET['invoice'];
            $namamitra = $_GET['subdb'];
            $koneksi->query("UPDATE pomitra SET status='Approve DB' WHERE invoice='$invoice'");
            echo "<script>alert('PO Sub DB Anda telah di Approve');</script>";
            echo "<script>location='datapo_subdb.php?id=$idpoproduk&subdb=$namamitra&invoice=$invoice';</script>";
        }

        if(isset($_POST["cancel"])){
            $invoice = $_GET['invoice'];
            $namamitra = $_GET['subdb'];
            $koneksi->query("DELETE FROM pomitra WHERE invoice='$invoice'");
            echo "<script>alert('PO Sub DB telah di Batalkan');</script>";
            echo "<script>location='datapo_subdb.php?id=$idpoproduk&subdb=$namamitra&invoice=$invoice';</script>";
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>