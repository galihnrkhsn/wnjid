<?php
    $id             = $_SESSION['user_id'] ?? null;
    $role           = $_SESSION['user_level'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($role) ?> | Wanoja</title>

    <!-- Satu-satunya sumber Bootstrap yang dipakai di seluruh manajemen/ (CDN, versi 5.3.3) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800,800i" rel="stylesheet">

    <style>
        /* Tema beige - dipusatkan lewat variable Bootstrap supaya semua util class
           (btn-primary, text-primary, border-primary, bg-primary, dst) otomatis ikut. */
        :root {
            --bs-primary: #a3845f;
            --bs-primary-rgb: 163, 132, 95;
            --bs-primary-text-emphasis: #6b5738;
            --bs-primary-bg-subtle: #f3ecdf;
            --bs-primary-border-subtle: #ddccae;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #faf7f0;
            font-family: 'Nunito', sans-serif;
            color: #4a4030;
        }

        main {
            flex: 1;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            color: var(--bs-primary-text-emphasis);
        }

        .app-header {
            background-color: #efe6d3;
            border-bottom: 1px solid #ddccae;
        }

        .app-header .brand {
            font-size: 1.25rem;
            font-weight: 800;
            color: #6b5738;
        }

        .app-header .btn-logout {
            color: #6b5738;
            border-color: #ddccae;
        }

        .app-header .btn-logout:hover {
            background-color: #ddccae;
            color: #4a4030;
        }

        /* Warna aksen border-left kartu dashboard, disamakan ke palet beige/earth-tone. */
        .border-left-primary { border-left: .25rem solid var(--bs-primary) !important; }
        .border-left-success { border-left: .25rem solid #6c8c5a !important; }
        .border-left-info    { border-left: .25rem solid #6d98a6 !important; }
        .border-left-warning { border-left: .25rem solid #c9a24b !important; }
        .border-left-danger  { border-left: .25rem solid #b5654f !important; }

        .text-gray-800 { color: #4a4030 !important; }

        .card {
            border: 1px solid #eee2cd;
        }

        footer {
            background-color: #efe6d3;
            border-top: 1px solid #ddccae;
            color: #6b5738;
        }
    </style>
</head>
<body>
    <header class="app-header py-2 mb-4">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="brand" href="index.php">Wanoja</a>
            <a href="logout.php" class="btn btn-sm btn-outline-secondary btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </header>
    <main>
