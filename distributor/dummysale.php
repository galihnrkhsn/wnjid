<?php
    session_start();

    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distributor | Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <!-- NAVBAR -->
    <? include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <div class="container mt-2" style="padding-bottom: 5rem">
        <div class="d-flex align-items-center justify-content-end">
            <p class="mb-2"><input type="radio" onclick="javascript:window.location.href='store.php';">Mode Hemat</p>
            <p class="mb-2 mx-2"><input type="radio" onclick="javascript:window.location.href='store2.php';" checked="checked"> Mode Cantik</p>
        </div>
        <form method="get" class="form-inline justify-content-between mb-3">
            <div class="form-group mr-2 mb-2" style="flex: 1;">
                <input type="text" class="form-control" name="namaproduk" placeholder="Masukkan Nama Produk ..." style="width: 100%;" /> 
            </div> 
            <button class="btn btn-primary mr-2" name="cari" type="submit" style="width:15%;">
                <span><i class="fa-solid fa-magnifying-glass"></i></span>
            </button> 
            <button class="btn btn-primary mr-2" name="tampil" type="submit">Tampil Semua</button>
        </form>

        <div class="row">
            <?php
                if (isset($_GET["cari"])) {
                    $namaproduk = $_GET['namaproduk'];
                    // Cek apakah terdapat data page pada URL
                    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
                    $limit = 20; // Jumlah data per halamannya
                    // Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
                    $limit_start = ($page - 1) * $limit;
                    // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
                    $sql = mysqli_query($koneksi, "SELECT * FROM produk WHERE stock > 0 AND namaproduk LIKE '%$namaproduk%' AND idkategori > 0 AND status <> 1");
                    $no = $limit_start + 1; // Untuk penomoran tabel
                } elseif (isset($_GET["tampil"])){
                    // Include / load file koneksi.php
                    include "koneksi.php";
                    $idmitra=$_SESSION ['idadmin'];		
                    // Cek apakah terdapat data page pada URL
                    $page = (isset($_GET['page']))? $_GET['page'] : 1;
                    $limit = 20; // Jumlah data per halamannya
                    // Untuk menentukan dari data ke berapa yang akan ditampilkan pada tabel yang ada di database
                    $limit_start = ($page - 1) * $limit;
                    // Buat query untuk menampilkan data siswa sesuai limit yang ditentukan
                    $sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0  and idkategori>0 and status<>1 order by tgl desc, idproduk desc LIMIT ".$limit_start.",".$limit);
                    $no = $limit_start + 1; // Untuk penomoran tabel
                } else {
                    include "koneksi.php";
                    $idmitra=$_SESSION ['idadmin'];
                    $page = (isset($_GET['page']))? $_GET['page'] : 1;
                    $limit = 20;
                    $limit_start = ($page - 1) * $limit;
                    $sql = mysqli_query($koneksi, query: "SELECT * FROM produk WHERE jenis = 'Promo' AND status = 1 AND stock > 0 ORDER BY tgl DESC, idproduk DESC LIMIT ". $limit_start . "," . $limit);
                    $no = $limit_start + 1;
                }
                while ($data = mysqli_fetch_array($sql)) {
            ?>
                <div class="col-lg-3 col-md-6 col-xs-6 col-6 mb-4">
                    <div class="card">
                        <?php if ($data['foto'] <> ""): ?>
                            <img class="card-img-top" src="../image/produk/<?php echo $data['foto']; ?>" alt="Card image">
                        <?php else: ?>
                            <img class="card-img-top" src="foto/nophoto.png" alt="Card image">
                        <?php endif ?>
                        <div class="card-body">
                            <h6 class="card-title"><?= $data['namaproduk']; ?></h6>
                            <p class="card-text">
                                <?php if ($data['idkategori'] >= 51): ?>
                                    -
                                <?php elseif (strpos($data['namaproduk'], "Vanellus Dress") !== false) : ?>
                                    <span>Rp. <?= number_format($data['harga']); ?></span>
                                    <span class="text-decoration-line-through d-block">Rp. <?= number_format(480000); ?></span>
                                <?php else: ?>
                                    Rp. <?= number_format($data['harga']); ?>
                                <?php endif ?>
                            </p>
                            <p class="card-text">
                                (<?= $data['stock']; ?>) Pcs&nbsp;&nbsp;&nbsp;
                                <a href="add_chart4.php?namaproduk=<?= $data['namaproduk']; ?>&id=<?= $data['idproduk']; ?>&harga=<?= $data['harga']; ?>" class="btn btn-primary">Beli</a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php
                    if($page == 1){
                    ?>
                        <li class="page-item disabled"><a class="page-link" href="#">First</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                    <?php
                    } else {
                        $link_prev = ($page > 1) ? $page - 1 : 1;
                    ?>
                        <li class="page-item"><a class="page-link" href="?page=1">First</a></li>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $link_prev; ?>">&laquo;</a></li>
                    <?php
                    }
                    ?>
                    <?php
                    // Buat query untuk menghitung semua jumlah data
                    $sql2 = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM katalog");
                    $get_jumlah = mysqli_fetch_array($sql2);
                    
                    $jumlah_page = ceil($get_jumlah['jumlah'] / $limit); // Hitung jumlah halamannya
                    $jumlah_number = 3; // Tentukan jumlah link number sebelum dan sesudah page yang aktif
                    $start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; // Untuk awal link number
                    $end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page; // Untuk akhir link number

                    for($i = $start_number; $i <= $end_number; $i++){
                        $link_active = ($page == $i) ? ' active' : '';
                    ?>
                    <li class="page-item<?php echo $link_active; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php
                    }
                    ?>
                    <?php
                    if($page == $jumlah_page){
                    ?>
                    <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
                    <li class="page-item disabled"><a class="page-link" href="#">Last</a></li>
                    <?php
                    } else {
                        $link_next = ($page < $jumlah_page) ? $page + 1 : $jumlah_page;
                    ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $link_next; ?>">&raquo;</a></li>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $jumlah_page; ?>">Last</a></li>
                <?php
                    }
                ?>
            </ul>
        </nav>
    </div>


    <!-- FOOTER -->
    <? include 'menubawahstore.php'; ?>
    <!-- FOOTER END -->

    <!-- Jquery, Popper, Bootstrap -->
    <script src="./assets2/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="./assets2/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets2/js/popper.min.js"></script>
    <script src="./assets2/js/bootstrap.min.js"></script>

    <!-- Slick-slider , Owl-Carousel ,slick-nav -->
    <script src="./assets2/js/owl.carousel.min.js"></script>
    <script src="./assets2/js/slick.min.js"></script>
    <script src="./assets2/js/jquery.slicknav.min.js"></script>

    <!-- One Page, Animated-HeadLin, Date Picker -->
    <script src="./assets2/js/wow.min.js"></script>
    <script src="./assets2/js/animated.headline.js"></script>
    <script src="./assets2/js/jquery.magnific-popup.js"></script>
    <script src="./assets2/js/gijgo.min.js"></script>

    <!-- Nice-select, sticky,Progress -->
    <script src="./assets2/js/jquery.nice-select.min.js"></script>
    <script src="./assets2/js/jquery.sticky.js"></script>
    <script src="./assets2/js/jquery.barfiller.js"></script>

    <!-- counter , waypoint,Hover Direction -->
    <script src="./assets2/js/jquery.counterup.min.js"></script>
    <script src="./assets2/js/waypoints.min.js"></script>
    <script src="./assets2/js/jquery.countdown.min.js"></script>
    <script src="./assets2/js/hover-direction-snake.min.js"></script>

    <!-- contact js -->
    <script src="./assets2/js/contact.js"></script>
    <script src="./assets2/js/jquery.form.js"></script>
    <script src="./assets2/js/jquery.validate.min.js"></script>
    <script src="./assets2/js/mail-script.js"></script>
    <script src="./assets2/js/jquery.ajaxchimp.min.js"></script>

    <!-- Jquery Plugins, main Jquery -->  
    <script src="./assets2/js/plugins.js"></script>
    <script src="./assets2/js/main.js"></script>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <?php include "settingdatatables.php"; ?>
</body>
</html>