<?php
    session_start();
    include 'koneksi.php';
    $invoice        = $_GET["id"];
    $datamitra      = $koneksi->query("SELECT pomitra.idpoproduk
                                    FROM pomitra
                                    where pomitra.invoice='$invoice'
                                ");  
    $mitra          = $datamitra->fetch_array();
    $idpoproduk     = $mitra['idpoproduk'];
    $datamitra      = $koneksi->query("SELECT pomitra.idpoproduk,admin_mitra.namamitra,
                                            admin_mitra.idadmin,
                                            admin_mitra_cs.namacs,
                                            mitraagen.namaagen AS agen,
                                            mitrareseller.namaagen AS reseller,
                                            mitramarketer.namaagen AS marketer
                                        FROM pomitra
                                        LEFT JOIN mitraagen ON pomitra.idmitraagen = mitraagen.idmitraagen
                                        LEFT JOIN mitrareseller ON mitrareseller.idmitrareseller = pomitra.idmitrareseller
                                        LEFT JOIN mitramarketer ON pomitra.idmitramarketer = mitramarketer.idmitramarketer
                                        LEFT JOIN admin_mitra ON admin_mitra.idadmin = pomitra.idmitra 
                                        OR mitraagen.idadmin = admin_mitra.idadmin
                                        OR mitrareseller.idadmin = admin_mitra.idadmin 
                                        OR mitramarketer.idadmin = admin_mitra.idadmin
                                        LEFT JOIN admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin
                                        where pomitra.invoice='$invoice'
                                    ");

    $tampilnama     = $datamitra->fetch_assoc();
    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
<body id="page-top" class="sidebar-toggled">
    <!-- Page Wrapper -->
    <div id="wrapper">

    <?php include "sidebar.php"; ?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <h3>
            <strong>
                <a href="listpodropship.php?id=<?= $mitra['idpoproduk'] ?>">
                    <span class="fa fa-chevron-left"></span> Kembali
                </a>
            </strong>
        </h3>

        <table>
            <tr>
                <th>Invoice</th>
                <td>:</td>
                <td><?php echo $invoice; ?></td>
            </tr>

            <tr>
                <th>Mitra DB</th>
                <td>:</td>
                <td><?= $tampilnama['namamitra']; ?>(<?= $tampilnama['idadmin']; ?>)</td>
            </tr>

            <tr>
                <th>Mitra Sub-DB</th>
                <td>:</td>
                <td><?= $tampilnama['agen']; ?><?= $tampilnama['reseller']; ?><?= $tampilnama['marketer']; ?></td>
            </tr>

            <tr>
                <th>CS</th>
                <td>:</td>
                <td><?= $tampilnama['namacs']; ?></td>
            </tr>
        </table>

        <!-- Content Row -->
        <div class="">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#home" class="nav-item nav-link active">
                        Daftar Dropship
                    </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#menu1" class="nav-item nav-link">
                        Sumary Dropship
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="home" class="tab-pane fade show active" role="tabpanel"> 
                    <div class="table-responsive mt-4">
                        <div class="mb-3">
                            <input type="hidden" class="form-control form-control-sm" value="<?= $mitra['invoice'] ?>" name="parse">
                            <input type="hidden" class="form-control form-control-sm" value="<?= $tampilnama['namacs']; ?>" name="namacs">
                            <button type='submit' class="btn btn-success mb-4" onclick="redirectPrint('alamat')">
                                Cetak Alamat
                            </button>
                            <button type='submit' class="btn btn-info mb-4" onclick="redirectPrint('suratjalan')">
                                Cetak Surat Jalan
                            </button>
                            <button type='submit' class="btn btn-warning mb-4" onclick="redirectPrint('batalproses')">
                                Batal Proses
                            </button>
                            <input type='submit' class="btn btn-danger mb-4" value='Hapus Dropship' name='but_hapus' onclick="return confirm('Yakin Akan Menghapus Dropship?');">      
                            <table class="table table-bordered" id="tb_pods">
                                <thead>
                                    <tr>
                                        <th><input type='checkbox' id='checkAll' > Check</th>
                                        <th>Inv Dropship</th>
                                        <th style="width: 30%">Data Pengiriman</th>
                                        <th>Alamat Penerima</th>
                                        <?php if ($idpoproduk==186 || $idpoproduk == '276' || $idpoproduk == '278' || $idpoproduk == '281' || $idpoproduk == '283' || $idpoproduk == '286' || $idpoproduk == '287'): ?> 
                                            <th>Kartu Ucapan</th>
                                        <?php else: ?>
                                            <th>Keterangan</th>
                                        <?php endif ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $datapodropship = $koneksi->query("SELECT * FROM podropship 
                                                                            INNER JOIN poproduk
                                                                            ON poproduk.idpoproduk = podropship.idpoproduk 
                                                                            WHERE podropship.invoice = '$invoice'
                                                                        ");
                                        $no = 1;
                                        while($tampilkan=$datapodropship->fetch_assoc()){
                                            $id = $tampilkan['iddropship'];
                                            $no_ds= $tampilkan['no_ds'];
                                    ?>
                                        <tr>
                                            <td>
                                                <input type='checkbox' name='update[]' value='<?= $id ?>' >
                                                <input type='hidden' name='invoice[]' value='<?php echo $tampilkan['no_ds']; ?>' >
                                            </td>
                                            <td>
                                                <?php if ($tampilkan['no_ds']) : ?>
                                                    <a href="detail_ds_inv.php?id=<?php echo $tampilkan['no_ds']; ?>"target="blank"><?php echo $tampilkan['no_ds']; ?></a>
                                                <?php else : ?>
                                                    <a href="input_detail_ds_inv.php?id=<?php echo $id; ?>"target="blank">Tambah Detail</a>
                                                <?php endif; ?>
                                                    <br>
                                                    <a href="ubahdropship.php?id=<?= $id; ?>">Ubah Dropship</a>
                                                <?php if ($tampilkan['proses']) : ?>
                                                    <br>
                                                    <span class="badge bg-success text-white"><?= $tampilkan['proses']; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <p class="p-0 m-0"><strong>Pengirim</strong></p>
                                                <p class="p-0 m-0"><?= $tampilkan['namapengirim']; ?></p>
                                                <p class="p-0 m-0"><?php echo $tampilkan['tlppengirim']; ?></p>
                                                <p class="p-0 m-0"><strong>Penerima</strong></p>
                                                <p class="p-0 m-0"><?php echo $tampilkan['namapenerima']; ?></p>
                                                <p class="p-0 m-0"><?php echo $tampilkan['tlppenerima']; ?></p>
                                            </td>
                                            <td>
                                                <p class="p-0 m-0"><strong>Ekspedisi</strong></p>
                                                <p class="p-0 m-0"><?php echo strtoupper($tampilkan['ekspedisi']); ?></p>
                                                <p class="p-0 m-0"><strong>Alamat</strong></p>
                                                <p class="p-0 m-0"><?php echo $tampilkan['alamatpenerima']; ?></p>
                                            </td>
                                            <?php if ($idpoproduk==186 || $idpoproduk == '283'): ?> 
                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?= $no_ds; ?>">
                                                        Kartu Ucapan
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="exampleModal<?= $no_ds; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Inv. <?= $no_ds; ?></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <strong>Keterangan Ucapan Box</strong>
                                                                    <br>
                                                                    <?php
                                                                        $ambil_ucapan=$koneksi->query("SELECT *
                                                                                                        FROM hampers
                                                                                                        WHERE hampers.no_ds= '$no_ds'
                                                                                                        ORDER BY nobox ASC
                                                                                                    "); 
                                                                        while($data_ucapan=$ambil_ucapan->fetch_assoc()){
                                                                            $result_explode = explode('|', $data_ucapan['ucapan']);
                                                                            $dari=$result_explode[0];
                                                                            $kepada=$result_explode[1];
                                                                            $ucapan=$result_explode[2];
                                                                    ?>
                                                                        <?php if ($idpoproduk == '276' || $idpoproduk == '278' || $idpoproduk == '281' || $idpoproduk == '283' || $idpoproduk == '286' || $idpoproduk == '287') : ?>
                                                                            <strong>Ucapan Item <?= $data_ucapan['nobox'] ?> :</strong>
                                                                        <?php elseif ($idpoproduk == '186') : ?>
                                                                            <strong>Ucapan Box <?= $data_ucapan['nobox'] ?> :</strong>
                                                                        <?php endif; ?>
                                                                        <table>
                                                                            <tr>
                                                                                <th>Dari</th>
                                                                                <td>:</td>
                                                                                <td><?= $dari; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Kepada</th>    
                                                                                <td>:</td>
                                                                                <td><?= $kepada; ?></td>
                                                                            </tr>  
                                                                            <tr >
                                                                                <th style=" vertical-align: top;">Ucapan</th>    
                                                                                <td>:</td>
                                                                                <td><?= $ucapan; ?>
                                                                                </td>
                                                                            </tr>   
                                                                        </table>
                                                                        <hr>
                                                                    <?php } ?>
                                                                </div>
                                                                
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </td>
                                            <?php else: ?>                    
                                                <td>
                                                    <?php echo $tampilkan['keterangan']; ?>
                                                </td>
                                            <?php endif ?>                    
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                        
                <div id="menu1" class="tab-pane fade">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tb_sumary">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Produk</th>
                                    <th>Stok Invoice</th>
                                    <th>Jumlah Distribusi</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                    <th>Invoice</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                                $sql = mysqli_query($koneksi, "SELECT podetail.variant,
                                                                        pomitra.idpodetail,
                                                                        pomitra.jumlah,
                                                                        pomitra.idpomitra,
                                                                        pomitra.custom
                                                                    FROM pomitra
                                                                    JOIN podetail
                                                                    ON podetail.idpodetail = pomitra.idpodetail
                                                                    WHERE pomitra.invoice='$invoice'
                                                                    AND pomitra.jumlah > 0
                                                                ");
                                $no = 1;
                                while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
                                    $idpodetail = $data['idpodetail'];
                                    $idpomitra = $data['idpomitra'];
                                    $jumlah_pomitra = $data['jumlah'];
                                    
                                    if ($idpodetail==8920) {
                                        $data_jumlah=$koneksi->query("SELECT SUM(pods.jumlah) AS jumlahnya, pomitra.idpomitra
                                                                        FROM pods JOIN pomitra
                                                                        ON pomitra.idpomitra = pods.idpomitra
                                                                        WHERE pods.invoice='$invoice'
                                                                        AND pods.idpodetail = '$idpodetail'
                                                                        AND pods.idpomitra = '$idpomitra'
                                                                    ");
                                        $tampilprogres=$data_jumlah->fetch_assoc();                     
                                    } else {
                                        $data_jumlah=$koneksi->query("SELECT SUM(pods.jumlah) AS jumlahnya
                                                                        FROM pods 
                                                                        WHERE pods.invoice = '$invoice'
                                                                        and pods.idpodetail = '$idpodetail'
                                                                    ");
                                        $tampilprogres=$data_jumlah->fetch_assoc();  
                                    }            
                                    $jmlh_pods = $tampilprogres['jumlahnya'];
                                    $sisa = $jumlah_pomitra-$jmlh_pods;  
                                    $total_sisa +=$sisa;
                                    $total_jumlah +=$data['jumlah'];
                                    $total_proses += $tampilprogres['jumlahnya'];      
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['variant'];?> <?php if ($data['custom']): ?>
                                        <?= $data['custom'];?>
                                    <?php endif ?></td>
                                    <td><?= $data['jumlah'];?></td>
                                    <td><?= $tampilprogres['jumlahnya'];?></td>
                                    <td><?= $sisa; ?></td>
                                    <td>
                                        <?php if($sisa==0):?>
                                            <span class="badge bg-success text-white">Sesuai</span>
                                        <?php elseif($sisa<0):?>
                                            <span class="badge bg-danger text-white">Lebih</span>
                                        <?php elseif($sisa>0):?>
                                            <span class="badge bg-info text-white">Ada Stok</span>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <?php
                                            $sql_inv = mysqli_query($koneksi, "SELECT pods.no_ds 
                                                                                FROM pods WHERE pods.invoice='$invoice'
                                                                                AND pods.idpodetail='$idpodetail'
                                                                            ");
                                            while($data_inv = mysqli_fetch_array($sql_inv)){ 
                                        ?>
                                            <a href="detail_ds_inv.php?id=<?= $data_inv['no_ds']; ?>" target="_blank()" ><?= $data_inv['no_ds'];?></a>
                                        <?php } ?>
                                    </td>
                                </tr>           
                            <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td><?= $total_jumlah; ?></td>
                                    <td><?= $total_proses; ?></td>
                                    <td><?= $total_sisa; ?></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website 2020</span>
            </div>
            </div>
        </footer>
        <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/adminwnj/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            // Check/Uncheck ALl
            $('#checkAll').change(function(){
                if($(this).is(':checked')){
                    $('input[name="update[]"]').prop('checked',true);
                }else{
                    $('input[name="update[]"]').each(function(){
                        $(this).prop('checked',false);
                    }); 
                }
            });

            // Checkbox click
            $('input[name="update[]"]').click(function(){
                var total_checkboxes = $('input[name="update[]"]').length;
                var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                if(total_checkboxes_checked == total_checkboxes){
                    $('#checkAll').prop('checked',true);
                }else{
                    $('#checkAll').prop('checked',false);
                }
            });
        });
    </script>

    <?php include "settingdatatables.php"; ?>

    <?php
        if (isset($_POST['but_export'])) {
            date_default_timezone_set('Asia/Jakarta');
            $tanggal = date('Y-m-d');
            $invoice = $_GET['id'];
            if (isset($_POST['update'])) {
                $count = count($_POST['update']);
                for ($x = 0; $x < $count; $x++) {
                    $iddropship = $_POST['update'][$x];
                    var_dump($iddropship);
                    die();
                    $sql = $koneksi->query("SELECT 
                                                    podropship.namapengirim,
                                                    podropship.tlppengirim,
                                                    podropship.namapenerima,
                                                    podropship.tlppenerima,
                                                    podropship.alamatpenerima,
                                                    podropship.invoice,
                                                    podropship.idpoproduk,
                                                    podropship.keterangan,
                                                    podropship.no_ds,
                                                    podropship.ekspedisi,
                                                    podropship.layanan,
                                                    admin_mitra.namamitra,
                                                    admin_mitra.idadmin,
                                                    poproduk.namapo,
                                                    poproduk.idpoproduk,
                                                    mitraagen.idmitraagen,
                                                    mitraagen.idadmin,
                                                    mitrareseller.idmitrareseller,
                                                    mitrareseller.idadmin,
                                                    mitramarketer.idmitramarketer,
                                                    mitramarketer.idadmin,
                                                    tb_ro_provinces.province_name AS provinsi,
                                                    tb_ro_cities.city_name AS kota,
                                                    tb_ro_subdistricts.subdistrict_name AS kecamatan
                                                FROM
                                                    podropship
                                                        LEFT JOIN
                                                    tb_ro_provinces ON podropship.provinsi = tb_ro_provinces.province_id
                                                        LEFT JOIN
                                                    tb_ro_cities ON podropship.kota = tb_ro_cities.city_id
                                                        LEFT JOIN
                                                    tb_ro_subdistricts ON podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                                                        JOIN
                                                    poproduk ON podropship.idpoproduk = poproduk.idpoproduk
                                                        JOIN
                                                    admin_mitra ON podropship.idadmin = admin_mitra.idadmin
                                                        JOIN
                                                    mitraagen ON podropship.idmitraagen = mitraagen.idmitraagen
                                                        JOIN
                                                    mitrareseller ON podropship.idmitrareseller = mitrareseller.idmitrareseller
                                                        JOIN
                                                    mitramarketer ON podropship.idmitramarketer = mitramarketer.idmitramarketer
                                                WHERE
                                                    podropship.iddropship = '$iddropship'
                                            ");
                    $data               = $sql->fetch_assoc();
                    $pengirim           = $data['namapengirim'];
                    $telp_pengirim      = $data['tlppengirim'];
                    $penerima           = $data['namapenerima'];
                    $telp_penerima      = $data['tlppenerima'];
                    $alamat             = $data['alamatpenerima'] . ", " . $data['kecamatan'] . ", " . $data['kota'] . ". " . $data['provinsi'];
                    $idadmin            = $data['idadmin'];
                    $namacs             = $tampilnama['namacs'];
                    $ekspedisi          = $data['ekspedisi'];
                    $keterangan         = $data['keterangan'];
                    $namamitra          = $data['namamitra'];
                    $idmitraagen        = isset($data['idmitraagen']) ? $data['idmitraagen'] : NULL;
                    $idmitrareseller    = isset($data['idmitrareseller']) ? $data['idmitrareseller'] : NULL;
                    $idmitramarketer    = isset($data['idmitramarketer']) ? $data['idmitramarketer'] : NULL;
                    
                    $update = $koneksi->query("UPDATE podropship SET proses = 'Proses' WHERE iddropship = '$iddropship'");

                    if ($update) {
                        $ins_logistik = $koneksi->query("INSERT INTO logistik3 
                                                            (
                                                                `idkeranjang`,
                                                                `idproduk`,`idmitra`,`idagen`,`idreseller`,
                                                                `idmarketer`,`jmlh`,`harga`,`subtotal`,`tgl`,
                                                                `waktu`,`status`,`variant`
                                                            )
                                                            VALUES
                                                            (
                                                                NULL, '$tanggal', '$penerima',
                                                                '$ekspedisi', NULL, '0', '0',
                                                                '$keterangan', NULL, '$idadmin',
                                                                '$idmitraagen', '$idmitrareseller', '$idmitramarketer',
                                                                '$namacs', NULL, 'WNJ'
                                                            )
                                                    ");
                        $sql_logistik = $koneksi->query("SELECT MAX(idlogistik) AS idlogistik FROM logistik");
                        $data_logistik = $sql_logistik->fetch_assoc();
                        $idlogistik = $data_logistik['idlogistik'];
                        $ins_resi = $koneksi->query("INSERT INTO t_user VALUES
                                                        (
                                                            NULL, '$idlogistik', '$namacs',
                                                            '$pengirim', '$telp_pengirim',
                                                            '$penerima', '$telp_penerima',
                                                            '$alamat', '$keterangan', '$ekspedisi', '$invoice',
                                                            NULL, '$tanggal', NOW(),
                                                            NULL, '0', NULL, NULL,
                                                            '$namamitra', '$idadmin', NULL, 'WNJ'
                                                        )
                                                    ");
                        if ($ins_logistik && $ins_resi) {
                            echo "
                                <script>
                                    alert('Data berhasil ditambahkan ke portal dan siap dicetak');
                                    location='print.php?id=$iddropship'
                                </script>
                            ";
                        } else {
                            echo "
                                <script>
                                    alert('Data Gagal dimasukan!');
                                    location='print.php?id=$iddropship'
                                </script>
                            ";
                        }
                    } else {
                        echo "
                            <script>
                                alert('Data gagal ditambahkan ke portal!')
                                location='detaildropship.php?id=$invoice'
                            </script>
                        ";
                    }
                }
            } else {
                echo "
                    <script>
                        alert('Jangan lupa checklist!')
                        location='detaildropship.php?id=$invoice'
                    </script>
                ";
            }
        } elseif (isset($_POST['but_inv'])) {
            try {
                date_default_timezone_set('Asia/Jakarta');
                $tanggal = date('Y-m-d');
                if (isset($_POST['update'])) {
                    $count = count($_POST['update']);
                    for ($x = 0; $x < $count; $x++) {
                        $iddropship = $_POST['update'][$x];
                        
                        echo "
                            <scrtip>
                                location=''
                            </scrtip>
                        ";
                    }
                } else {
                    echo "
                        <script>
                            alert('Jangan lupa checklist!')
                            location='detaildropship.php?id=$invoice'
                        </script>
                    ";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>

<script>
    function redirectPrint(type) {
        let checked = document.querySelectorAll('input[name="update[]"]:checked');

        if (checked.length === 0) {
            alert('Pilih minimal 1 data');
            return;
        }

        let params = new URLSearchParams();

        checked.forEach((item) => {
            params.append('ids[]', item.value);
        });

        params.append('type', type);

        window.open(
            'multiprintds3.php?' + params.toString(),
            '_blank'
        );
    }
</script>
</body>
</html>