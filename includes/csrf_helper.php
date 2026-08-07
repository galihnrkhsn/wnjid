<?php
// Proteksi CSRF - token disimpan di session, wajib dikirim balik di setiap POST.
// Enforcement-nya dipasang di chokepoint (sesKonsumen.php) supaya otomatis berlaku
// buat semua halaman konsumen tanpa perlu diulang manual di tiap file.

if (!function_exists('csrfToken')) {
    function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrfField')) {
    // Dipakai di dalam <form method="post">
    function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrfToken()) . '">';
    }
}

if (!function_exists('csrfValidate')) {
    function csrfValidate(): bool
    {
        $token = $_POST['csrf_token'] ?? '';
        return !empty($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('csrfRequireValid')) {
    // Panggil setelah session_start(). Non-POST request dibiarkan lewat (GET murni baca data).
    // Balasan disesuaikan: JSON kalau request AJAX (jQuery selalu kirim X-Requested-With),
    // alert+kembali kalau submit form HTML biasa - supaya tidak nampilin JSON mentah ke user.
    function csrfRequireValid(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        if (csrfValidate()) {
            return;
        }

        http_response_code(403);
        $isAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sesi tidak valid, silakan muat ulang halaman dan coba lagi.']);
        } else {
            echo "<script>alert('Sesi tidak valid, silakan muat ulang halaman dan coba lagi.'); history.back();</script>";
        }
        exit;
    }
}
