<?php
    error_reporting(E_ALL);

    include 'koneksi.php';
    $ids = $_GET['ids'] ?? [];
    $type = $_GET['type'] ?? '';

    if (!is_array($ids)) {
        $ids = [$ids];
    }
?>

<style type="text/css">
    @media print {
        footer {page-break-after: always;}
    }
</style>
<?php
    if ($type == 'alamat') {
        if (!empty($ids)) {
            foreach ($ids as $updateid) {
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
                                            poproduk.namapo,
                                            poproduk.idpoproduk,
                                            tb_ro_provinces.province_name AS provinsi,
                                            tb_ro_cities.city_name AS kota,
                                            tb_ro_subdistricts.subdistrict_name AS kecamatan,
                                            mitraagen.idmitraagen, mitrareseller.idmitrareseller, mitramarketer.idmitramarketer,
                                            COALESCE(mitraagen.idmitraagen, mitrareseller.idmitrareseller, mitramarketer.idmitramarketer) AS idmitra,
                                            COALESCE(mitraagen.idadmin, mitrareseller.idadmin, mitramarketer.idadmin, admin_mitra.idadmin) AS idadmin_mitra
                                        FROM podropship
                                            LEFT JOIN tb_ro_provinces ON podropship.provinsi = tb_ro_provinces.province_id
                                            LEFT JOIN tb_ro_cities ON podropship.kota = tb_ro_cities.city_id
                                            LEFT JOIN tb_ro_subdistricts ON podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                                            JOIN poproduk ON podropship.idpoproduk = poproduk.idpoproduk
                                            LEFT JOIN mitraagen ON podropship.idmitraagen = mitraagen.idmitraagen
                                            LEFT JOIN mitrareseller ON podropship.idmitrareseller = mitrareseller.idmitrareseller
                                            LEFT JOIN mitramarketer ON podropship.idmitramarketer = mitramarketer.idmitramarketer
                                            LEFT JOIN admin_mitra ON podropship.idadmin = admin_mitra.idadmin
                                        WHERE podropship.iddropship = '$updateid'
                                        ");
                $data = $sql->fetch_assoc();
                $tanggal        = date('Y-m-d');
                $invoice        = $data['invoice'];
                $penerima       = $data['namapenerima'];
                $keterangan     = $data['keterangan'];
                $namacs         = $_POST['namacs'];
                $ekspedisi      = $data['ekspedisi'];
                $pengirim       = $data['namapengirim'];
                $telp_pengirim  = $data['tlppengirim'];
                $telp_penerima  = $data['tlppenerima'];
                $idpoproduk     = $data['idpoproduk'];
                $alamat_parts = array_filter([
                    $data['alamatpenerima'],
                    $data['kecamatan'],
                    $data['kota'],
                    $data['provinsi']
                ]);

                
                $alamat = implode(', ', $alamat_parts);
                $idmitra        = $data['idmitra'] ?? NULL;
                $idadmin_mitra  = $data['idadmin_mitra'] ?? NULL;
                $idmitraagen     = ($idmitra == $data['idmitraagen']) ? "'$idmitra'" : "NULL";
                $idmitrareseller = ($idmitra == $data['idmitrareseller']) ? "'$idmitra'" : "NULL";
                $idmitramarketer = ($idmitra == $data['idmitramarketer']) ? "'$idmitra'" : "NULL";

                $ins_log = $koneksi->query("INSERT INTO logistik3 
                                                                (
                                                                    `idlogistik`,
                                                                    `tgl`, `penerima`, `ekspedisi`, `noresi`, `biayakirim`, `jumlah_koli`,
                                                                    `keterangan`, `status`, `idadmin`, `idmitraagen`, `idmitrareseller`, `idmitramarketer`,
                                                                    `namacs`, `no_sj`, `jenis_mitra`, `jenis_pengiriman`, `detail_pengiriman`
                                                                )
                                                            VALUES 
                                                                (
                                                                    NULL, '$tanggal', '$penerima', '$ekspedisi', NULL, 0, 0,
                                                                    '$keterangan', NULL, '$idadmin_mitra', 
                                                                    " . ($idmitra == $data['idmitraagen'] ? "'$idmitra'" : "NULL") . ",
                                                                    " . ($idmitra == $data['idmitrareseller'] ? "'$idmitra'" : "NULL") . ",
                                                                    " . ($idmitra == $data['idmitramarketer'] ? "'$idmitra'" : "NULL") . ",
                                                                    '$namacs', NULL, 'WNJ', 'PO', '$idpoproduk'
                                                                )
                                        ");
                if (!$ins_log) {
                    echo "Error: " . $koneksi->error;
                }
                if ($ins_log) {
                    $idlogistik_baru    = mysqli_insert_id($koneksi);
                    if (!$idlogistik_baru) {
                        die("Gagal mendapatkan idlogistik_baru: " . $koneksi->error);
                    }
                    $pengirim       = mysqli_real_escape_string($koneksi, $pengirim);
                    $alm_penerima   = mysqli_real_escape_string($koneksi, $alm_penerima);
                    $namacs         = mysqli_real_escape_string($koneksi, $namacs);
                    $alamat         = mysqli_real_escape_string($koneksi, $alamat);

                    $ins_tuser          = $koneksi->query("INSERT INTO `t_user`
                                                                (
                                                                    `id_user`, `idlogistik`, `namacs`, `nama`, `teleponpengirim`, `nama_penerima`, `teleponpenerima`, 
                                                                    `alamat`, `keterangan`, `ekspedisi`, `invoice`, `status`,
                                                                    `created_date`,`modified_date`,`resi_pengiriman`,`ongkir`,`pcs`,`marketplace`,`namamitra`,`idadmin`,`no_sj`,
                                                                    `jenis_mitra`
                                                                ) 
                                                            VALUES 
                                                                (
                                                                    NULL, '$idlogistik_baru', '$namacs', '$pengirim', '$telp_pengirim', '$penerima', '$telp_penerima', 
                                                                    '$alamat', NULL, '$ekspedisi', '$invoice', NULL, NOW(), 
                                                                    NOW(), NULL, NULL, NULL, NULL, 
                                                                    '$pengirim', '$idadmin_mitra', NULL, 'WNJ'
                                                                )
                                                        ");
                    if ($ins_tuser) {
                        $idTuser = $koneksi->insert_id;
                        $response = file_get_contents("https://wnj.id/api/generate_qr_api.php?id=$idTuser");

                        if ($response !== false) {
                            $data = json_decode($response, true);
                            $qrUrl = $data['qr_url'] ?? '';
                            $qrId  = $data['id'] ?? '';

                            $koneksi->query("UPDATE podropship SET proses = 'Proses' WHERE iddropship = '$updateid'");
                            ?>

                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        margin: 2rem;
                                        color: #000;
                                    }
                                    .warning {
                                        text-align: center;
                                        color: red;
                                        font-weight: bold;
                                        margin-bottom: 1rem;
                                    }
                                    .section {
                                        margin-bottom: 1.5rem;
                                    }
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        margin-bottom: 1rem;
                                    }
                                    td, th {
                                        padding: 0.5rem;
                                        border: 1px solid #000;
                                    }
                                    h4 {
                                        margin: 0.25rem 0;
                                    }
                                    p {
                                        margin: 0.125rem 0;
                                    }
                                    .divider {
                                        border-bottom: 1px dashed #000;
                                        margin: 1.5rem 0;
                                    }
                                    .flex-container {
                                        display: flex;
                                        justify-content: space-between;
                                        align-items: flex-start; /* atau center jika ingin sejajar vertikal */
                                        margin-top: 1rem;
                                    }

                                    .left-info {
                                        flex: 1;
                                    }

                                    .qr-container {
                                        text-align: center;
                                    }

                                    .qr-container img {
                                        border: 1px solid #000;
                                        padding: 5px;
                                        width: 100px;
                                    }
                                    table.columns {
                                        width: 100%;
                                        table-layout: fixed;
                                    }
                                    table.columns td {
                                        width: 33.33%; /* 100% / jumlah kolom */
                                    }
                                    .text-center {
                                        text-align: center;
                                    }
                                    @media print {
                                        .page-break {
                                            page-break-after: always;
                                        }
                                    }
                                </style>

                                <script>
                                    window.onload = function () {
                                        window.print();
                                    };
                                </script>
                                <div class="warning">
                                    <p>Jika Pesanan Sudah Sampai, Segera Cek Barang Sesuai Struk</p>
                                    <p style="font-size: 1.25rem;">Kami Tidak Menerima Komplain Tanpa Foto/Video Lebih dari 1x24 Jam</p>
                                </div>

                                <div class="divider"></div>

                                <div class="flex-container">
                                    <div class="section" align="center">
                                        <?php if (htmlspecialchars($data['jenis_mitra']) === "WNJ") : ?>
                                            <img src="../portal/img/wanoja.png" width="100">
                                        <?php else : ?>
                                            <img src="../portal/img/zizazu.png" width="100">
                                        <?php endif; ?>
                                    </div>
                                    <div class="qr-container">
                                        <img src="<?= $data['qr_url'] ?>" alt="QR Code Pengiriman">
                                        <p><small><?= $data['id'] ?></small></p>
                                    </div>
                                </div>

                                <table class="columns text-center">
                                    <tr>
                                        <td><strong>CSO:</strong> <?= htmlspecialchars($data['namacs']) ?></td>
                                        <td><strong>Ekspedisi:</strong> <?= htmlspecialchars($data['ekspedisi']) ?></td>
                                        <td></td>
                                    </tr>
                                </table>

                                <table class="text-center">
                                    <tr>
                                        <td>
                                            <h4>Pengirim:</h4>
                                            <p><?= htmlspecialchars($data['nama']) ?></p>
                                            <p><?= htmlspecialchars($data['tlppengirim']) ?></p>
                                        </td>
                                    </tr>
                                </table>

                                <table class="text-center">
                                    <tr>
                                        <td>
                                            <h4>Penerima:</h4>
                                            <p><?= htmlspecialchars($data['nama_penerima']) ?></p>
                                            <p><?= htmlspecialchars($data['tlppenerima']) ?></p>
                                            <p><?= htmlspecialchars($data['alamat']) ?></p>
                                        </td>
                                    </tr>
                                </table>

                                <div class="divider"></div>

                                <p><strong>Note:</strong> <?= htmlspecialchars($data['keterangan']) ?></p>
                                
                                <div class="left-info">
                                    <p><strong>Kode Mitra:</strong> <?= htmlspecialchars($data['idadmin']) ?></p>
                                    <p><strong>Tanggal:</strong> <?= date('d-m-Y') ?></p>
                                </div>

                                <div class="page-break"></div>

                            <?php
                        }
                    }
                }
            }
        }
    } elseif ($type == 'suratjalan') {
        try {
            if (!empty($ids)) {
                $countUpdatedId = count($ids);
                for ($x = 0; $x < $countUpdatedId; $x++) {
                    $iddropship = $ids[$x];
                    $datamitra = $koneksi->query("SELECT 
                                                        admin_mitra.*,
                                                        admin_mitra_cs.namacs,
                                                        podropship.*
                                                    FROM
                                                        podropship
                                                            JOIN
                                                        admin_mitra ON podropship.idadmin = admin_mitra.idadmin
                                                            INNER JOIN
                                                        admin_mitra_cs ON admin_mitra.idadmin = admin_mitra_cs.idadmin
                                                    WHERE
                                                        podropship.iddropship = '$iddropship'
                                                ");
                    $tampilnama     = $datamitra->fetch_assoc();
                    $idpoproduk     = $tampilnama['idpoproduk'];
                    $invoice        = $tampilnama['invoice'];
                    $no_ds          = $tampilnama['no_ds'];

                    $bukapo         = $koneksi->query("SELECT jenis_po FROM bukapo WHERE idpoproduk = '$idpoproduk'")->fetch_assoc();
                    $jenisPO        = $bukapo['jenis_po'];
    ?>
                    <link href="../vendor/adminwnj/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
                    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
                    <!-- Custom styles for this template-->
                    <link href="css/sb-admin-2.min.css" rel="stylesheet">
                    <style type="text/css">
                        table, th, td, tr {
                            border: 2px solid;
                            border-collapse: collapse;
                        }
                        body {
                            color: black;
                        }
                    </style>
                    <body>
                        <center><p style="font-size:60;margin-bottom:0;"><strong>WNJ.ID</strong></p></center>
                        <center><p style="font-size:40;margin-top:0;"><strong>Inv. <?= $invoice ?></strong></p></center>
                        <div class="row align-items-start">
                            <div class="col">
                                <h5><strong style="float:left">Penerima : <?php echo $tampilnama['namapenerima']; ?> </strong></h5>
                            </div>
                            <div class="col"></div>
                            <div class="col">
                                <h5><strong style="float:rigth">Nama CS : <?php echo $tampilnama['namacs']; ?> </strong></h5>
                            </div>
                        </div>
                        <table style="width:100%;font-size: 28px">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                        <th>Custom</th>
                                        <th>Font</th>
                                    <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                        <th>Template</th>
                                    <?php endif; ?>
                                    <th>Qty</th>
                                    <th>Checker</th>
                                    <th>Penerima</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($idpoproduk == 355) : ?>
                                    <?php
                                        $no = 1;
                                        $ambil = $koneksi->query("SELECT podetail.variant, 
                                                                        pods.jumlah, 
                                                                        pods.invoice, 
                                                                        pods.idpodetail, 
                                                                        pods.id, 
                                                                        pods.idpomitra, 
                                                                        pomitra.custom, 
                                                                        pomitra.idpo, 
                                                                        pomitra.idpodetail
                                                                    FROM pods
                                                                    INNER JOIN podetail ON podetail.idpodetail = pods.idpodetail
                                                                    LEFT JOIN pomitra ON pomitra.idpodetail = pods.idpodetail 
                                                                                    AND pomitra.invoice = pods.invoice 
                                                                                    AND pomitra.jumlah > 0 
                                                                                    AND pomitra.idpomitra = pods.idpomitra
                                                                    WHERE pods.no_ds = '$no_ds'
                                                                    GROUP BY pomitra.custom
                                                                    ORDER BY pods.id ASC
                                                                    ");
                                        while($data = $ambil->fetch_assoc()) {
                                            $custom = $data['custom'];
                                            $string = $custom;
                                            // Mengubah semua huruf menjadi huruf kecil
                                            $string = strtolower($string);
                                            // Mengganti spasi dengan tanda hubung
                                            $string = str_replace(' ', '-', $string);
                                            // Menghilangkan tanda - di awal string
                                            $string = ltrim($string, '-');
                                            // Menghapus karakter yang tidak diperlukan
                                            $string = preg_replace('/[^a-z0-9\-]/', '', $string);

                                            // Reset variabel untuk total per custom
                                            $total_custom = 0;
                                            $pack = 0;

                                            $idpodetail     = $data['idpodetail'];
                                            $invoice        = $data['invoice'];
                                            $idpomitra      = $data['idpomitra'];
                                            $id             = $data['id'];
                                            $sql_sarung     = $koneksi->query("SELECT * FROM podetail WHERE variant LIKE '%Sarung Etnic%'");
                                            $query_sarung   = $sql_sarung->fetch_assoc();
                                            $idsarung       = $query_sarung['idpodetail'];

                                            $data_jumlah = $koneksi->query("SELECT pomitra.jumlah, pomitra.custom
                                                                            FROM pomitra
                                                                            WHERE (pomitra.invoice= '$invoice')
                                                                            and pomitra.idpodetail = '$idpodetail'
                                                                            and pomitra.idpomitra = '$idpomitra'
                                                                            GROUP BY pomitra.idpomitra");
                                            $tampilprogres = $data_jumlah->fetch_assoc();

                                            $data_jumlah2 = $koneksi->query("SELECT SUM(pods.jumlah) as progresnya
                                                                            FROM pods
                                                                            WHERE pods.invoice='$invoice'
                                                                            and pods.idpodetail = '$idpodetail'
                                                                            and pods.idpomitra = '$idpomitra'");
                                            $tampilprogres2 = $data_jumlah2->fetch_assoc();
                                            $sisa = $tampilprogres['jumlah'] - $tampilprogres2['progresnya'];

                                            if ($idpodetail == 8920) {
                                                $datacustom = $koneksi->query("SELECT pomitra.custom
                                                                                FROM pomitra
                                                                                WHERE pomitra.idpomitra = '$idpomitra'");
                                                $custom = $datacustom->fetch_assoc();
                                            }
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <?php
                                                    $query = $koneksi->query("SELECT 
                                                                                podetail.*, pomitra.*
                                                                            FROM
                                                                                pomitra
                                                                                    INNER JOIN
                                                                                podetail ON pomitra.idpodetail = podetail.idpodetail
                                                                            WHERE
                                                                                pomitra.custom = '$custom'
                                                                                    AND pomitra.invoice = '$invoice'
                                                                                    AND pomitra.jumlah > 0
                                                                            ");
                                                    $first = true;
                                                    while ($data_produk = $query->fetch_assoc()) {
                                                        if (!$first) {
                                                            echo " | ";
                                                        }
                                                        $first = false;

                                                        // Tambahkan data produk
                                                        echo $data_produk['variant'];

                                                        // Hitung jumlah dan harga per item
                                                        $pack = $data_produk['jumlah'];
                                                        $total_custom += $data_produk['jumlah'] * $data_produk['harga'];
                                                    }
                                                ?>
                                            </td>
                                            <td class="text-center"><?= $data['jumlah']; ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                <?php else :
                                    $datapodropship = $koneksi->query("SELECT podetail.harga,
                                                                                podetail.variant, 
                                                                                pods.jumlah, 
                                                                                pods.invoice, 
                                                                                pods.idpodetail, 
                                                                                pods.id,
                                                                                pomitra.custom,
                                                                                pomitra.font,
                                                                                pomitra.template
                                                                        FROM pods
                                                                        JOIN podetail ON podetail.idpodetail = pods.idpodetail
                                                                        JOIN pomitra ON pomitra.idpomitra = pods.idpomitra
                                                                        WHERE pods.no_ds= '$no_ds'
                                                                        AND pods.jumlah>0
                                                                    ");
                                    $no = 1;
                                    while($tampilkan = $datapodropship->fetch_assoc()){
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>    
                                        <td><?= $tampilkan['variant'];?></td>
                                        <?php if ($jenisPO === 'PO Custom Inisial') : ?>
                                            <td><?= $tampilkan['custom'] ?></td>
                                            <td><?= $tampilkan['font'] ?></td>
                                        <?php elseif ($jenisPO === 'PO Custom Template') : ?>
                                            <td><?= $tampilkan['template'] ?></td>
                                        <?php endif; ?>
                                        <td><center><?= $tampilkan['jumlah'];?></center></td>
                                        <td></td>
                                        <td></td>
                                    </tr>                      
                                <?php 
                                    }
                                endif;
                                ?>
                            </tbody>
                        </table>
                        <br><br>
                        <center>
                            <table style="font-size: 25px;border-color: white;">
                                <tr>
                                    <td style="border-color: white;">
                                        <center>Gudang</center>
                                        <br><br><br><br>      
                                    </td>
                                    <td width="5%" style="border-color: white;"></td>
                                    <td style="border-color: white;">
                                        <center>Checker</center>
                                        <br><br><br><br>      
                                    </td>
                                    <td width="5%" style="border-color: white;"></td>    
                                    <td style="border-color: white;">
                                        <center>Penerima</center>
                                        <br><br><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-color: white;">
                                        <center>
                                            (<span style="color:transparent;">_____________________</span>)
                                        </center>      
                                    </td >
                                    <td style="border-color: white;"></td>
                                    <td style="border-color: white;">
                                        <center>
                                            (<span style="color:transparent;">_____________________</span>)
                                        </center>     
                                    </td>   
                                    <td style="border-color: white;"></td> 
                                    <td style="border-color: white;">
                                        <center>
                                            (<span style="color:transparent;">_____________________</span>)
                                        </center>
                                    </td>
                                </tr>
                            </table>
                        </center>
                        <div>
                            <p style="font-size: 22px"> 
                                Note : Setelah barang diterima, mohon langsung dicek. Surat jalan yang sudah diverifikasi dan sudah ditandatangani mohon untuk difoto dan dikirim melalui No HP CS Pusat maksimal 3 X 24 Jam
                                <br><br>
                                <?php if ($idpoproduk==186 or $idpoproduk==187): ?>
                                    <strong>Keterangan Isi Box</strong>
                                <?php endif; ?>
                                <br>
                                <?php 
                                    $ambil_box = $koneksi->query("SELECT *
                                                                FROM hampers
                                                                WHERE hampers.no_ds= '$invoice'
                                                                ORDER BY nobox ASC"); 
                                    while($data_box = $ambil_box->fetch_assoc()){
                                    $result_explode = explode('|', $data_box['ucapan']);
                                    $dari           = $result_explode[0];    
                                    $kepada         = $result_explode[1];    
                                    $ucapan         = $result_explode[2];        
                                ?>
                                <label>Box <?= $data_box['nobox'] ?> : <?php echo str_replace("|",", ",$data_box['idpodetail']); ?> <?php if ($dari or $kepada or $ucapan): ?>
                                    <span style="color: red">(Req. Kartu Ucapan)</span>
                                <?php endif ?></label>
                                <br>

                                    <?php } ?>                
                                </div>                  
                            </p>
                            <br>
                            <div class="footer"></div>
                            <footer></footer>
                        </div>
                    </body>
            <?php           
                }
            } else {
                echo "
                    <script>
                        alert('Pilih data yang ingin diubah!');
                        location='daftards.php';
                    </script>
                ";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif (isset($_POST['but_hapus'])) {
        if($ids){
            foreach($ids as $updateid) {
                $tampil = $koneksi->query("SELECT invoice FROM podropship where iddropship='$updateid' ");
                $tampilMar=$tampil->fetch_assoc();
                $id= $tampilMar['invoice'];
                $delete = "DELETE FROM podropship where iddropship='$updateid'";
                $sql = mysqli_query( $koneksi, $delete);
            }
            if ($sql) {
                echo "<script>alert('data berhasil dihapus');</script>";
                echo "<script>location='detaildropship.php?id=$id';</script>";
            }
        }
    } elseif ($type == 'batalproses') {
        try {
            date_default_timezone_set('Asia/Jakarta');
            $tanggal    = date('Y-m-d');
            if (!empty($ids)) {
                foreach ($ids as $updateid) {
                    $sql            = $koneksi->query("SELECT podropship.*, 
                                                            tb_ro_provinces.province_name AS provinsi,
                                                            tb_ro_cities.city_name AS kota,
                                                            tb_ro_subdistricts.subdistrict_name AS kecamatan,
                                                            mitraagen.idmitraagen, mitrareseller.idmitrareseller, mitramarketer.idmitramarketer,
                                                            COALESCE(mitraagen.idmitraagen, mitrareseller.idmitrareseller, mitramarketer.idmitramarketer) AS idmitra,
                                                            COALESCE(mitraagen.idadmin, mitrareseller.idadmin, mitramarketer.idadmin, admin_mitra.idadmin) AS idadmin_mitra
                                                        FROM podropship
                                                        LEFT JOIN tb_ro_provinces ON podropship.provinsi = tb_ro_provinces.province_id
                                                        LEFT JOIN tb_ro_cities ON podropship.kota = tb_ro_cities.city_id
                                                        LEFT JOIN tb_ro_subdistricts ON podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                                                        LEFT JOIN mitraagen ON podropship.idmitraagen = mitraagen.idmitraagen
                                                        LEFT JOIN mitrareseller ON podropship.idmitrareseller = mitrareseller.idmitrareseller
                                                        LEFT JOIN mitramarketer ON podropship.idmitramarketer = mitramarketer.idmitramarketer
                                                        LEFT JOIN admin_mitra ON podropship.idadmin = admin_mitra.idadmin
                                                        WHERE iddropship = '$updateid'
                                                    ");
                    $data           = $sql->fetch_assoc();

                    $alamat_parts = array_filter([
                        $data['alamatpenerima'],
                        $data['kecamatan'],
                        $data['kota'],
                        $data['provinsi']
                    ]);

                    $alamat         = implode(', ', $alamat_parts);
                    $alamat         = mysqli_real_escape_string($koneksi, $alamat);

                    $invoice        = $data['invoice'];

                    $getPortal      = $koneksi->query("SELECT * FROM t_user WHERE invoice = '$invoice' AND alamat = '$alamat'")->fetch_assoc();
                    if ($getPortal) {
                        $idlogistik     = $getPortal['idlogistik'];

                        $cancelProses   = $koneksi->query("UPDATE podropship SET proses = NULL WHERE iddropship = '$updateid'");
                        $deleteLogistik = $koneksi->query("DELETE FROM logistik3 WHERE idlogistik = '$idlogistik'");
                        $deleteTuser    = $koneksi->query("DELETE FROM t_user WHERE invoice = '$invoice' AND alamat = '$alamat'");
                        if (!$cancelProses || !$deleteLogistik || !$deleteTuser) {
                            echo "
                                <script>
                                    alert('Data Gagal di cancel proses!');
                                    location='detaildropship.php?id=$invoice';
                                </script>
                            ";
                        } else {
                            echo "
                                <script>
                                    alert('Data Berhasil cancel proses!');
                                    location='detaildropship.php?id=$invoice';
                                </script>
                            ";
                        }
                    } else {
                        echo "
                            <script>
                                alert('Data tidak ditemukan di t_user!');
                                location='detaildropship.php?id=$invoice';
                            </script>
                        ";
                    }
                }
            }
        } catch (Exception $e) {
            echo "Error" . $e->getMessage();
        }
    } 
?>
<script>
 window.print();
</script>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>

</html> -->