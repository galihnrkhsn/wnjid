<?php
  session_start();
  include 'koneksi.php'; 
  if(!isset($_SESSION["mitraagen"])){
    echo "<script>alert('anda harus login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    header('location:login.php');
    exit();
  }

  $invoice = $_GET['invoice'];
  $idadmin=$_SESSION["mitraagen"]["idmitrareseller"];
  $query = "SELECT poproduk.idpoproduk,
                  poproduk.namapo, pomitra.tgl, pomitra.waktu, pomitra.status
            FROM poproduk
            INNER JOIN pomitra ON poproduk.idpoproduk=pomitra.idpoproduk 
            WHERE pomitra.invoice='$invoice'
          ";
  $sqlpo = mysqli_query($koneksi, $query);  
  $datapo = mysqli_fetch_array($sqlpo);

  $idpoproduk = $datapo['idpoproduk'];

  $querypengiriman = "SELECT podropship.namapengirim, podropship.tlppengirim,
                            podropship.namapenerima, podropship.tlppenerima,
                            podropship.alamatpenerima, podropship.ekspedisi,
                            podropship.layanan, podropship.ongkir,
                            podropship.dropship, tb_ro_provinces.province_name,
                            tb_ro_cities.city_name, tb_ro_subdistricts.subdistrict_name
                        FROM podropship 
                        LEFT JOIN tb_ro_provinces ON podropship.provinsi = tb_ro_provinces.province_id
                        LEFT JOIN tb_ro_cities ON podropship.kota = tb_ro_cities.city_id
                        LEFT JOIN tb_ro_subdistricts ON podropship.kecamatan = tb_ro_subdistricts.subdistrict_id
                        WHERE podropship.invoice='$invoice'
                      ";
  $sqlpengiriman = mysqli_query($koneksi, $querypengiriman);  
  $datapengiriman = mysqli_fetch_array($sqlpengiriman);

  $querybukapo = "SELECT bukapo.idbpo, bukapo.jenis_mitra, bukapo.jenis_po,
                        bukapo.idpoproduk, bukapo.tgl, bukapo.tgl_dropship,
                        bukapo.tgl_bayar, bukapo.status, poproduk.namapo 
                  FROM bukapo INNER JOIN poproduk ON bukapo.idpoproduk = poproduk.idpoproduk
                  WHERE poproduk.idpoproduk = '$idpoproduk'
                  AND (bukapo.jenis_mitra = 'Semua Mitra' or bukapo.jenis_mitra = 'Distributor')
                ";
  $sqlbukapo = mysqli_query($koneksi, $querybukapo);  
  $databukapo = mysqli_fetch_array($sqlbukapo);
  $sqldp = mysqli_query($koneksi, "SELECT jmlhtransfer, jenis
                          FROM popembayaran
                          WHERE invoice = '$invoice'
                        ");
  while ($datadp = mysqli_fetch_array($sqldp)) {
    $payment += $datadp['jmlhtransfer'];
    $sisa = $payment - $subtotal;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WNJ | <?= $_SESSION["mitraagen"]["namaagen"] ?></title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  <style>
    .navbaru {
      background: #eee center center;
      margin: auto;
      text-align: center;
      overflow: hidden;
    }

    .navbaru p {  
      padding: 12px 0;
      font-size: 20px;
      color: #0f0f0a;
      text-align: center;
    }

    .navbaru i {
      padding: 15px 0;
      font-size: 23px;
      color: #0f0f0a;
      text-align: center;
    }

    .navbaru2 {
      margin: auto;
      text-align: center;
      overflow: hidden;
    }
  </style>
</head>
<body>

  <div class="row fixed-top navbaru py-2">
    <div class="col-2">
      <a href="listpreorder.php">
        <i class="bi bi-chevron-left"></i>
      </a>
    </div>
    <div class="col-8">
      <h4>PRE ORDER</h4>
    </div>
    <div class="col-2"></div>
  </div>

  <div class="container" style="margin: 4rem auto; font-size: .9rem">
    <div class="row">
      <div class="col-sm-12 text-center">
        <h5 class="text-uppercase"><?= $datapo['namapo']; ?></h5>
        <h6>Inv. #<?= $invoice; ?></h6>
        <hr />
      </div>

      <div class="col-sm-6 mb-3">
        <p class="fw-bold">Info Pesanan</p>
        <hr width="20%" />
        <table border="0" style="font-size: 0.85rem">
          <tr>
            <th width="70">Tanggal</th>
            <td width="20">:</td>
            <td><?= $datapo['tgl']; ?></td>
          </tr>
          <tr>
            <th>Status</th>
            <td>:</td>
            <td><?= $datapo['status']; ?></td>
          </tr>
        </table>
      </div>

      <div class="col-sm-6 mb-3">
        <p class="fw-bold">Info Pengiriman</p>
        <hr width="20%" />
        <table border="0" style="font-size: 0.85rem">
          <tr>
            <th width="150">Pengirim</th>
            <td width="20">:</td>
            <td><?= $datapengiriman['namapengirim']; ?> ( <?= $datapengiriman['tlppengirim']; ?> )</td>
          </tr>
          <tr>
            <th>Keluarga / Penerima</th>
            <td>:</td>
            <td><?= $datapengiriman['namapenerima']; ?> ( <?= $datapengiriman['tlppenerima']  ?> )</td>
          </tr>
          <tr>
            <th>Alamat</th>
            <td>:</td>
            <td>
              <span>
                <?= $datapengiriman['alamatpenerima']; ?>,
                <?= $datapengiriman['subdistrict_name']; ?>,
                <?= $datapengiriman['city_name']; ?>.
                <?= $datapengiriman['province_name']; ?>.
              </span>
            </td>
          </tr>
          <tr>
            <th>Ekspedisi</th>
            <td>:</td>
            <td><?= strtoupper($datapengiriman['ekspedisi']); ?> <?= strtoupper($datapengiriman['layanan']); ?></td>
          </tr>
        </table>
      </div>

      <div class="col-sm-12">
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Info Invoice</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Progres</button>
          </li>
        </ul>

        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>QTY</th>
                    <th>Total</th>
                  </tr>
                </thead>

                <?php
                  $no = 1;
                  $sql = mysqli_query($koneksi, "SELECT podetail.*, pomitra.*
                                                  FROM pomitra INNER JOIN podetail
                                                  ON podetail.idpodetail = pomitra.idpodetail
                                                  WHERE pomitra.invoice = '$invoice'
                                                  AND pomitra.jumlah > 0
                                                "
                                      );
                  while($data = mysqli_fetch_array($sql)) {
                ?>
                <tbody>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $data['variant'] ?></td>
                    <td>Rp. <?= number_format($data['harga']) ?></td>
                    <td><?= $data['jumlah'] ?></td>
                    <td>Rp. <?= number_format($data['harga'] * $data['jumlah']) ?></td>
                  </tr>
                </tbody>
                <?php 
                    $sum += $data['jumlah'];
                    $total += $data['jumlah'] * $data['harga'];
                  } 
                ?>
              </table>
            </div>

            <div class="col-sm-12 d-flex justify-content-end">
              <div class="table-resposive">
                <table border="0">
                  <tr>
                    <th width="150">Total QTY</th>
                    <td width="20">:</td>
                    <td><?= $sum ?></td>
                  </tr>
                  <tr>
                    <th>Jumlah</th>
                    <td>:</td>
                    <td>Rp. <?= number_format($total) ?></td>
                  </tr>

                  <?php
                    $persen = 15;
                    $diskon = 15/100*$total;
                    $ongkir = $datapengiriman['ongkir'];
                    $dropship = $datapengiriman['dropship'];
                    $subtotal = $total + $dropship + $ongkir - $diskon; 
                    $dp1 = $subtotal * 50/100;
                    $dp2 = $subtotal * 50/100;
                  
                    if ($idpoproduk == 153 or $idpoproduk == 259) {
                      $dp1 = $subtotal - 100000;
                      $dp2 = $dp1 * 30/100;
                      $dp3 = $dp1 * 25/100;
                      $dp4 = $dp1 * 25/100;
                      $dp5 = $dp1 * 20/100;
                    }
                  ?>

                  <tr>
                    <th>Ongkir</th>
                    <td>:</td>
                    <td>Rp. <?= number_format($ongkir) ?></td>
                  </tr>
                  <tr>
                    <th>Dropship</th>
                    <td>:</td>
                    <td>Rp. <?= number_format($dropship) ?></td>
                  </tr>
                  <tr>
                    <th>Diskon DB <?= $persen ?>%</th>
                    <td>:</td>
                    <td>- Rp. <?= number_format($diskon) ?></td>
                  </tr>
                  <tr>
                    <th>Total Bayar</th>
                    <td>:</td>
                    <td>Rp. <?= number_format($subtotal) ?></td>
                  </tr>
                </table>

                <hr />
                <table>
                  <?php if($subtotal < 100000) : ?>
                    <tr>
                      <th width="150">Payment 1</th>
                      <td width="20">:</td>
                      <td>
                        Rp. <?= number_format($subtotal); ?>
                      </td>
                    </tr>
                  <?php else : ?>
                    <tr>
                      <th width="150">Payment 1</th>
                      <td width="20">:</td>
                      <td>
                        <?php if ($idpoproduk == 259) : ?>
                          Rp. <?= number_format(100000); ?>
                        <?php else : ?>
                          Rp. <?= number_format($dp1); ?>
                        <?php endif; ?>
                      </td>
                    </tr>

                    <tr>
                      <th width="150">Payment 2</th>
                      <td width="20">:</td>
                      <td>
                        <?php if ($idpoproduk == 259 and $total < 100000 ) : ?>
                          Rp. <?= number_format($subtotal); ?>
                        <?php else : ?>
                          Rp. <?= number_format($dp2); ?>
                        <?php endif; ?>
                      </td>
                    </tr>

                    <tr>
                      <th width="150">Payment 3</th>
                      <td width="20">:</td>
                      <td>
                        <?php if ($idpoproduk == 259 and $total < 100000 ) : ?>
                          Rp. <?= number_format($subtotal); ?>
                        <?php else : ?>
                          Rp. <?= number_format($dp3); ?>
                        <?php endif; ?>
                      </td>
                    </tr>

                    <tr>
                      <th width="150">Payment 4</th>
                      <td width="20">:</td>
                      <td>
                        <?php if ($idpoproduk == 259 and $total < 100000 ) : ?>
                          Rp. <?= number_format($subtotal); ?>
                        <?php else : ?>
                          Rp. <?= number_format($dp4); ?>
                        <?php endif; ?>
                      </td>
                    </tr>

                    <tr>
                      <th width="150">Payment 5</th>
                      <td width="20">:</td>
                      <td>
                        <?php if ($idpoproduk == 259 and $total < 100000 ) : ?>
                          Rp. <?= number_format($subtotal); ?>
                        <?php else : ?>
                          Rp. <?= number_format($dp5); ?>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endif; ?>

                  <tr>
                    <td colspan="3">
                      <hr />
                    </td>
                  </tr>

                  <tr>
                    <th>Konfirmasi Payment</th>
                    <td>:</td>
                    <td>Rp. <?= number_format($payment) ?></td>
                  </tr>
                  <tr>
                    <th>Sisa Taighan</th>
                    <td>:</td>
                    <td>
                      <?php 
                        $sisa = $payment - $subtotal;
                        if($sisa > 0) : 
                      ?>
                        +
                      <?php endif; ?>
                      <?= number_format($sisa); ?>
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            <div class="col-sm-12 text-center">
              <?php
                $harinya = '+2 Days';
                date_default_timezone_set('Asia/Jakarta');
                $tgl11 = $datapo['tgl'];
                $tgl22 = date('Y-m-d', strtotime($harinya, strtotime($tgl11)));
                $sekarang =  date('Y-m-d');
                $tgl3 = new DateTime($tgl11);
                $tgl4 = new DateTime($sekarang);
                $jarak = $tgl4->diff($tgl3);
                $jaraknya = $jarak->d;

                if ($datapo['status'] == "Belum Acc DB") :
              ?>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                  Ubah Variant
                </button>
                <a href="formpo_konin?invoice=<?= $invoice ?>" class="btn btn-primary btn-sm">Tambah Variant</a>

                <!-- Modal Start -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h3 class="modal-title fs-5" id="staticBackdropLabel">Ubah QTY Variant</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form method="post" enctype="multipart/form-data">
                        <div class="modal-body text-start">
                          <?php
                            $sql_qty = mysqli_query($koneksi,
                                                    "SELECT podetail.*, pomitra.*
                                                      FROM pomitra INNER JOIN podetail
                                                      ON podetail.idpodetail = pomitra.idpodetail
                                                      WHERE pomitra.invoice = '$invoice'
                                                      AND pomitra.jumlah > 0
                                                    "
                                                  );
                            while ($data_qty = mysqli_fetch_array($sql_qty)) {
                          ?>
                            <div class="mb-3">
                              <label for="<?= $data_qty['variant'] ?>" class="form-label"><?= $data_qty['variant'] ?></label>
                              <input type="hidden" name="idpomitra[]" class="form-control form-control-sm" value="<?= $data_qty['idpomitra'] ?>">
                              <input type="number" name="jumlah[]" class="form-control form-control-sm" id="<?= $data_qty['variant'] ?>" min="0" value="<?= $data_qty['jumlah'] ?>">
                            </div>
                          <?php } ?>
                        </div>
                        
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-primary btn-sm" name="simpan_qty">Simpan</button>
                          <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- Modal End -->
              <?php endif; ?>

              <?php
                if(isset($_POST["simpan_qty"])) {
                  $idpomitra = $_POST["idpomitra"];
                  $jumlah = $_POST["jumlah"];
                  $custom = count($idpomitra);

                  for ($x = 0; $x < $custom; $x++) {
                    $sql = $koneksi->query("UPDATE pomitra SET jumlah='$jumlah[$x]' WHERE idpomitra = '$idpomitra[$x]'");
                  }

                  if ($sql) {
                    echo "<script>alert('Data Berhasil Diupdate!');</script>";
                    echo "<script>location='datapokonin2?invoice=$invoice';</script>";
                  }else{
                    echo "<script>alert('Data Gagal Diupdate!');</script>";
                    echo "<script>location='datapokonin2?invoice=$invoice';</script>";
                  }
                }
              ?>

              <?php if ($datapo['status'] == "Approve DB" or $datapo['status'] == "Belum Acc DB"  or $datapo['status'] == "Sudah Confirm Payment 1" or $datapo['status'] == "Sudah Confirm Payment 2" or $datapo['status'] == "Sudah Confirm Payment 3" or $datapo['status'] == "Sudah Confirm Payment 4") : ?>
                <div class="alert alert-danger text-center mt-3" role="alert">
                  <?php if ($datapengiriman['province_name'] == "") : ?>
                    <p style="margin-bottom: .5rem; font-size: .75rem">Silahkan Isi Alamat. <br /> Segera Lakukan Pembayaran Payment 1.</p>
                  <?php endif; ?>

                  <div id="linkmiki">
                    <?php if ($datapengiriman['province_name'] == "") : ?>
                      <a href="formdropship_kolibri?id=<?= $invoice ?>" class="btn btn-primary btn-sm">Isi Alamat</a>
                    <?php else : ?>
                      <?php if ($datapo['status'] == "Approve DB") : ?>
                        <p class="mb-1">Silahkan Lakukan Payment 1 <br /> Rp. <?= number_format(100000); ?></p>

                        <a href="popembayaran.php?invoice=<?= $invoice ?>&total=<?= $idpoproduk == 259 ? 100000 : $dp1 ?>&bayar=<?= $subtotal ?>&idpo=<?= $idpoproduk ?>&jenis=Payment 1" class="btn btn-primary btn-sm">Konfirmasi Pembayaran</a>
                      <?php elseif ($datapo['status'] == "Sudah Confirm Payment 1") : ?>
                        <p class="mb-1">
                          Rp. <?= number_format($dp2) ?>
                          <br />
                          <b style="font-size: .75rem">Minimal Rp. <?= number_format(100000) ?></b>
                        </p>

                        <a href="popembayaran.php?invoice=<?= $invoice ?>&total=<?= $dp2 ?>&idpo=<?= $idpoproduk ?>&jenis=Payment 2" class="btn btn-primary btn-sm">Konfirmasi Pembayaran</a>
                      <?php elseif($datapo['status'] == "Sudah Confirm Payment 2") : ?>
                        <p class="mb-1">
                          Rp. <?= number_format($dp3) ?>
                          <br />
                          <b style="font-size: .75rem">Minimal Rp. <?= number_format(100000) ?></b>
                        </p>

                        <a href="popembayaran.php?invoice=<?= $invoice ?>&total=<?= $dp3 ?>&idpo=<?= $idpoproduk ?>&jenis=Payment 3" class="btn btn-primary btn-sm">Konfirmasi Pembayaran</a>
                      <?php elseif($datapo['status'] == "Sudah Confirm Payment 3") : ?>
                        <p class="mb-1">
                          Rp. <?= number_format($dp4) ?>
                          <br />
                          <b style="font-size: .75rem">Minimal Rp. <?= number_format(100000) ?></b>
                        </p>

                        <a href="popembayaran.php?invoice=<?= $invoice ?>&total=<?= $dp4 ?>&idpo=<?= $idpoproduk ?>&jenis=Payment 4" class="btn btn-primary btn-sm">Konfirmasi Pembayaran</a>
                      <?php elseif($datapo['status'] == "Sudah Confirm Payment 4") : ?>
                        <p class="mb-1">
                          Rp. <?= number_format($dp5) ?>
                          <br />
                          <b style="font-size: .75rem">Minimal Rp. <?= number_format(100000) ?></b>
                        </p>

                        <a href="popembayaran.php?invoice=<?= $invoice ?>&total=<?= $dp5 ?>&idpo=<?= $idpoproduk ?>&jenis=Payment 5" class="btn btn-primary btn-sm">Pelunasan</a>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                </div>
              <?php elseif($datapo['status'] == "Sudah Confirm Payment 5") : ?>
                <div class="alert alert-success mt-3" role="alert">
                  <p class="fw-bold" style="margin-bottom: 0;">Pembayaran Sudah Lunas</p>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <?php
              $sqlprogres = mysqli_query($koneksi,
                          "SELECT 
                            SUM(surat_jalan_po.progres) AS progres,
                            SUM(pomitra.jumlah) AS jumlah
                            FROM surat_jalan_po
                            INNER JOIN pomitra ON pomitra.idpomitra = surat_jalan_po.idpomitra
                            INNER JOIN podetail ON podetail.idpodetail = surat_jalan_po.idpodetail
                            WHERE pomitra.idmitrareseller = '$idadmin' 
                            AND pomitra.idpoproduk = '$idpoproduk' 
                            AND pomitra.jumlah > 0 
                            AND surat_jalan_po.invoice = '$invoice' 
                            AND (surat_jalan_po.status = 'Checker' OR surat_jalan_po.status = 'Ambil Barang')
                            GROUP BY pomitra.idpodetail
                          ");
              $dataprogres = mysqli_fetch_array($sqlprogres);

              $sqlinvoice = mysqli_query($koneksi, "SELECT
                                                    SUM(pomitra.jumlah) as jumlah
                                                    FROM pomitra
                                                    WHERE pomitra.idmitrareseller = '$idadmin'
                                                    AND pomitra.idpoproduk = '$idpoproduk'
                                                    AND pomitra.jumlah > 0
              ");
              $datainvoice = mysqli_fetch_array($sqlinvoice);
            ?>

            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Barang</th>
                  <th>QTY</th>
                  <th>Progres</th>
                  <th>Sisa</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $no = 1;
                  $sql = mysqli_query($koneksi, "SELECT
                                      podetail.variant,
                                      podetail.harga,
                                      pomitra.*,
                                      SUM(surat_jalan_po.progres) AS progres
                                      FROM pomitra
                                      INNER JOIN podetail ON podetail.idpodetail = pomitra.idpodetail
                                      LEFT JOIN surat_jalan_po ON pomitra.idpomitra = surat_jalan_po.idpomitra
                                      WHERE pomitra.idmitrareseller = '$idadmin'
                                      AND pomitra.idpoproduk = '$idpoproduk'
                                      AND pomitra.invoice = '$invoice'
                                      AND pomitra.jumlah > 0
                                      GROUP BY pomitra.idpodetail
                                      ORDER BY podetail.variant ASC
                                    ");
                  while ($data = mysqli_fetch_array($sql)) {
                    $id = $data['idpomitra'];
                    $sisa = $data['jumlah'] - $data['progres'];
                ?>
                  <tr>
                    <td class="align-middle"><?= $no++; ?></td>
                    <td class="align-middle"><?= $data['variant'] ?></td>
                    <td class="align-middle"><?= $data['jumlah'] ?></td>
                    <td class="align-middle">
                      <?php if($data['progres'] == "") : ?>
                        0
                      <?php else : ?>
                        <?= $data['progres'] ?>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle"><?= $sisa ?></td>
                    <td class="align-middle">
                      <?php if($sisa == 0) : ?>
                        <span class="badge text-bg-success">Selesai</span>
                      <?php else : ?>
                        <span class="badge text-bg-warning">Progres</span>
                      <?php endif; ?>
                    </td>
                  </tr>   
                <?php
                    $sum_progres += $data['progres'];
                    $sum_jumlah += $data['jumlah'];
                    $sum_sisa += $sisa;
                    $jumlah_progres = $jumlah_progres + $totalnya;
                  }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="2">Total</th>
                  <td><?= $sum_jumlah ?></td>
                  <td><?= $sum_progres ?></td>
                  <td colspan="2"><?= $sum_sisa ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>