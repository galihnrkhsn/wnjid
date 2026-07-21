<?php 
session_start();

include 'koneksi.php'; 


if(!isset($_SESSION["administrator"])){
  echo "<script>alert('anda harus login terlebih dahulu');</script>";
   echo "<script>location='login.php';</script>";
   header('location:login.php');
   exit();
}

$sql1 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-01-31' AS DATE); ");
$row11 = mysqli_fetch_assoc($sql1);

$sql2 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-02-28' AS DATE); ");
$row12 = mysqli_fetch_assoc($sql2);

$sql3 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-03-31' AS DATE); ");
$row13 = mysqli_fetch_assoc($sql3);

$sql4 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-04-30' AS DATE); ");
$row14 = mysqli_fetch_assoc($sql4);

$sql5 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-05-31' AS DATE); ");
$row15 = mysqli_fetch_assoc($sql5);

$sql6 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-06-30' AS DATE); ");
$row16 = mysqli_fetch_assoc($sql6);

$sql7 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-07-31' AS DATE); ");
$row17 = mysqli_fetch_assoc($sql7);

$sql8 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-08-31' AS DATE); ");
$row18 = mysqli_fetch_assoc($sql8);

$sql9 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-09-30' AS DATE); ");
$row19 = mysqli_fetch_assoc($sql9);

$sql10 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-10-31' AS DATE); ");
$row110 = mysqli_fetch_assoc($sql10);

$sql11 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-11-30' AS DATE); ");
$row111 = mysqli_fetch_assoc($sql11);

$sql12 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-12-31' AS DATE); ");
$row112 = mysqli_fetch_assoc($sql12);

// AGEN ==========================================================================================================================================

$asql1 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-01-31' AS DATE); ");
$arow11 = mysqli_fetch_assoc($asql1);

$asql2 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-02-28' AS DATE); ");
$arow12 = mysqli_fetch_assoc($asql2);

$asql3 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-03-31' AS DATE); ");
$arow13 = mysqli_fetch_assoc($asql3);

$asql4 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-04-30' AS DATE); ");
$arow14 = mysqli_fetch_assoc($asql4);

$asql5 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-05-31' AS DATE); ");
$arow15 = mysqli_fetch_assoc($asql5);

$asql6 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-06-30' AS DATE); ");
$arow16 = mysqli_fetch_assoc($asql6);

$asql7 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-07-31' AS DATE); ");
$arow17 = mysqli_fetch_assoc($asql7);

$asql8 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-08-31' AS DATE); ");
$arow18 = mysqli_fetch_assoc($asql8);

$asql9 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-09-30' AS DATE); ");
$arow19 = mysqli_fetch_assoc($asql9);

$asql10 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-10-31' AS DATE); ");
$arow110 = mysqli_fetch_assoc($asql10);

$asql11 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-11-30' AS DATE); ");
$arow111 = mysqli_fetch_assoc($asql11);

$asql12 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-12-31' AS DATE); ");
$arow112 = mysqli_fetch_assoc($asql12);

// RESELLER ==========================================================================================================================================
$rsql1 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-01-31' AS DATE); ");
$rrow11 = mysqli_fetch_assoc($rsql1);

$rsql2 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-02-28' AS DATE); ");
$rrow12 = mysqli_fetch_assoc($rsql2);

$rsql3 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-03-31' AS DATE); ");
$rrow13 = mysqli_fetch_assoc($rsql3);

$rsql4 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-04-30' AS DATE); ");
$rrow14 = mysqli_fetch_assoc($rsql4);

$rsql5 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-05-31' AS DATE); ");
$rrow15 = mysqli_fetch_assoc($rsql5);

$rsql6 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-06-30' AS DATE); ");
$rrow16 = mysqli_fetch_assoc($rsql6);

$rsql7 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-07-31' AS DATE); ");
$rrow17 = mysqli_fetch_assoc($rsql7);

$rsql8 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-08-31' AS DATE); ");
$rrow18 = mysqli_fetch_assoc($rsql8);

$rsql9 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-09-30' AS DATE); ");
$rrow19 = mysqli_fetch_assoc($rsql9);

$rsql10 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-10-31' AS DATE); ");
$rrow110 = mysqli_fetch_assoc($rsql10);

$rsql11 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-11-30' AS DATE); ");
$rrow111 = mysqli_fetch_assoc($rsql11);

$rsql12 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-12-31' AS DATE); ");
$rrow112 = mysqli_fetch_assoc($rsql12);

// MARKETER ==========================================================================================================================================

$msql1 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-01-31' AS DATE); ");
$mrow11 = mysqli_fetch_assoc($msql1);

