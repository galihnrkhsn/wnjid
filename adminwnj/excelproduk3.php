<?php

// Set headers for Excel export
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_Produk.xls");

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';
require_once '../includes/foto_helper.php';

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
            <th>Variant</th>
            <th>Link Foto</th>
            <th>Size</th>
            <th>Harga</th>
            <th>Berat</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Stock</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1; // Initialize $no
            $queryProduk = $koneksi->query("SELECT pkategori.namakategori,
                                                kategori.namakategori AS grrade,
                                                variants.id,
                                                products.id AS idProduct,
                                                variants.berat,
                                                variants.status,
                                                variants.variant,
                                                products.namaproduk,
                                                variants.harga,
                                                variants.tgl,
                                                variants.stock,
                                                fp.foto AS nama_foto,
                                                mf.name AS nama_folder,
                                                variants.size
                                            FROM products  
                                            LEFT JOIN kategori ON products.idkategori = kategori.idkategori 
                                            LEFT JOIN pkategori ON pkategori.idpkategori = products.idpkategori
                                            LEFT JOIN variants ON variants.idproducts = products.id
                                            LEFT JOIN foto_produk fp ON fp.id = variants.foto
                                            LEFT JOIN master_folder mf ON mf.id = fp.folder
                                            ORDER BY variants.id DESC");

            while ($tampilkan = $queryProduk->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($tampilkan['namaproduk']); ?></td>
                    <td><?php echo htmlspecialchars($tampilkan['variant']); ?></td>
                    <td>
                        <?php if (!empty($tampilkan['nama_foto'])): ?>
                            <?php $fotoSrc = fotoProdukSrc($tampilkan['nama_folder'] ?? null, $tampilkan['nama_foto']); ?>
                            <a href="<?= htmlspecialchars($fotoSrc) ?>" target="_blank" rel="noopener noreferrer">Lihat Foto</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($tampilkan['size']); ?></td>
                    <td><?php echo $tampilkan['harga']; ?></td>
                    <td><?php echo $tampilkan['berat']; ?></td>
                    <td><?php echo $tampilkan['namakategori']; ?></td>
                    <td><?php echo $tampilkan['status'] == 1 ? 'Unpublish' : 'Publish'; ?></td>
                    <td><?php echo $tampilkan['stock']; ?></td>
                    <td><?php echo $tampilkan['tgl']; ?></td>
                </tr>
                <?php
            }
        ?>
    </tbody>
</table>

<?php
// Flush the output buffer
ob_end_flush();
?>
