<?php 
    session_start();
    include '../koneksi.php'; 
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
    $vendor         = $_GET['vendor'];
    $queryVendor    = $koneksi->query("SELECT nama_vendor FROM vendor_bill WHERE idvendor = '$vendor'");
    $rowVendor      = $queryVendor->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $role ?> | Wanoja</title>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Bill</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>
</head>

</head> 
<body>
    <!-- NAVBAR -->
    <? include "../assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <a href="../index" class="btn btn-info float-right"><i class="fa fa-arrow-left"></i> Kembali</a>
        <h2>Pilih Vendor</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_vendor">Nama Vendor</label>
                <input type="text" placeholder="Masukan Nama Vendor Baru.." class="form-control" name="nama_vendor" id="nama_vendor" required>
            </div>
            <button type="submit" name="kirim_vendor" class="btn btn-primary">Submit</button>
        </form>
        <?php
            if (isset($_POST['kirim_vendor'])) {
                $namaVendor     = $_POST['nama_vendor'];
                
                $query_insert   = $koneksi->query("INSERT INTO vendor_bill 
                                                        (idvendor, nama_vendor, created_at, updated_at, deleted_at)
                                                    VALUES
                                                        (NULL, '$namaVendor', NOW(), NULL, NULL)");
                if ($query_insert) {
                    echo "<script>alert('Data vendor: $namaVendor, telah ditambahkan!');</script>";
                    echo "<script>location='bill.php'</script>";
                } else {
                    echo "<script>alert('Gagal menambahkan vendor baru!');</script>";
                    echo "<script>location='bill.php'</script>";
                }
            }
        ?>
        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Vendor</th>
                            <th>Sisa Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $data   = $koneksi->query("SELECT 
                                                            * 
                                                        FROM vendor_bill vb
                                                        LEFT JOIN saldo_per_tipe spt ON spt.tipe = vb.idvendor
                                                    ");
                            $no     = 1;
                            while($row = $data->fetch_assoc()) {
                                $totalTagihan += $row['sisasaldo'];
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['nama_vendor'] ?></td>
                                <td><?= number_format($row['sisasaldo']) ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2">Total :</td>
                            <td><?= number_format($totalTagihan); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <form action="bill.php" method="GET" class="mt-3">
            <div class="form-group">
                <label>Nama Vendor</label>
                <select class="form-control" name="vendor" id="vendor">
                    <option disabled selected>- Pilih Nama Vendor -</option>
                    <?php
                        $query_vendor = $koneksi->query("SELECT * FROM vendor_bill"); 
                        while ($row = $query_vendor->fetch_assoc()) {                 
                    ?>
                    <option value="<?= $row['idvendor']; ?>"><?= $row['nama_vendor']; ?></option>
                    <?php } ?>  
                </select>      
            </div>
        </form>
        <br>
        <div class="mb-5">
            <?php if (isset($vendor)) :?>
                <!-- SALDO AKHIR -->
                <h4>Nama Vendor : <?= $rowVendor['nama_vendor']; ?></h4>
                <div id="saldoContainer" class="row mt-3">
                    <div class="text-center text-dark bg-warning rounded border border-dark">
                        <p class="pt-3">Sisa Tagihan</p>
                        <?php
                            $querySaldo = $koneksi->query("SELECT sisasaldo FROM saldo_per_tipe WHERE tipe = '$vendor'");
                            $rowSaldo   = $querySaldo->fetch_assoc();
                        ?>
                        <h2 class="pb-3" id="saldoValue">Rp. <?= number_format($rowSaldo['sisasaldo']) ?></h2>
                    </div>
                </div>
                <div class="container mt-3">
                    <button class="btn btn-success" data-toggle="modal" data-target="#modalForm2"><i class="fa fa-plus"></i> Bayar</button>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#modalForm" ><i class="fa fa-plus"></i> Tagihan</button>
                    <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class="fa fa-file-export"></i> Export</button> -->
                </div>
                <br>
                <!-- SALDO END -->
                <table class="table table-striped table-bordered mt-3" id="billTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Tagihan</th>
                            <th>Bayar</th>
                            <th class="text-center">
                                <select name="skill_dropdown" id="skill_dropdown">
                                    <option value="View">View</option>
                                    <option value="Edit">Edit</option>
                                    <option value="Delete">Delete</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $queryBill      = $koneksi->query("SELECT * FROM bill WHERE vendor = '$vendor' AND deleted_at IS NULL ORDER BY idbill DESC");
                            while ($row = $queryBill->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $row['tgl']; ?></td>
                            <td><?= $row['desc']; ?></td>
                            <td class="text-danger"><?= number_format($row['tagihan']); ?></td>
                            <td class="text-success"><?= number_format($row['bayar']); ?></td>
                            <td>
                                <div class="View skill">
                                    <button class="btn btn-info" data-toggle="modal" data-target="#modalView<?= $row['idbill'];?>"><i class="fa fa-eye"></i></button>
                                </div>
                                <div class="Edit skill" style="display: none">
                                    <button class="btn btn-warning" data-toggle="modal" data-target="#modalEdit<?= $row['idbill']; ?>"><i class="fa fa-edit"></i></button>
                                </div>
                                <form method="POST">
                                    <div class="Delete skill" style="display: none">
                                        <input type="hidden" value="<?= $row['idbill']; ?>" name="idbill" id="idbill" readonly>
                                        <button class="btn btn-danger" type="submit" name="hapus" onclick="return confirm('Yakin Akan Hapus Data?');"><i class="fa fa-trash"></i></button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal VIEW -->
                        <div class="modal fade" id="modalView<?= $row['idbill'];?>" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="labelModalKu"><i class="fa fa-eye"></i> View</h4>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                            <span class="sr-only">Tutup</span>
                                        </button>
                                    </div>
                                    <!-- Modal Body -->
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <p class="statusMsg"></p>
                                            <center>
                                                <div class="card" style="width: 18rem;">
                                                    <?php if($row['buktitf']==""){ ?>
                                                        <img src="..." class="card-img-top" alt="Foto Tidak Ada">
                                                    <?php } else { ?>
                                                        <img src="../buktitransfer/<?= $row['buktitf'];?>" class="card-img-top" alt="Bukti Transfer">
                                                    <?php } ?>
                                                </div>       
                                            </center>
                                        </div>
                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal EDIT -->
                        <div class="modal fade" id="modalEdit<?= $row['idbill']; ?>" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="labelModalKu"><i class="fa fa-edit"></i> Ubah</h4>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                            <span class="sr-only">Tutup</span>
                                        </button>
                                    </div>
                                    <!-- Modal Body -->
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <p class="statusMsg"></p>
                                            <input type="hidden" name="idbill" value="<?= $row['idbill'];?>"/>    
                                            <div class="form-group">
                                                <label for="tgl">Tanggal</label>
                                                <input type="date" class="form-control" id="tgl" name="tgl" value="<?= $row['tgl'];?>" required/>
                                            </div>
                                            <div class="form-group">
                                                <label for="keterangan">Keterangan</label>
                                                <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" value="<?= $row['desc'];?>" required/>
                                            </div>
                                            <div class="form-group">
                                                <label for="bayar">Pembayaran</label>
                                                <input type="number" min="0" class="form-control" id="bayar" name="bayar" placeholder="Masukkan Total Pembayaran" value="<?= $row['bayar'];?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="tagihan">Tagihan</label>
                                                <input type="number" min="0" class="form-control" id="tagihan" name="tagihan" placeholder="Masukkan Total Tagihan" value="<?= $row['tagihan'];?>"/>
                                            </div>
                                        </div>
                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
                                            <button type="submit" class="btn btn-primary" name="pembayaran">KIRIM</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </tbody>
                </table>
                <!-- Modal Bayar -->
                <div class="modal fade" id="modalForm2" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu"><i class="fa fa-plus"></i> Bayar</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                            <!-- Modal Body -->
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <p class="statusMsg"></p>
                                    <input type="hidden" value="<?= $vendor ?>" name="vendor">
                                    <div class="form-group">
                                        <label for="tgl">Tanggal</label>
                                        <input type="date" class="form-control" id="tgl" name="tgl" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="bayar">Jumlah</label>
                                        <input type="number" min="0" class="form-control" id="bayar" name="bayar" placeholder="Masukkan Total Pembayaran" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="foto">Nota</label>
                                        <br>
                                        <input type="file" name="foto" id="foto" required />
                                    </div>
                                </div>
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
                                    <button type="submit" class="btn btn-primary" name="pembayaran">KIRIM</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal Tagihan -->
                <div class="modal fade" id="modalForm" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu"><i class="fa fa-minus"></i> Tagihan</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                            <!-- Modal Body -->
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <p class="statusMsg"></p>
                                    <input type="hidden" value="<?= $vendor ?>" name="vendor">
                                    <div class="form-group">
                                        <label for="tgl">Tanggal</label>
                                        <input type="date" class="form-control" id="tgl" name="tgl" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="tagihan">Jumlah</label>
                                        <input type="number" min="0" class="form-control" id="tagihan" name="tagihan" placeholder="Masukkan Total Tagihan" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="foto">Nota</label>
                                        <br>
                                        <input type="file" name="foto" id="foto" required />
                                    </div>
                                </div>
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button>
                                    <button type="submit" class="btn btn-primary" name="pembayaran">KIRIM</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById('vendor').addEventListener('change', function() {
            let vendorID = this.value;
            if (vendorID) {
                window.location.href = "bill.php?vendor=" + vendorID;
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#billTable').DataTable({
                ordering: false
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#skill_dropdown").change(function () {
                var inputVal = $(this).val();
                var eleBox = $("." + inputVal);
                $(".skill").hide();
                $(eleBox).show();
            });
        });
    </script>

    <!-- STORE KREDIT -->
    <?php 
        if (isset($_POST["pembayaran"])) {
            date_default_timezone_set('Asia/Jakarta');
            $keterangan     = addslashes(htmlspecialchars($_POST["keterangan"]));
            $waktu          = date('H:i:s');
            $tanggal        = $_POST["tgl"];
            $vendor         = $_POST["vendor"];
            $bayar          = $_POST["bayar"];
            $tagihan        = $_POST["tagihan"];
            $idBill         = $_POST["idbill"];
            // Move the uploaded file
            if (isset($idBill)) {
                $vendor     = $_GET['vendor'];
                $updateBill = $koneksi->query("UPDATE bill SET tgl = '$tanggal', `desc` = '$keterangan', bayar = '$bayar', tagihan = '$tagihan' WHERE idbill = '$idBill'");
                
                if ($updateBill) {
                    echo "<script>alert('Update ($keterangan) berhasil ditambahkan!');</script>";
                    echo "<script>location='bill.php?vendor=$vendor'</script>";
                } else {
                    echo "Error: " . $koneksi->error;
                }
            } else {
                $foto           = $_FILES['foto']['name'];
                $tmp            = $_FILES['foto']['tmp_name'];
                $ukuranFile     = $_FILES['foto']['size'];
                $errorFile      = $_FILES['foto']['error'];
                // Debugging information
                echo "Error code: " . $errorFile . "<br>";
                echo "File size: " . $ukuranFile . "<br>";
                echo "Temp file location: " . $tmp . "<br>";

                if ($errorFile !== UPLOAD_ERR_OK) {
                    echo "<script>alert('Gagal mengupload file dengan error code: $errorFile');</script>";
                    exit;
                }

                
                // Cek apakah yang diupload adalah gambar
                $ekstensiGambarValid    = ['jpg', 'jpeg', 'png', 'svg'];
                $ekstensiGambar         = explode('.', $foto);
                $ekstensiGambar         = strtolower(end($ekstensiGambar));
                
                if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                    echo "<script>alert('Yang anda upload bukan gambar');</script>";
                    echo "<script>location='bill.php</script>";
                    exit;
                }
                
                // Optional: Cek ukuran file
                $ukuranMaksimal = 2 * 1024 * 1024; // 2MB
                if ($ukuranFile > $ukuranMaksimal) {
                    echo "<script>alert('Ukuran file terlalu besar');</script>";
                    echo "<script>location='bill.php'</script>";
                    exit;
                }
                
                $today          = date("His"); 
                $tglsekarang    = date("ymd");
                $namaFileBaru   = 'P' . $vendor . $tglsekarang . $today . '.' . $ekstensiGambar;

                // Ensure directory exists and is writable
                $targetDir = '../buktitransfer/';
                if (!is_dir($targetDir) || !is_writable($targetDir)) {
                    echo "<script>alert('Directory tujuan tidak ada atau tidak bisa ditulisi');</script>";
                    exit;
                }
                if (move_uploaded_file($tmp, $targetDir . $namaFileBaru)) {
                    if ($bayar) {
                        $insert = $koneksi->query("INSERT INTO `bill` (`idbill`,`tgl`,`vendor`,`desc`,`tagihan`,`bayar`,`buktitf`,`created_at`,`updated_at`,`deleted_at`)
                                                    VALUES (NULL, '$tanggal' ,'$vendor', '$keterangan', 0, '$bayar', '$namaFileBaru', NOW(), NULL, NULL)
                                            ");
                        if ($insert) {
                            echo "<script>alert('Pembayaran ($keterangan) berhasil ditambahkan!');</script>";
                            echo "<script>location='bill.php?vendor=$vendor'</script>";
                        } else {
                            echo "Error: " . $koneksi->error;
                        }
                    } elseif ($tagihan) {
                        $insert = $koneksi->query("INSERT INTO `bill` (`idbill`,`tgl`,`vendor`,`desc`,`tagihan`,`bayar`,`buktitf`,`created_at`,`updated_at`,`deleted_at`)
                                                    VALUES (NULL, '$tanggal' ,'$vendor', '$keterangan', '$tagihan', 0, '$namaFileBaru', NOW(), NULL, NULL)
                                            ");
                        if ($insert) {
                            echo "<script>alert('Tagihan ($keterangan) berhasil ditambahkan!');</script>";
                            echo "<script>location='bill.php?vendor=$vendor'</script>";
                        } else {
                            echo "Error: " . $koneksi->error;
                        }
                    }
                } else {
                    // Error logging
                    error_log('Failed to move uploaded file from ' . $tmp . ' to ' . $targetDir . $namaFileBaru);
                    echo "<script>alert('Gagal mengupload file');</script>";
                }
            }
        }
        if (isset($_POST['hapus'])) {
            date_default_timezone_set('Asia/Jakarta');
            $idBill = $_POST['idbill'];

            $queryDelete = $koneksi->query("UPDATE bill SET deleted_at = NOW() WHERE idbill = '$idBill'");
            if ($queryDelete) {
                echo "<script>alert('Data berhasil dihapus!');</script>";
                echo "<script>location='bill.php?vendor=$vendor'</script>";
            } else {
                echo "<script>alert('Data Gagal dihapus!');</script>";
                echo "<script>location='bill.php?vendor=$vendor'</script>";
            }
        } 
    ?>
    <!-- MAIN CONTENT END -->
    <!-- FOOTER -->
    <? include "../assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->

    <!-- SCRIPT -->
    <!-- END SCRIPT -->
</body>
</html>