$msql2 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-02-28' AS DATE); ");
$mrow12 = mysqli_fetch_assoc($msql2);

$msql3 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-03-31' AS DATE); ");
$mrow13 = mysqli_fetch_assoc($msql3);

$msql4 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-04-30' AS DATE); ");
$mrow14 = mysqli_fetch_assoc($msql4);

$msql5 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-05-31' AS DATE); ");
$mrow15 = mysqli_fetch_assoc($msql5);

$msql6 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-06-30' AS DATE); ");
$mrow16 = mysqli_fetch_assoc($msql6);

$msql7 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-07-31' AS DATE); ");
$mrow17 = mysqli_fetch_assoc($msql7);

$msql8 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-08-31' AS DATE); ");
$mrow18 = mysqli_fetch_assoc($msql8);

$msql9 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-09-30' AS DATE); ");
$mrow19 = mysqli_fetch_assoc($msql9);

$msql10 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-10-31' AS DATE); ");
$mrow110 = mysqli_fetch_assoc($msql10);

$msql11 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-11-30' AS DATE); ");
$mrow111 = mysqli_fetch_assoc($msql11);

$msql12 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-12-31' AS DATE); ");
$mrow112 = mysqli_fetch_assoc($msql12);

?>

<script>
// <block:setup:1>
const labels = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember'
];
const data = {
  labels: labels,
  datasets: [{
    label: 'Distributor',
    backgroundColor: 'rgb(0, 128, 255)',
    borderColor: 'rgb(0, 128, 255)',
    data: [<?= $row11['jumlah']; ?>, <?= $row12['jumlah']; ?>, <?= $row13['jumlah']; ?>, <?= $row14['jumlah']; ?>, 
            <?= $row15['jumlah']; ?>,<?= $row16['jumlah']; ?>,<?= $row17['jumlah']; ?>,<?= $row18['jumlah']; ?>,<?= $row19['jumlah']; ?>,<?= $row110['jumlah']; ?>,<?= $row111['jumlah']; ?>,<?= $row112['jumlah']; ?>]
  },
  {
    label: 'Agen',
    backgroundColor: 'rgb(0, 204, 0)',
    borderColor: 'rgb(0, 204, 0)',
    data: [<?= $arow11['jumlah']; ?>, <?= $arow12['jumlah']; ?>, <?= $arow13['jumlah']; ?>, <?= $arow14['jumlah']; ?>, 
            <?= $arow15['jumlah']; ?>,<?= $arow16['jumlah']; ?>,<?= $arow17['jumlah']; ?>,<?= $arow18['jumlah']; ?>,<?= $arow19['jumlah']; ?>,<?= $arow110['jumlah']; ?>,<?= $arow111['jumlah']; ?>,<?= $arow112['jumlah']; ?>]
  },
  {
    label: 'Reseller',
    backgroundColor: 'rgb(247, 253, 4)',
    borderColor: 'rgb(247, 253, 4)',
    data: [<?= $rrow11['jumlah']; ?>, <?= $rrow12['jumlah']; ?>, <?= $rrow13['jumlah']; ?>, <?= $rrow14['jumlah']; ?>, 
            <?= $rrow15['jumlah']; ?>,<?= $rrow16['jumlah']; ?>,<?= $rrow17['jumlah']; ?>,<?= $rrow18['jumlah']; ?>,<?= $rrow19['jumlah']; ?>,<?= $rrow110['jumlah']; ?>,<?= $rrow111['jumlah']; ?>,<?= $rrow112['jumlah']; ?>]
  },
  {
    label: 'Marketer',
    backgroundColor: 'rgb(255, 51, 51)',
    borderColor: 'rgb(255, 51, 51)',
    data: [<?= $mrow11['jumlah']; ?>, <?= $mrow12['jumlah']; ?>, <?= $mrow13['jumlah']; ?>, <?= $mrow14['jumlah']; ?>, 
            <?= $mrow15['jumlah']; ?>,<?= $mrow16['jumlah']; ?>,<?= $mrow17['jumlah']; ?>,<?= $mrow18['jumlah']; ?>,<?= $mrow19['jumlah']; ?>,<?= $mrow110['jumlah']; ?>,<?= $mrow111['jumlah']; ?>,<?= $mrow112['jumlah']; ?>]
  }]
};
// </block:setup>

// <block:config:0>
const config = {
  type: 'line',
  data,
  options: {}
};
// </block:config>

module.exports = {
  actions: [],
  config: config,
};
</script>
<script>
  // === include 'setup' then 'config' above ===

  var myChart = new Chart(
    document.getElementById('myChart22'),
    config
  );
</script>