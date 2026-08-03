<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk     = intval($_GET["id"]);
    $invoice        = mysqli_real_escape_string($koneksi, $_GET["invoice"]);					 
    $idadmin        = $_SESSION['idadmin'];	
    
    $query          = "SELECT * FROM admin_mitra WHERE idadmin=$idadmin";
    $sql            = mysqli_query($koneksi, $query);  
    $data           = mysqli_fetch_array($sql);

    $special_po     = [186, 187];

    $querywaktu     = "SELECT 
                            bukapo.idbpo, 
                            bukapo.jenis_mitra, 
                            bukapo.jenis_po, 
                            bukapo.idpoproduk,
                            bukapo.tgl, 
                            bukapo.tgl_dropship, 
                            bukapo.status, 
                            poproduk.namapo 
                        FROM bukapo 
                        INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk 
                        WHERE bukapo.idpoproduk = $idpoproduk 
                        AND (bukapo.jenis_mitra = 'Semua Mitra' OR bukapo.jenis_mitra = 'Distributor')
                    ";
    $sqlwaktu       = mysqli_query($koneksi, $querywaktu);
    $datawaktu      = mysqli_fetch_array($sqlwaktu); 
    $idbpo          = $datawaktu['idbpo'];
    $tgl_dropship   = $datawaktu['tgl_dropship'];
    $namapo         = $datawaktu['namapo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    
    <title>Distributor | Wanoja</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <?php if (in_array($idpoproduk, $special_po)): ?>
            <a href="formds?idpo=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>" class="btn btn-primary btn-sm text-white">
                Tambah Dropship <?= $namapo; ?>
            </a>
        <?php else: ?>
            <?php if (strtotime(date('Y-m-d')) <= strtotime($tgl_dropship)) : ?>
                <a href="formds?idpo=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>" class="btn btn-primary btn-sm text-white">
                    Tambah Dropship <?= $namapo; ?>
                </a>
            <?php else: ?>
                <p>Link Dropship tidak tersedia!</p>
            <?php endif; ?>
        <?php endif; ?>

        <h2 class="text-center">Dropship <?= $namapo; ?></h2><hr>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tb_lunas">
                <thead>
                    <tr>
                        <th>Opsi</th>
                        <th>Invoice</th>
                        <th>Data Pengirim/Penerima</th>
                        <th>Pengiriman</th>
                        <th><?= in_array($idpoproduk, $special_po) ? ' ' : 'Keterangan' ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $sql        = mysqli_query($koneksi, "SELECT * FROM podropship WHERE invoice = '$invoice' AND idadmin = '$idadmin'");
                    while($data = mysqli_fetch_array($sql)):
                        $iddropship = $data['iddropship'];
                        $no_ds      = $data['no_ds'];
                ?>
                <tr>
                    <td class="text-center">
                        <?php if ($no_ds != "" && $data['proses'] == ""): ?>
                            <a href="ubahdropship?iddropship=<?= $iddropship; ?>" class="btn btn-success btn-xs">Ubah Alamat</a>
                            <form method="POST">
                                <input type="hidden" name="no_ds" value="<?= $no_ds; ?>">
                                <button type="submit" name="hapus" class="btn btn-danger btn-xs mt-2" onclick="return confirm('Yakin Akan Hapus Data?');">Hapus</button>
                            </form>
                        <?php elseif ($no_ds != ""): ?>
                            <span class="bg-success rounded text-white px-2 py-1 d-inline-block"><?= $data['proses']; ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($no_ds): ?>
                            <a href="detail_ds?no_ds=<?= $no_ds; ?>" target="_blank" class="btn btn-secondary btn-xs"><?= $no_ds; ?></a>
                        <?php else: ?>
                            <a href="input_detail_ds?id=<?= $iddropship; ?>" target="_blank" class="btn btn-secondary btn-xs">Input Detail Produk</a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong>Pengirim</strong><br>
                        <?= $data['namapengirim']; ?> / <?= $data['tlppengirim']; ?><br>
                        <strong>Penerima</strong><br>
                        <?= $data['namapenerima']; ?> / <?= $data['tlppenerima']; ?>
                    </td>
                    <td>
                        <strong>Ekspedisi</strong><br>
                        <?= strtoupper($data['ekspedisi']); ?><br>
                        <strong>Alamat Penerima</strong><br>
                        <span class="short-text" id="dots<?= $iddropship; ?>"><?= substr($data['alamatpenerima'], 0, 10); ?>...</span>
                        <span class="full-text" id="more<?= $iddropship; ?>" style="display:none;"><?= substr($data['alamatpenerima'], 0, 300); ?></span>
                        <button class="btn btn-outline-dark toggle-detail" data-id="<?= $iddropship; ?>"><i class='fa fa-eye'></i></button>
                    </td>
                    <?php if (!in_array($idpoproduk, $special_po)): ?>
                    <td>
                        <span class="short-text" id="dots<?= $iddropship; ?>1"><?= substr($data['keterangan'], 0, 10); ?>...</span>
                        <span class="full-text" id="more<?= $iddropship; ?>1" style="display:none;"><?= substr($data['keterangan'], 0, 300); ?></span>
                        <button class="btn btn-outline-dark toggle-detail" data-id="<?= $iddropship; ?>1"><i class='fa fa-eye'></i></button>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).on('click', '.toggle-detail', function () {
            const id = $(this).data('id');
            const dots = $("#dots" + id);
            const more = $("#more" + id);

            if (dots.is(":visible")) {
                dots.hide();
                more.show();
                $(this).html("<i class='fa fa-eye-slash'></i>");
            } else {
                dots.show();
                more.hide();
                $(this).html("<i class='fa fa-eye'></i>");
            }
        });
    </script>

    <?php
        if (isset($_POST['hapus'])) {
            $no_ds = $_POST['no_ds'];
            if ($no_ds != "") {
                $delete1 = $koneksi->query("DELETE FROM podropship WHERE no_ds = '$no_ds'");
                $koneksi->query("DELETE FROM pods WHERE no_ds = '$no_ds'");
                echo $delete1 ? "<script>alert('Data Berhasil Dihapus');location='listds?id=$idpoproduk&invoice=$invoice';</script>"
                            : "<script>alert('Data Gagal Dihapus');location='listds?id=$idpoproduk&invoice=$invoice';</script>";
            }
        }
    ?>

    <!-- MAIN CONTENT END -->
    
    <br><br><br><br>
    
    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>