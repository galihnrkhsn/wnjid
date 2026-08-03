<?php 
    session_start();
    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesManage.php';

    $idmanage    = $_SESSION["idmanage"];
    $tipe        = $_SESSION["user_tipe"];
    $idpoproduk  = $_GET["id"];

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
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <a href="javascript:void(0);" onclick="history.back();" style="float: right;">
                <i class="fas fa-arrow-left fa-m"> Kembali</i>
            </a>
            <h2 class="m-0 font-weight-bold text-secondary">List Po Per Invoice</h2>
        </div>
        <!-- Content Row -->
        <div class="row" style="margin: auto;">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tbmaximus">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama PO</th>
                            <th>Nama DB</th>
                            <th>Sub DB</th>
                            <th>Kemitraan</th>
                            <th>Status PO</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $datapo=$koneksi->query("SELECT 
                                                          MIN(admin_mitra.idadmin) AS idadmin,
                                                          admin_mitra.namamitra,
                                                          poproduk.namapo,
                                                          CONCAT_WS(' ', mitraagen.namaagen, mitrareseller.namaagen, mitramarketer.namaagen) AS kemitraan,
                                                          pomitra.invoice,
                                                          pomitra.status,
                                                          pomitra.tgl,
                                                          poproduk.idpoproduk
                                                      FROM 
                                                          pomitra 
                                                      LEFT JOIN 
                                                          mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen 
                                                      LEFT JOIN 
                                                          mitrareseller ON pomitra.idmitrareseller = mitrareseller.idmitrareseller 
                                                      LEFT JOIN 
                                                          mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer 
                                                      LEFT JOIN 
                                                          admin_mitra ON pomitra.idmitra = admin_mitra.idadmin 
                                                          OR mitraagen.idadmin = admin_mitra.idadmin 
                                                          OR mitrareseller.idadmin = admin_mitra.idadmin 
                                                          OR mitramarketer.idadmin = admin_mitra.idadmin 
                                                      INNER JOIN 
                                                          poproduk ON pomitra.idpoproduk = poproduk.idpoproduk 
                                                      WHERE 
                                                          pomitra.jumlah > 0 
                                                          AND pomitra.idpoproduk = '$idpoproduk' 
                                                      GROUP BY 
                                                          pomitra.invoice,
                                                          admin_mitra.namamitra,
                                                          poproduk.namapo,
                                                          CONCAT_WS(' ', mitraagen.namaagen, mitrareseller.namaagen, mitramarketer.namaagen),
                                                          pomitra.status,
                                                          pomitra.tgl,
                                                          poproduk.idpoproduk
                                                      ORDER BY 
                                                          MIN(pomitra.idpomitra) DESC
                                                      LIMIT 0, 50000;
");
                            $no=1;
                            while($tampilkan=$datapo->fetch_assoc()){
                        ?>
                        <tr>
                            <td><strong><?php echo $no++; ?></strong></td>     
                            <td><i class="fas fa-calendar" style="color: red"></i> <?php echo $tampilkan['tgl']; ?></td>
                            <td><?php echo $tampilkan['namapo']; ?></td>
                            <td><i class="fas fa-user"></i> <?php echo $tampilkan['namamitra']; ?></td>
                            <td><i class="fas fa-users"></i> <?php echo $tampilkan['agen']; ?> <?php echo $tampilkan['reseller']; ?> <?php echo $tampilkan['marketer']; ?></td>
                            <td class="align-middle">
                                <?php 
                                    if($tampilkan['distributor']<>''){ echo "<div class='badge bg-info text-white rounded-pill'>Distributor</div>";}
                                    if($tampilkan['agen']<>''){ echo "<div class='badge bg-info text-white rounded-pill'>Agen</div>";}
                                    if($tampilkan['reseller']<>''){ echo "<div class='badge bg-warning text-white rounded-pill'>Reseller</div>";}
                                    if($tampilkan['marketer']<>''){ echo "<div class='badge bg-danger text-white rounded-pill'>Marketer</div>";}
                                    if($tampilkan['agen']=='' and $tampilkan['reseller']=='' and $tampilkan['marketer']=='') { 
                                        echo "<div class='badge bg-success text-white rounded-pill'>Distributor</div>";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php if ($tampilkan['status']=='Belum DP'): ?>
                                    <div class="badge bg-danger text-white rounded-pill"><?php echo $tampilkan['status']; ?></div>                                    </div>
                                <?php endif ?>
                                <?php if ($tampilkan['status']=='Belum Acc DB'): ?>
                                    <div class="badge bg-warning text-white rounded-pill"><?php echo $tampilkan['status']; ?></div>
                                <?php endif ?> 
                                <?php if ($tampilkan['status']=='Sudah Confirm DP'): ?>
                                    <div class="badge bg-success text-white rounded-pill"><?php echo $tampilkan['status']; ?></div>
                                <?php endif ?>  
                                <?php if ($tampilkan['status']=='Sudah Konfirmasi Pembayaran 1'): ?>
                                    <div class="badge bg-success text-white rounded-pill"><?php echo $tampilkan['status']; ?></div>
                                <?php endif ?> 
                                <?php if ($tampilkan['status']=='Sudah Konfirmasi Pembayaran 2'): ?>
                                    <div class="badge bg-success text-white rounded-pill"><?php echo $tampilkan['status']; ?></div>
                                <?php endif ?> 
                                <?php if ($tampilkan['status']=='Sudah Konfirmasi Pembayaran 3'): ?>
                                    <div class="badge bg-success text-white rounded-pill"><?php echo $tampilkan['status']; ?></div>
                                <?php endif ?>  
                            </td>
                            <td>
                                <a href="detailinvoice.php?invoice=<?php echo $tampilkan['invoice']; ?>&idpoproduk=<?php echo $tampilkan['idpoproduk']; ?>" target=_blank()>
                                    <?php echo $tampilkan['invoice']; ?>   
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>       
        </div><!-- Content Row -->

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