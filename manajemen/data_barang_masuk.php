<?php 
    session_start();
    $namalengkap= $_SESSION["management"]["namalengkap"];
    $title = $namalengkap;
    include 'template/header.php'; 

    if(!isset($_SESSION["management"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login';</script>";
        header('location:login.php');
        exit();
    }

    $iduser= $_SESSION["management"]["id"];
    $idvendor = $_GET['vendor'];
    $idpoproduk = $_GET['id'];
    $sql = "SELECT * FROM management WHERE id='$iduser'";
    $query = $koneksi->query($sql);
    $data = $query->fetch_assoc();
    $sql_vendor = "SELECT * FROM vendor WHERE id='$idvendor'";
    $query_vendor = $koneksi->query($sql_vendor);
    $data_vendor = $query_vendor->fetch_assoc();
    $namavendor = $data_vendor['vendor'];
    $tipe = $data['tipe'];
?>

<?php
    include 'template/topbar.php'; 
?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h2 class="m-0 font-weight-bold text-secondary">Vendor <?= $namavendor; ?></h2>
        <h2>
            <a href="index.php" style="float: right;"><i class="fas fa-arrow-left fa-m "></i></a>
        </h2>
    </div>
                 
    <!-- Content Row -->
    <div class="row" style="margin: auto;">
        <div class="table-responsive">
            <form method="post" action="print_sjk.php" target="_blank()">  
                <table class="table table-bordered table-striped" id="tb_vendor">
                    <thead>
                        <tr>
                            <th>No SJK</th>
                            <th>Nama PO</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $no=1;
                            $ambil=$koneksi->query("SELECT sjk.sjk,
                                                            SUM(sjk.jumlah) as jumlah,
                                                            poproduk.namapo,
                                                            sjk.image
                                                        FROM sjk
                                                        INNER JOIN poproduk
                                                        ON poproduk.idpoproduk = sjk.idpoproduk
                                                        WHERE sjk.idpoproduk = '$idpoproduk'
                                                        AND sjk.vendor ='$idvendor'
                                                        GROUP BY sjk.sjk
                                                    ");
                            while($tampil=$ambil->fetch_assoc()){
                                $id = $tampil['sjk'];                  
                        ?>
                            <tr>
                                <td><a href="detail_sjk.php?id=<?= $tampil['sjk']; ?>"><?= $tampil['sjk']; ?></a></td>
                                <td><?= $tampil['namapo']; ?></td>
                                <td><?= $tampil['jumlah']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <!-- Content Row -->

<?php
    include 'template/footer.php';
?>