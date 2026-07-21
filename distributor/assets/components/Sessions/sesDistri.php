<?php
    session_start();
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='https://wnj.id/';</script>";
        header('Location: https://wnj.id/');
        exit;
    }
    $idUser = $_SESSION['user_id'];
    $userLevel = $_SESSION['user_level'];
    if ($userLevel == 'distributor' && isset($_SESSION['idadmin'])) {
        $idAdmin = $_SESSION['idadmin'];
    } else {
        header("Location: https://wnj.id/");
        exit();
    }
?>