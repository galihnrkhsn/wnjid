<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    
    include 'koneksi.php';
    if (!isset($_SESSION['logistik'])) {
        echo "
            <script>
                alert('Login terlebih dahulu')
                location='login.php'
            </script>
        ";
        exit();
    }

    $iduser             = $_SESSION['logistik']['id'];
    $tgl                = $_GET['tgl'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portal | Detail Resi</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="template/plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="template/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="template/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- JQVMap -->
        <link rel="stylesheet" href="template/plugins/jqvmap/jqvmap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="template/dist/css/adminlte.min.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="template/plugins/daterangepicker/daterangepicker.css">
        <!-- summernote -->
        <link rel="stylesheet" href="template/plugins/summernote/summernote-bs4.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <?php include 'template/component/navbar.php'?>

            <?php include 'template/component/sidebar.php' ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Detail Resi  / <?= $tgl; ?></h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="resi.php">Resi</a></li>
                                    <li class="breadcrumb-item active"><?= $tgl ?></li>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header">
                                <a href="export.php?f=<?= $tgl ?>" class="btn btn-success btn-xs"><i class="fas fa-file-excel"></i></a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form method="POST">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>Nama CS</th>
                                                <th>Penerima</th>
                                                <th>Ekspedisi</th>
                                                <th>No Resi</th>
                                                <th>Biaya Kirim</th>
                                                <th>Status Pengiriman</th>
                                                <th>Jenis Pengiriman</th>
                                                <th>Status Pembayaran</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sql    = $koneksi->query("SELECT *, logistik3.status AS statusLogistik FROM logistik3 
                                                                            LEFT JOIN t_user ON t_user.idlogistik = logistik3.idlogistik
                                                                            WHERE tgl = '$tgl' 
                                                                            ORDER BY logistik3.idlogistik");
                                                $no     = 1;
                                                while($data = $sql->fetch_assoc()) {
                                                    $eks    = $data['ekspedisi'] ?? '';
                                                    $ekspedisi = explode(" ", $eks);
                                                    $ekspedisi[0] = strtolower($ekspedisi[0]);
                                                    if ($data['ekspedisi'] == 'Wahana' || $data['ekspedisi'] == 'Wahana Ekspres') {
                                                        $data['ekspedisi'] = 'Wahana';
                                                    } else {
                                                        $data['ekspedisi'];
                                                    }
                                                    $jenis_pengiriman   = $data['jenis_pengiriman'];
                                                    $idLogistik         = $data['idlogistik'];
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="selected_ids[]" value="<?= $idLogistik ?>">
                                                        <input type="hidden" name="idadmin[<?= $idLogistik ?>]" value="<?= $data['idadmin']; ?>">
                                                        <input type="hidden" name="biayakirim[<?= $idLogistik ?>]" value="<?= $data['biayakirim'] ?>">
                                                        <input type="hidden" name="invoice[<?= $idLogistik ?>]" value="<?= $data['invoice'] ?>">
                                                        <input type="hidden" name="ekspedisi[<?= $idLogistik?>]" value="<?= $data['ekspedisi'] ?>">
                                                        <input type="hidden" name="pengiriman[<?= $idLogistik ?>]" value="<?= $jenis_pengiriman ?>">
                                                        <input type="hidden" name="detail_pengiriman[<?= $idLogistik ?>]" value="<?= $data['detail_pengiriman'] ?>">
                                                        <input type="hidden" name="penerima[<?= $idLogistik ?>]" value="<?= $data['penerima'] ?>">
                                                        <input type="hidden" name="jenis_mitra[<?= $idLogistik ?>]" value="<?= $data['jenis_mitra'] ?>">
                                                    </td>
                                                    <?php if ($data['jenis_mitra'] == 'WNJ') :?>
                                                        <td class="text-primary">
                                                            <?= $data['namacs'] ?>
                                                        </td>
                                                    <?php else : ?>
                                                        <td class="text-info">
                                                            <?= $data['namacs'] ?>
                                                        </td>
                                                    <?php endif; ?>
                                                    <td><?= $data['penerima'] ?></td>
                                                    <td>
                                                        <a href="ekspedisi.php?eks=<?= $ekspedisi[0] ?>&tgl=<?= $data['tgl'] ?>" class="text-capitalize"><?= $data['ekspedisi'] ?></a>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" class="form-control form-control-sm" value="<?= $idLogistik ?>" name="idlogistik[<?= $idLogistik ?>]" readonly>
                                                        <input type="text" class="form-control form-control-sm" value="<?= $data['noresi'] ?>" name="resi[<?= $idLogistik ?>]" placeholder="Masukan no resi...">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" value="<?= number_format($data['biayakirim']) ?>" value="0" min="0" name="ongkir[<?= $idLogistik ?>]">
                                                    </td>
                                                    <td>
                                                        <?php if ($data['statusLogistik'] == '' || $data['statusLogistik'] == NULL) :?>
                                                            <p class="text-red">Belum Terkirim</p>
                                                        <?php else : ?>
                                                            <p class="text-success"><?= $data['statusLogistik']; ?></p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($jenis_pengiriman == '' || $jenis_pengiriman == NULL) :?>
                                                            <p class="text-red">Belum ditentukan</p>
                                                        <?php else : ?>
                                                            <p class="text-success"><?= $jenis_pengiriman; ?></p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($data['status_pengiriman'] == '' || $data['status_pengiriman'] == NULL) :?>
                                                            <p class="text-red">Belum bayar</p>
                                                        <?php else : ?>
                                                            <p class="text-success"><?= $data['status_pengiriman']; ?></p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <!-- <button type="submit" class="btn btn-success btn-xs" name="update">Update</button> -->
                                                        <!-- BUTTON MODAL KETERANGAN -->
                                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal-keterangan-<?= $idLogistik ?>">
                                                            <i class="fas fa-ad"></i>
                                                        </button>
                                                        <!-- MODAL KETERANGAN -->
                                                        <div class="modal fade" id="modal-keterangan-<?= $idLogistik ?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Update Keterangan <?= $data['penerima'] ?></h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form method="post" enctype="multipart/form-data">
                                                                        <div class="modal-body">
                                                                            <input type="hidden" class="form-control form-control-sm" value="<?= $idLogistik ?>" name="idlogistik" required>
                                                                            <div class="form-group">
                                                                                <label for="keterangan" class="mb-1">Keterangan</label>
                                                                                <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" placeholder="Keterangan" rows="4"><?= $data['keterangan'] ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-start">
                                                                            <button type="submit" class="btn btn-sm btn-success" name="update_keterangan">Update Data</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                            <!-- /.modal-dialog -->
                                                        </div>
                                                        <!-- END MODAL KETERANGAN -->
                                                        <a href="form_edit_resi.php?id=<?= $idLogistik ?>" class="btn btn-xs btn-warning text-white"><i class="fas fa-edit"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="mt-2">
                                        <button type="submit" name="update" class="btn btn-info mb-3">Update</button>
                                        <button type="submit" name="print_excel" class="btn btn-success mb-3">Print</button>
                                        <br>
                                        <button type="submit" name="ubah_status" value="Bayar" class="btn btn-primary">Bayar</button>
                                        <button type="submit" name="ubah_status" value="COD" class="btn btn-secondary">COD</button>
                                        <button type="submit" name="ubah_status" value="Deposit" class="btn btn-success">Deposit</button>
                                        <button type="submit" name="ubah_status" value="Tanggung Pusat" class="btn btn-danger">Tanggung Pusat</button>
                                        <br>
                                        <button type="submit" name="terkirim" class="btn btn-warning mt-3" >Terkirim</button>
                                        <button type="submit" name="belum_terkirim" class="btn btn-dark mt-3">Belum Terkirim</button>
                                    </div>
                                </form>
                                <?php 
                                    if (isset($_POST['ubah_status']) && isset($_POST['selected_ids'])) {
                                        $today              = date('Y-m-d'); 
                                        $statusBaru         = $_POST['ubah_status']; // Bayar, COD, dll
                                        $selectedIDs        = $_POST['selected_ids'];
                                        $idadminList        = $_POST['idadmin'];
                                        $biayakirimList     = $_POST['biayakirim'];
                                        
                                        foreach ($selectedIDs as $id) {
                                            $idadmin        = $idadminList[$id];
                                            $biayakirim     = $biayakirimList[$id];

                                            $updateStatus   = $koneksi->query("UPDATE logistik3 SET status_pengiriman = '$statusBaru' WHERE idlogistik = '$id'");
                                        }

                                        if ($updateStatus) {
                                            echo "<script>alert('Status pengiriman berhasil diupdate!'); location='detail_resi.php?tgl=$tgl';</script>";
                                        } else {
                                            echo "<script>alert('Status pengiriman gagal diupdate!'); location='detail_resi.php?tgl=$tgl';</script>";
                                        }
                                    } elseif (isset($_POST['update']) && isset($_POST['selected_ids'])) {
                                        try {
                                            $selectedIDs            = $_POST['selected_ids'];
                                            $resi                   = $_POST['resi'];
                                            $ongkir                 = $_POST['ongkir'];
                                            $invoice                = $_POST['invoice'];
                                            $idadmin                = $_POST['idadmin'];
                                            $ekspedisi              = $_POST['ekspedisi'];
                                            $pengiriman             = $_POST['pengiriman'];
                                            $detail_pengiriman      = $_POST['detail_pengiriman'];
                                            $penerima               = $_POST['penerima'];
                                            $jenis_mitra            = $_POST['jenis_mitra'];
                                            $allSuccess             = true;
                                            
                                            foreach ($selectedIDs as $id) {
                                                $success                    = true;

                                                $noresiVal                  = isset($resi[$id]) ? $resi[$id] : NULL;
                                                $invoiceVal                 = isset($invoice[$id]) ? $invoice[$id] : NULL;
                                                $idadminVal                 = isset($idadmin[$id]) ? $idadmin[$id] : NULL;
                                                $ekspedisiVal               = isset($ekspedisi[$id]) ? $ekspedisi[$id] : NULL;
                                                $pengirimanVal              = isset($pengiriman[$id]) ? $pengiriman[$id] : NULL;
                                                $penerimaVal                = isset($penerima[$id]) ? $penerima[$id] : NULL;
                                                $detail_pengirimanVal       = isset($detail_pengiriman[$id]) ? $detail_pengiriman[$id] : NULL;
                                                $jenis_mitraVal             = isset($jenis_mitra[$id]) ? $jenis_mitra[$id] : NULL;
                                                $rawOngkir                  = $ongkir[$id] ?? '0';
                                                $ongkirVal                  = (int) str_replace(['.', ','], '', $rawOngkir);

                                                if (is_null($idadminVal) || !is_numeric($idadminVal)) {
                                                    echo " idadmin tidak valid untuk ID: $id<br>";
                                                    $success = false;
                                                }

                                                if (is_numeric($detail_pengirimanVal)) {
                                                    $getPO      = $koneksi->query("SELECT namapo, idpoproduk FROM poproduk WHERE idpoproduk =  '$detail_pengirimanVal'");
                                                    $dataPO     = $getPO->fetch_assoc();

                                                    $namapoVal  = $dataPO ? $dataPO['namapo'] : '';
                                                } else {
                                                    $namapoVal  = $detail_pengirimanVal;
                                                }
                                                
                                                $sql            = $koneksi->query("UPDATE logistik3 
                                                                                    SET 
                                                                                        noresi = '$noresiVal', 
                                                                                        biayakirim = '$ongkirVal' 
                                                                                    WHERE idlogistik = '$id'
                                                                                ");

                                                if (!$sql) {
                                                    echo "❌ Gagal update logistik3 untuk ID: $id<br>";
                                                    $success = false;
                                                }

                                                $query          = $koneksi->query("UPDATE t_user 
                                                                                    SET 
                                                                                        resi_pengiriman = '$noresiVal', 
                                                                                        ongkir = '$ongkirVal', 
                                                                                        modified_date = NOW() 
                                                                                    WHERE idlogistik = '$id'
                                                                                ");

                                                if (!$query) {
                                                    echo "❌ Gagal update t_user untuk ID: $id<br>";
                                                    $success = false;
                                                }

                                                // Order konsumen (retail) baru dianggap "Sedang dalam perjalanan" begitu resi-nya
                                                // diisi di sini, bukan pada saat data masuk ke portal ini (waktu itu masih
                                                // "Menunggu Resi"). Invoice mitra tidak akan pernah cocok dengan invoice
                                                // orderkonsumen jadi aman dijalankan untuk semua baris.
                                                if (!empty($noresiVal) && !empty($invoiceVal)) {
                                                    $stmtSyncKonsumen = $koneksi->prepare("UPDATE orderkonsumen SET status = 'Sedang dalam perjalanan' WHERE invoice = ? AND status = 'Menunggu Resi'");
                                                    $stmtSyncKonsumen->bind_param('s', $invoiceVal);
                                                    $stmtSyncKonsumen->execute();
                                                }

                                                if ($jenis_mitraVal === 'WNJ') {
                                                    if (!empty($noresiVal) && $ongkirVal !== '' && $ongkirVal !== null) {
                                                        $transaksiStr = ($pengirimanVal == "PO")
                                                            ? "Ongkir PO #$namapoVal, Penerima #$penerimaVal, No Resi #$noresiVal, Ekspedisi #$ekspedisiVal"
                                                            : "Ongkir Invoice #$invoiceVal, No Resi #$noresiVal, Ekspedisi #$ekspedisiVal";
                                                        
                                                        $checkSaldo         = $koneksi->query("SELECT id_saldo FROM saldo 
                                                                                            WHERE transaksi LIKE '%$transaksiStr%'
                                                                                            AND idadmin = '$idadminVal' 
                                                                                            AND deleted_at IS NULL
                                                                                        ");
    
                                                        if ($checkSaldo->num_rows > 0) {
                                                            $updateSaldo    = $koneksi->query("UPDATE saldo SET credit = '$ongkirVal' 
                                                                                                WHERE transaksi LIKE '%$transaksiStr'
                                                                                                AND idadmin = '$idadminVal'
                                                                            ");
    
                                                            if (!$updateSaldo) {
                                                                echo "❌ Gagal update saldo untuk ID: $id<br>";
                                                                $success    = false;
                                                            }
                                                        } else {
                                                            $insertSaldo    = $koneksi->query("INSERT INTO saldo 
                                                                                                    (`id_saldo`, `idadmin`, `tgl`, `transaksi`, `debit`, `credit`)
                                                                                                VALUES
                                                                                                    (NULL, '$idadminVal', NOW(), '$transaksiStr', 0, '$ongkirVal')
                                                                                            ");
                                                            if (!$insertSaldo) {
                                                                $success    = false;
                                                                echo "❌ Gagal insert saldo untuk ID: $id<br>";
                                                            }
                                                        }
                                                    }
                                                }
                                                if (!$success) {
                                                    $allSuccess = false;
                                                    $failedIDs[] = $id;
                                                }
                                            }
                                            if ($allSuccess) {
                                                echo "<script>
                                                        alert('Data berhasil diupdate!');
                                                        location='detail_resi.php?tgl=$tgl';
                                                    </script>";
                                            } else {
                                                $failedList = implode(', ', $failedIDs);
                                                echo "<script>
                                                        alert('Sebagian data gagal diupdate: $failedList!');
                                                    </script>";
                                            }
                                        } catch (Exception $e) {
                                            echo "Error: " . $e->getMessage();
                                        }
                                    } elseif (isset($_POST['terkirim'])) {
                                        $tgl                = $_GET['tgl'];
                                        $selectedIDs        = $_POST['selected_ids'];
                                        if (isset($selectedIDs)) {
                                            foreach ($selectedIDs as $idlogistik) {
                                                try {
                                                    $status         = 'Terkirim';
                                
                                                    $sql = $koneksi->prepare("UPDATE logistik3 SET status = ? WHERE idlogistik = ?");
                                                    $sql->bind_param("si", $status, $idlogistik);
                                                    $sql->execute();
                                                    if ($sql) {
                                                        echo "
                                                            <script>
                                                                alert('Data berhasil diupdate!');
                                                                location='detail_resi.php?tgl=$tgl';
                                                            </script>
                                                        ";
                                                    } else {
                                                        echo "
                                                            <script>
                                                                alert('Data gagal diupdate!');
                                                                location='detail_resi.php?tgl=$tgl';
                                                            </script>
                                                        ";
                                                    }
                                                } catch (Exception $e) {
                                                    echo "Error: " . $e->getMessage();
                                                }
                                            }
                                        } else {
                                            echo "
                                                <script>
                                                    alert('Tidak ada data yang dipilih untuk diupdate.');
                                                    location='detail_resi.php?tgl=$tgl';
                                                </script>
                                            ";
                                        }
                                    } elseif (isset($_POST['belum_terkirim'])) {
                                        $tgl                = $_GET['tgl'];
                                        $selectedIDs        = $_POST['selected_ids'];
                                        if (isset($selectedIDs)) {
                                            try {
                                                foreach ($selectedIDs as $idlogistik) {
                                                    $status         = NULL;

                                                    $sql = $koneksi->prepare("UPDATE logistik3 SET status = ? WHERE idlogistik = ?");
                                                    $sql->bind_param("si", $status, $idlogistik);
                                                    $sql->execute();
                                                    if ($sql) {
                                                        echo "
                                                            <script>
                                                                alert('Data berhasil diupdate!');
                                                                location='detail_resi.php?tgl=$tgl';
                                                            </script>
                                                        ";
                                                    } else {
                                                        echo "
                                                            <script>
                                                                alert('Data gagal diupdate!');
                                                                location='detail_resi.php?tgl=$tgl';
                                                            </script>
                                                        ";
                                    
                                                    }
                                                }
                                            } catch (Exception $e) {
                                                echo "Error: " . $e->getMessage();
                                            }
                                        } else {
                                            echo "
                                                <script>
                                                    alert('Tidak ada data yang dipilih untuk diupdate.');
                                                    location='detail_resi.php?tgl=$tgl';
                                                </script>
                                            ";
                                        }
                                    } elseif (isset($_POST['print_excel'])) {
                                        echo "<script>
                                                location='print_logistik.php?tgl=$tgl'
                                            </script>";
                                        exit;
                                    }
                                ?>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </section>
                <!-- /.content -->
            </div>

            <?php include 'template/component/footer.php' ?>
        </div>
        <!-- ./wrapper -->

        <?php
            if (isset($_POST['update_keterangan'])) {
                try {
                    $id             = $_POST['idlogistik'];
                    $keterangan     = $_POST['keterangan'];
                    
                    $sql            = $koneksi->query("UPDATE logistik3 SET keterangan = '$keterangan' WHERE idlogistik = '$id'");
                    if ($sql) {
                        $query = $koneksi->query("UPDATE t_user SET keterangan = '$keterangan' WHERE idlogistik = '$id'");
                        echo "
                            <script>
                                alert('Data berhasil diupdate!')
                                location='detail_resi.php?tgl=$tgl'
                            </script>
                        ";
                    } else {
                        echo "
                            <script>
                                alert('Data Gagal diupdate!')
                                location='detail_resi.php?tgl=$tgl'
                            </script>
                        ";
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        ?>

        <!-- jQuery -->
        <script src="template/plugins/jquery/jquery.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="template/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- ChartJS -->
        <script src="template/plugins/chart.js/Chart.min.js"></script>
        <!-- Sparkline -->
        <script src="template/plugins/sparklines/sparkline.js"></script>
        <!-- JQVMap -->
        <script src="template/plugins/jqvmap/jquery.vmap.min.js"></script>
        <script src="template/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="template/plugins/jquery-knob/jquery.knob.min.js"></script>
        <!-- daterangepicker -->
        <script src="template/plugins/moment/moment.min.js"></script>
        <script src="template/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="template/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- Summernote -->
        <script src="template/plugins/summernote/summernote-bs4.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="template/dist/js/adminlte.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="template/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="template/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="template/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="template/plugins/jszip/jszip.min.js"></script>
        <script src="template/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="template/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="template/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="template/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="template/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
        <script>
            $(function () {
                $("#example1").DataTable({
                    "responsive": true, "lengthChange": true, "autoWidth": true,
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                $('#example2').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $("#checkAll").click(function () {
                    $('input[name="selected_ids[]"]').prop('checked', this.checked);
                });
            });
        </script>
    </body>
</html>
