<?php
    // Hardening cookie session. session_set_cookie_params() cuma kepakai kalau session_start()
    // di bawah ini yang PERTAMA KALI bikin cookie-nya - tapi login konsumen sebenarnya terjadi
    // di luar /konsumen (redirect ke wnj.id), jadi saat sampai sini cookie-nya kemungkinan besar
    // SUDAH ada dari request sebelumnya (Set-Cookie tidak dikirim ulang oleh PHP untuk session
    // yang sudah jalan). Makanya re-issue eksplisit lewat setcookie() di bawah, supaya flag
    // keamanannya tetap ke-set walau sesi-nya bukan dibuat dari sini.
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['SERVER_PORT'] ?? null) == 443
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'secure'   => $isHttps,
            'samesite' => 'Lax',
        ]);
    }

    session_start();

    if (!headers_sent()) {
        setcookie(session_name(), session_id(), [
            'expires'  => 0,
            'path'     => '/',
            'httponly' => true,
            'secure'   => $isHttps,
            'samesite' => 'Lax',
        ]);
    }

    include_once __DIR__ . '/../../../../includes/csrf_helper.php';

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='https://wnj.id/';</script>";
        header('Location: https://wnj.id/');
        exit;
    }
    $idUser = $_SESSION['user_id'];
    $userLevel = $_SESSION['user_level'];
    if ($userLevel == 'konsumen' && isset($_SESSION['idkonsumen'])) {
        $idKonsumen = $_SESSION['idkonsumen'];
    } else {
        header("Location: https://wnj.id/");
        exit();
    }

    // Chokepoint proteksi CSRF - berlaku otomatis ke semua halaman konsumen yang include
    // file ini, tanpa perlu dipasang manual satu-satu di tiap POST handler.
    csrfRequireValid();
?>
