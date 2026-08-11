<?php
    // Chokepoint sesi buat semua halaman mitra/ - satu tempat buat validasi login +
    // hardening cookie, menggantikan pola lama (tiap file di distributor/agen/reseller/marketer
    // ulang sendiri-sendiri pengecekan session_start()+isset($_SESSION[...])).
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

    include_once __DIR__ . '/../includes/mitra_role_helper.php';

    $mitraRolesValid = ['distributor', 'agen', 'reseller', 'marketer'];

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level']) || !in_array($_SESSION['user_level'], $mitraRolesValid, true)) {
        header('Location: login.php');
        exit;
    }

    // Variabel siap-pakai buat halaman yang include file ini - satu sumber kebenaran,
    // bukan baca $_SESSION[...] langsung dengan nama key yang beda-beda tiap role.
    $mitraRole        = $_SESSION['user_level'];
    $mitraCfg         = mitraRoleConfig($mitraRole);
    $idMitra          = (int) ($_SESSION['idmitra'] ?? 0);
    $idAdminInduk     = (int) ($_SESSION['idadmin_induk'] ?? 0);
    $idMitraAgenInduk = isset($_SESSION['idmitraagen_induk']) && $_SESSION['idmitraagen_induk'] !== null
        ? (int) $_SESSION['idmitraagen_induk']
        : null;
    $diskonPersen     = $mitraCfg['diskon'] ?? 0;
    $namaMitra        = $_SESSION['nama_mitra'] ?? '';
