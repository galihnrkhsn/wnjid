<?php
    session_start();
    error_reporting (0);

    include 'koneksi.php';
    include 'assets/components/Sessions/sesAgen.php';
    include "settingdatatables.php";

    $invoice            = $_GET["invoice"];
    $idpoproduk         = $_GET["id"];
    $idmitraagen        = $_SESSION['idmitraagen'];
    $query              = "SELECT * FROM mitraagen WHERE idmitraagen = $idmitraagen";
    $sql                = mysqli_query($koneksi, $query);
    $data               = mysqli_fetch_array($sql);
    $querynamapo        = "SELECT namapo FROM poproduk WHERE idpoproduk = $idpoproduk";
    $sqlnamapo          = mysqli_query($koneksi, $querynamapo);
    $datanamapo         = mysqli_fetch_array($sqlnamapo);
    $namapo             = $datanamapo['namapo'];
    $querywaktu         = "SELECT bukapo.idbpo,
                                bukapo.jenis_mitra,
                                bukapo.jenis_po,
                                bukapo.idpoproduk,
                                bukapo.tgl,
                                bukapo.tgl_dropship,
                                bukapo.status,
                                poproduk.namapo 
                            FROM bukapo 
                            inner join poproduk on bukapo.idpoproduk = poproduk.idpoproduk 
                            WHERE poproduk.idpoproduk='$idpoproduk' and (bukapo.jenis_mitra = 'Semua Mitra' or bukapo.jenis_mitra = 'Distributor')";
    $sqlwaktu           = mysqli_query($koneksi, $querywaktu);  
    $datawaktu          = mysqli_fetch_array($sqlwaktu); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Agen | WNJ.ID</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?>
            <button type="submit" class="btn btn-primary btn-sm">
            <a style="color:white" href="formds?idpo=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>">Tambah Dropship <?= $tampilkan['namapo']; ?></a>
            </button>
        <?php endif; ?>
        <?php 
            $no = 1;
            $query = "SELECT bukapo.idbpo, bukapo.jenis_mitra, bukapo.jenis_po, bukapo.idpoproduk, bukapo.tgl, bukapo.tgl_dropship, bukapo.status, poproduk.namapo 
                    FROM bukapo 
                    INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk 
                    WHERE poproduk.idpoproduk='$idpoproduk' 
                    AND poproduk.idpoproduk<>186 
                    AND (bukapo.jenis_mitra = 'Semua Mitra' OR bukapo.jenis_mitra = 'Distributor')";
            $sql = mysqli_query($koneksi, $query);  
            $tampilkan = mysqli_fetch_array($sql);  
        ?>

        <button type="submit" class="btn btn-primary btn-sm" name="cari" id="linkmiki1<?= $tampilkan['idbpo']; ?>">
            <?php if ($idpoproduk == 355 || $idpoproduk == 361 || $idpoproduk == 371 || $idpoproduk == 374) : ?>
                <!-- <a style="color:white" href="formdsbundling?idpo=<?= $tampilkan['idpoproduk']; ?>&invoice=<?= $invoice; ?>">Tambah Dropship <?= $tampilkan['namapo']; ?></a> -->
            <?php else : ?>
                <?php endif; ?>
            <a style="color:white" href="formds?idpo=<?= $tampilkan['idpoproduk']; ?>&invoice=<?= $invoice; ?>">Tambah Dropship <?= $tampilkan['namapo']; ?></a>
        </button>
        <p id="demomiki1<?= $tampilkan['idbpo']; ?>"></p>
        <br>

        <script>
            var countDownDatemiki1<?= $tampilkan['idbpo']; ?> = new Date("<?= $tampilkan['tgl_dropship']; ?> 23:59:00").getTime();
            var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDatemiki1<?= $tampilkan['idbpo']; ?> - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("demomiki1<?= $tampilkan['idbpo']; ?>").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demomiki1<?= $tampilkan['idbpo']; ?>").innerHTML = "Link PO tidak tersedia";
                var x = document.getElementById("linkmiki1<?= $tampilkan['idbpo']; ?>");
                x.style.display = "none";
            }
            }, 1000);
        </script>

        <?php
            echo "<h2><center>Dropship $namapo</center></h2><hr>";    
        ?>	
        
        <div class="table-responsive">
            <table class="table table-bordered" id="tb_lunas">
                <thead>
                    <tr>
                    <th>Opsi</th>
                    <th>Invoice</th>
                    <th>Data Pengirim/Penerima</th>
                    <th>Pengiriman</th>
                    <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?> 
                    <!-- <th>Kartu Ucapan</th> -->
                    <?php else: ?>
                    <th>Keterangan</th>
                    <?php endif ?>          
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($koneksi, "SELECT * FROM podropship WHERE invoice='$invoice' AND idmitraagen='$idmitraagen'");
                    $no = 1;
                    while($data = mysqli_fetch_array($sql)):
                        $no_ds =  $data['no_ds'];
                    ?>
                    <tr>
                    <td class="text-center">
                        <?php if ($data['no_ds'] <> ""): ?>                  
                        <?php if ($data['proses'] == ""): ?>
                            <a href="ubahdropship?iddropship=<?= $data['iddropship']; ?>" class="btn btn-success btn-xs mt-4">Ubah Alamat</a>
                            <form method="POST">
                            <input type="hidden" name="no_ds" value="<?= $data['no_ds']; ?>">
                            <button type="submit" name="hapus" class="btn btn-danger btn-xs mt-4" onclick="return confirm('Yakin Akan Hapus Data?');">Hapus</button>
                            </form>
                        <?php else: ?>
                            <span class="badge bg-success text-white"><?= $data['proses']; ?></span>          
                        <?php endif; ?>
                        <?php endif; ?>                   
                        <?php
                        if(isset($_POST['hapus'])){
                            $no_ds = $_POST['no_ds'];
                            if ($no_ds <> ""){
                            $sql = $koneksi->query("DELETE FROM podropship WHERE no_ds='$no_ds'");
                            $koneksi->query("DELETE FROM pods WHERE no_ds='$no_ds'");
                            if ($sql) {
                                echo "<script>alert('Data Berhasil Dihapus');</script>";
                                echo "<script>location='listds?id=$idpoproduk&invoice=$invoice';</script>";    
                            } else {
                                echo "<script>alert('Data Gagal Dihapus');</script>";
                                echo "<script>location='listds?id=$idpoproduk&invoice=$invoice';</script>";    
                            }
                            }
                        }
                        ?>
                    </td>
                    <td class="">
                        <?php if ($data['no_ds'] <> ""): ?>
                            <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?> 
                                <?php 
                                    $data_jumlah = $koneksi->query("SELECT SUM(pods.jumlah) as jumlahnya FROM pods WHERE pods.no_ds = '$no_ds'");
                                    $tampilprogres = $data_jumlah->fetch_assoc();  
                                    $jmlh_semua = $tampilprogres['jumlahnya'];
                                ?>  
                                <?php if ($jmlh_semua % 3 <> 0): ?>
                                    <div class="alert alert-danger" role="alert">
                                        Voal Belum Kelipatan 3
                                    </div>
                                <?php endif; ?> 
                            <?php endif; ?>
                            <?php if ($idpoproduk == 355 || $idpoproduk == 361 || $idpoproduk == 371 || $idpoproduk == 374) : ?>
                                <!-- <a href="detail_dsbundling?no_ds=<?= $data['no_ds']; ?>" target="_blank()" class="btn btn-secondary btn-xs"><?= $data['no_ds']; ?></a> -->
                            <?php else : ?>
                            <?php endif; ?>         
                            <a href="detail_ds?no_ds=<?= $data['no_ds']; ?>" target="_blank()" class="btn btn-secondary btn-xs"><?= $data['no_ds']; ?></a>
                        <?php endif; ?>  
                        <?php if ($data['no_ds'] == ""): ?>
                        <a href="input_detail_ds.php?id=<?= $data['iddropship']; ?>" target="_blank()" class="btn btn-secondary btn-xs">Input Detail Produk</a>
                        <?php endif; ?> 
                    </td>
                    <td class="">
                        <strong>
                        <p>Pengirim</p>
                        </strong>
                        <p>
                        <?= $data['namapengirim']; ?>
                        /
                        <?= $data['tlppengirim']; ?>
                        </p>
                        <strong>
                        <p>Penerima</p>
                        </strong>
                        <p>
                        <?= $data['namapenerima']; ?>
                        /
                        <?= $data['tlppenerima']; ?>
                        </p>
                    </td>
                    <td class="">
                        <strong>
                        <p>Ekspedisi</p>
                        </strong>
                        <p>
                        <?= strtoupper($data['ekspedisi']); ?>
                        </p>
                        <strong>
                        <p>Alamat Penerima</p>
                        </strong>
                        <span id="dots<?= $data['iddropship']; ?>"><?= substr($data['alamatpenerima'], 0, 10); ?>...</span>
                        <p>
                        <span id="more<?= $data['iddropship']; ?>" style="display:none;">
                            <?= substr($data['alamatpenerima'], 0, 300); ?>   
                        </span>
                        </p>
                        <button onclick="myFunction<?= $data['iddropship']; ?>()" id="myBtn<?= $data['iddropship']; ?>" class="btn btn-outline-dark">
                        <i class='fa fa-eye'></i>
                        </button>
                    </td>
                    <?php if ($idpoproduk == 186 || $idpoproduk == 187): ?> 
                        <?php 
                        $result_explode = explode('|', $data['keterangan']);
                        $dari = $result_explode[0];  
                        $kepada = $result_explode[1];  
                        $ucapan = $result_explode[2];  
                        ?>
                    <?php else: ?>
                        <td class="">
                        <span id="dots<?= $data['iddropship']; ?>1"><?= substr($data['keterangan'], 0, 10); ?>...</span>
                        <p>
                            <span id="more<?= $data['iddropship']; ?>1" style="display:none;">
                            <?= substr($data['keterangan'], 0, 300); ?>   
                            </span>
                        </p>
                        <button onclick="myFunction<?= $data['iddropship']; ?>1()" id="myBtn<?= $data['iddropship']; ?>1" class="btn btn-outline-dark">
                            <i class='fa fa-eye'></i>
                        </button>
                        <script>
                            function myFunction<?= $data['iddropship']; ?>1() {
                            var dots = document.getElementById("dots<?= $data['iddropship']; ?>1");
                            var moreText = document.getElementById("more<?= $data['iddropship']; ?>1");
                            var btnText = document.getElementById("myBtn<?= $data['iddropship']; ?>1");

                            if (dots.style.display === "none") {
                                dots.style.display = "inline";
                                btnText.innerHTML = "<i class='fa fa-eye'></i>"; 
                                moreText.style.display = "none";
                            } else {
                                dots.style.display = "none";
                                btnText.innerHTML = "<i class='fa fa-eye-slash'></i>"; 
                                moreText.style.display = "inline";
                            }
                            }
                        </script>                                               
                        </td>
                    <?php endif; ?>
                    <script>
                        function myFunction<?= $data['iddropship']; ?>() {
                        var dots = document.getElementById("dots<?= $data['iddropship']; ?>");
                        var moreText = document.getElementById("more<?= $data['iddropship']; ?>");
                        var btnText = document.getElementById("myBtn<?= $data['iddropship']; ?>");

                        if (dots.style.display === "none") {
                            dots.style.display = "inline";
                            btnText.innerHTML = "<i class='fa fa-eye'></i>"; 
                            moreText.style.display = "none";
                        } else {
                            dots.style.display = "none";
                            btnText.innerHTML = "<i class='fa fa-eye-slash'></i>"; 
                            moreText.style.display = "inline";
                        }
                        }
                    </script>             
                    </tr>           
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- PHP -->
    <!-- PHP END -->

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->
    
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>