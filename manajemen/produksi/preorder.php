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
<?php include '../assets/components/Navbar/navbar.php'; ?>
    <div class="container mt-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-secondary"><?= $namapo; ?></h2>
            <a href="../index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Button Section -->
        <!-- <div class="mb-4">
            <a href="totalbarangvendor.php?id=<?= $idpoproduk ?>" class="btn btn-primary">
                Totalan Barang Vendor
            </a>
        </div> -->

        <!-- Table Section -->
        <div class="table-responsive">
            <form method="post" action="print_sjk.php" target="_blank">
                <table class="table table-bordered table-striped" id="tb_vendor">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Variant</th>
                            <th>Jumlah</th>
                            <th>Masuk</th>
                            <th>Kurang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;

                        // Query 1: Ambil data dari `pokategori`, `podetail`, dan `pomitra`
                        $query1 = "SELECT podetail.*, 
                                        COALESCE(SUM(pomitra.jumlah), 0) AS jumlah_pomitra, 
                                        REPLACE(RIGHT(podetail.variant, 2), ' ', '') AS ukuran
                                FROM pokategori
                                INNER JOIN podetail ON pokategori.idpo = podetail.idpo
                                LEFT JOIN pomitra ON podetail.idpodetail = pomitra.idpodetail 
                                    AND pomitra.idpoproduk = pokategori.idpoproduk
                                WHERE pokategori.idpoproduk = '$idpoproduk'
                                GROUP BY podetail.idpodetail, podetail.variant
                                ORDER BY podetail.idpodetail";

                        $result1 = $koneksi->query($query1);
                        $data1 = [];
                        while ($row = $result1->fetch_assoc()) {
                            $data1[$row['idpodetail']] = $row;
                        }

                        // Query 2: Ambil data dari `sjk`
                        $query2 = "SELECT idpodetail, COALESCE(SUM(jumlah), 0) AS jumlah_sjk
                                FROM sjk
                                WHERE idpoproduk = '$idpoproduk'
                                GROUP BY idpodetail";

                        $result2 = $koneksi->query($query2);
                        while ($row = $result2->fetch_assoc()) {
                            if (isset($data1[$row['idpodetail']])) {
                                $data1[$row['idpodetail']]['jumlah_sjk'] = $row['jumlah_sjk'];
                            } else {
                                $data1[$row['idpodetail']] = ['jumlah_sjk' => $row['jumlah_sjk']];
                            }
                        }

                        // Iterasi hasil akhir untuk ditampilkan di tabel HTML
                        foreach ($data1 as $idpodetail => $detail) {
                            $jumlahPomitra = $detail['jumlah_pomitra'] ?? 0;
                            $jumlahSjk = $detail['jumlah_sjk'] ?? 0;
                            $kurang = $jumlahPomitra - $jumlahSjk; // Logika untuk kolom Kurang
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($detail['variant'] ?? ''); ?></td>
                                <td><?= htmlspecialchars($jumlahPomitra); ?></td>
                                <td><?= htmlspecialchars($jumlahSjk); ?></td>
                                <td><?= htmlspecialchars($kurang); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>


                <!-- Action Buttons -->
                <!-- <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary me-2" name="but_print">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button type="submit" class="btn btn-danger" name="but_hapus" onclick="return confirm('Yakin Akan Menghapus SJK?');">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div> -->
            </form>
        </div>
    </div>

    <?php include '../template/footer.php'; ?>

    <!-- JavaScript for Checkbox -->
    <script>
        $(document).ready(function() {
            // Check/Uncheck All
            $('#checkAll').change(function() {
                $('input[name="update[]"]').prop('checked', this.checked);
            });

            // Individual Checkbox Click
            $('input[name="update[]"]').click(function() {
                $('#checkAll').prop(
                    'checked',
                    $('input[name="update[]"]:not(:checked)').length === 0
                );
            });
        });
    </script>
