<?php
    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produksi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../../../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <link href="../vendor/manajemen/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Open-sans', sans-serif;
        }

        body {
            height: 100vh;
            background-color: #F6F6F6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        li {
            list-style: none;
        }

        a {
            text-decoration: none;
            font-size: 1rem;
        }

        .aText {
            text-decoration: none;
            color: black;
            font-size: 1rem;
        }

        a:hover {
            color: #FF0000;
        }

        .navbar {
            width: 100%;
            height: 60px;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar .logo a{
            font-size: 1.5rem;
            font-weight: bold;
            color: black;
        }

        .navbar .links {
            display: flex;
            gap: 2rem;
            margin-top: 20px;
        }

        .links a {
            color:black;
        }

        .links a:hover {
            color: #7e7fe5;
        }

        .navbar .toggle_btn {
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            display: none;
        }

        .action_btn {
            background-color: orange;
            color: #fff;
            padding: 0.5rem 1rem;
            border: none;
            outline: none;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            cursor: pointer;
            transition: scale 0.2 ease;
        }

        .action_btn:hover {
            scale: 1.05;
            color: #fff;
        }

        .action_btn:active {
            scale: 0.95;
        }


        /* DROPDOWN */
        .dropdown_menu {
            display: none;
            position: absolute;
            right: 2rem;
            top: 80px;
            height: 0;
            width: 300px;
            background-color: #749BC2;
            backdrop-filter: blur(15px);
            border-radius: 10px;
            overflow: hidden;
            transition: height 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .dropdown_menu.open {
            height: 75px;
            border: solid 1px;
            z-index: 1000;
        }

        .dropdown_menu li {
            padding: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dropdown_menu .action_btn {
            width: 100%;
            display: flex;
            justify-content: center;

        }
        main {
            flex: 1;
        }
        footer {
            background-color: #749BC2;
            color: white;
            padding: 10px 0;
        }

        .custom-text-color {
            color: #028391;
        }

        .custom-text-color2 {
            color: #003285;
        }

        @media (min-width: 768px) {
            header {
                position: relative;
                background-color: #749BC2;
                padding: auto;
                height: 80px;
            }
        }

        @media (max-width: 992px) {
            .navbar .links,
            .navbar .action_btn {
                display: none;
                color: black;
            }

            .navbar .toggle_btn {
                display: block;
                color: black;
            }

            .dropdown_menu {
                display: block;
                padding-right: 25px;
            }

            header {
                position: relative;
                background-color: #749BC2;
                padding: 0 2rem 20px;
            }
            .keranjang {
                padding-left: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <a href="index">Wanoja</a>
            </div>
            <ul class="links">
                <li style="font-size: 15px;"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a></li>
            </ul>
        <div class="toggle_btn">
            <i class="fa-solid fa-bars"></i>
        </div>
        </div>
        <div class="dropdown_menu">
            <ul class="links">
                <li><a href="logout.php" class="action_btn">Logout</a></li>
            </ul>
        </div>
    </header>
    <script>
        const toggleBtn = document.querySelector('.toggle_btn');
        const toggleBtnIcon = document.querySelector('.toggle_btn i');
        const dropDownMenu = document.querySelector('.dropdown_menu')
    
        toggleBtn.onclick = function () {
            dropDownMenu.classList.toggle('open');
            const isOpen = dropDownMenu.classList.contains('open');

            toggleBtnIcon.classList = isOpen
            ? 'fa-solid fa-x'
            : 'fa-solid fa-bars'
        }
    </script>
</body>
</html>