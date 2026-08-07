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
    <!-- Page Heading -->
    <div class="container mt-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <a href="javascript:void(0);" onclick="history.back();" style="float: right;">
                <i class="fas fa-arrow-left fa-m"> Kembali</i>
            </a>
            <h2 class="m-0 font-weight-bold text-secondary">List Po Pembayaran</h2>
        </div>
        <!-- Content Row -->
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <div class="form-group">
                    <label for="namapo">Nama PO</label>
                    <select class="form-control" name="namapo" id="namapo">
                        <option enabled selected>- Pilih Nama PO -</option>
                        <?php
                        $datadb = $koneksi->query("SELECT * FROM poproduk ORDER BY idpoproduk DESC");
                        while ($tampilkan = $datadb->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $tampilkan['idpoproduk']; ?>"><?php echo $tampilkan['namapo']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group" id="tabel_dp" name="tabel_dp"></div>
            </div>
        </div><!-- Content Row -->

        <?php include 'template/footer.php'; ?>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#namapo').change(function(){
                var namapo = $('#namapo').val();
                $.ajax({
                    type: 'GET',
                    url: 'cek_dp_po.php',
                    data: { namapo: namapo },
                    success: function (data) {
                        $("#tabel_dp").html(data);
                    }     
                });
            });
        });
    </script>

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