<?php 
    session_start();
    include '../../includes/db.php'; 
    include '../assets/components/Sessions/sesManage.php';
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
        echo "
            <script>alert('Anda harus login terlebih dahulu!');</script>
            <script>location='login-multi.php';</script>
        ";
        header("Location: login-multi.php");
        exit();
    }
    $id             = $_SESSION['user_id'];
    $role           = $_SESSION['user_level'];
    $idpoproduk     = $_GET['id'];
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h2 class="m-0 font-weight-bold text-secondary">Total Barang Masuk</h2>
    <h2>
        <a href="preorder.php?id=<?= $idpoproduk ?>" style="float: right;"><i class="fas fa-arrow-left fa-m "></i></a>
    </h2>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Variant</th>
                <th>Barang Datang</th>
                <th>Totalan PO</th>
                <th>Kekurangan</th>
            </tr>
        </thead>

        <tbody>
            <?php
                $no = 1;
                // if ($idpoproduk == 321) {
                //     $sql = $koneksi->query("SELECT podetail.idpodetail, podetail.variant,
                //                                     SUM(sjk.jumlah) AS total
                //                                 FROM poproduk
                //                                 INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                //                                 INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                //                                 INNER JOIN sjk ON sjk.idpodetail = podetail.idpodetail
                //                                 WHERE (poproduk.idpoproduk = '321' OR poproduk.idpoproduk = '320')
                //                                 GROUP BY podetail.variant
                //                                 ORDER BY podetail.idpodetail;
                //                             ");
                // } else {
                    $sql = $koneksi->query("SELECT podetail.idpodetail, podetail.variant,
                                                    SUM(sjk.jumlah) AS total
                                                FROM poproduk
                                                INNER JOIN pokategori ON pokategori.idpoproduk = poproduk.idpoproduk
                                                INNER JOIN podetail ON podetail.idpo = pokategori.idpo
                                                INNER JOIN sjk ON sjk.idpodetail = podetail.idpodetail
                                                WHERE poproduk.idpoproduk = $idpoproduk
                                                GROUP BY podetail.variant
                                                ORDER BY podetail.idpodetail
                                            ");
                // }
                while ($data = $sql->fetch_assoc()) {
                    $idpodetail = $data['idpodetail'];
                    $query = $koneksi->query("SELECT SUM(pomitra.jumlah) AS total_po FROM pomitra WHERE idpodetail = '$idpodetail'");
                    while ($datapo = $query->fetch_assoc()) {
                        $kurang = $data['total'] - $datapo['total_po'];
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['variant']; ?></td>
                    <td><?= $data['total']; ?></td>
                    <td><?= $datapo['total_po']; ?></td>
                    <td><?= $kurang; ?></td>
                </tr>
            <?php
                            $total_po += $datapo['total_po'];
                        }
                    $total_barang_datang += $data['total'];
                    if ($kurang < 0) {
                        $total_kurang += $kurang;
                    }
                }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total Keseluruhan Barang</th>
                <td><?= $total_barang_datang ?></td>
                <td><?= $total_po ?></td>
                <td><?= $total_kurang ?></td>
            </tr>
        </tfoot>
    </table>
</div>