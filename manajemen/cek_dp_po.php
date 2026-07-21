<?php
session_start();
include 'koneksi.php'; 
include 'assets/components/Sessions/sesManage.php';
$namapo = $_GET['namapo'];
$idmanage = $_SESSION['idmanage'];
$tipe = $_SESSION['user_tipe'];

$queryManage = $koneksi->query("SELECT * FROM management WHERE id='$idmanage'");
$data = $queryManage->fetch_assoc();

$datapo = $koneksi->query("SELECT namapo FROM poproduk WHERE idpoproduk='$namapo'");
$tampilpo = $datapo->fetch_assoc(); 
echo $tampilpo['namapo'];
?>

<table class="table table-bordered" id="tb_dp">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Mitra</th>
            <th>Nama PO</th>
            <th>Bank Pengirim</th>
            <th>Rekening / Nama Pengirim</th>
            <th>Transfer DP</th>
            <th>Transfer Pelunasan</th>
            <th>Metode Pembayaran</th>
            <th>No Order</th>
            <th>Tanggal TF & Waktu</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $datapo = $koneksi->query("SELECT 
              MAX(pomitra.status) AS status,
              MAX(poproduk.namapo) AS namapo,
              popembayaran.invoice,
              MAX(popembayaran.bankpengirim) AS bankpengirim,
              MAX(popembayaran.rekeningpengirim) AS rekeningpengirim,
              MAX(popembayaran.jmlhtransfer) AS jmlhtransfer,
              MAX(popembayaran.jmlh_lunas) AS jmlh_lunas,
              MAX(popembayaran.metodebayar) AS metodebayar,
              MAX(popembayaran.tgl) AS tgl,
              MAX(popembayaran.waktu) AS waktu,
              popembayaran.idpembayaran 
          FROM 
              popembayaran 
          LEFT JOIN 
              pomitra ON popembayaran.invoice = pomitra.invoice 
          LEFT JOIN 
              admin_mitra ON pomitra.idmitra = admin_mitra.idadmin 
          INNER JOIN 
              poproduk ON popembayaran.idpoproduk = poproduk.idpoproduk 
          WHERE 
              pomitra.idpoproduk = '$namapo'
              AND pomitra.idmitra <> ''
          GROUP BY 
              popembayaran.invoice, 
              popembayaran.idpembayaran 
          ORDER BY 
              popembayaran.idpembayaran DESC
          LIMIT 0, 50000;
        ");

        while ($tampilkan = $datapo->fetch_assoc()) {
            $id = $tampilkan['idpembayaran'];
            $invoice1 = $tampilkan['invoice'];
            $datamitra = $koneksi->query("
                SELECT admin_mitra.namamitra, pomitra.invoice 
                FROM pomitra
                JOIN admin_mitra ON admin_mitra.idadmin = pomitra.idmitra
                WHERE pomitra.invoice = '$invoice1'
            ");
            $tampilprogres = $datamitra->fetch_assoc();                                   
            $namamitra = $tampilprogres['namamitra'];                             
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $namamitra; ?></td>
            <td><?php echo $tampilkan['namapo']; ?></td>
            <td><?php echo $tampilkan['bankpengirim']; ?></td>
            <td><?php echo $tampilkan['rekeningpengirim']; ?></td>
            <td><?php echo $tampilkan['jmlhtransfer']; ?></td>
            <td><?php echo $tampilkan['jmlh_lunas']; ?></td>
            <td><?php echo $tampilkan['metodebayar']; ?></td>
            <td>
                <a href="detailinvoice.php?invoice=<?php echo $tampilkan['invoice']; ?>">
                    <?php echo $tampilkan['invoice']; ?>
                </a>
            </td>
            <td>
                <?php echo $tampilkan['tgl']; ?><br>
                <?php echo $tampilkan['waktu']; ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tb_dp').DataTable({
            "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
        });
    });
</script>
