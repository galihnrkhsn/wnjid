<?php
    session_start();
    error_reporting (0);

    include 'floatingbutton.php';
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include 'settingdatatables.php';

    $idpoproduk = $_GET['id'];
    $invoice    = $_GET['invoice'];
    $idadmin    = $_SESSION["idadmin"];
    $query      = "SELECT
                        poproduk.note,
                        poproduk.pembayaran,
                        pomitra.ket,
                        pomitra.tgl,
                        pomitra.waktu,
                        pomitra.invoice,
                        pomitra.status
                    FROM poproduk 
                    INNER JOIN pomitra ON poproduk.idpoproduk = pomitra.idpoproduk 
                    WHERE poproduk.idpoproduk = '$idpoproduk' 
                    AND pomitra.idmitra = '$idadmin' 
                    AND pomitra.invoice = '$invoice'
                    GROUP BY
                        poproduk.note,
                        poproduk.pembayaran,
                        pomitra.ket,
                        pomitra.tgl,
                        pomitra.waktu,
                        pomitra.invoice";
    $sql    = mysqli_query($koneksi, $query);  
    $datapo = mysqli_fetch_array($sql);

    $note           = $datapo['note'];
    $pembayaranpo   = $datapo['pembayaran'];
    $query_tgl      = "SELECT bukapo.idpoproduk, bukapo.tgl_bayar
                        FROM bukapo  
                        WHERE bukapo.idpoproduk = '$idpoproduk'
                    ";
    $sqlpo_tgl      = mysqli_query($koneksi, $query_tgl);  
    $datapo_tgl     = mysqli_fetch_array($sqlpo_tgl); 
    $tgl_bayar      = $datapo_tgl['tgl_bayar']; 
    $waktu_bayar    = '23:59:59';

    $findUser = $koneksi->query("SELECT * FROM admin_mitra WHERE idadmin='$idadmin'");
    $queryUser = $findUser->fetch_assoc(); 
    
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    
    <title>Distributor | WNJ.ID</title>
