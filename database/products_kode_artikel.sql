-- =====================================================================
-- Kolom kode_artikel: grouping "artikel/koleksi" produk untuk promo B1G1
-- =====================================================================
--
-- Sebelumnya grouping ini hidup sebagai array PHP hardcoded di
-- distributor/save_cart.php ($categories, 17 entri) yang harus diedit manual
-- oleh developer setiap ada koleksi/artikel baru yang mau dijadikan promo
-- B1G1 ("beli 1 gratis 1" - item yang boleh digabung dalam 1x checkout B1G1
-- harus dari artikel yang sama). Dipindah jadi kolom di `products` supaya
-- staff bisa isi sendiri lewat form tambah produk (adminwnj/tambah_produk.php),
-- tidak perlu ubah kode lagi tiap ada koleksi baru.
ALTER TABLE products ADD COLUMN kode_artikel VARCHAR(50) NULL AFTER idkategori;

-- Backfill sekali pakai isi array $categories yang sudah ada, supaya data
-- produk yang sudah ada tidak kehilangan grouping-nya.
UPDATE products SET kode_artikel = 'sarung_etnik' WHERE id IN (2514);
UPDATE products SET kode_artikel = 'voal_hampers' WHERE id IN (2489);
UPDATE products SET kode_artikel = 'koko_etnik' WHERE id IN (2516,2517,2524,2525);
UPDATE products SET kode_artikel = 'rail_sport' WHERE id IN (2509,2510,2511);
UPDATE products SET kode_artikel = 'outer_parotia' WHERE id IN (2455);
UPDATE products SET kode_artikel = 'khimar_kolibri_anak' WHERE id IN (2336);
UPDATE products SET kode_artikel = 'bergo_fiarca' WHERE id IN (2230);
UPDATE products SET kode_artikel = 'limicola_abaya' WHERE id IN (2226,2248);
UPDATE products SET kode_artikel = 'konin_25' WHERE id IN (2521,2527,2528,2522,2520,2519,2565,2526,2523,2529,2531,2530,2566);
UPDATE products SET kode_artikel = 'konin_24' WHERE id IN (2422,2421,2420,2412,2413,2414,2417,2418,2416,2415,2419);
UPDATE products SET kode_artikel = 'sula_scarves' WHERE id IN (2502);
UPDATE products SET kode_artikel = 'mukena_skena' WHERE id IN (2515);
UPDATE products SET kode_artikel = 'kolibri_25' WHERE id IN (2513);
UPDATE products SET kode_artikel = 'kolibri_22' WHERE id IN (2334);
UPDATE products SET kode_artikel = 'kolibri_24' WHERE id IN (2400,2391,2394,2398,2399,2395,2396,2397);
UPDATE products SET kode_artikel = 'kolibri_luxury' WHERE id IN (2558,2559);
UPDATE products SET kode_artikel = 'kolibri_sarung' WHERE id IN (2536);
