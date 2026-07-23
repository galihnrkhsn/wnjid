<?php

// Endpoint di folder ini (generate_qr_api.php, get_mitra.php) dipanggil langsung
// lewat nama filenya masing-masing. index.php hanya menjadi fallback ketika ada
// request ke path yang tidak cocok dengan file apa pun di dalam api/ (lihat .htaccess).
header('Content-Type: application/json');
http_response_code(404);
echo json_encode(['status' => 'error', 'message' => 'Endpoint tidak ditemukan']);
