<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include "settingdatatables.php";

    $idmitraagen=$_SESSION["idmitraagen"];
    $findUser = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen='$idmitraagen'");
    $queryUser = $findUser->fetch_assoc(); 

    $idpoproduk = $_GET['id'];
    $invoice=$_GET['invoice']; 
    $query = "SELECT COUNT(*) as jumlah,
                        poproduk.idpoproduk,
                        poproduk.namapo,
                        poproduk.status 
                        FROM poproduk 
                        inner join pomitra on poproduk.idpoproduk=pomitra.idpoproduk 
                        WHERE poproduk.idpoproduk='$idpoproduk' 
                        AND pomitra.idmitraagen='$idmitraagen'
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
    <meta name="author" content="">
    <title>Agen | WNJ.ID</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar.php'; ?>
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
                    WHERE pomitra.idmitraagen = '$idmitraagen' 
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
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>