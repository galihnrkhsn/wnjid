<?php
$koneksi = mysqli_connect("localhost","root","@RjmKaler08","wnj_web_v2");

if ($koneksi->connect_error) {
    die("Koneksi WNJ Gagal: " . $koneksi->connect_error);
}

date_default_timezone_set('Asia/Jakarta');
mysqli_begin_transaction($koneksi);

try {

    $query = mysqli_query($koneksi, "
        SELECT * FROM ordermitra 
        WHERE TIMESTAMP(tgl, waktu) <= NOW() - INTERVAL 1 DAY
        AND status = 'Pending'
    ");

    while ($row = mysqli_fetch_assoc($query)) {
        $variant_id = $row['idproduk'];
        $qty = $row['jumlah'];

        $variant = mysqli_query($koneksi, "
            SELECT idproducts 
            FROM variants 
            WHERE id = $variant_id
        ");
        
        $dataVariant = mysqli_fetch_assoc($variant);
        $idproduk = [
            2555, 2515, 
            2230, 2226,
            2489, 2455,
            2536, 2514,
            2517, 2553,
            2546, 2550,
            2549, 2544,
            2542, 2560,
            2554, 2540,
            2538, 2537,
            2313, 2314,
            2518, 2556,
            2556, 2530,
            2529, 2499,
            2422, 2421,
            2420, 2419,
            2412, 2559,
            2558, 2513,
            2400, 2399,
            2398, 2394,
            2391, 2334,
            2511, 2510,
            2567
        ];
        
        if ($dataVariant && in_array($dataVariant['idproducts'], $idproduk)) {
            mysqli_query($koneksi, "
                UPDATE variants 
                SET stock = stock + $qty 
                WHERE id = $variant_id
            ");

            mysqli_query($koneksi, "
                DELETE FROM ordermitra 
                WHERE idorder = ".$row['idorder']."
            ");
        }

    }

    mysqli_commit($koneksi);

} catch (Exception $e) {
    mysqli_rollback($koneksi);
}