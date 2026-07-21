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
$row1 = mysqli_fetch_assoc($sql1);

$sql2 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-02-28' AS DATE); ");
$row2 = mysqli_fetch_assoc($sql2);

$sql3 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-03-31' AS DATE); ");
$row3 = mysqli_fetch_assoc($sql3);

$sql4 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-04-30' AS DATE); ");
$row4 = mysqli_fetch_assoc($sql4);

$sql5 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-05-31' AS DATE); ");
$row5 = mysqli_fetch_assoc($sql5);

$sql6 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-06-30' AS DATE); ");
$row6 = mysqli_fetch_assoc($sql6);

$sql7 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-07-31' AS DATE); ");
$row7 = mysqli_fetch_assoc($sql7);

$sql8 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-08-31' AS DATE); ");
$row8 = mysqli_fetch_assoc($sql8);

$sql9 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-09-30' AS DATE); ");
$row9 = mysqli_fetch_assoc($sql9);

$sql10 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-10-31' AS DATE); ");
$row10 = mysqli_fetch_assoc($sql10);

$sql11 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-11-30' AS DATE); ");
$row11 = mysqli_fetch_assoc($sql11);

$sql12 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM admin_mitra WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-12-31' AS DATE); ");
$row12 = mysqli_fetch_assoc($sql12);

// AGEN ==========================================================================================================================================

$asql1 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-01-31' AS DATE); ");
$arow1 = mysqli_fetch_assoc($asql1);

$asql2 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-02-28' AS DATE); ");
$arow2 = mysqli_fetch_assoc($asql2);

$asql3 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-03-31' AS DATE); ");
$arow3 = mysqli_fetch_assoc($asql3);

$asql4 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-04-30' AS DATE); ");
$arow4 = mysqli_fetch_assoc($asql4);

$asql5 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-05-31' AS DATE); ");
$arow5 = mysqli_fetch_assoc($asql5);

$asql6 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-06-30' AS DATE); ");
$arow6 = mysqli_fetch_assoc($asql6);

$asql7 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-07-31' AS DATE); ");
$arow7 = mysqli_fetch_assoc($asql7);

$asql8 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-08-31' AS DATE); ");
$arow8 = mysqli_fetch_assoc($asql8);

$asql9 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-09-30' AS DATE); ");
$arow9 = mysqli_fetch_assoc($asql9);

$asql10 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-10-31' AS DATE); ");
$arow10 = mysqli_fetch_assoc($asql10);

$asql11 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-11-30' AS DATE); ");
$arow11 = mysqli_fetch_assoc($asql11);

$asql12 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitraagen WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-12-31' AS DATE); ");
$arow12 = mysqli_fetch_assoc($asql12);

// RESELLER ==========================================================================================================================================
$rsql1 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-01-31' AS DATE); ");
$rrow1 = mysqli_fetch_assoc($rsql1);

$rsql2 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-02-28' AS DATE); ");
$rrow2 = mysqli_fetch_assoc($rsql2);

$rsql3 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-03-31' AS DATE); ");
$rrow3 = mysqli_fetch_assoc($rsql3);

$rsql4 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-04-30' AS DATE); ");
$rrow4 = mysqli_fetch_assoc($rsql4);

$rsql5 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-05-31' AS DATE); ");
$rrow5 = mysqli_fetch_assoc($rsql5);

$rsql6 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-06-30' AS DATE); ");
$rrow6 = mysqli_fetch_assoc($rsql6);

$rsql7 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-07-31' AS DATE); ");
$rrow7 = mysqli_fetch_assoc($rsql7);

$rsql8 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-08-31' AS DATE); ");
$rrow8 = mysqli_fetch_assoc($rsql8);

$rsql9 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-09-30' AS DATE); ");
$rrow9 = mysqli_fetch_assoc($rsql9);

$rsql10 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-10-31' AS DATE); ");
$rrow10 = mysqli_fetch_assoc($rsql10);

$rsql11 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-11-30' AS DATE); ");
$rrow11 = mysqli_fetch_assoc($rsql11);

$rsql12 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitrareseller WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-12-31' AS DATE); ");
$rrow12 = mysqli_fetch_assoc($rsql12);

// MARKETER ==========================================================================================================================================

$msql1 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-01-31' AS DATE); ");
$mrow1 = mysqli_fetch_assoc($msql1);

