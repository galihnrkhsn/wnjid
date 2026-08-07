<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
    session_start();
    include 'koneksi.php';


    $idmitra    = $_SESSION['idadmin'];
    $sql        = "SELECT  k.*,                     
                        v.variant, v.size, v.jenis, v.berat, v.hargacoret, v.stock,
                        p.namaproduk
                    FROM    keranjang k
                    JOIN    variants  v ON k.idproduk  = v.id
                    JOIN    products  p ON v.idproducts = p.id
                    WHERE   k.idmitra = ?
                    AND   k.jmlh    > 0
                    AND   p.idkategori < 50
                    AND   k.status   IN ('Active','Expired')
                ";
    $stmt       = $koneksi->prepare($sql);
    $stmt->bind_param('i', $idmitra);
    $stmt->execute();
    $items      = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $tabs = [
        'home'  => [],
        'sale'  => [],
        'flash' => []
    ];

    foreach ($items as $row) {
        $key = 'home';

        $jenis = $row['jenis'] ?? ''; // jika null, ganti jadi string kosong

        if (stripos($jenis, 'Sale') !== false) {
            $key = 'sale';
        }

        if (stripos($jenis, 'Flash') !== false) {
            $key = 'flash';
        }

        $tabs[$key][] = $row;
    }


    function renderCartTable($rows, $idTab) {
        $total = $qty = $berat = 0;
        ?>
        <form method="POST" action="save_cart.php" class="mb-4">
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th><input type="checkbox" id="pilihsemua-<?= $idTab;?>" onclick="checkAll(this,'<?= $idTab;?>')"></th>
                        <th>Nama</th><th>Variant</th><th>Harga</th>
                        <th>Jumlah</th><th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
        <?php foreach ($rows as $r): ?>
            <?php
                $total += $r['subtotal'];
                $qty   += $r['jmlh'];
                $berat += $r['berat'];
                $disabled = $r['status']=='Expired';
            ?>
            <tr>
                <td class="text-center">
                    <?php if($disabled): ?>
                        <span class="badge badge-danger">Expired</span>
                    <?php else: ?>
                        <input type="checkbox" name="idkeranjang[]" value="<?= $r['idkeranjang']; ?>" class="check-<?= $idTab;?>">
                    <?php endif; ?>
                </td>
                <td><?= $r['namaproduk']; ?></td>
                <td><?= "{$r['variant']} - Sz {$r['size']}"; ?></td>
                <td>
                    <?php if($r['hargacoret']): ?>
                        <del class="text-muted">Rp <?= number_format($r['hargacoret'],0,',','.'); ?></del><br>
                    <?php endif; ?>
                    Rp <?= number_format($r['harga'],0,',','.'); ?>
                </td>
                <td>
                    <?php if($disabled): echo $r['jmlh']; else: ?>
                        <input type="number" min="0" max="<?= $r['stock']; ?>" name="jmlhbaru[]" value="<?= $r['jmlh']; ?>" class="form-control form-control-sm">
                    <?php endif; ?>
                    <small class="text-muted">Stok: <?= $r['stock']; ?></small>
                </td>
                <td>Rp <?= number_format($r['subtotal'],0,',','.'); ?></td>
                <input type="hidden" name="idprodukubah[]" value="<?= $r['idproduk']; ?>">
                <input type="hidden" name="harga[]" value="<?= $r['harga']; ?>">
                <input type="hidden" name="idkeranjangubah[]" value="<?= $r['idkeranjang']; ?>">
                <input type="hidden" name="idmitra" value="<?= $_SESSION['idadmin']; ?>">
                <input type="hidden" name="jmlh[]" value="<?= $r['jmlh']; ?>">
                <input type="hidden" name="subtotal[]" value="<?= $r['subtotal']; ?>">
            </tr>
        <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan="5" class="text-right">Qty</td>
                        <td><?= $qty; ?></td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td colspan="5" class="text-right">Total</td>
                        <td>Rp <?= number_format($total,0,',','.'); ?></td>
                    </tr>
                </tfoot>
            </table>
            <input type="hidden" name="berat" value="<?= $berat; ?>">
            <div class="d-flex justify-content-between">
                <a href="store4.php" class="btn btn-warning"><i class="fas fa-chevron-left"></i></a>
                <div>
                    <button type="submit" name="save" class="btn btn-success">Ubah Stock</button>
                    <?php if($total>0): ?>
                        <button type="submit" name="checkout" class="btn btn-primary">
                            Checkout <i class="fas fa-chevron-right"></i>
                        </button>
                    <?php else: ?>
                        <a href="store4.php" class="btn btn-primary">Lanjut Belanja Yuk!</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <?php
    }
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Font (Opsional) -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">


</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <div class="container mt-3">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">Ready Stok</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sale">Sale Bergo</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#flash">Cuci Gudang</a></li>
        </ul>

        <div class="tab-content pt-3">
            <div id="home" class="tab-pane fade show active"><?php renderCartTable($tabs['home'], 'home'); ?></div>
            <div id="sale" class="tab-pane fade"><?php renderCartTable($tabs['sale'], 'sale'); ?></div>
            <div id="flash" class="tab-pane fade"><?php renderCartTable($tabs['flash'], 'flash'); ?></div>
        </div>
    </div>

    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <?php include 'menubawah.php'; ?>
    <!-- FOOTER END -->
    <script>
        function checkAll(master, tab){
            const checks = document.querySelectorAll('.check-' + tab);
            checks.forEach(c => c.checked = master.checked);
        }
    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle (JS + Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