</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-3">
        <h2 class="text-center mb-4">SALES INVOICE</h2>
        <p class="text-center"><strong><?php echo $data['namapo']; ?></strong></p><br>
        <p class="text-left">Nama Mitra  : <?php echo $queryUser["namamitra"]; ?> </p>
        <p class="text-left">Alamat  : <?php echo $queryUser["alamat"]; ?> </p>
        <p class="text-left">No Invoice  : <?php echo $invoice ; ?> </p>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#agen">Info Invoice</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#reseller">Info Progres</a>
            </li>
        </ul>
        <div class="tab-content mt-5">
            <!-- Tab Content for Agen -->
            <div id="agen" class="container tab-pane active">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no         = 1;
                                $jumlah     = 0;
                                $subtotal   = 0;
                                $diskonDb   = 35;

                                $sql = mysqli_query($koneksi, "SELECT podetail.variant,
                                                                        podetail.harga,
                                                                        pomitra.jumlah,
                                                                        pomitra.total,
                                                                        pomitra.idpomitra,
                                                                        poproduk.namapo,
                                                                        poproduk.diskon
                                                                    FROM pomitra 
                                                                    JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                    JOIN poproduk ON poproduk.idpoproduk = pomitra.idpoproduk
                                                                    WHERE pomitra.invoice = '$invoice' 
                                                                    AND pomitra.jumlah > 0");

                                while($data = mysqli_fetch_array($sql)){ ?>
                                <tr>
                                    <td class="align-middle"><?php echo $no++; ?></td>
                                    <td class="align-middle">
                                        <?php echo $data['variant']; ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php echo $data['jumlah']; ?>
                                    </td>
                                    <td class="align-middle">Rp. <?php echo number_format($data['harga']); ?></td>
                                    <td class="align-middle">Rp. <?php echo number_format($data['total']); ?></td>
                                    <?php
                                        $sum                += $data['jumlah'];
                                        $jumlah             += $data['jumlah'] * $data['harga'];
                                        $total_diskon_db    = $jumlah*$diskonDb/100;
                                        $invoice            = $data['invoice'];
                                        $persen_tambahan    = $data['diskon'];
                                        $total_bayar        = $jumlah-$total_diskon_db;
                                    ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <table style="float: right;width: 100%">
                        <tbody style="float: right;">
                            <tr>
                                <th style="padding-bottom: 5%;">Total Qty</th>
                                <td style="padding-bottom: 5%;">: </td>
                                <td style="padding-bottom: 5%;"> <?php echo $sum; ?></td>
                            </tr>
                            <tr>
                                <th>SubTotal</th>
                                <td>: </td>
                                <td>Rp. <?= number_format($jumlah) ?></td>
                            </tr>
                            <tr>
                                <th>Diskon DB <?= $diskonDb; ?>%</th>
                                <td>:</td>
                                <td>Rp. <?= number_format($total_diskon_db); ?></td>
                            </tr>
                            <tr>
                                <th style="padding-bottom: 5%;">Total Bayar</th>
                                <td style="padding-bottom: 5%;">:</td>
                                <td style="padding-bottom: 5%;">Rp. <?= number_format($total_bayar); ?></td>
                            </tr>
                            <?php
                                $invoice            = $_GET['invoice'];
                                $termin_po_result   = $koneksi->query("SELECT * FROM termin_po WHERE idpoproduk = '$idpoproduk' ORDER BY seq ASC");
                                $total_termin       = 0;
                                $data_termin        = [];

                                if ($termin_po_result) {
                                    $total_termin   = $termin_po_result->num_rows; 
                                    while ($termin  = $termin_po_result->fetch_assoc()) {
                                        $data_termin[] = $termin;
                                    }
                                } else {
                                    die("Error saat menjalankan query: " . $koneksi->error);
                                }

                                $jumlah_pembayaran_terencana    = $total_termin;
                                $jumlah_pembayaran_final        = $jumlah_pembayaran_terencana;

                                $ambilPembayaran    = $koneksi->query("SELECT termin_seq FROM popembayaran WHERE invoice = '$invoice'");
                                $terminSelesai      = [];
                                while($row = $ambilPembayaran->fetch_assoc()) {
                                    $terminSelesai[] = $row['termin_seq'];
                                }
                            ?>
                            <?php foreach ($data_termin as $index => $termin): ?>
                                <?php
                                    $sudahBayar = in_array($termin['seq'], $terminSelesai);    
                                ?>
                                <tr style="<?= $sudahBayar ? 'color: gray;' : '' ?>">
                                    <th>
                                        <?php if ($sudahBayar): ?>
                                            ✔ <s>
                                        <?php endif; ?>

                                        <?php if ($termin['is_pelunasan'] == 1): ?>
                                            Pelunasan (<?= $termin['dp'] ?>%)
                                        <?php else: ?>
                                            DP <?= $termin['seq'] ?> (<?= $termin['dp'] ?>%)
                                        <?php endif; ?>

                                        <?php if ($sudahBayar): ?>
                                            </s>
                                        <?php endif; ?>
                                    </th>
                                    <td>: </td>
                                    <td>
                                        <?php if ($sudahBayar): ?><s><?php endif; ?>
                                        Rp. <?= number_format(($termin['dp'] / 100) * $total_bayar) ?>
                                        <?php if ($sudahBayar): ?></s><?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <th style="padding-top: 5%;">Status PO</th>
                                <td style="padding-top: 5%;">: </td>
                                <td style="padding-top: 5%;"><?= $datapo['status']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <p align="left"><strong>Note: </strong><?php echo $note ?></p>
                    <br>
                    <div class="card border-danger mb-3">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">Info Update Rekening Transfer Bank WNJ.ID Pusat Tahun 2025</h5>
                        </div>
                        <div class="card-body text-dark">
                            <h6 class="text-uppercase">Rekening Mandiri Perusahaan</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> CV KAFAA BILLAHI SYAHIDA</li>
                                <li><strong>No. Rek:</strong> 1300026727597</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening BCA (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 7751616671</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening BRI (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 114101000949560</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening MUAMALAT (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 1100003930</li>
                            </ul>

                            <h6 class="text-uppercase">Rekening BSI (BARU)</h6>
                            <ul class="mb-3">
                                <li><strong>Nama:</strong> MARIA ULFAH FATHIMAH</li>
                                <li><strong>No. Rek:</strong> 7105696706</li>
                            </ul>

                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center my-3">
                        <a class="btn btn-success btn-sm" href="invoice.php?idmitra=<?= $idadmin; ?>&id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>">Download PDF</a>
                    </div>
                    <?php if ($datapo['ket'] == 'Perpanjang' or $tgl_bayar == ""): ?>
                        <?php
                            date_default_timezone_set('Asia/Jakarta');
                            $jumlahhari     = '+1 days';
                            $tgl1           = $datapo['tgl'];
                            $tgl2           = date('Y-m-d', strtotime($jumlahhari, strtotime($tgl1)));
                            $tgl_bayar      = $tgl2;
                            $waktu_bayar    = $datapo['waktu'];    
                        ?>
                    <? endif; ?>
                    <?php
                        $invoice = $_GET['invoice'];

                        $checkPembayaran = $koneksi->query("SELECT termin_seq FROM popembayaran WHERE invoice = '$invoice'");
                        $terminSelesai = [];
                        while($row = $checkPembayaran->fetch_assoc()) {
                            $terminSelesai[] = $row['termin_seq'];
                        }

                        $nextTermin = null;
                        foreach ($data_termin as $termin) {
                            if (!in_array($termin['seq'], $terminSelesai)) {
                                $nextTermin = $termin;
                                break;
                            }
                        }

                        if ($nextTermin !== null) {
                            $jenisPayment = ($nextTermin['is_pelunasan'] == "1")
                            ? 'Pelunasan'
                            : 'Payment' . $nextTermin['seq'];

                            $jumlahBayar = ($nextTermin['dp'] / 100) * $total_bayar;

                            echo "
                                <div class='d-flex justify-content-center'>
                                    <a class='btn btn-primary'
                                        href='popembayaran.php?invoice=$invoice&total=$jumlahBayar&idpo=$idpoproduk&jenis=$jenisPayment&termin={$nextTermin['seq']}'>
                                        Konfirmasi $jenisPayment (".number_format($jumlahBayar).")
                                    </a>
                                </div>
                            ";
                        } else {
                            echo "<span class='badge bg-success'>LUNAS</span>";
                        }
                    ?>
                    <script>
                        // Mengatur waktu akhir perhitungan mundur
                        var countDownDatemiki<?= $data['idpomitra']; ?>= new Date("<?php echo $tgl_bayar; ?> <?php echo $waktu_bayar; ?>").getTime();

                        // Memperbarui hitungan mundur setiap 1 detik
                        var x = setInterval(function() {
                            // Untuk mendapatkan tanggal dan waktu hari ini
                            var now = new Date().getTime();
                            
                            // Temukan jarak antara sekarang dan tanggal hitung mundur
                            var distance = countDownDatemiki<?= $data['idpomitra']; ?> - now;
                            
                            // Perhitungan waktu untuk hari, jam, menit dan detik
                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            
                            // Keluarkan hasil dalam elemen dengan id = "demo"
                            document.getElementById("demomiki<?= $data['idpomitra']; ?>").innerHTML = days + "d " + hours + "h "
                            + minutes + "m " + seconds + "s ";
                            
                            // Jika hitungan mundur selesai, tulis beberapa teks 
                            if (distance < 0) {
                                clearInterval(x);
                                document.getElementById("demomiki<?= $data['idpomitra']; ?>").innerHTML = "Melebihi batas waktu konfirmasi Payment PO";
                                var x = document.getElementById("linkmiki<?= $data['idpomitra']; ?>");
                                var y = document.getElementById("link2<?= $data['idpomitra']; ?>");
                                y.style.display = "none";
                                x.style.display = "none";
                            }
                        }, 1000);
                    </script>
                    <?php
                        if ($datapo['status']=="Sudah DP") {
                            echo "<a class='btn btn-primary' href='popembayaran.php?invoice=$invoice&total=$sisalunas&idpo=$idpoproduk&jenis=lunas'>Konfirmasi Pelunasan</a>";                  
                        }
                    ?>
                    <br>
                    <?php if ($datapo['status'] == "Belum DP"): ?>
                    <?php 
                        $dataproduk = $koneksi->query("SELECT bukapo.idbpo,
                                                        bukapo.jenis_mitra,
                                                        bukapo.jenis_po,
                                                        bukapo.idpoproduk,
                                                        bukapo.tgl,
                                                        bukapo.tgl_acc_db,
                                                        bukapo.tgl_ubah,
                                                        bukapo.tgl_dropship,
                                                        bukapo.status,
                                                        poproduk.namapo 
                                                        FROM bukapo 
                                                        INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk 
                                                        WHERE poproduk.idpoproduk='$idpoproduk' 
                                                        AND bukapo.status = 'PUBLISH'
                                                        AND (bukapo.jenis_mitra = 'Semua Mitra' OR bukapo.jenis_mitra = 'Distributor')"
                                                    );
                        while ($tampilkan = $dataproduk->fetch_assoc()) {
                    ?>
                        <div class="d-flex justify-content-center">
                            <?php if ($tampilkan['jenis_po'] == "PO dengan Stok"): ?>
                                <button type="submit" class="btn btn-success btn-sm text-center" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                                    <a style="color:white" href="ubahpostok?id=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                                </button>
                                <p id="demomiki<? // = $tampilkan['idbpo']; ?>"></p>
                            <?php elseif ($tampilkan['jenis_po'] == "PO Mandiri"): ?>
                                <button type="submit" class="btn btn-success btn-sm text-center" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                                    <a style="color:white" href="ubahpo_mandiri?id=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                                </button>
                                <p id="demomiki<? // = $tampilkan['idbpo']; ?>"></p>
                            <?php endif ?>
                        </div>
                        <?php if ($tampilkan['jenis_po'] == "PO tanpa Stok" || $tampilkan['jenis_po'] == "PO Custom Tab" || $tampilkan['idpoproduk'] == '292' || $tampilkan['idpoproduk'] == '333' || $tampilkan['jenis_po'] == "PO Konin") : ?>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-success btn-sm text-center" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                                    <a style="color:white" href="ubahpo?id=<?= $idpoproduk; ?>&invoice=<?= $invoice; ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                                </button>
                            </div>
                            <?php if ($idpoproduk == 257) : ?>
                            <?php else : ?>
                                <p id="demomiki<?= $tampilkan['idbpo']; ?>" class="text-center"></p>
                            <?php endif; ?>
                        <?php endif ?> 
                        <div class="d-flex justify-content-center">
                            <?php // if ($tampilkan['jenis_po'] == "PO Custom Tab"): ?>
                                <!-- <div name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">	
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <a href="" data-toggle="modal" data-target="#modalView" style="color:white">Ubah Variant</a> 
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <a style="color:white" href="formpo_tabtambah?id=<?php echo $tampilkan['idpoproduk']; ?>&invoice=<?= $invoice; ?>">Tambah Variant</a>
                                    </button>
                                    <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>    
                                </div>      -->
                            <?php // endif ?>  
                        </div>
                        <div class="d-flex justify-content-center">
                            <?php if ($tampilkan['jenis_po'] == "PO Custom Tab Stok"): ?>
                                <div name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <a style="color:white" href="ubahpostok.php?id=<?= $idpoproduk; ?>&invoice=<?= $invoice ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <a style="color:white" href="formpo_tabtambahstok?id=<?php echo $tampilkan['idpoproduk']; ?>&invoice=<?= $invoice; ?>">Tambah Variant</a>
                                    </button>
                                    <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>    
                                </div>          
                            <?php endif ?>
                        </div>
                        <div class="d-flex justify-content-center">
                            <?php if ($tampilkan['jenis_po'] == "PO Custom Tab Stok Max"): ?>
                                <button type="submit" class="btn btn-success btn-sm" name="cari" id="linkmiki<?= $tampilkan['idbpo']; ?>">
                                    <a style="color:white" href="ubahpo_tabmax.php?id=<?= $idpoproduk; ?>">Ubah <?php echo $tampilkan['namapo']; ?></a>
                                </button>
                                <p id="demomiki<?= $tampilkan['idbpo']; ?>"></p>
                            <?php endif ?>
                        </div>
                        <br>
                        <script>
                            // Mengatur waktu akhir perhitungan mundur
                            var countDownDatemiki<?= $tampilkan['idbpo']; ?> = new Date("<?php echo $tampilkan['tgl_ubah']; ?> 23:59:00").getTime();

                            // Memperbarui hitungan mundur setiap 1 detik
                            var x = setInterval(function() {
                                // Untuk mendapatkan tanggal dan waktu hari ini
                                var now = new Date().getTime();

                                // Temukan jarak antara sekarang dan tanggal hitung mundur
                                var distance = countDownDatemiki<?= $tampilkan['idbpo']; ?> - now;

                                // Perhitungan waktu untuk hari, jam, menit dan detik
                                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                // Keluarkan hasil dalam elemen dengan id = "demo"
                                document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

                                // Jika hitungan mundur selesai, tulis beberapa teks 
                                if (distance < 0) {
                                    clearInterval(x);
                                    document.getElementById("demomiki<?= $tampilkan['idbpo']; ?>").innerHTML = " ";
                                    var x = document.getElementById("linkmiki<?= $tampilkan['idbpo']; ?>");
                                    x.style.display = "none";
                                }
                            }, 1000);
                        </script>
                    <?php } ?> 
                    <?php endif ?>
                    <?php if ($datapo['status'] != "Belum DP"): ?>
                        <div class="d-flex justify-content-center">
                            <a href="excel_po?invoice=<?= $invoice; ?>" class="btn btn-success btn-sm">Excel Invoice</a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <!-- Tab Content for Agen END -->
            <!-- Tab Content for Reseller -->
            <div id="reseller" class="container tab-pane fade">
                <?php
                    $sqlprogres = mysqli_query($koneksi, "SELECT 
                        SUM(surat_jalan_po.progres) as progres,
                        SUM(pomitra.jumlah) as jumlah
                        FROM surat_jalan_po
                        INNER JOIN pomitra on pomitra.idpomitra=surat_jalan_po.idpomitra
                        INNER JOIN podetail on podetail.idpodetail=surat_jalan_po.idpodetail
                        WHERE pomitra.idmitra='$idadmin' 
                        AND pomitra.idpoproduk='$idpoproduk' 
                        AND pomitra.jumlah>0 
                        AND surat_jalan_po.invoice='$invoice' 
                        AND (surat_jalan_po.status='Checker' OR surat_jalan_po.status='Ambil Barang')
                        GROUP BY pomitra.idpodetail");
                    $dataprogres = mysqli_fetch_array($sqlprogres); // Ambil semua data dari hasil eksekusi $sql                    
                ?>  
                <?php 
                    $sqlinvoice = mysqli_query($koneksi, "SELECT SUM(pomitra.jumlah) as jumlah
                                                            FROM pomitra
                                                            WHERE pomitra.idmitra='$idadmin' 
                                                            AND pomitra.idpoproduk='$idpoproduk' 
                                                            AND pomitra.jumlah>0 ");
                    $datainvoice = mysqli_fetch_array($sqlinvoice); // Ambil semua data dari hasil eksekusi $sql   
                ?>     
                <div class="table-responsive">
                    <?php
                        $progres = $dataprogres['progres'] / $datainvoice['jumlah'] * 100; 
                    ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty PO</th>
                                <th>Progres</th>
                                <th>Sisa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include "koneksi.php";
                                
                                $idadmin    = $_SESSION['idadmin'];
                                $idpoproduk = $_GET["id"];
                                $no         = 1;

                                $sql = mysqli_query($koneksi, "SELECT 
                                                                    pomitra.idpomitra,
                                                                    pomitra.jumlah,
                                                                    pomitra.invoice,
                                                                    pomitra.total,
                                                                    pomitra.custom,
                                                                    podetail.variant,
                                                                    podetail.harga,
                                                                    SUM(surat_jalan_po.progres) AS progres
                                                                FROM 
                                                                    pomitra 
                                                                INNER JOIN 
                                                                    podetail ON podetail.idpodetail = pomitra.idpodetail 
                                                                LEFT JOIN 
                                                                    surat_jalan_po ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                                WHERE 
                                                                    pomitra.idmitra = '$idadmin'
                                                                    AND pomitra.idpoproduk = '$idpoproduk'
                                                                    AND pomitra.invoice = '$datapo[invoice]'
                                                                    AND pomitra.jumlah > 0
                                                                GROUP BY 
                                                                    podetail.variant,
                                                                    pomitra.idpomitra,
                                                                    pomitra.jumlah,
                                                                    pomitra.invoice,
                                                                    pomitra.total,
                                                                    podetail.harga,
                                                                    pomitra.custom
                                                                ORDER BY 
                                                                    podetail.variant ASC
                                                                
                                                                    ");

                                while($data = mysqli_fetch_array($sql)){ // Ambil semua data dari hasil eksekusi $sql
                                    $id = $data['idpomitra'];
                                    $sisa = $data['jumlah'] - $data['progres'];
                            ?>
                            <tr>
                                <td class="align-middle"><?php echo $no++; ?></td>
                                <td class="align-middle"><?php echo $data['variant']; ?></td>
                                <td class="align-middle"><?php echo $data['jumlah']; ?></td>
                                <td class="align-middle">
                                    <?php if ($data['progres'] == ""): ?>
                                        0
                                    <?php else: ?>
                                        <?php echo $data['progres']; ?>
                                    <?php endif ?>
                                </td>
                                <td class="align-middle"><?= $sisa; ?></td>
                                <td class="align-middle">
                                    <? if($sisa == 0) : ?>
                                        <span class="p-1 bg-success rounded text-white">Selesai</span>
                                    <? else : ?>
                                        <span class="p-1 bg-warning rounded text-dark">Progres</span>
                                    <? endif ?>
                                </td>
                            </tr>
                            <?php
                                $sum_progres += $data['progres'];
                                $sum_jumlah += $data['jumlah'];
                                $sum_sisa += $sisa;
                                $jumlah_progres = $jumlah_progres + $totalnya;
                                $invoice = $datapo['invoice'];
                            ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">Total</td>
                                <td><?= $sum_jumlah; ?></td>
                                <td><?= $sum_progres; ?></td>
                                <td><?= $sum_sisa; ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- Tab Content for Reseller END -->
        </div>
    </div>
    
    <!-- MAIN CONTENT END -->
    
    <br><br><br><br>
    
    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP SYNTAK -->
    <!-- PHP SYNTAK END -->
    
    <!-- SCRIPT -->
    <script>
        document.getElementById('download').addEventListener('click', () => {
            const element = document.getElementById('invoice');
            html2pdf().from(element).save('invoice.pdf');
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- END SCRIPT -->
</body>
</html>