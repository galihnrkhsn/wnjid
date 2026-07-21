<?php
    session_start();

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }

    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];

    $query          = $koneksi->query("SELECT *, role.id AS role_id 
                                        FROM user_manajemen 
                                        INNER JOIN role ON role.id = user_manajemen.id_role 
                                        WHERE user_manajemen.id = '$id'");
    $user           = $query->fetch_assoc();
    $userTipe       = $user['name'];
?>