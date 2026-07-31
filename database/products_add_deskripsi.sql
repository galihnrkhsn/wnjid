-- Deskripsi produk (teks bebas panjang, termasuk emoji) untuk ditampilkan
-- di bawah foto pada konsumen/index.php.
ALTER TABLE `products`
    ADD COLUMN `deskripsi` TEXT NULL AFTER `spek`;
