<?php
    session_start();

    include 'koneksi.php'; 
    include 'assets/components/Sessions/sesReseller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseller | Wanoja</title>
</head>
<body>
    <!-- NAVBAR -->
    <?php include 'assets/components/Navbar/navbar2.php'; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-2">
        <?php
            // Info message
            if(isset($_SESSION['message'])) {
        ?>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="alert alert-info text-center">
                        <?= $_SESSION['message']; ?>
                    </div>
                </div>
            </div>
        <?php
            unset($_SESSION['message']);
            }
        ?>
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#home">Ready Stok Reguler</a></li>
            <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#get">Sale Bergo</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#short">Bundling Short</a></li> -->
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#flash">Cuci Gudang</a></li>
        </ul>
        <div class="tab-content">
            <div id="home" class="tab-pane fade show active">
                <br>
                <form method="POST" action="save_cart.php">
                    <table class="table table-bordered table-striped">
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
                                $idmitra    = $_SESSION["idmitrareseller"];
                                $total      = 0;
                                $berat      = 0;
                                $qty        = 0;

                                $sql        = "SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                                INNER JOIN variants ON keranjang.idproduk = variants.id
                                                INNER JOIN products ON variants.idproducts = products.id
                                                WHERE keranjang.idreseller = '$idmitra'
                                                AND keranjang.status = 'Active'
                                                AND (variants.jenis IS NULL 
                                                OR (variants.jenis NOT LIKE 'Sale' 
                                                    AND variants.jenis NOT LIKE '%Bundling Short%'
                                                    AND variants.jenis NOT LIKE '%Flash%')
                                        )";
                                $query      = $koneksi->query($sql);
                                while($row = $query->fetch_assoc()) {
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?= $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= $row['harga']; ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= $idmitra; ?>">
                                <input type="hidden" name="stock[]" value="<?= $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $row['subtotal']; ?>">
                                <td style="text-align: right;">
                                    <?php 
                                    $disable = "block";
                                    if ( $row['statusnya']=="Expired" ) {
                                        $disable = "none";
                                    }
                                    ?>
                                    <?php if ( $row['statusnya'] == "Expired" ): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?= $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> | 
                                        <div class="badge bg-danger text-white rounded-pill"><?= $row['statusnya']; ?></div>
                                    <?php endif ?>
                                    <?php if ( $row['statusnya'] == "Active" ): ?>
                                        <input type="checkbox" name="idkeranjang[]" value="<?= $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= $row['namaproduk'];?></td>
                                <td><?= $row['variant'] . ' - Sz ' . $row['size']; ?></td>
                                <td>
                                    <?php $coret = number_format($row['hargacoret'],2); 
                                        if($row['hargacoret'] <> 0){
                                            echo "<span style='text-decoration: line-through'>Rp. $coret </span>"; 
                                        }
                                    ?>
                                    <br>
                                    Rp. <?= number_format($row['harga'], 2); ?>
                                </td>
                                <?php
                                    $max    = $row['stock'];
                                    $max1   = $max + 1;
                                ?>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?= $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?= $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?= $row['stock']; ?>                                    
                                </td>
                                <?php $subtotal = number_format($row['subtotal'], 2); ?>
                                <td><?= $subtotal  ?></td>
                                <?php 
                                    $total += $row['subtotal']; 
                                    $berat += $row['berat'] 
                                ?>
                            </tr>
                            <? $qty+=$row['jmlh']; } ?>
                            <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><?= $qty; ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><?= number_format($total,2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= $berat; ?>">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2"><a href="store2.php" class="btn btn-warning btn-s"><i class="fa-solid fa-chevron-left"></i></a></div>
                        <div class="p-2"></div>
                        <div class="p-2"><button type="submit" class="btn btn-success btn-s" name="save">Ubah Stock</button></div>
                    </div>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Klik Ubah Stock sebelum checkout
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php
                        if($total==0) {
                            echo "<a href='store2.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
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
                    <table class="table table-bordered table-striped">
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
                                $idmitra    = $_SESSION["idmitrareseller"];
                                $total      = 0;
                                $berat      = 0;
                                $qty        = 0;

                                $sql = "SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        INNER JOIN products ON variants.idproducts = products.id
                                        WHERE keranjang.idreseller = '$idmitra'
                                        AND keranjang.jmlh > 0
                                        AND (products.idkategori < 50)
                                        AND keranjang.status = 'Active'
                                        AND variants.jenis LIKE '%Sale%'";
                                $query = $koneksi->query($sql);
                                while($row = $query->fetch_assoc()) {
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?= $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= $row['harga']; ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= $idmitra; ?>">
                                <input type="hidden" name="stock[]" value="<?= $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $row['subtotal']; ?>">
                                <td style="text-align: right;">
                                    <?php 
                                        $disable = "block";
                                        if ( $row['statusnya']=="Expired" ) {
                                            $disable = "none";
                                        }
                                    ?>
                                    <?php if ( $row['statusnya'] == "Expired" ): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?= $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> | 
                                        <div class="badge bg-danger text-white rounded-pill"><?= $row['statusnya']; ?></div>
                                    <?php endif ?>
                                    <?php if ( $row['statusnya'] == "Active" ): ?>
                                        <input type="checkbox" name="idkeranjang[]" value="<?= $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= $row['namaproduk'];?></td>
                                <td><?= $row['variant'] . ' - Sz ' . $row['size']; ?></td>
                                <td>
                                    <?php $coret = number_format($row['hargacoret'],2); 
                                        if($row['hargacoret'] <> 0){
                                            echo "<span style='text-decoration: line-through'>Rp. $coret </span>"; 
                                        }
                                    ?>
                                    <br>
                                    Rp. <?= number_format($row['harga'], 2); ?>
                                </td>
                                <?php
                                    $max    = $row['stock'];
                                    $max1   = $max + 1;
                                ?>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?= $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?= $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?= $row['stock']; ?>                                    
                                </td>
                                <?php $subtotal = number_format($row['subtotal'], 2); ?>
                                <td><?= $subtotal  ?></td>
                                <?php 
                                    $total += $row['subtotal']; 
                                    $berat += $row['berat'] 
                                ?>
                            </tr>
                            <?php
                                $qty+=$row['jmlh'];
                                }
                            ?>
                            <tr>
                                <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><?= $qty; ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><?= number_format($total,2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= $berat; ?>">
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
                        if($total == 0) {
                            echo "<a href='store4.php' class='btn btn-primary btn-lg'>Lanjut Belanja Yuk!</a>";
                        } else {
                            echo "<button type='submit' class='btn btn-primary btn-lg' name='checkout'> CHECKOUT <i class='fa-solid fa-chevron-right'></i></button>";
                        }
                        ?>
                    </div>
                </form>
            </div>
            <div id="short" class="tab-pane">
                <br>
                <form method="POST" action="save_cart.php">
                    <table class="table table-bordered table-striped">
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
                                $idmitra    = $_SESSION["idmitrareseller"];
                                $total      = 0;
                                $berat      = 0;
                                $qty        = 0;

                                $sql = "SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        INNER JOIN products ON variants.idproducts = products.id
                                        WHERE keranjang.idreseller = '$idmitra'
                                        AND keranjang.jmlh > 0
                                        AND (products.idkategori < 50)
                                        AND keranjang.status = 'Active'
                                        AND variants.jenis LIKE '%Bundling Short%'";
                                $query = $koneksi->query($sql);
                                while($row = $query->fetch_assoc()) {
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?= $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= $row['harga']; ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= $idmitra; ?>">
                                <input type="hidden" name="stock[]" value="<?= $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $row['subtotal']; ?>">
                                <td style="text-align: right;">
                                    <?php 
                                        $disable = "block";
                                        if ( $row['statusnya']=="Expired" ) {
                                            $disable = "none";
                                        }
                                    ?>
                                    <?php if ( $row['statusnya'] == "Expired" ): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?= $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> | 
                                        <div class="badge bg-danger text-white rounded-pill"><?= $row['statusnya']; ?></div>
                                    <?php endif ?>
                                    <?php if ( $row['statusnya'] == "Active" ): ?>
                                        <input type="checkbox" name="idkeranjang[]" value="<?= $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= $row['namaproduk'];?></td>
                                <td><?= $row['variant'] . ' - Sz ' . $row['size']; ?></td>
                                <td>
                                    <?php $coret = number_format($row['hargacoret'],2); 
                                        if($row['hargacoret'] <> 0){
                                            echo "<span style='text-decoration: line-through'>Rp. $coret </span>"; 
                                        }
                                    ?>
                                    <br>
                                    Rp. <?= number_format($row['harga'], 2); ?>
                                </td>
                                <?php
                                    $max    = $row['stock'];
                                    $max1   = $max + 1;
                                ?>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?= $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?= $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?= $row['stock']; ?>                                    
                                </td>
                                <?php $subtotal = number_format($row['subtotal'], 2); ?>
                                <td><?= $subtotal  ?></td>
                                <?php 
                                    $total += $row['subtotal']; 
                                    $berat += $row['berat'] 
                                ?>
                            </tr>
                            <?php
                                $qty+=$row['jmlh'];
                                }
                            ?>
                            <tr>
                                <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><?= $qty; ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><?= number_format($total,2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= $berat; ?>">
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
                        if($total == 0) {
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
                    <table class="table table-bordered table-striped">
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
                                $idmitra    = $_SESSION["idmitrareseller"];
                                $total      = 0;
                                $berat      = 0;
                                $qty        = 0;

                                $sql = "SELECT keranjang.*, keranjang.status AS statusnya, variants.*, products.namaproduk FROM keranjang
                                        INNER JOIN variants ON keranjang.idproduk = variants.id
                                        INNER JOIN products ON variants.idproducts = products.id
                                        WHERE keranjang.idreseller = '$idmitra'
                                        AND keranjang.jmlh > 0
                                        AND (products.idkategori < 50)
                                        AND keranjang.status = 'Active'
                                        AND variants.jenis LIKE '%Flash%'";
                                $query = $koneksi->query($sql);
                                while($row = $query->fetch_assoc()) {
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?= $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?= $row['harga']; ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?= $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitra" value="<?= $idmitra; ?>">
                                <input type="hidden" name="stock[]" value="<?= $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?= $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?= $row['subtotal']; ?>">
                                <td style="text-align: right;">
                                    <?php 
                                        $disable = "block";
                                        if ( $row['statusnya']=="Expired" ) {
                                            $disable = "none";
                                        }
                                    ?>
                                    <?php if ( $row['statusnya'] == "Expired" ): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?= $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> | 
                                        <div class="badge bg-danger text-white rounded-pill"><?= $row['statusnya']; ?></div>
                                    <?php endif ?>
                                    <?php if ( $row['statusnya'] == "Active" ): ?>
                                        <input type="checkbox" name="idkeranjang[]" value="<?= $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?= $row['namaproduk'];?></td>
                                <td><?= $row['variant'] . ' - Sz ' . $row['size']; ?></td>
                                <td>
                                    <?php $coret = number_format($row['hargacoret'],2); 
                                        if($row['hargacoret'] <> 0){
                                            echo "<span style='text-decoration: line-through'>Rp. $coret </span>"; 
                                        }
                                    ?>
                                    <br>
                                    Rp. <?= number_format($row['harga'], 2); ?>
                                </td>
                                <?php
                                    $max    = $row['stock'];
                                    $max1   = $max + 1;
                                ?>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?= $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?= $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?= $row['stock']; ?>                                    
                                </td>
                                <?php $subtotal = number_format($row['subtotal'], 2); ?>
                                <td><?= $subtotal  ?></td>
                                <?php 
                                    $total += $row['subtotal']; 
                                    $berat += $row['berat'] 
                                ?>
                            </tr>
                            <?php
                                $qty+=$row['jmlh'];
                                }
                            ?>
                            <tr>
                                <td colspan="5" align="right"><b>Jumlah Qty</b></td>
                                <td><b><?= $qty; ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right"><b>Total</b></td>
                                <td><b><?= number_format($total,2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?= $berat; ?>">
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
                        if($total == 0) {
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

    <!-- PHP -->
    <?php include "settingdatatables.php"; ?>
    <!-- PHP END -->

    <!-- SCRIPT -->
    <script type="text/javascript">
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
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <!-- SCRIPT END -->

</body>
</html>