<?php
    session_start();
    include 'koneksi.php';
    include 'assets/components/Sessions/sesMarketer.php';
    include "settingdatatables.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer | Wanoja</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
    <style>
        .fixed-size-img {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-2">
        <?php
            //info message
            if(isset($_SESSION['message'])){
                ?>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-6">
                        <div class="alert alert-info text-center">
                            <?= $_SESSION['message']; ?>
                        </div>
                    </div>
                </div>
                <?php
                unset($_SESSION['message']);
            }
        ?>
        <p align="right"><input type="radio" onclick="javascript:window.location.href='store3.php'; "> Mode Hemat  &nbsp&nbsp
            <input type="radio" onclick="javascript:window.location.href='store4.php'; " checked="checked"> Mode Cantik
        </p>
        <form method="get" class="form-inline justify-content-between mb-3">
            <div class="form-group mr-2 mb-2" style="flex: 1;">
                <input type="text" class="form-control" name="namaproduk" placeholder="Masukkan Nama Produk ..." style="width: 100%;" /> 
            </div> 
            <button class="btn btn-primary mr-2" name="cari" type="submit" style="width:15%;">
                <span><i class="fa-solid fa-magnifying-glass"></i></span>
            </button> 
            <button class="btn btn-primary mr-2" name="tampil" type="submit">Tampil Semua</button>
        </form>
    </div>
    <div class="container">
        <div class="row">
        <?php
                include "koneksi.php";
                if(isset($_GET["cari"])){
                    $namaproduk     = $_GET['namaproduk'];
                    $page           = (isset($_GET['page']))? $_GET['page'] : 1;
                    $limit          = 20;
                    $limit_start    = ($page - 1) * $limit;
                    $sql            = mysqli_query($koneksi, "SELECT products.namaproduk, variants.* FROM variants 
                                                                INNER JOIN products ON variants.idproducts = products.id
                                                                WHERE stock > 0 AND namaproduk 
                                                                LIKE '%$namaproduk%' AND idkategori > 0 AND status <> 1");
                    $no             = $limit_start + 1;
                }elseif(isset($_GET["tampil"])){
                    $page           = (isset($_GET['page']))? $_GET['page'] : 1;
                    $limit          = 20;
                    $limit_start    = ($page - 1) * $limit;
                    $sql            = mysqli_query($koneksi, "SELECT products.namaproduk, variants.* FROM variants 
                                                                INNER JOIN products ON variants.idproducts = products.id
                                                                WHERE stock > 0 AND idkategori > 0 AND status <> 1 
                                                                ORDER BY tgl DESC, idproduk DESC LIMIT ".$limit_start.",".$limit);
                    $no             = $limit_start + 1;
                } else{   
                    $page           = (isset($_GET['page']))? $_GET['page'] : 1;
                    $limit          = 20;
                    $limit_start    = ($page - 1) * $limit;
                    $sql            = mysqli_query($koneksi, "SELECT products.namaproduk, variants.* FROM variants 
                                                                INNER JOIN products ON variants.idproducts = products.id
                                                                WHERE variants.status = '$status' AND variants.stock > 0
                                                                ORDER BY tgl DESC, products.id DESC LIMIT ".$limit_start.",".$limit);
                    $no             = $limit_start + 1;
                }
                while($data = mysqli_fetch_array($sql)){
            ?>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6" style="margin-bottom: 2%;">
                <div class="card">
                    <?php if ($data['foto'] <> ""): ?>
                        <img class="card-img-top fixed-size-img" src="../image/produk/<?= $data['foto']; ?>" alt="Card image">
                    <?php else: ?>
                        <img class="card-img-top fixed-size-img" src="../distributor/foto/nophoto.png" alt="Card image">
                    <?php endif ?>
                    <div class="card-body">
                        <h6 class="card-title"><?= $data['namaproduk']; ?> <?= $data['variant'] ?> <?= $data['size'] ?></h6>
                        <?php if ($data['status'] == 0): ?>
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
                                <a href="add_chart2.php?id=<?= $data['id']; ?>&harga=<?= $data['harga']; ?>" class="btn btn-primary">Beli</a>
                            </p>
                        <?php else: ?>
                            <p class="card-text">
                                Produk sedang diupdate, akan aktif setelah proses update selesai.
                            </p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <? } ?>
        </div>
    </div>
        <!-- PAGINATION -->
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
                        <li class="page-item"><a class="page-link" href="?page=<?= $link_prev; ?>">&laquo;</a></li>
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
                    <li class="page-item<?= $link_active; ?>"><a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a></li>
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
                    <li class="page-item"><a class="page-link" href="?page=<?= $link_next; ?>">&raquo;</a></li>
                    <li class="page-item"><a class="page-link" href="?page=<?= $jumlah_page; ?>">Last</a></li>
                    <?php
                    }
                ?>
            </ul>
        </nav>
        <!-- PAGINATION END -->
    <!-- MAIN CONTENT END -->
    <br><br><br><br>
    
    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <? include 'menubawahstore.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>