<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesAgen.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Agen | Wanoja</title>
</head> 
<body>
<?php include "assets/components/Navbar/navbar2.php"; ?>
<!--================Slider Area=================-->
    <div class="carousel-container">
        <div class="carousel-slide">
            <img src="foto/carousel/61c3d5ba510fb.jpg" alt="Image 1">
            <img src="foto/carousel/61d7adf4b142a.jpg" alt="Image 2">
            <img src="foto/carousel/61d6b45dd9841.jpg" alt="Image 3">
        </div>
    </div>
<!--================Slider Area End =================-->
    <div class="container text-center">
        <div class="my-4">
            <div class="row mt-5">
                <div class="col-6">
                    <a href="listnewpo">
                        <img src="foto/icon/icon 3d preorder.png" class="d-block w-100">
                    </a>
                </div>
                <div class="col-6">
                    <a href="store2">
                        <img src="foto/icon/icon 3d readystock.png" class="d-block w-100">
                    </a>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-4 col-lg-2">
                    <a href="dataagen">
                        <img src="foto/icon 3d submitra.png" class="d-block w-100"><p class="namaico">SubDB</p>
                    </a>
                </div> 
                <div class="col-4 col-lg-2">
                    <a href="https://t.me/joinchat/HI-N0pw7f3rlIxKe" target="blank()">
                        <img src="foto/icon/icon 3d nyabar.png" class="d-block w-100"> <p class="namaico">Nyabar</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2">
                    <a href="return">
                        <img src="foto/icon/icon 3d suport ticket.png" class="d-block w-100" > <p class="namaico">Support Ticket</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2">
                    <a href="https://wnj.web.id/inkubator/katalog">
                        <img src="foto/icon/icon 3d katalog.png" class="d-block w-100" > <p class="namaico">Katalog</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2">
                    <a href="pricelist">
                        <img src="foto/icon/icon 3d pricelist.png" class="d-block w-100" > <p class="namaico">Pricelist</p>
                    </a>
                </div>
                <div class="col-4 col-lg-2">
                    <a href="resi">
                        <img src="foto/icon/icon 3d resi.png" class="d-block w-100" > <p class="namaico">Resi</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <br><br><br><br>
    <?
        include 'menubawah.php';
    ?>
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
</body>
</html>