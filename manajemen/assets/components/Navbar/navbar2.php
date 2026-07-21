<?php
    session_start();
    include 'koneksi.php'; 

    $idmitra = $_SESSION["idadmin"];
    $keranjang=0;
    $sql2 = "SELECT * FROM keranjang  WHERE idmitra='$idmitra' and jmlh>0 ";
    $query2 = $koneksi->query($sql2);
    while($apaya = $query2->fetch_assoc()){
        $keranjang+=$apaya['jmlh'];
    }

    $idadmin = $_SESSION["idadmin"];

    $ambil = $koneksi->query("SELECT (SUM(saldo.debit) - SUM(saldo.credit)) AS saldo_sisa,
        (SELECT (SUM(saldo.debit) - SUM(saldo.credit)) FROM saldo WHERE idadmin = $idadmin) AS total_saldo_sisa
        FROM saldo  
        WHERE idadmin = $idadmin
        AND saldo.transaksi NOT LIKE '%Fee Order Agen%'  
        AND saldo.transaksi NOT LIKE '%Fee Order Reseller%'
        AND saldo.transaksi NOT LIKE '%Fee Order marketer%'
        GROUP BY idadmin");
    if ($ambil) {
        $result = $ambil->fetch_assoc();
        $saldo_sisa = $result["saldo_sisa"];
        $total_saldo_sisa = $result["total_saldo_sisa"];
    } else {
        echo "Gagal mengambil data saldo.";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">  

    <!-- <link rel="stylesheet" href="assets/css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="assets/css/bootstrap2.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Open-sans', sans-serif;
        }

        body {
            height: 100vh;
            background-color: white;
        }
        li {
            list-style: none;
        }

        a {
            text-decoration: none;
            color: white;
            font-size: 1rem;
        }

        a:hover {
            color: #7e7fe5;
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
            background-color: #f0f6ff;
            backdrop-filter: blur(15px);
            border-radius: 10px;
            overflow: hidden;
            transition: height 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .dropdown_menu.open {
            height: 230px;
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

        @media (min-width: 768px) {
            header {
                position: relative;
                background-color: #f0f6ff;
                padding: auto;
                height: 80px;
            }
            .badge {
                position: absolute;
                top: 20px;
                /* right: -5px; */
                border-radius: 50%;
                padding: 3px;
                background-color: #dc3545;
                color: white;
                font-size: 12px;
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
                background-color: #f0f6ff;
                padding: 0 2rem 20px;
            }
            .badge {
                position: absolute;
                top: 45px;
                border-radius: 50%;
                padding: 3px;
                background-color: #dc3545;
                color: white;
                font-size: 12px;
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
                <a href="index2">Wanoja</a>
            </div>
            <ul class="links">
                <li style="font-size: 15px;">
                    <a href="saldo">
                        <i class="fa-solid fa-money-bill">
                            <? echo number_format($total_saldo_sisa) ?>
                        </i>
                    </a>
                </li>
                <li style="font-size: 15px;">
                    <a href="view_cart">
                        <i class="fa-solid fa-cart-shopping">
                            <span class="badge" style="border-radius: .25rem;padding: 3px 3px 3px 3px; background-color: #dc3545; color: white;">
                                <?php echo $keranjang; ?>
                            </span>
                        </i>
                    </a>
                </li>
                <li style="font-size: 15px;">
                    <a href="pesan"><i class="fa-solid fa-message"></i>
                    <?php 
                    $mitra= $_SESSION ['idadmin'];
                    $ambiljmlh=$koneksi->query("SELECT COUNT(*) as jmlh FROM tinbox where idadmin='$mitra' and status='Belum Dibaca'"); 
                    $datajmlh=$ambiljmlh->fetch_assoc();
                    ?>
                <?php if ($datajmlh['jmlh']==0) {
                
                }else{?>
                            <span class="badge" style="border-radius: .25rem;padding: 3px 3px 3px 3px; background-color: #dc3545; color: white;">
                        <?php echo $datajmlh['jmlh']; ?> 
                    </span>
                <?php
                }
                ?>
                    </a>
                </li>
                <li style="font-size: 15px;"><a href="listnewpo"><i class="fa-solid fa-bell"></i></a></li>
                <li style="font-size: 15px;"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a></li>
            </ul>
        <div class="toggle_btn">
            <i class="fa-solid fa-bars"></i>
        </div>
        </div>
        <div class="dropdown_menu">
            <ul class="links">
                <li>
                    <i class="fa-solid fa-money-bill">
                        <a href="saldo">
                            <? echo number_format($total_saldo_sisa) ?>
                        </a>
                    </i>
                </li>
                <li>
                    <i class="fa-solid fa-cart-shopping">
                        <span class="badge" style="border-radius: .25rem;padding: 3px 3px 3px 3px; background-color: #dc3545; color: white;">
                            <?php echo $keranjang; ?>
                        </span>
                        <a href="view_cart" class="keranjang">Keranjang</a>
                    </i>
                </li>
                <li><i class="fa-solid fa-message"><a href="pesan"> Message</a></i></li>
                <li><i class="fa-solid fa-bell"><a href="listnewpo"> List PO</a></i></li>
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


