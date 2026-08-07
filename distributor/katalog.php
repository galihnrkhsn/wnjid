<?php
    session_start();

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
    <title>Distributor | WNJ.ID</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-2">
        <form method="post" class="form-inline justify-content-between mb-3">
            <div class="form-group mr-2 mb-2" style="flex: 1;">
                <input type="text" class="form-control" name="namaproduk" placeholder="Masukkan Nama Produk ..." style="width: 100%;" /> 
            </div> 
            <button class="btn btn-primary mr-2" name="cari" type="submit" style="width:15%;">
                <span><i class="fa-solid fa-magnifying-glass"></i></span>
            </button> 
            <button class="btn btn-primary mr-2" name="tampil" type="submit">Tampil Semua</button>
            <a class="btn btn-info" data-toggle="modal" data-target="#modalForm2" style="color: white;">
                <i class="fa fa-info"></i> Info Stock
            </a>
        </form>
    </div>
        <!-- MODAL -->
        <div class="modal fade" id="modalForm2" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <!-- MODAL HEADER -->
                <div class="modal-header">
                    <p class="modal-title" id="labelModalKu">Info Stock</p>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Tutup</span>
                    </button>
                </div>
                <!-- MODAL BODY -->
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="w3-table-all" id="tb_store">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>stock</th>
                                    <th>produk</th>
                                </tr>
                            </thead>  
                            <tbody>
                                <?php
                                include "koneksi.php";
                                
                                $sql = mysqli_query($koneksi, "SELECT * from produk WHERE stock>0 and harga>0 and idkategori>0 and status=0 ORDER BY idproduk DESC");
                                
                                $no = 1;
                                while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
                                ?>
                                
                                    <tr>
                                    <td><?= $no++; ?></td>
                                    <td style="width:20%"><?php echo $data['stock']; ?> </td>
                                    <td style="width:80%"><?php echo $data['namaproduk']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- MODAL BODY END -->
                <!-- MODAL FOOTER -->
                <div class="modal-footer">
                    <a href="katalog.php" type="button" class="btn btn-danger">&times; Tutup</a>
                </div>
                <!-- MODAL FOOTER END -->
            </div>
            </div>
        </div>
        <!-- MODAL END -->
        <div class="container mt-5">
            <div class="row">
                <?php
                    if(isset($_POST["cari"])){
                        include "koneksi.php";
                        $namaproduk=$_POST['namaproduk'];
                        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
                        $limit = 20;
                        $limit_start = ($page - 1) * $limit;
                        $sql = mysqli_query($koneksi, "SELECT * FROM katalog WHERE namaproduk LIKE '%$namaproduk%' ORDER BY idkatalog DESC LIMIT $limit_start, $limit");
                        $no = $limit_start + 1;
                    } elseif(isset($_POST["tampil"])){
                        include "koneksi.php";
                        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
                        $limit = 20;
                        $limit_start = ($page - 1) * $limit;
                        $sql = mysqli_query($koneksi, "SELECT * FROM katalog ORDER BY idkatalog DESC LIMIT $limit_start, $limit");
                        $no = $limit_start + 1;
                    } else {
                        include "koneksi.php";
                        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
                        $limit = 20;
                        $limit_start = ($page - 1) * $limit;
                        $sql = mysqli_query($koneksi, "SELECT * FROM katalog ORDER BY idkatalog DESC LIMIT $limit_start, $limit");
                        $no = $limit_start + 1;
                    }
                    while($data = mysqli_fetch_array($sql)){
                ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-6">
                    <div class="card mb-5">
                        <a href="detailproduk.php?idkatalog=<?=$data['idkatalog']; ?>" target="_blank">
                            <img class="card-img-top" src="../image/katalog/<?php echo $data['foto']; ?>" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title" style="color: #4a4a4a;"><?php echo $data['namaproduk']; ?></h5>
                                <p class="card-text" style="color: red;">Rp. <?php echo str_replace("-"," - Rp. ",str_replace(".0000","0.000",str_replace("..000",".000",str_replace("000",".000",str_replace("0000","0.000",$data['harga']))))); ?></p>
                                <i class="fas fa-eye" class="btn btn-primary btn-sm" style="color: #7e7fe5;"> Detail</i>
                            </div>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>
            <!-- Pagination -->
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
            
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
        <!-- Jquery, Popper, Bootstrap -->
        <script src="../vendor/legacy-js/modernizr-3.5.0.min.js"></script>
        <script src="../vendor/legacy-js/jquery-1.12.4.min.js"></script>
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
    <!-- SCRIPT END -->

    <?php include "settingdatatables.php"; ?>


</body>
</html>