<?php
    session_start();
    include "koneksi.php";
    if(!isset($_SESSION["admin_mitra"])){
        echo "<script>alert('anda harus login terlebih dahulu');</script>";
        echo "<script>location='login.php';</script>";
        header('location:login.php');
        exit();
    }

    $idpoproduk = $_GET['id'];
    $invoice = $_GET['invoice'];
    $idadmin = $_SESSION["admin_mitra"]["idadmin"];
    $query = $koneksi->query("SELECT * FROM poproduk WHERE idpoproduk = $idpoproduk");
    $sql = $query->fetch_assoc();

    $data_mitra_query = $koneksi->query("SELECT * FROM pomitra WHERE invoice = '$invoice'");
    $data_mitra = $data_mitra_query->fetch_assoc();

    $query_produk = $koneksi->query("SELECT pomitra.*, podetail.*
                                            FROM pomitra INNER JOIN podetail
                                            ON podetail.idpodetail = pomitra.idpodetail
                                            WHERE pomitra.invoice = '$invoice'
                                            AND pomitra.jumlah > 0
                                    ");
    $querypengiriman = "SELECT podropship.namapengirim,
                                podropship.tlppengirim,
              					podropship.namapenerima,
                                podropship.tlppenerima,
              					podropship.alamatpenerima,
              					podropship.ekspedisi,
              					podropship.layanan,
                                podropship.ongkir,
                                podropship.dropship,
              					tb_ro_provinces.province_name,
              					tb_ro_cities.city_name,
              					tb_ro_subdistricts.subdistrict_name
              			FROM podropship 
              			LEFT JOIN tb_ro_provinces on podropship.provinsi = tb_ro_provinces.province_id
            			LEFT JOIN tb_ro_cities on podropship.kota = tb_ro_cities.city_id
            			LEFT JOIN tb_ro_subdistricts on podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
              			WHERE podropship.invoice='$invoice'
              		";
    $sqlpengiriman = mysqli_query($koneksi, $querypengiriman);  
    $datapengiriman = mysqli_fetch_array($sqlpengiriman);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mitra WNJ | <?= $_SESSION["admin_mitra"]["namamitra"] ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <style>
            /* Place the navbar at the bottom of the page, and make it stick */
            .navbaru {
                background-color: #eee;
                margin: auto;
                text-align: center;
                overflow: hidden;
                font-size: 20px;
                font-weight: 600;
            }

            .navbaru p {
                color: #0f0f0a;
                text-align: center;
            }

            .navbaru i {
                color: #0f0f0a;
                text-align: center;
            }

            .navbaru2 {
                margin: auto;
                text-align: center;
                overflow: hidden;
            }
        </style>
    </head>
    <body>
        <div class="row fixed-top navbaru py-2">
            <div class="col-2"><a href="listnewpo.php"><i class="bi bi-chevron-left"></i></a></div>
            <div class="col-8" ><p class="mb-0">PRE ORDER</p></div>
            <div class="col-2"></div>
        </div>

        <div class="container my-5 py-4">
            <div class="text-center">
                <h5 class="text-center"><?= $sql['namapo'] ?></h5>
                <p>#<span class="bg-success-subtle px-1 mx-1 fw-medium"><?= $invoice ?></span></p>
            </div>

            <hr />

            <div class="relative">
                <p class="d-inline-flex fw-semibold bg-danger-subtle px-2 rounded d-block mb-1">Info Pemesanan</p>
            </div>

            <div class="row my-1">
                <div class="col-lg-12">
                    <p class="fw-semibold d-block mb-1">Tanggal Pemesanan: <span class="fw-normal"><?= $data_mitra['tgl'] ?></span></p>
                    <!--<p class="fw-semibold d-block mb-1">Alamat: <span class="fw-normal"><?php echo $datapengiriman['alamatpenerima'] ?></span></p>-->
                    <!--<p class="fw-semibold d-block mb-1">Penerima: <span class="fw-normal"><?php echo $datapengiriman['namapenerima'] ?>, <?php echo $datapengiriman['tlppenerima'] ?></span></p>-->
                    <!--<p class="fw-semibold d-block mb-1">Pengirim: <span class="fw-normal"><?php echo $datapengiriman['namapengirim'] ?>, <?php echo $datapengiriman['tlppengirim'] ?></span></p>-->
                    <p class="fw-semibold d-block mb-1">Status Pembayaran: <span class="fw-normal"><?= $data_mitra['status'] ?></span></p>
                    <!--<a class="btn btn-sm btn-link" href="formdropship_kolibri?&id=<?= $invoice; ?>&idadmin=<?= $idadmin;?>">Isi Alamat</a>-->
                </div>

                <div class="col-lg-12 my-2">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-invoice-tab" data-bs-toggle="tab" data-bs-target="#nav-invoice" type="button" role="tab" aria-controls="nav-invoice" aria-selected="true">Info Invoice</button>
                            <button class="nav-link" id="nav-progres-tab" data-bs-toggle="tab" data-bs-target="#nav-progres" type="button" role="tab" aria-controls="nav-progres" aria-selected="false">Progres</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active mt-3" id="nav-invoice" role="tabpanel" aria-labelledby="nav-invoice-tab" tabindex="0">
                            <?php
                                $total_jumlah = 0;
                                $query = $koneksi->query("SELECT pomitra.*, podetail.*
                                                            FROM pomitra INNER JOIN podetail
                                                            ON podetail.idpodetail = pomitra.idpodetail
                                                            WHERE pomitra.invoice = '$invoice'
                                                            AND pomitra.jumlah > 0
                                                            AND podetail.idpodetail != 13558
                                                        ");
                                while ($row = $query->fetch_assoc()) {
                                    $total_jumlah += $row['jumlah'];
                                }

                                if ($total_jumlah >= 3) :
                            ?>
                                <a href="gift.php?id=<?= $idpoproduk ?>&invoice=<?= $invoice ?>&qty=<?= $total_jumlah; ?>" class="btn btn-success btn-sm">Ambil Hadiah</a>
                            <?php else : ?>
                                <a class="btn btn-success disabled btn-sm" aria-disabled="true" role="button" data-bs-toggle="button">Ambil Hadiah</a>
                                <p class="d-block mb-0 text-danger fz-6">Barang harus kelipatan 3</p>
                            <?php endif; ?>
                            
                            <div class="my-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                            $no = 1;
                                            $query_data = mysqli_query($koneksi, "SELECT podetail.*, pomitra.*
                                                                                FROM pomitra INNER JOIN podetail
                                                                                ON podetail.idpodetail = pomitra.idpodetail
                                                                                WHERE pomitra.invoice = '$invoice'
                                                                                AND pomitra.jumlah > 0
                                                                ");
                                            while ($item = mysqli_fetch_array($query_data)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <?php if ($item['custom'] !== NULL ) : ?>
                                                    <form method="post">
                                                        <td><?= $item['variant'] ?> (Gift)</td>
                                                        <td>0</td>
                                                        <td><?= $item['jumlah'] ?></td>
                                                        <td>Rp. 0</td>
                                                        <td>
                                                            <button type="submit" class="btn btn-success btn-sm" name="ubah">Ubah Variant</button>
                                                        </td>
                                                    </form>
                                                <?php else : ?>
                                                    <form method="post">
                                                        <td>
                                                            <select class="form-select form-select-sm" name="ubah_idpodetail">
                                                                <option selected><?= $item['variant'] ?></option>
                                                                <!-- Navy 543 -->
                                                                <option value="13552" class="fw-medium">Voal Hampers Navy</option>
                                                                <!-- Black 314 -->
                                                                <option value="13553" class="fw-medium">Voal Hampers Black (Stock habis)</option>
                                                                <!-- White 997 -->
                                                                <option value="13554" class="fw-medium">Voal Hampers White</option>
                                                                <!-- Grey 296 -->
                                                                <option value="13555" class="fw-medium">Voal Hampers Grey (Stock habis)</option>
                                                                <!-- Avocado 307 -->
                                                                <option value="13556" class="fw-medium">Voal Hampers Avocado</option>
                                                                <!-- Red Plum 456 -->
                                                                <option value="13557" class="fw-medium">Voal Hampers Red Plum</option>
                                                            </select>
                                                            <input type="hidden" value="<?= $item['idpomitra'] ?>" name="idpomitra" class="form-control form-control-sm">
                                                            <input type="hidden" value="<?= $item['idpo'] ?>" name="idpo_lama" class="form-control form-control-sm">
                                                        </td>
                                                        <td>Rp. <?= number_format($item['harga']) ?></td>
                                                        <td><?= $item['jumlah'] ?></td>
                                                        <td>Rp. <?= number_format($item['total']) ?></td>
                                                        <td>
                                                            <button type="submit" class="btn btn-success btn-sm" name="ubah">Ubah Variant</button>
                                                        </td>
                                                    </form>
                                                <?php endif; ?>
                                            </tr>
                                        <?php 
                                                $sum += $item['jumlah'];
                                                if ($item['custom'] == NULL) {
                                                    $total += $item['jumlah'] * $item['harga'];
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end">
                                <div class="table-responsive">
                                    <table border="0">
                                        <tr>
                                            <th width="150">Total Qty</th>
                                            <td width="20">:</td>
                                            <td><?= $sum ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah</th>
                                            <td>:</td>
                                            <td>Rp. <?= number_format($total) ?></td>
                                        </tr>

                                        <?php
                                            $persen = 35;
                                            $diskon = 35/100 * $total;
                                            $subtotal = $total + $dropship + $ongkir - $diskon;
                                        ?>
                                        <tr>
                                            <th>Ongkir</th>
                                            <td>:</td>
                                            <td>Rp. 0</td>
                                        </tr>
                                        <tr>
                                            <th>Dropship</th>
                                            <td>:</td>
                                            <td>Rp. 0</td>
                                        </tr>
                                        <tr>
                                            <th>Diskon DB <?= $persen ?>%</th>
                                            <td>:</td>
                                            <td>- Rp. <?= number_format($diskon) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Total Bayar</th>
                                            <td>:</td>
                                            <td>Rp. <?= number_format($subtotal) ?></td>
                                        </tr>
                                        <?php
                                            $sqldp = mysqli_query($koneksi, "SELECT popembayaran.invoice, popembayaran.jmlhtransfer, popembayaran.jmlh_lunas
                                                                                    FROM popembayaran 
                                                                                    WHERE popembayaran.invoice ='$invoice'
                                                                            ");
                                            $datadp = mysqli_fetch_array($sqldp);
                                            $sisa = $datadp['jmlhtransfer'] + $datadp['jmlh_lunas'] - $subtotal;
                                            $sisalunas = $subtotal - $datadp['jmlhtransfer']- $datadp['jmlh_lunas'];

                                            $namapayemnt = "DP";
                                            $jenispayment = "dp";
                                            $dp = $subtotal;
                                            if ($pembayaranpo == "Lunas") {
                                                $namapayemnt = "Pembayaran";
                                            }
                                        ?>
                                        <tr>
                                            <th>Sisa Tagihan</th>
                                            <td>:</td>
                                            <td>
                                                Rp.
                                                <?php if ($sisa > 0): ?>
                                                    +
                                                <?php endif; ?>

                                                <?php echo number_format($sisa); ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <p align="left"><strong>Note</strong>: <?php echo $note ?></p>
                                </div>
                            </div>

                            <div class="text-center">
                                <?php if ($sisa < 0) : ?>
                                    <a class="btn btn-primary btn-sm" href="popembayaran.php?invoice=<?= $invoice ?>&total=<?= $dp ?>&bayar=<?= $subtotal ?>&idpo=<?= $idpoproduk ?>&jenis=<?= $jenispayment ?>">Konfirmasi Pembayaran</a>
                                <?php else : ?>
                                    <span class="badge bg-success">Lunas</span>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-12">
                                
                            </div>
                        </div>
                        <div class="tab-pane fade my-2" id="nav-progres" role="tabpanel" aria-labelledby="nav-progres-tab" tabindex="0">
                            <?php
                                $sqlprogres = mysqli_query($koneksi,
                                                            "SELECT 
                                                                SUM(surat_jalan_po.progres) AS progres,
                                                                SUM(pomitra.jumlah) AS jumlah
                                                                FROM surat_jalan_po
                                                                INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                                INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                                                                WHERE pomitra.idmitra = '$idadmin' 
                                                                AND pomitra.idpoproduk = '$idpoproduk' 
                                                                AND pomitra.jumlah > 0 
                                                                AND surat_jalan_po.invoice = '$invoice' 
                                                                AND (surat_jalan_po.status = 'Checker' OR surat_jalan_po.status = 'Ambil Barang')
                                                                GROUP BY pomitra.idpodetail
                                                            ");
                                $dataprogres = mysqli_fetch_array($sqlprogres);

                                $sqlinvoice = mysqli_query($koneksi, "SELECT
                                                                        SUM(pomitra.jumlah) as jumlah
                                                                        FROM pomitra
                                                                        WHERE pomitra.idmitra = '$idadmin'
                                                                        AND pomitra.idpoproduk = '$idpoproduk'
                                                                        AND pomitra.jumlah > 0
                                ");
                                $datainvoice = mysqli_fetch_array($sqlinvoice);
                            ?>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>QTY</th>
                                        <th>Progres</th>
                                        <th>Sisa</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no = 1;
                                        $sql = mysqli_query($koneksi, "SELECT
                                                                podetail.variant,
                                                                podetail.harga,
                                                                pomitra.*,
                                                                SUM(surat_jalan_po.progres) AS progres
                                                                FROM pomitra
                                                                INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                                                LEFT JOIN surat_jalan_po ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                                                WHERE pomitra.idmitra = '$idadmin'
                                                                AND pomitra.idpoproduk = '$idpoproduk'
                                                                AND pomitra.invoice = '$invoice'
                                                                AND pomitra.jumlah > 0
                                                                GROUP BY pomitra.idpodetail
                                                                ORDER BY podetail.variant ASC
                                                            ");
                                        while ($data = mysqli_fetch_array($sql)) {
                                            $id = $data['idpomitra'];
                                            $sisa = $data['jumlah'] - $data['progres'];
                                    ?>
                                    <tr>
                                        <td class="align-middle"><?= $no++; ?></td>
                                        <td class="align-middle"><?= $data['variant'] ?></td>
                                        <td class="align-middle"><?= $data['jumlah'] ?></td>
                                        <td class="align-middle">
                                        <?php if($data['progres'] == "") : ?>
                                            0
                                        <?php else : ?>
                                            <?= $data['progres'] ?>
                                        <?php endif; ?>
                                        </td>
                                        <td class="align-middle"><?= $sisa ?></td>
                                        <td class="align-middle">
                                        <?php if($sisa == 0) : ?>
                                            <span class="badge text-bg-success">Selesai</span>
                                        <?php else : ?>
                                            <span class="badge text-bg-warning">Progres</span>
                                        <?php endif; ?>
                                        </td>
                                    </tr>   
                                    <?php
                                            $sum_progres += $data['progres'];
                                            $sum_jumlah += $data['jumlah'];
                                            $sum_sisa += $sisa;
                                            $jumlah_progres = $jumlah_progres + $totalnya;
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <td><?= $sum_jumlah ?></td>
                                        <td><?= $sum_progres ?></td>
                                        <td colspan="2"><?= $sum_sisa ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            if (isset($_POST['ubah'])) {
                $idpodetail = $_POST['ubah_idpodetail'];
                $idpo_lama = $_POST['idpo_lama'];
                $idpomitra = $_POST['idpomitra'];
                $query = $koneksi->query("SELECT * FROM poproduk
                                            INNER JOIN pomitra ON pomitra.idpoproduk = poproduk.idpoproduk
                                            INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                            INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                                            WHERE pomitra.idpomitra = $idpomitra
                                            AND podetail.idpodetail = $idpodetail
                                        ");
                $sql = $query->fetch_assoc();
                $idpo = $sql['idpo'];
                $qty = $sql['jumlah'];

                $check_produk = $koneksi->query("SELECT * FROM pokategori WHERE idpo = $idpo");
                $stock = $check_produk->fetch_assoc();

                if ($qty < $stock) {
                    $query_update = $koneksi->query("UPDATE pomitra SET idpodetail = $idpodetail, idpo = $idpo WHERE idpomitra = $idpomitra");
                    $query_stock = $koneksi->query("UPDATE pokategori SET stok = stok - $qty WHERE idpo = $idpo");
                    $query_plus_stock = $koneksi->query("UPDATE pokategori SET stok = stok + $qty WHERE idpo = $idpo_lama");
                    echo "<script>alert('Data berhasil diubah');</script>";
                    echo "<script>location='datapovoal.php?id=$idpoproduk&invoice=$invoice';</script>";
                } else {
                    echo "<script>alert('Stock barang tidak ada!');</script>";
                    echo "<script>location='datapovoal.php?id=$idpoproduk&invoice=$invoice';</script>";
                }
            }
        ?>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>