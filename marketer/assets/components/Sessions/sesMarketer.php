<?php
    session_start();
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='https://wnj.id/';</script>";
        header('Location: https://wnj.id/'); // Redirect ke halaman login jika tidak ada session yang sesuai
        exit;
    }
    $idUser = $_SESSION['user_id'];
    $userLevel = $_SESSION['user_level'];
    if ($userLevel == 'marketer' && isset($_SESSION['idmitramarketer'])) {
        $idAdmin = $_SESSION['idmitramarketer'];
    } else {
        header("Location: https://wnj.id/");
        exit();
    }
?>