<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    session_start();
    include 'koneksi.php';
    if (!isset($_SESSION['administrator'])) {
        echo "<script>alert('Anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php'</script>";
        header('location:login.php');
        exit();
    }
    $idpoproduk = $_GET['id'];
    $query      = $koneksi->query("SELECT * FROM poproduk
                                INNER JOIN bukapo ON bukapo.idpoproduk = poproduk.idpoproduk
                                WHERE poproduk.idpoproduk = '$idpoproduk'
                            ");
    $sql        = $query->fetch_assoc();
    $namapo     = $sql['namapo'];
    $jenispo    = $sql['jenis_po'];
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
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
    </head>
    <body id="page-top">

        <div id="wrapper">
            <?php include 'sidebar.php'; ?>
            <div class="content-wrapper d-flex flex-column">
                <div class="content">
                    <div class="container-fluid">
                        
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-0 text-secondary">Tambahan Invoice <?= $namapo ?></h3>
                            <a href="listpokolibri.php?id=<?= $idpoproduk ?>">Kembali</a>
                        </div>

                        <hr class="mb-4" />

                        <!-- Page Main -->
                        <div class="col-xl-12 mb-4">
                            <div class="card shadow rounded">
                                <div class="card-body">
                                    <form method="post">
                                        <?php
                                            $idpoproduk = $_GET['id'];
                                        ?>
                                        <input type="hidden" name="jenis_po" class="form-control form-control-sm" value="<?= $jenispo ?>">
                                        <?php if ($jenispo == "PO tanpa Stok" || $jenispo === 'PO Mandiri') : ?>
                                            <p class="font-weight-semibold text-lg text-primary">Jenis Pre-Order Tanpa Stok</p>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label for="jenis_mitra" class="mb-1">Jenis Mitra</label>
                                                        <select id="jenis_mitra" class="form-control form-control-sm">
                                                            <option selected>~ Default Selected ~</option>
                                                            <option value="Distributor">Distributor</option>
                                                            <option value="Agen">Agen</option>
                                                            <option value="Reseller">Reseller</option>
                                                            <option value="Marketer">Marketer</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="nama_mitra"></div>
                                                    <?php
                                                        $idpoproduk = $_GET['id'];
                                                        $query_variant = $koneksi->query("SELECT 
                                                                                                *
                                                                                            FROM
                                                                                                poproduk
                                                                                                    INNER JOIN
                                                                                                pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                                                    INNER JOIN
                                                                                                podetail ON podetail.idpo = pokategori.idpo
                                                                                            WHERE
                                                                                                poproduk.idpoproduk = '$idpoproduk'
                                                                                        ");
                                                        while ($sql_variant = $query_variant->fetch_assoc()) {
                                                    ?>
                                                        <div class="form-group">
                                                            <label for="<?= $sql_variant['idpodetail'] ?>" class="mb-1"><?= $sql_variant['variant'] ?></label>
                                                            <input type="number" class="form-control form-control-sm" value="0" min="0" name="qty[]" id="<?= $sql_variant['idpodetail'] ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control form-control-sm" value="<?= $sql_variant['idpodetail'] ?>" name="idpodetail[]" id="<?= $sql_variant['idpodetail'] ?>">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php elseif ($jenispo == "PO dengan Stok") : ?>
                                            <p class="font-weight-semibold text-lg text-primary">Jenis Pre-Order Dengan Stok</p>
                                            <hr />
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="font-weight-bold mb-1">Stock</p>
                                                    <div class="row">
                                                        <div class="col-lg-12 row">
                                                            <?php
                                                                $idpoproduk = $_GET['id'];
                                                                $query_stok = $koneksi->query("SELECT * FROM poproduk
                                                                                                    INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                                                    INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                                                                                                    WHERE poproduk.idpoproduk = '$idpoproduk'
                                                                                                    ORDER BY poproduk.idpoproduk
                                                                                            ");
                                                                while ($sql_stok = $query_stok->fetch_assoc()) {
                                                            ?>
                                                                <div class="col-lg-3">
                                                                    <p class="mb-0"><?= $sql_stok['variant'] ?> <span class="font-weight-bold">(<?= $sql_stok['stok'] ?>)</span></p>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                </div>
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label for="jenis_mitra" class="mb-1">Jenis Mitra</label>
                                                        <select name="jenis_mitra" id="jenis_mitra" class="form-control form-control-sm">
                                                            <option selected>~ Default Selected ~</option>
                                                            <option value="Distributor">Distributor</option>
                                                            <option value="Agen">Agen</option>
                                                            <option value="Reseller">Reseller</option>
                                                            <option value="Marketer">Marketer</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group" id="nama_mitra"></div>

                                                    <?php
                                                        $query_variant = $koneksi->query("SELECT * FROM poproduk
                                                                                                INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                                                INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                                                                                                WHERE poproduk.idpoproduk = '$idpoproduk'
                                                                                                ORDER BY poproduk.idpoproduk
                                                                                    ");
                                                        while ($sql_variant = $query_variant->fetch_assoc()) {
                                                    ?>
                                                        <div class="form-group">
                                                            <label for="<?= $sql_variant['idpodetail'] ?>" class="mb-1"><?= $sql_variant['variant'] ?></label>
                                                            <input type="number" class="form-control form-control-sm" value="0" min="0" max="<?= $sql_variant['stok'] ?>" name="qty[]">
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control form-control-sm" value="<?= $sql_variant['idpodetail'] ?>" name="idpodetail[]">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php elseif ($jenispo == "PO Custom Tab"):?>
                                            <div class="form-group">
                                                <label for="jenis_mitra" class="mb-1">Jenis Mitra</label>
                                                <select id="jenis_mitra" class="form-control form-control-sm">
                                                    <option selected>~ Default Selected ~</option>
                                                    <option value="Distributor">Distributor</option>
                                                    <option value="Agen">Agen</option>
                                                    <option value="Reseller">Reseller</option>
                                                    <option value="Marketer">Marketer</option>
                                                </select>
                                            </div>

                                            <div class="form-group" id="nama_mitra"></div>		
                                            <div style="padding: 0 15px;">
                                                <ul class="nav nav-tabs">
                                                    <?php
                                                        $noo    = 1;
                                                        $idpoproduk = $_GET['id'];
                                                        $sql    = "SELECT * FROM bukapo_tab 
                                                                    WHERE idpoproduk = '$idpoproduk' order by id asc";
                                                        $query  = $koneksi->query($sql);
                                                        while($row = $query->fetch_assoc()){
                                                    ?>	
                                                        <?php if ($noo==1): ?>
                                                            <li class="active"><a data-toggle="tab" href="#home<?= $row['id']; ?>"  class="nav-item nav-link active"><?= $row['nama_tab']; ?></a></li>
                                                        <?php else: ?>				
                                                            <li class=""><a data-toggle="tab" href="#home<?= $row['id']; ?>" class="nav-item nav-link"><?= $row['nama_tab']; ?></a></li>
                                                        <?php endif ?>
                                                        <?php $noo++; ?>	
                                                    <?php } ?>
                                                </ul>
                                                <br>
                                                <div class="tab-content">
                                                    <?php
                                                        $no         = 1;
                                                        $sql_isi    = "SELECT * FROM bukapo_tab WHERE idpoproduk='$idpoproduk' order by id asc";
                                                        $query_isi  = $koneksi->query($sql_isi);
                                                        while($row_isi = $query_isi->fetch_assoc()){
                                                            $id_awal    = $row_isi['id_awal'];
                                                            $id_akhir   = $row_isi['id_akhir'];		
                                                    ?>	
                                                        <?php if ($no==1): ?>
                                                            <div id="home<?= $row_isi['id']; ?>" class="tab-pane fade active show in">
                                                        <?php else: ?>
                                                            <div id="home<?= $row_isi['id']; ?>" class="tab-pane fade ">					
                                                        <?php endif ?>		
                                                            <?php
                                                                $no++;
                                                                $idpoproduk     = $_GET['id'];
                                                                $sql_variant    = "SELECT 
                                                                                                *
                                                                                            FROM
                                                                                                poproduk
                                                                                                    INNER JOIN
                                                                                                pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                                                                    INNER JOIN
                                                                                                podetail ON podetail.idpo = pokategori.idpo
                                                                                            WHERE
                                                                                                poproduk.idpoproduk = '$idpoproduk' 
                                                                                            AND (podetail.idpodetail BETWEEN '$id_awal' AND '$id_akhir') 
                                                                                            ORDER BY pokategori.idpo asc";
                                                                $query_variant  = $koneksi->query($sql_variant);
                                                                    while($row_variant = $query_variant->fetch_assoc()){
                                                            ?>
                                                                <div class="form-group">
                                                                    <label><?php echo $row_variant['variant']; ?></label>
                                                                    <input type="number" min="0" value="0" name="qty[]" class="form-control form-control-sm" style="width:300px;" id="<?= $row_variant['idpodetail']; ?>" >
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="hidden" class="form-control form-control-sm" value="<?= $row_variant['idpodetail'] ?>" name="idpodetail[]" id="<?= $row_variant['idpodetail'] ?>">
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>	
                                                </div>
                                            </div>
                                        <?php elseif ($jenispo == "PO Bundling 2") :?>
                                            <?php include 'components/form/formpobundling2.php'; ?>
                                        <?php elseif ($jenispo == "PO Bundling 5") :?>
                                            <?php include 'components/form/formpobundling5.php'; ?>
                                        <?php else : ?>
                                            <div class="alert alert-danger">
                                                <span>Tentukan jenis po terlebih dahulu</span>
                                            </div>
                                        <?php endif; ?>
                                        <button type="submit" class="btn btn-primary btn-sm" name="kirim">Kirim</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php
            if (isset($_POST['kirim'])) {
                include "koneksi.php";
                date_default_timezone_set("Asia/Jakarta");
                $qty        = count($_POST['qty']);
                $check_user = $_POST['jenis_mitra'];
                $explode    = explode(" | ", $check_user);
                $mitra      = $explode[0];
                $id         = $explode[1];
                $idpoproduk = $_GET['id'];

                if ($mitra == 'Distributor') {
                    $kolom_mitra = 'pomitra.idmitra';
                } elseif ($mitra == 'Agen') {
                    $kolom_mitra = 'pomitra.idmitraagen';
                } elseif ($mitra == 'Reseller') {
                    $kolom_mitra = 'pomitra.idmitraagen'; // atau bisa kasih error / handle lainnya
                } else {
                    $kolom_mitra = 'pomitra.idmitramarketer';
                }
                if ($kolom_mitra != '') {
                    $query_check = $koneksi->query("SELECT COUNT(*) AS jumlah,
                                                        poproduk.idpoproduk,
                                                        poproduk.namapo,
                                                        poproduk.status
                                                    FROM poproduk
                                                    INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk
                                                    WHERE poproduk.idpoproduk = '$idpoproduk'
                                                    AND $kolom_mitra = '$id'
                                            ");
                    $sql_check  = $query_check->fetch_assoc();
                } else {
                    echo "<script>alert('Data gagal dikirim');</script>";
                    echo "<script>location='formpo.php?id=$idpoproduk'</script>";
                }

                // Fungsi untuk menghasilkan angka acak dengan panjang tertentu
                function generateAngkaAcak($length) {
                    $angka_acak = '';
                    for ($i = 0; $i < $length; $i++) {
                        // Menggunakan mt_rand untuk angka acak dari 0 hingga 9
                        $angka_acak .= mt_rand(0, 9);
                    }
                    return $angka_acak;
                }

                // Menghasilkan angka acak dengan panjang minimal 7 dan maksimal 7 angka
                $angka_acak = generateAngkaAcak(5);

                if ($mitra == "Distributor") {
                    $invoice = "D" . $idpoproduk . "-" . $id . $angka_acak;
                } elseif ($mitra == "Agen") {
                    $invoice = "A" . $idpoproduk . "-" . $id . $angka_acak;
                } elseif ($mitra == "Reseller") {
                    $invoice = "R" . $idpoproduk . "-" . $id . $angka_acak;
                } elseif ($mitra == "Marketer") {
                    $invoice = "M" . $idpoproduk . "-" . $id . $angka_acak;
                }
                if ($jenispo == "PO tanpa Stok" || $jenispo == "PO Custom Tab" || $jenispo === "PO Mandiri") {
                    if ($sql_check['jumlah'] < 1) {
                        for ($x = 0; $x < $qty; $x++) {
                            $jmlh_item              = $_POST['qty'][$x];
                            $idpodetail_item        = $_POST['idpodetail'][$x];
                            $query_detail_barang    = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail_item'");
                            $detail_barang          = $query_detail_barang->fetch_assoc();
                            $harga                  = $detail_barang['harga'];
                            $idpo                   = $detail_barang['idpo'];
                            $total_harga            = $jmlh_item * $harga;
                            $idadmin                = $id;

                            if ($mitra == "Distributor") {
                                echo "<br/>" . $invoice . "<br/>";
                                $insertData = $koneksi->query("INSERT INTO pomitra
                                                                (
                                                                    idpomitra, idmitra,
                                                                    idpoproduk,
                                                                    idpo, idpodetail, jumlah,
                                                                    total, invoice, status, tgl
                                                                )
                                                                VALUES 
                                                                (
                                                                    NULL, '$idadmin',
                                                                    '$idpoproduk',
                                                                    '$idpo', '$idpodetail_item', '$jmlh_item',
                                                                    '$total_harga', '$invoice', 'Belum DP',
                                                                    NOW()
                                                                )
                                                            ");
                            } elseif ($mitra == "Agen") {
                                echo "<br/>" . $invoice . "<br/>";
                                $getData = $koneksi->query("SELECT * FROM mitraagen WHERE idmitraagen = '$id'");
                                $data    = $getData->fetch_assoc();                    
                                $idmitraagen        = $data['idmitraagen'];
                                $idadmin            = $data['idadmin'];

                                $insertData = $koneksi->query("INSERT INTO pomitra
                                                                (
                                                                    idpomitra, idmitra, idmitraagen,
                                                                    idpoproduk,
                                                                    idpo, idpodetail, jumlah,
                                                                    total, invoice, status, tgl
                                                                )
                                                                VALUES 
                                                                (
                                                                    NULL, '$idadmin', '$idmitraagen',
                                                                    '$idpoproduk',
                                                                    '$idpo', '$idpodetail_item', '$jmlh_item',
                                                                    '$total_harga', '$invoice', 'Belum DP',
                                                                    NOW()
                                                                )
                                                            ");
                            } elseif ($mitra == "Reseller"){
                                echo "<br/>" . $invoice . "<br/>";
                                $getData = $koneksi->query("SELECT * FROM mitrareseller WHERE idmitrareseller = '$id'");
                                $data    = $getData->fetch_assoc();
                                $idmitrareseller    = $data['idmitrareseller'];
                                $idmitraagen        = $data['idmitraagen'];
                                $idadmin            = $data['idadmin'];

                                $insertData = $koneksi->query("INSERT INTO pomitra
                                                                (
                                                                    idpomitra, idmitra, idmitraagen, idmitrareseller,
                                                                    idpoproduk,
                                                                    idpo, idpodetail, jumlah,
                                                                    total, invoice, status, tgl
                                                                )
                                                                VALUES 
                                                                (
                                                                    NULL, '$idadmin', '$idmitraagen', '$idmitrareseller',
                                                                    '$idpoproduk',
                                                                    '$idpo', '$idpodetail_item', '$jmlh_item',
                                                                    '$total_harga', '$invoice', 'Belum DP',
                                                                    NOW()
                                                                )
                                                            ");
                            } elseif ($mitra == "Marketer") {
                                echo "<br/>" . $invoice . "<br/>";
                                $getData = $koneksi->query("SELECT * FROM mitramarketer WHERE idmitramarketer = '$id'");
                                $data    = $getData->fetch_assoc();
                                $idmitramarketer    = $data['idmitramarketer'];
                                $idmitraagen        = $data['idmitraagen'];
                                $idadmin            = $data['idadmin'];

                                $insertData = $koneksi->query("INSERT INTO pomitra
                                                                (
                                                                    idpomitra, idmitra, idmitraagen, idmitramarketer,
                                                                    idpoproduk,
                                                                    idpo, idpodetail, jumlah,
                                                                    total, invoice, status, tgl
                                                                )
                                                                VALUES 
                                                                (
                                                                    NULL, '$idadmin', '$idmitraagen', '$idmitramarketer',
                                                                    '$idpoproduk',
                                                                    '$idpo', '$idpodetail_item', '$jmlh_item',
                                                                    '$total_harga', '$invoice', 'Belum DP',
                                                                    NOW()
                                                                )
                                                            ");
                            } else {
                                echo "Tidak ada data distributor";
                            }
                        }
                        if ($insertData) {
                            echo "<script>alert('Data berhasil dikirim');</script>";
                            echo "<script>location='detailinvoice.php?invoice=$invoice&idpoproduk=$idpoproduk'</script>";
                        } else {
                            echo "<script>alert('Data gagal dikirim!');</script>";
                            echo "<script>location='formpo.php?id=$idpoproduk'</script>";
                        }
                    } else {
                        echo "<script>alert('Invoice sudah ada!');</script>";
                        echo "<script>location='formpo.php?id=$idpoproduk'</script>";
                    }
                    // echo "<script>alert('Data berhasil dikirim');</script>";
                    // echo "<script>location='listpokolibri.php?id=$idpoproduk';</script>";
                } elseif ($jenispo == "PO dengan Stok") {
                    if ($sql_check['jumlah'] < 1) {
                        for ($x = 0; $x < $qty; $x++) {
                            $jmlh_item = $_POST['qty'][$x];
                            $idpodetail_item = $_POST['idpodetail'][$x];
                            $query_detail_barang = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail_item'");
                            $detail_barang = $query_detail_barang->fetch_assoc();
                            $harga = $detail_barang['harga'];
                            $idpo = $detail_barang['idpo'];
                            $total_harga = $jmlh_item * $harga;

                            if ($mitra == "Distributor") {
                                echo "<br/>" . $invoice . "<br/>";
                            } elseif ($mitra == "Agen") {
                                echo "<br/>" . $invoice . "<br/>";
                            } elseif ($mitra == "Marketer") {
                                echo "<br/>" . $invoice . "<br/>";
                            } elseif ($mitra == "Markater") {
                                echo "<br/>" . $invoice . "<br/>";
                            } else {
                                echo "Tidak ada data distributor";
                            }

                            $update_stok = $koneksi->query("UPDATE pokategori SET stok = stok - $jmlh_item WHERE idpo = '$idpo'");
                            $insertData = $koneksi->query("INSERT INTO pomitra
                                                            (idpomitra, idmitra, idpoproduk,
                                                            idpo, idpodetail, jumlah,
                                                            total, invoice, status, tgl)
                                                            VALUES (NULL, '$id', '$idpoproduk',
                                                            '$idpo', '$idpodetail_item', '$jmlh_item',
                                                            '$total_harga', '$invoice', 'Belum DP',
                                                            NOW())
                                                        ");
                        }
                        
                        if ($insertData) {
                            echo "<script>alert('Data berhasil dikirim');</script>";
                            echo "<script>location='listpokolibri.php?id=$idpoproduk'</script>";
                        } else {
                            echo "<script>alert('Data gagal dikirim!');</script>";
                            echo "<script>location='formpo.php?id=$idpoproduk'</script>";
                        }
                    } else {
                        echo "<script>alert('Invoice sudah ada!');</script>";
                        echo "<script>location='formpo.php?id=$idpoproduk'</script>";
                    }
                } elseif ($jenispo == "PO Bundling 2") {
                    try {
                        $checkQuery     = $koneksi->query("SELECT COUNT(*) as count 
                                                            FROM pomitra 
                                                            WHERE idmitra = '$id' 
                                                            AND idpoproduk = '$idpoproduk'
                                                            AND idmitraagen IS NULL
                                                            AND idmitrareseller IS NULL
                                                            AND idmitramarketer IS NULL");
                        $exists         = $checkQuery->fetch_assoc()['count'];

                        if ($exists > 0) {
                            echo "<script>alert('Data sudah ada, tidak bisa mengirim ulang!');</script>";
                            echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
                            exit();
                        }

                        date_default_timezone_set('Asia/Jakarta');
                        $today      = date('s');
                        $waktu      = date('H:i:s');
                        $qty        = $_POST['qty'];
                        $variant    = $_POST['variant'];
                        $count      = count($variant);
                        
                        for ($x = 0; $x < $count; $x++) {
                            $qty_item       = $qty[$x];
                            $variant_item   = $variant[$x];
                            $data           = explode("|", $variant_item);
                            $idpodetail     = $data[0];
                            $idpo           = $data[1];
                            $custom         = $data[2];
                            
                            $query          = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail'");
                            $data_produk    = $query->fetch_assoc();
                            $total_harga    = $data_produk['harga'];

                            $sql = $koneksi->query("INSERT INTO pomitra
                                                            (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu)
                                                        VALUES
                                                            (NULL, '$id', '$idpoproduk', '$idpo', '$idpodetail', '$qty_item', '$custom', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')
                                                    ");
                            
                            if ($sql) {
                                echo "
                                    <script>
                                        alert('Data sudah terkirim')
                                        location='detailinvoicebundling2.php?id=$idpoproduk&invoice=$invoice'
                                    </script>
                                ";
                            } else {
                                echo "
                                    <script>
                                        alert('Data gagal terkirim!')
                                        location='formpo.php?id=$idpoproduk'
                                    </script>
                                ";
                            }
                        }
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } elseif ($jenispo == "PO Bundling 5") {
                    try {
                        $checkQuery     = $koneksi->query("SELECT COUNT(*) as count 
                                                            FROM pomitra 
                                                            WHERE idmitra = '$id' 
                                                            AND idpoproduk = '$idpoproduk'
                                                            AND idmitraagen IS NULL
                                                            AND idmitrareseller IS NULL
                                                            AND idmitramarketer IS NULL
                                                        ");
                        $exists         = $checkQuery->fetch_assoc()['count'];

                        if ($exists > 0) {
                            echo "<script>alert('Data sudah ada, tidak bisa mengirim ulang!');</script>";
                            echo "<script>location='detailpo.php?id=$idpoproduk';</script>";
                            exit();
                        }

                        date_default_timezone_set('Asia/Jakarta');
                        $today      = date('s');
                        $waktu      = date('H:i:s');
                        $qty        = $_POST['qty'];
                        $variant    = $_POST['variant'];
                        $count      = count($variant);

                        for ($x = 0; $x < $count; $x++) {
                            $qty_item       = $qty[$x];
                            $variant_item   = $variant[$x];
                            $data           = explode("|", $variant_item);
                            $idpodetail     = $data[0];
                            $idpo           = $data[1];
                            $custom         = $data[2];
                            
                            $query          = $koneksi->query("SELECT * FROM podetail WHERE idpodetail = '$idpodetail'");
                            $data_produk    = $query->fetch_assoc();
                            $total_harga    = $data_produk['harga'];

                            $sql = $koneksi->query("INSERT INTO pomitra
                                                            (idpomitra, idmitra, idpoproduk, idpo, idpodetail, jumlah, custom, total, invoice, status, tgl, waktu)
                                                        VALUES
                                                            (NULL, '$id', '$idpoproduk', '$idpo', '$idpodetail', '$qty_item', '$custom', '$total_harga', '$invoice', 'Belum DP', NOW(), '$waktu')
                                                    ");
                            
                            if ($sql) {
                                echo "
                                    <script>
                                        alert('Data sudah terkirim')
                                        location='detailinvoicebundling2.php?id=$idpoproduk&invoice=$invoice'
                                    </script>
                                ";
                            } else {
                                echo "
                                    <script>
                                        alert('Data gagal terkirim!')
                                        location='formpo.php?id=$idpoproduk'
                                    </script>
                                ";
                            }
                        }
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "<script>alert('Form PO dalam Perbaikan');</script>";
                }
            }
        ?>

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

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
        
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
        
        <script src="assets/dist/js/jquery.min.js"></script>
        <script src="assets/dist/js/bootstrap.min.js"></script>
        <script src="assets/dist/DataTables/datatables.min.js"></script>

        <script type="text/javascript">
            $('#jenis_mitra').change(function() {
                var jenisMitra = $('#jenis_mitra').val();
                $.ajax({
                    type: 'GET',
                    url: 'cek_db.php',
                    data: 'jenis_mitra=' + jenisMitra,
                    success: function (data) {
                        $("#nama_mitra").html(data)
                    }
                })
            });
        </script>
    </body>
</html>