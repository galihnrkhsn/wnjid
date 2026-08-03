<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Reseller | Wanoja</title>
    <style>
        .namaico {
            text-align: center;
            font-weight: 600;
            color: #7e7fe5;
            text-decoration: none;
        }

        .carousel-container {
            width: 80%;
            height: 500px; /* Mengurangi tinggi carousel */
            margin: auto;
            overflow: hidden;
        }

        .carousel-slide {
            display: flex;
            width: 100%; /* Menambahkan lebar 100% */
            margin: 0;
            padding: 0;
        }

        .carousel-slide img {
            width: 100%;
            height: auto; /* Agar gambar tidak terdistorsi */
        }

        @media (max-width: 768px) {
            .carousel-container {
                margin-bottom: -300px; /* Mengurangi margin bawah */
                width: 100%;
                margin-top: -20px;
            }
        }

    </style>
</head> 
<body>

    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar.php'; ?>
    <!-- NAVBAR END -->

    <!-- CAROUSEL -->
    <div class="carousel-container">
        <div class="carousel-slide">
            <img src="../image/carousel/61c3d5ba510fb.jpg" alt="Image 1">
            <img src="../image/carousel/61d7adf4b142a.jpg" alt="Image 2">
            <img src="../image/carousel/61d6b45dd9841.jpg" alt="Image 3">
        </div>
    </div>
    <!-- CAROUSEL END -->

    <!-- MAIN CONTENT -->
    <div class="container text-center">
        <div class="my-4">
            <div class="row mt-5">
                <div class="col-6">
                    <a href="listnewpo">
                        <img src="../image/icon/icon 3d preorder.png" class="d-block w-100">
                    </a>
                </div>
                <div class="col-6">
                    <a href="store4">
                        <img src="../image/icon/icon 3d readystock.png" class="d-block w-100">
                    </a>
                </div>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-4 col-lg-2 text-center">
                    <a href="https://t.me/joinchat/HI-N0pw7f3rlIxKe" target="blank()">
                        <img src="../image/icon/icon 3d nyabar.png" class="d-block w-100 mb-2">
                        <p class="namaico">Nyabar</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2 text-center">
                    <a href="https://t.me/wnjzizazu_supportcenter">
                        <img src="../image/icon/icon 3d suport ticket.png" class="d-block w-100 mb-2">
                        <p class="namaico">Support Ticket</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2 text-center">
                    <a href="https://wlink.id/katalog">
                        <img src="../image/icon/icon 3d katalog.png" class="d-block w-100 mb-2">
                        <p class="namaico">Katalog</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2 text-center">
                    <a href="pricelist">
                        <img src="../image/icon/icon 3d pricelist.png" class="d-block w-100 mb-2">
                        <p class="namaico">Pricelist</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2 text-center">
                    <a href="resi">
                        <img src="../image/icon/icon 3d resi.png" class="d-block w-100 mb-2">
                        <p class="namaico">Resi</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <br><br><br><br>
    <!-- MAIN CONTENT END -->

    <!-- FOOTER -->
    <?php
        include 'menubawah.php';
    ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script>
        const carousel = document.querySelector('.carousel-slide');
        const carouselItems = document.querySelectorAll('.carousel-slide img');
        const totalItems = carouselItems.length;
        let currentIndex = 0;

        function showSlide(index) {
            if (index < 0) {
                index = totalItems - 1;
            } else if (index >= totalItems) {
                index = 0;
            }
            carousel.style.transform = `translateX(-${index * 100}%)`;
            currentIndex = index;
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
        }

        setInterval(nextSlide, 3000); // Auto slide every 3 seconds
    </script>
    <!-- SCRIPT END -->
</body>
</html>