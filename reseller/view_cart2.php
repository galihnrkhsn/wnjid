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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
    crossorigin="anonymous">
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
                        <?php echo $_SESSION['message']; ?>
                    </div>
                </div>
            </div>
        <?php
            unset($_SESSION['message']);
            }
        ?>
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#home">Ready Stok Reguler</a></li>
            <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#get">Ready Stok Voal</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#free">Ready Stok Buy 1 Get 1</a></li> -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#sale">Sale Bergo</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="home" class="tab-pane fade show active">
                <br>
                <form method="POST" action="save_cart2.php">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <th><input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <?php
                                include "koneksi.php";
                                $idmitrareseller    = $_SESSION["idmitrareseller"];
                                $total              = 0;
                                $berat              = 0;
                                $qty                = 0;

                                $sql = "SELECT *, keranjang.status as statusnya 
                                        FROM keranjang 
                                        INNER JOIN produk ON keranjang.idproduk=produk.idproduk 
                                        WHERE keranjang.idreseller = '$idmitrareseller'
                                        AND keranjang.jmlh > 0
                                        AND (produk.idkategori < 50)
                                        AND produk.jenis NOT LIKE '%Sale%'
                                        ";
                                $query = $koneksi->query($sql);
                                while($row = $query->fetch_assoc()) {
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?php echo $row['idproduk']; ?>">
                                <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?php echo $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitrareseller" value="<?php echo $idmitrareseller; ?>">
                                <input type="hidden" name="stock[]" value="<?php echo $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?php echo $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?php echo $row['subtotal']; ?>">
                                <input type="hidden" name="jenis" value="D">
                                <td style="text-align: right;">
                                    <?php 
                                    $disable = "block";
                                    if ($row['statusnya']=="Expired") {
                                        $disable = "none";
                                    }
                                    ?>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?php echo $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> | 
                                        <div class="badge bg-danger text-white rounded-pill"><?php echo $row['statusnya']; ?></div>
                                    <?php endif ?>
                                    <?php if ($row['statusnya']=="Active"): ?>
                                        <input type="checkbox" name="idkeranjang[]" value="<?php echo $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?php echo $row['namaproduk']; ?></td>
                                <td><?php $coret=number_format($row['hargacoret'],2); if($row['hargacoret']<>0){ echo "<span style='text-decoration: line-through'>Rp. $coret </span>"; } ?><br>Rp. <?php echo number_format($row['harga'], 2); ?></td>
                                <?php
                                    $max=$row['stock'];
                                    $max1=$max+1;
                                ?>
                                <td>
                                    <?php if ($row['statusnya']=="Expired"): ?>
                                        <?php echo $row['jmlh']; ?>
                                        <br>
                                     <?php endif ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?php echo $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?php echo $row['stock']; ?>                                    
                                </td>
                                <?php $subtotal=number_format($row['subtotal'], 2); ?>
                                <td><?php echo $subtotal  ?></td>
                                <?php $total +=$row['subtotal']; 
                                       $berat += $row['berat'] ?>
                            </tr>
                            <? $qty+=$row['jmlh']; } ?>
                            <td colspan="4" align="right"><b>Jumlah Qty</b></td>
                                <td><b><?php echo $qty; ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right"><b>Total</b></td>
                                <td><b><?php echo number_format($total,2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?php echo $berat; ?>">
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
            <div id="sale" class="tab-pane mt-4">
                <form method="POST" action="save_cart3.php">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <th><input type="checkbox" id="pilihsemua" onchange="checkAll(this)"/></th>
                            <th>Nama</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            <?php
                                include "koneksi.php";
                                $idmitrareseller    = $_SESSION["idmitrareseller"];
                                $total = 0;
                                $berat = 0;
                                $qty = 0;

                                $sql = "SELECT *, keranjang.status as statusnya 
                                        FROM keranjang 
                                        INNER JOIN produk ON keranjang.idproduk=produk.idproduk 
                                        WHERE keranjang.idreseller = '$idmitrareseller'
                                        AND keranjang.jmlh > 0
                                        AND (produk.idkategori < 50)
                                        AND produk.jenis LIKE '%Sale%'";
                                $query = $koneksi->query($sql);
                                while($row = $query->fetch_assoc()) {
                                    $idproduk = $row['idproduk'];
                            ?>
                            <tr>
                                <input type="hidden" name="idprodukubah[]" value="<?php echo $row['idproduk']; ?>">
                                <input type="hidden" name="idkeranjangubah[]" value="<?php echo $row['idkeranjang']; ?>">
                                <input type="hidden" name="idmitrareseller" value="<?php echo $idmitrareseller; ?>">
                                <input type="hidden" name="harga[]" value="<?php echo $row['harga']; ?>">
                                <input type="hidden" name="stock[]" value="<?php echo $row['stock']; ?>">
                                <input type="hidden" name="jmlh[]" value="<?php echo $row['jmlh']; ?>">
                                <input type="hidden" name="subtotal[]" value="<?php echo $row['subtotal']; ?>">
                                <input type="hidden" name="jenis" value="D">
                                <td style="text-align: right;">
                                    <?php 
                                        $disable = "block";
                                        if ($row['statusnya']=="Expired") {
                                            $disable = "none";
                                        }
                                    ?>
                                    <?php if ($row['statusnya'] == "Expired") : ?>
                                        <div class="badge bg-warning text-white rounded-pill"><a href="hapus_expired.php?idkeranjang=<?php echo $row['idkeranjang']; ?>" class="link text-white" onclick="return confirm('Yakin Akan Menghapus Pesanan Keranjang?');">Hapus</a></div> | 
                                        <div class="badge bg-danger text-white rounded-pill"><?php echo $row['statusnya']; ?></div>
                                    <?php endif ?>
                                    <?php if ($row['statusnya'] == "Active") : ?>
                                        <input type="checkbox" name="idkeranjang[]" value="<?php echo $row['idkeranjang']; ?>"/>
                                    <?php endif ?>
                                </td>
                                <td><?php echo $row['namaproduk']; ?></td>
                                <td>
                                    <?php $coret = number_format($row['hargacoret'],2); if($row['hargacoret']<>0){ echo "<span style='text-decoration: line-through'>Rp. $coret </span>"; } ?><br>Rp. <?php echo number_format($row['harga'], 2); ?></td>
                                    <?php
                                        $max=$row['stock'];
                                        $max1=$max+1;
                                    ?>
                                <td>
                                    <?php if ($row['statusnya'] == "Expired") : ?>
                                        <?php echo $row['jmlh']; ?>
                                        <br>
                                     <?php endif; ?>
                                    <input type="number" min="0" class="form-control" style="display: <?= $disable; ?>" value="<?php echo $row['jmlh']; ?>" name="jmlhbaru[]">Ready Stock : <?php echo $row['stock']; ?>                                    
                                </td>
                                <?php
                                    if ($row['jenis'] === "Sale") {
                                        $subtotal = $row['jmlh'] * 100000 / 4;
                                        $harga_awal = number_format($row['subtotal'], 2);
                                    } else {
                                        $subtotal = number_format($row['subtotal'], 2);
                                    }
                                ?>
                                <td><del class="text-muted"><?= $harga_awal ?></del> <p><?= number_format($subtotal, 2) ?></p></td>
                                <?php
                                    if ($row['jenis'] === "Sale") {
                                        $total += $subtotal;
                                    } else {
                                        $total += $row['subtotal'];
                                    }
                                    $berat += $row['berat'];
                                ?>
                            </tr>
                            <? $qty+=$row['jmlh']; } ?>
                            <td colspan="4" align="right"><b>Jumlah Qty</b></td>
                                <td><b><?php echo $qty; ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right"><b>Total</b></td>
                                <td><b><?php echo number_format($total,2); ?></b></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="berat" value="<?php echo $berat; ?>">
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
        </div>
    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <? include 'menubawah.php'; ?>
    <!-- FOOTER END -->

    <!-- PHP -->
    <? include "settingdatatables.php"; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- SCRIPT END -->
</body>
</html>