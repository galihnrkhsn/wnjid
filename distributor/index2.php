<?php
    // session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesDistri.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Distributor | Wanoja</title>
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

        .modal-content {
            border-radius: 10px;
        }
        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5rem;
            color: #000;
            text-decoration: none;
        }
        .modal-body img {
            max-width: 100%;
            border-radius: 10px;
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
    <!-- <div class="modal fade" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Close" style="top: -10px; right: -10px; background-color: white; border-radius: 50%; padding: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);"></button>
                <div class="modal-body text-center">
                    <?php
                        $banner = $koneksi->query("SELECT * FROM slider WHERE tipe = 'banner'")->fetch_assoc();
                    ?>
                    <img src="assets/img/news/<?= $banner['foto'] ?>" alt="Popup Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show modal on page load
        document.addEventListener('DOMContentLoaded', function () {
            const popupModal = new bootstrap.Modal(document.getElementById('popupModal'));
            popupModal.show();
        });
    </script>


    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar2.php"; ?>
    <!-- NAVBAR END -->

    <!-- CAROUSEL -->
    <div class="carousel-container">
        <div class="carousel-slide">
            <img src="../home/assets/images/banner.png" alt="Image 1">
            <img src="../home/assets/images/banner-2.png" alt="Image 2">
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
            <div class="row mt-5">
                <div class="col-4 col-lg-2">
                    <a href="dataagen">
                        <img src="../image/icon/icon 3d submitra.png" class="d-block w-100"><p class="namaico">SubDB</p>
                    </a>
                </div> 
                <div class="col-4 col-lg-2">
                    <a href="https://t.me/joinchat/HI-N0pw7f3rlIxKe" target="blank()">
                        <img src="../image/icon/icon 3d nyabar.png" class="d-block w-100"> <p class="namaico">Nyabar</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2">
                    <a href="https://t.me/wnjzizazu_supportcenter" target="blank()">
                        <img src="../image/icon/icon 3d suport ticket.png" class="d-block w-100" > <p class="namaico">Support Ticket</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2">
                    <a href="https://wlink.id/katalog">
                        <img src="../image/icon/icon 3d katalog.png" class="d-block w-100" > <p class="namaico">Katalog</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2">
                    <a href="pricelist">
                        <img src="../image/icon/icon 3d pricelist.png" class="d-block w-100" > <p class="namaico">Pricelist</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2">
                    <a href="resi">
                        <img src="../image/icon/icon 3d resi.png" class="d-block w-100" > <p class="namaico">Resi</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->

    <br><br><br><br>

    <!-- FOOTER -->
    <?
        require_once 'menubawah.php';
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