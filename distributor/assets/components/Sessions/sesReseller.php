<?php
    if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] !== 'reseller') {
        header("Location: login2.php");
        exit();
    }
    
    // Simpan informasi penting dari sesi ke dalam variabel lokal
    $user_id = $_SESSION['user_id'];
    $user_level = $_SESSION['user_level'];
?>