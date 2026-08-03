<?php 
    session_start();
    include 'koneksi.php'; 
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
    $tanggal        = $_GET['tanggal'];
    $createdDate    = $_GET['created_date'];
    $total = 0;

    $totalQuery = $koneksi->query("SELECT rekening, SUM(nominal) AS total FROM catatan WHERE tanggal = '$tanggal' AND created_at = '$createdDate' GROUP BY rekening");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $role ?> | Wanoja</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>

</head> 
<body>
    <!-- NAVBAR -->
    <?php include "assets/components/Navbar/navbar.php"; ?>
    <!-- NAVBAR END -->

    <!-- MAIN CONTENT -->
    <div class="container mt-5">
        <!-- TABLE -->
        <div class="card p-3">
            <div class="col mt-2">
                <h2 class="text-center text-dark">Pemasukan <?= $tanggal; ?></h2>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal-kredit">
                        <i class="bi bi-plus"></i>
                        Pemasukan
                    </button>
                    <!-- <a href="excel-pemasukan.php?tanggal=<?= $tanggal; ?>" class="btn btn-success btn-sm"><i class="fa fa-download"></i></a> -->
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#excelModal">
                        <i class="fa fa-download"></i>
                    </button>
                    <a href="pemasukan.php" class="btn btn-info btn-sm float-right"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rekening</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $query = $koneksi->query("SELECT * FROM catatan WHERE tanggal = '$tanggal' AND created_at = '$createdDate' ORDER BY id DESC");
                                while ($data = $query->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['rekening'] ?></td>
                                    <td>Rp. <?= number_format($data['nominal']) ?></td>
                                    <td><?= $data['tanggal'] ?></td>
                                    <td><?= $data['waktu'] ?></td>
                                    <td>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" class="form-control form-control-sm" value="<?= $data['id'] ?>" name="id">
                                            <button type="submit" class="btn btn-danger btn-sm" name="delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModal_<?= $data['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal_<?= $data['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Ubah Data</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <input type="hidden" class="form-control form-control-sm" value="<?= $data['id'] ?>" name="id" readonly>
                                                            <div class="form-group">
                                                                <select class="form-control form-control-sm" name="rekening">
                                                                    <option value="<?= $data['rekening'] ?>"><?= $data['rekening'] ?></option>
                                                                    <?php
                                                                        $sql = $koneksi->query("SELECT * FROM rekeningwnj WHERE status = 'A' ORDER BY namabank");
                                                                        while ($data1 = $sql->fetch_assoc()) {
                                                                    ?>
                                                                        <option value="<?= $data1['namabank'] ?>"><?= $data1['namabank'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="number" class="form-control form-control-sm" value="<?= $data['nominal'] ?>" name="nominal">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success btn-sm" name="ubah">Ubah Data</button>
                                                        </div>
                                                    </form>
    
                                                    <?php
                                                        if (isset($_POST['ubah'])) {
                                                            try {
                                                                date_default_timezone_set('Asia/Jakarta');
                                                                $waktu      = date('H:i:s');
                                                                $nominal    = $_POST['nominal'];
                                                                $rekening   = $_POST['rekening'];
                                                                $idcatatan  = $_POST['id'];
    
                                                                $sql = $koneksi->query("UPDATE catatan SET nominal = '$nominal', rekening = '$rekening', waktu = '$waktu' WHERE id = '$idcatatan'");
                                                                if ($sql) {
                                                                    echo "
                                                                        <script>
                                                                            alert('Data berhasil diubah!');
                                                                            location='detail_pemasukan.php?tanggal=$tanggal&created_date=$createdDate';
                                                                        </script>
                                                                    ";
                                                                } else {
                                                                    echo "
                                                                        <script>
                                                                            alert('Data gagal diubah!');
                                                                            location='detail_pemasukan.php?tanggal=$tanggal&created_date=$createdDate';
                                                                        </script>
                                                                    ";
                                                                }
                                                            } catch (Exception $e) {
                                                                echo "Error: " . $e->getMessage();
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            if (isset($_POST['delete'])) {
                                                try {
                                                    $idcatatan = $_POST['id'];
    
                                                    $query = $koneksi->query("DELETE FROM catatan WHERE id = '$idcatatan'");
                                                    if ($query) {
                                                        $check = $koneksi->query("SELECT * FROM catatan WHERE tanggal = '$tanggal'");
                                                        $jumlah = $check->num_rows;
    
                                                        echo "<script>alert('Data berhasil dihapus!'); </script>";
                                                        if ($jumlah > 0) {
                                                            echo "<script>location='detail_pemasukan.php?tanggal=$tanggal&created_date=$createdDate';</script>";
                                                        } else {
                                                            echo "<script>location='catatan.php';</script>";
                                                        }
                                                    } else {
                                                        echo "
                                                            <script>
                                                                alert('Data gagal dihapus!');
                                                                location='detail_pemasukan.php?tanggal=$tanggal&created_date=$createdDate';
                                                            </script>
                                                        ";
                                                    }
                                                } catch(Exception $e) {
                                                    echo "Error: " . $e->getMessage();
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php 
                                    $total += $data['nominal'];
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th colspan="4">Rp. <?= number_format($total) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <hr>
                <div class="table-responsive mt-3">
                    <h3 class="text-center text-dark">Summary <?= $tanggal; ?></h3>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Rekening</th>
                                <th>Total Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($rekap = $totalQuery->fetch_assoc()): ?>
                            <tr>
                                <td><?= $rekap['rekening']; ?></td>
                                <td>Rp. <?= number_format($rekap['total']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>Rp. <?= number_format($total) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- TABLE END -->
        <!-- Modal Pemasukan -->
        <div class="modal fade" id="exampleModal-kredit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Pemasukan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formPemasukan">
                        <div class="modal-body">
                            <input type="hidden" id="tanggal" value="<?= $tanggal; ?>" required>

                            <div class="form-group">
                                <label for="rekening" class="mb-0">Rekening <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" id="rekening" required>
                                    <option disabled selected>~ Default Selected ~</option>
                                    <?php
                                        $query = $koneksi->query("SELECT * FROM rekeningwnj WHERE status = 'A' ORDER BY namabank");
                                        while ($data = $query->fetch_assoc()) {
                                    ?>
                                        <option value="<?= $data['namabank'] ?>"><?= $data['namabank'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="nominal" class="form-label mb-0">Nominal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="nominal" placeholder="Rp. " min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="keterangan" class="form-label mb-0">Keterangan <span class="text-danger">*</span></label>
                                <textarea class="form-control form-control-sm" id="keterangan"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-start">
                            <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
        <!-- Modal Excel -->
        <div class="modal fade" id="excelModal" tabindex="-1" role="dialog" aria-labelledby="excelModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="excelModalLabel">Download Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="excelDownloadForm" method="GET">
                        <div class="modal-body">
                            <p>Pilih Bulan & Tahun untuk mendownload laporan pemasukan:</p>

                            <div class="form-inline mt-2">
                                <select class="form-control mr-2" name="bulan" required>
                                    <option value="">Pilih Bulan</option>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i ?>"><?= date('F', mktime(0, 0, 0, $i, 10)) ?></option>
                                    <?php endfor; ?>
                                </select>

                                <select class="form-control" name="tahun" required>
                                    <option value="">Pilih Tahun</option>
                                    <?php
                                    $currentYear = date('Y');
                                    for ($y = $currentYear; $y >= $currentYear - 10; $y--): ?>
                                        <option value="<?= $y ?>"><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Download</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
    <!-- MAIN CONTENT END -->
    <br><br><br><br>

    <!-- FOOTER -->
    <?php include "assets/components/Footer/footer.php"; ?>
    <!-- FOOTER END -->
    <!-- SCRIPT -->
    <script>
        document.getElementById("formPemasukan").addEventListener("submit", function(e) {
            e.preventDefault();

            const tanggal = document.getElementById("tanggal").value;
            const rekening = document.getElementById("rekening").value;
            const nominal = document.getElementById("nominal").value;
            const keterangan = document.getElementById("keterangan").value;

            const createdDate = new Date().toISOString().slice(0,10);

            fetch("https://wnj.id/manajemen/api/insert-pemasukan.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    tanggal: tanggal,
                    rekening: rekening,
                    nominal: nominal,
                    keterangan: keterangan
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.href = "https://wnj.id/manajemen/detail_pemasukan.php?tanggal=" + tanggal + "&created_date=" + createdDate;
            })
            .catch(error => {
                alert("Terjadi kesalahan: " + error);
                console.error(error);
            });
        });
    </script>
    <!-- JavaScript untuk handle pilihan -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('excelDownloadForm');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const bulan = form.querySelector('select[name="bulan"]').value;
                const tahun = form.querySelector('select[name="tahun"]').value;

                if (!bulan || !tahun) {
                    alert('Mohon pilih bulan dan tahun terlebih dahulu.');
                    return;
                }

                const url = `excel-pemasukan.php?bulan=${bulan}&tahun=${tahun}`;
                window.location.href = url;
            });
        });
    </script>

    <!-- END SCRIPT -->
</body>
</html>