$msql2 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-02-28' AS DATE); ");
$mrow2 = mysqli_fetch_assoc($msql2);

$msql3 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-03-31' AS DATE); ");
$mrow3 = mysqli_fetch_assoc($msql3);

$msql4 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-04-30' AS DATE); ");
$mrow4 = mysqli_fetch_assoc($msql4);

$msql5 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-05-31' AS DATE); ");
$mrow5 = mysqli_fetch_assoc($msql5);

$msql6 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-06-30' AS DATE); ");
$mrow6 = mysqli_fetch_assoc($msql6);

$msql7 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-07-31' AS DATE); ");
$mrow7 = mysqli_fetch_assoc($msql7);

$msql8 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-08-31' AS DATE); ");
$mrow8 = mysqli_fetch_assoc($msql8);

$msql9 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-09-30' AS DATE); ");
$mrow9 = mysqli_fetch_assoc($msql9);

$msql10 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-10-31' AS DATE); ");
$mrow10 = mysqli_fetch_assoc($msql10);

$msql11 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-11-30' AS DATE); ");
$mrow11 = mysqli_fetch_assoc($msql11);

$msql12 = mysqli_query($koneksi, "SELECT count(*) as jumlah FROM mitramarketer WHERE tgl_daftar BETWEEN CAST('2021-01-01' AS DATE) AND CAST('2022-12-31' AS DATE); ");
$mrow12 = mysqli_fetch_assoc($msql12);

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
    data: [<?= $row1['jumlah']; ?>, <?= $row2['jumlah']; ?>, <?= $row3['jumlah']; ?>, <?= $row4['jumlah']; ?>, 
            <?= $row5['jumlah']; ?>,<?= $row6['jumlah']; ?>,<?= $row7['jumlah']; ?>,<?= $row8['jumlah']; ?>,<?= $row9['jumlah']; ?>,<?= $row10['jumlah']; ?>,<?= $row11['jumlah']; ?>,<?= $row12['jumlah']; ?>]
  },
  {
    label: 'Agen',
    backgroundColor: 'rgb(0, 204, 0)',
    borderColor: 'rgb(0, 204, 0)',
    data: [<?= $arow1['jumlah']; ?>, <?= $arow2['jumlah']; ?>, <?= $arow3['jumlah']; ?>, <?= $arow4['jumlah']; ?>, 
            <?= $arow5['jumlah']; ?>,<?= $arow6['jumlah']; ?>,<?= $arow7['jumlah']; ?>,<?= $arow8['jumlah']; ?>,<?= $arow9['jumlah']; ?>,<?= $arow10['jumlah']; ?>,<?= $arow11['jumlah']; ?>,<?= $arow12['jumlah']; ?>]
  },
  {
    label: 'Reseller',
    backgroundColor: 'rgb(247, 253, 4)',
    borderColor: 'rgb(247, 253, 4)',
    data: [<?= $rrow1['jumlah']; ?>, <?= $rrow2['jumlah']; ?>, <?= $rrow3['jumlah']; ?>, <?= $rrow4['jumlah']; ?>, 
            <?= $rrow5['jumlah']; ?>,<?= $rrow6['jumlah']; ?>,<?= $rrow7['jumlah']; ?>,<?= $rrow8['jumlah']; ?>,<?= $rrow9['jumlah']; ?>,<?= $rrow10['jumlah']; ?>,<?= $rrow11['jumlah']; ?>,<?= $rrow12['jumlah']; ?>]
  },
  {
    label: 'Marketer',
    backgroundColor: 'rgb(255, 51, 51)',
    borderColor: 'rgb(255, 51, 51)',
    data: [<?= $mrow1['jumlah']; ?>, <?= $mrow2['jumlah']; ?>, <?= $mrow3['jumlah']; ?>, <?= $mrow4['jumlah']; ?>, 
            <?= $mrow5['jumlah']; ?>,<?= $mrow6['jumlah']; ?>,<?= $mrow7['jumlah']; ?>,<?= $mrow8['jumlah']; ?>,<?= $mrow9['jumlah']; ?>,<?= $mrow10['jumlah']; ?>,<?= $mrow11['jumlah']; ?>,<?= $mrow12['jumlah']; ?>]
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
    document.getElementById('myChart2'),
    config
  );
</script>