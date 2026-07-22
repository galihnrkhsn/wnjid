<?php 
    error_reporting(E_ALL);
    ini_set('error_reporting', 1);
    session_start();

    include 'koneksi.php'; 

    if(!isset($_SESSION["administrator"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }
    $invoice    = $_GET["invoice"];
    $query      = "SELECT poproduk.idpoproduk,
                        poproduk.namapo,
                        pomitra.waktu,
                        pomitra.tgl,
                        pomitra.status,
                        pomitra.idpodetail,
                        pomitra.idmitra,
                        pomitra.idmitraagen,
                        pomitra.idmitrareseller,
                        pomitra.idmitramarketer
                    FROM pomitra 
                    INNER JOIN poproduk ON poproduk.idpoproduk=pomitra.idpoproduk    
                    WHERE pomitra.invoice = '$invoice'
                ";
    $sqlpo      = mysqli_query($koneksi, $query);  
    $datapo     = mysqli_fetch_array($sqlpo);

    $idpoproduk         = $datapo['idpoproduk'];
    $idmitra            = $datapo['idmitra'];
    $idmitraagen        = $datapo['idmitraagen'];
    $idmitrareseller    = $datapo['idmitrareseller'];
    $idmitramarketer    = $datapo['idmitramarketer'];
    $tgl                = $datapo['tgl'];
    $waktu              = $datapo['waktu'];
    $status             = $datapo['status'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Pusat | Wanoja</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">
</head>

<body id="page-top" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <?php
            $idpoproduk = $datapo['idpoproduk'];
            $bukapo     = $koneksi->query("SELECT jenis_po FROM bukapo WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();
        ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <?php if ($bukapo['jenis_po'] == 'PO tanpa Stok' || $bukapo['jenis_po'] == 'PO dengan Stok' || $bukapo['jenis_po'] == 'PO Konin') : ?>
                            <h1 class="h3 mb-0 text-gray-800"><a class="link" href="detailinvoice.php?invoice=<?= $invoice; ?>&idpoproduk=<?= $idpoproduk; ?>"><i class="fa fa-arrow-left"></i> Kembali</a></h1>
                        <?php elseif ($bukapo['jenis_po'] == 'PO Bundling 2' || $bukapo['jenis_po'] == 'PO Bundling 5') :?>
                            <h1 class="h3 mb-0 text-gray-800"><a class="link" href="detailinvoicebundling2.php?invoice=<?= $invoice; ?>&idpoproduk=<?= $idpoproduk; ?>"><i class="fa fa-arrow-left"></i> Kembali</a></h1>
                        <?php endif; ?>
                    </div>
                    <div class="row w3-container">
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Ubah PO</h6>
                                </div>
                                <div class="card-body">
                                    <?php if ($bukapo['jenis_po'] == 'PO tanpa Stok' || $bukapo['jenis_po'] == 'PO Custom Tab') : ?>
                                        <?php include 'components/tab/potanpastok.php'; ?>
                                    <?php elseif ($bukapo['jenis_po'] == 'PO dengan Stok') : ?>
                                        <?php include 'components/tab/postok.php'; ?>
                                    <?php elseif ($bukapo['jenis_po'] == 'PO Bundling 2') : ?>
                                        <?php include 'components/tab/pobundling2.php'; ?>
                                    <?php elseif ($bukapo['jenis_po'] == 'PO Bundling 5') : ?>
                                        <?php include 'components/tab/pobundling5.php'; ?>
                                    <?php elseif ($bukapo['jenis_po'] == 'PO Konin') : ?>
                                        <?php include 'components/tab/pokonin.php'; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        if (isset($_POST['update'])) {
            try {

                $invoice    = $_POST['invoice'];
                $qty        = $_POST['qty'];
                $bundling   = $_POST['custom'];
        
                if (empty($invoice) || !is_array($qty) || !is_array($bundling)) {
                    die("Data tidak lengkap: " . $koneksi->error);
                }
        
                $count      = count($bundling);
                $stmt       = $koneksi->prepare("UPDATE pomitra SET jumlah = ? WHERE invoice = ? AND TRIM(custom) = ?");
        
                if (!$stmt) {
                    die("Prepare statement gagal: " . $koneksi->error);
                }
                
                if (!$stmt->bind_param("iss", $qty_item, $invoice, $bundling_item)) {
                    die("Bind param gagal: " . $koneksi->error);
                }
        
                for ($i = 0; $i < $count; $i++) {
                    $qty_item       = intval($qty[$i]);
                    $bundling_item  = trim($bundling[$i]);
        
        
                    if (!$stmt->execute()) {
                        die("Gagal Execute data: " . $koneksi->error);
                    }
                }
        
                $stmt->close();
        
                echo "
                    <script>
                        alert('Stok berhasil diubah')
                        location='ubahpo.php?invoice=$invoice'
                    </script>
                ";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        if (isset($_POST['kirim'])) {
            try {
                date_default_timezone_set('Asia/Jakarta');
                $today      = date('s');
                $waktu      = date('H:i:s');
                $qty        = $_POST['bundling_qty'];
                $variant    = $_POST['bundling_variant'];
                $count      = count($variant);

                for ($x = 0; $x < $count; $x++) {
                    $qty_item       = $qty[$x];
                    $variant_item   = $variant[$x];
                    $data           = explode("|", $variant_item);
                    $idpodetail     = $data[0];
                    $idpo           = $data[1];
                    $custom         = $data[2];
                    
                    $query = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail'");
                    $data_produk = $query->fetch_assoc();
                    $total_harga = $data_produk['harga'];

                    $sql = $koneksi->query("INSERT INTO pomitra
                                                    (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu)
                                                VALUES
                                                    (NULL, '$idmitra', '$idpoproduk', '$idpo', '$idpodetail', '$qty_item', '$custom', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')
                                            ");
                    
                    if ($sql) {
                        echo "
                            <script>
                                alert('Data sudah terkirim')
                                location='ubahpo.php?invoice=$invoice'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data gagal terkirim!')
                                location='ubahpo.php?invoice=$invoice'
                            </script>
                        ";
                    }
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    ?>
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Wanoja Development 2020</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/adminwnj/jquery/jquery.min.js"></script>
    <script src="../vendor/adminwnj/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/adminwnj/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>


</body>

</html>
