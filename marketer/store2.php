<?php
    header("Location: http://wnj.id/marketer/store4.php");
    exit();
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
    <title>Marketer | WNJ.ID</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
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
                            <?php echo $_SESSION['message']; ?>
                        </div>
                    </div>
                </div>
                <?php
                unset($_SESSION['message']);
            }
        ?>
        <p align="right"><input type="radio" onclick="javascript:window.location.href='store.php'; "> Mode Hemat  &nbsp&nbsp
            <input type="radio" onclick="javascript:window.location.href='store2.php'; " checked="checked"> Mode Cantik
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
                if(isset($_GET["cari"])){
                    include "koneksi.php";
                    $namaproduk=$_GET['namaproduk'];
                    $page = (isset($_GET['page']))? $_GET['page'] : 1;                
                    $limit = 20;
                    $limit_start = ($page - 1) * $limit;
                    $sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0  and namaproduk LIKE '%$namaproduk%' and idkategori>0 and status<>1");
                    $no = $limit_start + 1;                
                } elseif(isset($_GET["tampil"])){
                    include "koneksi.php";
                    $idmitra=$_SESSION ['idmitramarketer'];
                    $page = (isset($_GET['page']))? $_GET['page'] : 1;
                    $limit = 20;
                    $limit_start = ($page - 1) * $limit;
                    $sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0  and idkategori>0 and status<>1 order by tgl desc, idproduk desc LIMIT ".$limit_start.",".$limit);
                    $no = $limit_start + 1;
                } else{   
                    include "koneksi.php";
                    $idmitra=$_SESSION ['idmitramarketer'];
                    //$idkategori=$_GET['idkategori'];					
                    $page = (isset($_GET['page']))? $_GET['page'] : 1;
                    $limit = 20;
                    $limit_start = ($page - 1) * $limit;
                    $sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0  and idkategori>0 and status<>1 order by tgl desc, idproduk desc LIMIT ".$limit_start.",".$limit);
                    $no = $limit_start + 1;
                }
                while($data = mysqli_fetch_array($sql)){
            ?>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6" style="margin-bottom: 2%;">
                <div class="card">
                    <?php if ($data['foto'] <> ""): ?>
                    <img class="card-img-top" src="../distributor/foto/<?php echo $data['foto']; ?>" alt="Card image">
                    <?php else: ?>
                    <img class="card-img-top" src="../distributor/foto/nophoto.png" alt="Card image">
                    <?php endif ?>
                    <div class="card-body">
                        <h6 class="card-title"><?php echo $data['namaproduk']; ?></h6>
                        <?php if ($data['status'] == 0): ?>
                        <p class="card-text">
                            <?php if ($data['idkategori'] >= 51): ?>
                            -
                            <?php else: ?>
                            Rp. <?php echo number_format($data['harga']); ?>
                            <?php endif ?>
                        </p>
                        <p class="card-text">
                            (<?php echo $data['stock']; ?>) Pcs&nbsp;&nbsp;&nbsp;
                            <a href="add_chart3.php?namaproduk=<?php echo $data['namaproduk']; ?>&id=<?php echo $data['idproduk']; ?>&harga=<?php echo $data['harga']; ?>" class="btn btn-primary">Beli</a>
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
        <!-- PAGINATION END -->
    <!-- MAIN CONTENT END -->
    <br><br><br><br>
    
    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include 'menubawahstore.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>