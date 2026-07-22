<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    include 'koneksi.php';
    include 'assets/components/Sessions/sesDistri.php';

    $idadmin = $_SESSION["idadmin"];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .aText {
            color: #c21b1b;
        }
        .po-highlight {
            margin-bottom: 1.5rem;
        }
        .po-highlight .btn {
            white-space: normal;
        }
        .po-highlight .countdown {
            color: #6c757d;
            font-size: .9rem;
            margin-top: .25rem;
        }
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }
    </style>
<title>Pre Order | Wanoja</title>
    <!-- Load File bootstrap.min.css yang ada difolder css -->
  </head>
  <body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- END NAVBAR -->

    <div class="container mt-5">
        <?php
            $stmtOpenPo = $koneksi->prepare("SELECT
                                                bukapo.idbpo,
                                                bukapo.jenis_mitra,
                                                bukapo.jenis_po,
                                                bukapo.idpoproduk,
                                                bukapo.tgl,
                                                bukapo.tgl_dropship,
                                                bukapo.status,
                                                poproduk.namapo
                                            FROM bukapo
                                            INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk
                                            WHERE bukapo.status = 'PUBLISH'
                                            AND (bukapo.jenis_mitra = 'Semua Mitra' OR bukapo.jenis_mitra = 'Distributor')
                                            ORDER BY bukapo.tgl DESC");
            $stmtOpenPo->execute();
            $dataproduk = $stmtOpenPo->get_result();

            $jenisPoRoutes  = [
                'PO dengan Stok'                => 'formpostok',
                'PO tanpa Stok'                  => 'formpoku',
                'PO Custom Tab'                  => 'formpo_tab',
                'PO Custom Tab Stok'             => 'formpo_tabstok',
                'PO Custom Tab Stok Max'         => 'formpo_tabstokmax',
                'PO Konin'                       => 'pokonin.php',
                'PO Kolibri'                     => 'pokolibri',
                'PO Miki Custom'                 => 'formpomikicustom',
                'PO Miki Polos'                  => 'formpomikipolos',
                'PO Brooch Custom'               => 'formpobrooch_custom',
                'PO Bagi Rata'                   => 'formbagirata',
                'PO Hampers'                     => 'formpo_thr',
                'PO Karakter Stok'               => 'formpo_karakterstok',
                'PO Custom Inner'                => 'formpoinner_custom',
                'PO custom Rocela'               => 'formporocela',
                'PO custom Goura'                => 'formpogoura',
                'PO custom Bundling'             => 'formpocustomgabungan',
                'PO Bundling 2'                  => 'formpobundling2.php',
                'PO Tab tanpa stok'              => 'formpobundling2.php',
                'PO Bundling Custom'             => 'formpobundling.php',
                'PO Ducula Stok'                 => 'formpoducula.php',
                'PO Tazmahal'                    => 'formpotazmahal.php',
                'PO Bundling 5'                  => 'formpobundling5.php',
                'PO Set'                         => 'formpobundlingset.php',
                'PO Mandiri'                     => 'formpo_mandiri.php',
                'PO Custom'                      => 'pocustom.php',
                'PO Custom Inisial'              => 'formpocustom.php',
                'PO Custom Template'             => 'formpocustomtemplate.php'
            ];

            $produkSpecial  = [
                '328' => 'pocustom.php',
                '331' => 'pocustom.php',
                '260' => 'formpocustomlegging',
                '261' => 'formpocustomlegging2',
                '269' => 'formpomatari',
                '289' => 'formposongkok.php',
                '299' => 'formpovoal_custom.php',
                '301' => 'formpovoal_custom.php',
                '315' => 'formpocustom.php',
                '506' => 'formpocustom.php',
                '307' => 'formpoinner.php',
                '314' => 'formpoinner.php',
                '317' => 'formpoinner.php',
                '325' => 'formpoinner.php',
                '339' => 'formpoinner.php',
                '335' => 'formpoinner2.php'
            ];

            $hasOpenPo = $dataproduk->num_rows > 0;
        ?>
        <?php if (!$hasOpenPo): ?>
            <div class="empty-state">
                <i class="fa-solid fa-calendar-xmark fa-2x mb-2"></i><br>
                Belum ada PO yang sedang dibuka saat ini.
            </div>
        <?php endif; ?>
        <?php while ($row = $dataproduk->fetch_assoc()): ?>
            <div class="po-highlight text-center">
                <?php
                    $id     = $row['idpoproduk'];
                    $link   = '#';
                    $text   = $row['namapo'];
                    if (isset($jenisPoRoutes[$row['jenis_po']])) {
                        $link = $jenisPoRoutes[$row['jenis_po']] . '?id=' . $id;
                    } elseif (isset($produkSpecial[$id])) {
                        $link = $produkSpecial[$id] . '?id=' . $id;
                        if (in_array($id, ['260', '261'])) $link .= '&idadmin=' . urlencode($idadmin);
                    }
                ?>
                <a type="submit" class="btn btn-primary btn-lg" name="cari" id="linkmiki<?= $row['idbpo'] ?>" style="color:white;" href="<?= htmlspecialchars($link) ?>"><?= htmlspecialchars($text) ?></a>
                <p class="countdown" id="demomiki<?= $row['idbpo'] ?>"></p>
            </div>
            <script>
                const countdownTarget<?= (int) $row['idbpo'] ?> = new Date("<?= addslashes($row['tgl']) ?> 23:59:00").getTime();
                const interval<?= (int) $row['idbpo'] ?> = setInterval(() => {
                const now = new Date().getTime();
                const distance = countdownTarget<?= (int) $row['idbpo'] ?> - now;
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("demomiki<?= (int) $row['idbpo'] ?>").innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;

                if (distance < 0) {
                    clearInterval(interval<?= (int) $row['idbpo'] ?>);
                    document.getElementById("demomiki<?= (int) $row['idbpo'] ?>").innerHTML = "Link PO tidak tersedia";
                    document.getElementById("linkmiki<?= (int) $row['idbpo'] ?>").style.display = "none";
                }
                }, 1000);
            </script>
        <?php endwhile; ?>
        <div class="text-center mt-5 mb-5" style="color: var(--color1)">
            <h3>PO Regular</h3>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th class="col-4 text-center">Tanggal</th>
                        <th class="col-6 text-center">Nama PO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmtRegular = $koneksi->prepare("SELECT
                                                    x.tgl,
                                                    pr.namapo,
                                                    pr.idpoproduk,
                                                    pr.jenis,
                                                    bo.jenis_po
                                                FROM (
                                                    SELECT
                                                        p.idpoproduk,
                                                        MAX(p.tgl) AS tgl
                                                    FROM pomitra p
                                                    LEFT JOIN mitraagen ma ON ma.idmitraagen = p.idmitraagen
                                                    LEFT JOIN mitrareseller mr ON mr.idmitrareseller = p.idmitrareseller
                                                    LEFT JOIN mitramarketer mm ON mm.idmitramarketer = p.idmitramarketer
                                                    WHERE
                                                        p.idmitra = ?
                                                        OR ma.idadmin = ?
                                                        OR mr.idadmin = ?
                                                        OR mm.idadmin = ?
                                                    GROUP BY p.idpoproduk
                                                ) x
                                                JOIN poproduk pr ON pr.idpoproduk = x.idpoproduk
                                                LEFT JOIN bukapo bo ON bo.idpoproduk = pr.idpoproduk
                                                WHERE
                                                    pr.status = 'open'
                                                    AND pr.tipe = 'Normal'
                                                    AND pr.idpoproduk > 233
                                                ORDER BY x.tgl DESC");
                        $stmtRegular->bind_param('ssss', $idadmin, $idadmin, $idadmin, $idadmin);
                        $stmtRegular->execute();
                        $sqlRegular = $stmtRegular->get_result();

                        if ($sqlRegular->num_rows === 0):
                    ?>
                    <tr>
                        <td colspan="2" class="text-center text-muted">Belum ada PO reguler yang tersedia.</td>
                    </tr>
                    <?php
                        endif;
                        while ($data = $sqlRegular->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="text-center"><?= htmlspecialchars($data['tgl']) ?></td>
                        <td class="text-center">
                            <?php if ($data['jenis'] == 'Kolibri'): ?>
                                <a href="detailkolibri?id=<?= (int) $data['idpoproduk'] ?>"><?= htmlspecialchars($data['namapo']) ?></a>
                            <?php elseif ($data['idpoproduk'] == '259' || $data['idpoproduk'] == '267'): ?>
                                <a href="detailkonin?id=<?= (int) $data['idpoproduk'] ?>"><?= htmlspecialchars($data['namapo']) ?></a>
                            <?php elseif ($data['jenis_po'] === 'PO Konin') : ?>
                                <a href="detailpokonin.php?id=<?= (int) $data['idpoproduk'] ?>" class="aText"><?= htmlspecialchars($data['namapo']) ?></a>
                            <?php elseif ($data['jenis_po'] === 'PO Custom Inisial') : ?>
                                <a href="detailpo.php?id=<?= (int) $data['idpoproduk'] ?>" class="aText"><?= htmlspecialchars($data['namapo']) ?></a>
                            <?php else: ?>
                                <a href="detailpo?id=<?= (int) $data['idpoproduk'] ?>" class="aText"><?= htmlspecialchars($data['namapo']) ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>
    <br><br><br><br>
    <?php include "menubawah.php" ?>
</body>
</html>
