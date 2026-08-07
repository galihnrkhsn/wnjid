<?php
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';
    include '../includes/promo_badge_helper.php';

    $idmitra = $_SESSION["idadmin"];
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
    <div class="container mt-2">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#home">Ready Stok Reguler</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#get">Buy 1 Get 1</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bundling3">Bundling 3</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bundling5">Bundling 5</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#flash">Cuci Gudang</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#gb">Grade B</a></li>
        </ul>
        <div class="tab-content">
            <div id="home" class="tab-pane fade show active">
                <br>
                <form method="POST" action="save_cart.php">
                    <table class="table table-bordered table-striped" data-cart-table>
                        <thead>
                            <th><input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                            <th>Nama</th>
                            <th>Variant</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <?php
                                $total = 0;
                                $berat = 0;
                                $qty   = 0;

                                $stmtHome = $koneksi->prepare("SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        INNER JOIN products ON variants.idproducts = products.id
                                        WHERE keranjang.idmitra = ?
                                        AND keranjang.jmlh > 0
                                        AND (products.idkategori < 50)
                                        AND keranjang.status = 'Active'
                                            AND (variants.jenis IS NULL
                                            OR (
                                                variants.jenis NOT LIKE 'Sale'
                                                AND variants.jenis NOT LIKE '%bundling%'
                                                AND variants.jenis NOT LIKE '%Flash%'
                                                AND variants.jenis NOT LIKE '%b1g1%'
                                                AND variants.jenis NOT LIKE '%GB%'
                                            )
                                       )");
                                $stmtHome->bind_param('s', $idmitra);
                                $stmtHome->execute();
                                $query = $stmtHome->get_result();

                                if ($query->num_rows === 0):
                            ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada produk Ready Stok Reguler di keranjang.</td></tr>
                            <?php
                                endif;
                                $adaItemHome = ($query->num_rows > 0);
                                while($row = $query->fetch_assoc()) {
                                    $idproduk = $row['id'];
                                    $hargaEfektif    = ($row['disc'] > 0) ? hargaSetelahDisc((int) $row['harga'], (int) $row['disc']) : (int) $row['harga'];
                                    $subtotalDinamis = $hargaEfektif * (int) $row['jmlh'];
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?= (int) $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= htmlspecialchars($row['harga']); ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= (int) $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= htmlspecialchars($idmitra); ?>">
                                <input type="hidden" name="stock[]" value="<?= (int) $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= (int) $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $subtotalDinamis; ?>">
                                <td style="text-align: right;">
                                    <?php
                                        $disable = "block";
                                        if ( $row['statusnya']=="Expired" ) {
                                            $disable = "none";
                                        }
                                    ?>
                                    <?php if ( $row['statusnya'] == "Expired" ): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?= (int) $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> |
                                        <div class="badge bg-danger text-white rounded-pill"><?= htmlspecialchars($row['statusnya']); ?></div>
                                    <?php endif ?>
                                    <?php if ( $row['statusnya'] == "Active" ): ?>
                                        <input type="checkbox" name="idkeranjang[]" class="item-checkbox" data-jmlh="<?= (int) $row['jmlh']; ?>" data-subtotal="<?= $subtotalDinamis; ?>" value="<?= (int) $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= htmlspecialchars($row['namaproduk']); ?></td>
                                <td><?= htmlspecialchars($row['variant'] . ' - Sz ' . $row['size']); ?></td>
                                <td>
                                    <?php if ($row['disc'] > 0): ?>
                                        <span style='text-decoration: line-through'>Rp. <?= number_format($row['harga'], 2); ?></span>
                                        <br>
                                        Rp. <?= number_format($hargaEfektif, 2); ?>
                                    <?php else: ?>
                                        <?php $coret = number_format($row['hargacoret'],2);
                                            if($row['hargacoret'] <> 0){
                                                echo "<span style='text-decoration: line-through'>Rp. $coret </span>";
                                            }
                                        ?>
                                        <br>
                                        Rp. <?= number_format($row['harga'], 2); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?= (int) $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?= (int) $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?= (int) $row['stock']; ?>
                                </td>
                                <td><?= number_format($subtotalDinamis, 2) ?></td>
                                <?php
                                    $berat += $row['berat'];
                                ?>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><span class="js-qty">0</span></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><span class="js-total">0.00</span></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= htmlspecialchars($berat); ?>">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2"><a href="store4.php" class="btn btn-warning btn-s"><i class="fa-solid fa-chevron-left"></i></a></div>
                        <div class="p-2"></div>
                        <div class="p-2"><button type="submit" class="btn btn-success btn-s" name="save" id="ubahstock">Ubah Stock</button></div>
                    </div>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Klik Ubah Stock sebelum checkout
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php
                        if(!$adaItemHome) {
                            echo "<a href='store4.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                        } else {
                            echo "<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <i class='fa-solid fa-chevron-right'></i></button>";
                        }
                        ?>
                    </div>
                </form>
            </div>
            <div id="get" class="tab-pane">
                <br>
                <form method="POST" action="save_cart.php">
                    <table class="table table-bordered table-striped" data-cart-table>
                        <thead>
                            <th><input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                            <th>Nama</th>
                            <th>Variant</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <?php
                                $total = 0;
                                $berat = 0;
                                $qty   = 0;

                                $stmtGet = $koneksi->prepare("SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        INNER JOIN products ON variants.idproducts = products.id
                                        WHERE keranjang.idmitra = ?
                                        AND keranjang.jmlh > 0
                                        AND (products.idkategori < 50)
                                        AND keranjang.status = 'Active'
                                        AND variants.jenis LIKE '%b1g1%'");
                                $stmtGet->bind_param('s', $idmitra);
                                $stmtGet->execute();
                                $query = $stmtGet->get_result();

                                if ($query->num_rows === 0):
                            ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada produk Buy 1 Get 1 di keranjang.</td></tr>
                            <?php
                                endif;
                                $adaItemGet = ($query->num_rows > 0);
                                while($row = $query->fetch_assoc()) {
                                    $hargaEfektif    = ($row['disc'] > 0) ? hargaSetelahDisc((int) $row['harga'], (int) $row['disc']) : (int) $row['harga'];
                                    $subtotalDinamis = $hargaEfektif * (int) $row['jmlh'];
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?= (int) $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= htmlspecialchars($row['harga']); ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= (int) $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= htmlspecialchars($idmitra); ?>">
                                <input type="hidden" name="stock[]" value="<?= (int) $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= (int) $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $subtotalDinamis; ?>">
                                <input type="hidden" name="jenis" value="<?= htmlspecialchars($row['jenis']); ?>">
                                <td style="text-align: right;">
                                    <?php
                                        $disable = "block";
                                        if ( $row['statusnya']=="Expired" ) {
                                            $disable = "none";
                                        }
                                    ?>
                                    <?php if ( $row['statusnya'] == "Expired" ): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?= (int) $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> |
                                        <div class="badge bg-danger text-white rounded-pill"><?= htmlspecialchars($row['statusnya']); ?></div>
                                    <?php endif ?>
                                    <?php if ( $row['statusnya'] == "Active" ): ?>
                                        <input type="checkbox" name="idkeranjang[]" class="item-checkbox" data-jmlh="<?= (int) $row['jmlh']; ?>" data-subtotal="<?= $subtotalDinamis; ?>" value="<?= (int) $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= htmlspecialchars($row['namaproduk']); ?></td>
                                <td><?= htmlspecialchars($row['variant'] . ' - Sz ' . $row['size']); ?></td>
                                <td>
                                    <?php if ($row['disc'] > 0): ?>
                                        <span style='text-decoration: line-through'>Rp. <?= number_format($row['harga'], 2); ?></span>
                                        <br>
                                        Rp. <?= number_format($hargaEfektif, 2); ?>
                                    <?php else: ?>
                                        <?php $coret = number_format($row['hargacoret'],2);
                                            if($row['hargacoret'] <> 0){
                                                echo "<span style='text-decoration: line-through'>Rp. $coret </span>";
                                            }
                                        ?>
                                        <br>
                                        Rp. <?= number_format($row['harga'], 2); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?= (int) $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                     <?= (int) $row['jmlh']; ?>
                                </td>
                                <td><?= number_format($subtotalDinamis, 2) ?></td>
                                <?php
                                    $berat += $row['berat'];
                                ?>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><span class="js-qty">0</span></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><span class="js-total">0.00</span></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= htmlspecialchars($berat); ?>">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2"><a href="store4.php" class="btn btn-warning btn-s"><i class="fa-solid fa-chevron-left"></i></a></div>
                        <div class="p-2"></div>
                        <div class="p-2"><button type="submit" class="btn btn-success btn-s" name="save" id="ubahstock">Ubah Stock</button></div>
                    </div>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Klik Ubah Stock sebelum checkout
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php
                        if(!$adaItemGet) {
                            echo "<a href='store4.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                        } else {
                            echo "<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <i class='fa-solid fa-chevron-right'></i></button>";
                        }
                        ?>
                    </div>
                </form>
            </div>
            <div id="bundling3" class="tab-pane">
                <br>
                <form method="POST" action="save_cart.php">
                    <table class="table table-bordered table-striped" data-cart-table>
                        <thead>
                            <th><input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                            <th>Nama</th>
                            <th>Variant</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                        <?php
                            $total = 0;
                            $berat = 0;
                            $qty   = 0;

                            $stmtB3 = $koneksi->prepare("SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                    INNER JOIN variants ON keranjang.idproduk = variants.id
                                    INNER JOIN products ON variants.idproducts = products.id
                                    WHERE keranjang.idmitra = ?
                                    AND keranjang.jmlh > 0
                                    AND (products.idkategori < 50)
                                    AND keranjang.status = 'Active'
                                    AND variants.jenis LIKE '%Bundling 3%'");
                            $stmtB3->bind_param('s', $idmitra);
                            $stmtB3->execute();
                            $query = $stmtB3->get_result();

                            if ($query->num_rows === 0):
                        ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada produk Bundling 3 di keranjang.</td></tr>
                        <?php
                            endif;
                            $adaItemB3 = ($query->num_rows > 0);
                            while($row = $query->fetch_assoc()) {
                                $kelipatan = intval($row['jmlh'] / 3);
                                $pengurangan = $kelipatan * 15000;

                                $hargaEfektif    = ($row['disc'] > 0) ? hargaSetelahDisc((int) $row['harga'], (int) $row['disc']) : (int) $row['harga'];
                                $subtotalDinamis = $hargaEfektif * (int) $row['jmlh'] - $pengurangan;
                                if ($subtotalDinamis < 0) {
                                    $subtotalDinamis = 0;
                                }

                                $berat += $row['berat'];
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?= (int) $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= htmlspecialchars($row['harga']); ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= (int) $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= htmlspecialchars($idmitra); ?>">
                                <input type="hidden" name="stock[]" value="<?= (int) $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= (int) $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $subtotalDinamis; ?>">
                                <td style="text-align: right;">
                                    <?php
                                    $disable = "block";
                                    if ($row['statusnya'] == "Expired") {
                                        $disable = "none";
                                    }
                                    ?>
                                    <?php if ($row['statusnya'] == "Expired"): ?>
                                        <div class="badge bg-warning text-white rounded-pill">
                                            <a href="hapus_expired.php?idkeranjang=<?= (int) $row['idkeranjang']; ?>"
                                            class="link text-white"
                                            onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">
                                            Hapus
                                            </a>
                                        </div> |
                                        <div class="badge bg-danger text-white rounded-pill"><?= htmlspecialchars($row['statusnya']); ?></div>
                                    <?php endif ?>
                                    <?php if ($row['statusnya'] == "Active"): ?>
                                        <input type="checkbox" name="idkeranjang[]" class="item-checkbox" data-jmlh="<?= (int) $row['jmlh']; ?>" data-subtotal="<?= $subtotalDinamis; ?>" value="<?= (int) $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= htmlspecialchars($row['namaproduk']); ?></td>
                                <td><?= htmlspecialchars($row['variant'] . ' - Sz ' . $row['size']); ?></td>
                                <td>
                                    <?php if ($row['disc'] > 0): ?>
                                        <span style='text-decoration: line-through'>Rp. <?= number_format($row['harga'], 2); ?></span>
                                        <br>
                                        Rp. <?= number_format($hargaEfektif, 2); ?>
                                    <?php elseif ($row['jmlh'] % 3 == 0) : ?>
                                        <span style='text-decoration: line-through'>
                                            Rp. <?= number_format($row['harga'], 2); ?>
                                        </span>
                                    <?php else : ?>
                                            Rp. <?= number_format($row['harga'], 2); ?>
                                    <?php endif; ?>
                                    <br>
                                </td>
                                <td>
                                    <?php if ($row['statusnya'] == "Expired"): ?>
                                        <?= (int) $row['jmlh']; ?>
                                        <br>
                                    <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?= (int) $row['jmlh']; ?>" name="jmlhbaru[]">
                                    Ready Stock : <?= (int) $row['stock']; ?>
                                </td>
                                <td>
                                    Rp. <?= number_format($subtotalDinamis, 2); ?> <br>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><span class="js-qty">0</span></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><span class="js-total">0.00</span></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= htmlspecialchars($berat); ?>">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2"><a href="store4.php" class="btn btn-warning btn-s"><i class="fa-solid fa-chevron-left"></i></a></div>
                        <div class="p-2"></div>
                        <div class="p-2"><button type="submit" class="btn btn-success btn-s" name="save" id="ubahstock">Ubah Stock</button></div>
                    </div>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Klik Ubah Stock sebelum checkout
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php
                        if(!$adaItemB3) {
                            echo "<a href='store4.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                        } else {
                            echo "<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <i class='fa-solid fa-chevron-right'></i></button>";
                        }
                        ?>
                    </div>
                </form>
            </div>
            <div id="bundling5" class="tab-pane">
                <br>
                <form method="POST" action="save_cart.php">
                    <table class="table table-bordered table-striped" data-cart-table>
                        <thead>
                            <th><input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                            <th>Nama</th>
                            <th>Variant</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <?php
                                $total = 0;
                                $berat = 0;
                                $qty   = 0;

                                $stmtB5 = $koneksi->prepare("SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        INNER JOIN products ON variants.idproducts = products.id
                                        WHERE keranjang.idmitra = ?
                                        AND keranjang.jmlh > 0
                                        AND (products.idkategori < 50)
                                        AND keranjang.status = 'Active'
                                        AND variants.jenis = 'Bundling 5'");
                                $stmtB5->bind_param('s', $idmitra);
                                $stmtB5->execute();
                                $query = $stmtB5->get_result();

                                if ($query->num_rows === 0):
                            ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada produk Bundling 5 di keranjang.</td></tr>
                            <?php
                                endif;
                                $adaItemB5 = ($query->num_rows > 0);
                                while($row = $query->fetch_assoc()) {
                                    $kelipatan = intval($row['jmlh'] / 5);
                                    $pengurangan = $kelipatan * 25000;

                                    $hargaEfektif    = ($row['disc'] > 0) ? hargaSetelahDisc((int) $row['harga'], (int) $row['disc']) : (int) $row['harga'];
                                    $subtotalDinamis = $hargaEfektif * (int) $row['jmlh'] - $pengurangan;
                                    if ($subtotalDinamis < 0) {
                                        $subtotalDinamis = 0;
                                    }

                                    $berat += $row['berat'];
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?= (int) $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= htmlspecialchars($row['harga']); ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= (int) $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= htmlspecialchars($idmitra); ?>">
                                <input type="hidden" name="stock[]" value="<?= (int) $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= (int) $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $subtotalDinamis; ?>">
                                <td style="text-align: right;">
                                    <?php
                                    $disable = "block";
                                    if ($row['statusnya'] == "Expired") {
                                        $disable = "none";
                                    }
                                    ?>
                                    <?php if ($row['statusnya'] == "Expired"): ?>
                                        <div class="badge bg-warning text-white rounded-pill">
                                            <a href="hapus_expired.php?idkeranjang=<?= (int) $row['idkeranjang']; ?>"
                                            class="link text-white"
                                            onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">
                                            Hapus
                                            </a>
                                        </div> |
                                        <div class="badge bg-danger text-white rounded-pill"><?= htmlspecialchars($row['statusnya']); ?></div>
                                    <?php endif ?>
                                    <?php if ($row['statusnya'] == "Active"): ?>
                                        <input type="checkbox" name="idkeranjang[]" class="item-checkbox" data-jmlh="<?= (int) $row['jmlh']; ?>" data-subtotal="<?= $subtotalDinamis; ?>" value="<?= (int) $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= htmlspecialchars($row['namaproduk']); ?></td>
                                <td><?= htmlspecialchars($row['variant'] . ' - Sz ' . $row['size']); ?></td>
                                <td>
                                    <?php if ($row['disc'] > 0): ?>
                                        <span style='text-decoration: line-through'>Rp. <?= number_format($row['harga'], 2); ?></span>
                                        <br>
                                        Rp. <?= number_format($hargaEfektif, 2); ?>
                                    <?php elseif ($row['jmlh'] % 5 == 0) : ?>
                                        <span style='text-decoration: line-through'>
                                            Rp. <?= number_format($row['harga'], 2); ?>
                                        </span>
                                    <?php else : ?>
                                            Rp. <?= number_format($row['harga'], 2); ?>
                                    <?php endif; ?>
                                    <br>
                                </td>
                                <td>
                                    <?php if ($row['statusnya'] == "Expired"): ?>
                                        <?= (int) $row['jmlh']; ?>
                                        <br>
                                    <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?= (int) $row['jmlh']; ?>" name="jmlhbaru[]">
                                    Ready Stock : <?= (int) $row['stock']; ?>
                                </td>
                                <td>
                                    Rp. <?= number_format($subtotalDinamis, 2); ?> <br>
                                </td>
                            </tr>
                            <?php
                                }
                            ?>
                            <tr>
                                <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><span class="js-qty">0</span></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><span class="js-total">0.00</span></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= htmlspecialchars($berat); ?>">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2"><a href="store4.php" class="btn btn-warning btn-s"><i class="fa-solid fa-chevron-left"></i></a></div>
                        <div class="p-2"></div>
                        <div class="p-2"><button type="submit" class="btn btn-success btn-s" name="save" id="ubahstock">Ubah Stock</button></div>
                    </div>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Klik Ubah Stock sebelum checkout
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php
                        if(!$adaItemB5) {
                            echo "<a href='store4.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                        } else {
                            echo "<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <i class='fa-solid fa-chevron-right'></i></button>";
                        }
                        ?>
                    </div>
                </form>
            </div>
            <div id="flash" class="tab-pane">
                <br>
                <form method="POST" action="save_cart.php">
                    <table class="table table-bordered table-striped" data-cart-table>
                        <thead>
                            <th><input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                            <th>Nama</th>
                            <th>Variant</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <?php
                                $total = 0;
                                $berat = 0;
                                $qty   = 0;

                                $stmtFlash = $koneksi->prepare("SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        INNER JOIN products ON variants.idproducts = products.id
                                        WHERE keranjang.idmitra = ?
                                        AND keranjang.jmlh > 0
                                        AND (products.idkategori < 50)
                                        AND keranjang.status = 'Active'
                                        AND variants.jenis LIKE '%Flash%'");
                                $stmtFlash->bind_param('s', $idmitra);
                                $stmtFlash->execute();
                                $query = $stmtFlash->get_result();

                                if ($query->num_rows === 0):
                            ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada produk Cuci Gudang di keranjang.</td></tr>
                            <?php
                                endif;
                                $adaItemFlash = ($query->num_rows > 0);
                                while($row = $query->fetch_assoc()) {
                                    $hargaEfektif    = ($row['disc'] > 0) ? hargaSetelahDisc((int) $row['harga'], (int) $row['disc']) : (int) $row['harga'];
                                    $subtotalDinamis = $hargaEfektif * (int) $row['jmlh'];
                            ?>
                            <tr>
                                <input type="hidden" name="jenis" value="<?= htmlspecialchars($row['jenis']); ?>">
                                <input type="hidden" name="idprodukubah[]" value="<?= (int) $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= htmlspecialchars($row['harga']); ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= (int) $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= htmlspecialchars($idmitra); ?>">
                                <input type="hidden" name="stock[]" value="<?= (int) $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= (int) $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $subtotalDinamis; ?>">
                                <td style="text-align: right;">
                                    <?php
                                        $disable = "block";
                                        if ( $row['statusnya']=="Expired" ) {
                                            $disable = "none";
                                        }
                                    ?>
                                    <?php if ( $row['statusnya'] == "Expired" ): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?= (int) $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> |
                                        <div class="badge bg-danger text-white rounded-pill"><?= htmlspecialchars($row['statusnya']); ?></div>
                                    <?php endif ?>
                                    <?php if ( $row['statusnya'] == "Active" ): ?>
                                        <input type="checkbox" name="idkeranjang[]" class="item-checkbox" data-jmlh="<?= (int) $row['jmlh']; ?>" data-subtotal="<?= $subtotalDinamis; ?>" value="<?= (int) $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= htmlspecialchars($row['namaproduk']); ?></td>
                                <td><?= htmlspecialchars($row['variant'] . ' - Sz ' . $row['size']); ?></td>
                                <td>
                                    <?php if ($row['disc'] > 0): ?>
                                        <span style='text-decoration: line-through'>Rp. <?= number_format($row['harga'], 2); ?></span>
                                        <br>
                                        Rp. <?= number_format($hargaEfektif, 2); ?>
                                    <?php else: ?>
                                        <?php $coret = number_format($row['hargacoret'],2);
                                            if($row['hargacoret'] <> 0){
                                                echo "<span style='text-decoration: line-through'>Rp. $coret </span>";
                                            }
                                        ?>
                                        <br>
                                        Rp. <?= number_format($row['harga'], 2); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?= (int) $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?= (int) $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?= (int) $row['stock']; ?>
                                </td>
                                <td><?= number_format($subtotalDinamis, 2) ?></td>
                                <?php
                                    $berat += $row['berat'];
                                ?>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><span class="js-qty">0</span></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><span class="js-total">0.00</span></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= htmlspecialchars($berat); ?>">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2"><a href="store4.php" class="btn btn-warning btn-s"><i class="fa-solid fa-chevron-left"></i></a></div>
                        <div class="p-2"></div>
                        <div class="p-2"><button type="submit" class="btn btn-success btn-s" name="save" id="ubahstock">Ubah Stock</button></div>
                    </div>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Klik Ubah Stock sebelum checkout
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php
                        if(!$adaItemFlash) {
                            echo "<a href='store4.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                        } else {
                            echo "<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <i class='fa-solid fa-chevron-right'></i></button>";
                        }
                        ?>
                    </div>
                </form>
            </div>
            <div id="gb" class="tab-pane">
                <br>
                <form method="POST" action="save_cart.php">
                    <table class="table table-bordered table-striped" data-cart-table>
                        <thead>
                            <th><input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                            <th>Nama</th>
                            <th>Variant</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <?php
                                $total = 0;
                                $berat = 0;
                                $qty   = 0;

                                $stmtGb = $koneksi->prepare("SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        INNER JOIN products ON variants.idproducts = products.id
                                        WHERE keranjang.idmitra = ?
                                        AND keranjang.jmlh > 0
                                        AND (products.idkategori < 50)
                                        AND keranjang.status = 'Active'
                                        AND variants.jenis LIKE '%GB%'");
                                $stmtGb->bind_param('s', $idmitra);
                                $stmtGb->execute();
                                $query = $stmtGb->get_result();

                                if ($query->num_rows === 0):
                            ?>
                            <tr><td colspan="6" class="text-center text-muted">Belum ada produk Grade B di keranjang.</td></tr>
                            <?php
                                endif;
                                $adaItemGb = ($query->num_rows > 0);
                                while($row = $query->fetch_assoc()) {
                                    $hargaEfektif    = ($row['disc'] > 0) ? hargaSetelahDisc((int) $row['harga'], (int) $row['disc']) : (int) $row['harga'];
                                    $subtotalDinamis = $hargaEfektif * (int) $row['jmlh'];
                            ?>
                            <tr>
                                <input type="hidden" name="jenis" value="<?= htmlspecialchars($row['jenis']); ?>">
                                <input type="hidden" name="idprodukubah[]" value="<?= (int) $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= htmlspecialchars($row['harga']); ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= (int) $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= htmlspecialchars($idmitra); ?>">
                                <input type="hidden" name="stock[]" value="<?= (int) $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= (int) $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $subtotalDinamis; ?>">
                                <td style="text-align: right;">
                                    <?php
                                        $disable = "block";
                                        if ( $row['statusnya']=="Expired" ) {
                                            $disable = "none";
                                        }
                                    ?>
                                    <?php if ( $row['statusnya'] == "Expired" ): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?= (int) $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> |
                                        <div class="badge bg-danger text-white rounded-pill"><?= htmlspecialchars($row['statusnya']); ?></div>
                                    <?php endif ?>
                                    <?php if ( $row['statusnya'] == "Active" ): ?>
                                        <input type="checkbox" name="idkeranjang[]" class="item-checkbox" data-jmlh="<?= (int) $row['jmlh']; ?>" data-subtotal="<?= $subtotalDinamis; ?>" value="<?= (int) $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= htmlspecialchars($row['namaproduk']); ?></td>
                                <td><?= htmlspecialchars($row['variant'] . ' - Sz ' . $row['size']); ?></td>
                                <td>
                                    <?php if ($row['disc'] > 0): ?>
                                        <span style='text-decoration: line-through'>Rp. <?= number_format($row['harga'], 2); ?></span>
                                        <br>
                                        Rp. <?= number_format($hargaEfektif, 2); ?>
                                    <?php else: ?>
                                        <?php $coret = number_format($row['hargacoret'],2);
                                            if($row['hargacoret'] <> 0){
                                                echo "<span style='text-decoration: line-through'>Rp. $coret </span>";
                                            }
                                        ?>
                                        <br>
                                        Rp. <?= number_format($row['harga'], 2); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?= (int) $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?= (int) $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?= (int) $row['stock']; ?>
                                </td>
                                <td><?= number_format($subtotalDinamis, 2) ?></td>
                                <?php
                                    $berat += $row['berat'];
                                ?>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><span class="js-qty">0</span></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><span class="js-total">0.00</span></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= htmlspecialchars($berat); ?>">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2"><a href="store4.php" class="btn btn-warning btn-s"><i class="fa-solid fa-chevron-left"></i></a></div>
                        <div class="p-2"></div>
                        <div class="p-2"><button type="submit" class="btn btn-success btn-s" name="save" id="ubahstock">Ubah Stock</button></div>
                    </div>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Klik Ubah Stock sebelum checkout
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php
                        if(!$adaItemGb) {
                            echo "<a href='store4.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                        } else {
                            echo "<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <i class='fa-solid fa-chevron-right'></i></button>";
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <script type="text/javascript">
        // Jumlah Qty & Total tiap tabel dihitung ulang dari checkbox yang DICENTANG saja
        // (data-jmlh & data-subtotal sudah termasuk hargaSetelahDisc kalau produknya ada disc).
        function recalcAllTables() {
            document.querySelectorAll('table[data-cart-table]').forEach(function (table) {
                let qty = 0;
                let total = 0;
                table.querySelectorAll('.item-checkbox:checked').forEach(function (cb) {
                    qty   += parseInt(cb.dataset.jmlh || '0', 10);
                    total += parseFloat(cb.dataset.subtotal || '0');
                });
                let qtyEl   = table.querySelector('.js-qty');
                let totalEl = table.querySelector('.js-total');
                if (qtyEl)   qtyEl.textContent   = qty;
                if (totalEl) totalEl.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            });
        }

        function checkAll(box)
        {
            let checkboxes = document.getElementsByTagName('input');

            if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
                for (let i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = true;
                    }
                }
            } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
                for (let i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = false;
                    }
                }
            }
            recalcAllTables();
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.item-checkbox').forEach(function (cb) {
                cb.addEventListener('change', recalcAllTables);
            });
            recalcAllTables();
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <!-- END SCRIPT -->
</body>
</html>
