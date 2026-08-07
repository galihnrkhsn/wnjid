<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    $idmanage    = $_SESSION["idmanage"];
    $tipe        = $_SESSION["user_tipe"];

    $queryManage = $koneksi->query("SELECT * FROM management WHERE id='$idmanage'");
    $data = $queryManage->fetch_assoc();

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
    <div class="container pt-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <a href="javascript:void(0);" onclick="history.back();" style="float: right;">
                <i class="fas fa-arrow-left fa-m"> Kembali</i>
            </a>
            <h2 class="m-0 font-weight-bold text-secondary">List Po Artikel</h2>
        </div>
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tb_listpo_artikel">
                    <thead>
                        <tr>
                            <th style="width:1%">No</th>
                            <th>Nama PO (ID)</th>
                            <th>Totalan PO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $datapo=$koneksi->query("SELECT * from poproduk WHERE idpoproduk > 100 order by idpoproduk desc ");
                            $no=1;

                            while($tampilkan=$datapo->fetch_assoc()){
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <a href="listpovariant.php?id=<?php echo $tampilkan['idpoproduk']; ?>">
                                    <?php echo $tampilkan['namapo']; ?> (<?php echo $tampilkan['idpoproduk']; ?>)
                                </a>
                            </td>
                            <td>
                                <?php 
                                    if ($tampilkan['idpoproduk']==87 or $tampilkan['idpoproduk']==90): ?>
                                        <a href="totalanpomiki.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target=blank()> Totalan Pembayaran <?php echo $tampilkan['namapo']; ?> </a>
                                <?php 
                                    elseif ($tampilkan['idpoproduk']==88 or $tampilkan['idpoproduk']==89 or $tampilkan['idpoproduk']==93): ?>
                                        <a href="totalanpobrooch.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target=blank()> Totalan Pembayaran <?php echo $tampilkan['namapo']; ?> </a>
                                <?php 
                                    elseif ($tampilkan['idpoproduk']==102 or $tampilkan['idpoproduk']==105): ?>
                                        <a href="totalanpokolibricustom.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target=blank()> Totalan Pembayaran <?php echo $tampilkan['namapo']; ?> </a>
                                <?php 
                                    elseif ($tampilkan['idpoproduk']==114): ?>
                                        <a href="totalanporompi.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target=blank()> Totalan Pembayaran <?php echo $tampilkan['namapo']; ?> </a>
                                <?php 
                                    else : ?>
                                        <a href="totalanpo.php?id=<?php echo $tampilkan['idpoproduk']; ?>" target=blank()> Totalan Pembayaran <?php echo $tampilkan['namapo']; ?> </a>
                                <?php 
                                    endif 
                                ?>
                            </td>
                        </tr>
                        <?php 
                            } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include "assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <!-- END SCRIPT -->
</body>
</html>