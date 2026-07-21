<?php

// Set headers for Excel export
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=Data_Produk.xls");

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

if (!isset($_SESSION["administrator"])) {
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    header('location:login.php');
    exit();
}

// Turn on output buffering
ob_start();
?>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Berat</th>
            <th>Kategori</th>
            <th>Stock</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1; // Initialize $no
        if ($idkategori) {
            $dataproduk = $koneksi->query("SELECT kategori.namakategori,
                                                produk.idproduk,
                                                produk.berat,
                                                produk.namaproduk,
                                                produk.harga,
                                                produk.tgl,
                                                produk.stock,
                                                produk.foto  
                                            FROM produk 
                                            INNER JOIN kategori ON produk.idkategori=kategori.idkategori
                                            ORDER BY produk.namaproduk ASC");

            while ($tampilkan = $dataproduk->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $tampilkan['namaproduk']; ?></td>
                    <td><?php echo $tampilkan['harga']; ?></td>
                    <td><?php echo $tampilkan['berat']; ?></td>
                    <td><?php echo $tampilkan['namakategori']; ?></td>
                    <td><?php echo $tampilkan['stock']; ?></td>
                    <td><?php echo $tampilkan['tgl']; ?></td>
                </tr>
                <?php
            }
        } else {
            $queryProduk = $koneksi->query("SELECT pkategori.namakategori,
                                                kategori.namakategori AS grrade,
                                                produk.idproduk,
                                                produk.berat,
                                                produk.status,
                                                produk.namaproduk,
                                                produk.harga,
                                                produk.tgl,
                                                produk.stock,
                                                produk.foto 
                                            FROM produk 
                                            LEFT JOIN kategori ON produk.idkategori=kategori.idkategori 
                                            LEFT JOIN pkategori ON pkategori.idpkategori=produk.idpkategori
                                            ORDER BY produk.idproduk DESC");

            while ($tampilkan = $queryProduk->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $tampilkan['namaproduk']; ?></td>
                    <td><?php echo $tampilkan['harga']; ?></td>
                    <td><?php echo $tampilkan['berat']; ?></td>
                    <td><?php echo $tampilkan['namakategori']; ?></td>
                    <td><?php echo $tampilkan['stock']; ?></td>
                    <td><?php echo $tampilkan['tgl']; ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>

<?php
// Flush the output buffer
ob_end_flush();
?>
