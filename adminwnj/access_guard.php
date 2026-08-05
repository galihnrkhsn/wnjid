<?php
    // Guard akses halaman adminwnj berdasarkan role user (users.role, dibawa ke
    // $_SESSION['administrator']['role'] saat login - lihat login.php).
    //
    // Dipanggil otomatis dari koneksi.php, jadi berlaku untuk semua halaman yang
    // include koneksi.php (mayoritas adminwnj). Endpoint di adminwnj/api/ yang tidak
    // include koneksi.php harus include file ini sendiri setelah session_start().
    //
    // role 'creative' dipakai tim editor foto - HANYA boleh akses menu Foto Produk
    // (upload/hapus/link foto ke variant), tidak ada akses ke halaman/data lain.
    // Role lain (admin, dst) tidak terpengaruh sama sekali oleh guard ini.
    if (!function_exists('adminwnjCekAksesHalaman')) {
        function adminwnjCekAksesHalaman(): void
        {
            if (!isset($_SESSION['administrator'])) {
                return; // belum login - biar ditangani pengecekan login masing-masing halaman
            }

            $role = $_SESSION['administrator']['role'] ?? null;
            if ($role !== 'creative') {
                return; // role lain: akses penuh seperti sebelumnya, tidak ada perubahan
            }

            $halamanDiizinkan = [
                'foto_produk.php',
                'foto_produk_gallery.php',
                'foto_produk_variants.php',
                'foto_produk_upload.php',
                'foto_produk_delete.php',
                'foto_produk_link.php',
                'logout.php',
            ];

            $halamanSekarang = basename($_SERVER['SCRIPT_NAME'] ?? '');
            if (in_array($halamanSekarang, $halamanDiizinkan, true)) {
                return;
            }

            // Location header relatif - halaman di adminwnj/api/ butuh "../" krn 1 folder
            // lebih dalam dari adminwnj/foto_produk.php, halaman di adminwnj/ langsung.
            $direktoriSekarang = basename(dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            $prefix = ($direktoriSekarang === 'api') ? '../' : '';
            header('Location: ' . $prefix . 'foto_produk.php');
            exit();
        }
    }

    adminwnjCekAksesHalaman();
